<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Combo;
use App\Models\ComboProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComboController extends Controller
{
    public function index()
    {
        $shopID = Auth::user()->seller->shops->first()->id;
        $combos = Combo::where('shopID', $shopID)->with('products.product')->get();

        return view('seller.combo.index', compact('combos'));
    }

    public function create()
    {
        $shopID = Auth::user()->seller->shops->first()->id;
        $products = Product::where('shopID', $shopID)->where('status', 'active')->get();

        return view('seller.combo.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'combo_name' => 'required|string|max:100',
            'combo_description' => 'nullable|string',
            'total_price' => 'required|numeric|min:0',
            'discount_value' => 'nullable|numeric|min:0',
            'discount_type' => 'nullable|in:percentage,fixed',
            'products' => 'required|array|min:2', // Ít nhất 2 sản phẩm
            'products.*.productID' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        $shopID = Auth::user()->seller->shops->first()->id;

        // Tạo combo
        $combo = Combo::create([
            'shopID' => $shopID,
            'combo_name' => $request->combo_name,
            'combo_description' => $request->combo_description,
            'total_price' => $request->total_price,
            'discount_value' => $request->discount_value ?? 0,
            'discount_type' => $request->discount_type,
            'status' => 'active',
        ]);

        // Thêm sản phẩm vào combo
        foreach ($request->products as $product) {
            ComboProduct::create([
                'comboID' => $combo->id,
                'productID' => $product['productID'],
                'quantity' => $product['quantity'],
            ]);
        }

        return redirect()->route('seller.combo.index')->with('success', 'Combo created successfully.');
    }

    public function edit($id)
    {
        $shopID = Auth::user()->seller->shops->first()->id;
        $combo = Combo::where('shopID', $shopID)->with('products.product')->findOrFail($id);
        $products = Product::where('shopID', $shopID)->where('status', 'active')->get();

        return view('seller.combo.edit', compact('combo', 'products'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'combo_name' => 'required|string|max:100',
            'combo_description' => 'nullable|string',
            'total_price' => 'required|numeric|min:0',
            'discount_value' => 'nullable|numeric|min:0',
            'discount_type' => 'nullable|in:percentage,fixed',
            'products' => 'required|array|min:2',
            'products.*.productID' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        $shopID = Auth::user()->seller->shops->first()->id;
        $combo = Combo::where('shopID', $shopID)->findOrFail($id);

        // Cập nhật combo
        $combo->update([
            'combo_name' => $request->combo_name,
            'combo_description' => $request->combo_description,
            'total_price' => $request->total_price,
            'discount_value' => $request->discount_value ?? 0,
            'discount_type' => $request->discount_type,
        ]);

        // Xóa sản phẩm cũ
        ComboProduct::where('comboID', $combo->id)->delete();

        // Thêm sản phẩm mới
        foreach ($request->products as $product) {
            ComboProduct::create([
                'comboID' => $combo->id,
                'productID' => $product['productID'],
                'quantity' => $product['quantity'],
            ]);
        }

        return redirect()->route('seller.combo.index')->with('success', 'Combo updated successfully.');
    }

     public function destroy($id)
    {
        $shopID = Auth::user()->seller->shops->first()->id;
        $combo = Combo::where('shopID', $shopID)->findOrFail($id);

        // Xóa các sản phẩm liên quan trong ComboProduct
        ComboProduct::where('comboID', $combo->id)->delete();

        // Xóa combo
        $combo->delete();

        return redirect()->route('seller.combo.index')->with('success', 'Combo deleted successfully.');
    }
}



