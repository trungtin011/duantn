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
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProductControllerSeller extends Controller
{
    /**
     * Hiển thị danh sách sản phẩm
     */
    public function index(Request $request)
    {
        $seller = Auth::user()->seller;
        $shop = $seller->shops->first(); // Hoặc dùng session('current_shop_id') nếu bạn có hỗ trợ đa shop

        if (!$shop) {
            return back()->with('error', 'Bạn chưa có shop để quản lý sản phẩm.');
        }

        $query = Product::with(['variants', 'images'])
            ->where('shopID', $shop->id); // 🔐 Lọc sản phẩm đúng shop

        // Tìm kiếm
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                    ->orWhere('sku', 'like', "%{$searchTerm}%");
            });
        }

        // Lọc trạng thái
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'low_stock':
                    $query->where('stock_total', '<=', 5)->where('stock_total', '>', 0);
                    break;
                case 'out_of_stock':
                    $query->where('stock_total', 0);
                    break;
                case 'scheduled':
                case 'active':
                case 'inactive':
                    $query->where('status', $request->status);
                    break;
            }
        }

        $products = $query->latest()->paginate(10);

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
     * Lưu sản phẩm mới
     */
    public function store(Request $request)
    {
        $request->validate(
            $this->validationRules(), // Không truyền gì là store
            $this->validationMessages()
        );
        try {
            DB::beginTransaction();

            Log::info('Creating new product', [
                'request_data' => $request->except(['images', 'variant_images']),
                'has_images' => $request->hasFile('images'),
                'has_variants' => $request->filled('variants'),
                'user_id' => Auth::id() ?? 'guest'
            ]);

            // Kiểm tra seller
            $seller = Auth::user()->seller;
            if (!$seller) {
                Log::error('Seller not found for user ID: ' . Auth::id());
                return back()->withErrors('Bạn cần đăng ký làm seller trước.');
            }

            // Lấy userID từ seller để gán cho ownerID
            $userId = $seller->userID;

            // Tìm shop hiện có liên kết với seller qua userID
            $shop = $seller->shops()->where('ownerID', $userId)->first();

            // Nếu không tìm thấy shop, kiểm tra trực tiếp trong bảng shops
            if (!$shop) {
                $shop = Shop::where('ownerID', $userId)->first();
                if (!$shop) {
                    // Tạo shop mới chỉ khi thực sự không có
                    $shop = Shop::create([
                        'ownerID' => $userId,
                        'shop_name' => 'Default Shop for Seller ' . $seller->id,
                        'shop_phone' => '0900000000',
                        'shop_email' => 'default_' . $seller->id . '@example.com',
                        'shop_description' => 'Mô tả mặc định cho shop của seller ' . $seller->id,
                        'shop_logo' => '/logos/default.png',
                        'shop_banner' => '/banners/default.png',
                        'shop_status' => 'active',
                    ]);
                    Log::info('Created default shop for seller', ['seller_id' => $seller->id, 'shop_id' => $shop->id, 'ownerID' => $userId]);
                } else {
                    Log::info('Found existing shop for seller', ['shop_id' => $shop->id, 'ownerID' => $userId]);
                }
            } else {
                Log::info('Found existing shop for seller via relationship', ['shop_id' => $shop->id, 'ownerID' => $userId]);
            }

            if ($request->sale_price < $request->purchase_price) {
                return back()->withErrors(['sale_price' => 'Giá bán không được nhỏ hơn giá nhập.'])->withInput();
            }

            // Xử lý meta_keywords
            $metaKeywords = $request->meta_keywords ?: Str::slug($request->name);

            // Lưu sản phẩm
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
                'shopID' => $shop->id,
            ]);

            // Xử lý thuộc tính
            if ($request->filled('attributes')) {
                Log::info('Processing attributes', ['attributes' => $request->input('attributes')]);
                $attributeIds = [];
                foreach ($request->input('attributes') as $attributeInput) {
                    if (!isset($attributeInput['name']) || !isset($attributeInput['values']) || empty(trim($attributeInput['name'])) || empty(trim($attributeInput['values']))) {
                        continue;
                    }
                    $attribute = Attribute::firstOrCreate(['name' => trim($attributeInput['name'])]);
                    $attributeIds[] = $attribute->id;

                    foreach (explode(',', $attributeInput['values']) as $value) {
                        $value = trim($value);
                        if (empty($value)) continue;
                        $attributeValue = AttributeValue::firstOrCreate([
                            'attribute_id' => $attribute->id,
                            'value' => $value,
                        ]);
                        Log::info('Attribute value created', [
                            'attribute_id' => $attribute->id,
                            'attribute_value_id' => $attributeValue->id,
                            'attribute_value' => $attributeValue->value
                        ]);
                    }
                }
                if (!empty($attributeIds)) {
                    $product->attributes()->sync($attributeIds);
                    Log::info('Attributes attached to product', [
                        'product_id' => $product->id,
                        'attribute_ids' => $attributeIds
                    ]);
                }
            }

            // Xử lý biến thể
            $variants = [];
            if ($request->filled('variants')) {
                Log::info('Processing variants', ['variants_count' => count($request->variants)]);
                foreach ($request->variants as $variantIndex => $variantData) {
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

                    // Lưu kích thước cho biến thể
                    ProductDimension::create([
                        'productID' => $product->id,
                        'variantID' => $variant->id,
                        'length' => isset($variantData['length']) ? $variantData['length'] : null,
                        'width' => isset($variantData['width']) ? $variantData['width'] : null,
                        'height' => isset($variantData['height']) ? $variantData['height'] : null,
                        'weight' => isset($variantData['weight']) ? $variantData['weight'] : null,
                    ]);

                    Log::info('Variant created with dimensions', [
                        'variant_id' => $variant->id,
                        'variant_name' => $variant->variant_name,
                        'variant_sku' => $variant->sku,
                        'dimensions' => [
                            'length' => $variantData['length'] ?? null,
                            'width' => $variantData['width'] ?? null,
                            'height' => $variantData['height'] ?? null,
                            'weight' => $variantData['weight'] ?? null,
                        ]
                    ]);

                    // Lưu thuộc tính cho biến thể
                    if (isset($variantData['attributes'])) {
                        foreach ($variantData['attributes'] as $attrData) {
                            if (!isset($attrData['name']) || !isset($attrData['value']) || empty(trim($attrData['name'])) || empty(trim($attrData['value']))) {
                                continue;
                            }
                            $attribute = Attribute::firstOrCreate(['name' => trim($attrData['name'])]);
                            $attributeValue = AttributeValue::firstOrCreate([
                                'attribute_id' => $attribute->id,
                                'value' => trim($attrData['value']),
                            ]);
                            ProductVariantAttributeValue::create([
                                'product_variant_id' => $variant->id,
                                'attribute_value_id' => $attributeValue->id,
                            ]);
                            Log::info('Variant attribute value linked', [
                                'variant_id' => $variant->id,
                                'attribute_value_id' => $attributeValue->id,
                                'attribute_value' => $attributeValue->value
                            ]);
                        }
                    }
                }
            } else {
                // Lưu kích thước cho sản phẩm chính
                ProductDimension::create([
                    'productID' => $product->id,
                    'variantID' => null,
                    'length' => $request->length ?? null,
                    'width' => $request->width ?? null,
                    'height' => $request->height ?? null,
                    'weight' => $request->weight ?? null,
                ]);

                Log::info('Product dimensions saved', [
                    'product_id' => $product->id,
                    'dimensions' => [
                        'length' => $request->length ?? null,
                        'width' => $request->width ?? null,
                        'height' => $request->height ?? null,
                        'weight' => $request->weight ?? null,
                    ]
                ]);
            }

            // Lưu ảnh sản phẩm chính
            $displayOrder = 0;
            if ($request->hasFile('images')) {
                $imageCount = count($request->file('images'));
                Log::info('Processing main product images', ['image_count' => $imageCount]);
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
                    Log::info('Main product image saved', [
                        'image_path' => $path,
                        'is_default' => ($index === 0) ? 1 : 0,
                        'display_order' => $displayOrder - 1
                    ]);
                }
            }

            // Lưu ảnh biến thể
            foreach ($variants as $variantIndex => $variant) {
                if ($request->hasFile("variant_images.{$variantIndex}")) {
                    $variantImageCount = count($request->file("variant_images.{$variantIndex}"));
                    Log::info('Processing variant images', [
                        'variant_id' => $variant->id,
                        'variant_name' => $variant->variant_name,
                        'image_count' => $variantImageCount
                    ]);
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
                        Log::info('Variant image saved', [
                            'variant_id' => $variant->id,
                            'image_path' => $path,
                            'display_order' => $displayOrder - 1
                        ]);
                    }
                }
            }

            DB::commit();

            Log::info('Product creation completed successfully', [
                'product_id' => $product->id,
                'total_variants' => count($variants),
                'total_images' => $displayOrder
            ]);

            return redirect()->route('seller.products.index')->with('success', 'Sản phẩm đã được tạo thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Product creation failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Hiển thị form chỉnh sửa sản phẩm
     */
    public function edit($id)
    {
        // Tải sản phẩm với các quan hệ cần thiết
        $product = Product::with([
            'variants.attributeValues.attribute', // Tải biến thể và thuộc tính liên quan
            'images', // Tải ảnh sản phẩm
            'dimensions', // Tải kích thước
            'attributes.values', // Tải thuộc tính và giá trị
            'brands', // Tải thương hiệu
            'categories' // Tải danh mục
        ])->findOrFail($id);

        Log::info('Product loaded', [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'brand_ids' => $product->brands->pluck('id')->toArray(),
            'category_ids' => $product->categories->pluck('id')->toArray()
        ]);

        // Lấy ID các giá trị thuộc tính được sử dụng bởi biến thể
        $usedValueIds = DB::table('product_variant_attribute_values')
            ->join('product_variants', 'product_variants.id', '=', 'product_variant_attribute_values.product_variant_id')
            ->where('product_variants.productID', $product->id)
            ->pluck('attribute_value_id')
            ->toArray();

        // Lấy thuộc tính với các giá trị được sử dụng
        $attributes = $product->attributes()->with(['values' => function ($query) use ($usedValueIds) {
            $query->whereIn('id', $usedValueIds);
        }])->get();

        // Ghi log chi tiết về thuộc tính
        foreach ($attributes as $attribute) {
            Log::info('Product attribute loaded', [
                'attribute_id' => $attribute->id,
                'attribute_name' => $attribute->name,
                'values' => $attribute->values->pluck('value')->toArray(),
            ]);
        }

        // Tải danh sách thương hiệu (chỉ lấy trạng thái active)
        $brands = Brand::where('status', 'active')->get();
        // Tải danh sách danh mục
        $categories = Category::all();

        Log::info('Brands and categories loaded', [
            'brands_count' => $brands->count(),
            'categories_count' => $categories->count(),
        ]);

        return view('seller.products.edit', compact('product', 'attributes', 'brands', 'categories'));
    }

    /**
     * Cập nhật sản phẩm
     */
    public function update(Request $request, $id)
    {
        $request->validate(
            $this->validationRules(true, $id),
            $this->validationMessages()
        );

        try {
            DB::beginTransaction();

            $product = Product::findOrFail($id);
            Log::info('Updating product', ['product_id' => $id, 'request_data' => $request->except(['images', 'variant_images'])]);

            $metaKeywords = $request->meta_keywords ?: Str::slug($request->name);

            // Cập nhật thông tin sản phẩm
            $product->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description ?: '',
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

            // ======= Xử lý thương hiệu (brand) =======
            if ($request->has('brand_id')) {
                // Đồng bộ brand_id với bảng product_brands
                $product->brands()->sync([$request->brand_id]);
                Log::info('Brand synced', [
                    'product_id' => $product->id,
                    'brand_id' => $request->brand_id
                ]);
            } else {
                // Nếu không có brand_id, xóa tất cả quan hệ thương hiệu
                $product->brands()->detach();
                Log::info('Brand detached', ['product_id' => $product->id]);
            }

            // ======= Xử lý danh mục (category) =======
            if ($request->has('category_id')) {
                // Đồng bộ category_id với bảng product_categories
                $product->categories()->sync([$request->category_id]);
                Log::info('Category synced', [
                    'product_id' => $product->id,
                    'category_id' => $request->category_id
                ]);
            } else {
                // Nếu không có category_id, xóa tất cả quan hệ danh mục
                $product->categories()->detach();
                Log::info('Category detached', ['product_id' => $product->id]);
            }

            // ======= Xử lý thuộc tính sản phẩm =======
            if ($request->has('attributes')) {
                $existingAttributeValues = $product->attributes()
                    ->with('values')
                    ->get()
                    ->mapWithKeys(function ($attr) {
                        return [$attr->name => array_values($attr->values->pluck('value')->sort()->toArray())];
                    })
                    ->toArray();

                $newAttributeValues = collect($request->input('attributes', []))
                    ->filter(function ($attr) {
                        return !empty(trim($attr['name'])) && !empty(trim($attr['values']));
                    })
                    ->mapWithKeys(function ($attr) {
                        return [
                            $attr['name'] => array_values(collect(explode(',', $attr['values']))
                                ->map(fn($v) => trim($v))
                                ->filter()
                                ->sort()
                                ->toArray())
                        ];
                    })
                    ->toArray();

                Log::info('Existing attribute values', $existingAttributeValues);
                Log::info('New attribute values', $newAttributeValues);
                Log::info('Attribute values equal?', ['equal' => $existingAttributeValues == $newAttributeValues]);

                // Chỉ cập nhật nếu có sự thay đổi hoặc có thuộc tính mới
                if ($newAttributeValues != $existingAttributeValues) {
                    $product->attributes()->detach();
                    Log::info('Old attributes detached', ['product_id' => $product->id]);

                    $attributeIds = [];

                    foreach ($newAttributeValues as $name => $values) {
                        $attribute = Attribute::firstOrCreate(['name' => trim($name)]);
                        $attributeIds[] = $attribute->id;

                        foreach ($values as $value) {
                            $attributeValue = AttributeValue::firstOrNew([
                                'attribute_id' => $attribute->id,
                                'value' => trim($value),
                            ]);

                            if (!$attributeValue->exists) {
                                $attributeValue->save();
                                Log::info('Attribute value created', [
                                    'attribute_id' => $attribute->id,
                                    'attribute_value_id' => $attributeValue->id,
                                    'attribute_value' => $attributeValue->value
                                ]);
                            }

                            // Gán giá trị thuộc tính cho sản phẩm nếu không có biến thể
                            if (!$request->filled('variants')) {
                                DB::table('product_attribute')->updateOrInsert([
                                    'product_id' => $product->id,
                                    'attribute_id' => $attribute->id,
                                ]);
                            }
                        }
                    }

                    if (!empty($attributeIds)) {
                        $product->attributes()->sync($attributeIds);
                        Log::info('New attributes attached to product', [
                            'product_id' => $product->id,
                            'attribute_ids' => $attributeIds
                        ]);
                    }
                } else {
                    Log::info('Attributes not changed, skipping update.', ['product_id' => $product->id]);
                }
            } else {
                Log::info('No attributes provided in request, retaining existing attributes.', ['product_id' => $product->id]);
            }

            // ======= Xử lý biến thể =======
            $existingVariants = $product->variants()->with('images')->get();
            $variantImageMap = [];
            foreach ($existingVariants as $variant) {
                $variantImageMap[$variant->variant_name] = $variant->images->pluck('image_path')->toArray();
            }

            $product->variants()->delete();
            $product->dimensions()->whereNotNull('variantID')->delete();

            if ($request->filled('variants')) {
                foreach ($request->variants as $variantIndex => $variantData) {
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
                            if (!isset($attrData['name']) || !isset($attrData['value']) || empty(trim($attrData['name'])) || empty(trim($attrData['value']))) {
                                continue;
                            }

                            $attribute = Attribute::firstOrCreate(['name' => trim($attrData['name'])]);
                            $attributeValue = AttributeValue::firstOrNew([
                                'attribute_id' => $attribute->id,
                                'value' => trim($attrData['value']),
                            ]);
                            if (!$attributeValue->exists) {
                                $attributeValue->save();
                            }

                            ProductVariantAttributeValue::create([
                                'product_variant_id' => $variant->id,
                                'attribute_value_id' => $attributeValue->id,
                            ]);

                            Log::info('Variant attribute value linked', [
                                'variant_id' => $variant->id,
                                'attribute_value_id' => $attributeValue->id,
                                'attribute_value' => $attributeValue->value
                            ]);
                        }
                    }

                    if ($request->hasFile("variant_images.{$variantIndex}")) {
                        ProductImage::where('productID', $product->id)->where('variantID', $variant->id)->delete();

                        foreach ($request->file("variant_images.{$variantIndex}") as $image) {
                            $path = $image->store('product_images', 'public');
                            ProductImage::create([
                                'productID' => $product->id,
                                'variantID' => $variant->id,
                                'image_path' => $path,
                                'is_default' => 0,
                                'display_order' => 0,
                                'alt_text' => "{$variant->variant_name} - Image",
                            ]);
                            Log::info('Variant image saved', [
                                'variant_id' => $variant->id,
                                'image_path' => $path
                            ]);
                        }
                    } else {
                        if (isset($variantImageMap[$variantData['name']])) {
                            foreach ($variantImageMap[$variantData['name']] as $imagePath) {
                                ProductImage::create([
                                    'productID' => $product->id,
                                    'variantID' => $variant->id,
                                    'image_path' => $imagePath,
                                    'is_default' => 0,
                                    'display_order' => 0,
                                    'alt_text' => "{$variant->variant_name} - Image",
                                ]);
                                Log::info('Existing variant image retained', [
                                    'variant_id' => $variant->id,
                                    'image_path' => $imagePath
                                ]);
                            }
                        }
                    }
                }
            }

            // ======= Ảnh chính và ảnh phụ =======
            $hasMainImage = $request->hasFile('main_image');
            $hasAdditionalImages = $request->hasFile('images');

            if ($hasMainImage) {
                ProductImage::where('productID', $product->id)
                    ->whereNull('variantID')
                    ->where('is_default', 1)
                    ->delete();

                $mainImage = $request->file('main_image');
                $path = $mainImage->store('product_images', 'public');

                ProductImage::create([
                    'productID' => $product->id,
                    'variantID' => null,
                    'image_path' => $path,
                    'is_default' => 1,
                    'display_order' => 0,
                    'alt_text' => "{$product->name} - Ảnh chính",
                ]);
            }

            $existingImages = ProductImage::where('productID', $product->id)
                ->whereNull('variantID')
                ->where('is_default', 0)
                ->pluck('image_path')
                ->toArray();

            $retainedImages = $request->input('existing_images', []);
            $imagesToDelete = array_diff($existingImages, $retainedImages);
            if (!empty($imagesToDelete)) {
                ProductImage::where('productID', $product->id)
                    ->whereNull('variantID')
                    ->where('is_default', 0)
                    ->whereIn('image_path', $imagesToDelete)
                    ->delete();
                Log::info('Deleted additional images', ['image_paths' => $imagesToDelete]);
            }

            if ($hasAdditionalImages) {
                $lastOrder = ProductImage::where('productID', $product->id)
                    ->whereNull('variantID')
                    ->max('display_order') ?? 0;

                foreach ($request->file('images') as $image) {
                    $path = $image->store('product_images', 'public');
                    $lastOrder++;

                    ProductImage::create([
                        'productID' => $product->id,
                        'variantID' => null,
                        'image_path' => $path,
                        'is_default' => 0,
                        'display_order' => $lastOrder,
                        'alt_text' => "{$product->name} - Ảnh phụ {$lastOrder}",
                    ]);
                    Log::info('Additional image saved', [
                        'product_id' => $product->id,
                        'image_path' => $path
                    ]);
                }
            }

            $product->dimensions()->updateOrCreate(
                ['productID' => $product->id, 'variantID' => null],
                [
                    'length' => $request->length ?? 0,
                    'width' => $request->width ?? 0,
                    'height' => $request->height ?? 0,
                    'weight' => $request->weight ?? 0,
                ]
            );

            DB::commit();
            return redirect()->route('seller.products.index')->with('success', 'Sản phẩm đã được cập nhật thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Product update failed', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())->withInput();
        }
    }


    /**
     * Xóa sản phẩm (soft delete)
     * @param int $id ID của sản phẩm cần xóa
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            // Kiểm tra seller
            $seller = Auth::user()->seller;
            if (!$seller) {
                Log::error('Seller not found for user ID: ' . Auth::id());
                return redirect()->back()->with('error', 'Bạn cần đăng ký làm seller trước.');
            }

            // Tìm sản phẩm cần xóa
            $product = Product::with(['shop', 'variants', 'images', 'dimensions'])->findOrFail($id);

            // Kiểm tra quyền sở hữu
            if ($product->shop->ownerID !== $seller->userID) {
                Log::warning('Unauthorized attempt to delete product', [
                    'product_id' => $product->id,
                    'seller_user_id' => $seller->userID,
                    'shop_owner_id' => $product->shop->ownerID,
                ]);
                return redirect()->back()->with('error', 'Bạn không có quyền xóa sản phẩm này.');
            }

            Log::info('Starting product soft delete', [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'shop_id' => $product->shopID,
                'seller_user_id' => $seller->userID,
            ]);

            // Soft delete các bản ghi liên quan
            $variantCount = ProductVariant::where('productID', $product->id)->count();
            ProductVariant::where('productID', $product->id)->delete(); // Soft delete

            $imageCount = ProductImage::where('productID', $product->id)->count();
            ProductImage::where('productID', $product->id)->delete(); // Soft delete

            $dimensionCount = ProductDimension::where('productID', $product->id)->count();
            ProductDimension::where('productID', $product->id)->delete(); // Soft delete

            // Xóa liên kết thuộc tính của biến thể
            $variantIds = $product->variants->pluck('id');
            $attributeValueCount = ProductVariantAttributeValue::whereIn('product_variant_id', $variantIds)->count();
            ProductVariantAttributeValue::whereIn('product_variant_id', $variantIds)->delete(); // Soft delete

            // Soft delete sản phẩm chính
            $product->delete();

            Log::info('Product soft delete completed', [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'variants_deleted' => $variantCount,
                'images_deleted' => $imageCount,
                'dimensions_deleted' => $dimensionCount,
                'variant_attribute_values_deleted' => $attributeValueCount,
            ]);

            DB::commit();
            return redirect()->route('seller.products.index')->with('success', 'Sản phẩm đã được xóa thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Product soft delete failed', [
                'product_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi xóa sản phẩm. Vui lòng thử lại.');
        }
    }


    // Tái sử dụng cho store & update
    protected function validationRules($isUpdate = false, $productId = null)
    {
        $skuRule = $isUpdate
            ? 'required|string|max:100|unique:products,sku,' . $productId
            : 'required|string|max:100|unique:products,sku';

        $variantSkuRule = $isUpdate
            ? 'required|string|max:100|distinct'
            : 'required|string|max:100|unique:product_variants,sku';

        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'brand_id' => 'required|exists:brand,id', // Kiểm tra brand_id tồn tại trong bảng brand
            'category_id' => 'required|exists:categories,id', // Kiểm tra category_id tồn tại trong bảng categories
            'sku' => $skuRule,
            'price' => 'required|numeric|min:0',
            'purchase_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'stock_total' => 'required|integer|min:0',
            'length' => 'nullable|numeric|min:0',
            'width' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            'weight' => 'nullable|numeric|min:0',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:320',
            'meta_keywords' => 'nullable|string|max:255',
            'attributes' => 'nullable|array',
            'attributes.*.name' => 'nullable|string|max:100',
            'attributes.*.values' => 'nullable|string',
            'variants' => 'nullable|array',
            'variants.*.name' => 'required|string|max:255',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.purchase_price' => 'required|numeric|min:0',
            'variants.*.sale_price' => 'required|numeric|min:0',
            'variants.*.sku' => $variantSkuRule,
            'variants.*.stock_total' => 'required|integer|min:0',
            'variants.*.length' => 'nullable|numeric|min:0',
            'variants.*.width' => 'nullable|numeric|min:0',
            'variants.*.height' => 'nullable|numeric|min:0',
            'variants.*.weight' => 'nullable|numeric|min:0',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:5120',
            'variant_images.*.*' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:5120',
            'main_image' => ($isUpdate ? 'nullable' : 'required') . '|image|mimes:jpeg,png,jpg,webp,svg|max:5120', // Thêm validation cho main_image
        ];
    }

    protected function validationMessages()
    {
        return [
            'name.required' => 'Vui lòng nhập tên sản phẩm.',
            'name.max' => 'Tên sản phẩm không được vượt quá :max ký tự.',
            'sku.required' => 'Vui lòng nhập mã SKU.',
            'sku.unique' => 'Mã SKU này đã tồn tại.',
            'brand_id.required' => 'Vui lòng chọn thương hiệu.',
            'brand_id.exists' => 'Thương hiệu đã chọn không hợp lệ.',
            'category_id.required' => 'Vui lòng chọn danh mục.',
            'category_id.exists' => 'Danh mục đã chọn không hợp lệ.',
            'price.required' => 'Vui lòng nhập giá gốc.',
            'price.numeric' => 'Giá gốc phải là số.',
            'price.min' => 'Giá gốc không được nhỏ hơn 0.',
            'purchase_price.required' => 'Vui lòng nhập giá nhập.',
            'sale_price.required' => 'Vui lòng nhập giá bán.',
            'stock_total.required' => 'Vui lòng nhập số lượng tồn kho.',
            'stock_total.integer' => 'Số lượng tồn kho phải là số nguyên.',
            'stock_total.min' => 'Tồn kho không được nhỏ hơn 0.',
            'meta_title.max' => 'Tiêu đề SEO không vượt quá :max ký tự.',
            'meta_description.max' => 'Mô tả SEO không vượt quá :max ký tự.',
            'meta_keywords.max' => 'Từ khóa SEO không vượt quá :max ký tự.',
            'variants.*.name.required' => 'Vui lòng nhập tên phiên bản.',
            'variants.*.sku.required' => 'Vui lòng nhập mã SKU cho phiên bản.',
            'variants.*.sku.unique' => 'Mã SKU phiên bản đã tồn tại.',
            'variants.*.price.required' => 'Vui lòng nhập giá gốc cho phiên bản.',
            'variants.*.purchase_price.required' => 'Vui lòng nhập giá nhập cho phiên bản.',
            'variants.*.sale_price.required' => 'Vui lòng nhập giá bán cho phiên bản.',
            'variants.*.stock_total.required' => 'Vui lòng nhập tồn kho cho phiên bản.',
            'variants.*.stock_total.integer' => 'Tồn kho phiên bản phải là số nguyên.',
            'variants.*.stock_total.min' => 'Tồn kho phiên bản không được nhỏ hơn 0.',
            'images.*.image' => 'Mỗi tệp tải lên phải là hình ảnh.',
            'images.*.mimes' => 'Chỉ chấp nhận ảnh định dạng: jpeg, png, jpg, webp, svg.',
            'images.*.max' => 'Ảnh không được vượt quá 5MB.',
            'variant_images.*.*.image' => 'Ảnh phiên bản phải là hình ảnh.',
            'variant_images.*.*.mimes' => 'Ảnh phiên bản chỉ chấp nhận định dạng jpeg, png, jpg, webp, svg.',
            'variant_images.*.*.max' => 'Ảnh phiên bản không được vượt quá 5MB.',
            'main_image.required' => 'Vui lòng chọn ảnh chính cho sản phẩm.',
            'main_image.image' => 'Ảnh chính phải là hình ảnh.',
            'main_image.mimes' => 'Ảnh chính chỉ chấp nhận định dạng jpeg, png, jpg, webp, svg.',
            'main_image.max' => 'Ảnh chính không được vượt quá 5MB.',
        ];
    }
}
