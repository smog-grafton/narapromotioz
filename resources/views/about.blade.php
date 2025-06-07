@extends('layouts.app')

@section('title', 'About Us - ' . config('app.name'))

@section('content')
<!-- Banner Style One Start -->
<section class="banner-style-one">
    <div class="parallax" style="background-image: url(https://via.placeholder.com/1920x640);"></div>
    <div class="container">
        <div class="row">
            <div class="banner-details">
                <h2>About Us</h2>
                <p>our values and vaulted us to the top of our industry.</p>
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
                            <p>Home</p>
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
                    <h2>Welcome to GymOn Fitness Training Center & Yoga Studio</h2>
                    <figure>
                        <img class="w-100" src="https://via.placeholder.com/636x400" alt="About Image One">
                    </figure>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="who-we-are space">
                    <div>
                        <h3>Who We Are?</h3>
                        <p>A gym isn't just a place for exercise; it's the place you go to unwind, socialize & work out. The gym is a whole some experience. Some of the most successful facilities have several gym features that contribute to the kind of member experience that drives retention and sales. Our mission is to create a nurturing and empowering the environment where individuals of all ages, abilities, and fitness aspirations can thrive. We dive headfirst into your brand, content, and goals. </p>
                    </div>
                    <div>
                        <h3>What's in it for me?</h3>
                        <ul>
                            <li><i class="fa-solid fa-circle-dot"></i> 22,000 square feet Gym</li>
                            <li><i class="fa-solid fa-circle-dot"></i> State of the Art Equipment</li>
                            <li><i class="fa-solid fa-circle-dot"></i> programs for weight loss</li>
                            <li><i class="fa-solid fa-circle-dot"></i> Meet Experts Trainers</li>
                            <li><i class="fa-solid fa-circle-dot"></i> Don't take our word for it</li>
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
                        <span class="counter">10</span>+<i>Years</i>
                    </div>
                    <h4>Professional Experience</h4>
                </div>
            </div>
            <div class="col-lg-4" >
                <div class="counter-data upper-space">
                    <div class="count">
                        <span class="counter">90</span><i>Trainers</i>
                    </div>
                    <h4>Experts Trainers Team Members</h4>
                </div>
            </div>
            <div class="col-lg-4" >
                <div class="counter-data">
                    <div class="count">
                        <span class="counter">21</span>+<i>Locations</i>
                    </div>
                    <h4>Different centers in different states</h4>
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
        <span>Plan + Control</span>
        <h2>How it Works</h2>
    </div> 
    <div class="container">
        <div class="row g-0">
            <div class="col-lg-3 col-md-6 col-sm-12" >
                <div class="plans">
                    <i class="flaticon-location-1"></i>
                    <div class="y-box d-flex-all">
                        1.
                    </div>
                    <h3>Select Location</h3>
                    <p>The gym is a whole experience. Some of the most successful facilities have several gym</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12" >
                <div class="plans">
                    <i class="flaticon-contact-form"></i>
                    <div class="y-box d-flex-all">
                        2.
                    </div>
                    <h3>Get Membership</h3>
                    <p>The gym is a whole experience. Some of the most successful facilities have several gym</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12" >
                <div class="plans">
                    <i class="flaticon-gym"></i>
                    <div class="y-box d-flex-all">
                        3.
                    </div>
                    <h3>Start Classes</h3>
                    <p>The gym is a whole experience. Some of the most successful facilities have several gym</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12" >
                <div class="plans">
                    <i class="flaticon-arm"></i>
                    <div class="y-box d-flex-all">
                        4.
                    </div>
                    <h3>Healthy & Fit</h3>
                    <p>The gym is a whole experience. Some of the most successful facilities have several gym</p>
                </div>
            </div>
        </div>
    </div>
</section>
<!--About How It Works End -->

<!-- Video Section Start -->
<video autoplay muted loop>
    <source src="{{ asset('assets/videos/video.mp4') }}" type="video/mp4">
    Your browser does not support the video tag.
</video>
<!-- Video Section End -->

<!--About Key Benefits Start -->
<section class="gap certificates">
    <div class="container">
        <div class="row">
            <div class="col-lg-6" >
                <div class="data">
                    <figure class="c-img">
                        <img src="https://via.placeholder.com/606x704" alt="certificates img">
                    </figure> 
                </div>
            </div>
            <div class="col-lg-6" >
                <div class="data data2">
                    <span>Since we were founded in 1984</span>
                    <h2>A Brief History of the GymOn Fitness Center</h2>
                </div>
                <div class="c-slider owl-carousel">
                    <div class="c-main">
                        <div class="c-second">
                            <span>2001 - 2006</span>
                            <h3>In the Beginning of GymOn Fitness Center</h3>
                            <p>The SEGD Global Design Awards represents the best in experiential graphic design and covers a variety of topics.</p>
                        </div>
                        <div class="c-first">
                            <figure>
                                <img src="https://via.placeholder.com/188x188" alt="c-img-1">
                            </figure>
                        </div>
                    </div> 
                    <div class="c-main">
                        <div class="c-second">
                            <span>2007 - 2012</span>
                            <h3>The Dark Ages and Rebirth of Fitness</h3>
                            <p>The SEGD Global Design Awards represents the best in experiential graphic design and covers a variety of topics.</p>
                        </div>
                        <div class="c-first">
                            <figure>
                                <img src="https://via.placeholder.com/188x188" alt="c-img-1">
                            </figure>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--About Key Benefits End -->

<!-- Team Style One Start -->
<section class="team-style-one">
    <div class="heading-style-2">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 col-md-8">
                    <div class="data">
                        <span>Meet Experts Trainers</span>
                        <h2>Expert Coaches</h2>
                    </div>
                </div> 
                <div class="col-lg-6 col-md-4">
                    <div class="team-slider-nav">
                        
                    </div>
                </div>         
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="team-slider owl-carousel">
                    <div class="team-data item">
                        <div class="team-info">
                            <h3>Gorden Qlark</h3>
                            <p>CROSSFIT COACH</p>
                            <div class="team-social-media">
                                <a href="#"><i class="fa-brands fa-facebook-f"></i></a> 
                                <a href="#"><i class="flaticon-twitter"></i></a> 
                                <a href="#"><i class="flaticon-instagram"></i></a>  
                            </div>
                            <svg width="261" height="235" viewBox="0 0 261 235" xmlns="http://www.w3.org/2000/svg">
                                <path d="M261 0L190.483 144.088H128.135L162.104 102.062L188.333 45.885H89.8666L70.0873 87.4818H117.815L0 235L49.0181 125.219H0L61.9176 0H261Z"></path>
                            </svg>
                        </div>
                        <div class="team-image">
                            <figure>
                                <img src="https://via.placeholder.com/327x430" alt="team-3">
                            </figure>
                        </div> 
                    </div> 
                    <div class="team-data item">
                        <div class="team-info">
                            <h3>Moniqa Linda</h3>
                            <p>FITNESS COACH</p>
                            <div class="team-social-media">
                                <a href="#"><i class="fa-brands fa-facebook-f"></i></a> 
                                <a href="#"><i class="flaticon-twitter"></i></a> 
                                <a href="#"><i class="flaticon-instagram"></i></a>  
                            </div>
                            <svg width="261" height="235" viewBox="0 0 261 235" xmlns="http://www.w3.org/2000/svg">
                                <path d="M261 0L190.483 144.088H128.135L162.104 102.062L188.333 45.885H89.8666L70.0873 87.4818H117.815L0 235L49.0181 125.219H0L61.9176 0H261Z"></path>
                            </svg>
                        </div>
                        <div class="team-image">
                            <figure>
                                <img src="https://via.placeholder.com/327x430" alt="team-3">
                            </figure>
                        </div> 
                    </div>
                    <div class="team-data item">
                        <div class="team-info">
                            <h3>Robert Jessi</h3>
                            <p>BODYBUILDING COACH</p>
                            <div class="team-social-media">
                                <a href="#"><i class="fa-brands fa-facebook-f"></i></a> 
                                <a href="#"><i class="flaticon-twitter"></i></a> 
                                <a href="#"><i class="flaticon-instagram"></i></a>  
                            </div>
                            <svg width="261" height="235" viewBox="0 0 261 235" xmlns="http://www.w3.org/2000/svg">
                                <path d="M261 0L190.483 144.088H128.135L162.104 102.062L188.333 45.885H89.8666L70.0873 87.4818H117.815L0 235L49.0181 125.219H0L61.9176 0H261Z"></path>
                            </svg>
                        </div>
                        <div class="team-image">
                            <figure>
                                <img src="https://via.placeholder.com/327x430" alt="team-3">
                            </figure>
                        </div> 
                    </div>
                    <div class="team-data item">
                        <div class="team-info">
                            <h3>Willimes Haniq</h3>
                            <p>YOGA COACH</p>
                            <div class="team-social-media">
                                <a href="#"><i class="fa-brands fa-facebook-f"></i></a> 
                                <a href="#"><i class="flaticon-twitter"></i></a> 
                                <a href="#"><i class="flaticon-instagram"></i></a>  
                            </div>
                            <svg width="261" height="235" viewBox="0 0 261 235" xmlns="http://www.w3.org/2000/svg">
                                <path d="M261 0L190.483 144.088H128.135L162.104 102.062L188.333 45.885H89.8666L70.0873 87.4818H117.815L0 235L49.0181 125.219H0L61.9176 0H261Z"></path>
                            </svg>
                        </div>
                        <div class="team-image">
                            <figure>
                                <img src="https://via.placeholder.com/327x430" alt="team-3">
                            </figure>
                        </div> 
                    </div> 
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Team Style One End -->

<!-- Client Review Style One Start -->
<section class="gap client-review-style-one">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6" >
                <div class="head-review">
                    <span>Testimonials</span>
                    <h3>Client's Reviews</h3>
                </div>
                <div class="client-review-slider owl-carousel">
                    <div class="slider-data">
                        <p>Comprehensive services, state-of-the-art equipment, and a supportive environment ensuring members achieve their fitness objectives comfortably and effectively.</p>
                        <div class="bio d-flex-all justify-content-start w-100">
                            <div class="icon d-flex-all">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="26" height="26" viewBox="0 0 26 26"> <defs> <clipPath id="clip-Inverted"> <rect width="26" height="26"/> </clipPath> </defs> <g id="Inverted_" data-name="Inverted commas flaky" clip-path="url(#clip-Inverted)"> <path id="Path_3444" data-name="Path 3" d="M.032,24.036V14.478l-.032,0V8.991C.4.4,9.086,0,9.086,0V5.961c-3.535,0-3.555,3.03-3.555,3.03v4.045h5.5v11ZM0,8.991Z" transform="translate(14 0.964)"/> <path id="Path_weee4" data-name="Path 4" d="M.032,24.036V14.478l-.032,0V8.991C.4.4,9.086,0,9.086,0V5.961c-3.535,0-3.555,3.03-3.555,3.03v4.045h5.5v11ZM0,8.991Z" transform="translate(0.969 0.964)"/> </g> </svg>
                            </div>
                            <div class="details w-100">
                                <h3>Marko Marlee</h3>
                                <p>Chairman, Building Corp</p>
                            </div>
                        </div>
                    </div>
                    <div class="slider-data">
                        <p>The instructors are fantastic – very friendly and enthusiastic. The activities are varied from week to week which keeps it exciting. I would highly recommend this gym. We dive headfirst into your brand, content, and goals. </p>
                        <div class="bio d-flex-all justify-content-start w-100">
                            <div class="icon d-flex-all">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="26" height="26" viewBox="0 0 26 26"> <defs> <clipPath id="clip-Inverted_comma"> <rect width="26" height="26"/> </clipPath> </defs> <g id="Inver" data-name="Inverted commas flaky" clip-path="url(#clip-Inverted_comma)"> <path id="Path_332" data-name="Path 3" d="M.032,24.036V14.478l-.032,0V8.991C.4.4,9.086,0,9.086,0V5.961c-3.535,0-3.555,3.03-3.555,3.03v4.045h5.5v11ZM0,8.991Z" transform="translate(14 0.964)"/> <path id="Path_3344" data-name="Path 4" d="M.032,24.036V14.478l-.032,0V8.991C.4.4,9.086,0,9.086,0V5.961c-3.535,0-3.555,3.03-3.555,3.03v4.045h5.5v11ZM0,8.991Z" transform="translate(0.969 0.964)"/> </g> </svg>
                            </div>
                            <div class="details w-100">
                                <h3>Christopher</h3>
                                <p>Social Worker</p>
                            </div>
                        </div>
                    </div>
                    <div class="slider-data">
                        <p>Sed rhoncus nulla turpis, vitae rutrum velit iaculis et. Curabitur vestibulum, erat non imperdiet vulputate, est neque iaculis mi, at malesuada eros ante sit amet elit.</p>
                        <div class="bio d-flex-all justify-content-start w-100">
                            <div class="icon d-flex-all">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="26" height="26" viewBox="0 0 26 26"> <defs> <clipPath id="clip-Inve"> <rect width="26" height="26"/> </clipPath> </defs> <g id="Inverted_co" data-name="Inverted commas flaky" clip-path="url(#clip-Inve)"> <path id="Path_35555" data-name="Path 3" d="M.032,24.036V14.478l-.032,0V8.991C.4.4,9.086,0,9.086,0V5.961c-3.535,0-3.555,3.03-3.555,3.03v4.045h5.5v11ZM0,8.991Z" transform="translate(14 0.964)"/> <path id="Path_4545454" data-name="Path 4" d="M.032,24.036V14.478l-.032,0V8.991C.4.4,9.086,0,9.086,0V5.961c-3.535,0-3.555,3.03-3.555,3.03v4.045h5.5v11ZM0,8.991Z" transform="translate(0.969 0.964)"/> </g> </svg>
                            </div>
                            <div class="details w-100">
                                <h3>Donald Paul</h3>
                                <p>Fitness Trainer</p>
                            </div>
                        </div>
                    </div>
                    <div class="slider-data">
                        <p>Adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.natus error sit voluptatem accusantium doloremque laudantium. Veritatis et quasi architecto beatae vitae dicta sunt explicabo. </p>
                        <div class="bio d-flex-all justify-content-start w-100">
                            <div class="icon d-flex-all">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="26" height="26" viewBox="0 0 26 26"> <defs> <clipPath id="clip-Inverted_coadsadad"> <rect width="26" height="26"/> </clipPath> </defs> <g id="Inverte" data-name="Inverted commas flaky" clip-path="url(#clip-Inverted_coadsadad)"> <path id="Path_3fewrrw" data-name="Path 3" d="M.032,24.036V14.478l-.032,0V8.991C.4.4,9.086,0,9.086,0V5.961c-3.535,0-3.555,3.03-3.555,3.03v4.045h5.5v11ZM0,8.991Z" transform="translate(14 0.964)"/> <path id="Path_werwer4" data-name="Path 4" d="M.032,24.036V14.478l-.032,0V8.991C.4.4,9.086,0,9.086,0V5.961c-3.535,0-3.555,3.03-3.555,3.03v4.045h5.5v11ZM0,8.991Z" transform="translate(0.969 0.964)"/> </g> </svg>
                            </div>
                            <div class="details w-100">
                                <h3>Kevin Samuel</h3>
                                <p>Creative Head</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6" >
                <figure>
                    <img src="https://via.placeholder.com/546x441" alt="Client Images">
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
                    <img class="w-auto m-auto" src="https://via.placeholder.com/154x94" alt="client-1">
                    <img class="w-auto m-auto" src="https://via.placeholder.com/154x94" alt="client-2">
                    <img class="w-auto m-auto" src="https://via.placeholder.com/154x94" alt="client-3">
                    <img class="w-auto m-auto" src="https://via.placeholder.com/154x94" alt="client-4">
                    <img class="w-auto m-auto" src="https://via.placeholder.com/154x94" alt="client-5">
                    <img class="w-auto m-auto" src="https://via.placeholder.com/154x94" alt="client-1">
                    <img class="w-auto m-auto" src="https://via.placeholder.com/154x94" alt="client-2">
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Client Style One End -->
@endsection 