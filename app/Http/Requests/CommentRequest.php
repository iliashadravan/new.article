<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Validation\Validator;

class CommentRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'body' => 'required|string|max:1000|min:3',
            'commentable_id' => 'required|integer',
            'commentable_type' => 'required|string|in:article,comment',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param \Illuminate\Validation\Validator $validator
     */
    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $commentableType = $this->input('commentable_type');
            $commentableId = $this->input('commentable_id');

            if ($commentableType === 'comment') {
                if (!Comment::find($commentableId)) {
                    $validator->errors()->add('commentable_id', 'Cannot reply to a non-existent comment.');
                }
                // اینجا چک می‌کنیم که آیا کامنت والد ریپلای است یا خیر
                $parentComment = Comment::find($commentableId);
                if ($parentComment && $parentComment->isReply()) {
                    $validator->errors()->add('commentable_id', 'Cannot reply to a reply comment.');
                }
            } elseif ($commentableType === 'article') {
                if (!Article::find($commentableId)) {
                    $validator->errors()->add('commentable_id', 'Article does not exist.');
                }
            }
        });
    }
}
