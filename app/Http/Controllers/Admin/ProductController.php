<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductDimension;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\ProductAttribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Hiển thị danh sách sản phẩm
     */
    public function index(Request $request)
    {
        $query = Product::with(['variants', 'images']);

        // Tìm kiếm theo tên hoặc SKU
        if ($request->filled('search')) {
            $query->where('name', 'LIKE', '%' . $request->search . '%')
                ->orWhere('sku', 'LIKE', '%' . $request->search . '%');
        }

        // Lọc theo trạng thái nếu có chọn
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $products = $query->paginate(5);

        return view('admin.products.index', compact('products'));
    }


    /**
     * Hiển thị form thêm sản phẩm đơn giản
     */
    public function create()
    {
        $categories = Category::where('status', 'active')->get();
        $brands = Brand::where('status', 'active')->get();
        return view('admin.products.create', compact('categories', 'brands'));
    }

    /**
     * Lưu sản phẩm đơn giản
     */
    public function store(Request $request)
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

            // Lưu sản phẩm chính
            $product = Product::create([
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
            ]);

            // Lưu thuộc tính vào `product_attributes`
            if ($request->filled('attributes')) {
                foreach ($request->attributes as $attributeInput) {
                    $attribute = Attribute::firstOrCreate(['name' => $attributeInput['name']]); // Chỉ dùng 'name'

                    foreach (explode(',', $attributeInput['values']) as $value) {
                        $attributeValue = AttributeValue::firstOrCreate([
                            'attribute_id' => $attribute->id,
                            'value' => trim($value),
                        ]);

                        ProductAttribute::create([
                            'product_id' => $product->id,
                            'attribute_id' => $attribute->id,
                            'attribute_value_id' => $attributeValue->id,
                        ]);
                    }
                }
            }

            // Lưu biến thể sản phẩm & kích thước vào `product_dimensions`
            $variants = [];
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
                    $variants[] = $variant;

                    // Lưu kích thước của biến thể vào `product_dimensions`
                    ProductDimension::create([
                        'productID' => $product->id,
                        'variantID' => $variant->id,
                        'length' => $request->length ?? 0,
                        'width' => $request->width ?? 0,
                        'height' => $request->height ?? 0,
                        'weight' => $request->weight ?? 0,
                    ]);
                }
            } else {
                // Lưu kích thước cho sản phẩm chính (không có biến thể)
                ProductDimension::create([
                    'productID' => $product->id,
                    'variantID' => null,
                    'length' => $request->length ?? 0,
                    'width' => $request->width ?? 0,
                    'height' => $request->height ?? 0,
                    'weight' => $request->weight ?? 0,
                ]);
            }

            // Lưu ảnh sản phẩm chính & biến thể vào `product_images`
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

            // Lưu ảnh biến thể vào `product_images`
            foreach ($variants as $variantIndex => $variant) {
                if ($request->hasFile("variant_images.{$variantIndex}")) {
                    foreach ($request->file("variant_images.{$variantIndex}") as $image) {
                        $path = $image->store('product_images', 'public');
                        ProductImage::create([
                            'productID' => $product->id,
                            'variantID' => $variant->id,
                            'image_path' => $path,
                            'is_default' => 0,
                            'display_order' => $displayOrder++,
                            'alt_text' => "{$variant->variant_name} - Image",
                        ]);
                    }
                }
            }

            DB::commit(); // Hoàn thành transaction

            return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được tạo thành công.');
        } catch (\Exception $e) {
            DB::rollBack(); // Khôi phục nếu có lỗi
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
        $variants = ProductVariant::all();
        return view('admin.products.create-variant', compact('product', 'attributes', 'variants'));
    }

    /**
     * Lưu biến thể cho sản phẩm
     */
    public function storeVariant(Request $request, $productId)
    {
        try {
            $product = Product::findOrFail($productId);

            $rules = [
                'attributes.*.name' => 'required|string|max:100',
                'attribute_values.*.attribute_name' => 'required|string|max:100',
                'attribute_values.*.value' => 'required|string|max:100',
                'attribute_values.*.product_variant_id' => 'required|integer',
                'variants.*.name' => 'required|string|max:100',
                'variants.*.price' => 'required|numeric|min:0',
                'variants.*.purchase_price' => 'required|numeric|min:0',
                'variants.*.sale_price' => 'required|numeric|min:0',
                'variants.*.stock' => 'required|integer|min:0',
                'variants.*.sku' => 'required|string|max:100|unique:product_variants,sku',
                'variants.*.discount_type' => 'required|in:no_discount,fixed,percent',
                'variants.*.length' => 'required|numeric|min:0',
                'variants.*.width' => 'nullable|numeric|min:0',
                'variants.*.height' => 'nullable|numeric|min:0',
                'variants.*.weight' => 'nullable|numeric|min:0',
                'variants.*.shipping_cost' => 'nullable|numeric|min:0',
                'variants.*.attributes.*.attribute_name' => 'required|string|max:100',
                'variants.*.attributes.*.value' => 'required|string|max:100',
                'variants.*.image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            ];

            $request->validate($rules);

            // Cập nhật product_type thành variant
            $product->update(['product_type' => 'variant']);

            // Tạo hoặc lấy thuộc tính từ tên, không sử dụng product_id
            $attributeMap = [];
            if ($request->has('attributes')) {
                foreach ($request->attributes as $attributeData) {
                    $attribute = Attribute::firstOrCreate(['name' => $attributeData['name']]);
                    $attributeMap[$attributeData['name']] = $attribute->id;
                }
            }

            // Lưu variants và lưu trữ ID theo index
            $variantIds = [];
            foreach ($request->variants as $index => $variantData) {
                $variant = ProductVariant::create([
                    'productID' => $product->id,
                    'variant_name' => $variantData['name'],
                    'price' => $variantData['price'],
                    'purchase_price' => $variantData['purchase_price'],
                    'sale_price' => $variantData['sale_price'],
                    'stock' => $variantData['stock'],
                    'sku' => $variantData['sku'],
                    'status' => 'active',
                    'discount_type' => $variantData['discount_type'],
                ]);

                // Lưu hình ảnh biến thể
                if ($request->hasFile("variants.$index.image")) {
                    $path = $request->file("variants.$index.image")->store('variant_images', 'public');
                    ProductImage::create([
                        'productID' => $product->id,
                        'variantID' => $variant->id,
                        'image_path' => $path,
                        'is_default' => 0,
                        'display_order' => 0,
                        'alt_text' => $variantData['name'] . ' - Variant Image',
                    ]);
                }

                // Lưu kích thước biến thể
                ProductDimension::create([
                    'productID' => $product->id,
                    'variantID' => $variant->id,
                    'length' => $variantData['length'],
                    'width' => $variantData['width'] ?? 0,
                    'height' => $variantData['height'] ?? 0,
                    'weight' => $variantData['weight'] ?? 0,
                    'shipping_cost' => $variantData['shipping_cost'] ?? 0,
                ]);

                // Lưu trữ ID của biến thể theo index
                $variantIds[$index] = $variant->id;

                // Lưu attributes của biến thể
                if (isset($variantData['attributes'])) {
                    foreach ($variantData['attributes'] as $attrData) {
                        $attribute = Attribute::firstOrCreate(['name' => $attrData['attribute_name']]);
                        $attributeMap[$attrData['attribute_name']] = $attribute->id;

                        AttributeValue::create([
                            'attribute_id' => $attribute->id,
                            'value' => $attrData['value'],
                            'product_variant_id' => $variant->id,
                            'product_id' => $product->id,
                        ]);
                    }
                }
            }

            // Lưu attribute values
            if ($request->has('attribute_values')) {
                foreach ($request->attribute_values as $valueData) {
                    $variantIndex = $valueData['product_variant_id'];
                    $variantId = $variantIds[$variantIndex] ?? null;

                    if ($variantId) {
                        $attribute = Attribute::firstOrCreate(['name' => $valueData['attribute_name']]);
                        $attributeMap[$valueData['attribute_name']] = $attribute->id;

                        AttributeValue::create([
                            'attribute_id' => $attribute->id,
                            'value' => $valueData['value'],
                            'product_variant_id' => $variantId,
                            'product_id' => $product->id,
                        ]);
                    }
                }
            }

            return redirect()->route('admin.products.index')->with('success', 'Biến thể đã được thêm thành công.');
        } catch (\Exception $e) {
            Log::error('Lỗi khi thêm biến thể: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi thêm biến thể: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Hiển thị form chỉnh sửa sản phẩm
     */
    public function edit($id)
    {
        $product = Product::with(['variants', 'images', 'dimensions', 'attributes'])->findOrFail($id);
        $categories = Category::where('status', 'active')->get();
        $brands = Brand::where('status', 'active')->get();
        $attributes = Attribute::all();

        return view('admin.products.edit', compact('product', 'categories', 'brands', 'attributes'));
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
                'sku' => 'required|string|max:100|unique:products,sku,' . $id,
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
            ProductAttribute::where('product_id', $product->id)->delete(); // Xóa dữ liệu cũ
            if ($request->filled('attributes')) {
                foreach ($request->attributes as $attributeInput) {
                    $attribute = Attribute::firstOrCreate(['name' => $attributeInput['name']]); // Chỉ dùng 'name'

                    foreach (explode(',', $attributeInput['values']) as $value) {
                        $attributeValue = AttributeValue::firstOrCreate([
                            'attribute_id' => $attribute->id,
                            'value' => trim($value),
                        ]);

                        ProductAttribute::create([
                            'product_id' => $product->id,
                            'attribute_id' => $attribute->id,
                            'attribute_value_id' => $attributeValue->id,
                        ]);
                    }
                }
            }

            // Cập nhật kích thước sản phẩm
            ProductDimension::where('productID', $product->id)->whereNull('variantID')->delete();
            ProductDimension::create([
                'productID' => $product->id,
                'variantID' => null,
                'length' => $request->dimensions['length'] ?? 0,
                'width' => $request->dimensions['width'] ?? 0,
                'height' => $request->dimensions['height'] ?? 0,
                'weight' => $request->dimensions['weight'] ?? 0,
                'shipping_cost' => $request->shipping_cost ?? 0,
            ]);

            // Cập nhật biến thể sản phẩm
            ProductVariant::where('productID', $product->id)->delete(); // Xóa biến thể cũ
            if ($request->filled('variants')) {
                foreach ($request->variants as $variantData) {
                    $variant = ProductVariant::create([
                        'productID' => $product->id,
                        'variant_name' => $variantData['variant_name'],
                        'price' => $variantData['price'],
                        'purchase_price' => $variantData['purchase_price'],
                        'sale_price' => $variantData['sale_price'],
                        'stock' => $variantData['stock'],
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

            return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được cập nhật thành công.');
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

            // Tìm sản phẩm cần xóa
            $product = Product::findOrFail($id);

            // Xóa tất cả liên kết: biến thể, ảnh, thuộc tính, kích thước
            ProductVariant::where('productID', $product->id)->delete();
            ProductImage::where('productID', $product->id)->delete();
            ProductAttribute::where('product_id', $product->id)->delete();
            ProductDimension::where('productID', $product->id)->delete();

            // Xóa sản phẩm chính
            $product->delete();

            DB::commit();
            return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được xóa thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Hiển thị chi tiết sản phẩm
     */
    public function show($id)
    {
        $product = Product::with(['variants', 'images', 'dimensions'])->findOrFail($id);
        return view('admin.products.show', compact('product'));
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
}
