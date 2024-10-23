<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleUpdateRequest;
use App\Models\Article;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::with('user')->paginate(10);

        return response()->json([
            'success' => true,
            'articles' => $articles
        ]);
    }

    public function update(ArticleUpdateRequest $request, Article $article)
    {
        $validated = $request->validated();

        // به روز رسانی مقاله
        $article->update([
            'title' => $validated['title'],
            'body' => $validated['body'],
        ]);

        // همگام‌سازی دسته‌بندی‌ها با مقاله
        $article->categories()->sync($validated['categories']);

        return response()->json([
            'success' => true,
            'message' => 'Article updated successfully',
            'article' => $article
        ]);
    }


    public function delete(Article $article)
    {

        // حذف مقاله
        $article->delete();

        return response()->json([
            'success' => true,
            'message' => 'Article deleted successfully!'
        ]);
    }
}
