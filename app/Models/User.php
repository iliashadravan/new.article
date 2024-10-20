<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject; // افزودن این خط

class User extends Authenticatable implements AuthenticatableContract, JWTSubject // افزودن JWTSubject به قراردادها
{
    use HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password'
    ];

    public function articles()
    {
        return $this->hasMany(Article::class, 'user_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // متدهای مربوط به JWTSubject
    public function getJWTIdentifier()
    {
        return $this->getKey(); // برگرداندن کلید اصلی کاربر
    }

    public function getJWTCustomClaims()
    {
        return []; // هیچ ادعای سفارشی در اینجا اضافه نمی‌شود
    }
}
