<!DOCTYPE html>
<html>
<head>
    <title>Forum Threads</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif; background: #f5f5f5; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        .header { background: white; border-radius: 12px; padding: 24px; margin-bottom: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .header h1 { font-size: 28px; color: #1a1a1a; margin-bottom: 8px; }
        .stats-bar { background: white; border-radius: 12px; padding: 16px 24px; margin-bottom: 24px; display: flex; gap: 32px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .stat { display: flex; align-items: baseline; gap: 8px; }
        .stat-value { font-size: 24px; font-weight: bold; color: #1a73e8; }
        .filters { background: white; border-radius: 12px; padding: 16px 24px; margin-bottom: 24px; display: flex; flex-wrap: wrap; gap: 16px; align-items: center; justify-content: space-between; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .filter-group { display: flex; gap: 12px; align-items: center; }
        .filter-group select, .filter-group input { padding: 8px 12px; border: 1px solid #ddd; border-radius: 8px; }
        .filter-group button { background: #1a73e8; color: white; border: none; padding: 8px 16px; border-radius: 8px; cursor: pointer; }
        .create-btn { background: #28a745; color: white; padding: 8px 20px; border-radius: 8px; text-decoration: none; }
        .categories { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 24px; }
        .category-pill { background: #e9ecef; padding: 6px 14px; border-radius: 20px; text-decoration: none; color: #495057; font-size: 13px; }
        .category-pill.active { background: #1a73e8; color: white; }
        .thread-list { background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .thread-item { display: flex; align-items: center; padding: 20px 24px; border-bottom: 1px solid #eee; }
        .thread-title { font-size: 18px; font-weight: 600; margin-bottom: 8px; }
        .btn-sm { padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 12px; border: none; cursor: pointer; }
        .btn-show { background: #1a73e8; color: white; }
        .btn-edit { background: #ffc107; color: #333; }
        .btn-delete { background: #dc3545; color: white; }
        .alert { background: #d4edda; color: #155724; padding: 12px 20px; border-radius: 8px; margin-bottom: 20px; }
    </style>
</head>
<body>
<div class="container">
    @if(session('success')) <div class="alert">{{ session('success') }}</div> @endif
    <div class="header"><h1>Community Forum</h1></div>
    <div class="stats-bar">
        <div class="stat"><span class="stat-value">{{ \App\Models\Thread::count() }}</span> Threads</div>
        <div class="stat"><span class="stat-value">{{ \App\Models\Post::count() }}</span> Replies</div>
        <div class="stat"><span class="stat-value">{{ \App\Models\User::count() }}</span> Members</div>
    </div>
    <div class="categories">
        <a href="{{ route('threads.index') }}" class="category-pill {{ !isset($category) ? 'active' : '' }}">All</a>
        @foreach($categories as $cat)
            <a href="{{ route('threads.byCategory', $cat) }}" class="category-pill {{ isset($category) && $category->id == $cat->id ? 'active' : '' }}">{{ $cat->name }}</a>
        @endforeach
    </div>
    <div class="filters">
        <form method="GET" action="{{ isset($category) ? route('threads.byCategory', $category) : route('threads.index') }}" class="filter-group">
            <input type="text" name="search" placeholder="Search..." value="{{ request('search') }}">
            <select name="sort">
                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Popular</option>
                <option value="most_liked" {{ request('sort') == 'most_liked' ? 'selected' : '' }}>Most Liked</option>
            </select>
            <button type="submit">Apply</button>
        </form>
        <a href="{{ route('threads.create') }}" class="create-btn">+ New Thread</a>
    </div>
    <div class="thread-list">
        @forelse($threads as $thread)
            <div class="thread-item">
                <div class="thread-content" style="flex:1;">
                    <div class="thread-title"><a href="{{ route('threads.show', $thread) }}">{{ $thread->title }}</a></div>
                    <div class="thread-meta">By {{ $thread->user->name }} | {{ $thread->created_at->diffForHumans() }}</div>
                </div>
                <div class="thread-actions">
                    <a href="{{ route('threads.show', $thread) }}" class="btn-sm btn-show">View</a>
                    @if(auth()->id() === $thread->user_id)
                        <a href="{{ route('threads.edit', $thread) }}" class="btn-sm btn-edit">Edit</a>
                        <form action="{{ route('threads.destroy', $thread) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-sm btn-delete" onclick="return confirm('Delete?')">Delete</button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div style="padding: 20px; text-align: center;">No threads found.</div>
        @endforelse
    </div>
    <div class="pagination" style="margin-top:20px;">
        {{ $threads->links() }}
    </div>
</div>
</body>
</html>