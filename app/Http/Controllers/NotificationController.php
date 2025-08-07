<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\NotificationReceiver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $query = Notification::query();
        
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }
        
        // Lấy tất cả notifications
        $allNotifications = $query->whereHas('receiver', function($q) {
            $q->where('receiver_id', Auth::id())
              ->where('receiver_type', 'user');
        })
        ->orWhere('receiver_type', 'all')
        ->orderBy('created_at', 'desc')
        ->get();

        // Gộp notifications order theo order_code
        $groupedNotifications = collect();
        
        foreach ($allNotifications as $notification) {
            if ($notification->type === 'order' && $notification->order_code) {
                // Kiểm tra xem đã có notification cho order_code này chưa
                $existingNotification = $groupedNotifications->first(function($item) use ($notification) {
                    return $item->type === 'order' && $item->order_code === $notification->order_code;
                });
                
                if (!$existingNotification) {
                    // Thêm notification đầu tiên cho order_code này
                    $groupedNotifications->push($notification);
                } else {
                    // Nếu notification mới hơn, thay thế notification cũ
                    if ($notification->created_at->gt($existingNotification->created_at)) {
                        $groupedNotifications = $groupedNotifications->map(function($item) use ($notification, $existingNotification) {
                            if ($item->id === $existingNotification->id) {
                                return $notification;
                            }
                            return $item;
                        });
                    }
                }
            } else {
                // Với các notification không phải order, thêm bình thường
                $groupedNotifications->push($notification);
            }
        }

        // Sắp xếp lại theo thời gian tạo mới nhất
        $groupedNotifications = $groupedNotifications->sortByDesc('created_at');
        
        // Tạo pagination cho collection
        $perPage = 15;
        $currentPage = $request->get('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        
        $notifications = new \Illuminate\Pagination\LengthAwarePaginator(
            $groupedNotifications->slice($offset, $perPage),
            $groupedNotifications->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        
        // Nếu là notification order, mark tất cả notifications có cùng order_code
        if ($notification->type === 'order' && $notification->order_code) {
            $relatedNotifications = Notification::where('type', 'order')
                ->where('order_code', $notification->order_code)
                ->pluck('id');
                
            foreach ($relatedNotifications as $notificationId) {
                $existingReceiver = NotificationReceiver::where('notification_id', $notificationId)
                    ->where('receiver_id', Auth::user()->id)
                    ->first();
                    
                if (!$existingReceiver) {
                    NotificationReceiver::create([
                        'notification_id' => $notificationId,
                        'receiver_id' => Auth::user()->id,
                        'receiver_type' => 'user',
                        'is_read' => true,
                        'read_at' => now()
                    ]);
                } else {
                    NotificationReceiver::where('notification_id', $notificationId)
                        ->where('receiver_id', Auth::user()->id)
                        ->update([
                            'is_read' => true,
                            'read_at' => now()
                        ]);
                }
            }
        } else {
            // Xử lý notification thường
            $existingReceiver = NotificationReceiver::where('notification_id', $notification->id)
                ->where('receiver_id', Auth::user()->id)
                ->first();
                
            if (!$existingReceiver) {
                NotificationReceiver::create([
                    'notification_id' => $notification->id,
                    'receiver_id' => Auth::user()->id,
                    'receiver_type' => 'user',
                    'is_read' => true,
                    'read_at' => now()
                ]);
            } else {
                NotificationReceiver::where('notification_id', $notification->id)
                    ->where('receiver_id', Auth::user()->id)
                    ->update([
                        'is_read' => true,
                        'read_at' => now()
                    ]);
            }
        }
        
        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        // Mark all notifications as read for current user
        NotificationReceiver::where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $notification = Notification::findOrFail($id);
        
        // Only allow user to delete their own notification receivers
        NotificationReceiver::where('notification_id', $id)
            ->where('receiver_id', Auth::id())
            ->delete();

        return response()->json(['success' => true]);
    }
} 