<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use Sluggable;

    protected $fillable = ['title', 'body', 'slug', 'user_id'];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'article_category');
    }

    public function ratings()
    {
        return $this->belongsToMany(User::class, 'ratings', 'article_id', 'user_id')
            ->withPivot('rating');
    }

    public function averageRating()
    {
        return $this->ratings()->avg('rating');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function likes()
    {
        return $this->belongsToMany(User::class, 'likes', 'article_id', 'user_id');
    }
}
