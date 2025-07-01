<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\NotificationReceiver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $query = Notification::query();
        
        // Filter by type if provided
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }
        
        // Get notifications for current user
        $notifications = $query->whereHas('receiver', function($q) {
            $q->where('receiver_id', Auth::id())
              ->where('receiver_type', 'user');
        })
        ->orWhere('receiver_type', 'all')
        ->orderBy('created_at', 'desc')
        ->paginate(15);

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        
        // Update notification receiver status
        NotificationReceiver::where('notification_id', $id)
            ->where('receiver_id', Auth::id())
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);

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