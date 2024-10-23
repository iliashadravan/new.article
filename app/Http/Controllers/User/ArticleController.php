<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleStoreRequest;
use App\Http\Requests\ArticleUpdateRequest;
use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    public function store(ArticleStoreRequest $request)
    {
        // دریافت داده‌های اعتبارسنجی‌شده از درخواست
        $validated_data = $request->validated();

        // ایجاد مقاله جدید
        $article = Article::create([
            'title' => $validated_data['title'],
            'body' => $validated_data['body'],
            'user_id' => auth()->id(), // کاربر لاگین شده را به مقاله نسبت می‌دهیم
        ]);

        // پیوست کردن دسته‌بندی‌ها به مقاله
        $article->categories()->attach($validated_data['categories']);

        return response()->json([
            'success' => true,
            'message' => 'مقاله با موفقیت ایجاد شد.',
            'article' => $article
        ]);
    }

    public function update(ArticleUpdateRequest $request, Article $article)
    {
        // دریافت داده‌های اعتبارسنجی‌شده از درخواست
        $validated_data = $request->validated();

        // به روز رسانی مقاله
        $article->update([
            'title' => $validated_data['title'],
            'body' => $validated_data['body'],
        ]);

        // همگام‌سازی دسته‌بندی‌ها با مقاله
        $article->categories()->sync($validated_data['categories']);

        return response()->json([
            'success' => true,
            'message' => 'مقاله با موفقیت به‌روزرسانی شد!',
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
