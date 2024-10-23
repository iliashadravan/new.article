<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleStoreRequest extends BaseRequest
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
            'title' => 'required|min:3|max:100',
            'body' => 'required|min:5',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
        ];
    }
}
