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

        // اگر نوع کامنت "reply" است، بررسی وجود کامنت والد
        if ($commentableType === 'reply') {
            if (!$this->isCommentValid($commentableId)) {
                return response()->json([
                    'message' => 'Cannot reply to a non-existent comment.',
                    'success' => false,
                ], 400);
            }

            // بررسی وجود ریپلای قبلی برای این کامنت
            if ($this->hasExistingReply($commentableId)) {
                return response()->json([
                    'message' => 'Each comment can only have one reply.',
                    'success' => false,
                ], 400);
            }
        } else { // اگر نوع کامنت "comment" است، بررسی وجود مقاله
            if (!$this->isArticleValid($commentableId)) {
                return response()->json([
                    'message' => 'Article does not exist.',
                    'success' => false,
                ], 400);
            }
        }

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

    private function isCommentValid($commentableId)
    {
        return Comment::where('id', $commentableId)
            ->where('commentable_type', Comment::class)
            ->exists();
    }

    private function hasExistingReply($commentableId)
    {
        return Comment::where('commentable_id', $commentableId)
            ->where('commentable_type', Comment::class)
            ->exists();
    }
    private function isArticleValid($articleId)
    {
        return Article::where('id', $articleId)->exists();
    }
}
