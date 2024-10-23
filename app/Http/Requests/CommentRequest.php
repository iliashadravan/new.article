<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'body' => 'required|string|max:1000|min:3',
            'commentable_id' => 'required|integer',
            'commentable_type' => 'required|string|in:comment,reply',
        ];
    }
}
