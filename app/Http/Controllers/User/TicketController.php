<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTicketRequest;
use App\Http\Requests\ReplyTicketRequest;
use App\Models\Ticket;
use App\Models\TicketReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    /**
     * Hiển thị danh sách ticket của user
     */
    public function index()
    {
        $tickets = Ticket::where('user_id', Auth::id())
            ->with(['assignedTo', 'replies' => function($query) {
                $query->public()->latest();
            }])
            ->latest()
            ->paginate(10);

        return view('user.tickets.index', compact('tickets'));
    }

    /**
     * Hiển thị form tạo ticket mới
     */
    public function create()
    {
        return view('user.tickets.create');
    }

    /**
     * Lưu ticket mới
     */
    public function store(CreateTicketRequest $request)
    {
        $data = $request->validated();
        
        // Xử lý file đính kèm
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('tickets/attachments', 'public');
        }

        // Tạo ticket
        $ticket = Ticket::create([
            'ticket_code' => Ticket::generateTicketCode(),
            'user_id' => Auth::id(),
            'subject' => $data['subject'],
            'description' => $data['description'],
            'priority' => $data['priority'],
            'category' => $data['category'],
            'attachment_path' => $attachmentPath,
            'status' => 'waiting_for_customer', // Trạng thái mặc định: chờ xử lý
        ]);

        return redirect()->route('user.tickets.show', $ticket)
            ->with('success', 'Ticket đã được tạo thành công!');
    }

    /**
     * Hiển thị chi tiết ticket
     */
    public function show(Ticket $ticket)
    {
        // Kiểm tra quyền truy cập
        if ($ticket->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền truy cập ticket này');
        }

        $ticket->load(['user', 'assignedTo', 'replies.user']);

        return view('user.tickets.show', compact('ticket'));
    }

    /**
     * Trả lời ticket
     */
    public function reply(ReplyTicketRequest $request, Ticket $ticket)
    {
        // Kiểm tra quyền truy cập
        if ($ticket->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền trả lời ticket này');
        }

        // Kiểm tra ticket có thể trả lời không
        if (!$ticket->canBeUpdated()) {
            return back()->with('error', 'Ticket này đã được đóng và không thể trả lời');
        }

        $data = $request->validated();
        
        // Xử lý file đính kèm
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('tickets/replies/attachments', 'public');
        }

        // Tạo phản hồi
        TicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $data['message'],
            'attachment_path' => $attachmentPath,
            'is_internal' => false, // User không thể tạo phản hồi nội bộ
        ]);

        // Cập nhật status ticket nếu đang waiting_for_customer
        if ($ticket->status === 'waiting_for_customer') {
            $ticket->update(['status' => 'in_progress']);
        }

        return back()->with('success', 'Phản hồi đã được gửi thành công!');
    }

    /**
     * Đóng ticket
     */
    public function close(Ticket $ticket)
    {
        // Kiểm tra quyền truy cập
        if ($ticket->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền đóng ticket này');
        }

        if ($ticket->status === 'closed') {
            return back()->with('error', 'Ticket này đã được đóng');
        }

        $ticket->markAsClosed();

        return back()->with('success', 'Ticket đã được đóng thành công!');
    }

    /**
     * Tải file đính kèm
     */
    public function downloadAttachment($filename)
    {
        $path = 'tickets/attachments/' . $filename;
        
        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'File không tồn tại');
        }

        return response()->download(storage_path('app/public/' . $path));
    }

    /**
     * Tải file đính kèm của phản hồi
     */
    public function downloadReplyAttachment($filename)
    {
        $path = 'tickets/replies/attachments/' . $filename;
        
        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'File không tồn tại');
        }

        return response()->download(storage_path('app/public/' . $path));
    }
}