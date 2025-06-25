@extends('layouts.app')

@section('title', 'Reset Password - ' . config('app.name'))

@section('meta')
    <meta name="description" content="Create a new password for your {{ config('app.name') }} account. Enter your new password to complete the reset process.">
    <meta name="keywords" content="password reset, new password, {{ config('app.name') }}, boxing">
@endsection

@section('content')
<!-- Page Banner -->
<x-page-banner 
    title="Reset Password" 
    subtitle="Enter your email and new password to reset your account"
    :breadcrumbs="[
        ['title' => 'Account', 'url' => route('login')],
        ['title' => 'Reset Password']
    ]" />

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
                        <h2>Create New Password</h2>
                        <p>Enter your new password to complete the reset process</p>
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

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group">
                            <label for="email">{{ __('Email Address') }}</label>
                            <input id="email" type="email" class="form-control @if(isset($errors)) @error('email') is-invalid @enderror @endif" 
                                   name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus
                                   placeholder="Enter your email address" readonly>
                            @if(isset($errors))
                                @error('email')
                                    <div class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="password">{{ __('New Password') }}</label>
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
                            <label for="password-confirm">{{ __('Confirm New Password') }}</label>
                            <input id="password-confirm" type="password" class="form-control" 
                                   name="password_confirmation" required autocomplete="new-password"
                                   placeholder="Confirm your new password">
                        </div>

                        <button type="submit" class="btn btn-auth">
                            <i class="fa-solid fa-key me-2"></i>
                            {{ __('Reset Password') }}
                        </button>

                        <div class="auth-links">
                            <span>Remember your password?</span>
                            <a href="{{ route('login') }}">
                                {{ __('Back to Login') }}
                            </a>
                        </div>
                    </form>

                    <div class="password-requirements">
                        <h5><i class="fa-solid fa-shield-alt me-2"></i>Password Requirements</h5>
                        <ul>
                            <li id="length-req">At least 8 characters long</li>
                            <li id="lowercase-req">Contains lowercase letters</li>
                            <li id="uppercase-req">Contains uppercase letters</li>
                            <li id="number-req">Contains numbers</li>
                            <li id="special-req">Contains special characters</li>
                        </ul>
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
    
    // Password strength validation
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('password-confirm');
    
    // Password requirements elements
    const lengthReq = document.getElementById('length-req');
    const lowercaseReq = document.getElementById('lowercase-req');
    const uppercaseReq = document.getElementById('uppercase-req');
    const numberReq = document.getElementById('number-req');
    const specialReq = document.getElementById('special-req');
    
    passwordInput.addEventListener('input', function() {
        const password = this.value;
        
        // Check requirements
        const hasLength = password.length >= 8;
        const hasLowercase = /[a-z]/.test(password);
        const hasUppercase = /[A-Z]/.test(password);
        const hasNumber = /[0-9]/.test(password);
        const hasSpecial = /[^a-zA-Z0-9]/.test(password);
        
        // Update requirement indicators
        updateRequirement(lengthReq, hasLength);
        updateRequirement(lowercaseReq, hasLowercase);
        updateRequirement(uppercaseReq, hasUppercase);
        updateRequirement(numberReq, hasNumber);
        updateRequirement(specialReq, hasSpecial);
        
        // Update input styling
        const allMet = hasLength && hasLowercase && hasUppercase && hasNumber && hasSpecial;
        this.classList.remove('weak', 'medium', 'strong');
        
        if (password.length === 0) {
            // No styling for empty
        } else if (allMet) {
            this.classList.add('strong');
        } else if (hasLength && (hasLowercase || hasUppercase) && (hasNumber || hasSpecial)) {
            this.classList.add('medium');
        } else {
            this.classList.add('weak');
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
    
    function updateRequirement(element, met) {
        if (met) {
            element.classList.add('met');
            element.classList.remove('unmet');
        } else {
            element.classList.remove('met');
            element.classList.add('unmet');
        }
    }
    
    // Add loading state to submit button
    const form = document.querySelector('form');
    const submitBtn = document.querySelector('.btn-auth');
    
    form.addEventListener('submit', function(e) {
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;
        
        if (password !== confirmPassword) {
            e.preventDefault();
            alert('Passwords do not match. Please check and try again.');
            return;
        }
        
        submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i>Resetting Password...';
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

.form-control[readonly] {
    background-color: rgba(255, 255, 255, 0.05);
    cursor: not-allowed;
}

.password-requirements {
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.password-requirements h5 {
    color: var(--theme-color);
    font-size: 1rem;
    margin-bottom: 1rem;
}

.password-requirements ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.password-requirements li {
    padding: 0.25rem 0;
    font-size: 0.9rem;
    color: #999;
    position: relative;
    padding-left: 1.5rem;
}

.password-requirements li::before {
    content: '\f00d';
    font-family: 'Font Awesome 6 Free';
    font-weight: 900;
    position: absolute;
    left: 0;
    color: #dc3545;
}

.password-requirements li.met {
    color: #28a745;
}

.password-requirements li.met::before {
    content: '\f00c';
    color: #28a745;
}

.text-theme {
    color: var(--theme-color) !important;
}

.form-text.text-muted {
    color: #999 !important;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

[data-theme="dark"] .form-control[readonly] {
    background-color: rgba(0, 0, 0, 0.2);
}
</style>
@endsection
