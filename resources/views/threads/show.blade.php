{{-- resources/views/threads/show.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <title>{{ $thread->title }} - Forum</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif; background: #f5f5f5; padding: 20px; }
        .container { max-width: 1000px; margin: 0 auto; }
        
        .back-link { margin-bottom: 20px; display: inline-block; color: #1a73e8; text-decoration: none; }
        .back-link:hover { text-decoration: underline; }
        
        /* Thread Card */
        .thread-card { background: white; border-radius: 12px; margin-bottom: 24px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .thread-header { padding: 24px; border-bottom: 1px solid #eee; }
        .thread-header h1 { font-size: 28px; margin-bottom: 12px; color: #1a1a1a; }
        .thread-meta { display: flex; gap: 16px; font-size: 14px; color: #666; flex-wrap: wrap; }
        .thread-meta .category { background: #e9ecef; padding: 4px 12px; border-radius: 20px; }
        .thread-meta .reputation { color: #f5a623; }
        
        .thread-body { padding: 24px; font-size: 16px; line-height: 1.6; color: #333; }
        
        /* Replies Section */
        .replies-header { margin: 24px 0 16px; font-size: 20px; font-weight: 600; color: #1a1a1a; }
        
        .reply-card { background: white; border-radius: 12px; margin-bottom: 16px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1); transition: all 0.2s; }
        .reply-card.best { border: 2px solid #f5a623; background: #fffbf0; }
        .best-badge { background: #f5a623; color: white; padding: 4px 12px; font-size: 12px; font-weight: bold; display: inline-block; margin-bottom: 12px; border-radius: 4px; }
        
        .reply-header { display: flex; justify-content: space-between; align-items: center; padding: 16px 20px; background: #f8f9fa; border-bottom: 1px solid #eee; }
        .reply-author { display: flex; align-items: center; gap: 12px; }
        .author-avatar { width: 40px; height: 40px; border-radius: 50%; background: #1a73e8; color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; }
        .author-name { font-weight: 600; color: #1a1a1a; }
        .author-reputation { font-size: 12px; color: #f5a623; margin-left: 8px; }
        .reply-date { font-size: 12px; color: #999; }
        
        .reply-body { padding: 20px; font-size: 15px; line-height: 1.6; color: #333; }
        
        .reply-actions { padding: 12px 20px; border-top: 1px solid #eee; display: flex; gap: 12px; align-items: center; flex-wrap: wrap; }
        
        .like-form { display: inline; }
        .like-btn { background: none; border: 1px solid #ddd; padding: 6px 12px; border-radius: 20px; cursor: pointer; font-size: 13px; transition: all 0.2s; display: inline-flex; align-items: center; gap: 6px; }
        .like-btn:hover { background: #ff4d4d; color: white; border-color: #ff4d4d; }
        .like-btn.liked { background: #ff4d4d; color: white; border-color: #ff4d4d; }
        
        .best-btn { background: none; border: 1px solid #f5a623; padding: 6px 12px; border-radius: 20px; cursor: pointer; font-size: 13px; color: #f5a623; }
        .best-btn:hover { background: #f5a623; color: white; }
        
        .edit-btn, .delete-btn { background: none; border: 1px solid #ddd; padding: 6px 12px; border-radius: 20px; cursor: pointer; font-size: 13px; text-decoration: none; color: #666; display: inline-block; }
        .edit-btn:hover { background: #ffc107; border-color: #ffc107; color: #333; }
        .delete-btn:hover { background: #dc3545; border-color: #dc3545; color: white; }
        
        /* Reply Form */
        .reply-form { background: white; border-radius: 12px; padding: 24px; margin-top: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .reply-form h3 { margin-bottom: 16px; font-size: 18px; }
        .reply-form textarea { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; font-family: inherit; resize: vertical; margin-bottom: 12px; }
        .reply-form button { background: #1a73e8; color: white; border: none; padding: 10px 24px; border-radius: 8px; cursor: pointer; font-size: 14px; }
        .reply-form button:hover { background: #1557b0; }
        
        .error { background: #f8d7da; color: #721c24; padding: 12px; border-radius: 8px; margin-bottom: 16px; }
        .alert { background: #d4edda; color: #155724; padding: 12px 20px; border-radius: 8px; margin-bottom: 20px; }
    </style>
</head>
<body>
<div class="container">
    @if(session('success'))
        <div class="alert">{{ session('success') }}</div>
    @endif

    <a href="{{ route('threads.index') }}" class="back-link">← Back to Threads</a>

    <div class="thread-card">
        <div class="thread-header">
            <h1>{{ $thread->title }}</h1>
            <div class="thread-meta">
                <span class="category">{{ $thread->category->name ?? 'General' }}</span>
                <span>Posted by <strong>{{ $thread->user->name }}</strong></span>
                <span>{{ $thread->created_at->format('M d, Y') }}</span>
                <span class="reputation">⭐ {{ $thread->user->reputation }} reputation</span>
            </div>
        </div>
        <div class="thread-body">
            {{ $thread->body }}
        </div>
    </div>

    <div class="replies-header">
        {{ $thread->posts->count() }} Replies
    </div>

    @foreach($thread->posts as $post)
        <div class="reply-card {{ $post->isBest() ? 'best' : '' }}" id="reply-{{ $post->id }}">
            @if($post->isBest())
                <div class="best-badge">✓ Best Answer</div>
            @endif
            
            <div class="reply-header">
                <div class="reply-author">
                    <div class="author-avatar">{{ substr($post->user->name, 0, 2) }}</div>
                    <div>
                        <span class="author-name">{{ $post->user->name }}</span>
                        <span class="author-reputation">⭐ {{ $post->user->reputation }}</span>
                    </div>
                </div>
                <div class="reply-date">{{ $post->created_at->diffForHumans() }}</div>
            </div>
            
            <div class="reply-body">
                {{ $post->body }}
            </div>
            
            <div class="reply-actions">
                <!-- Like Button -->
                @php $userLiked = $post->likes->contains('user_id', auth()->id()); @endphp
                <form action="{{ route('posts.like', $post) }}" method="POST" class="like-form">
                    @csrf
                    <button type="submit" class="like-btn {{ $userLiked ? 'liked' : '' }}">
                        👍 {{ $post->likes->count() }} Like{{ $post->likes->count() != 1 ? 's' : '' }}
                    </button>
                </form>
                
                <!-- Best Reply (only thread owner) -->
                @if(auth()->id() === $thread->user_id && !$post->isBest() && auth()->id() !== $post->user_id)
                    <form action="{{ route('posts.best', $post) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="best-btn">⭐ Mark as Best</button>
                    </form>
                @endif
                
                @if($post->isBest() && auth()->id() === $thread->user_id)
                    <form action="{{ route('posts.best.remove', $post) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="submit" class="best-btn">❌ Remove Best</button>
                    </form>
                @endif
                
                <!-- Edit/Delete (post owner) -->
                @if(auth()->id() === $post->user_id)
                    <a href="{{ route('posts.edit', $post) }}" class="edit-btn">✏️ Edit</a>
                    <form action="{{ route('posts.destroy', $post) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="submit" class="delete-btn" onclick="return confirm('Delete this reply?')">🗑️ Delete</button>
                    </form>
                @endif
            </div>
        </div>
    @endforeach

    @if ($errors->any())
        <div class="error">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    @auth
        <div class="reply-form">
            <h3>Write a Reply</h3>
            <form action="{{ route('posts.store', $thread) }}" method="POST">
                @csrf
                <textarea name="body" rows="4" placeholder="Share your thoughts..."></textarea>
                <button type="submit">Post Reply</button>
            </form>
        </div>
    @else
        <div class="reply-form" style="text-align: center;">
            <p><a href="{{ route('login') }}">Login</a> to join the discussion</p>
        </div>
    @endauth
</div>
</body>
</html>