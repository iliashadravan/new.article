<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function showComments(Article $article)
    {
        // گرفتن تمام کامنت‌های مربوط به یک مقاله همراه با پاسخ‌ها
        $comments = Comment::where('commentable_id', $article->id)->with('replies')->get();

        return response()->json([
            'success' => true,
            'article' => $article,
            'comments' => $comments
        ]);
    }

    public function updateCommentVisibility(Request $request, Comment $comment)
    {
        // بروزرسانی وضعیت قابل مشاهده بودن کامنت
        $comment->is_visible = $request->get('is_visible');

        // ذخیره‌سازی تغییرات
        $comment->save();

        return response()->json([
            'success' => true,
            'message' => 'Comment visibility updated successfully!',
            'comment' => $comment
        ]);
    }
}
