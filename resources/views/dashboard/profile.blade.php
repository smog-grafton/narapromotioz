@extends('layouts.app')

@section('title', 'Profile Settings')

@section('content')
<!-- Page Banner -->
<x-page-banner 
    title="Profile Settings" 
    subtitle="Manage your account information and preferences"
    :breadcrumbs="[
        ['title' => 'Dashboard', 'url' => route('dashboard')],
        ['title' => 'Profile']
    ]" />

<div class="dashboard-container">
    <div class="container">
        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <div class="dashboard-info">
                <h1 class="dashboard-title">Account Settings</h1>
                <p class="dashboard-subtitle">Update your personal information and security settings</p>
            </div>
            <div class="dashboard-actions">
                <a href="{{ route('dashboard') }}" class="btn-secondary">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M16 7l-4-4-4 4 1.5 1.5L11 7v6h2V7l1.5 1.5L16 7z"/>
                        <path d="M20 18v-1a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v1h16z"/>
                    </svg>
                    Back to Dashboard
                </a>
            </div>
        </div>

        <!-- Profile Form -->
        <div class="dashboard-card">
            <div class="card-header">
                <h3 class="card-title">Account Information</h3>
            </div>
            <div class="card-content">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('dashboard.update-profile') }}" enctype="multipart/form-data" class="profile-form">
                    @csrf
                    @method('PUT')

                    <!-- Avatar Section -->
                    <div class="form-section">
                        <label class="form-label">Profile Picture</label>
                        <div class="avatar-upload">
                            <div class="current-avatar">
                                @if(Auth::user()->avatar)
                                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Current Avatar" class="avatar-image">
                                @else
                                    <div class="avatar-placeholder">
                                        <svg viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="avatar-actions">
                                <input type="file" id="avatar" name="avatar" accept="image/*" class="file-input">
                                <label for="avatar" class="btn-secondary">
                                    <svg viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M9 16h6v-6h4l-7-7-7 7h4v6zm-4 2h14v2H5v-2z"/>
                                    </svg>
                                    Change Picture
                                </label>
                                <span class="file-info">JPG, PNG up to 2MB</span>
                            </div>
                        </div>
                        @error('avatar')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Personal Information -->
                    <div class="form-section">
                        <h4 class="section-title">Personal Information</h4>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="name" class="form-label">Full Name *</label>
                                <input type="text" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', Auth::user()->name) }}"
                                       class="form-input @error('name') error @enderror"
                                       required>
                                @error('name')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="email" class="form-label">Email Address *</label>
                                <input type="email" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email', Auth::user()->email) }}"
                                       class="form-input @error('email') error @enderror"
                                       required>
                                @error('email')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="bio" class="form-label">Bio</label>
                            <textarea id="bio" 
                                      name="bio" 
                                      rows="4"
                                      class="form-textarea @error('bio') error @enderror"
                                      placeholder="Tell us a bit about yourself...">{{ old('bio', Auth::user()->bio) }}</textarea>
                            @error('bio')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Account Details -->
                    <div class="form-section">
                        <h4 class="section-title">Account Details</h4>
                        
                        <div class="readonly-fields">
                            <div class="readonly-field">
                                <label class="readonly-label">Account Role</label>
                                <div class="readonly-value">
                                    <span class="role-badge">{{ ucfirst(Auth::user()->role) }}</span>
                                </div>
                            </div>

                            <div class="readonly-field">
                                <label class="readonly-label">Member Since</label>
                                <div class="readonly-value">{{ Auth::user()->created_at->format('F j, Y') }}</div>
                            </div>

                            <div class="readonly-field">
                                <label class="readonly-label">Last Updated</label>
                                <div class="readonly-value">{{ Auth::user()->updated_at->format('F j, Y g:i A') }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                            </svg>
                            Save Changes
                        </button>
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Security Section -->
        <div class="dashboard-card">
            <div class="card-header">
                <h3 class="card-title">Security</h3>
            </div>
            <div class="card-content">
                <div class="security-info">
                    <div class="security-item">
                        <div class="security-icon">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/>
                            </svg>
                        </div>
                        <div class="security-content">
                            <h5 class="security-title">Password</h5>
                            <p class="security-description">Last updated {{ Auth::user()->updated_at->diffForHumans() }}</p>
                        </div>
                        <div class="security-action">
                            <a href="#" class="btn-link">Change Password</a>
                        </div>
                    </div>

                    <div class="security-item">
                        <div class="security-icon">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/>
                            </svg>
                        </div>
                        <div class="security-content">
                            <h5 class="security-title">Account Security</h5>
                            <p class="security-description">Two-factor authentication is not enabled</p>
                        </div>
                        <div class="security-action">
                            <a href="#" class="btn-link">Enable 2FA</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Profile-specific styles */
.profile-form {
    .form-section {
        margin-bottom: 2rem;
        padding-bottom: 2rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);

        &:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }
    }

    .section-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: #fff;
        margin-bottom: 1.5rem;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1rem;

        @media (min-width: 768px) {
            grid-template-columns: 1fr 1fr;
        }
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .form-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 500;
        color: #9ca3af;
        margin-bottom: 0.5rem;
    }

    .form-input, .form-textarea {
        width: 100%;
        background-color: #374151;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        color: #fff;
        padding: 0.75rem 1rem;
        transition: all 0.2s ease;

        &:focus {
            outline: none;
            border-color: #dc2626;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.2);
        }

        &.error {
            border-color: #ef4444;
        }

        &::placeholder {
            color: #6b7280;
        }
    }

    .error-message {
        color: #ef4444;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    .alert {
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;

        &.alert-success {
            background-color: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.2);
            color: #22c55e;
        }
    }
}

.avatar-upload {
    display: flex;
    align-items: center;
    gap: 1.5rem;

    .current-avatar {
        flex-shrink: 0;
    }

    .avatar-image {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid rgba(255, 255, 255, 0.1);
    }

    .avatar-placeholder {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background-color: #374151;
        border: 3px solid rgba(255, 255, 255, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;

        svg {
            width: 2rem;
            height: 2rem;
            color: #6b7280;
        }
    }

    .avatar-actions {
        flex: 1;
    }

    .file-input {
        display: none;
    }

    .file-info {
        display: block;
        font-size: 0.875rem;
        color: #6b7280;
        margin-top: 0.5rem;
    }
}

.readonly-fields {
    display: grid;
    gap: 1rem;

    .readonly-field {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        background-color: #374151;
        border-radius: 8px;
    }

    .readonly-label {
        font-size: 0.875rem;
        font-weight: 500;
        color: #9ca3af;
    }

    .readonly-value {
        color: #fff;
        font-weight: 500;
    }

    .role-badge {
        background-color: rgba(220, 38, 38, 0.2);
        color: #dc2626;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }
}

.form-actions {
    display: flex;
    gap: 1rem;
    padding-top: 1.5rem;

    .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;

        svg {
            margin-right: 0.5rem;
            width: 1rem;
            height: 1rem;
        }

        &.btn-primary {
            background-color: #dc2626;
            color: #fff;

            &:hover {
                background-color: #b91c1c;
                transform: translateY(-1px);
            }
        }

        &.btn-secondary {
            background-color: transparent;
            color: #9ca3af;
            border: 1px solid rgba(255, 255, 255, 0.2);

            &:hover {
                background-color: rgba(255, 255, 255, 0.1);
                color: #fff;
                border-color: rgba(255, 255, 255, 0.4);
            }
        }
    }
}

.security-info {
    .security-item {
        display: flex;
        align-items: center;
        padding: 1.5rem;
        background-color: #374151;
        border-radius: 8px;
        margin-bottom: 1rem;

        &:last-child {
            margin-bottom: 0;
        }

        .security-icon {
            flex-shrink: 0;
            width: 3rem;
            height: 3rem;
            background-color: rgba(220, 38, 38, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;

            svg {
                width: 1.5rem;
                height: 1.5rem;
                color: #dc2626;
            }
        }

        .security-content {
            flex: 1;

            .security-title {
                font-size: 1rem;
                font-weight: 600;
                color: #fff;
                margin-bottom: 0.25rem;
            }

            .security-description {
                font-size: 0.875rem;
                color: #9ca3af;
                margin: 0;
            }
        }

        .security-action {
            .btn-link {
                color: #dc2626;
                text-decoration: none;
                font-size: 0.875rem;
                font-weight: 500;
                transition: color 0.2s ease;

                &:hover {
                    color: #b91c1c;
                }
            }
        }
    }
}
</style>
@endsection 