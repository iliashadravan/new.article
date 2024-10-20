<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use App\Http\Resources\ArticleResource;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // دریافت عبارت جستجو از ورودی کاربر
        $search_term = $request->input('query');

        // اگر عبارت جستجو وجود داشته باشد، مقالات مرتبط را پیدا کن
        if ($search_term) {
            $articles = Article::query()
                ->where(function ($query) use ($search_term) {
                    $query->where('title', 'LIKE', '%' . $search_term . '%')
                        ->orWhere('body', 'LIKE', '%' . $search_term . '%')
                        ->orWhereHas('user', function ($q) use ($search_term) {
                            $q->where('name', 'LIKE', '%' . $search_term . '%');
                        })
                        ->orWhereHas('categories', function ($q) use ($search_term) {
                            $q->where('name', 'LIKE', '%' . $search_term . '%');
                        });
                })
                ->with(['likes', 'ratings']) // لایک‌ها و امتیازها را همراه داده‌ها دریافت کن
                ->get();
        } else {
            // در غیر این صورت، همه مقالات را نمایش بده
            $articles = Article::with(['likes', 'ratings'])->get();
        }

        // تبدیل مجموعه مقالات به Resource و ارسال پاسخ
        return ArticleResource::collection($articles);
    }
}
