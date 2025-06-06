<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationControllers extends Controller
{
    public function index(Request $request)
    {
        $notifications = Notification::where('receiver_user_id', Auth::user()->id)
            ->orWhere('receiver_type', 'user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.notifications.index', compact('notifications'));
    }

    public function markAsRead(Request $request)
    {
        $notification = Notification::find($request->id);
        $notification->is_read = 'read';
        $notification->save();
        return response()->json(['success' => true]);
    }
}
