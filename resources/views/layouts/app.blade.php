<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- SEO Meta Tags -->
        <x-seo-meta 
            :title="isset($seoData['title']) ? $seoData['title'] : null"
            :description="isset($seoData['description']) ? $seoData['description'] : null"
            :keywords="isset($seoData['keywords']) ? $seoData['keywords'] : null"
            :image="isset($seoData['image']) ? $seoData['image'] : null"
            :type="isset($seoData['type']) ? $seoData['type'] : 'website'"
            :url="isset($seoData['url']) ? $seoData['url'] : null"
            :published-time="isset($seoData['published_time']) ? $seoData['published_time'] : null"
            :modified-time="isset($seoData['modified_time']) ? $seoData['modified_time'] : null"
            :author="isset($seoData['author']) ? $seoData['author'] : null"
            :video-duration="isset($seoData['video:duration']) ? $seoData['video:duration'] : null"
            :video-release-date="isset($seoData['video:release_date']) ? $seoData['video:release_date'] : null"
        />

        <!-- Structured Data -->
        @if(isset($structuredData))
        <script type="application/ld+json">
        {!! json_encode($structuredData, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
        </script>
        @endif

        <!-- Organization Structured Data (Global) -->
        <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "Organization",
            "name": "Nara Promotionz",
            "url": "{{ url('/') }}",
            "logo": "{{ asset('assets/images/logo.png') }}",
            "description": "Professional boxing promotion company organizing world-class boxing events and managing professional boxers.",
            "contactPoint": {
                "@type": "ContactPoint",
                "telephone": "+1-XXX-XXX-XXXX",
                "contactType": "customer service",
                "availableLanguage": "English"
            },
            "sameAs": [
                "https://facebook.com/narapromotionz",
                "https://twitter.com/narapromotionz",
                "https://instagram.com/narapromotionz"
            ]
        }
        </script>

        <!-- Additional Meta Tags -->
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
    <body class="light-d">
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
        
        <!-- Bootstrap and Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        
        <!-- Third-party Libraries -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jarallax/2.0.4/jarallax.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.imagesloaded/5.0.0/imagesloaded.pkgd.min.js"></script>
        
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
        
        <!-- Global Video Functions -->
        <script>
            // Global video interaction functions
            window.likeVideo = function(videoId) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                return fetch(`/videos/${videoId}/like`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update any like count displays on the page
                        document.querySelectorAll(`[data-video-id="${videoId}"] .likes-count`).forEach(element => {
                            element.textContent = data.likes_count;
                        });
                        
                        // Show success message
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Liked!',
                                text: 'You liked this video',
                                timer: 1500,
                                showConfirmButton: false
                            });
                        }
                    }
                    return data;
                })
                .catch(error => {
                    console.error('Error liking video:', error);
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to like video. Please try again.'
                        });
                    }
                    return { success: false };
                });
            };
            
            window.shareVideo = function(videoId, videoTitle = 'Check out this boxing video!') {
                const videoUrl = `${window.location.origin}/videos/${videoId}`;
                
                if (navigator.share) {
                    navigator.share({
                        title: videoTitle,
                        text: 'Watch this amazing boxing video',
                        url: videoUrl
                    }).catch(err => console.log('Error sharing:', err));
                } else if (navigator.clipboard) {
                    navigator.clipboard.writeText(videoUrl).then(() => {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Link Copied!',
                                text: 'Video link has been copied to clipboard',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        } else {
                            alert('Video link copied to clipboard!');
                        }
                    }).catch(() => {
                        // Fallback: show the URL in a prompt
                        prompt('Copy this link:', videoUrl);
                    });
                } else {
                    // Final fallback: show the URL in a prompt
                    prompt('Copy this link:', videoUrl);
                }
            };
            
            window.downloadVideo = function(videoId) {
                window.open(`/videos/${videoId}/download`, '_blank');
            };
            
            window.reportVideo = function(videoId) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Report Video',
                        text: 'Please tell us why you are reporting this video:',
                        input: 'select',
                        inputOptions: {
                            'inappropriate': 'Inappropriate content',
                            'copyright': 'Copyright violation',
                            'spam': 'Spam or misleading',
                            'violence': 'Violence or dangerous content',
                            'other': 'Other'
                        },
                        inputPlaceholder: 'Select a reason',
                        showCancelButton: true,
                        confirmButtonText: 'Report',
                        cancelButtonText: 'Cancel',
                        inputValidator: (value) => {
                            if (!value) {
                                return 'Please select a reason for reporting';
                            }
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Submit report
                            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                            
                            fetch(`/videos/${videoId}/report`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    reason: result.value
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire('Reported!', 'Thank you for your report. We will review it shortly.', 'success');
                                } else {
                                    Swal.fire('Error!', data.message || 'Failed to submit report.', 'error');
                                }
                            })
                            .catch(error => {
                                console.error('Error reporting video:', error);
                                Swal.fire('Error!', 'Failed to submit report. Please try again.', 'error');
                            });
                        }
                    });
                } else {
                    const reason = prompt('Please tell us why you are reporting this video:');
                    if (reason) {
                        // Submit basic report
                        console.log('Report submitted for video:', videoId, 'Reason:', reason);
                        alert('Thank you for your report. We will review it shortly.');
                    }
                }
            };
        </script>
        
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
