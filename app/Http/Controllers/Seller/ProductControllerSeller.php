<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductDimension;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\ProductVariantAttributeValue; // Thêm model mới
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;

class ProductControllerSeller extends Controller
{
    /**
     * Hiển thị danh sách sản phẩm
     */
    public function index(Request $request)
    {
        $query = Product::with(['variants', 'images']);

        if ($request->filled('search')) {
            $query->where('name', 'LIKE', '%' . $request->search . '%')
                ->orWhere('sku', 'LIKE', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $products = $query->paginate(5);

        return view('seller.products.index', compact('products'));
    }

    /**
     * Hiển thị form thêm sản phẩm đơn giản
     */
    public function create()
    {
        $categories = Category::where('status', 'active')->get();
        $brands = Brand::where('status', 'active')->get();
        return view('seller.products.create', compact('categories', 'brands'));
    }

    /**
     * Lưu sản phẩm đơn giản
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Validation
            $rules = [
                'name' => 'required|string|max:100',
                'description' => 'nullable|string',
                'brand' => 'required|string|max:100',
                'category' => 'required|string|max:100',
                'sku' => 'required_if:product_type,simple_product|nullable|string|max:100|unique:products,sku',
                'price' => 'required_if:product_type,simple_product|nullable|numeric|min:0',
                'purchase_price' => 'required_if:product_type,simple_product|nullable|numeric|min:0',
                'sale_price' => 'required_if:product_type,simple_product|nullable|numeric|min:0',
                'stock_total' => 'required_if:product_type,simple_product|nullable|integer|min:0',
                'meta_title' => 'nullable|string|max:255',
                'meta_description' => 'nullable|string|max:320',
                'meta_keywords' => 'nullable|string|max:255',
                'attributes' => 'nullable|array',
                'variants' => 'nullable|array',
                'variants.*.name' => 'required|string',
                'variants.*.price' => 'nullable|numeric|min:0',
                'variants.*.purchase_price' => 'nullable|numeric|min:0',
                'variants.*.sale_price' => 'nullable|numeric|min:0',
                'variants.*.stock_total' => 'nullable|integer|min:0',
                'variants.*.sku' => 'nullable|string|max:100',
                'variants.*.length' => 'nullable|numeric|min:0',
                'variants.*.width' => 'nullable|numeric|min:0',
                'variants.*.height' => 'nullable|numeric|min:0',
                'variants.*.weight' => 'nullable|numeric|min:0',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            ];

            $request->validate($rules);

            Log::info('Dữ liệu request đã validate:', $request->all());

            $metaKeywords = $request->meta_keywords ?: Str::slug($request->name);

            $shop = \App\Models\Shop::where('ownerID', auth()->id())->firstOrFail();
            $shopID = $shop->id;

            // Tạo sku mặc định cho sản phẩm nếu là variable_product và sku không được cung cấp
            $sku = $request->sku ?? (Carbon::now()->format('YmdHis') . '-' . Str::random(5));

            $product = Product::create([
                'shopID' => $shopID,
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description ?? '',
                'brand' => $request->brand,
                'category' => $request->category,
                'sku' => $sku,
                'price' => $request->price ?? 0,
                'purchase_price' => $request->purchase_price ?? 0,
                'sale_price' => $request->sale_price ?? 0,
                'stock_total' => $request->stock_total ?? 0,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'meta_keywords' => $metaKeywords,
                'is_featured' => $request->has('is_featured') ? 1 : 0,
                'is_variant' => $request->filled('variants') ? 1 : 0,
                'status' => $request->save_draft ? 'draft' : 'active',
                'sold_quantity' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            ProductDimension::create([
                'productID' => $product->id,
                'variantID' => null,
                'length' => $request->length ?? 0,
                'width' => $request->width ?? 0,
                'height' => $request->height ?? 0,
                'weight' => $request->weight ?? 0,
            ]);

            if ($request->filled('variants')) {
                foreach ($request->variants as $variantData) {
                    DB::beginTransaction();
                    try {
                        $price = is_numeric($variantData['price']) ? (float)$variantData['price'] : 0;
                        $purchasePrice = is_numeric($variantData['purchase_price']) ? (float)$variantData['purchase_price'] : 0;
                        $salePrice = is_numeric($variantData['sale_price']) ? (float)$variantData['sale_price'] : 0;
                        $stockTotal = is_numeric($variantData['stock_total']) ? (int)$variantData['stock_total'] : 0;

                        Log::info('Dữ liệu biến thể sau xử lý:', [
                            'name' => $variantData['name'] ?? '',
                            'price' => $price,
                            'purchase_price' => $purchasePrice,
                            'sale_price' => $salePrice,
                            'stock_total' => $stockTotal,
                            'sku' => $variantData['sku'] ?? '',
                        ]);

                        $variant = ProductVariant::create([
                            'productID' => $product->id,
                            'variant_name' => $variantData['name'] ?? '',
                            'price' => $price,
                            'purchase_price' => $purchasePrice,
                            'sale_price' => $salePrice,
                            'stock' => $stockTotal,
                            'sku' => $variantData['sku'] ?? Str::random(10), // Tạo sku mặc định cho biến thể nếu không có
                            'status' => 'active',
                        ]);

                        ProductDimension::create([
                            'productID' => $product->id,
                            'variantID' => $variant->id,
                            'length' => $variantData['length'] ?? 0,
                            'width' => $variantData['width'] ?? 0,
                            'height' => $variantData['height'] ?? 0,
                            'weight' => $variantData['weight'] ?? 0,
                        ]);

                        DB::commit();
                    } catch (\Exception $e) {
                        DB::rollBack();
                        Log::error('Lỗi tạo biến thể: ' . $e->getMessage(), ['variant' => $variantData]);
                        throw $e;
                    }
                }
            }

            $displayOrder = 0;
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $path = $image->store('product_images', 'public');
                    ProductImage::create([
                        'productID' => $product->id,
                        'variantID' => null,
                        'image_path' => $path,
                        'is_default' => ($index === 0) ? 1 : 0,
                        'display_order' => $displayOrder++,
                        'alt_text' => "{$product->name} - Image {$index}",
                        'created_at' => now(),
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('seller.products.index')->with('success', 'Sản phẩm đã được tạo thành công vào ' . now()->format('H:i:s d/m/Y'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi khi thêm sản phẩm: ' . $e->getMessage(), ['request' => $request->all()]);
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi thêm sản phẩm: ' . $e->getMessage())->withInput();
        }
    }

    public function uploadImage(Request $request)
    {
        try {
            if ($request->hasFile('file')) {
                $image = $request->file('file');
                $path = $image->store('destination_images', 'public');
                $url = Storage::url($path); // Sửa tại đây
                return response()->json([
                    'location' => $url
                ]);
            }
            return response()->json(['error' => 'Không có file nào được upload'], 400);
        } catch (\Exception $e) {
            Log::error('Lỗi upload hình ảnh: ' . $e->getMessage());
            return response()->json(['error' => 'Có lỗi xảy ra khi upload hình ảnh'], 500);
        }
    }

    /**
     * Hiển thị form chỉnh sửa sản phẩm
     */
    public function edit($id)
    {
        try {
            $product = Product::with(['variants', 'images', 'dimensions'])->findOrFail($id);
            $categories = Category::where('status', 'active')->get();
            $brands = Brand::where('status', 'active')->get();

            // Ghi log để kiểm tra dữ liệu
            Log::info('Product Edit Data', [
                'product_id' => $id,
                'product' => $product->toArray(),
                'categories_count' => $categories->count(),
                'brands_count' => $brands->count(),
            ]);

            return view('seller.products.edit', compact('product', 'categories', 'brands'));
        } catch (\Exception $e) {
            Log::error('Error in Product Edit', [
                'product_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->with('error', 'Không thể tải sản phẩm để chỉnh sửa.');
        }
    }

    /**
     * Cập nhật sản phẩm
     */
    public function update(Request $request, $id)
    {
        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            // Log dữ liệu request để kiểm tra
            \Illuminate\Support\Facades\Log::info('Product Update Request', [
                'product_id' => $id,
                'request_data' => $request->all(),
                'files' => $request->hasFile('images') ? array_map(fn($file) => $file->getClientOriginalName(), $request->file('images')) : [],
            ]);

            // Quy tắc xác thực
            $rules = [
                'name' => 'required|string|max:100',
                'description' => 'nullable|string',
                'brand' => 'required|string|max:100',
                'sub_brand' => 'nullable|string|max:100',
                'category' => 'required|string|max:100',
                'sub_category' => 'nullable|string|max:100',
                'product_type' => 'required|in:simple_product,variable_product',
                'sku' => ['required_if:product_type,simple_product', 'string', 'max:100', \Illuminate\Validation\Rule::unique('products', 'sku')->ignore($id)],
                'price' => 'required_if:product_type,simple_product|numeric|min:0.01',
                'purchase_price' => 'required_if:product_type,simple_product|numeric|min:0.01',
                'sale_price' => 'required_if:product_type,simple_product|numeric|min:0.01',
                'stock_total' => 'required_if:product_type,simple_product|integer|min:0',
                'length' => 'nullable|numeric|min:0',
                'width' => 'nullable|numeric|min:0',
                'height' => 'nullable|numeric|min:0',
                'weight' => 'nullable|numeric|min:0',
                'meta_title' => 'nullable|string|max:255',
                'meta_description' => 'nullable|string|max:320',
                'meta_keywords' => 'nullable|string|max:255',
                'attributes' => 'nullable|array',
                'variants' => 'required_if:product_type,variable_product|array',
                'variants.*.name' => 'required_if:product_type,variable_product|string|max:100',
                'variants.*.price' => 'required_if:product_type,variable_product|numeric|min:0.01',
                'variants.*.purchase_price' => 'required_if:product_type,variable_product|numeric|min:0.01',
                'variants.*.sale_price' => 'required_if:product_type,variable_product|numeric|min:0.01',
                'variants.*.stock_total' => 'required_if:product_type,variable_product|integer|min:0',
                'variants.*.sku' => 'required_if:product_type,variable_product|string|max:100',
                'variants.*.length' => 'nullable|numeric|min:0',
                'variants.*.width' => 'nullable|numeric|min:0',
                'variants.*.height' => 'nullable|numeric|min:0',
                'variants.*.weight' => 'nullable|numeric|min:0',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
                'variant_images.*.*' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            ];

            $request->validate($rules);

            \Illuminate\Support\Facades\Log::info('Validation Passed', ['product_id' => $id]);

            // Xử lý meta_keywords
            $metaKeywords = $request->meta_keywords ?: \Illuminate\Support\Facades\Str::slug($request->name);

            // Tìm sản phẩm
            $product = \App\Models\Product::findOrFail($id);

            // Cập nhật sản phẩm
            $product->update([
                'name' => $request->name,
                'slug' => \Illuminate\Support\Facades\Str::slug($request->name),
                'description' => $request->description,
                'brand' => $request->brand,
                'sub_brand' => $request->sub_brand,
                'category' => $request->category,
                'sub_category' => $request->sub_category,
                'sku' => $request->product_type === 'simple_product' ? $request->sku : null,
                'price' => $request->product_type === 'simple_product' ? $request->price : null,
                'purchase_price' => $request->product_type === 'simple_product' ? $request->purchase_price : null,
                'sale_price' => $request->product_type === 'simple_product' ? $request->sale_price : null,
                'stock_total' => $request->product_type === 'simple_product' ? $request->stock_total : null,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'meta_keywords' => $metaKeywords,
                'is_featured' => $request->has('is_featured') ? 1 : 0,
                'is_variant' => $request->product_type === 'variable_product' ? 1 : 0,
                'status' => $request->save_draft ? 'draft' : 'active',
            ]);

            \Illuminate\Support\Facades\Log::info('Product Updated', ['product_id' => $id, 'product' => $product->toArray()]);

            // Xử lý thuộc tính
            \App\Models\ProductVariantAttributeValue::where('product_id', $product->id)->delete();
            if ($request->filled('attributes')) {
                foreach ($request->attributes as $attributeInput) {
                    $attribute = \App\Models\Attribute::firstOrCreate(['name' => $attributeInput['name']]);
                    foreach (explode(',', $attributeInput['values']) as $value) {
                        $attributeValue = \App\Models\AttributeValue::firstOrCreate([
                            'attribute_id' => $attribute->id,
                            'value' => trim($value),
                        ]);
                        \App\Models\ProductVariantAttributeValue::create([
                            'product_id' => $product->id,
                            'attribute_id' => $attribute->id,
                            'attribute_value_id' => $attributeValue->id,
                        ]);
                    }
                }
            }

            // Xử lý kích thước sản phẩm
            \App\Models\ProductDimension::where('productID', $product->id)->whereNull('variantID')->delete();
            if ($request->product_type === 'simple_product') {
                \App\Models\ProductDimension::create([
                    'productID' => $product->id,
                    'variantID' => null,
                    'length' => $request->length ?? 0,
                    'width' => $request->width ?? 0,
                    'height' => $request->height ?? 0,
                    'weight' => $request->weight ?? 0,
                    'shipping_cost' => $request->shipping_cost ?? 0,
                ]);
            }

            // Xử lý biến thể
            \App\Models\ProductVariant::where('productID', $product->id)->delete();
            if ($request->filled('variants') && $request->product_type === 'variable_product') {
                foreach ($request->variants as $index => $variantData) {
                    $variant = \App\Models\ProductVariant::create([
                        'productID' => $product->id,
                        'variant_name' => $variantData['name'],
                        'price' => $variantData['price'] ?? 0,
                        'purchase_price' => $variantData['purchase_price'] ?? 0,
                        'sale_price' => $variantData['sale_price'] ?? 0,
                        'stock' => $variantData['stock_total'] ?? 0,
                        'sku' => $variantData['sku'] ?? \Illuminate\Support\Facades\Str::random(8),
                        'status' => 'active',
                    ]);

                    // Cập nhật kích thước biến thể
                    \App\Models\ProductDimension::create([
                        'productID' => $product->id,
                        'variantID' => $variant->id,
                        'length' => $variantData['length'] ?? 0,
                        'width' => $variantData['width'] ?? 0,
                        'height' => $variantData['height'] ?? 0,
                        'weight' => $variantData['weight'] ?? 0,
                        'shipping_cost' => $variantData['shipping_cost'] ?? 0,
                    ]);

                    // Cập nhật ảnh biến thể
                    if ($request->hasFile("variant_images.{$index}")) {
                        foreach ($request->file("variant_images.{$index}") as $image) {
                            $path = $image->store('product_images', 'public');
                            \App\Models\ProductImage::create([
                                'productID' => $product->id,
                                'variantID' => $variant->id,
                                'image_path' => $path,
                                'is_default' => 0,
                                'display_order' => 0,
                                'alt_text' => "{$variant->variant_name} - Image",
                            ]);
                        }
                    }
                }
            }

            // Cập nhật ảnh sản phẩm
            if ($request->hasFile('images')) {
                \App\Models\ProductImage::where('productID', $product->id)->whereNull('variantID')->delete();
                $displayOrder = 0;
                foreach ($request->file('images') as $index => $image) {
                    $path = $image->store('product_images', 'public');
                    \App\Models\ProductImage::create([
                        'productID' => $product->id,
                        'variantID' => null,
                        'image_path' => $path,
                        'is_default' => ($index === 0) ? 1 : 0,
                        'display_order' => $displayOrder++,
                        'alt_text' => "{$product->name} - Image {$index}",
                    ]);
                }
            }

            \Illuminate\Support\Facades\Log::info('Product Update Completed', ['product_id' => $id]);

            \Illuminate\Support\Facades\DB::commit();
            return redirect()->route('seller.products.index')->with('success', 'Sản phẩm đã được cập nhật thành công.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Validation Error in Product Update', [
                'product_id' => $id,
                'errors' => $e->errors(),
            ]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Error in Product Update', [
                'product_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Xóa sản phẩm (soft delete)
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $product = Product::findOrFail($id);

            // Xóa các liên kết
            ProductVariant::where('productID', $product->id)->delete();
            ProductImage::where('productID', $product->id)->delete();
            ProductDimension::where('productID', $product->id)->delete();

            // Xóa sản phẩm
            $product->delete();

            DB::commit();
            return redirect()->route('seller.products.index')->with('success', 'Sản phẩm đã được xóa thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi xóa sản phẩm ID ' . $id . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Hiển thị chi tiết sản phẩm
     */
    public function show($id)
    {
        $product = Product::with(['variants', 'images', 'dimensions'])->findOrFail($id);
        return view('seller.products.show', compact('product'));
    }

    public function simple()
    {
        return view('product', ['type' => 'simple']);
    }

    public function variable()
    {
        return view('product', ['type' => 'variable']);
    }
}
