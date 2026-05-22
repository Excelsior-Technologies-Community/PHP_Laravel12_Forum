<x-guest-layout>
    <h2>Confirm Password</h2>
    
    <p style="margin-bottom: 20px; color: #666; text-align: center;">
        This is a secure area of the application. Please confirm your password before continuing.
    </p>
    
    @if ($errors->any())
        <div class="error">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif
    
    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf
        
        <div class="form-group">
            <label for="password">Password</label>
            <input id="password" type="password" name="password" required autofocus>
        </div>
        
        <button type="submit" class="btn">Confirm</button>
    </form>
</x-guest-layout>