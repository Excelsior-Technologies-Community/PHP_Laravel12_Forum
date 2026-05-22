{{-- resources/views/posts/edit.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <title>Edit Reply</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif; background: #f5f5f5; padding: 20px; }
        .container { max-width: 800px; margin: 40px auto; background: white; border-radius: 12px; padding: 32px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        h1 { margin-bottom: 24px; font-size: 24px; color: #1a1a1a; }
        .back-link { display: inline-block; margin-bottom: 20px; color: #1a73e8; text-decoration: none; }
        textarea { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; font-family: inherit; resize: vertical; margin-bottom: 16px; }
        button { background: #1a73e8; color: white; border: none; padding: 10px 24px; border-radius: 8px; cursor: pointer; font-size: 14px; }
        button:hover { background: #1557b0; }
        .error { background: #f8d7da; color: #721c24; padding: 12px; border-radius: 8px; margin-bottom: 16px; }
    </style>
</head>
<body>
<div class="container">
    <a href="{{ route('threads.show', $post->thread) }}" class="back-link">← Back to Thread</a>
    <h1>Edit Reply</h1>
    
    @if ($errors->any())
        <div class="error">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif
    
    <form action="{{ route('posts.update', $post) }}" method="POST">
        @csrf @method('PUT')
        <textarea name="body" rows="6" required>{{ old('body', $post->body) }}</textarea>
        <button type="submit">Update Reply</button>
    </form>
</div>
</body>
</html>