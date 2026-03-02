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