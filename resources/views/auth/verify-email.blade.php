<x-guest-layout>
    <h2>Verify Your Email</h2>
    
    <p style="margin-bottom: 20px; color: #666; text-align: center;">
        Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.
    </p>
    
    @if (session('status') == 'verification-link-sent')
        <div style="background: #d4edda; color: #155724; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
            A new verification link has been sent to the email address you provided during registration.
        </div>
    @endif
    
    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn">Resend Verification Email</button>
    </form>
    
    <div class="text-center" style="margin-top: 16px;">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" style="background: none; border: none; color: #dc3545; cursor: pointer;">Logout</button>
        </form>
    </div>
</x-guest-layout>