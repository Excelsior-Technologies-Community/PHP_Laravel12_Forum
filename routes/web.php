<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ThreadController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\LikeController;

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

// Dashboard route (optional)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Forum routes (threads & posts) - protected by auth middleware
Route::middleware(['auth'])->group(function () {
    // Threads CRUD
    Route::resource('threads', ThreadController::class);

    // Posts (replies) for a thread
    Route::post('threads/{thread}/posts', [PostController::class, 'store'])->name('posts.store');

    // ❤️ Like / Unlike post
    Route::post('/posts/{post}/like', [LikeController::class, 'toggle'])
        ->name('posts.like');
});
// Profile routes (from Breeze / Laravel 12 auth scaffolding)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Include default auth routes (login, register, password reset, etc.)
require __DIR__.'/auth.php';