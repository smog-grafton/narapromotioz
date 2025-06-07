<!-- Footer Style One Start -->
<footer class="footer-style-one" style="background-image: url(https://via.placeholder.com/1920x732);">
    <div class="footer-p-1">
        <div class="container">
            <div class="row">
                <div class="footer-first">
                    <div class="footer-logo">
                        <a href="{{ route('home') }}">
                            <img src="{{ asset('assets/images/logo-2.svg') }}" alt="Footer Logo">
                        </a>
                    </div>
                    <div class="contact-info d-flex-all">
                        <div class="images d-flex-all justify-content-start">
                            <figure>
                                <img src="{{ asset('assets/images/profiles/fighter1.jpg') }}" alt="Contact Images">
                            </figure>
                            <figure>
                                <img src="{{ asset('assets/images/profiles/fighter2.jpg') }}" alt="Contact Images">
                            </figure>
                            <figure>
                                <img src="{{ asset('assets/images/profiles/fighter3.jpg') }}" alt="Contact Images">
                            </figure>
                        </div>
                        <p>expert trainers <span>+1 (251) 344 0 66</span> free call !</p>
                    </div>
                    <a href="{{ route('contact') }}" class="theme-btn">Get a Consultation </a>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-p-2">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="footer-col">
                        <h3>Information</h3>
                        <p>Regular trips to the gym are great, but don't worry if you can't find a large chunk of time to exercise every day.</p>
                        <ul class="social-media">
                            <li><a href="#"><i class="fa-brands fa-facebook-f"></i></a></li>
                            <li><a href="#"><i class="flaticon-twitter"></i></a></li>
                            <li><a href="#"><i class="flaticon-instagram"></i></a></li>
                            <li><a href="#"><i class="flaticon-pinterest"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="footer-col">
                        <h3>Quick Links</h3>
                        <ul>
                            <li>
                                <i class="flaticon-maps"></i>
                                <p><a href="{{ route('home') }}">Home</a></p>
                            </li>
                            <li>
                                <i class="flaticon-iphone"></i>
                                <p><a href="{{ route('about') }}">About Us</a></p>
                            </li>
                            <li>
                                <i class="flaticon-mail"></i>
                                <p><a href="{{ route('boxers.index') }}">Our Boxers</a></p>
                            </li>
                            <li>
                                <i class="flaticon-mail"></i>
                                <p><a href="{{ route('news.index') }}">News & Updates</a></p>
                            </li>
                            <li>
                                <i class="flaticon-calendar"></i>
                                <p><a href="{{ route('events.index') }}">Boxing Events</a></p>
                            </li>
                            <li>
                                <i class="flaticon-clock"></i>
                                <p><a href="{{ route('events.upcoming') }}">Upcoming Events</a></p>
                            </li>
                            <li>
                                <i class="flaticon-mail"></i>
                                <p><a href="{{ route('contact') }}">Contact Us</a></p>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="footer-col">
                        <h3>Newsletter</h3>
                        <p>Signup for our weekly newsletter to get the latest news.</p>
                        <form>
                            <input type="email" name="email" placeholder="Enter your email.">
                            <button>
                                <i class="fa-solid fa-arrow-up-long"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <ul class="image-gallery">
        <li>
            <a href="{{ asset('assets/images/o-p-o-1.jpg') }}" data-fancybox="gallery">
                <i class="flaticon-instagram"></i>
            </a>
            <figure><img alt="gallery image" src="{{ asset('assets/images/o-p-o-2.jpg') }}"></figure>
        </li>
        <li>
            <a href="{{ asset('assets/images/o-p-o-3.jpg') }}" data-fancybox="gallery">
                <i class="flaticon-instagram"></i>
            </a>
            <figure><img alt="gallery image" src="{{ asset('assets/images/o-p-o-4.jpg') }}"></figure>
        </li>
        <li>
            <a href="{{ asset('assets/images/o-p-o-4.jpg') }}" data-fancybox="gallery">
                <i class="flaticon-instagram"></i>
            </a>
            <figure><img alt="gallery image" src="{{ asset('assets/images/o-p-o-5.jpg') }}"></figure>
        </li>
        <li>
            <a href="{{ asset('assets/images/o-p-o-5.jpg') }}" data-fancybox="gallery">
                <i class="flaticon-instagram"></i>
            </a>
            <figure><img alt="gallery image" src="{{ asset('assets/images/o-p-o-6.jpg') }}"></figure>
        </li>
        <li>
            <a href="{{ asset('assets/images/o-p-o-6.jpg') }}" data-fancybox="gallery">
                <i class="flaticon-instagram"></i>
            </a>
            <figure><img alt="gallery image" src="{{ asset('assets/images/o-p-o-7.jpg') }}"></figure>
        </li>
        <li>
            <a href="https://via.placeholder.com/250x150" data-fancybox="gallery">
                <i class="flaticon-instagram"></i>
            </a>
            <figure><img alt="gallery image" src="{{ asset('assets/images/o-p-o-8.jpg') }}"></figure>
        </li>
        <li>
            <a href="https://via.placeholder.com/250x150" data-fancybox="gallery">
                <i class="flaticon-instagram"></i>
            </a>
            <figure><img alt="gallery image" src="{{ asset('assets/images/o-p-o-9.jpg') }}"></figure>
        </li> 
    </ul>
    <div class="footer-p-3 rights">
        <div class="container">
            <div class="row">
                <div class="footer-col">
                    <p>{{ config('app.name') }} Center<i class="fa-solid fa-heart"></i> © {{ date('Y') }} <a href="{{ route('home') }}"> {{ config('app.name') }}</a> All rights reserved</p> 
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- Footer Style One End --> 