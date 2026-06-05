<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ThreadController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\BestReplyController;

Route::get('/', function () {
    return redirect()->route('threads.index');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ✅ Public routes - ORDER IMPORTANT: 'create' pehla aavu joiye {thread} thi
Route::get('threads', [ThreadController::class, 'index'])->name('threads.index');
Route::get('threads/create', [ThreadController::class, 'create'])->name('threads.create'); // 👈 AUTH BAHAR, UPAR
Route::get('threads/{thread}', [ThreadController::class, 'show'])->name('threads.show');
Route::get('/categories/{category}', [ThreadController::class, 'byCategory'])->name('threads.byCategory');

// Auth required routes
Route::middleware(['auth'])->group(function () {

    Route::post('threads', [ThreadController::class, 'store'])->name('threads.store');
    Route::get('threads/{thread}/edit', [ThreadController::class, 'edit'])->name('threads.edit');
    Route::put('threads/{thread}', [ThreadController::class, 'update'])->name('threads.update');
    Route::delete('threads/{thread}', [ThreadController::class, 'destroy'])->name('threads.destroy');

    Route::post('threads/{thread}/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

    Route::post('/posts/{post}/like', [LikeController::class, 'toggle'])->name('likes.toggle');

    Route::post('/posts/{post}/best', [BestReplyController::class, 'store'])->name('posts.best.store');
    Route::delete('/posts/{post}/best', [BestReplyController::class, 'destroy'])->name('posts.best.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/{user}/activity', [ProfileController::class, 'activity'])->name('profile.activity');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';