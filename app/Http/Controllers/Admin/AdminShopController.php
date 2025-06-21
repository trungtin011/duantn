<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminShopController extends Controller
{
    /**
     * Display a list of pending shops for admin approval.
     */
    public function pending()
    {
        $shops = Shop::where('shop_status', 'inactive')->orWhere('shop_status', 'pending')->get();
        return view('admin.shops.pending', compact('shops'));
    }

    /**
     * Approve a shop registration.
     */
    public function approve(Shop $shop)
    {
        DB::beginTransaction();
        try {
            $shop->update(['shop_status' => 'active']);

            // Update user role to seller if not already
            User::where('id', $shop->ownerID)->update(['role' => 'seller']);

            // Send notification to the shop owner
            Notification::create([
                'receiver_user_id' => $shop->ownerID,
                'title' => 'Shop của bạn đã được duyệt!',
                'content' => 'Chúc mừng! Shop "' . $shop->shop_name . '" của bạn đã được Admin duyệt và có thể bắt đầu hoạt động.',
                'type' => 'shop_approval',
                'priority' => 'high',
                'status' => 'unread',
                'receiver_type' => 'user',
            ]);

            DB::commit();
            return back()->with('success', 'Shop ' . $shop->shop_name . ' đã được duyệt thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra khi duyệt shop: ' . $e->getMessage());
        }
    }

    /**
     * Reject a shop registration.
     */
    public function reject(Request $request, Shop $shop)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ], [
            'rejection_reason.required' => 'Lý do từ chối là bắt buộc.',
        ]);

        DB::beginTransaction();
        try {
            $shop->update([
                'shop_status' => 'banned', // Assuming 'banned' for rejected or you can add a 'rejected' status
                'rejection_reason' => $request->rejection_reason,
            ]);

            // Send notification to the shop owner
            Notification::create([
                'receiver_user_id' => $shop->ownerID,
                'title' => 'Đăng ký Shop của bạn bị từ chối',
                'content' => 'Rất tiếc, shop "' . $shop->shop_name . '" của bạn đã bị từ chối. Lý do: ' . $request->rejection_reason,
                'type' => 'shop_rejection',
                'priority' => 'high',
                'status' => 'unread',
                'receiver_type' => 'user',
            ]);

            DB::commit();
            return back()->with('success', 'Shop ' . $shop->shop_name . ' đã bị từ chối thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra khi từ chối shop: ' . $e->getMessage());
        }
    }
} 