<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('receiver_user_id', Auth::id())
            ->orWhere('receiver_type', 'all')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->is_read = 'read';
        $notification->read_at = now();
        $notification->save();

        return back();
    }

    public function markAllAsRead()
    {
        Notification::where('receiver_user_id', Auth::id())
            ->where('is_read', 'unread')
            ->update([
                'is_read' => 'read',
                'read_at' => now()
            ]);

        return back();
    }

    public function destroy($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->delete();

        return back();
    }
} 