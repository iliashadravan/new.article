<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Models\Article;
use App\Models\Comment;

class CommentController extends Controller
{
    // ذخیره کامنت یا ریپلای
    public function comment(CommentRequest $request, Article $article, Comment $comment = null)
    {
        $data = [
            'body' => $request->body,
            'user_id' => auth()->id(),
        ];

        if ($comment) {
            // اگر کامنت وجود داشته باشد، ریپلای به آن ایجاد می‌شود
            $comment->replies()->create($data);
        } else {
            // اگر کامنت وجود نداشته باشد، کامنت جدید برای مقاله ایجاد می‌شود
            $article->comments()->create($data);
        }

        return response()->json([
            'message' => $comment ? 'Reply added' : 'Comment added',
            'success' => true,
        ]);
    }
}
