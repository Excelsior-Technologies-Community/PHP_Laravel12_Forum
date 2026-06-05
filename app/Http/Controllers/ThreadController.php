<?php

namespace App\Http\Controllers;

use App\Models\Thread;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class ThreadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show', 'byCategory']);
    }

    public function index(Request $request)
    {
        $query = Thread::with(['user', 'category']);

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'popular':
                $query->withCount('posts')->orderBy('posts_count', 'desc');
                break;
            case 'most_liked':
                $query->withCount(['posts as posts_likes_count' => function($q) {
                    $q->withSum('likes', 'id');
                }])->orderBy('posts_likes_count', 'desc');
                break;
            case 'oldest':
                $query->oldest();
                break;
            default:
                $query->latest();
        }

        $threads = $query->paginate(10)->appends($request->all());
        $categories = Category::all();

        return view('threads.index', compact('threads', 'categories'));
    }

    public function byCategory(Category $category, Request $request)
    {
        $query = $category->threads()->with(['user', 'category']);

        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'popular':
                $query->withCount('posts')->orderBy('posts_count', 'desc');
                break;
            case 'most_liked':
                $query->withCount(['posts as posts_likes_count' => function($q) {
                    $q->withSum('likes', 'id');
                }])->orderBy('posts_likes_count', 'desc');
                break;
            default:
                $query->latest();
        }

        $threads = $query->paginate(10)->appends($request->all());
        $categories = Category::all();

        return view('threads.index', compact('threads', 'categories', 'category'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('threads.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'category_id' => 'required|exists:categories,id'
        ]);

        auth()->user()->threads()->create($request->all());

        auth()->user()->increment('reputation', 5);

        return redirect()->route('threads.index')->with('success', 'Thread created!');
    }

    public function show(Thread $thread)
    {
        $thread->load(['user', 'posts.user', 'category', 'bestPost']);
        return view('threads.show', compact('thread'));
    }

    public function edit(Thread $thread)
    {
        if (auth()->id() !== $thread->user_id) {
            abort(403);
        }
        $categories = Category::all();
        return view('threads.edit', compact('thread', 'categories'));
    }

    public function update(Request $request, Thread $thread)
    {
        if (auth()->id() !== $thread->user_id) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'category_id' => 'required|exists:categories,id'
        ]);

        $thread->update($request->only(['title', 'body', 'category_id']));

        return redirect()->route('threads.index')->with('success', 'Thread updated!');
    }

    public function destroy(Thread $thread)
    {
        if (auth()->id() !== $thread->user_id) {
            abort(403);
        }

        $thread->delete();
        return redirect()->route('threads.index')->with('success', 'Thread deleted!');
    }
}