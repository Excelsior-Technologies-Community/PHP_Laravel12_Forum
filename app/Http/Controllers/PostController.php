<?php
// app/Http/Controllers/PostController.php (updated)

namespace App\Http\Controllers;

use App\Models\Thread;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function store(Request $request, Thread $thread)
    {
        $request->validate(['body' => 'required|string|min:2']);

        $post = $thread->posts()->create([
            'body' => $request->body,
            'user_id' => auth()->id()
        ]);

        // Give reputation for posting reply
        auth()->user()->increment('reputation', 2);

        return redirect()->route('threads.show', $thread)->with('success', 'Reply posted!');
    }

    public function edit(Post $post)
    {
        if (auth()->id() !== $post->user_id) {
            abort(403);
        }

        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        if (auth()->id() !== $post->user_id) {
            abort(403);
        }

        $request->validate(['body' => 'required|string|min:2']);

        $post->update(['body' => $request->body]);

        return redirect()->route('threads.show', $post->thread)->with('success', 'Reply updated!');
    }

    public function destroy(Post $post)
    {
        $thread = $post->thread;

        // Allow if user owns post OR user owns thread
        if (auth()->id() !== $post->user_id && auth()->id() !== $thread->user_id) {
            abort(403);
        }

        $post->delete();

        // If this was the best reply, remove it
        if ($thread->best_post_id === $post->id) {
            $thread->update(['best_post_id' => null]);
        }

        return redirect()->route('threads.show', $thread)->with('success', 'Reply deleted!');
    }
}