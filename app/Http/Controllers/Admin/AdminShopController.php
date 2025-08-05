<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\BusinessLicense;
use App\Models\ShopAddress;
use App\Models\ShopShippingOption;
use App\Models\Seller;
use App\Models\IdentityVerification;
use Illuminate\Support\Facades\Storage;
use App\Enums\ShopStatus;

class AdminShopController extends Controller
{
    /**
     * Display a list of all shops with filtering and pagination.
     */
    public function index(Request $request)
    {
        $query = Shop::with(['owner', 'shopAddress']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('shop_status', $request->status);
        }

        // Filter by search term
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('shop_name', 'like', "%{$search}%")
                  ->orWhere('shop_email', 'like', "%{$search}%")
                  ->orWhere('shop_phone', 'like', "%{$search}%")
                  ->orWhereHas('owner', function($ownerQuery) use ($search) {
                      $ownerQuery->where('fullname', 'like', "%{$search}%");
                  });
            });
        }

        // Sort by
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $shops = $query->paginate(15);
        
        // Get statistics - đảm bảo xử lý trường hợp null
        $stats = [
            'total' => Shop::count(),
            'active' => Shop::where('shop_status', 'active')->count(),
            'inactive' => Shop::where('shop_status', 'inactive')->count(),
            'banned' => Shop::where('shop_status', 'banned')->count(),
        ];

        return view('admin.shops.index', compact('shops', 'stats'));
    }

    /**
     * Display a list of inactive shops for admin approval.
     */
    public function pending()
    {
        $shops = Shop::where('shop_status', 'inactive')
                    ->with(['owner', 'shopAddress'])
                    ->orderBy('created_at', 'desc')
                    ->paginate(15);
        
        return view('admin.shops.pending', compact('shops'));
    }

    /**
     * Display active shops.
     */
    public function active()
    {
        $shops = Shop::where('shop_status', 'active')
                    ->with(['owner', 'shopAddress'])
                    ->orderBy('created_at', 'desc')
                    ->paginate(15);
        
        return view('admin.shops.active', compact('shops'));
    }

    /**
     * Display banned shops.
     */
    public function banned()
    {
        $shops = Shop::where('shop_status', 'banned')
                    ->with(['owner', 'shopAddress'])
                    ->orderBy('created_at', 'desc')
                    ->paginate(15);
        
        return view('admin.shops.banned', compact('shops'));
    }

    /**
     * Display shop analytics and statistics.
     */
    public function analytics()
    {
        // Shop growth over time (last 6 months)
        $shopGrowth = Shop::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Status distribution
        $statusDistribution = Shop::selectRaw('shop_status, COUNT(*) as count')
            ->groupBy('shop_status')
            ->get();

        // Top performing shops by sales
        $topShops = Shop::with('owner')
            ->where('shop_status', 'active')
            ->orderBy('total_sales', 'desc')
            ->limit(10)
            ->get();

        // Recent shop registrations
        $recentShops = Shop::with('owner')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.shops.analytics', compact(
            'shopGrowth', 
            'statusDistribution', 
            'topShops', 
            'recentShops'
        ));
    }

    /**
     * Display the specified shop registration details.
     */
    public function show(Shop $shop)
    {
        $shop->load('shopAddress', 'shopShippingOptions', 'owner.seller.businessLicense', 'owner.seller.identityVerification');

        return view('admin.shops.show', compact('shop'));
    }

    /**
     * Approve a shop registration.
     */
    public function approve(Shop $shop)
    {
        DB::beginTransaction();
        try {
            $shop->update(['shop_status' => ShopStatus::ACTIVE]);
            
            // Create notification for shop owner
            Notification::create([
                'user_id' => $shop->ownerID,
                'title' => 'Cửa hàng đã được duyệt',
                'content' => "Cửa hàng '{$shop->shop_name}' của bạn đã được duyệt và có thể bắt đầu hoạt động.",
                'type' => 'shop_approval',
                'receiver_type' => 'seller',
                'link' => route('seller.products.index')
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Cửa hàng đã được duyệt thành công!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi duyệt cửa hàng!');
        }
    }

    /**
     * Reject a shop registration.
     */
    public function reject(Request $request, Shop $shop)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        DB::beginTransaction();
        try {
            $shop->update(['shop_status' => ShopStatus::BANNED]);
            
            // Create notification for shop owner
            Notification::create([
                'user_id' => $shop->ownerID,
                'title' => 'Cửa hàng bị từ chối',
                'content' => "Cửa hàng '{$shop->shop_name}' của bạn đã bị từ chối. Lý do: {$request->rejection_reason}",
                                        'type' => 'shop_rejection',
                        'receiver_type' => 'seller',
                        'link' => route('seller.products.index')
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Cửa hàng đã bị từ chối!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi từ chối cửa hàng!');
        }
    }

    /**
     * Deactivate a shop (change to inactive).
     */
    public function deactivate(Shop $shop)
    {
        DB::beginTransaction();
        try {
            // Kiểm tra trạng thái hiện tại
            if ($shop->shop_status === ShopStatus::INACTIVE) {
                return redirect()->back()->with('error', 'Cửa hàng đã bị tạm ngưng rồi!');
            }

            // Cập nhật trạng thái
            $shop->update(['shop_status' => ShopStatus::INACTIVE]);
            
            // Tạo thông báo cho chủ cửa hàng (chỉ khi có ownerID)
            if ($shop->ownerID) {
                try {
                    Notification::create([
                        'user_id' => $shop->ownerID,
                        'title' => 'Cửa hàng bị tạm ngưng',
                        'content' => "Cửa hàng '{$shop->shop_name}' của bạn đã bị tạm ngưng hoạt động.",
                        'type' => 'shop_deactivation',
                        'receiver_type' => 'seller',
                        'link' => route('seller.products.index')
                    ]);
                } catch (\Exception $notificationError) {
                    // Log lỗi notification nhưng không rollback toàn bộ transaction
                    Log::error('Lỗi tạo notification: ' . $notificationError->getMessage());
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Cửa hàng đã bị tạm ngưng!');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Lỗi khi tạm ngưng cửa hàng: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi tạm ngưng cửa hàng! Lỗi: ' . $e->getMessage());
        }
    }

    /**
     * Ban a shop permanently.
     */
    public function ban(Shop $shop)
    {
        DB::beginTransaction();
        try {
            // Kiểm tra trạng thái hiện tại
            if ($shop->shop_status === ShopStatus::BANNED) {
                return redirect()->back()->with('error', 'Cửa hàng đã bị cấm rồi!');
            }

            // Cập nhật trạng thái
            $shop->update(['shop_status' => ShopStatus::BANNED]);
            
            // Tạo thông báo cho chủ cửa hàng (chỉ khi có ownerID)
            if ($shop->ownerID) {
                try {
                    Notification::create([
                        'user_id' => $shop->ownerID,
                        'title' => 'Cửa hàng bị cấm',
                        'content' => "Cửa hàng '{$shop->shop_name}' của bạn đã bị cấm hoạt động vĩnh viễn.",
                        'type' => 'shop_ban',
                        'receiver_type' => 'seller',
                        'link' => route('seller.products.index')
                    ]);
                } catch (\Exception $notificationError) {
                    // Log lỗi notification nhưng không rollback toàn bộ transaction
                    Log::error('Lỗi tạo notification: ' . $notificationError->getMessage());
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Cửa hàng đã bị cấm!');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Lỗi khi cấm cửa hàng: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi cấm cửa hàng! Lỗi: ' . $e->getMessage());
        }
    }

    /**
     * Reactivate an inactive shop.
     */
    public function reactivate(Shop $shop)
    {
        DB::beginTransaction();
        try {
            // Kiểm tra trạng thái hiện tại
            if ($shop->shop_status === ShopStatus::ACTIVE) {
                return redirect()->back()->with('error', 'Cửa hàng đã đang hoạt động rồi!');
            }

            // Cập nhật trạng thái
            $shop->update(['shop_status' => ShopStatus::ACTIVE]);
            
            // Tạo thông báo cho chủ cửa hàng (chỉ khi có ownerID)
            if ($shop->ownerID) {
                try {
                    Notification::create([
                        'user_id' => $shop->ownerID,
                        'title' => 'Cửa hàng đã được kích hoạt lại',
                        'content' => "Cửa hàng '{$shop->shop_name}' của bạn đã được kích hoạt lại và có thể hoạt động bình thường.",
                        'type' => 'shop_reactivation',
                        'receiver_type' => 'seller',
                        'link' => route('seller.products.index')
                    ]);
                } catch (\Exception $notificationError) {
                    // Log lỗi notification nhưng không rollback toàn bộ transaction
                    Log::error('Lỗi tạo notification: ' . $notificationError->getMessage());
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Cửa hàng đã được kích hoạt lại!');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Lỗi khi kích hoạt lại cửa hàng: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi kích hoạt lại cửa hàng! Lỗi: ' . $e->getMessage());
        }
    }

    /**
     * Unban a banned shop (change from banned to active).
     */
    public function unban(Shop $shop)
    {
        DB::beginTransaction();
        try {
            // Kiểm tra trạng thái hiện tại
            if ($shop->shop_status !== ShopStatus::BANNED) {
                return redirect()->back()->with('error', 'Cửa hàng không ở trạng thái bị cấm!');
            }

            // Cập nhật trạng thái
            $shop->update(['shop_status' => ShopStatus::ACTIVE]);
            
            // Tạo thông báo cho chủ cửa hàng (chỉ khi có ownerID)
            if ($shop->ownerID) {
                try {
                    Notification::create([
                        'user_id' => $shop->ownerID,
                        'title' => 'Cửa hàng đã được mở lại',
                        'content' => "Cửa hàng '{$shop->shop_name}' của bạn đã được mở lại và có thể hoạt động bình thường.",
                        'type' => 'shop_unban',
                        'receiver_type' => 'seller',
                        'link' => route('seller.products.index')
                    ]);
                } catch (\Exception $notificationError) {
                    // Log lỗi notification nhưng không rollback toàn bộ transaction
                    Log::error('Lỗi tạo notification: ' . $notificationError->getMessage());
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Cửa hàng đã được mở lại!');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Lỗi khi mở lại cửa hàng: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi mở lại cửa hàng! Lỗi: ' . $e->getMessage());
        }
    }

    /**
     * Update shop information.
     */
    public function update(Request $request, Shop $shop)
    {
        $validated = $request->validate([
            'shop_name' => 'required|string|max:100',
            'shop_phone' => 'nullable|string|max:20',
            'shop_email' => 'nullable|email|max:100',
            'shop_description' => 'nullable|string|max:500',
            'shop_status' => 'required|in:active,inactive,banned',
        ]);

        $shop->update($validated);
        
        return redirect()->back()->with('success', 'Thông tin cửa hàng đã được cập nhật!');
    }

    /**
     * Delete a shop (soft delete).
     */
    public function destroy(Shop $shop)
    {
        DB::beginTransaction();
        try {
            // Soft delete the shop
            $shop->delete();
            
            // Create notification for shop owner
            Notification::create([
                'user_id' => $shop->ownerID,
                'title' => 'Cửa hàng đã bị xóa',
                'content' => "Cửa hàng '{$shop->shop_name}' của bạn đã bị xóa khỏi hệ thống.",
                                        'type' => 'shop_deletion',
                        'receiver_type' => 'seller',
                        'link' => route('seller.products.index')
            ]);

            DB::commit();
            return redirect()->route('admin.shops.index')->with('success', 'Cửa hàng đã được xóa!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi xóa cửa hàng!');
        }
    }
}
