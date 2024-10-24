<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $request->validated();

        // ایجاد کاربر جدید
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // ایجاد توکن برای کاربر
        $token = JWTAuth::fromUser($user);

        return response()->json([
            'message' => 'ثبت‌نام موفقیت‌آمیز بود.',
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        $request->validated();
        // اعتبارسنجی کاربر
        if (!$token = JWTAuth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'email or password is wrong'], 401);
        }

        // پیدا کردن کاربر با ایمیل
        $user = User::where('email', $request->email)->first();

        return response()->json([
            'message' => 'ورود موفقیت‌آمیز بود.',
            'user' => $user,
            'token' => $token,
        ], 200);
    }

    public function me()
    {
        // دریافت اطلاعات کاربر احراز شده
        return response()->json(auth()->user());
    }
}
