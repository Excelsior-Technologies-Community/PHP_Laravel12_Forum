<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    protected $fillable = ['title', 'body', 'user_id', 'category_id', 'best_post_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function bestPost()
    {
        return $this->belongsTo(Post::class, 'best_post_id');
    }

    public function markBestReply(Post $post)
    {
        $this->best_post_id = $post->id;
        $this->save();

        $post->user->increment('reputation', 10);
    }

    public function getLikeCountAttribute()
    {
        return $this->posts->sum(function ($post) {
            return $post->likes->count();
        });
    }
}