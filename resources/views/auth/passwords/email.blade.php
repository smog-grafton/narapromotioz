@extends('layouts.app')

@section('title', 'Reset Password - ' . config('app.name'))

@section('meta')
    <meta name="description" content="Reset your {{ config('app.name') }} account password. Enter your email address to receive password reset instructions.">
    <meta name="keywords" content="password reset, forgot password, {{ config('app.name') }}, boxing">
@endsection

@section('content')
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
                        <h2>Reset Password</h2>
                        <p>Enter your email address and we'll send you a link to reset your password</p>
                    </div>

                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            <i class="fa-solid fa-check-circle me-2"></i>
                            {{ session('status') }}
                        </div>
                    @endif

                    @if(isset($errors) && $errors->any())
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> There was a problem with your request.
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
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

                        <button type="submit" class="btn btn-auth">
                            <i class="fa-solid fa-paper-plane me-2"></i>
                            {{ __('Send Password Reset Link') }}
                        </button>

                        <div class="auth-links">
                            <span>Remember your password?</span>
                            <a href="{{ route('login') }}">
                                {{ __('Back to Login') }}
                            </a>
                        </div>

                        <div class="auth-links">
                            <span>Don't have an account?</span>
                            <a href="{{ route('register') }}">
                                {{ __('Create Account') }}
                            </a>
                        </div>
                    </form>

                    <div class="auth-help">
                        <div class="help-section">
                            <h5><i class="fa-solid fa-question-circle me-2"></i>Need Help?</h5>
                            <p>If you're having trouble accessing your account, please contact our support team.</p>
                            <a href="mailto:support@{{ str_replace(['http://', 'https://'], '', config('app.url')) }}" class="text-theme">
                                <i class="fa-solid fa-envelope me-1"></i>
                                Contact Support
                            </a>
                        </div>
                    </div>
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
        submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i>Sending Reset Link...';
        submitBtn.disabled = true;
    });
    
    // Auto-hide success message after 10 seconds
    const successAlert = document.querySelector('.alert-success');
    if (successAlert) {
        setTimeout(function() {
            successAlert.style.opacity = '0';
            setTimeout(function() {
                successAlert.style.display = 'none';
            }, 300);
        }, 10000);
    }
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

.auth-help {
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.help-section h5 {
    color: var(--theme-color);
    font-size: 1rem;
    margin-bottom: 0.5rem;
}

.help-section p {
    color: #ccc;
    font-size: 0.9rem;
    margin-bottom: 1rem;
}

.text-theme {
    color: var(--theme-color) !important;
    text-decoration: none;
}

.text-theme:hover {
    text-decoration: underline;
}

.alert-success {
    transition: opacity 0.3s ease;
}

[data-theme="dark"] .help-section p {
    color: #999;
}
</style>
@endsection
