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

class NotificationsControllers extends Controller
{
    public function index(Request $request){
        $query = Notification::query();

        if ($request->receiver_type && $request->receiver_type !== 'all') {
            $query->where('receiver_type', $request->receiver_type);
        }

        $rawNotifications = $query
            ->select('id', 'title', 'content', 'sender_id', 'receiver_type', 'priority', 'type', 'status', 'created_at', 'updated_at', 'receiver_user_id', 'receiver_shop_id')
            ->orderBy('created_at', 'desc')
            ->get();

        $grouped = $rawNotifications->groupBy('title')
            ->map(function ($group) {
                return [
                    'id' => $group->first()->id,
                    'title' => $group->first()->title,
                    'content' => $group->first()->content,
                    'sender' => $group->first()->sender,
                    'receiver_type' => $group->first()->receiver_type,
                    'priority' => $group->first()->priority,
                    'type' => $group->first()->type,
                    'status' => $group->first()->status,
                    'created_at' => $group->first()->created_at,
                    'updated_at' => $group->first()->updated_at,
                    'receiver_ids' => $group->map(function ($notification) {
                        return $notification->receiver_user_id ?? $notification->receiver_shop_id;
                    })->filter()->unique()->values(),
                    'first_id' => $group->first()->id
                ];
            })
            ->values();

        $perPage = 10;
        $currentPage = $request->input('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        $total = $grouped->count();
        $paginatedItems = $grouped->slice($offset, $perPage);
        $notifications = new LengthAwarePaginator(
            $paginatedItems,
            $total,
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('admin.notifications.index', compact('notifications'));
    }

    public function create(){
        $users = User::where('role', 'customer')->get();
        $shops = Shop::where('shop_status', 'active')->get();
        return view('admin.notifications.create', compact('users', 'shops'));
    }

    public function store(NotificationsRequest $request){  
        $receiverType = $request->receiver_type;
        $directTo = $request->direct_to ?? null;
        $priority = $request->priority;
        $title = $request->title;
        $content = $request->content;
        $type = $request->type;
        
        $expireDate = $this->expireDateHandle($priority);
    
        if ($directTo) {
            $notifications = $this->storeNotificationToSpecific($receiverType, $title, $content, $priority,  $expireDate, $type, $directTo);
        } else {
            $getGroupType = $this->getGroupType($receiverType);
            $notifications = $this->storeNotification($receiverType, $title, $content, $priority, $expireDate, $getGroupType, $type);
        }
        

        if($notifications){
            return redirect()->route('admin.notifications.index')->with('success', 'Notification created successfully');
        } else{
            return redirect()->route('admin.notifications.index')->with('error', 'Notification creation failed');
        }
    }
    
    public function expireDateHandle($priority){
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
    
    public function getGroupType($receiver_type){

            if ($receiver_type == 'user') {
                return User::where('role', 'customer')->pluck('id');
            }
            if ($receiver_type == 'shop') {
                return Shop::where('shop_status', 'active')
                    ->with('owner')
                    ->pluck('id');
            }
            if($receiver_type == 'all'){
                return User::all()->pluck('id');
            }
            if($receiver_type == 'admin'){
                return User::where('role', 'admin')->pluck('id');
            }
            if($receiver_type == 'employee'){
                return User::where('role', 'employee')->pluck('id');
            }
            return collect(); 
    }
    
    public function storeNotificationToSpecific($receiver_type, $title, $content, $priority, $expireDate, $type, $receiverId){
        $notificationData = [
            'sender_id' => Auth::user()->id,
            'title' => $title,
            'content' => $content,
            'receiver_type' => $receiver_type,
            'type' => $type,
            'priority' => $priority,
            'expired_at' => $expireDate,
        ];
    
        if ($receiver_type == 'user') {
            $notificationData['receiver_user_id'] = $receiverId;
        } elseif ($receiver_type == 'shop') {
            $notificationData['receiver_shop_id'] = $receiverId;
        } elseif ($receiver_type == 'admin') {
            $notificationData['receiver_user_id'] = $receiverId;
        } elseif ($receiver_type == 'employee') {
            $notificationData['receiver_user_id'] = $receiverId;
        }
    
        return Notification::create($notificationData);
    }
    
    public function storeNotification($receiver_type, $title, $content, $priority, $expireDate, $getGroupType, $type){
        $notifications = [];
        foreach($getGroupType as $group){
            $notificationData = [
                'sender_id' => Auth::user()->id,
                'title' => $title,
                'content' => $content,
                'receiver_type' => $receiver_type,
                'type' => $type,
                'priority' => $priority,
                'expired_at' => $expireDate,
            ];
    
            if ($receiver_type == 'user') {
                $notificationData['receiver_user_id'] = $group;
            } elseif ($receiver_type == 'shop') {
                $notificationData['receiver_shop_id'] = $group;
            } elseif ($receiver_type == 'admin') {
                $notificationData['receiver_user_id'] = $group;
            } elseif ($receiver_type == 'employee') {
                $notificationData['receiver_user_id'] = $group;
            }
            $notifications[] = Notification::create($notificationData);
        }
        return $notifications;
    }

    public function destroy($id){
        $notification = Notification::find($id);
        if($notification){
            $notification->delete();
        }
        return redirect()->route('admin.notifications.index')->with('success', 'Notification deleted successfully');
    }


}
