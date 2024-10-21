<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Models\Article;
use App\Models\Comment;

class CommentController extends Controller
{
    // ذخیره کامنت یا ریپلای
    public function comment(CommentRequest $request, Article $article)
    {
        $data = [
            'body' => $request->body,
            'user_id' => auth()->id(),
        ];

        // بررسی اینکه آیا نوع کامنت reply است یا comment
        if ($request->type === 'reply') {
            // اگر نوع ریپلای است، ریپلای به کامنت مشخص شده ایجاد می‌شود
            $comment = Comment::findOrFail($request->comment_id);
            $comment->replies()->create($data);
        } else {
            // اگر نوع کامنت است، برای مقاله کامنت جدید ایجاد می‌شود
            $article->comments()->create($data);
        }

        return response()->json([
            'message' => 'Comment added',
            'success' => true,
        ]);
    }
}
