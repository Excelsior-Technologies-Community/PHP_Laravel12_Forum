<?php

namespace App\Http\Controllers;

use App\Models\Thread;
use App\Models\Post;
use App\Models\Like;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function store(Request $request, Thread $thread)
    {
        $request->validate(['body' => 'required|string|min:2']);

        $thread->posts()->create([
            'body' => $request->body,
            'user_id' => auth()->id()
        ]);

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

        if (auth()->id() !== $post->user_id && auth()->id() !== $thread->user_id) {
            abort(403);
        }

        if ($thread->best_post_id === $post->id) {
            $thread->update(['best_post_id' => null]);
        }

        $post->delete();

        return redirect()->route('threads.show', $thread)->with('success', 'Reply deleted!');
    }

    public function best(Post $post)
    {
        $thread = $post->thread;

        if (auth()->id() !== $thread->user_id) {
            abort(403);
        }

        $thread->markBestReply($post);

        return back()->with('success', 'Best reply marked!');
    }

    public function toggleLike(Post $post)
    {
        $like = $post->likes()->where('user_id', auth()->id())->first();

        if ($like) {
            $like->delete();
        } else {
            $post->likes()->create(['user_id' => auth()->id()]);
        }

        return back();
    }
}