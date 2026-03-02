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