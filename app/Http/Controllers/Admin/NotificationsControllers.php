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
    public function index(Request $request){

        $notifications = Notification::paginate(10);
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
            'status' => 'pending',
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
                'status' => 'pending',
            ];
                $notification = Notification::create($notificationData);
                $notification_receiver = NotificationReceiver::create([
                    'notification_id' => $notification->id,
                    'receiver_id' => $group,
                    'receiver_type' => $receiver_type,
                ]);
                $notifications[] = $notification;
                event(new NotificationEvent($notification));
            }
        return $notifications;
    }

    public function destroy($id){
        $notification = Notification::find($id);
        if($notification){
            Notification::where('title', $notification->title)->delete();
            return redirect()->route('admin.notifications.index')->with('success', 'Notification deleted successfully');
        }
        return redirect()->route('admin.notifications.index')->with('error', 'Notification deleted failed');
    }


}
