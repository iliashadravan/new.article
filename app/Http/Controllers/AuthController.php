<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validate_data = $request->validate([
            'name' => 'required|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:3',
        ]);

        // ایجاد کاربر جدید
        $user = User::create([
            'name' => $validate_data['name'],
            'email' => $validate_data['email'],
            'password' => Hash::make($validate_data['password']),
        ]);

        // ایجاد توکن برای کاربر
        $token = JWTAuth::fromUser($user);

        return response()->json([
            'message' => 'ثبت‌نام موفقیت‌آمیز بود.',
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    public function login(Request $request)
    {
        $validate_data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:3',
        ]);

        // اعتبارسنجی کاربر
        if (!$token = JWTAuth::attempt($validate_data)) {
            return response()->json(['message' => 'ایمیل یا رمز عبور اشتباه است.'], 401);
        }

        // پیدا کردن کاربر با ایمیل
        $user = User::where('email', $validate_data['email'])->first();

        return response()->json([
            'message' => 'ورود موفقیت‌آمیز بود.',
            'user' => $user,
            'token' => $token,
        ], 200);
    }

    public function logout(Request $request)
    {
        // خروج کاربر و از بین بردن توکن
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json([
            'message' => 'خروج موفقیت‌آمیز بود.',
        ], 200);
    }

    public function me()
    {
        // دریافت اطلاعات کاربر احراز شده
        return response()->json(auth()->user());
    }

    public function refresh()
    {
        // تازه‌سازی توکن
        return response()->json([
            'token' => JWTAuth::refresh(JWTAuth::getToken()),
        ]);
    }
}
