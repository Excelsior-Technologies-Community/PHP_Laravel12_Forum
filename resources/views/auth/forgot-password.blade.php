<x-guest-layout>
    <h2>Reset Password</h2>
    
    <p style="margin-bottom: 20px; color: #666; text-align: center;">
        Forgot your password? No problem. Just let us know your email address and we will email you a password reset link.
    </p>
    
    @if ($errors->any())
        <div class="error">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif
    
    @if (session('status'))
        <div style="background: #d4edda; color: #155724; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
            {{ session('status') }}
        </div>
    @endif
    
    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        
        <div class="form-group">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required>
        </div>
        
        <button type="submit" class="btn">Email Password Reset Link</button>
        
        <div class="text-center" style="margin-top: 16px;">
            <a href="{{ route('login') }}">Back to Login</a>
        </div>
    </form>
</x-guest-layout>