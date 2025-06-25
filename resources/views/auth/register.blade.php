@extends('layouts.app')

@section('title', 'Register - ' . config('app.name'))

@section('meta')
    <meta name="description" content="Create your {{ config('app.name') }} account to access exclusive content, purchase tickets, and join the boxing community.">
    <meta name="keywords" content="register, sign up, account, {{ config('app.name') }}, boxing">
@endsection

@section('content')
<!-- Page Banner -->
<x-page-banner 
    title="Join the Fight" 
    subtitle="Create your account to access exclusive boxing content and purchase tickets"
    :breadcrumbs="[['title' => 'Register']]" />

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
                        <h2>Create Account</h2>
                        <p>Join the boxing community</p>
                    </div>

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

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group">
                            <label for="name">{{ __('Full Name') }}</label>
                            <input id="name" type="text" class="form-control @if(isset($errors)) @error('name') is-invalid @enderror @endif" 
                                   name="name" value="{{ old('name') }}" required autocomplete="name" autofocus
                                   placeholder="Enter your full name">
                            @if(isset($errors))
                                @error('name')
                                    <div class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="email">{{ __('Email Address') }}</label>
                            <input id="email" type="email" class="form-control @if(isset($errors)) @error('email') is-invalid @enderror @endif" 
                                   name="email" value="{{ old('email') }}" required autocomplete="email"
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
                                   name="password" required autocomplete="new-password"
                                   placeholder="Create a strong password">
                            @if(isset($errors))
                                @error('password')
                                    <div class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            @endif
                            <small class="form-text text-muted">
                                Password must be at least 8 characters long
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="password-confirm">{{ __('Confirm Password') }}</label>
                            <input id="password-confirm" type="password" class="form-control" 
                                   name="password_confirmation" required autocomplete="new-password"
                                   placeholder="Confirm your password">
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="terms" id="terms" required>
                            <label class="form-check-label" for="terms">
                                I agree to the <a href="#" class="text-theme">Terms of Service</a> and 
                                <a href="#" class="text-theme">Privacy Policy</a>
                            </label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="newsletter" id="newsletter" checked>
                            <label class="form-check-label" for="newsletter">
                                Subscribe to our newsletter for fight updates and exclusive content
                            </label>
                        </div>

                        <button type="submit" class="btn btn-auth">
                            <i class="fa-solid fa-user-plus me-2"></i>
                            {{ __('Create Account') }}
                        </button>

                        <div class="auth-links">
                            <span>Already have an account?</span>
                            <a href="{{ route('login') }}">
                                {{ __('Sign In Here') }}
                            </a>
                        </div>
                    </form>

                    {{-- Optional Social Registration Section --}}
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
    
    // Password strength indicator
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('password-confirm');
    
    passwordInput.addEventListener('input', function() {
        const password = this.value;
        let strength = 0;
        
        // Check password strength
        if (password.length >= 8) strength++;
        if (password.match(/[a-z]/)) strength++;
        if (password.match(/[A-Z]/)) strength++;
        if (password.match(/[0-9]/)) strength++;
        if (password.match(/[^a-zA-Z0-9]/)) strength++;
        
        // Update visual indicator (you can add a progress bar here)
        this.classList.remove('weak', 'medium', 'strong');
        if (strength < 3) {
            this.classList.add('weak');
        } else if (strength < 5) {
            this.classList.add('medium');
        } else {
            this.classList.add('strong');
        }
    });
    
    // Password confirmation validation
    confirmPasswordInput.addEventListener('input', function() {
        const password = passwordInput.value;
        const confirmPassword = this.value;
        
        if (confirmPassword && password !== confirmPassword) {
            this.classList.add('is-invalid');
            this.classList.remove('is-valid');
        } else if (confirmPassword) {
            this.classList.remove('is-invalid');
            this.classList.add('is-valid');
        }
    });
    
    // Add loading state to submit button
    const form = document.querySelector('form');
    const submitBtn = document.querySelector('.btn-auth');
    
    form.addEventListener('submit', function(e) {
        // Check if terms are accepted
        const termsCheckbox = document.getElementById('terms');
        if (!termsCheckbox.checked) {
            e.preventDefault();
            alert('Please accept the Terms of Service and Privacy Policy to continue.');
            return;
        }
        
        submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i>Creating Account...';
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

.form-control.weak {
    border-color: #dc3545;
}

.form-control.medium {
    border-color: #ffc107;
}

.form-control.strong {
    border-color: #28a745;
}

.text-theme {
    color: var(--theme-color) !important;
}

.form-text.text-muted {
    color: #999 !important;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}
</style>
@endsection
