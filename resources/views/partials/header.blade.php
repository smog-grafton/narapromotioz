<!-- Header Style One Start -->
<header class="header-style-one three" >
    <div class="container">
        <div class="top-bar">
            <p><i class="fa-regular fa-clock"></i>Mon - Sat: 09.00 to 06.00</p>
            <ul class="social-media">
                <li><a href="#"><i class="fa-brands fa-facebook-f"></i></a></li>
                <li><a href="#"><i class="flaticon-twitter"></i></a></li>
                <li><a href="#"><i class="flaticon-instagram"></i></a></li> 
            </ul>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="desktop-nav" id="stickyHeader">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="d-flex-all justify-content-between">
                                <div class="header-logo">
                                    <a href="{{ route('home') }}">
                                        <figure>
                                            <img src="{{ asset('assets/images/logo.svg') }}" alt="{{ config('app.name') }} Logo">
                                        </figure>
                                    </a>
                                </div>
                                <div class="nav-bar">
                                    <ul>
                                        <li>
                                            <a href="{{ route('home') }}">Home</a>                                           
                                        </li>
                                        <li >
                                            <a href="{{ route('about') }}">About</a>                                           
                                        </li>
                                        <li>
                                            <a href="{{ route('boxers.index') }}">Boxers</a>
                                        </li>
                                        
                                        <li class="menu-item-has-children"><a href="JavaScript:void(0)">Pages</a>
                                            <ul class="sub-menu">
                                                <li class="menu-item-has-children">
                                                    <a href="javascript:void(0)">Services</a>
                                                    <ul class="sub-menu">
                                                        <li><a href="#">Services</a></li>
                                                        <li><a href="#">Service Detail</a></li>
                                                    </ul>
                                                </li>
                                                <li class="menu-item-has-children">
                                                    <a href="javascript:void(0)">Classes</a>
                                                    <ul class="sub-menu">
                                                        <li><a href="#">Our Classes</a></li>
                                                        <li><a href="#">Our Classes 2</a></li>
                                                        <li><a href="#">Class Details</a></li>
                                                        <li><a href="#">Classes Schedule</a></li>
                                                    </ul>
                                                </li>
                                                <li class="menu-item-has-children">
                                                    <a href="javascript:void(0)">Team</a>
                                                    <ul class="sub-menu">
                                                        <li><a href="#">Our Team</a></li>
                                                        <li><a href="#">Team Detail</a></li>
                                                    </ul>
                                                </li>
                                                @if (Route::has('login'))
                                                    @auth
                                                        <li><a href="{{ url('/admin') }}">Admin Panel</a></li>
                                                    @else
                                                        <li><a href="{{ route('login') }}">Login & Register</a></li>
                                                    @endauth
                                                @endif
                                            </ul>
                                        </li>
                                        <li>
                                            <a href="{{ route('news.index') }}">News</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('contact') }}">Contact</a>
                                        </li>
                                    </ul>
                                    
                                    <div class="extras">
                                        <a href="javascript:void(0)" id="mobile-menu" class="menu-start">
                                            <svg id="ham-menu" viewBox="0 0 100 100"> 
                                                <path class="line line1" d="M 20,29.000046 H 80.000231 C 80.000231,29.000046 94.498839,28.817352 94.532987,66.711331 94.543142,77.980673 90.966081,81.670246 85.259173,81.668997 79.552261,81.667751 75.000211,74.999942 75.000211,74.999942 L 25.000021,25.000058" /> 
                                                <path class="line line2" d="M 20,50 H 80" /> 
                                                <path class="line line3" d="M 20,70.999954 H 80.000231 C 80.000231,70.999954 94.498839,71.182648 94.532987,33.288669 94.543142,22.019327 90.966081,18.329754 85.259173,18.331003 79.552261,18.332249 75.000211,25.000058 75.000211,25.000058 L 25.000021,74.999942" /> 
                                            </svg>
                                        </a> 
                                        <a href="#" class="phone-number"><i class="flaticon-iphone"></i>+256 752 463322</a>
                                        <a href="#" class="theme-btn">Buy Tickets</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mobile-nav" id="mobile-nav">
                <div class="res-log">
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('assets/images/logo-2.svg') }}" alt="Responsive Logo">
                    </a>
                </div>
                <ul>
                    <li class="menu-item-has-children">
                        <a href="javascript:void(0)">Home</a>
                        <ul class="sub-menu">
                            <li><a href="{{ route('home') }}">Home One</a></li>
                            <li><a href="#">Home Two</a></li>
                            <li><a href="#">Home Three</a></li>
                        </ul>
                    </li>
                    <li class="menu-item-has-children">
                        <a href="javascript:void(0)">About</a>
                        <ul class="sub-menu">
                            <li><a href="{{ route('about') }}">About</a></li>
                            <li><a href="#">Features & Benefits</a></li>  
                        </ul>
                    </li>
                    <li>
                        <a href="{{ route('boxers.index') }}">Boxers</a>
                    </li>
                    <li class="menu-item-has-children"><a href="JavaScript:void(0)">Shop</a>
                        <ul class="sub-menu">
                            <li><a href="#">Our Products</a></li>
                            <li><a href="#">Product Details</a></li>
                            <li><a href="#">Shop Cart</a></li>
                            <li><a href="#">Cart Checkout</a></li>
                        </ul>
                    </li>
                    <li class="menu-item-has-children"><a href="JavaScript:void(0)">Pages</a>
                        <ul class="sub-menu">
                            <li class="menu-item-has-children">
                                <a href="javascript:void(0)">Services</a>
                                <ul class="sub-menu">
                                    <li><a href="#">Services</a></li>
                                    <li><a href="#">Service Detail</a></li>
                                </ul>
                            </li>
                            <li class="menu-item-has-children">
                                <a href="javascript:void(0)">Classes</a>
                                <ul class="sub-menu">
                                    <li><a href="#">Our Classes</a></li>
                                    <li><a href="#">Our Classes 2</a></li>
                                    <li><a href="#">Class Details</a></li>
                                    <li><a href="#">Classes Schedule</a></li>
                                </ul>
                            </li>
                            <li class="menu-item-has-children">
                                <a href="javascript:void(0)">Team</a>
                                <ul class="sub-menu">
                                    <li><a href="#">Our Team</a></li>
                                    <li><a href="#">Team Detail</a></li>
                                </ul>
                            </li>
                            @if (Route::has('login'))
                                @auth
                                    <li><a href="{{ url('/admin') }}">Admin Panel</a></li>
                                @else
                                    <li><a href="{{ route('login') }}">Login & Register</a></li>
                                @endauth
                            @endif
                        </ul>
                    </li>
                    <li class="menu-item-has-children">
                        <a href="javascript:void(0)">News</a>
                        <ul class="sub-menu">
                            <li><a href="{{ route('news.index') }}">Our Blog One</a></li>
                            <li><a href="#">Our Blog Two</a></li>
                            <li><a href="#">Blog Detail</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="{{ route('contact') }}">Contact</a>
                    </li>
                </ul>
                <a href="JavaScript:void(0)" id="res-cross"></a>
            </div>
        </div>
    </div>
</header>
<!-- Header Style One End --> 