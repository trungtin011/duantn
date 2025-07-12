<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Combo;
use App\Models\ComboProduct;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ComboController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Check if seller has a shop
        $shop = $user->seller->shops->first();
        if (!$shop) {
            return redirect()->back()->with('error', 'Bạn chưa có shop nào để quản lý combo.');
        }

        $query = Combo::where('shopID', $shop->id)->with('products.product', 'products.variant');

        if ($request->filled('search')) {
            $query->where('combo_name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $combos = $query->latest()->paginate(10);

        return view('seller.combo.index', compact('combos'));
    }

    public function create()
    {
        $shop = Auth::user()->seller->shops->first();
        if (!$shop) {
            return redirect()->route('seller.dashboard')->with('error', 'Bạn chưa có shop nào để quản lý combo.');
        }
        $products = Product::where('shopID', $shop->id)->where('status', 'active')->with('variants')->get();

        return view('seller.combo.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'combo_description' => 'nullable|string',
            'total_price' => 'required|numeric|min:0',
            'discount_value' => 'nullable|numeric|min:0',
            'discount_type' => 'nullable|in:percentage,fixed',
            'quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'products' => 'required|array|min:2',
            'products.*.productID' => 'required|exists:products,id',
            'products.*.variantID' => 'nullable|exists:product_variants,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        Log::info('Store Combo Request Data:', [
            'input' => $request->all(),
            'combo_description' => $request->combo_description,
            'discount_value' => $request->discount_value,
            'image' => $request->hasFile('image') ? 'Image uploaded' : 'No image',
        ]);

        $shop = Auth::user()->seller->shops->first();
        if (!$shop) {
            return redirect()->route('seller.dashboard')->with('error', 'Bạn chưa có shop nào để quản lý combo.');
        }
        $shopID = $shop->id;

        // Calculate base price
        $basePrice = 0;
        foreach ($request->products as $product) {
            $productData = Product::find($product['productID']);
            $price = $productData->sale_price ?? $productData->price;
            if ($product['variantID']) {
                $variant = ProductVariant::find($product['variantID']);
                $price = $variant->sale_price ?? $variant->price ?? $price;
            }
            $basePrice += $price * $product['quantity'];
        }

        // Calculate total price with discount
        $discountValue = $request->discount_value ?? 0;
        $discountType = $request->discount_type ?? null;
        $totalPrice = $basePrice;
        if ($discountType === 'percentage' && $discountValue > 0) {
            $totalPrice -= ($basePrice * $discountValue) / 100;
        } elseif ($discountType === 'fixed' && $discountValue > 0) {
            $totalPrice -= $discountValue;
        }
        $totalPrice = max(0, $totalPrice);

        Log::info('Combo Calculated Values:', [
            'basePrice' => $basePrice,
            'discount_value' => $discountValue,
            'discountType' => $discountType,
            'totalPrice' => $totalPrice,
        ]);

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('combo_images', 'public');
        }

        // Create combo
        $comboData = [
            'shopID' => $shopID,
            'combo_name' => $request->name,
            'slug' => \Str::slug($request->name),
            'combo_description' => $request->combo_description,
            'image' => $imagePath,
            'total_price' => $totalPrice,
            'discount_value' => $discountValue,
            'discount_type' => $discountType,
            'quantity' => $request->quantity,
            'status' => 'active',
        ];

        Log::info('Combo Data to Save:', $comboData);

        $combo = Combo::create($comboData);

        Log::info('Saved Combo:', $combo->toArray());

        // Add products/variants to combo
        foreach ($request->products as $product) {
            if ($product['variantID']) {
                $variant = ProductVariant::where('id', $product['variantID'])
                    ->where('productID', $product['productID'])
                    ->first();
                if (!$variant) {
                    return redirect()->back()->withErrors(['products' => 'Biến thể không hợp lệ cho sản phẩm: ' . $product['productID']]);
                }
            }

            ComboProduct::create([
                'comboID' => $combo->id,
                'productID' => $product['productID'],
                'variantID' => $product['variantID'] ?? null,
                'quantity' => $product['quantity'],
            ]);
        }

        return redirect()->route('seller.combo.index')->with('success', 'Combo created successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'combo_description' => 'nullable|string',
            'total_price' => 'required|numeric|min:0',
            'discount_value' => 'nullable|numeric|min:0',
            'discount_type' => 'nullable|in:percentage,fixed',
            'quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'products' => 'required|array|min:2',
            'products.*.productID' => 'required|exists:products,id',
            'products.*.variantID' => 'nullable|exists:product_variants,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        Log::info('Update Combo Request Data:', [
            'input' => $request->all(),
            'combo_description' => $request->combo_description,
            'discount_value' => $request->discount_value,
            'image' => $request->hasFile('image') ? 'Image uploaded' : 'No image',
        ]);

        $shop = Auth::user()->seller->shops->first();
        if (!$shop) {
            return redirect()->route('seller.dashboard')->with('error', 'Bạn chưa có shop nào để quản lý combo.');
        }
        $shopID = $shop->id;
        $combo = Combo::where('id', $id)->where('shopID', $shopID)->firstOrFail();

        // Calculate base price
        $basePrice = 0;
        foreach ($request->products as $product) {
            $productData = Product::find($product['productID']);
            $price = $productData->sale_price ?? $productData->price;
            if ($product['variantID']) {
                $variant = ProductVariant::find($product['variantID']);
                $price = $variant->sale_price ?? $variant->price ?? $price;
            }
            $basePrice += $price * $product['quantity'];
        }

        // Calculate total price with discount
        $discountValue = $request->discount_value ?? 0;
        $discountType = $request->discount_type ?? null;
        $totalPrice = $basePrice;
        if ($discountType === 'percentage' && $discountValue > 0) {
            $totalPrice -= ($basePrice * $discountValue) / 100;
        } elseif ($discountType === 'fixed' && $discountValue > 0) {
            $totalPrice -= $discountValue;
        }
        $totalPrice = max(0, $totalPrice);

        Log::info('Combo Calculated Values:', [
            'basePrice' => $basePrice,
            'discount_value' => $discountValue,
            'discountType' => $discountType,
            'totalPrice' => $totalPrice,
        ]);

        // Handle image upload
        $imagePath = $combo->image;
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('image')->store('combo_images', 'public');
        }

        // Update combo
        $comboData = [
            'combo_name' => $request->name,
            'slug' => \Str::slug($request->name),
            'combo_description' => $request->combo_description,
            'image' => $imagePath,
            'total_price' => $totalPrice,
            'discount_value' => $discountValue,
            'discount_type' => $discountType,
            'quantity' => $request->quantity,
        ];

        Log::info('Combo Data to Update:', $comboData);

        $combo->update($comboData);

        Log::info('Updated Combo:', $combo->toArray());

        // Delete old products/variants
        ComboProduct::where('comboID', $combo->id)->delete();

        // Add new products/variants
        foreach ($request->products as $product) {
            if ($product['variantID']) {
                $variant = ProductVariant::where('id', $product['variantID'])
                    ->where('productID', $product['productID'])
                    ->first();
                if (!$variant) {
                    return redirect()->back()->withErrors(['products' => 'Biến thể không hợp lệ cho sản phẩm: ' . $product['productID']]);
                }
            }

            ComboProduct::create([
                'comboID' => $combo->id,
                'productID' => $product['productID'],
                'variantID' => $product['variantID'] ?? null,
                'quantity' => $product['quantity'],
            ]);
        }

        return redirect()->route('seller.combo.index')->with('success', 'Combo updated successfully.');
    }

    public function edit($id)
    {
        $shop = Auth::user()->seller->shops->first();
        if (!$shop) {
            return redirect()->route('seller.dashboard')->with('error', 'Bạn chưa có shop nào để quản lý combo.');
        }
        $shopID = $shop->id;
        $combo = Combo::where('id', $id)->where('shopID', $shopID)->with('products.product', 'products.variant')->firstOrFail();
        $products = Product::where('shopID', $shopID)->where('status', 'active')->with('variants')->get();

        return view('seller.combo.edit', compact('combo', 'products'));
    }

    public function destroy($id)
    {
        $shop = Auth::user()->seller->shops->first();
        if (!$shop) {
            return redirect()->route('seller.dashboard')->with('error', 'Bạn chưa có shop nào để quản lý combo.');
        }
        $shopID = $shop->id;
        $combo = Combo::where('id', $id)->where('shopID', $shopID)->firstOrFail();

        // Delete image if exists
        if ($combo->image && Storage::disk('public')->exists($combo->image)) {
            Storage::disk('public')->delete($combo->image);
        }

        // Delete related products/variants
        ComboProduct::where('comboID', $combo->id)->delete();

        // Delete combo
        $combo->delete();

        return redirect()->route('seller.combo.index')->with('success', 'Combo deleted successfully.');
    }
}