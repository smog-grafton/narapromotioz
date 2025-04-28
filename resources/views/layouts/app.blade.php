<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Nara Promotionz - Premier boxing promotions company bringing you world-class fights, legendary fighters, and unforgettable moments.">
    <meta name="keywords" content="boxing, promotions, fights, fighters, events, boxing tickets, live streaming, boxing rankings">
    <meta name="author" content="Nara Promotionz">
    
    <title>{{ config('app.name', 'Nara Promotionz') }} - @yield('title', 'Premier Boxing Promotions')</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    
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
    
    <!-- Additional Styles -->
    @yield('styles')
    
    <!-- Structured Data for SEO -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "SportsOrganization",
        "name": "Nara Promotionz",
        "url": "{{ url('/') }}",
        "logo": "{{ asset('images/logo.png') }}",
        "sameAs": [
            "https://www.facebook.com/narapromotionz",
            "https://www.instagram.com/narapromotionz",
            "https://twitter.com/narapromotionz"
        ],
        "description": "Premier boxing promotions company bringing you world-class fights, legendary fighters, and unforgettable moments."
    }
    </script>
</head>
<body>
    <div id="app">
        <!-- Skip to content link for accessibility -->
        <a href="#main-content" class="skip-to-content">Skip to main content</a>
        
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
            <div class="container">
                <!-- Logo and Brand -->
                <a class="navbar-brand" href="{{ route('home') }}">
                    <img src="{{ asset('images/logo.png') }}" alt="Nara Promotionz Logo" height="40" class="me-2 d-none d-sm-inline">
                    <strong>NARA PROMOTIONZ</strong>
                </a>
                
                <!-- Mobile Toggle Button -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <!-- Navigation Links -->
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <!-- Main Navigation -->
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
                        <!-- Streams Link -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('streams.*') ? 'active' : '' }}" href="{{ route('streams.index') }}">
                                Live Streams
                                <!-- Live Indicator dot when streams are active -->
                                @if(isset($activeStreams) && $activeStreams > 0)
                                    <span class="live-badge ms-1">LIVE</span>
                                @endif
                            </a>
                        </li>
                    </ul>
                    
                    <!-- User Navigation and Actions -->
                    <ul class="navbar-nav">
                        <!-- Dark Mode Toggle -->
                        <li class="nav-item me-2 d-flex align-items-center">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="darkModeToggle">
                                <label class="form-check-label d-none d-lg-inline text-white" for="darkModeToggle">
                                    <i class="fas fa-moon"></i>
                                </label>
                            </div>
                        </li>
                        
                        <!-- Authentication Links -->
                        @auth
                            <!-- User Account Menu -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    @if(auth()->user()->profile_photo_url)
                                        <img src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}" class="rounded-circle me-1" width="25" height="25">
                                    @endif
                                    {{ auth()->user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="fas fa-user me-2"></i> My Profile</a></li>
                                    <li><a class="dropdown-item" href="{{ route('tickets.index') }}"><i class="fas fa-ticket-alt me-2"></i> My Tickets</a></li>
                                    
                                    <!-- Fighter-specific options if user is a fighter -->
                                    @if(auth()->user()->isFighter())
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="{{ route('fighter.dashboard') }}"><i class="fas fa-tachometer-alt me-2"></i> Fighter Dashboard</a></li>
                                        <li><a class="dropdown-item" href="{{ route('fighter.promotions') }}"><i class="fas fa-bullhorn me-2"></i> My Promotions</a></li>
                                        <li><a class="dropdown-item" href="{{ route('fighter.commissions') }}"><i class="fas fa-money-bill-wave me-2"></i> Commissions</a></li>
                                    @endif
                                    
                                    <!-- Admin-specific options if user is an admin -->
                                    @if(auth()->user()->isAdmin())
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="/admin"><i class="fas fa-crown me-2"></i> Admin Panel</a></li>
                                    @endif
                                    
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt me-2"></i> Logout</button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @else
                            <!-- Login/Register Links -->
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}"><i class="fas fa-sign-in-alt me-1"></i> Login</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}"><i class="fas fa-user-plus me-1"></i> Register</a>
                            </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main id="main-content">
            <!-- Alert Messages -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <div class="container">
                        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <div class="container">
                        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif

            @if (session('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <div class="container">
                        <i class="fas fa-info-circle me-2"></i> {{ session('info') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif
            
            <!-- Page Content -->
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="footer mt-5">
            <div class="container">
                <!-- Main Footer Content -->
                <div class="row">
                    <!-- About Column -->
                    <div class="col-lg-4 col-md-6 mb-4 mb-md-0">
                        <h4>NARA PROMOTIONZ</h4>
                        <p>The premier boxing promotions company bringing you the best fights, fighters, and entertainment in the world of boxing.</p>
                        <div class="social-icons mt-3">
                            <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                            <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                            <a href="#" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                            <a href="#" aria-label="TikTok"><i class="fab fa-tiktok"></i></a>
                        </div>
                    </div>
                    
                    <!-- Quick Links Column -->
                    <div class="col-lg-2 col-md-6 mb-4 mb-md-0">
                        <h4>QUICK LINKS</h4>
                        <ul class="list-unstyled">
                            <li><a href="{{ route('home') }}"><i class="fas fa-chevron-right me-1"></i> Home</a></li>
                            <li><a href="{{ route('events.index') }}"><i class="fas fa-chevron-right me-1"></i> Events</a></li>
                            <li><a href="{{ route('fighters.index') }}"><i class="fas fa-chevron-right me-1"></i> Fighters</a></li>
                            <li><a href="{{ route('rankings.index') }}"><i class="fas fa-chevron-right me-1"></i> Rankings</a></li>
                            <li><a href="{{ route('news.index') }}"><i class="fas fa-chevron-right me-1"></i> News</a></li>
                            <li><a href="{{ route('streams.index') }}"><i class="fas fa-chevron-right me-1"></i> Live Streams</a></li>
                        </ul>
                    </div>
                    
                    <!-- Support Column -->
                    <div class="col-lg-2 col-md-6 mb-4 mb-md-0">
                        <h4>SUPPORT</h4>
                        <ul class="list-unstyled">
                            <li><a href="#"><i class="fas fa-chevron-right me-1"></i> Help Center</a></li>
                            <li><a href="#"><i class="fas fa-chevron-right me-1"></i> FAQs</a></li>
                            <li><a href="#"><i class="fas fa-chevron-right me-1"></i> Terms of Service</a></li>
                            <li><a href="#"><i class="fas fa-chevron-right me-1"></i> Privacy Policy</a></li>
                            <li><a href="#"><i class="fas fa-chevron-right me-1"></i> Refund Policy</a></li>
                            <li><a href="#"><i class="fas fa-chevron-right me-1"></i> Contact Us</a></li>
                        </ul>
                    </div>
                    
                    <!-- Contact Column -->
                    <div class="col-lg-4 col-md-6">
                        <h4>CONTACT US</h4>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-map-marker-alt me-2"></i> 123 Boxing Way, Ringside City</li>
                            <li class="mb-2"><i class="fas fa-phone me-2"></i> +123 456 7890</li>
                            <li class="mb-2"><i class="fas fa-envelope me-2"></i> info@narapromotionz.com</li>
                        </ul>
                        
                        <!-- Newsletter Signup -->
                        <h5 class="mt-4 text-sky-blue">SUBSCRIBE TO OUR NEWSLETTER</h5>
                        <form action="#" method="POST" class="mt-2">
                            @csrf
                            <div class="input-group">
                                <input type="email" class="form-control" placeholder="Your email address" required>
                                <button class="btn btn-danger" type="submit">GO</button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Footer Divider -->
                <hr class="mt-4 mb-4" style="border-color: var(--light-gray); opacity: 0.1;">
                
                <!-- Footer Bottom -->
                <div class="row footer-bottom">
                    <div class="col-md-6 text-center text-md-start">
                        <p class="mb-0">&copy; {{ date('Y') }} Nara Promotionz. All rights reserved.</p>
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <p class="mb-0">
                            <a href="#">Privacy Policy</a> | 
                            <a href="#">Terms of Service</a> |
                            <a href="#">Cookies</a>
                        </p>
                    </div>
                </div>
            </div>
        </footer>
        
        <!-- Back to Top Button -->
        <button id="backToTop" class="btn btn-primary btn-sm rounded-circle" aria-label="Back to top">
            <i class="fas fa-arrow-up"></i>
        </button>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @vite(['resources/js/app.js'])
    
    <!-- Page Specific Scripts -->
    @yield('scripts')
    
    <!-- Common Scripts -->
    <script>
        $(document).ready(function() {
            // Navbar shrink on scroll
            $(window).scroll(function() {
                if ($(document).scrollTop() > 50) {
                    $('.navbar').addClass('scrolled');
                } else {
                    $('.navbar').removeClass('scrolled');
                }
            });
            
            // Back to top button
            $(window).scroll(function() {
                if ($(this).scrollTop() > 300) {
                    $('#backToTop').fadeIn();
                } else {
                    $('#backToTop').fadeOut();
                }
            });
            
            $('#backToTop').click(function() {
                $('html, body').animate({scrollTop : 0}, 800);
                return false;
            });
            
            // Dark Mode Toggle
            if (localStorage.getItem('darkMode') === 'enabled') {
                $('body').addClass('dark-mode');
                $('#darkModeToggle').prop('checked', true);
            }
            
            $('#darkModeToggle').change(function() {
                if ($(this).is(':checked')) {
                    $('body').addClass('dark-mode');
                    localStorage.setItem('darkMode', 'enabled');
                } else {
                    $('body').removeClass('dark-mode');
                    localStorage.setItem('darkMode', null);
                }
            });
            
            // Auto-dismiss alerts after 5 seconds
            setTimeout(function() {
                $('.alert').alert('close');
            }, 5000);
        });
    </script>
</body>
</html>