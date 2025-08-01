<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Http\Requests\NotificationsRequest;
use App\Models\User;
use App\Models\Shop;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Events\NotificationEvent;
use App\Models\NotificationReceiver;


class NotificationsControllers extends Controller
{
    public function index(Request $request)
    {
        $query = Notification::query()->where('sender_id', '!=', null)->orderBy('created_at', 'desc');

        if ($request->has('search') && !empty($request->search)) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->has('receiver_type') && $request->receiver_type !== 'all') {
            $query->where('receiver_type', $request->receiver_type);
        }

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $notifications = $query->paginate(10);

        return view('admin.notifications.index', compact('notifications'));
    }

    public function create()
    {
        $users = User::where('role', 'customer')->get();
        $shops = Shop::where('shop_status', 'active')->get();
        return view('admin.notifications.create', compact('users', 'shops'));
    }

    public function store(NotificationsRequest $request)
    {
        $receiverType = $request->receiver_type;
        $directTo = $request->direct_to ?? null;
        $priority = $request->priority;
        $title = $request->title;
        $content = $request->content;
        $type = $request->type;
        $imagePath = $request->image_path ?? null;

        if (!is_null($directTo) && $directTo !== '') {
            $validateDirectTo = function($receiverType, $directTo) {
                if ($receiverType === 'user') {
                    $exists = User::where('id', $directTo)->where('role', 'customer')->where('status', 'active')->exists();
                    if (!$exists) {
                        return ['status' => false, 'message' => 'Người dùng nhận không tồn tại hoặc không hợp lệ.'];
                    }
                } elseif ($receiverType === 'shop') {
                    $exists = Shop::where('id', $directTo)->where('shop_status', 'active')->exists();
                    if (!$exists) {
                        return ['status' => false, 'message' => 'Cửa hàng nhận không tồn tại hoặc không hợp lệ.'];
                    }
                } elseif ($receiverType === 'admin') {
                    $exists = User::where('id', $directTo)->where('role', 'admin')->where('status', 'active')->exists();
                    if (!$exists) {
                        return ['status' => false, 'message' => 'Quản trị viên nhận không tồn tại hoặc không hợp lệ.'];
                    }
                } else {
                    return ['status' => false, 'message' => 'Loại người nhận không hợp lệ cho gửi trực tiếp.'];
                }
                return ['status' => true];
            };

            $validateResult = $validateDirectTo($receiverType, $directTo);
            if (!$validateResult['status']) {
                return redirect()->back()->withInput()->withErrors(['direct_to' => $validateResult['message']]);
            }
        }

        if ($imagePath) {
            $imagePath = $this->saveImage($imagePath);
        }

        $expireDate = $this->expireDateHandle($priority);

        if ($directTo) {
            $notifications = $this->storeNotificationToSpecific($receiverType, $title, $content, $priority,  $expireDate, $type, $directTo, $imagePath);
        } else {
            $getGroupType = $this->getGroupType($receiverType);
            $notifications = $this->storeNotification($receiverType, $title, $content, $priority, $expireDate, $getGroupType, $type, $imagePath);
        }

        if ($notifications) {
            return redirect()->route('admin.notifications.index')->with('success', 'Notification created successfully');
        } else {
            return redirect()->route('admin.notifications.index')->with('error', 'Notification creation failed');
        }
    }
    public function expireDateHandle($priority)
    {
        if ($priority == 'low') {
            return now()->addDays(10);
        }
        if ($priority == 'normal') {
            return now()->addDays(20);
        }
        if ($priority == 'high') {
            return now()->addDays(30);
        }
    }

    public function saveImage($imagePath)
    {
        $image = $imagePath;
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('images/notifications'), $imageName);
        return $imageName;
    }

    public function getGroupType($receiver_type)
    {
        if ($receiver_type == 'user') {
            return User::where('role', 'customer')->where('status', 'active')->pluck('id');
        }
        if ($receiver_type == 'shop') {
            return Shop::where('shop_status', 'active')
                ->with('owner')
                ->pluck('id');
        }
        if ($receiver_type == 'all') {
            return User::where('status', 'active')->pluck('id');
        }
        if ($receiver_type == 'admin') {
            return User::where('role', 'admin')->where('status', 'active')->pluck('id');
        }

        return collect();
    }

    public function storeNotificationToSpecific($receiver_type, $title, $content, $priority, $expireDate, $type, $receiverId, $imagePath)
    {
        $notificationData = [
            'sender_id' => Auth::user()->id,
            'title' => $title,
            'content' => $content,
            'receiver_type' => $receiver_type,
            'type' => $type,
            'priority' => $priority,
            'expired_at' => $expireDate,
            'status' => 'pending',
            'image_path' => $imagePath,
            'reference_id' => $receiverId,
        ];

        $notification = Notification::create($notificationData);
        $notification_receiver = NotificationReceiver::create([
            'notification_id' => $notification->id,
            'receiver_id' => $receiverId,
            'receiver_type' => $receiver_type,
        ]);

        event(new NotificationEvent($notification));
        return $notification;
    }

    public function storeNotification($receiver_type, $title, $content, $priority, $expireDate, $getGroupType, $type, $imagePath)
    {
        // Tạo notification chỉ 1 lần
        $notificationData = [
            'sender_id' => Auth::user()->id,
            'title' => $title,
            'content' => $content,
            'receiver_type' => $receiver_type,
            'type' => $type,
            'priority' => $priority,
            'expired_at' => $expireDate,
            'status' => 'pending',
            'image_path' => $imagePath,
        ];

        $notification = Notification::create($notificationData);

        foreach ($getGroupType as $group) {
            $notification_receiver = NotificationReceiver::create([
                'notification_id' => $notification->id,
                'receiver_id' => $group,
                'receiver_type' => $receiver_type,
            ]);
        }
        return $notification;
    }

    public function toggleStatus($id)
    {        
        $notification = Notification::find($id);
        if (!$notification) {
            return response()->json(['success' => false, 'message' => 'Notification not found'], 404);
        }
        
        if ($notification->status == 'active') {
            $notification->status = 'inactive';
        } else {
            $notification->status = 'active';
        }
        $notification->save();
        event(new NotificationEvent($notification));

        return response()->json([
            'success' => true, 
            'message' => 'Notification status updated successfully',
            'new_status' => $notification->status
        ]);
    }

    public function markAsRead($id)
    {
        $notification = Notification::find($id);
        if (!$notification) {
            return response()->json(['success' => false, 'message' => 'Notification not found'], 404);
        }
        
        $notification_receiver = NotificationReceiver::where('notification_id', $notification->id)->where('receiver_id', Auth::user()->id)->first();
        $notification_receiver->is_read = true;
        $notification_receiver->read_at = now();
        $notification_receiver->save();

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read'
        ]);
    }

    public function destroy($id)
    {
        $notification = Notification::find($id);
        if ($notification) {
            Notification::where('title', $notification->title)->delete();
            NotificationReceiver::where('notification_id', $notification->id)->delete();
            return redirect()->route('admin.notifications.index')->with('success', 'Notification deleted successfully');
        }
        return redirect()->route('admin.notifications.index')->with('error', 'Notification deleted failed');
    }
}
