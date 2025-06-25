@extends('layouts.app')

@section('title', 'Login - ' . config('app.name'))

@section('meta')
    <meta name="description" content="Sign in to your {{ config('app.name') }} account to access exclusive boxing content, purchase tickets, and join the community.">
    <meta name="keywords" content="login, sign in, {{ config('app.name') }}, boxing">
@endsection

@section('content')
<!-- Page Banner -->
<x-page-banner 
    title="Welcome Back" 
    subtitle="Sign in to access your account and exclusive boxing content"
    :breadcrumbs="[['title' => 'Login']]" />

<div class="auth-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="auth-card">
                    <div class="auth-header">
                        <div class="auth-logo">
                            <a href="{{ route('home') }}">
                                <img src="{{ asset('assets/images/logo-2.svg') }}" alt="{{ config('app.name') }} Logo">
                            </a>
                        </div>
                        <h2>Sign In</h2>
                        <p>Access your boxing experience</p>
                    </div>

                    @if(session('status'))
                        <div class="alert alert-success" role="alert">
                            <i class="fa-solid fa-check-circle me-2"></i>
                            {{ session('status') }}
                        </div>
                    @endif

                    @if(isset($errors) && $errors->any())
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> There were some problems with your input.
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group">
                            <label for="email">{{ __('Email Address') }}</label>
                            <input id="email" type="email" class="form-control @if(isset($errors)) @error('email') is-invalid @enderror @endif" 
                                   name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                                   placeholder="Enter your email address">
                            @if(isset($errors))
                                @error('email')
                                    <div class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="password">{{ __('Password') }}</label>
                            <input id="password" type="password" class="form-control @if(isset($errors)) @error('password') is-invalid @enderror @endif" 
                                   name="password" required autocomplete="current-password"
                                   placeholder="Enter your password">
                            @if(isset($errors))
                                @error('password')
                                    <div class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            @endif
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                {{ __('Remember Me') }}
                            </label>
                        </div>

                        <button type="submit" class="btn btn-auth">
                            <i class="fa-solid fa-sign-in-alt me-2"></i>
                            {{ __('Sign In') }}
                        </button>

                        <div class="auth-links">
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                            @endif
                        </div>

                        <div class="auth-links">
                            <span>Don't have an account?</span>
                            <a href="{{ route('register') }}">
                                {{ __('Create Account') }}
                            </a>
                        </div>
                    </form>

                    {{-- Optional Social Login Section --}}
                    {{-- 
                    <div class="social-auth">
                        <a href="#" class="social-btn">
                            <i class="fa-brands fa-google"></i>
                            Continue with Google
                        </a>
                        <a href="#" class="social-btn">
                            <i class="fa-brands fa-facebook-f"></i>
                            Continue with Facebook
                        </a>
                    </div>
                    --}}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom JavaScript for Enhanced UX -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add focus effects to form inputs
    const inputs = document.querySelectorAll('.form-control');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });
        
        input.addEventListener('blur', function() {
            if (this.value === '') {
                this.parentElement.classList.remove('focused');
            }
        });
        
        // Check if input has value on load
        if (input.value !== '') {
            input.parentElement.classList.add('focused');
        }
    });
    
    // Add loading state to submit button
    const form = document.querySelector('form');
    const submitBtn = document.querySelector('.btn-auth');
    
    form.addEventListener('submit', function(e) {
        submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i>Signing In...';
        submitBtn.disabled = true;
    });
});
</script>

<style>
.form-group.focused label {
    color: var(--theme-color) !important;
    transform: scale(0.9);
}

.btn-auth:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

.fa-spinner {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
@endsection
