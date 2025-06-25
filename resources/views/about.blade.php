@extends('layouts.app')

@section('title', 'About Us - ' . config('app.name'))

@section('content')
<!-- Banner Style One Start -->
<section class="banner-style-one">
    <div class="parallax" style="background-image: url({{ asset('assets/images/banner/banner-style-one.jpg') }});"></div>
    <div class="container">
        <div class="row">
            <div class="banner-details">
                <h2>About Us</h2>
                <p>Elevating the sport of boxing across Uganda and East Africa through professional events and talent development.</p>
            </div>
        </div>
    </div>
    <div class="breadcrums">
        <div class="container">
            <div class="row">
                <ul>
                    <li>
                        <a href="{{ route('home') }}">
                            <i class="fa-solid fa-house"></i>
                           
                        </a>
                    </li>
                    <li class="current">
                        <p>About Us</p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>
<!-- Banner Style One End -->

<!-- About-First Start -->
<section class="gap about-first"> 
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="who-we-are">
                    <h2>Welcome to Nara Promotionz</h2>
                    <figure>
                        <img class="w-100" src="{{ asset('assets/images/how-it-work/aboutimg.jpg') }}" alt="About Nara Promotionz">
                    </figure>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="who-we-are space">
                    <div>
                        <h3>Who We Are?</h3>
                        <p>Nara Promotionz is a premier sports promotion company at the heart of Uganda's professional boxing scene. Headquartered in Kampala, we are dedicated to elevating the sport of boxing by creating professionally managed, high-octane events that showcase the best talent from Uganda and across the East African region. Our mission is to provide a consistent and credible platform for professional fighters to build their careers, challenge for titles, and gain international recognition.</p>
                    </div>
                    <div>
                        <h3>What We Offer?</h3>
                        <ul>
                            <li><i class="fa-solid fa-circle-dot"></i> Professional Boxing Events</li>
                            <li><i class="fa-solid fa-circle-dot"></i> Sweet Science Boxing Series</li>
                            <li><i class="fa-solid fa-circle-dot"></i> Talent Development Programs</li>
                            <li><i class="fa-solid fa-circle-dot"></i> International Title Fights</li>
                            <li><i class="fa-solid fa-circle-dot"></i> Community Boxing Support</li>
                        </ul>
                    </div> 
                </div>
            </div>
        </div>
    </div>
</section>
<!-- About-First End -->

<!-- Counter Style One Start -->
<section class="gap no-top counter-style-one">
    <div class="container">
        <div class="row">
            <div class="col-lg-4" >
                <div class="counter-data">
                    <div class="count">
                        <span class="counter">5</span>+<i>Years</i>
                    </div>
                    <h4>Professional Experience in Boxing Promotion</h4>
                </div>
            </div>
            <div class="col-lg-4" >
                <div class="counter-data upper-space">
                    <div class="count">
                        <span class="counter">20</span>+<i>Events</i>
                    </div>
                    <h4>Professional Boxing Events Organized</h4>
                </div>
            </div>
            <div class="col-lg-4" >
                <div class="counter-data">
                    <div class="count">
                        <span class="counter">100</span>+<i>Fighters</i>
                    </div>
                    <h4>Professional Boxers Featured</h4>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Counter Style One End -->

<!--About How It Works Start -->
<section class="gap about-how-it-works light-bg-color">
    <div class="heading">
        <figure>
            <img src="{{ asset('assets/images/heading-icon.png') }}" alt="Heading Icon">
        </figure>
        <span>Professional Boxing</span>
        <h2>How We Work</h2>
    </div> 
    <div class="container">
        <div class="row g-0">
            <div class="col-lg-3 col-md-6 col-sm-12" >
                <div class="plans">
                    <i class="flaticon-location-1"></i>
                    <div class="y-box d-flex-all">
                        1.
                    </div>
                    <h3>Scout Talent</h3>
                    <p>We identify and scout promising boxing talent from across Uganda and East Africa to feature in our events.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12" >
                <div class="plans">
                    <i class="flaticon-contact-form"></i>
                    <div class="y-box d-flex-all">
                        2.
                    </div>
                    <h3>Organize Events</h3>
                    <p>We plan and execute professional boxing events with high production value and compelling matchups.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12" >
                <div class="plans">
                    <i class="flaticon-gym"></i>
                    <div class="y-box d-flex-all">
                        3.
                    </div>
                    <h3>Promote Fights</h3>
                    <p>Through our media partnerships and platform, we provide maximum exposure for our fighters and events.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12" >
                <div class="plans">
                    <i class="flaticon-arm"></i>
                    <div class="y-box d-flex-all">
                        4.
                    </div>
                    <h3>Build Careers</h3>
                    <p>We help fighters build sustainable careers and gain international recognition in the sport of boxing.</p>
                </div>
            </div>
        </div>
    </div>
</section>
<!--About How It Works End -->

<!-- Video Section Start 
<video autoplay muted loop>
    <source src="{{ asset('assets/videos/video.mp4') }}" type="video/mp4">
    Your browser does not support the video tag.
</video>-->
<!-- Video Section End -->

<!--About Key Benefits Start -->
<section class="gap certificates">
    <div class="container">
        <div class="row">
            <div class="col-lg-6" >
                <div class="data">
                    <figure class="c-img">
                        <img src="{{ asset('assets/images/history/historyimg.jpg') }}" alt="Nara Promotionz History">
                    </figure> 
                </div>
            </div>
            <div class="col-lg-6" >
                <div class="data data2">
                    <span>Since we were founded</span>
                    <h2>A Brief History of Nara Promotionz</h2>
                </div>
                <div class="c-slider owl-carousel">
                    <div class="c-main">
                        <div class="c-second">
                            <span>2013 - 2020</span>
                            <h3>The Foundation Years</h3>
                            <p>Founded by Lubowa Babu Hussein, Nara Promotionz was born from a desire to address a clear need within the Ugandan sports community for consistent, high-quality professional boxing events.</p>
                        </div>
                        <div class="c-first">
                            <figure>
                                <img src="{{ asset('assets/images/history/hist1.png') }}" alt="Foundation Years">
                            </figure>
                        </div>
                    </div> 
                    <div class="c-main">
                        <div class="c-second">
                            <span>2021 - Present</span>
                            <h3>Sweet Science Series Launch</h3>
                            <p>The inaugural "Sweet Science Season 1" was held on September 30th, 2023, at Club Obligato in Kampala, featuring thrilling lineups of professional boxers from Uganda, Kenya, and Tanzania.</p>
                        </div>
                        <div class="c-first">
                            <figure>
                                <img src="{{ asset('assets/images/history/hist2.png') }}" alt="Sweet Science Launch">
                            </figure>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--About Key Benefits End -->

<!-- Director Section Start -->
<section class="gap about-first">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="who-we-are">
                    <div class="heading">
                        <span>Leadership</span>
                        <h2>Our Director</h2>
                    </div>
                    <h3>Lubowa Babu Hussein</h3>
                    <p><strong>Director & Founder</strong></p>
                    <p>Lubowa Babu Hussein is the driving force behind Nara Promotionz. With a career in boxing promotion spanning from 2013 to the present, he has a deep understanding of the sport and a passion for its growth in Uganda.</p>
                    <p>His expertise is further amplified by his role as a sports commentator at 90.8 Metro FM, which gives him a unique platform to connect with the boxing community and promote our events. His vision and leadership have been instrumental in establishing Nara Promotionz as a premier boxing promotion company in the region.</p>
                    <p>Under his leadership, we have successfully created the "Sweet Science" boxing series and organized multiple ABU (African Boxing Union) title fights, demonstrating our capability to host major international boxing events.</p>
                </div>
            </div>
            <div class="col-lg-6">
                <figure>
                    <img class="w-100" src="{{ asset('assets/images/babu.jpg') }}" alt="Lubowa Babu Hussein - Director">
                </figure>
            </div>
        </div>
    </div>
</section>
<!-- Director Section End -->

<!-- Client Review Style One Start -->
<section class="gap client-review-style-one">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6" >
                <div class="head-review">
                    <span>Testimonials</span>
                    <h3>Fighter's Reviews</h3>
                </div>
                <div class="client-review-slider owl-carousel">
                    <div class="slider-data">
                        <p>Nara Promotionz has provided me with the platform to showcase my skills on an international level. Their professional approach to boxing promotion is unmatched in Uganda.</p>
                        <div class="bio d-flex-all justify-content-start w-100">
                            <div class="icon d-flex-all">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="26" height="26" viewBox="0 0 26 26"> <defs> <clipPath id="clip-Inverted"> <rect width="26" height="26"/> </clipPath> </defs> <g id="Inverted_" data-name="Inverted commas flaky" clip-path="url(#clip-Inverted)"> <path id="Path_3444" data-name="Path 3" d="M.032,24.036V14.478l-.032,0V8.991C.4.4,9.086,0,9.086,0V5.961c-3.535,0-3.555,3.03-3.555,3.03v4.045h5.5v11ZM0,8.991Z" transform="translate(14 0.964)"/> <path id="Path_weee4" data-name="Path 4" d="M.032,24.036V14.478l-.032,0V8.991C.4.4,9.086,0,9.086,0V5.961c-3.535,0-3.555,3.03-3.555,3.03v4.045h5.5v11ZM0,8.991Z" transform="translate(0.969 0.964)"/> </g> </svg>
                            </div>
                            <div class="details w-100">
                                <h3>John Serunjogi</h3>
                                <p>Professional Boxer</p>
                            </div>
                        </div>
                    </div>
                    <div class="slider-data">
                        <p>Working with Nara Promotionz has been a game-changer for my boxing career. They create opportunities for fighters to compete at the highest level and gain the recognition we deserve.</p>
                        <div class="bio d-flex-all justify-content-start w-100">
                            <div class="icon d-flex-all">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="26" height="26" viewBox="0 0 26 26"> <defs> <clipPath id="clip-Inverted_comma"> <rect width="26" height="26"/> </clipPath> </defs> <g id="Inver" data-name="Inverted commas flaky" clip-path="url(#clip-Inverted_comma)"> <path id="Path_332" data-name="Path 3" d="M.032,24.036V14.478l-.032,0V8.991C.4.4,9.086,0,9.086,0V5.961c-3.535,0-3.555,3.03-3.555,3.03v4.045h5.5v11ZM0,8.991Z" transform="translate(14 0.964)"/> <path id="Path_3344" data-name="Path 4" d="M.032,24.036V14.478l-.032,0V8.991C.4.4,9.086,0,9.086,0V5.961c-3.535,0-3.555,3.03-3.555,3.03v4.045h5.5v11ZM0,8.991Z" transform="translate(0.969 0.964)"/> </g> </svg>
                            </div>
                            <div class="details w-100">
                                <h3>Herbat Mato</h3>
                                <p>Professional Boxer</p>
                            </div>
                        </div>
                    </div>
                    <div class="slider-data">
                        <p>The Sweet Science series organized by Nara Promotionz has elevated the standard of boxing in Uganda. It's exactly what our sport needed to gain international recognition.</p>
                        <div class="bio d-flex-all justify-content-start w-100">
                            <div class="icon d-flex-all">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="26" height="26" viewBox="0 0 26 26"> <defs> <clipPath id="clip-Inve"> <rect width="26" height="26"/> </clipPath> </defs> <g id="Inverted_co" data-name="Inverted commas flaky" clip-path="url(#clip-Inve)"> <path id="Path_35555" data-name="Path 3" d="M.032,24.036V14.478l-.032,0V8.991C.4.4,9.086,0,9.086,0V5.961c-3.535,0-3.555,3.03-3.555,3.03v4.045h5.5v11ZM0,8.991Z" transform="translate(14 0.964)"/> <path id="Path_4545454" data-name="Path 4" d="M.032,24.036V14.478l-.032,0V8.991C.4.4,9.086,0,9.086,0V5.961c-3.535,0-3.555,3.03-3.555,3.03v4.045h5.5v11ZM0,8.991Z" transform="translate(0.969 0.964)"/> </g> </svg>
                            </div>
                            <div class="details w-100">
                                <h3>Sulaiman Musaalo</h3>
                                <p>Professional Boxer</p>
                            </div>
                        </div>
                    </div>
                    <div class="slider-data">
                        <p>Nara Promotionz has created a professional platform where East African boxers can showcase their talents. Their events are world-class and provide great exposure for fighters.</p>
                        <div class="bio d-flex-all justify-content-start w-100">
                            <div class="icon d-flex-all">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="26" height="26" viewBox="0 0 26 26"> <defs> <clipPath id="clip-Inverted_coadsadad"> <rect width="26" height="26"/> </clipPath> </defs> <g id="Inverte" data-name="Inverted commas flaky" clip-path="url(#clip-Inverted_coadsadad)"> <path id="Path_3fewrrw" data-name="Path 3" d="M.032,24.036V14.478l-.032,0V8.991C.4.4,9.086,0,9.086,0V5.961c-3.535,0-3.555,3.03-3.555,3.03v4.045h5.5v11ZM0,8.991Z" transform="translate(14 0.964)"/> <path id="Path_werwer4" data-name="Path 4" d="M.032,24.036V14.478l-.032,0V8.991C.4.4,9.086,0,9.086,0V5.961c-3.535,0-3.555,3.03-3.555,3.03v4.045h5.5v11ZM0,8.991Z" transform="translate(0.969 0.964)"/> </g> </svg>
                            </div>
                            <div class="details w-100">
                                <h3>Lubega Wasswa</h3>
                                <p>Professional Boxer</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6" >
                <figure>
                    <img src="{{ asset('assets/images/testimonial/side_banner.png') }}" alt="Boxing Action">
                </figure>
            </div>
        </div>
    </div>
</section>
<!-- Client Review Style One End -->

<!-- Client Style One Start -->
<div class="gap no-top client-style-one">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="client-slider owl-carousel">
                    <img class="w-auto m-auto" src="{{ asset('assets/images/client/nara24fm.png') }}" alt="Nara 24 FM">
                    <img class="w-auto m-auto" src="{{ asset('assets/images/client/naraevents.png') }}" alt="Nara Events">
                    <img class="w-auto m-auto" src="{{ asset('assets/images/client/narasports.png') }}" alt="Nara Sports">
                    <img class="w-auto m-auto" src="{{ asset('assets/images/client/naratvlive.png') }}" alt="Nara TV Live">
                    <img class="w-auto m-auto" src="{{ asset('assets/images/client/smogcoders.png') }}" alt="Smog Coders">
                
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Client Style One End -->
@endsection 