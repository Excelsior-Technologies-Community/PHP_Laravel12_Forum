<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['body', 'user_id', 'thread_id'];

    // ✅ Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // ✅ Helper Methods
    public function isBest()
    {
        return $this->thread->best_post_id === $this->id;
    }

    public function canBeDeleted()
    {
        return auth()->id() === $this->user_id || auth()->id() === $this->thread->user_id;
    }

    public function canBeEdited()
    {
        return auth()->id() === $this->user_id;
    }
}