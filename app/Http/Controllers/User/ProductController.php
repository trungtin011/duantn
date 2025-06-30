<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Shop;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\ProductReview;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function show(Request $request, $slug)
    {
        $ratingFilter = $request->input('rating');
        $reviews = ProductReview::with('user')->whereHas('product', function ($query) use ($slug) {
            $query->where('slug', $slug);
        })->get();

        $product = Product::with([
            'images',
            'reviews.user',
            'variants.attributeValues.attribute',
            'variants.images',  
            'reviews.likes',
            'shop.coupons',
        ])->where('slug', $slug)->firstOrFail();

        // Gán hình ảnh, giá, và số lượng của biến thể
        $colorImages = [];
        $variantData = [];
        $selectedVariant = null;
        if ($request->has('variant_id')) {
            $selectedVariant = $product->variants->find($request->variant_id); // Lấy biến thể từ request nếu có
        } elseif ($product->variants->isNotEmpty()) {
            $selectedVariant = $product->variants->first(); // Lấy biến thể đầu tiên làm mặc định
        }

        if ($product->variants->isNotEmpty()) {
            foreach ($product->variants as $variant) {
                $color = $variant->attributeValues->where('attribute.name', 'Màu sắc')->first()->value ?? null;
                if ($color) {
                    $image = $variant->images->first()->image_path ?? null;
                    $colorImages[$color] = $image ?: asset('images/default_product_image.png');
                    $variantData[$variant->id] = [
                        'price' => $variant->getCurrentPriceAttribute(),
                        'original_price' => $variant->price,
                        'stock' => $variant->stock,
                        'image' => $image ?: asset('images/default_product_image.png'),
                        'discount_percentage' => $variant->getDiscountPercentageAttribute(),
                    ];
                }
            }
        }

        $viewed = session()->get('viewed_products', []);
        $viewed = array_unique(array_merge([$product->id], $viewed));
        session()->put('viewed_products', array_slice($viewed, 0, 10));

        $recentProducts = Product::whereIn('id', $viewed)->where('id', '!=', $product->id)->with('images')->get();
        $logoPath = $product->shop ? Storage::url($product->shop->logo) : asset('images/default_shop_logo.png');

        $hasPurchased = Auth::check() && $product->orders()->where('userID', Auth::id())->exists();

        $filter = $request->input('filter');
        $reviews = $product->reviews;

        if ($filter === 'images') {
            $filteredReviews = $reviews->filter(fn($r) => $r->images && $r->images->count() > 0);
        } elseif (Str::startsWith($filter, 'star-')) {
            $rating = (int) Str::after($filter, 'star-');
            $filteredReviews = $reviews->where('rating', $rating);
        } else {
            $filteredReviews = $reviews;
        }

        $filteredReviews = $filteredReviews->sortByDesc('created_at');

        if ($request->ajax()) {
            return view('partials.review_list', ['reviews' => $filteredReviews]);
        }

        return view('user.product.product_detail', [
            'product' => $product,
            'filteredReviews' => $filteredReviews,
            'ratingFilter' => $ratingFilter,
            'recentProducts' => $recentProducts,
            'shop' => $product->shop,
            'logoPath' => $logoPath,
            'hasPurchased' => $hasPurchased,
            'reviews' => $reviews,
            'colorImages' => $colorImages,
            'variantData' => $variantData,
            'selectedVariant' => $selectedVariant, // Truyền biến selectedVariant
        ]);
    }

    public function reportProduct(Request $request, Product $product)
    {
        $request->validate([
            'report_type' => 'required|in:product_violation,fake_product,copyright,other',
            'report_content' => 'required|string|max:1000',
            'evidence.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,mov|max:20480', // Max 20MB per file
            'is_anonymous' => 'boolean',
        ]);

        $evidencePaths = [];
        if ($request->hasFile('evidence')) {
            foreach ($request->file('evidence') as $file) {
                $path = $file->store('reports/evidence', 'public');
                $evidencePaths[] = Storage::url($path);
            }
        }

        $priority = 'medium';
        if (in_array($request->report_type, ['fake_product', 'copyright'])) {
            $priority = 'high';
        }

        try {
            Log::info('Attempting to create report.', [
                'reporter_id' => Auth::id(),
                'product_id' => $product->id,
                'user_id' => Auth::user()->id,
                'shop_id' => $product->shopID,
                'report_type' => $request->report_type,
                'report_content' => $request->report_content,
                'evidence' => !empty($evidencePaths) ? $evidencePaths : null,
                'priority' => $priority,
                'status' => 'pending',
                'is_anonymous' => $request->boolean('is_anonymous', false),
            ]);

            Report::create([
                'reporter_id' => Auth::id(),
                'product_id' => $product->id,
                'user_id' => Auth::user()->id,
                'shop_id' => $product->shopID,
                'report_type' => $request->report_type,
                'report_content' => $request->report_content,
                'evidence' => !empty($evidencePaths) ? json_encode($evidencePaths) : null,
                'priority' => $priority,
                'status' => 'pending',
                'is_anonymous' => $request->boolean('is_anonymous', false),
            ]);

            Log::info('Report created successfully for product: ' . $product->id);
        } catch (\Exception $e) {
            Log::error('Error creating report for product: ' . $product->id, [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi gửi báo cáo. Vui lòng thử lại.');
        }

        return redirect()->back()->with('success', 'Báo cáo của bạn đã được gửi thành công. Chúng tôi sẽ xem xét sớm nhất có thể.');
    }
}