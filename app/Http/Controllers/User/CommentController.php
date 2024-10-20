<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
// ذخیره کامنت جدید برای مقاله
    public function comment(Request $request, Article $article)
    {
        $request->validate([
            'body' => 'required|string|max:1000',
        ]);

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
    public function reply(Request $request, Comment $comment)
    {
        $request->validate([
            'body' => 'required|string|max:1000',
        ]);

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
