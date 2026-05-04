<?php

namespace App\Http\Controllers;

use App\Models\Thread;
use Illuminate\Http\Request;

class ThreadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index(Request $request)
    {
        $query = Thread::query();

        // SEARCH FEATURE
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $threads = $query->latest()
            ->with('user')
            ->paginate(10)
            ->appends($request->all());

        return view('threads.index', compact('threads'));
    }

    public function create()
    {
        return view('threads.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string'
        ]);
        auth()->user()->threads()->create($request->all());
        return redirect()->route('threads.index')->with('success', 'Thread created!');
    }

    public function show(Thread $thread)
    {
        $thread->load('posts.user', 'posts.likes');
        return view('threads.show', compact('thread'));
    }
    public function edit(Thread $thread)
    {
        if (auth()->id() !== $thread->user_id) {
            abort(403);
        }
        return view('threads.edit', compact('thread'));
    }

    public function update(Request $request, Thread $thread)
    {
        if (auth()->id() !== $thread->user_id) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $thread->update([
            'title' => $request->title,
            'body' => $request->body,
        ]);

        return redirect()->route('threads.index')->with('success', 'Thread updated!');
    }
}
