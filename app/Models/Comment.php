<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['body', 'user_id', 'is_visible', 'commentable_type', 'commentable_id'];

    // رابطه چند شکلی
    public function commentable()
    {
        return $this->morphTo();
    }

    // ریپلای‌ها
    public function replies()
    {
        return $this->morphMany(Comment::class, 'commentable')->where('commentable_type', Comment::class);
    }

    // کاربر کامنت‌دهنده
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // برای تشخیص اینکه آیا این کامنت یک ریپلای است یا خیر
    public function isReply()
    {
        return $this->commentable_type === Comment::class;
    }
}
