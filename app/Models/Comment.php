<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['body', 'user_id', 'is_visible'];

// رابطه چند شکلی
    public function commentable()
    {
        return $this->morphTo();
    }

// ریپلای‌ها
    public function replies()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

// کاربر کامنت‌دهنده
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
