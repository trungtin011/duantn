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
use App\Models\ProductVariantAttributeValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductControllerSeller extends Controller
{
    public function create()
    {
        $attributes = Attribute::all();
        $categories = Category::where('status', 'active')->get();
        $brands = Brand::where('status', 'active')->get();
        return view('seller.products.create', compact('attributes', 'categories', 'brands'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Xác thực dữ liệu
            $rules = [
                'name' => 'required|string|max:100',
                'description' => 'nullable|string',
                'brand' => 'required|exists:brand,name',
                'category' => 'required|exists:categories,name',
                'price' => 'required|numeric|min:0',
                'purchase_price' => 'required|numeric|min:0',
                'sale_price' => 'nullable|numeric|min:0',
                'stock_total' => 'required_if:is_variant,0|integer|min:0',
                'meta_title' => 'nullable|string|max:255',
                'meta_description' => 'nullable|string|max:320',
                'meta_keywords' => 'nullable|string|max:255',
                'attributes.*.name' => 'required|string|max:255',
                'attributes.*.values' => 'required|string',
                'variants.*.sku' => 'required|string|max:100|unique:product_variants,sku',
                'variants.*.price' => 'required|numeric|min:0',
                'variants.*.purchase_price' => 'nullable|numeric|min:0',
                'variants.*.sale_price' => 'nullable|numeric|min:0',
                'variants.*.stock' => 'required|integer|min:0',
                'variants.*.attributes' => 'nullable|array',
                'variants.*.attributes.*.attribute_name' => 'required|string|max:255',
                'variants.*.attributes.*.value' => 'required|string|max:255',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
                'is_variant' => 'required|boolean',
            ];

            // Log toàn bộ dữ liệu request để debug
            Log::info('Dữ liệu request khi thêm sản phẩm: ', $request->all());

            $request->validate($rules);

            $shop = Auth::user()->shop;
            if (!$shop) {
                throw new \Exception('Không tìm thấy cửa hàng của bạn.');
            }

            $metaKeywords = $request->meta_keywords ?: Str::slug($request->name);

            // Tạo SKU mặc định nếu không có biến thể
            $sku = $request->sku ?? Str::slug($request->name) . '-' . time();

            $product = Product::create([
                'shopID' => $shop->id,
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description ?? '',
                'brand' => $request->brand,
                'category' => $request->category,
                'sku' => $sku,
                'price' => $request->price,
                'purchase_price' => $request->purchase_price,
                'sale_price' => $request->sale_price ?? $request->price,
                'stock_total' => $request->is_variant ? 0 : $request->stock_total,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'meta_keywords' => $metaKeywords,
                'is_featured' => $request->has('is_featured') ? 1 : 0,
                'is_variant' => $request->is_variant,
                'status' => $request->save_draft ? 'draft' : 'active',
                'created_at' => now(),
            ]);

            // Lưu kích thước sản phẩm chính
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
                foreach ($request->attributes as $attributeData) {
                    $attribute = Attribute::firstOrCreate(['name' => $attributeData['name']]);
                    $values = array_filter(array_map('trim', explode(',', $attributeData['values'])));
                    foreach ($values as $value) {
                        AttributeValue::firstOrCreate([
                            'attribute_id' => $attribute->id,
                            'value' => $value,
                        ]);
                    }
                }
            }

            // Lưu biến thể
            if ($request->is_variant && $request->filled('variants')) {
                foreach ($request->variants as $index => $variantData) {
                    $variantData = json_decode($variantData, true); // Giải mã JSON
                    if (is_array($variantData)) {
                        $variant = ProductVariant::create([
                            'productID' => $product->id,
                            'variant_name' => implode(', ', array_column($variantData['attributes'] ?? [], 'value')),
                            'sku' => $variantData['sku'],
                            'price' => $variantData['price'],
                            'purchase_price' => $variantData['purchase_price'],
                            'sale_price' => $variantData['sale_price'] ?? $variantData['price'],
                            'stock' => $variantData['stock'],
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
                            foreach ($variantData['attributes'] as $attrName => $attrValue) {
                                $attribute = Attribute::firstOrCreate(['name' => $attrName]);
                                $attributeValue = AttributeValue::firstOrCreate([
                                    'attribute_id' => $attribute->id,
                                    'value' => $attrValue,
                                ]);
                                ProductVariantAttributeValue::create([
                                    'product_variant_id' => $variant->id,
                                    'attribute_value_id' => $attributeValue->id,
                                ]);
                            }
                        }
                    }
                }
            }

            // Lưu ảnh
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
        } catch (\ValidationException $e) {
            DB::rollBack();
            // Log lỗi validation chi tiết
            Log::error('Lỗi validation khi thêm sản phẩm: ', $e->errors());
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . implode(' ', $e->errors()['purchase_price'] ?? ['Dữ liệu không hợp lệ']))->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi khi thêm sản phẩm: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())->withInput();
        }
    }

    public function index(Request $request)
    {
        $query = Product::with(['variants', 'images'])->where('shopID', Auth::user()->shop->id);

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

    public function edit($id)
    {
        $product = Product::with(['variants', 'images', 'dimensions'])->findOrFail($id);
        if ($product->shopID !== Auth::user()->shop->id) {
            return redirect()->back()->with('error', 'Bạn không có quyền chỉnh sửa sản phẩm này.');
        }

        $attributes = Attribute::all();
        $categories = Category::where('status', 'active')->get();
        $brands = Brand::where('status', 'active')->get();
        return view('seller.products.create', compact('product', 'attributes', 'categories', 'brands'));
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $product = Product::findOrFail($id);
            if ($product->shopID !== Auth::user()->shop->id) {
                throw new \Exception('Bạn không có quyền chỉnh sửa sản phẩm này.');
            }

            $rules = [
                'name' => 'required|string|max:100',
                'description' => 'nullable|string',
                'brand' => 'required|exists:brand,name',
                'category' => 'required|exists:categories,name',
                'sku' => 'required|string|max:100|unique:products,sku,' . $id,
                'price' => 'required|numeric|min:0',
                'purchase_price' => 'required|numeric|min:0',
                'sale_price' => 'nullable|numeric|min:0',
                'stock_total' => 'required|integer|min:0',
                'meta_title' => 'nullable|string|max:255',
                'meta_description' => 'nullable|string|max:320',
                'meta_keywords' => 'nullable|string|max:255',
                'attributes.*.name' => 'required|string|max:255',
                'attributes.*.values' => 'required|string',
                'variants.*.sku' => 'required|string|max:100|unique:product_variants,sku',
                'variants.*.price' => 'required|numeric|min:0',
                'variants.*.purchase_price' => 'required|numeric|min:0',
                'variants.*.sale_price' => 'nullable|numeric|min:0',
                'variants.*.stock' => 'required|integer|min:0',
                'variants.*.attributes' => 'nullable|array',
                'variants.*.attributes.*.attribute_name' => 'required|string|max:255',
                'variants.*.attributes.*.value' => 'required|string|max:255',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            ];

            $request->validate($rules);

            $metaKeywords = $request->meta_keywords ?: Str::slug($request->name);

            $product->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description ?? '',
                'brand' => $request->brand,
                'category' => $request->category,
                'sku' => $request->sku,
                'price' => $request->price,
                'purchase_price' => $request->purchase_price,
                'sale_price' => $request->sale_price ?? $request->price,
                'stock_total' => $request->stock_total,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'meta_keywords' => $metaKeywords,
                'is_featured' => $request->has('is_featured') ? 1 : 0,
                'is_variant' => $request->filled('variants') ? 1 : 0,
                'status' => $request->save_draft ? 'draft' : 'active',
            ]);

            // Xóa và cập nhật kích thước sản phẩm chính
            ProductDimension::where('productID', $product->id)->whereNull('variantID')->delete();
            ProductDimension::create([
                'productID' => $product->id,
                'variantID' => null,
                'length' => $request->length ?? 0,
                'width' => $request->width ?? 0,
                'height' => $request->height ?? 0,
                'weight' => $request->weight ?? 0,
            ]);

            // Xóa và cập nhật thuộc tính
            ProductVariantAttributeValue::where('product_id', $product->id)->delete();
            if ($request->filled('attributes')) {
                foreach ($request->attributes as $attributeData) {
                    $attribute = Attribute::firstOrCreate(['name' => $attributeData['name']]);
                    $values = array_filter(array_map('trim', explode(',', $attributeData['values'])));
                    foreach ($values as $value) {
                        $attributeValue = AttributeValue::firstOrCreate([
                            'attribute_id' => $attribute->id,
                            'value' => $value,
                        ]);
                    }
                }
            }

            // Xóa và cập nhật biến thể
            ProductVariant::where('productID', $product->id)->delete();
            ProductDimension::where('productID', $product->id)->whereNotNull('variantID')->delete();
            if ($request->filled('variants')) {
                foreach ($request->variants as $variantData) {
                    $variant = ProductVariant::create([
                        'productID' => $product->id,
                        'variant_name' => implode(', ', array_column($variantData['attributes'] ?? [], 'value')),
                        'sku' => $variantData['sku'],
                        'price' => $variantData['price'],
                        'purchase_price' => $variantData['purchase_price'],
                        'sale_price' => $variantData['sale_price'] ?? $variantData['price'],
                        'stock' => $variantData['stock'],
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
                }
            }

            // Xóa và cập nhật ảnh
            ProductImage::where('productID', $product->id)->delete();
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
            return redirect()->route('seller.products.index')->with('success', 'Sản phẩm đã được cập nhật thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi khi cập nhật sản phẩm: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $product = Product::findOrFail($id);
            if ($product->shopID !== Auth::user()->shop->id) {
                throw new \Exception('Bạn không có quyền xóa sản phẩm này.');
            }

            $variantIds = ProductVariant::where('productID', $product->id)->pluck('id');
            ProductVariant::where('productID', $product->id)->delete();
            ProductImage::where('productID', $product->id)->delete();
            ProductVariantAttributeValue::whereIn('product_variant_id', $variantIds)->delete();
            ProductDimension::where('productID', $product->id)->delete();
            $product->delete();

            DB::commit();
            return redirect()->route('seller.products.index')->with('success', 'Sản phẩm đã được xóa thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi xóa sản phẩm ID ' . $id . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $product = Product::with(['variants', 'images', 'dimensions'])->findOrFail($id);
        if ($product->shopID !== Auth::user()->shop->id) {
            return redirect()->back()->with('error', 'Bạn không có quyền xem sản phẩm này.');
        }
        return view('seller.products.show', compact('product'));
    }

    public function getSubBrands(Request $request)
    {
        $brand = $request->query('brand');
        $subBrands = Brand::where('name', $brand)->first()?->subBrands()->where('status', 'active')->get(['name']);
        return response()->json($subBrands ?? []);
    }

    public function getSubCategories(Request $request)
    {
        $category = $request->query('category');
        $subCategories = Category::where('name', $category)->first()?->subCategories()->where('status', 'active')->get(['name']);
        return response()->json($subCategories ?? []);
    }
}
