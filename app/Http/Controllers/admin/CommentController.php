<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Product;
use App\Models\User;

class CommentController extends Controller
{
    // Hiển thị danh sách bình luận
    public function index()
    {
        $comments = Comment::with(['user', 'product'])->whereNull('parent_id')->orderByDesc('created_at')->paginate(10);
        return view('admin.comments.listComment', compact('comments'));
    }

    // Xem chi tiết bình luận và các rep comment
    public function show($id)
    {
        $comment = Comment::with(['user', 'product', 'replies.user'])->findOrFail($id);
        return view('admin.comments.showComment', compact('comment'));
    }

    // Admin rep comment (tạo rep comment)
    public function reply(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required|string|max:255',
        ]);
        $adminId = auth()->id();
        $parentComment = Comment::findOrFail($id);
        Comment::create([
            'user_id' => $adminId,
            'product_id' => $parentComment->product_id,
            'comment' => $request->comment,
            'parent_id' => $id,
        ]);
        return redirect()->route('admin.comments.show', $id)->with('success', 'Phản hồi thành công!');
    }

    // Xóa bình luận hoặc rep comment
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();
        return back()->with('success', 'Xóa bình luận thành công!');
    }
} 