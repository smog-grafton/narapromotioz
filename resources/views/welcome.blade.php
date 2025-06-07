<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'NaraPromotionz') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
    </head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">{{ config('app.name', 'NaraPromotionz') }}</a>
            
            <div class="navbar-nav ms-auto">
            @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="nav-link">Dashboard</a>
                        <a href="{{ url('/admin') }}" class="nav-link">Admin Panel</a>
                    @else
                        <a href="{{ route('login') }}" class="nav-link">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="nav-link">Register</a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
                </nav>

    <!-- Main Content -->
    <div class="container-fluid">
        <div class="row min-vh-100">
            <!-- Hero Section -->
            <div class="col-12">
                <div class="d-flex align-items-center justify-content-center min-vh-100 bg-primary text-white">
                    <div class="text-center">
                        <h1 class="display-4 fw-bold mb-4">Welcome to {{ config('app.name', 'NaraPromotionz') }}</h1>
                        <p class="lead mb-4">Your Premier Laravel 12 Application with Filament Admin Panel</p>
                        <div class="d-flex justify-content-center gap-3">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="btn btn-light btn-lg">Dashboard</a>
                                <a href="{{ url('/admin') }}" class="btn btn-outline-light btn-lg">Admin Panel</a>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-light btn-lg">Get Started</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg">Sign Up</a>
            @endif
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div class="row py-5">
            <div class="col-12">
                <div class="container">
                    <h2 class="text-center mb-5">Built with Modern Technologies</h2>
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Laravel 12</h5>
                                    <p class="card-text">Latest version of Laravel with modern PHP features and enhanced performance.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Filament Admin</h5>
                                    <p class="card-text">Beautiful admin panel with role-based access control and modern interface.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Bootstrap 5</h5>
                                    <p class="card-text">Responsive design with the latest Bootstrap framework and custom SCSS variables.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-dark text-white py-4">
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center">
                        <p class="mb-0">&copy; {{ date('Y') }} {{ config('app.name', 'NaraPromotionz') }}. All rights reserved.</p>
                        <p class="mb-0"><small>Powered by Laravel {{ app()->version() }} with Filament & Bootstrap 5</small></p>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    </body>
</html>
