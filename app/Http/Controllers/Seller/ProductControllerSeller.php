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
        $attributes = Attribute::all();
        $categories = Category::where('status', 'active')->get();
        $brands = Brand::where('status', 'active')->get();
        return view('seller.products.create', compact('categories', 'brands', 'attributes'));
    }

    /**
     * Lưu sản phẩm đơn giản
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $rules = [
                'name' => 'required|string|max:100',
                'description' => 'nullable|string',
                'brand' => 'required|string|max:100',
                'category' => 'required|string|max:100',
                'sku' => 'required|string|max:100|unique:products,sku',
                'price' => 'required|numeric|min:0',
                'purchase_price' => 'required|numeric|min:0',
                'sale_price' => 'required|numeric|min:0',
                'stock_total' => 'required|integer|min:0',
                'meta_title' => 'nullable|string|max:255',
                'meta_description' => 'nullable|string|max:320',
                'meta_keywords' => 'nullable|string|max:255',
                'attributes' => 'nullable|array',
                'variants' => 'nullable|array',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            ];

            $request->validate($rules);

            $metaKeywords = $request->meta_keywords ?: Str::slug($request->name);

            // Lấy shopID từ người dùng hiện tại
            $shop = \App\Models\Shop::where('ownerID', auth()->id())->firstOrFail();
            $shopID = $shop->id;

            // Lưu sản phẩm chính
            $product = Product::create([
                'shopID' => $shopID, // Thêm shopID
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description ?: '',
                'brand' => $request->brand,
                'category' => $request->category,
                'sku' => $request->sku,
                'price' => $request->price,
                'purchase_price' => $request->purchase_price,
                'sale_price' => $request->sale_price,
                'stock_total' => $request->stock_total,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'meta_keywords' => $metaKeywords,
                'is_featured' => $request->has('is_featured') ? 1 : 0,
                'is_variant' => $request->filled('variants') ? 1 : 0,
                'status' => $request->save_draft ? 'draft' : 'active',
                'sold_quantity' => 0,
                'created_at' => now(),
            ]);

            // Lưu kích thước
            ProductDimension::create([
                'productID' => $product->id,
                'variantID' => null,
                'length' => $request->length ?? 0,
                'width' => $request->width ?? 0,
                'height' => $request->height ?? 0,
                'weight' => $request->weight ?? 0,
            ]);

            // Lưu thuộc tính
            if ($request->filled('attributes')) {
                foreach ($request->attributes as $attributeInput) {
                    $attribute = Attribute::firstOrCreate(['name' => $attributeInput['name']]);
                    $values = explode(',', $attributeInput['values']);
                    foreach ($values as $value) {
                        $value = trim($value);
                        if ($value) {
                            AttributeValue::firstOrCreate([
                                'attribute_id' => $attribute->id,
                                'value' => $value,
                            ]);
                        }
                    }
                }
            }

            // Lưu biến thể và ảnh trong các giao dịch riêng biệt
            if ($request->filled('variants')) {
                foreach ($request->variants as $variantData) {
                    DB::beginTransaction(); // Bắt đầu giao dịch cho từng biến thể
                    try {
                        $variant = ProductVariant::create([
                            'productID' => $product->id,
                            'variant_name' => $variantData['name'],
                            'price' => $variantData['price'],
                            'purchase_price' => $variantData['purchase_price'],
                            'sale_price' => $variantData['sale_price'],
                            'stock' => $variantData['stock_total'],
                            'sku' => $variantData['sku'],
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

                        if (isset($variantData['attributes'])) {
                            foreach ($variantData['attributes'] as $attrData) {
                                $attribute = Attribute::firstOrCreate(['name' => $attrData['attribute_name']]);
                                $attributeValue = AttributeValue::firstOrCreate([
                                    'attribute_id' => $attribute->id,
                                    'value' => $attrData['value'],
                                ]);
                                ProductVariantAttributeValue::create([
                                    'product_variant_id' => $variant->id,
                                    'attribute_value_id' => $attributeValue->id,
                                ]);
                            }
                        }

                        if ($request->hasFile("variant_images.{$variantData['index']}")) {
                            foreach ($request->file("variant_images.{$variantData['index']}") as $image) {
                                $path = $image->store('product_images', 'public');
                                ProductImage::create([
                                    'productID' => $product->id,
                                    'variantID' => $variant->id,
                                    'image_path' => $path,
                                    'is_default' => 0,
                                    'display_order' => 0,
                                    'alt_text' => "{$variant->variant_name} - Image",
                                    'created_at' => now(),
                                ]);
                            }
                        }

                        DB::commit(); // Hoàn thành giao dịch cho biến thể
                    } catch (\Exception $e) {
                        DB::rollBack();
                        throw $e; // Ném lỗi để xử lý ở giao dịch ngoài
                    }
                }
            }

            // Lưu ảnh sản phẩm chính
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
            Log::error('Lỗi khi thêm sản phẩm: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi thêm sản phẩm: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Hiển thị form thêm biến thể cho sản phẩm
     */
    public function createVariant($productId)
    {
        $product = Product::findOrFail($productId);
        $attributes = Attribute::all();
        return view('seller.products.create-variant', compact('product', 'attributes'));
    }

    /**
     * Lưu biến thể cho sản phẩm
     */
    public function storeVariant(Request $request, $productId)
    {
        try {
            $product = Product::findOrFail($productId);

            // Kiểm tra xem sản phẩm có thuộc shop của seller không
            $shop = \App\Models\Shop::where('ownerID', Auth::user()->id)->firstOrFail();
            if ($product->shopID !== $shop->id) {
                throw new \Exception('Bạn không có quyền chỉnh sửa sản phẩm này.');
            }

            $rules = [
                'variants.*.name' => 'required|string|max:100',
                'variants.*.price' => 'required|numeric|min:0',
                'variants.*.purchase_price' => 'required|numeric|min:0',
                'variants.*.sale_price' => 'required|numeric|min:0',
                'variants.*.stock' => 'required|integer|min:0',
                'variants.*.sku' => 'required|string|max:100|unique:product_variants,sku',
                'variants.*.length' => 'required|numeric|min:0',
                'variants.*.width' => 'nullable|numeric|min:0',
                'variants.*.height' => 'nullable|numeric|min:0',
                'variants.*.weight' => 'nullable|numeric|min:0',
                'variants.*.image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
                'variants.*.attributes.*.attribute_name' => 'required|string|max:100',
                'variants.*.attributes.*.value' => 'required|string|max:100',
            ];

            $request->validate($rules);

            $product->update(['is_variant' => 1]);

            foreach ($request->variants as $variantData) {
                $variant = ProductVariant::create([
                    'productID' => $product->id,
                    'variant_name' => $variantData['name'],
                    'price' => $variantData['price'],
                    'purchase_price' => $variantData['purchase_price'],
                    'sale_price' => $variantData['sale_price'],
                    'stock' => $variantData['stock'],
                    'sku' => $variantData['sku'],
                    'status' => 'active',
                ]);

                // Lưu kích thước
                ProductDimension::create([
                    'productID' => $product->id,
                    'variantID' => $variant->id,
                    'length' => $variantData['length'],
                    'width' => $variantData['width'] ?? 0,
                    'height' => $variantData['height'] ?? 0,
                    'weight' => $variantData['weight'] ?? 0,
                ]);

                // Lưu ảnh
                if ($request->hasFile("variants.{$variantData['index']}.image")) {
                    $path = $request->file("variants.{$variantData['index']}.image")->store('product_images', 'public');
                    ProductImage::create([
                        'productID' => $product->id,
                        'variantID' => $variant->id,
                        'image_path' => $path,
                        'is_default' => 1,
                        'display_order' => 0,
                        'alt_text' => $variantData['name'] . ' - Variant Image',
                        'created_at' => now(),
                    ]);
                }

                if (isset($variantData['attributes'])) {
                    foreach ($variantData['attributes'] as $attrData) {
                        $attribute = Attribute::firstOrCreate(['name' => $attrData['attribute_name']]);
                        Log::info('Created/Found Attribute: ' . json_encode($attribute));

                        $attributeValue = AttributeValue::firstOrCreate([
                            'attribute_id' => $attribute->id,
                            'value' => $attrData['value'],
                        ]);
                        Log::info('Created/Found AttributeValue: ' . json_encode($attributeValue));

                        ProductVariantAttributeValue::create([
                            'product_variant_id' => $variant->id,
                            'attribute_value_id' => $attributeValue->id,
                        ]);
                        Log::info('Created ProductVariantAttributeValue for variant_id: ' . $variant->id);
                    }
                }
            }

            return redirect()->route('seller.products.index')->with('success', 'Biến thể đã được thêm thành công vào ' . now()->format('H:i:s d/m/Y'));
        } catch (\Exception $e) {
            Log::error('Lỗi khi thêm biến thể: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi thêm biến thể: ' . $e->getMessage())->withInput();
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
        $attributes = Attribute::all();
        $categories = Category::where('status', 'active')->get();
        $brands = Brand::where('status', 'active')->get();
        return view('seller.products.create', compact('categories', 'brands', 'attributes'));
    }

    /**
     * Cập nhật sản phẩm
     */
    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction(); // Đảm bảo dữ liệu nhất quán

            // Xác thực dữ liệu
            $rules = [
                'name' => 'required|string|max:100',
                'description' => 'nullable|string',
                'brand' => 'required|string|max:100',
                'category' => 'required|string|max:100',
                'sku' => 'required|string|max:100|unique:products,sku',
                'price' => 'required|numeric|min:0',
                'purchase_price' => 'required|numeric|min:0',
                'sale_price' => 'required|numeric|min:0',
                'stock_total' => 'required|integer|min:0',
                'meta_title' => 'nullable|string|max:255',
                'meta_description' => 'nullable|string|max:320',
                'meta_keywords' => 'nullable|string|max:255',
                'attributes' => 'nullable|array',
                'variants' => 'nullable|array',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            ];

            $request->validate($rules);

            // Xử lý `meta_keywords` nếu trống
            $metaKeywords = $request->meta_keywords ?: Str::slug($request->name);

            // Tìm sản phẩm cần cập nhật
            $product = Product::findOrFail($id);

            // Cập nhật thông tin sản phẩm
            $product->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description,
                'brand' => $request->brand,
                'category' => $request->category,
                'sku' => $request->sku,
                'price' => $request->price,
                'purchase_price' => $request->purchase_price,
                'sale_price' => $request->sale_price,
                'stock_total' => $request->stock_total,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'meta_keywords' => $metaKeywords,
                'is_featured' => $request->has('is_featured') ? 1 : 0,
                'is_variant' => $request->filled('variants') ? 1 : 0,
                'status' => $request->save_draft ? 'draft' : 'active',
            ]);

            // Cập nhật thuộc tính & giá trị
            ProductVariantAttributeValue::where('product_id', $product->id)->delete(); // Xóa dữ liệu cũ
            if ($request->filled('attributes')) {
                foreach ($request->attributes as $attributeInput) {
                    $attribute = Attribute::firstOrCreate(['name' => $attributeInput['name']]);

                    foreach (explode(',', $attributeInput['values']) as $value) {
                        $attributeValue = AttributeValue::firstOrCreate([
                            'attribute_id' => $attribute->id,
                            'value' => trim($value),
                        ]);

                        ProductVariantAttributeValue::create([
                            'product_id' => $product->id,
                            'attribute_id' => $attribute->id,
                            'attribute_value_id' => $attributeValue->id,
                        ]);
                    }
                }
            }

            // Cập nhật kích thước sản phẩm
            ProductDimension::where('productID', $product->id)->delete();
            ProductDimension::create([
                'productID' => $product->id,
                'variantID' => null,
                'length' => $request->length ?? 0,
                'width' => $request->width ?? 0,
                'height' => $request->height ?? 0,
                'weight' => $request->weight ?? 0,
                'shipping_cost' => $request->shipping_cost ?? 0,
            ]);

            // Cập nhật biến thể sản phẩm
            ProductVariant::where('productID', $product->id)->delete(); // Xóa biến thể cũ
            if ($request->filled('variants')) {
                foreach ($request->variants as $variantData) {
                    $variant = ProductVariant::create([
                        'productID' => $product->id,
                        'variant_name' => $variantData['name'],
                        'price' => $variantData['price'],
                        'purchase_price' => $variantData['purchase_price'],
                        'sale_price' => $variantData['sale_price'],
                        'stock' => $variantData['stock_total'],
                        'sku' => $variantData['sku'],
                        'status' => 'active',
                    ]);

                    // Cập nhật kích thước biến thể
                    ProductDimension::create([
                        'productID' => $product->id,
                        'variantID' => $variant->id,
                        'length' => $variantData['length'] ?? 0,
                        'width' => $variantData['width'] ?? 0,
                        'height' => $variantData['height'] ?? 0,
                        'weight' => $variantData['weight'] ?? 0,
                        'shipping_cost' => $variantData['shipping_cost'] ?? 0,
                    ]);
                }
            }

            // Cập nhật ảnh sản phẩm
            ProductImage::where('productID', $product->id)->delete(); // Xóa ảnh cũ
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
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('seller.products.index')->with('success', 'Sản phẩm đã được cập nhật thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
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

            // Lấy tất cả các variant_id của sản phẩm
            $variantIds = ProductVariant::where('productID', $product->id)->pluck('id');

            // Xóa các liên kết
            ProductVariant::where('productID', $product->id)->delete();
            ProductImage::where('productID', $product->id)->delete();
            ProductVariantAttributeValue::whereIn('product_variant_id', $variantIds)->delete(); // Sửa tại đây
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

    /**
     * Lấy danh sách thương hiệu phụ
     */
    public function getSubBrands(Request $request)
    {
        try {
            $brand = $request->query('brand');
            if ($brand) {
                $subBrands = Brand::where('name', $brand)->first()?->subBrands()->where('status', 'active')->get(['name']);
                return response()->json($subBrands ?? []);
            }
            return response()->json([]);
        } catch (\Exception $e) {
            Log::error('Lỗi khi lấy sub-brands: ' . $e->getMessage());
            return response()->json(['error' => 'Không thể lấy danh sách thương hiệu phụ'], 500);
        }
    }

    /**
     * Lấy danh sách danh mục phụ
     */
    public function getSubCategories(Request $request)
    {
        try {
            $category = $request->query('category');
            if ($category) {
                $subCategories = Category::where('name', $category)->first()?->subCategories()->where('status', 'active')->get(['name']);
                return response()->json($subCategories ?? []);
            }
            return response()->json([]);
        } catch (\Exception $e) {
            Log::error('Lỗi khi lấy sub-categories: ' . $e->getMessage());
            return response()->json(['error' => 'Không thể lấy danh sách danh mục phụ'], 500);
        }
    }

    public function getAttributes()
    {
        $attributes = Attribute::all()->pluck('name');
        return response()->json($attributes);
    }

    public function getAttributeValues(Request $request)
    {
        $attributeName = $request->query('attribute');
        if ($attributeName) {
            $values = AttributeValue::whereHas('attribute', function ($query) use ($attributeName) {
                $query->where('name', $attributeName);
            })->pluck('value')->toArray();

            return response()->json($values);
        }
        return response()->json([], 200);
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
