<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Nara Promotionz') }} - @yield('title', 'Premier Boxing Promotions')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Styles -->
    @vite(['resources/css/app.css'])

    <!-- Custom Styles -->
    <style>
        :root {
            --sky-blue: #00ADEF;
            --action-red: #E63946;
            --white: #FFFFFF;
            --light-gray: #F1FAEE;
            --dark-navy: #1D3557;
        }
        
        body {
            font-family: 'Roboto', sans-serif;
            color: var(--dark-navy);
        }
        
        h1, h2, h3, h4, h5, h6, .nav-link, .btn {
            font-family: 'Oswald', sans-serif;
            text-transform: uppercase;
        }
        
        .btn-primary {
            background-color: var(--sky-blue);
            border-color: var(--sky-blue);
        }
        
        .btn-primary:hover, .btn-primary:focus {
            background-color: #0090cb;
            border-color: #0090cb;
        }
        
        .btn-danger {
            background-color: var(--action-red);
            border-color: var(--action-red);
        }
        
        .navbar {
            background-color: var(--dark-navy);
        }
        
        .navbar-brand, .nav-link {
            color: var(--white);
        }
        
        .nav-link:hover {
            color: var(--sky-blue);
        }
        
        .nav-link.active {
            color: var(--sky-blue) !important;
            font-weight: bold;
        }
        
        .footer {
            background-color: var(--dark-navy);
            color: var(--white);
            padding: 3rem 0;
        }
        
        .footer a {
            color: var(--sky-blue);
        }
        
        .live-badge {
            background-color: var(--action-red);
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
            100% {
                opacity: 1;
            }
        }
    </style>
</head>
<body>
    <div id="app">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
            <div class="container">
                <a class="navbar-brand" href="{{ route('home') }}">
                    <strong>NARA PROMOTIONZ</strong>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('events.*') ? 'active' : '' }}" href="{{ route('events.index') }}">Events</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('fighters.*') ? 'active' : '' }}" href="{{ route('fighters.index') }}">Fighters</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('rankings.index') ? 'active' : '' }}" href="{{ route('rankings.index') }}">Rankings</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('news.*') ? 'active' : '' }}" href="{{ route('news.index') }}">News</a>
                        </li>
                    </ul>
                    <ul class="navbar-nav">
                        @auth
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
                                    <li><a class="dropdown-item" href="{{ route('tickets.index') }}">My Tickets</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item">Logout</button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">Login</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">Register</a>
                            </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Content -->
        <main>
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="footer mt-5">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 mb-4 mb-md-0">
                        <h4>NARA PROMOTIONZ</h4>
                        <p>The premier boxing promotions company bringing you the best fights, fighters, and entertainment.</p>
                        <div class="social-icons mt-3">
                            <a href="#" class="me-2"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="me-2"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="me-2"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="me-2"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4 mb-md-0">
                        <h4>QUICK LINKS</h4>
                        <ul class="list-unstyled">
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li><a href="{{ route('events.index') }}">Events</a></li>
                            <li><a href="{{ route('fighters.index') }}">Fighters</a></li>
                            <li><a href="{{ route('rankings.index') }}">Rankings</a></li>
                            <li><a href="{{ route('news.index') }}">News</a></li>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <h4>CONTACT US</h4>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-map-marker-alt me-2"></i> 123 Boxing Way, Ringside City</li>
                            <li><i class="fas fa-phone me-2"></i> +123 456 7890</li>
                            <li><i class="fas fa-envelope me-2"></i> info@narapromotionz.com</li>
                        </ul>
                    </div>
                </div>
                <hr class="mt-4 mb-4" style="border-color: var(--light-gray);">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start">
                        <p class="mb-0">&copy; {{ date('Y') }} Nara Promotionz. All rights reserved.</p>
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <p class="mb-0">
                            <a href="#">Privacy Policy</a> | 
                            <a href="#">Terms of Service</a>
                        </p>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @vite(['resources/js/app.js'])
    @yield('scripts')
    
    <!-- Dark Mode Toggle -->
    <script>
        $(document).ready(function() {
            // Check for saved dark mode preference
            if (localStorage.getItem('darkMode') === 'enabled') {
                $('body').addClass('dark-mode');
                $('#darkModeToggle').prop('checked', true);
            }
            
            // Toggle dark mode
            $('#darkModeToggle').change(function() {
                if ($(this).is(':checked')) {
                    $('body').addClass('dark-mode');
                    localStorage.setItem('darkMode', 'enabled');
                } else {
                    $('body').removeClass('dark-mode');
                    localStorage.setItem('darkMode', null);
                }
            });
        });
    </script>
</body>
</html>