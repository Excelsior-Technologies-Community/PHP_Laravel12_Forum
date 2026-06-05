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
        .thread-card { background: white; border-radius: 12px; margin-bottom: 24px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .thread-header { padding: 24px; border-bottom: 1px solid #eee; }
        .thread-header h1 { font-size: 28px; margin-bottom: 12px; color: #1a1a1a; }
        .thread-meta { display: flex; gap: 16px; font-size: 14px; color: #666; flex-wrap: wrap; align-items: center; }
        .thread-meta .category { background: #e9ecef; padding: 4px 12px; border-radius: 20px; }
        .thread-meta .reputation { color: #f5a623; }
        .thread-body { padding: 24px; font-size: 16px; line-height: 1.6; color: #333; }
        .thread-actions { padding: 12px 24px; border-top: 1px solid #eee; display: flex; gap: 10px; }
        .replies-header { margin: 24px 0 16px; font-size: 20px; font-weight: 600; color: #1a1a1a; }
        .reply-card { background: white; border-radius: 12px; margin-bottom: 16px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .reply-card.best { border: 2px solid #f5a623; background: #fffbf0; }
        .best-badge { background: #f5a623; color: white; padding: 6px 16px; font-size: 12px; font-weight: bold; display: inline-block; border-radius: 0 0 8px 0; }
        .reply-header { display: flex; justify-content: space-between; align-items: center; padding: 16px 20px; background: #f8f9fa; border-bottom: 1px solid #eee; }
        .reply-author { display: flex; align-items: center; gap: 12px; }
        .author-avatar { width: 40px; height: 40px; border-radius: 50%; background: #1a73e8; color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 14px; }
        .author-name { font-weight: 600; color: #1a1a1a; }
        .author-reputation { font-size: 12px; color: #f5a623; margin-left: 6px; }
        .reply-date { font-size: 12px; color: #999; }
        .reply-body { padding: 20px; font-size: 15px; line-height: 1.6; color: #333; }
        .reply-actions { padding: 12px 20px; border-top: 1px solid #eee; display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }
        .like-btn { background: none; border: 1px solid #ddd; padding: 6px 14px; border-radius: 20px; cursor: pointer; font-size: 13px; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s; }
        .like-btn:hover { border-color: #ff4d4d; color: #ff4d4d; }
        .like-btn.liked { background: #ff4d4d; color: white; border-color: #ff4d4d; }
        .best-btn { background: none; border: 1px solid #f5a623; padding: 6px 14px; border-radius: 20px; cursor: pointer; font-size: 13px; color: #f5a623; transition: all 0.2s; }
        .best-btn:hover { background: #f5a623; color: white; }
        .remove-best-btn { background: none; border: 1px solid #dc3545; padding: 6px 14px; border-radius: 20px; cursor: pointer; font-size: 13px; color: #dc3545; }
        .edit-btn { background: none; border: 1px solid #ffc107; padding: 6px 14px; border-radius: 20px; cursor: pointer; font-size: 13px; text-decoration: none; color: #856404; display: inline-block; }
        .delete-btn { background: none; border: 1px solid #dc3545; padding: 6px 14px; border-radius: 20px; cursor: pointer; font-size: 13px; color: #dc3545; }
        .btn-sm { padding: 6px 14px; border-radius: 6px; text-decoration: none; font-size: 13px; border: none; cursor: pointer; }
        .btn-edit { background: #ffc107; color: #333; }
        .btn-delete { background: #dc3545; color: white; }
        .reply-form { background: white; border-radius: 12px; padding: 24px; margin-top: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .reply-form h3 { margin-bottom: 16px; font-size: 18px; color: #1a1a1a; }
        .reply-form textarea { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; margin-bottom: 12px; font-size: 14px; font-family: inherit; resize: vertical; }
        .reply-form textarea:focus { outline: none; border-color: #1a73e8; }
        .reply-form button { background: #1a73e8; color: white; border: none; padding: 10px 24px; border-radius: 8px; cursor: pointer; font-size: 14px; }
        .reply-form button:hover { background: #1557b0; }
        .alert { background: #d4edda; color: #155724; padding: 12px 20px; border-radius: 8px; margin-bottom: 20px; }
        .login-prompt { background: white; border-radius: 12px; padding: 20px; margin-top: 24px; text-align: center; box-shadow: 0 1px 3px rgba(0,0,0,0.1); color: #666; }
        .login-prompt a { color: #1a73e8; text-decoration: none; font-weight: 500; }
    </style>
</head>
<body>
<div class="container">

    @if(session('success'))
        <div class="alert">{{ session('success') }}</div>
    @endif

    <a href="{{ route('threads.index') }}" class="back-link">← Back to Threads</a>

    {{-- Thread Card --}}
    <div class="thread-card">
        <div class="thread-header">
            <h1>{{ $thread->title }}</h1>
            <div class="thread-meta">
                <span class="category">{{ $thread->category->name ?? 'General' }}</span>
                <span>Posted by <strong>{{ $thread->user->name ?? 'Unknown' }}</strong></span>
                <span>{{ $thread->created_at->format('M d, Y') }}</span>
                <span class="reputation">⭐ {{ $thread->user->reputation ?? 0 }} reputation</span>
            </div>
        </div>
        <div class="thread-body">{{ $thread->body }}</div>

        {{-- Thread owner actions --}}
        @if(auth()->id() === $thread->user_id)
            <div class="thread-actions">
                <a href="{{ route('threads.edit', $thread) }}" class="btn-sm btn-edit">✏️ Edit Thread</a>
                <form action="{{ route('threads.destroy', $thread) }}" method="POST" style="display:inline;">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-sm btn-delete" onclick="return confirm('Delete this thread?')">🗑️ Delete Thread</button>
                </form>
            </div>
        @endif
    </div>

    {{-- Replies Count --}}
    <div class="replies-header">{{ $thread->posts->count() }} {{ $thread->posts->count() === 1 ? 'Reply' : 'Replies' }}</div>

    {{-- Replies --}}
    @forelse($thread->posts as $post)
        <div class="reply-card {{ $post->isBest() ? 'best' : '' }}">

            @if($post->isBest())
                <div class="best-badge">✓ Best Answer</div>
            @endif

            <div class="reply-header">
                <div class="reply-author">
                    <div class="author-avatar">{{ strtoupper(substr($post->user->name ?? 'U', 0, 2)) }}</div>
                    <div>
                        <span class="author-name">{{ $post->user->name ?? 'Guest' }}</span>
                        <span class="author-reputation">⭐ {{ $post->user->reputation ?? 0 }}</span>
                    </div>
                </div>
                <div class="reply-date">{{ $post->created_at->diffForHumans() }}</div>
            </div>

            <div class="reply-body">{{ $post->body }}</div>

            <div class="reply-actions">

                {{-- Like Button --}}
                @auth
                    @php $userLiked = $post->likes->contains('user_id', auth()->id()); @endphp
                    <form action="{{ route('likes.toggle', $post) }}" method="POST">
                        @csrf
                        <button type="submit" class="like-btn {{ $userLiked ? 'liked' : '' }}">
                            👍 {{ $post->likes->count() }}
                        </button>
                    </form>
                @else
                    <span class="like-btn" style="cursor:default;">👍 {{ $post->likes->count() }}</span>
                @endauth

                {{-- Best Reply Button (only thread owner, not their own post) --}}
                @auth
                    @if(auth()->id() === $thread->user_id && auth()->id() !== $post->user_id)
                        @if(!$post->isBest())
                            <form action="{{ route('posts.best.store', $post) }}" method="POST">
                                @csrf
                                <button type="submit" class="best-btn">⭐ Mark as Best</button>
                            </form>
                        @else
                            <form action="{{ route('posts.best.destroy', $post) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="remove-best-btn">❌ Remove Best</button>
                            </form>
                        @endif
                    @endif
                @endauth

                {{-- Edit / Delete (post owner only) --}}
                @if(auth()->id() === $post->user_id)
                    <a href="{{ route('posts.edit', $post) }}" class="edit-btn">✏️ Edit</a>
                    <form action="{{ route('posts.destroy', $post) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="submit" class="delete-btn" onclick="return confirm('Delete this reply?')">🗑️ Delete</button>
                    </form>
                @endif

            </div>
        </div>
    @empty
        <div style="background:white; border-radius:12px; padding:30px; text-align:center; color:#666; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            No replies yet. Be the first to reply!
        </div>
    @endforelse

    {{-- Reply Form --}}
    @auth
        <div class="reply-form">
            <h3>Write a Reply</h3>
            <form action="{{ route('posts.store', $thread) }}" method="POST">
                @csrf
                <textarea name="body" rows="4" placeholder="Share your thoughts..." required></textarea>
                @error('body') <p style="color:red; margin-bottom:10px; font-size:13px;">{{ $message }}</p> @enderror
                <button type="submit">Post Reply</button>
            </form>
        </div>
    @else
        <div class="login-prompt">
            <a href="{{ route('login') }}">Login</a> or <a href="{{ route('register') }}">Register</a> to post a reply.
        </div>
    @endauth

</div>
</body>
</html>