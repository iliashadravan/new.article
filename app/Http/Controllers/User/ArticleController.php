<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    public function store()
    {
        $validate_data = $this->validate(request(), [
            'title' => 'required|min:3|max:100',
            'body' => 'required|min:5',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
        ]);

        // ایجاد مقاله جدید
        $article = Article::create([
            'title' => $validate_data['title'],
            'body' => $validate_data['body'],
            'user_id' => auth()->id(), // اینجا کاربر لاگین شده را به مقاله نسبت می‌دهید
        ]);

        // پیوست کردن دسته‌بندی‌ها به مقاله
        $article->categories()->attach($validate_data['categories']);

        return response()->json([
            'success' => true,
            'message' => 'مقاله با موفقیت ایجاد شد.',
            'article' => $article
        ]);
    }

    public function update(Request $request, Article $article)
    {
        $validate_data = Validator::make($request->all(), [
            'title' => 'required|min:3|max:50',
            'body' => 'required',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
        ])->validated();

        // به روز رسانی مقاله
        $article->update([
            'title' => $validate_data['title'],
            'body' => $validate_data['body'],
        ]);

        // همگام‌سازی دسته‌بندی‌ها با مقاله
        $article->categories()->sync($validate_data['categories']);

        return response()->json([
            'success' => true,
            'message' => 'Article updated successfully!',
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

    public function index()
    {
        $user_id = auth()->id();
        $user = User::find($user_id);

        $articles = $user->articles;

        return response()->json([
            'success' => true,
            'articles' => $articles
        ]);
    }

    public function like(Article $article)
    {
        $userId = auth()->id();

        // بررسی اینکه آیا کاربر مقاله را لایک کرده یا نه
        if ($article->likes()->where('user_id', $userId)->exists()) {
            $article->likes()->detach($userId);
            $liked = false;
        } else {
            $article->likes()->attach($userId);
            $liked = true;
        }

        return response()->json([
            'success' => true,
            'liked' => $liked,
        ]);
    }

    public function rate(Request $request, Article $article)
    {
        $validatedData = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $userId = auth()->id();

        // بررسی اینکه آیا کاربر قبلاً امتیاز داده است یا نه
        $existing_rating = $article->ratings()->where('user_id', $userId)->first();

        if ($existing_rating) {
            // اگر کاربر قبلاً امتیاز داده، آن را به‌روزرسانی کن
            $article->ratings()->updateExistingPivot($userId, [
                'rating' => $validatedData['rating'],
            ]);
            $rated = 'updated';
        } else {
            // در غیر این صورت، امتیاز جدید را ایجاد کن
            $article->ratings()->attach($userId, [
                'rating' => $validatedData['rating'],
            ]);
            $rated = 'created';
        }

        return response()->json([
            'success' => true,
            'message' => "Rating {$rated} successfully!",
        ]);
    }
}
