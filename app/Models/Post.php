<?php
// app/Models/Post.php (add methods)

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['body', 'user_id', 'thread_id'];

    // Existing relationships plus:

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