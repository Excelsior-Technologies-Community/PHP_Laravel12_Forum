<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class BestReplyController extends Controller
{
    public function store(Post $post)
    {
        $thread = $post->thread;

        if (auth()->id() !== $thread->user_id) {
            abort(403);
        }

        $thread->markBestReply($post);

        return back()->with('success', 'Best reply selected!');
    }

    public function destroy(Post $post)
    {
        $thread = $post->thread;

        if (auth()->id() !== $thread->user_id) {
            abort(403);
        }

        $thread->update(['best_post_id' => null]);

        return back()->with('success', 'Best reply removed.');
    }
}