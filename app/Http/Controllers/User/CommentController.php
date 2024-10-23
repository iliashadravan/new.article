<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Models\Article;
use App\Models\Comment;

class CommentController extends Controller
{
    // ذخیره کامنت یا ریپلای
    public function comment(CommentRequest $request)
    {
        $userId = auth()->id();
        $commentableType = $request->commentable_type;
        $commentableId = $request->commentable_id;

        // ایجاد کامنت جدید
        $comment = Comment::create([
            'body' => $request->body,
            'user_id' => $userId,
            'commentable_id' => $commentableId,
            'commentable_type' => $commentableType === 'reply' ? Comment::class : Article::class,
        ]);

        return response()->json([
            'message' => 'Comment or reply added successfully',
            'success' => true,
            'comment' => $comment,
        ]);
    }
}
