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
use App\Models\ProductVariantAttributeValue;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use App\Models\NotificationReceiver;

class ProductControllerAdmin extends Controller
{
    /**
     * Hiển thị danh sách sản phẩm
     */
    public function index(Request $request)
    {
        $query = Product::with(['variants', 'images', 'shop']);

        // Tìm kiếm theo tên hoặc SKU
        if ($request->filled('search')) {
            $query->where('name', 'LIKE', '%' . $request->search . '%')
                ->orWhere('sku', 'LIKE', '%' . $request->search . '%');
        }

        // Lọc theo trạng thái nếu có chọn
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Lọc theo shop nếu có chọn
        if ($request->filled('shop_id')) {
            $query->where('shopID', $request->shop_id);
        }

        $products = $query->paginate(5);

        // Lấy danh sách shops để hiển thị trong dropdown
        $shops = Shop::where('shop_status', 'active')->get();

        return view('admin.products.index', compact('products', 'shops'));
    }

    /**
     * Hiển thị form thêm sản phẩm đơn giản
     */
    /**
     * Hiển thị trang chọn shop để thêm sản phẩm
     */
    public function selectShop()
    {
        $shops = Shop::where('shop_status', 'active')->get();
        return view('admin.products.select-shop', compact('shops'));
    }

    public function create(Request $request)
    {
        // Kiểm tra shop_id từ request
        $shopId = $request->query('shop_id');
        if (!$shopId) {
            return redirect()->route('admin.products.select-shop')
                ->withErrors('Vui lòng chọn cửa hàng để thêm sản phẩm.');
        }

        // Kiểm tra shop có tồn tại và active không
        $shop = Shop::where('id', $shopId)
            ->where('shop_status', 'active')
            ->first();
        
        if (!$shop) {
            return redirect()->route('admin.products.select-shop')
                ->withErrors('Cửa hàng không tồn tại hoặc không hoạt động.');
        }

        $brands = Brand::all();
        $categories = Category::all();
        $allAttributes = Attribute::with(['values' => function ($query) {
            $query->whereNotNull('value')->where('value', '!=', '');
        }])
            ->whereNotNull('name')
            ->where('name', '!=', '')
            ->get()
            ->map(function ($attr) {
                return [
                    'id' => $attr->id,
                    'name' => $attr->name,
                    'values' => $attr->values->pluck('value')->toArray(),
                ];
            })
            ->toArray();

        Log::info('Dữ liệu allAttributes trong create', [
            'allAttributes' => $allAttributes,
        ]);

        return view('admin.products.create', compact('brands', 'categories', 'allAttributes', 'shop'));
    }

    /** 
     * Lưu sản phẩm mới
     */
    public function store(Request $request)
    {
        Log::info('Request all', $request->all());
        $request->validate(
            $this->validationRules(),
            $this->validationMessages()
        );

        try {
            DB::beginTransaction();

            Log::info('Creating new product', [
                'request_data' => $request->except(['main_image', 'images', 'variant_images']),
                'has_main_image' => $request->hasFile('main_image'),
                'has_additional_images' => $request->hasFile('images'),
                'has_variants' => $request->filled('variants'),
                'variants_data' => $request->variants,
                'user_id' => Auth::id() ?? 'guest'
            ]);

            // Kiểm tra shop_id từ form
            $shopId = $request->input('shop_id');
            if (!$shopId) {
                return back()->withErrors('Vui lòng chọn cửa hàng để thêm sản phẩm.')->withInput();
            }

            $shop = Shop::where('id', $shopId)
                ->where('shop_status', 'active')
                ->first();
            
            if (!$shop) {
                return back()->withErrors('Cửa hàng không tồn tại hoặc không hoạt động.')->withInput();
            }

            if ($request->sale_price < $request->purchase_price) {
                return back()->withErrors(['sale_price' => 'Giá bán không được nhỏ hơn giá nhập.'])->withInput();
            }

            // Kiểm tra loại sản phẩm
            $isVariant = $request->product_type === 'variant';

            // Xử lý meta_keywords
            $metaKeywords = $request->meta_keywords ?: Str::slug($request->name);

            // Lưu sản phẩm
            $product = Product::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description ?: '',
                'sku' => $request->sku,
                'price' => $isVariant ? null : $request->price,
                'purchase_price' => $isVariant ? null : $request->purchase_price,
                'sale_price' => $isVariant ? null : $request->sale_price,
                'stock_total' => $isVariant ? null : $request->stock_total,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'meta_keywords' => $metaKeywords,
                'is_featured' => $request->has('is_featured') ? 1 : 0,
                'is_variant' => $isVariant ? 1 : 0,
                'status' => $request->save_draft ? 'draft' : 'pending',
                'sold_quantity' => 0,
                'shopID' => $shop->id,
            ]);

            Log::info('Product created', [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'has_variants' => $request->filled('variants'),
            ]);

            // Lưu danh mục
            if ($request->filled('category_ids') && is_array($request->category_ids)) {
                $product->categories()->sync($request->category_ids);
                Log::info('Categories synced', [
                    'product_id' => $product->id,
                    'category_ids' => $request->category_ids
                ]);
            }

            // Lưu thương hiệu
            if ($request->filled('brand_ids') && is_array($request->brand_ids)) {
                $product->brands()->sync($request->brand_ids);
                Log::info('Brands synced', [
                    'product_id' => $product->id,
                    'brand_ids' => $request->brand_ids
                ]);
            }

            // Xử lý thuộc tính
            if ($isVariant && $request->has('attributes')) {
                Log::info('Dữ liệu attributes được gửi', [
                    'attributes_raw' => $request->input('attributes'),
                    'is_array' => is_array($request->input('attributes')),
                    'attributes_count' => is_array($request->input('attributes')) ? count($request->input('attributes')) : 0
                ]);

                $attributeIds = [];
                if (is_array($request->input('attributes'))) {
                    foreach ($request->input('attributes', []) as $index => $attrData) {
                        Log::info('Xử lý thuộc tính', [
                            'attribute_index' => $index,
                            'attribute_data' => $attrData
                        ]);

                        // Bỏ qua nếu thiếu id hoặc name
                        if (empty($attrData['id']) && empty(trim($attrData['name'] ?? ''))) {
                            Log::warning('Bỏ qua thuộc tính do thiếu id và tên', [
                                'attribute_index' => $index,
                                'attribute_data' => $attrData
                            ]);
                            continue;
                        }

                        // Tìm hoặc tạo thuộc tính
                        $attribute = null;
                        if (!empty($attrData['id']) && $attrData['id'] !== 'new') {
                            $attribute = Attribute::find($attrData['id']);
                            if (!$attribute) {
                                Log::warning('Không tìm thấy thuộc tính với ID', [
                                    'attribute_id' => $attrData['id'],
                                    'attribute_index' => $index,
                                    'database_attributes' => Attribute::pluck('id')->toArray()
                                ]);
                                continue;
                            }
                            Log::info('Tìm thấy thuộc tính', [
                                'attribute_id' => $attribute->id,
                                'attribute_name' => $attribute->name
                            ]);
                        } elseif (!empty(trim($attrData['name'] ?? ''))) {
                            try {
                                $attribute = Attribute::firstOrCreate(['name' => trim($attrData['name'])]);
                                Log::info($attribute->wasRecentlyCreated ? 'Tạo thuộc tính mới' : 'Tìm thấy thuộc tính với name', [
                                    'attribute_id' => $attribute->id,
                                    'attribute_name' => $attribute->name
                                ]);
                            } catch (\Exception $e) {
                                Log::error('Lỗi khi tạo hoặc tìm thuộc tính', [
                                    'attribute_index' => $index,
                                    'attribute_data' => $attrData,
                                    'error' => $e->getMessage(),
                                    'trace' => $e->getTraceAsString()
                                ]);
                                continue;
                            }
                        }

                        if ($attribute) {
                            $attributeIds[] = $attribute->id;
                            Log::info('Thêm attribute_id vào danh sách đồng bộ', [
                                'attribute_id' => $attribute->id,
                                'attribute_index' => $index
                            ]);

                            // Xử lý giá trị thuộc tính (nếu có)
                            if (!empty(trim($attrData['values'] ?? ''))) {
                                $values = collect(explode(',', $attrData['values']))
                                    ->map(fn($v) => trim($v))
                                    ->filter()
                                    ->toArray();

                                if (empty($values)) {
                                    Log::warning('Không có giá trị thuộc tính hợp lệ', [
                                        'attribute_id' => $attribute->id,
                                        'attribute_values' => $attrData['values']
                                    ]);
                                } else {
                                    foreach ($values as $value) {
                                        try {
                                            $attributeValue = AttributeValue::firstOrNew([
                                                'attribute_id' => $attribute->id,
                                                'value' => $value,
                                            ]);
                                            if (!$attributeValue->exists) {
                                                $attributeValue->save();
                                                Log::info('Tạo giá trị thuộc tính', [
                                                    'attribute_id' => $attribute->id,
                                                    'attribute_value_id' => $attributeValue->id,
                                                    'attribute_value' => $attributeValue->value
                                                ]);
                                            }
                                        } catch (\Exception $e) {
                                            Log::error('Lỗi khi tạo giá trị thuộc tính', [
                                                'attribute_id' => $attribute->id,
                                                'value' => $value,
                                                'error' => $e->getMessage(),
                                                'trace' => $e->getTraceAsString()
                                            ]);
                                        }
                                    }
                                }
                            } else {
                                Log::info('Không có giá trị thuộc tính được cung cấp', [
                                    'attribute_id' => $attribute->id
                                ]);
                            }

                            // Thêm liên kết vào bảng product_attribute (nếu không có biến thể)
                            if (!$request->filled('variants')) {
                                try {
                                    DB::table('product_attribute')->updateOrInsert(
                                        [
                                            'product_id' => $product->id,
                                            'attribute_id' => $attribute->id,
                                        ],
                                        [
                                            'created_at' => now(),
                                            'updated_at' => now()
                                        ]
                                    );
                                    Log::info('Liên kết product_attribute được tạo hoặc cập nhật', [
                                        'product_id' => $product->id,
                                        'attribute_id' => $attribute->id
                                    ]);
                                } catch (\Exception $e) {
                                    Log::error('Lỗi khi liên kết product_attribute', [
                                        'product_id' => $product->id,
                                        'attribute_id' => $attribute->id,
                                        'error' => $e->getMessage(),
                                        'trace' => $e->getTraceAsString()
                                    ]);
                                }
                            }
                        }
                    }

                    if (!empty($attributeIds)) {
                        try {
                            $product->attributes()->sync($attributeIds);
                            Log::info('Đã đồng bộ thuộc tính cho sản phẩm', [
                                'product_id' => $product->id,
                                'attribute_ids' => $attributeIds
                            ]);
                        } catch (\Exception $e) {
                            Log::error('Lỗi khi đồng bộ thuộc tính', [
                                'product_id' => $product->id,
                                'attribute_ids' => $attributeIds,
                                'error' => $e->getMessage(),
                                'trace' => $e->getTraceAsString()
                            ]);
                        }
                    } else {
                        Log::warning('Không có thuộc tính hợp lệ để đồng bộ', [
                            'product_id' => $product->id,
                            'attributes' => $request->input('attributes')
                        ]);
                    }
                } else {
                    Log::warning('Dữ liệu attributes không phải mảng', [
                        'product_id' => $product->id,
                        'attributes' => $request->input('attributes')
                    ]);
                }
            } else {
                Log::info('Không có thuộc tính được cung cấp', [
                    'product_id' => $product->id
                ]);
            }

            // Xử lý biến thể
            if ($isVariant && $request->filled('variants')) {
                Log::info('Processing variants', ['variants_count' => count($request->variants)]);
                foreach ($request->variants as $index => $variantData) {
                    // Kiểm tra dữ liệu biến thể hợp lệ
                    if (empty($variantData['name']) || empty($variantData['sku']) || empty($variantData['price']) || empty($variantData['purchase_price']) || empty($variantData['sale_price']) || empty($variantData['stock_total'])) {
                        Log::warning('Skipping invalid variant data', [
                            'index' => $index,
                            'variant_data' => $variantData
                        ]);
                        continue;
                    }

                    // Tạo biến thể
                    $variant = ProductVariant::create([
                        'productID' => $product->id,
                        'variant_name' => $variantData['name'],
                        'price' => $variantData['price'] ?? 0,
                        'purchase_price' => $variantData['purchase_price'] ?? 0,
                        'sale_price' => $variantData['sale_price'] ?? 0,
                        'stock' => $variantData['stock_total'] ?? 0,
                        'sku' => $variantData['sku'],
                        'status' => 'active',
                    ]);

                    Log::info('Variant created', [
                        'variant_id' => $variant->id,
                        'variant_name' => $variant->variant_name,
                        'product_id' => $product->id
                    ]);

                    // Lưu kích thước
                    ProductDimension::create([
                        'productID' => $product->id,
                        'variantID' => $variant->id,
                        'length' => $variantData['length'] ?? null,
                        'width' => $variantData['width'] ?? null,
                        'height' => $variantData['height'] ?? null,
                        'weight' => $variantData['weight'] ?? null,
                    ]);

                    Log::info('Dimension created for variant', [
                        'variant_id' => $variant->id,
                        'product_id' => $product->id
                    ]);

                    // Xử lý thuộc tính của biến thể
                    if (!empty($variantData['attributes'])) {
                        foreach ($variantData['attributes'] as $attrIndex => $attrData) {
                            if (empty(trim($attrData['name'])) || empty(trim($attrData['value']))) {
                                Log::warning('Skipping invalid variant attribute', [
                                    'variant_id' => $variant->id,
                                    'attribute_data' => $attrData,
                                    'attr_index' => $attrIndex
                                ]);
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
                    } else {
                        Log::warning('No attributes provided for variant', [
                            'variant_id' => $variant->id,
                            'variant_name' => $variant->variant_name
                        ]);
                    }

                    // Xử lý ảnh biến thể
                    if ($request->hasFile("variant_images.$index")) {
                        foreach ($request->file("variant_images.$index") as $image) {
                            if ($image->isValid()) {
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
                            } else {
                                Log::warning('Invalid image file for variant', [
                                    'variant_id' => $variant->id,
                                    'index' => $index
                                ]);
                            }
                        }
                    }
                }
            } else if (!$isVariant) {
                // Sản phẩm đơn: lưu kích thước mặc định nếu không có biến thể
                ProductDimension::create([
                    'productID' => $product->id,
                    'variantID' => null,
                    'length' => $request->length ?? null,
                    'width' => $request->width ?? null,
                    'height' => $request->height ?? null,
                    'weight' => $request->weight ?? null,
                ]);
            }

            // Lưu ảnh chính
            if ($request->hasFile('main_image') && $request->file('main_image')->isValid()) {
                $path = $request->file('main_image')->store('product_images', 'public');
                ProductImage::create([
                    'productID' => $product->id,
                    'variantID' => null,
                    'image_path' => $path,
                    'is_default' => 1,
                    'display_order' => 0,
                    'alt_text' => "{$product->name} - Ảnh chính",
                ]);
                Log::info('Main image saved', ['image_path' => $path]);
            }

            // Lưu ảnh phụ
            if ($request->hasFile('images')) {
                $displayOrder = 1;
                foreach ($request->file('images') as $image) {
                    if ($image->isValid()) {
                        $path = $image->store('product_images', 'public');
                        ProductImage::create([
                            'productID' => $product->id,
                            'variantID' => null,
                            'image_path' => $path,
                            'is_default' => 0,
                            'display_order' => $displayOrder++,
                            'alt_text' => "{$product->name} - Ảnh phụ {$displayOrder}",
                        ]);
                        Log::info('Additional image saved', ['image_path' => $path]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được tạo thành công và đang chờ admin duyệt.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Product creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->except(['main_image', 'images', 'variant_images'])
            ]);
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
            'attributes.values', // Tải thuộc tính và tất cả giá trị
            'brands', // Tải thương hiệu
            'categories' // Tải danh mục
        ])->findOrFail($id);

        Log::info('Product loaded', [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'brand_ids' => $product->brands->pluck('id')->toArray(),
            'category_ids' => $product->categories->pluck('id')->toArray(),
            'variants_count' => $product->variants->count(),
            'images_count' => $product->images->count(),
            'dimensions_count' => $product->dimensions->count(),
            'attributes_count' => $product->attributes->count(),
        ]);

        // Lấy ID các giá trị thuộc tính được sử dụng bởi biến thể
        $usedValueIds = DB::table('product_variant_attribute_values')
            ->join('product_variants', 'product_variants.id', '=', 'product_variant_attribute_values.product_variant_id')
            ->where('product_variants.productID', $product->id)
            ->pluck('attribute_value_id')
            ->toArray();

        Log::info('Used attribute value IDs', [
            'product_id' => $product->id,
            'used_value_ids' => $usedValueIds,
        ]);

        // Lấy thuộc tính của sản phẩm với các giá trị đã được sử dụng
        $attributes = $product->attributes()->with(['values' => function ($query) use ($usedValueIds) {
            $query->whereIn('id', $usedValueIds)->orWhereNull('id');
        }])->get();

        // Ghi log chi tiết về thuộc tính
        foreach ($attributes as $attribute) {
            Log::info('Product attribute loaded', [
                'attribute_id' => $attribute->id,
                'attribute_name' => $attribute->name,
                'values' => $attribute->values->pluck('value')->toArray(),
                'values_count' => $attribute->values->count(),
                'pivot' => $attribute->pivot ? $attribute->pivot->toArray() : null,
            ]);
        }

        // Tải tất cả thuộc tính có sẵn (để hiển thị trong dropdown)
        $allAttributes = Attribute::with(['values' => function ($query) {
            $query->whereNotNull('value')->where('value', '!=', '');
        }])
            ->whereNotNull('name')
            ->where('name', '!=', '')
            ->get();

        // Ghi log chi tiết về tất cả thuộc tính
        Log::info('All attributes loaded', [
            'all_attributes' => $allAttributes->map(function ($attr) {
                return [
                    'id' => $attr->id,
                    'name' => $attr->name,
                    'values' => $attr->values->pluck('value')->toArray(),
                ];
            })->toArray(),
        ]);

        // Tải danh sách thương hiệu (chỉ lấy trạng thái active)
        $brands = Brand::where('status', 'active')->get();
        // Tải danh sách danh mục
        $categories = Category::all();

        Log::info('Brands, categories, and all attributes loaded', [
            'brands_count' => $brands->count(),
            'categories_count' => $categories->count(),
            'all_attributes_count' => $allAttributes->count(),
            'attributes' => $attributes->map(function ($attr) {
                return [
                    'id' => $attr->id,
                    'name' => $attr->name,
                    'values' => $attr->values->pluck('value')->toArray(),
                ];
            })->toArray(),
        ]);

        return view('admin.products.edit', compact('product', 'attributes', 'allAttributes', 'brands', 'categories'));
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

            // Admin có thể chỉnh sửa bất kỳ sản phẩm nào
            Log::info('Admin updating product', [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'shop_id' => $product->shopID,
                'admin_id' => Auth::id(),
            ]);

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
                'status' => $request->save_draft ? 'draft' : 'pending',
            ]);

            // Xử lý danh mục
            $newCategoryIds = $request->input('category_ids', []);
            Log::info('New category IDs from request', ['category_ids' => $newCategoryIds]);
            $currentCategoryIds = $product->categories()->pluck('category_id')->toArray();
            Log::info('Current category IDs', ['current_category_ids' => $currentCategoryIds]);
            sort($newCategoryIds);
            sort($currentCategoryIds);
            if ($newCategoryIds !== $currentCategoryIds) {
                if (!empty($newCategoryIds)) {
                    $product->categories()->sync($newCategoryIds);
                    Log::info('Categories synced', [
                        'product_id' => $product->id,
                        'category_ids' => $newCategoryIds,
                        'category_names' => Category::whereIn('id', $newCategoryIds)->pluck('name')->toArray(),
                    ]);
                } else {
                    $product->categories()->detach();
                    Log::info('Categories detached', [
                        'product_id' => $product->id,
                        'previous_category_ids' => $currentCategoryIds,
                    ]);
                }
            } else {
                Log::info('No changes in categories, skipping sync.', [
                    'product_id' => $product->id,
                    'category_ids' => $newCategoryIds,
                ]);
            }

            // Xử lý thương hiệu
            $newBrandIds = $request->input('brand_ids', []);
            Log::info('New brand IDs from request', ['brand_ids' => $newBrandIds]);
            $currentBrandIds = $product->brands()->pluck('brand_id')->toArray();
            Log::info('Current brand IDs', ['current_brand_ids' => $currentBrandIds]);
            sort($newBrandIds);
            sort($currentBrandIds);
            if ($newBrandIds !== $currentBrandIds) {
                if (!empty($newBrandIds)) {
                    $product->brands()->sync($newBrandIds);
                    Log::info('Brands synced', [
                        'product_id' => $product->id,
                        'brand_ids' => $newBrandIds,
                        'brand_names' => Brand::whereIn('id', $newBrandIds)->pluck('name')->toArray(),
                    ]);
                } else {
                    $product->brands()->detach();
                    Log::info('Brands detached', [
                        'product_id' => $product->id,
                        'previous_brand_ids' => $currentBrandIds,
                    ]);
                }
            } else {
                Log::info('No changes in brands, skipping sync.', [
                    'product_id' => $product->id,
                    'brand_ids' => $newBrandIds,
                ]);
            }

            // Xử lý thuộc tính sản phẩm
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
                        return (!empty(trim($attr['id'])) || !empty(trim($attr['name']))) && !empty(trim($attr['values']));
                    })
                    ->mapWithKeys(function ($attr) {
                        $name = $attr['id'] === 'new' ? trim($attr['name']) : Attribute::find($attr['id'])->name;
                        return [
                            $name => array_values(collect(explode(',', $attr['values']))
                                ->map(fn($v) => trim($v))
                                ->filter()
                                ->sort()
                                ->toArray())
                        ];
                    })
                    ->toArray();

                if ($newAttributeValues != $existingAttributeValues) {
                    $product->attributes()->detach();
                    Log::info('Old attributes detached', ['product_id' => $product->id]);

                    $attributeIds = [];

                    foreach ($request->input('attributes', []) as $attrData) {
                        if (empty(trim($attrData['values']))) {
                            continue;
                        }

                        $attribute = null;
                        if ($attrData['id'] === 'new' && !empty(trim($attrData['name']))) {
                            // Tạo thuộc tính mới
                            $attribute = Attribute::firstOrCreate(['name' => trim($attrData['name'])]);
                            Log::info('New attribute created', [
                                'attribute_id' => $attribute->id,
                                'attribute_name' => $attribute->name,
                            ]);
                        } elseif (!empty($attrData['id']) && $attrData['id'] !== 'new') {
                            // Sử dụng thuộc tính có sẵn
                            $attribute = Attribute::find($attrData['id']);
                            if (!$attribute) {
                                Log::warning('Attribute not found', ['attribute_id' => $attrData['id']]);
                                continue;
                            }
                        }

                        if ($attribute) {
                            $attributeIds[] = $attribute->id;

                            $values = collect(explode(',', $attrData['values']))
                                ->map(fn($v) => trim($v))
                                ->filter()
                                ->toArray();

                            foreach ($values as $value) {
                                $attributeValue = AttributeValue::firstOrNew([
                                    'attribute_id' => $attribute->id,
                                    'value' => $value,
                                ]);

                                if (!$attributeValue->exists) {
                                    $attributeValue->save();
                                    Log::info('Attribute value created', [
                                        'attribute_id' => $attribute->id,
                                        'attribute_value_id' => $attributeValue->id,
                                        'attribute_value' => $attributeValue->value
                                    ]);
                                }

                                if (!$request->filled('variants')) {
                                    DB::table('product_attribute')->updateOrInsert([
                                        'product_id' => $product->id,
                                        'attribute_id' => $attribute->id,
                                    ]);
                                }
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

            // Xử lý biến thể
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

            // Xử lý ảnh chính và ảnh phụ
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
            return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được cập nhật thành công.');
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

            // Admin có thể xóa bất kỳ sản phẩm nào
            // Tìm sản phẩm cần xóa
            $product = Product::with(['shop', 'variants', 'images', 'dimensions'])->findOrFail($id);

            Log::info('Admin deleting product', [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'shop_id' => $product->shopID,
                'admin_id' => Auth::id(),
            ]);

            Log::info('Starting product soft delete', [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'shop_id' => $product->shopID,
                'admin_id' => Auth::id(),
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
            return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được xóa thành công.');
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
        // Admin có thể tạo sản phẩm cho bất kỳ shop nào, không cần kiểm tra seller
        $skuRule = $isUpdate
            ? 'required|string|max:100|unique:products,sku,' . $productId
            : 'required|string|max:100|unique:products,sku';

        $variantSkuRule = $isUpdate
            ? 'required|string|max:100|distinct'
            : 'required|string|max:100|unique:product_variants,sku';

        // Tạo rule cho tên sản phẩm unique trong shop (nếu có shop_id trong request)
        $nameRule = 'required|string|max:100';
        $shopId = request('shop_id');
        if ($shopId) {
            $nameRule = $isUpdate
                ? 'required|string|max:100|unique:products,name,' . $productId . ',id,shopID,' . $shopId
                : 'required|string|max:100|unique:products,name,NULL,id,shopID,' . $shopId;
        }

        $rules = [
            'name' => $nameRule,
            'description' => 'nullable|string',
            'sku' => $skuRule,
            'brand_ids' => 'nullable|array',
            'brand_ids.*' => 'exists:brand,id',
            'category_ids' => 'required|array|min:1',
            'category_ids.*' => 'exists:categories,id',
            'length' => 'nullable|numeric|min:0',
            'width' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            'weight' => 'nullable|numeric|min:0',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:320',
            'meta_keywords' => 'nullable|string|max:255',
            'product_type' => 'required|in:simple,variant',
            'main_image' => ($isUpdate ? 'nullable' : 'required') . '|image|mimes:jpeg,png,jpg,webp,svg|max:5120',
        ];
        if (request('product_type') === 'variant') {
            $rules = array_merge($rules, [
                'attributes' => 'required|array|min:1',
                'attributes.*.name' => 'nullable|string|max:100',
                'attributes.*.values' => 'nullable|string',
                'variants' => 'required|array|min:1',
                'variants.*.name' => 'required|string|max:100',
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
            ]);
        } else {
            $rules = array_merge($rules, [
                'price' => 'required|numeric|min:0',
                'purchase_price' => 'required|numeric|min:0',
                'sale_price' => 'required|numeric|min:0',
                'stock_total' => 'required|integer|min:0',
            ]);
        }
        return $rules;
    }

    protected function validationMessages()
    {
        return [
            'name.required' => 'Vui lòng nhập tên sản phẩm.',
            'name.max' => 'Tên sản phẩm không được vượt quá 100 ký tự.',
            'name.unique' => 'Tên sản phẩm này đã tồn tại trong shop của bạn.',
            'sku.required' => 'Vui lòng nhập mã SKU.',
            'sku.unique' => 'Mã SKU này đã tồn tại.',
            'brand_ids.array' => 'Thương hiệu phải được chọn dưới dạng danh sách.',
            'brand_ids.*.exists' => 'Thương hiệu được chọn không tồn tại.',
            'category_ids.required' => 'Vui lòng chọn ít nhất một danh mục.',
            'category_ids.min' => 'Vui lòng chọn ít nhất một danh mục.',
            'category_ids.*.exists' => 'Danh mục được chọn không tồn tại.',
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

    /**
     * Lấy tất cả ID của danh mục/thương hiệu cha
     * @param array $ids Mảng ID ban đầu
     * @param string $model Tên model (Brand hoặc Category)
     * @return array
     */
    protected function getAllParentIds($ids, $model)
    {
        $parentIds = [];
        foreach ($ids as $id) {
            $item = $model::find($id);
            if ($item && $item->parent_id) {
                $parentIds[] = $item->parent_id;
                // Gọi đệ quy để lấy tất cả parent IDs
                $parentIds = array_merge($parentIds, $this->getAllParentIds([$item->parent_id], $model));
            }
        }
        return array_unique($parentIds);
    }

    public function destroyMultiple(Request $request)
    {
        $ids = $request->ids;

        if (!$ids || count($ids) == 0) {
            return response()->json(['message' => 'Không có sản phẩm nào được chọn'], 400);
        }

        try {
            \App\Models\Product::whereIn('id', $ids)->delete();
            return response()->json(['message' => 'Đã xóa thành công ' . count($ids) . ' sản phẩm']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Lỗi: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Kiểm tra tên sản phẩm đã tồn tại
     */
    public function checkProductName(Request $request)
    {
        $name = $request->input('name');
        $productId = $request->input('product_id'); // Cho trường hợp edit
        $shopId = $request->input('shop_id'); // Shop ID từ form

        if (empty($name)) {
            return response()->json(['exists' => false, 'message' => '']);
        }

        // Admin kiểm tra tên sản phẩm trong shop được chọn
        if (!$shopId) {
            return response()->json(['exists' => false, 'message' => 'Vui lòng chọn cửa hàng.']);
        }

        $query = Product::where('name', $name)
            ->where('shopID', $shopId);

        // Nếu đang edit, loại trừ sản phẩm hiện tại
        if ($productId) {
            $query->where('id', '!=', $productId);
        }

        $exists = $query->exists();

        return response()->json([
            'exists' => $exists,
            'message' => $exists ? 'Tên sản phẩm này đã tồn tại trong shop được chọn.' : ''
        ]);
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

    /**
     * Duyệt sản phẩm
     */
    public function approveProduct($id)
    {
        try {
            $product = Product::with('shop.owner')->findOrFail($id);
            $product->update(['status' => 'active']);

            Log::info('Product approved by admin', [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'admin_id' => Auth::id()
            ]);

            // Gửi thông báo cho seller
            if ($product->shop && $product->shop->owner) {
                $notification = Notification::create([
                    'sender_id' => Auth::id(),
                    'title' => 'Sản phẩm đã được duyệt',
                    'content' => "Sản phẩm '{$product->name}' của bạn đã được admin duyệt và hiện đang hoạt động.",
                    'receiver_type' => 'shop',
                    'type' => 'system',
                    'priority' => 'normal',
                    'status' => 'active',
                ]);

                NotificationReceiver::create([
                    'notification_id' => $notification->id,
                    'receiver_id' => $product->shop->id,
                    'receiver_type' => 'shop',
                ]);

                Log::info('Notification sent to seller for product approval', [
                    'product_id' => $product->id,
                    'shop_id' => $product->shop->id,
                    'notification_id' => $notification->id
                ]);
            }

            return redirect()->back()->with('success', 'Sản phẩm đã được duyệt thành công.');
        } catch (\Exception $e) {
            Log::error('Error approving product: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi duyệt sản phẩm.');
        }
    }

    /**
     * Từ chối sản phẩm
     */
    public function rejectProduct($id)
    {
        try {
            $product = Product::with('shop.owner')->findOrFail($id);
            $product->update(['status' => 'inactive']);

            Log::info('Product rejected by admin', [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'admin_id' => Auth::id()
            ]);

            // Gửi thông báo cho seller
            if ($product->shop && $product->shop->owner) {
                $notification = Notification::create([
                    'sender_id' => Auth::id(),
                    'title' => 'Sản phẩm bị từ chối',
                    'content' => "Sản phẩm '{$product->name}' của bạn đã bị admin từ chối. Vui lòng kiểm tra lại thông tin sản phẩm.",
                    'receiver_type' => 'shop',
                    'type' => 'system',
                    'priority' => 'high',
                    'status' => 'active',
                ]);

                NotificationReceiver::create([
                    'notification_id' => $notification->id,
                    'receiver_id' => $product->shop->id,
                    'receiver_type' => 'shop',
                ]);

                Log::info('Notification sent to seller for product rejection', [
                    'product_id' => $product->id,
                    'shop_id' => $product->shop->id,
                    'notification_id' => $notification->id
                ]);
            }

            return redirect()->back()->with('success', 'Sản phẩm đã bị từ chối.');
        } catch (\Exception $e) {
            Log::error('Error rejecting product: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi từ chối sản phẩm.');
        }
    }

    /**
     * Duyệt nhiều sản phẩm cùng lúc
     */
    public function approveMultiple(Request $request)
    {
        try {
            $ids = $request->input('ids', []);
            if (empty($ids)) {
                return response()->json(['message' => 'Không có sản phẩm nào được chọn'], 400);
            }

            Product::whereIn('id', $ids)->update(['status' => 'active']);

            Log::info('Multiple products approved by admin', [
                'product_ids' => $ids,
                'admin_id' => Auth::id()
            ]);

            return response()->json(['message' => 'Đã duyệt thành công ' . count($ids) . ' sản phẩm']);
        } catch (\Exception $e) {
            Log::error('Error approving multiple products: ' . $e->getMessage());
            return response()->json(['message' => 'Lỗi: ' . $e->getMessage()], 500);
        }
    }
}
