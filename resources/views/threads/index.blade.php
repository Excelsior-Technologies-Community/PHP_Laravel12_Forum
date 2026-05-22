{{-- resources/views/threads/index.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <title>Forum Threads</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif; background: #f5f5f5; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        
        /* Header */
        .header { background: white; border-radius: 12px; padding: 24px; margin-bottom: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .header h1 { font-size: 28px; color: #1a1a1a; margin-bottom: 8px; }
        .header p { color: #666; }
        
        /* Stats Bar */
        .stats-bar { background: white; border-radius: 12px; padding: 16px 24px; margin-bottom: 24px; display: flex; gap: 32px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .stat { display: flex; align-items: baseline; gap: 8px; }
        .stat-value { font-size: 24px; font-weight: bold; color: #1a73e8; }
        .stat-label { color: #666; font-size: 14px; }
        
        /* Filters */
        .filters { background: white; border-radius: 12px; padding: 16px 24px; margin-bottom: 24px; display: flex; flex-wrap: wrap; gap: 16px; align-items: center; justify-content: space-between; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .filter-group { display: flex; gap: 12px; align-items: center; flex-wrap: wrap; }
        .filter-group select, .filter-group input { padding: 8px 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; }
        .filter-group button { background: #1a73e8; color: white; border: none; padding: 8px 16px; border-radius: 8px; cursor: pointer; font-size: 14px; }
        .filter-group button:hover { background: #1557b0; }
        .clear-search a { color: #dc3545; text-decoration: none; font-size: 14px; }
        .create-btn { background: #28a745; color: white; padding: 8px 20px; border-radius: 8px; text-decoration: none; font-size: 14px; font-weight: 500; }
        .create-btn:hover { background: #1e7e34; }
        
        /* Category Pills */
        .categories { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 24px; }
        .category-pill { background: #e9ecef; padding: 6px 14px; border-radius: 20px; text-decoration: none; color: #495057; font-size: 13px; }
        .category-pill.active { background: #1a73e8; color: white; }
        .category-pill:hover:not(.active) { background: #dee2e6; }
        
        /* Thread List */
        .thread-list { background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .thread-item { display: flex; align-items: center; padding: 20px 24px; border-bottom: 1px solid #eee; transition: background 0.2s; }
        .thread-item:hover { background: #f8f9fa; }
        
        .thread-votes { text-align: center; min-width: 60px; margin-right: 16px; }
        .vote-count { font-size: 20px; font-weight: bold; color: #1a73e8; }
        .vote-label { font-size: 11px; color: #999; }
        
        .thread-content { flex: 1; }
        .thread-title { font-size: 18px; font-weight: 600; margin-bottom: 8px; }
        .thread-title a { color: #1a1a1a; text-decoration: none; }
        .thread-title a:hover { color: #1a73e8; }
        
        .thread-meta { display: flex; gap: 16px; font-size: 13px; color: #666; margin-bottom: 8px; flex-wrap: wrap; }
        .thread-meta .category { background: #e9ecef; padding: 2px 8px; border-radius: 12px; color: #495057; font-size: 11px; }
        .thread-meta .reputation { color: #f5a623; }
        
        .thread-preview { font-size: 14px; color: #666; margin-top: 4px; }
        
        .thread-stats { display: flex; gap: 16px; min-width: 120px; text-align: center; }
        .thread-stats div { font-size: 13px; color: #666; }
        .thread-stats strong { font-size: 16px; color: #333; display: block; }
        
        .thread-actions { display: flex; gap: 8px; margin-left: 16px; }
        .btn-sm { padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 12px; border: none; cursor: pointer; }
        .btn-show { background: #1a73e8; color: white; }
        .btn-edit { background: #ffc107; color: #333; }
        .btn-delete { background: #dc3545; color: white; }
        
        /* Pagination */
        .pagination { margin-top: 24px; display: flex; justify-content: center; gap: 8px; }
        .pagination a, .pagination span { padding: 8px 14px; background: white; border-radius: 8px; text-decoration: none; color: #1a73e8; }
        .pagination .active { background: #1a73e8; color: white; }
        
        /* Alert */
        .alert { background: #d4edda; color: #155724; padding: 12px 20px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #c3e6cb; }
    </style>
</head>
<body>
<div class="container">
    @if(session('success'))
        <div class="alert">{{ session('success') }}</div>
    @endif

    <div class="header">
        <h1>Community Forum</h1>
        <p>Ask questions, share knowledge, and connect with developers</p>
    </div>

    <div class="stats-bar">
        <div class="stat"><span class="stat-value">{{ \App\Models\Thread::count() }}</span><span class="stat-label">Threads</span></div>
        <div class="stat"><span class="stat-value">{{ \App\Models\Post::count() }}</span><span class="stat-label">Replies</span></div>
        <div class="stat"><span class="stat-value">{{ \App\Models\User::count() }}</span><span class="stat-label">Members</span></div>
    </div>

    <div class="categories">
        <a href="{{ route('threads.index') }}" class="category-pill {{ !isset($category) ? 'active' : '' }}">All Topics</a>
        @foreach($categories as $cat)
            <a href="{{ route('threads.byCategory', $cat) }}" class="category-pill {{ isset($category) && $category->id == $cat->id ? 'active' : '' }}">
                {{ $cat->name }}
            </a>
        @endforeach
    </div>

    <div class="filters">
        <div class="filter-group">
            <form method="GET" action="{{ isset($category) ? route('threads.byCategory', $category) : route('threads.index') }}" style="display: flex; gap: 12px;">
                <input type="text" name="search" placeholder="Search threads..." value="{{ request('search') }}">
                <select name="sort">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                    <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Replies</option>
                    <option value="most_liked" {{ request('sort') == 'most_liked' ? 'selected' : '' }}>Most Liked</option>
                </select>
                <button type="submit">Apply</button>
            </form>
            @if(request('search') || request('sort'))
                <div class="clear-search"><a href="{{ isset($category) ? route('threads.byCategory', $category) : route('threads.index') }}">Clear Filters</a></div>
            @endif
        </div>
        <a href="{{ route('threads.create') }}" class="create-btn">+ New Thread</a>
    </div>

    <div class="thread-list">
        @forelse($threads as $thread)
            <div class="thread-item">
                <div class="thread-votes">
                    <div class="vote-count">{{ $thread->like_count }}</div>
                    <div class="vote-label">likes</div>
                </div>
                
                <div class="thread-content">
                    <div class="thread-title">
                        <a href="{{ route('threads.show', $thread) }}">{{ $thread->title }}</a>
                    </div>
                    <div class="thread-meta">
                        <span class="category">{{ $thread->category->name ?? 'Uncategorized' }}</span>
                        <span>By <strong>{{ $thread->user->name }}</strong></span>
                        <span>{{ $thread->created_at->diffForHumans() }}</span>
                        <span class="reputation">⭐ {{ $thread->user->reputation }} pts</span>
                    </div>
                    <div class="thread-preview">{{ Str::limit($thread->body, 100) }}</div>
                </div>
                
                <div class="thread-stats">
                    <div><strong>{{ $thread->posts_count ?? $thread->posts->count() }}</strong> replies</div>
                </div>
                
                <div class="thread-actions">
                    <a href="{{ route('threads.show', $thread) }}" class="btn-sm btn-show">View</a>
                    @if(auth()->id() === $thread->user_id)
                        <a href="{{ route('threads.edit', $thread) }}" class="btn-sm btn-edit">Edit</a>
                        <form action="{{ route('threads.destroy', $thread) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-sm btn-delete" onclick="return confirm('Delete this thread?')">Delete</button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div style="padding: 60px; text-align: center; color: #666;">No threads found. Be the first to create one!</div>
        @endforelse
    </div>

    <div class="pagination">
        {{ $threads->links() }}
    </div>
</div>
</body>
</html>