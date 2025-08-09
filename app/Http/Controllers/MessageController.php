<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class MessageController extends Controller
{
    public function index()
    {
        $messages = session('chat_messages', []);
        $user = Auth::user();
        return view('chat.index', compact('messages', 'user'));
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $message = [
            'id' => uniqid(),
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'content' => $request->content,
            'created_at' => now()->format('H:i d/m/Y'),
            'user_role' => Auth::user()->role
        ];

        // Lưu vào session (giữ 50 tin nhắn gần nhất)
        $messages = session('chat_messages', []);
        $messages[] = $message;
        session(['chat_messages' => array_slice($messages, -50)]);

        event(new MessageSent($message));
        return redirect()->route('messages.index');
    }

    public function reset()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Không có quyền truy cập. Yêu cầu quyền Admin.');
        }

        session()->forget('chat_messages');
        return redirect()->route('messages.index')->with('success', 'Đã xóa toàn bộ lịch sử chat.');
    }

    public function deleteMessage($id)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Không có quyền truy cập. Yêu cầu quyền Admin.');
        }

        $messages = session('chat_messages', []);
        $messages = array_filter($messages, fn($msg) => $msg['id'] !== $id);
        session(['chat_messages' => $messages]);
        
        return redirect()->route('messages.index')->with('success', 'Đã xóa tin nhắn thành công.');
    }
} 