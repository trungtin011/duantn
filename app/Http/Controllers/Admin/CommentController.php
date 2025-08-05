<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    /**
     * Display a listing of comments.
     */
    public function index(Request $request)
    {
        $query = Comment::with(['user', 'product', 'post']);

        // Filter by search term
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('content', 'like', "%{$search}%");
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by type
        if ($request->filled('type')) {
            if ($request->type === 'product') {
                $query->whereNotNull('product_id');
            } elseif ($request->type === 'post') {
                $query->whereNotNull('post_id');
            }
        }

        // Sort by
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $comments = $query->paginate(15);

        return view('admin.comments.index', compact('comments'));
    }

    /**
     * Display the specified comment.
     */
    public function show(Comment $comment)
    {
        $comment->load(['user', 'product', 'post']);
        return view('admin.comments.show', compact('comment'));
    }

    /**
     * Approve a comment.
     */
    public function approve(Comment $comment)
    {
        try {
            $comment->update(['status' => 'approved']);
            
            // Create notification for user
            if ($comment->user_id) {
                \App\Models\Notification::create([
                    'user_id' => $comment->user_id,
                    'title' => 'Bình luận đã được duyệt',
                    'content' => 'Bình luận của bạn đã được duyệt và hiển thị.',
                    'type' => 'comment_approval',
                    'receiver_type' => 'user',
                    'link' => $comment->product_id ? route('product.show', $comment->product->slug) : route('post.show', $comment->post->slug)
                ]);
            }

            return redirect()->back()->with('success', 'Bình luận đã được duyệt!');
        } catch (\Exception $e) {
            Log::error('Lỗi khi duyệt bình luận: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi duyệt bình luận!');
        }
    }

    /**
     * Reject a comment.
     */
    public function reject(Comment $comment)
    {
        try {
            $comment->update(['status' => 'rejected']);
            
            // Create notification for user
            if ($comment->user_id) {
                \App\Models\Notification::create([
                    'user_id' => $comment->user_id,
                    'title' => 'Bình luận bị từ chối',
                    'content' => 'Bình luận của bạn đã bị từ chối và không được hiển thị.',
                    'type' => 'comment_rejection',
                    'receiver_type' => 'user',
                    'link' => $comment->product_id ? route('product.show', $comment->product->slug) : route('post.show', $comment->post->slug)
                ]);
            }

            return redirect()->back()->with('success', 'Bình luận đã bị từ chối!');
        } catch (\Exception $e) {
            Log::error('Lỗi khi từ chối bình luận: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi từ chối bình luận!');
        }
    }

    /**
     * Remove the specified comment from storage.
     */
    public function destroy(Comment $comment)
    {
        try {
            $comment->delete();
            return redirect()->back()->with('success', 'Bình luận đã được xóa!');
        } catch (\Exception $e) {
            Log::error('Lỗi khi xóa bình luận: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi xóa bình luận!');
        }
    }
} 