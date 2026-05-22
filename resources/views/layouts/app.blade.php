<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Forum') }}</title>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;
            background: #f5f5f5;
        }
        
        .navbar {
            background: white;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            padding: 16px 24px;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .navbar-inner {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo a {
            font-size: 20px;
            font-weight: bold;
            color: #1a73e8;
            text-decoration: none;
        }
        
        .nav-links {
            display: flex;
            gap: 20px;
            align-items: center;
        }
        
        .nav-links a {
            color: #333;
            text-decoration: none;
        }
        
        .nav-links a:hover {
            color: #1a73e8;
        }
        
        .user-menu {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        
        .username {
            font-weight: 500;
        }
        
        .logout-form {
            display: inline;
        }
        
        .logout-btn {
            background: none;
            border: none;
            color: #dc3545;
            cursor: pointer;
            font-size: 14px;
        }
        
        .logout-btn:hover {
            text-decoration: underline;
        }
        
        .main-content {
            max-width: 1200px;
            margin: 24px auto;
            padding: 0 24px;
        }
        
        .alert {
            background: #d4edda;
            color: #155724;
            padding: 12px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-inner">
            <div class="logo">
                <a href="{{ route('threads.index') }}">{{ config('app.name', 'Forum') }}</a>
            </div>
            
            <div class="nav-links">
                @auth
                    <a href="{{ route('threads.index') }}">Forum</a>
                    <a href="{{ route('threads.create') }}">New Thread</a>
                    <div class="user-menu">
                        <span class="username">{{ Auth::user()->name }}</span>
                        <a href="{{ route('profile.show', Auth::user()) }}">Profile</a>
                        <form method="POST" action="{{ route('logout') }}" class="logout-form">
                            @csrf
                            <button type="submit" class="logout-btn">Logout</button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}">Login</a>
                    <a href="{{ route('register') }}">Register</a>
                @endauth
            </div>
        </div>
    </nav>
    
    <main class="main-content">
        @if(session('success'))
            <div class="alert">
                {{ session('success') }}
            </div>
        @endif
        
        {{ $slot }}
    </main>
</body>
</html>