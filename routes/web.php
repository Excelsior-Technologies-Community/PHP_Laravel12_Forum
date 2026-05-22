<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ThreadController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\BestReplyController;
use App\Http\Controllers\CategoryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Redirect root to threads index (forum home)
Route::get('/', function () {
    return redirect()->route('threads.index');
});

// Dashboard route
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Categories (public)
Route::get('/categories/{category}', [ThreadController::class, 'byCategory'])->name('threads.byCategory');

// Forum routes - protected by auth middleware
Route::middleware(['auth'])->group(function () {
    // Threads CRUD
    Route::resource('threads', ThreadController::class);
    
    // Posts (replies)
    Route::post('threads/{thread}/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    
    // Like / Unlike
    Route::post('/posts/{post}/like', [LikeController::class, 'toggle'])->name('posts.like');
    
    // Best Reply
    Route::post('/posts/{post}/best', [BestReplyController::class, 'store'])->name('posts.best');
    Route::delete('/posts/{post}/best', [BestReplyController::class, 'destroy'])->name('posts.best.remove');
});

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/{user}/activity', [ProfileController::class, 'activity'])->name('profile.activity');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';