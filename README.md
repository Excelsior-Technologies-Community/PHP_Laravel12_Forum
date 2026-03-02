# PHP_Laravel12_Forum 


## Project Description:

PHP_Laravel12_Forum is a simple web-based forum application built with Laravel 12. It allows users to register, create discussion threads, post replies, and manage their content. The forum provides authentication, CRUD operations for threads and posts, and a clean Blade-based UI.


## Features:

1. User Authentication: Register, login, logout, and profile management using Laravel Breeze.

2. Thread Management: Users can create, edit, view, and delete discussion threads.

3. Post/Reply System: Users can comment/reply on threads.

4. Authorization: Users can only edit or delete their own threads.

5. Pagination: Threads are displayed with pagination for easy browsing.

6. Blade Templates: Responsive and clean front-end views using Blade.

7. Optional Likes: Can be extended to allow liking posts or threads.


## Technologies Used:

- Backend: PHP 8.2 + Laravel 12

- Frontend: Blade Templates, CSS

- Database: MySQL (or MariaDB)

- Authentication: Laravel Breeze

- Dependency Management: Composer & NPM

- Build Tools: Vite (npm run dev)

- Server: Built-in Laravel server (php artisan serve)



---



## Installation Steps


---


## STEP 1: Create Laravel 12 Project

### Open terminal / CMD and run:

```
composer create-project laravel/laravel PHP_Laravel12_Forum "12.*"

```

### Go inside project:

```
cd PHP_Laravel12_Forum

```

#### Explanation:

Installs a fresh Laravel 12 project and navigates into the project folder.




## STEP 2: Database Setup (Optional)

### Update database details:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel12_forum
DB_USERNAME=root
DB_PASSWORD=

```

### Create database in MySQL / phpMyAdmin:

```
Database name: laravel12_forum

```

#### Explanation:

Configures .env to connect Laravel with MySQL and creates the laravel12_forum database.



## STEP 3: Install Authentication

### Laravel 12 uses Breeze (lightweight, Blade-based):

```
composer require laravel/breeze --dev
php artisan breeze:install blade
php artisan migrate
npm install
npm run dev

```

#### Explanation:

Installs Laravel Breeze for basic login, registration, and dashboard using Blade templates.




## STEP 4: Create Models, Migrations, Relationships

### We need 3 main models:

```
Thread → forum topic

Post → comments/replies

Like → optional likes

```

### STEP 4.1: Thread Model & Migration

### Run:

```
php artisan make:model Thread -m

```
#### Open: database/migrations/xxxx_create_threads_table.php

```
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('threads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('body');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('threads');
    }
};

```

#### Open: Model app/Models/Thread.php:

```
<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'body', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}

```

#### Explanation:

Creates threads table with title, body, and user_id linking to the creator.



### STEP 4.2: Post Model & Migration

#### Run:

```
php artisan make:model Post -m

```

#### Open: database/migrations/xxxx_create_posts_table.php

```
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('thread_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('body');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};

```

#### Open: Model app/Models/Post.php:

```
<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $fillable = ['body', 'thread_id', 'user_id'];

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

```


#### Explanation:

Creates posts table with body, thread_id, and user_id for replies.





### STEP 4.3: User Model Relationships

#### Open: app/Models/User.php:

```
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function threads() {
        return $this->hasMany(Thread::class);
    }

    public function posts() {
        return $this->hasMany(Post::class);
    }
}

```

#### Explanation:

Defines that a user can have many threads and posts.





### STEP 4.4: Run Migrations

#### Run:

```
php artisan migrate

```

#### Explanation:

Runs all migrations to create tables in the database.





## STEP 5: Create Controllers

### Run:

```
php artisan make:controller ThreadController --resource

php artisan make:controller PostController --resource

```

### Edit app/Http/Controllers/ThreadController.php:

```
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

    public function index()
    {
        $threads = Thread::latest()->with('user')->paginate(10);
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
        $thread->load('posts.user');
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

```

#### Explanation:

Handles viewing, creating, editing, updating, and showing threads with authentication checks.





### Edit app/Http/Controllers/PostController.php:

```
<?php

namespace App\Http\Controllers;
use App\Models\Thread;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function store(Request $request, Thread $thread) {
        $request->validate(['body'=>'required|string']);
        $thread->posts()->create([
            'body'=>$request->body,
            'user_id'=>auth()->id()
        ]);
        return redirect()->route('threads.show',$thread)->with('success','Reply posted!');
    }
}

```

#### Explanation:

Handles storing replies for threads.




## STEP 6: Routes

### routes/web.php:

```
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ThreadController;
use App\Http\Controllers\PostController;

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
});

// Profile routes (from Breeze / Laravel 12 auth scaffolding)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Include default auth routes (login, register, password reset, etc.)
require __DIR__.'/auth.php';

```

#### Explanation:

Defines routes for threads, posts, dashboard, profile, and includes auth routes.





## STEP 7: Views Blade Files

### resources/views/threads/index.blade.php

```
<!DOCTYPE html>
<html>

<head>
    <title>Forum Threads</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 0 10px #ccc;
            border-radius: 8px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .thread {
            border-bottom: 1px solid #ddd;
            padding: 15px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .thread-info {
            flex: 1;
        }

        .thread a.title {
            font-size: 20px;
            text-decoration: none;
            color: #333;
            font-weight: bold;
        }

        .thread p {
            margin: 5px 0 0 0;
            color: #555;
        }

        .btn {
            padding: 5px 10px;
            margin-left: 5px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
            border: none;
        }

        .btn-show {
            background: #007bff;
            color: #fff;
        }

        .btn-show:hover {
            background: #0056b3;
        }

        .btn-edit {
            background: #ffc107;
            color: #fff;
        }

        .btn-edit:hover {
            background: #e0a800;
        }

        .btn-delete {
            background: #dc3545;
            color: #fff;
        }

        .btn-delete:hover {
            background: #c82333;
        }

        .create-btn {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 15px;
            background: #28a745;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
        }

        .create-btn:hover {
            background: #218838;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Forum Threads</h1>
        <a href="{{ route('threads.create') }}" class="create-btn">Create Thread</a>

        @foreach($threads as $thread)
            <div class="thread">
                <div class="thread-info">
                    <a href="{{ route('threads.show', $thread) }}" class="title">{{ $thread->title }}</a>
                    <p>By {{ $thread->user->name }} | {{ $thread->created_at->format('d M Y H:i') }}</p>
                </div>
                <div class="thread-actions">
                    <a href="{{ route('threads.show', $thread) }}" class="btn btn-show">Show</a>

                    @if(auth()->id() === $thread->user_id)
                        <a href="{{ route('threads.edit', $thread) }}" class="btn btn-edit">Edit</a>
                        <form action="{{ route('threads.destroy', $thread) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-delete"
                                onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach

        {{ $threads->links() }}
    </div>
</body>

</html>

```



### resources/views/threads/create.blade.php

```
<!DOCTYPE html>
<html>

<head>
    <title>Create Thread</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 50%;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 0 10px #ccc;
        }

        h1 {
            text-align: center;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        input,
        textarea {
            margin-bottom: 15px;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            padding: 10px;
            font-size: 16px;
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background: #0056b3;
        }

        .back {
            display: inline-block;
            margin-bottom: 20px;
            text-decoration: none;
            color: #007bff;
        }

        .error {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Create Thread</h1>
        <a href="{{ route('threads.index') }}" class="back">&larr; Back to Threads</a>

        @if ($errors->any())
            <div class="error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('threads.store') }}" method="POST">
            @csrf
            <input type="text" name="title" placeholder="Thread Title" value="{{ old('title') }}" required>
            <textarea name="body" rows="6" placeholder="Thread Body" required>{{ old('body') }}</textarea>
            <button type="submit">Create Thread</button>
        </form>
    </div>
</body>

</html>

```


### resources/views/threads/edit.blade.php

```
<!DOCTYPE html>
<html>

<head>
    <title>Edit Thread</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 50%;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 0 10px #ccc;
            border-radius: 8px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        input,
        textarea {
            margin-bottom: 15px;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            padding: 10px;
            font-size: 16px;
            background: #ffc107;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background: #e0a800;
        }

        .back {
            display: inline-block;
            margin-bottom: 20px;
            text-decoration: none;
            color: #007bff;
        }

        .error {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Edit Thread</h1>
        <a href="{{ route('threads.index') }}" class="back">&larr; Back to Threads</a>

        @if ($errors->any())
            <div class="error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('threads.update', $thread) }}" method="POST">
            @csrf
            @method('PUT')
            <input type="text" name="title" value="{{ old('title', $thread->title) }}" required>
            <textarea name="body" rows="6" required>{{ old('body', $thread->body) }}</textarea>
            <button type="submit">Update Thread</button>
        </form>
    </div>
</body>

</html>

```

### resources/views/threads/show.blade.php

```
<!DOCTYPE html>
<html>

<head>
    <title>{{ $thread->title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 70%;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 0 10px #ccc;
        }

        h1,
        h2,
        h3 {
            text-align: center;
        }

        .thread-body {
            margin-bottom: 30px;
        }

        .reply {
            border-top: 1px solid #ddd;
            padding: 10px 0;
        }

        .reply p {
            margin: 5px 0;
        }

        form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            font-size: 16px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        form button {
            padding: 10px 15px;
            font-size: 16px;
            background: #28a745;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        form button:hover {
            background: #218838;
        }

        .back {
            display: inline-block;
            margin-bottom: 20px;
            text-decoration: none;
            color: #007bff;
        }

        .error {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>{{ $thread->title }}</h1>
        <a href="{{ route('threads.index') }}" class="back">&larr; Back to Threads</a>
        <div class="thread-body">
            <p>{{ $thread->body }}</p>
            <p><strong>By:</strong> {{ $thread->user->name }} | {{ $thread->created_at->format('d M Y H:i') }}</p>
        </div>

        <h3>Replies</h3>
        @foreach($thread->posts as $post)
            <div class="reply">
                <p>{{ $post->body }}</p>
                <p><strong>By:</strong> {{ $post->user->name }} | {{ $post->created_at->format('d M Y H:i') }}</p>
            </div>
        @endforeach

        @if ($errors->any())
            <div class="error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('posts.store', $thread) }}" method="POST">
            @csrf
            <textarea name="body" rows="4" placeholder="Write a reply..." required></textarea>
            <button type="submit">Post Reply</button>
        </form>
    </div>
</body>

</html>

```




## STEP 8: Run Application

### Run:

```
php artisan serve

```
### Open

```
 http://127.0.0.1:8000

```

#### Explanation:

Starts Laravel server at http://127.0.0.1:8000 to access the forum.




## Expected Output:


### Register Page:


<img width="1919" height="968" alt="Screenshot 2026-03-02 133303" src="https://github.com/user-attachments/assets/af776988-a324-43d5-b208-feaefe955c6c" />


### Login Page:


<img width="1919" height="950" alt="Screenshot 2026-03-02 142905" src="https://github.com/user-attachments/assets/fb2ad190-6688-4a09-954f-3e1962323a35" />


### Forum Thread Page:


<img width="1919" height="938" alt="Screenshot 2026-03-02 133514" src="https://github.com/user-attachments/assets/66f1ce10-1b49-4a07-a08e-d8d402b180f7" />


### Create Thread:


<img width="1919" height="914" alt="Screenshot 2026-03-02 140649" src="https://github.com/user-attachments/assets/6aaeb84f-c1da-4d4b-9f60-867ef3db8b65" />


#### After Create Thread:

<img width="1919" height="840" alt="Screenshot 2026-03-02 140659" src="https://github.com/user-attachments/assets/d7b67b8b-1b2c-4340-b027-29714780cce0" />


### Edit Thread:


<img width="1919" height="928" alt="Screenshot 2026-03-02 140716" src="https://github.com/user-attachments/assets/36cd87d4-5a17-4aef-b62f-90897749bf0f" />


### Show Details and post:


<img width="1919" height="914" alt="Screenshot 2026-03-02 140741" src="https://github.com/user-attachments/assets/2eb506bb-e3e3-49b2-882c-216e3c84f493" />

<img width="1919" height="847" alt="Screenshot 2026-03-02 140752" src="https://github.com/user-attachments/assets/66b844e5-2ed0-48e5-bf0e-ab88186f9cc6" />


### Delete Thread:


<img width="1919" height="951" alt="image" src="https://github.com/user-attachments/assets/9ba62828-8b6b-4d73-a826-00d49b66345f" />


---


# Project Folder Structure:

```
PHP_Laravel12_Forum/
├── app/
│   ├── Console/
│   ├── Exceptions/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── ThreadController.php
│   │   │   └── PostController.php
│   │   ├── Middleware/
│   │   └── Kernel.php
│   ├── Models/
│   │   ├── Thread.php
│   │   ├── Post.php
│   │   └── User.php
│   ├── Providers/
│   └── ...
├── bootstrap/
├── config/
├── database/
│   ├── factories/
│   ├── migrations/
│   │   ├── xxxx_create_threads_table.php
│   │   └── xxxx_create_posts_table.php
│   └── seeders/
├── public/
│   ├── index.php
│   └── ...
├── resources/
│   ├── views/
│   │   ├── threads/
│   │   │   ├── index.blade.php
│   │   │   ├── create.blade.php
│   │   │   ├── edit.blade.php
│   │   │   └── show.blade.php
│   │   └── layouts/   (optional if you use a master layout)
│   └── ...
├── routes/
│   └── web.php
├── storage/
├── tests/
├── vendor/
├── composer.json
├── package.json
├── vite.config.js
└── README.md
```
