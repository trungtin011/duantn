<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReplyTicketRequest;
use App\Models\Ticket;
use App\Models\TicketReply;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    /**
     * Hiển thị danh sách tất cả ticket
     */
    public function index(Request $request)
    {
        $query = Ticket::with(['user', 'assignedTo', 'replies' => function ($query) {
            $query->latest();
        }]);

        // Lọc theo status
        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        // Lọc theo priority
        if ($request->filled('priority')) {
            $query->byPriority($request->priority);
        }

        // Lọc theo category
        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        // Tìm kiếm theo mã ticket hoặc tiêu đề
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ticket_code', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        $tickets = $query->latest()->paginate(15);

        return view('admin.tickets.index', compact('tickets'));
    }

    /**
     * Hiển thị chi tiết ticket
     */
    public function show(Ticket $ticket)
    {
        $ticket->load(['user', 'assignedTo', 'replies.user']);

        // Lấy danh sách admin để phân công
        $admins = User::where('role', 'admin')->get();

        return view('admin.tickets.show', compact('ticket', 'admins'));
    }

    /**
     * Phân công ticket cho admin
     */
    public function assign(Request $request, Ticket $ticket)
    {
        $request->validate([
            'assigned_to' => 'required|exists:users,id'
        ]);

        $ticket->update([
            'assigned_to' => $request->assigned_to,
            'status' => 'in_progress'
        ]);

        return back()->with('success', 'Ticket đã được phân công thành công!');
    }

    /**
     * Trả lời ticket
     */
    public function reply(ReplyTicketRequest $request, Ticket $ticket)
    {
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
            'is_internal' => $data['is_internal'] ?? false,
        ]);

        // Cập nhật status ticket
        if ($ticket->status === 'waiting_for_customer') {
            $ticket->update(['status' => 'in_progress']);
        }

        return back()->with('success', 'Phản hồi đã được gửi thành công!');
    }

    /**
     * Cập nhật status ticket
     */
    public function updateStatus(Request $request, Ticket $ticket)
    {
        $request->validate([
            'status' => 'required|in:open,in_progress,waiting_for_customer,resolved,closed'
        ]);

        $status = $request->status;

        if ($status === 'resolved') {
            $ticket->markAsResolved();
        } elseif ($status === 'closed') {
            $ticket->markAsClosed();
        } else {
            $ticket->update(['status' => $status]);
        }

        return back()->with('success', 'Trạng thái ticket đã được cập nhật thành công!');
    }

    /**
     * Đóng ticket
     */
    public function close(Ticket $ticket)
    {
        if ($ticket->status === 'closed') {
            return back()->with('error', 'Ticket này đã được đóng');
        }

        $ticket->markAsClosed();

        return back()->with('success', 'Ticket đã được đóng thành công!');
    }

    /**
     * Xóa ticket (chỉ admin mới có quyền)
     */
    public function destroy(Ticket $ticket)
    {
        // Xóa các file đính kèm
        if ($ticket->attachment_path) {
            Storage::disk('public')->delete($ticket->attachment_path);
        }

        // Xóa các file đính kèm của replies
        foreach ($ticket->replies as $reply) {
            if ($reply->attachment_path) {
                Storage::disk('public')->delete($reply->attachment_path);
            }
        }

        $ticket->delete();

        return redirect()->route('admin.tickets.index')
            ->with('success', 'Ticket đã được xóa thành công!');
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
