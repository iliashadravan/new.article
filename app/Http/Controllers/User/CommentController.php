<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
// ذخیره کامنت جدید برای مقاله
    public function comment(CommentRequest $request, Article $article)
    {
// ایجاد کامنت جدید برای مقاله
        $article->comments()->create([
            'body' => $request->body,
            'user_id' => auth()->id(),
        ]);

        return response()->json([
            'message' => 'Comment added',
            'success' => true,
        ]);
    }

// ذخیره ریپلای برای یک کامنت
    public function reply(CommentRequest $request, Comment $comment)
    {
        // ایجاد ریپلای جدید برای کامنت
        $comment->replies()->create([
            'body' => $request->body,
            'user_id' => auth()->id(),
        ]);

        return response()->json([
            'message' => 'Comment replied',
            'success' => true,
        ]);
    }
}
