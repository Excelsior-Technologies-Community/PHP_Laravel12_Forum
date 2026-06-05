<!DOCTYPE html>
<html>
<head>
    <title>Edit Thread</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif; background: #f5f5f5; padding: 20px; }
        .container { max-width: 800px; margin: 40px auto; background: white; border-radius: 12px; padding: 32px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        h1 { margin-bottom: 24px; font-size: 24px; color: #1a1a1a; }
        .back-link { display: inline-block; margin-bottom: 20px; color: #1a73e8; text-decoration: none; }
        label { display: block; margin-bottom: 8px; font-weight: 500; color: #333; }
        input, select, textarea { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; margin-bottom: 20px; font-family: inherit; }
        textarea { resize: vertical; }
        button { background: #1a73e8; color: white; border: none; padding: 10px 24px; border-radius: 8px; cursor: pointer; font-size: 14px; }
        button:hover { background: #1557b0; }
        .error { background: #f8d7da; color: #721c24; padding: 12px; border-radius: 8px; margin-bottom: 16px; }
    </style>
</head>
<body>
<div class="container">
    <a href="{{ route('threads.show', $thread) }}" class="back-link">← Back to Thread</a>
    <h1>Edit Thread</h1>

    @if ($errors->any())
        <div class="error">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form action="{{ route('threads.update', $thread) }}" method="POST">
        @csrf @method('PUT')

        <label>Title</label>
        <input type="text" name="title" value="{{ old('title', $thread->title) }}" required>

        {{-- FIXED: category_id field add karyu --}}
        <label>Category</label>
        <select name="category_id" required>
            <option value="">Select a category</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}"
                    {{ old('category_id', $thread->category_id) == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>

        <label>Content</label>
        <textarea name="body" rows="8" required>{{ old('body', $thread->body) }}</textarea>

        <button type="submit">Update Thread</button>
    </form>
</div>
</body>
</html>