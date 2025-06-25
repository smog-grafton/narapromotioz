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
                                        
                                        <li class="menu-item-has-children"><a href="{{ route('events.index') }}">Events</a>
                                            <ul class="sub-menu">
                                                <li><a href="{{ route('events.index') }}">All Events</a></li>
                                                <li><a href="{{ route('events.upcoming') }}">Upcoming Events</a></li>
                                                <li><a href="{{ route('events.past') }}">Past Events</a></li>
                                            </ul>
                                        </li>
                                        <li>
                                            <a href="{{ route('videos.index') }}">Videos</a>
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
                                        
                                        
                                        @if (Route::has('login'))
                                            @auth
                                                <!-- User Dropdown -->
                                                <div class="user-dropdown menu-item-has-children">
                                                    <a href="javascript:void(0)" class="user-profile-link">
                                                        @if(Auth::user()->avatar)
                                                            <img src="{{ Storage::url(Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}" class="user-avatar">
                                                        @else
                                                            <i class="fa-solid fa-user-circle"></i>
                                                        @endif
                                                        
                                                        <i class="fa-solid fa-chevron-down"></i>
                                                    </a>
                                                    <ul class="sub-menu user-menu">
                                                        <li><a href="{{ route('dashboard') }}"><i class="fa-solid fa-tachometer-alt"></i> Dashboard</a></li>
                                                        <li><a href="{{ route('tickets.my-tickets') }}"><i class="fa-solid fa-ticket"></i> My Tickets</a></li>
                                                        <li><a href="{{ route('dashboard.profile') }}"><i class="fa-solid fa-user-edit"></i> Profile Settings</a></li>
                                                        @if(Auth::user()->role === 'admin')
                                                            <li><a href="{{ url('/admin') }}"><i class="fa-solid fa-cogs"></i> Admin Panel</a></li>
                                                        @endif
                                                        <li class="divider"></li>
                                                        <li>
                                                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                                <i class="fa-solid fa-sign-out-alt"></i> Logout
                                                            </a>
                                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                                                @csrf
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                            @else
                                                <a href="{{ route('login') }}" class="login-btn"><i class="fa-solid fa-sign-in-alt"></i> Login</a>
                                            @endauth
                                        @endif
                                        
                                        <a href="{{ route('events.upcoming') }}" class="theme-btn">Buy Tickets</a>
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
                    <li>
                        <a href="{{ route('home') }}">Home</a>
                    </li>
                    <li>
                        <a href="{{ route('about') }}">About</a>
                    </li>
                    <li>
                        <a href="{{ route('boxers.index') }}">Boxers</a>
                    </li>
                    <li class="menu-item-has-children"><a href="{{ route('events.index') }}">Events</a>
                        <ul class="sub-menu">
                            <li><a href="{{ route('events.index') }}">All Events</a></li>
                            <li><a href="{{ route('events.upcoming') }}">Upcoming Events</a></li>
                            <li><a href="{{ route('events.past') }}">Past Events</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="{{ route('videos.index') }}">Videos</a>
                    </li>
                    <li>
                        <a href="{{ route('news.index') }}">News</a>
                    </li>
                    <li>
                        <a href="{{ route('contact') }}">Contact</a>
                    </li>
                    
                    @if (Route::has('login'))
                        @auth
                            <!-- Mobile User Menu -->
                            <li class="mobile-user-info">
                               <!-- <div class="user-profile">
                                    @if(Auth::user()->avatar)
                                        <img src="{{ Storage::url(Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}" class="mobile-user-avatar">
                                    @else
                                        <i class="fa-solid fa-user-circle"></i>
                                    @endif
                                    <span>{{ Auth::user()->name }}</span>
                                    <small class="user-role">{{ ucfirst(Auth::user()->role) }}</small>
                                </div>-->
                            </li>
                            <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li><a href="{{ route('tickets.my-tickets') }}"> My Tickets</a></li>
                            <li><a href="{{ route('dashboard.profile') }}">Profile Settings</a></li>
                            @if(Auth::user()->role === 'admin')
                                <li><a href="{{ url('/admin') }}"> Admin Panel</a></li>
                            @endif
                            <li>
                                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('mobile-logout-form').submit();">
                                     Logout
                                </a>
                                <form id="mobile-logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        @else
                            <li><a href="{{ route('login') }}"> Login</a></li>
                            <li><a href="{{ route('register') }}">Register</a></li>
                        @endauth
                    @endif
                </ul>
                <a href="JavaScript:void(0)" id="res-cross"></a>
            </div>
        </div>
    </div>
</header>
<!-- Header Style One End --> 