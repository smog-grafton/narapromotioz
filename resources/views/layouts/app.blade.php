<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Title -->
        <title>@yield('title', config('app.name', 'Nara Promotionz'))</title>

        <!-- Meta Tags -->
        @yield('meta')

        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/heading-icon.png') }}">

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Font Awesome -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200;300;400;500;600;700&family=Open+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Custom CSS Files -->
        <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/font/flaticon_mycollection.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/owl.carousel.min.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/owl.theme.default.min.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/jquery.fancybox.min.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/fontawesome.min.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/nice-select.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/style-dark.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/responsive.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/boxer-card.css') }}" rel="stylesheet">

        <!-- Vite Assets -->
        @vite(['resources/scss/app.scss', 'resources/js/app.js'])

        <!-- Force Dark Theme Styles -->
        <style>
            /* Emergency dark theme enforcement */
            html, body {
                background-color: #121212 !important;
                color: #ffffff !important;
            }
            
            .gap, .blog-style-two, section {
                background-color: #121212 !important;
                color: #ffffff !important;
            }
            
            .featured-news {
                background-color: #1e1e1e !important;
                color: #ffffff !important;
            }
            
            /* Override any white backgrounds */
            .bg-white, .bg-light {
                background-color: #121212 !important;
                color: #ffffff !important;
            }
            
            /* Text color fixes */
            h1, h2, h3, h4, h5, h6, p, span, div {
                color: inherit !important;
            }
            
            .text-dark {
                color: #ffffff !important;
            }
        </style>

        <!-- Page Specific Styles -->
        @stack('styles')

        <!-- Additional Head Content -->
        @yield('head')
    </head>
    <body>
        <!-- Page Wrapper -->
        <div class="page-wrapper">
            
            @include('partials.header')
            
            <!-- Main Content -->
            <main class="main-content">
                @yield('content')
            </main>
            
            @include('partials.footer')
            
        </div>

        <!-- Scroll Progress Indicator -->
        <div id="scroll-percentage"><span id="scroll-percentage-value"></span></div>

        <!-- Core JavaScript Libraries -->
        <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
        
        <!-- Third-party Libraries -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jarallax/2.0.4/jarallax.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.imagesloaded/5.0.0/imagesloaded.pkgd.min.js"></script>
        
        <!-- Bootstrap and Popper -->
        <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
        
        <!-- Plugin Libraries -->
        <script src="{{ asset('assets/js/owl.carousel.min.js') }}"></script>
        <script src="{{ asset('assets/js/slick.min.js') }}"></script>
        <script src="{{ asset('assets/js/jquery.waypoints.min.js') }}"></script>
        <script src="{{ asset('assets/js/jquery.counterup.min.js') }}"></script>
        <script src="{{ asset('assets/js/jquery.fancybox.min.js') }}"></script>
        <script src="{{ asset('assets/js/jquery.nice-select.js') }}"></script>
        <script src="{{ asset('assets/js/aos.js') }}"></script>
        <script src="{{ asset('assets/js/marquee.js') }}"></script>
        <script src="{{ asset('assets/js/sweetalert.min.js') }}"></script>
        
        <!-- Theme Scripts -->
        <script src="{{ asset('assets/js/common.min.js') }}"></script>
        <script src="{{ asset('assets/js/custom.js') }}"></script>
        
        <!-- Initialize all sliders and components -->
        <script>
            $(document).ready(function() {
                // Force dark theme on load
                $('body, html').css({
                    'background-color': '#121212',
                    'color': '#ffffff'
                });
                
                // Initialize Owl Carousel sliders
                if ($('.f-slider-three').length && $.fn.owlCarousel) {
                    $('.f-slider-three').owlCarousel({
                        items: 1,
                        loop: true,
                        margin: 0,
                        stagePadding: 0,
                        dots: true,
                        autoplay: true,
                        smartSpeed: 2000,
                        animateOut: 'fadeOut',
                        nav: false
                    });
                }
                
                // Initialize blog slider
                if ($('.blog-slider').length && $.fn.owlCarousel) {
                    $('.blog-slider').owlCarousel({
                        items: 3,
                        center: true,
                        loop: true,
                        margin: 12,
                        dots: true,
                        autoplay: true,
                        autoplayTimeout: 4000,
                        responsive: {
                            0: { items: 1 },
                            768: { items: 2 },
                            992: { items: 3 }
                        }
                    });
                }
                
                // Initialize Jarallax if available
                if (typeof jarallax !== 'undefined') {
                    jarallax(document.querySelectorAll('.jarallax'), {
                        speed: 0.5
                    });
                }
                
                // Initialize AOS (Animate On Scroll) if available
                if (typeof AOS !== 'undefined') {
                    AOS.init({
                        duration: 1000,
                        once: true
                    });
                }
                
                // Initialize Nice Select if available
                if ($.fn.niceSelect) {
                    $('select').niceSelect();
                }
                
                // Initialize Counter Up if elements exist
                if ($('.counter').length && $.fn.counterUp) {
                    $('.counter').counterUp({
                        delay: 10,
                        time: 1000
                    });
                }
                
                // Initialize Marquee if available
                if ($('.marquee_text').length && $.fn.marquee) {
                    $('.marquee_text').marquee({
                        direction: 'left',
                        duration: 20000,
                        gap: 50,
                        delayBeforeStart: 0,
                        duplicated: true,
                        startVisible: true
                    });
                }
                
                // Ensure images are loaded before initializing sliders
                if (typeof imagesLoaded !== 'undefined') {
                    imagesLoaded(document.body, function() {
                        // Refresh sliders after images load
                        if ($('.f-slider-three').data('owl.carousel')) {
                            $('.f-slider-three').trigger('refresh.owl.carousel');
                        }
                        if ($('.blog-slider').data('owl.carousel')) {
                            $('.blog-slider').trigger('refresh.owl.carousel');
                        }
                    });
                }
                
                // Scroll percentage functionality
                $(window).scroll(function() {
                    const scrollTop = $(window).scrollTop();
                    const docHeight = $(document).height();
                    const winHeight = $(window).height();
                    const scrollPercent = Math.round(scrollTop / (docHeight - winHeight) * 100);
                    $('#scroll-percentage-value').text(scrollPercent + '%');
                    
                    if (scrollPercent > 10) {
                        $('#scroll-percentage').addClass('visible');
                    } else {
                        $('#scroll-percentage').removeClass('visible');
                    }
                });
            });
        </script>
        
        <!-- Page Specific Scripts -->
        @stack('scripts')
        
        <!-- Additional Body Content -->
        @yield('body_scripts')
    </body>
</html>
