<!DOCTYPE html>
<html>
<head>
    <title>Create Thread</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif; background: #f5f5f5; padding: 20px; }
        .container { max-width: 800px; margin: 40px auto; background: white; border-radius: 12px; padding: 32px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        h1 { margin-bottom: 8px; font-size: 28px; }
        .subtitle { color: #666; margin-bottom: 24px; }
        .back-link { display: inline-block; margin-bottom: 20px; color: #1a73e8; text-decoration: none; }
        label { display: block; margin-bottom: 8px; font-weight: 500; color: #333; }
        input, select, textarea { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; margin-bottom: 20px; font-family: inherit; }
        textarea { resize: vertical; }
        button { background: #28a745; color: white; border: none; padding: 12px 28px; border-radius: 8px; cursor: pointer; font-size: 16px; font-weight: 500; }
        button:hover { background: #1e7e34; }
        .error { background: #f8d7da; color: #721c24; padding: 12px; border-radius: 8px; margin-bottom: 16px; }
    </style>
</head>
<body>
<div class="container">
    <a href="{{ route('threads.index') }}" class="back-link">← Back to Threads</a>
    <h1>Start a New Discussion</h1>
    <p class="subtitle">Share your question or idea with the community</p>
    
    @if ($errors->any())
        <div class="error">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif
    
    <form action="{{ route('threads.store') }}" method="POST">
        @csrf
        <label>Title</label>
        <input type="text" name="title" placeholder="What's your question about?" value="{{ old('title') }}" required>
        
        <label>Category</label>
        <select name="category_id" required>
            <option value="">Select a category</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
            @endforeach
        </select>
        
        <label>Content</label>
        <textarea name="body" rows="8" placeholder="Describe your question or idea in detail..." required>{{ old('body') }}</textarea>
        
        <button type="submit">Create Thread</button>
    </form>
</div>
</body>
</html>