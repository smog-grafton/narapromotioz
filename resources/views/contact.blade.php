@extends('layouts.app')

@section('title', 'Contact Us - ' . config('app.name'))

@section('content')
<!-- Banner Style One Start -->
<section class="banner-style-one">
    <div class="parallax" style="background-image: url(https://via.placeholder.com/1920x640);"></div>
    <div class="container">
        <div class="row">
            <div class="banner-details">
                <h2>Contact Us</h2>
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
                        <p>Contact Us</p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>
<!-- Banner Style One End -->

<!-- Contact Form 2 Start -->
<section class="gap contact-form-2">
    <div class="heading">
        <figure>
            <img src="{{ asset('assets/images/heading-icon.png') }}" alt="Heading Icon">
        </figure>
        <span>Frequently asked question</span>
        <h2>Hello Guys Have Question? FEEL FREE TO ASK US ANYTHING</h2>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-7">
                <div class="data">
                    <p>Have questions or want to chat? Fill out our contact form, and we'll put you in touch with the right people.</p>
                    <form class="content-form" id="contact-form" method="post">
                        @csrf
                        <div class="row g-0">
                            <input type="text" name="Complete Name" placeholder="Complete Name" required="">
                        </div>
                        <div class="row g-0">
                            <input type="email" name="Email Address" placeholder="Email Address" required="">
                        </div>
                        <div class="row g-0">
                            <input type="number" name="Phone No" placeholder="Phone No" required="">
                        </div>
                        <div class="row g-0">
                            <textarea name="message" placeholder="Question / Message?"></textarea>
                        </div>
                        <button type="submit" class="theme-btn">Send Message</button>
                    </form>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="info">
                    <ul class="contact">
                        <li>
                            <i class="flaticon-maps"></i>
                            <div>
                                <h3>Address:</h3>
                                <p>65 Allerton Street 901 N Pitt Str, USA</p>
                            </div>
                        </li>
                        <li>
                            <i class="flaticon-iphone"></i>
                            <div>
                                <h3>Telephone:</h3>
                                <a href="tel:(+380)503184707">(+380) 50 318 47 07</a>
                                <a href="tel:(+182)503184707">(+182) 50 318 47 07</a>
                            </div>
                        </li>
                        <li class="pb-0">
                            <i class="flaticon-mail"></i>
                            <div>
                                <h3>Email:</h3>
                                <a href="mailto:username@domain.com">username@domain.com</a>
                                <a href="mailto:info@domain.com">info@domain.com</a>
                            </div>
                        </li>
                    </ul> 
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Contact Form 2 End -->

<!-- Contact Faqs Start -->
<section class="contact-faqs">
    <div class="heading">
        <figure>
            <img src="{{ asset('assets/images/heading-icon.png') }}" alt="Heading Icon">
        </figure>
        <span>Frequently asked question</span>
        <h2>Gym beginners guide</h2>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="acc2">
                    <div class="accordion" id="accordionExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading-Two">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-Two" aria-expanded="false" aria-controls="collapse-Two">
                                    How to start at the gym as a beginner?
                                </button>
                            </h2>
                            <div id="collapse-Two" class="accordion-collapse collapse" aria-labelledby="heading-Two" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    Lorem ipsum dolor sit amet, consectetur adi piscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading-One">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-One" aria-expanded="true" aria-controls="collapse-One">
                                    How do I find a gym routine for beginners?
                                </button>
                            </h2>
                            <div id="collapse-One" class="accordion-collapse collapse show" aria-labelledby="heading-One" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    Lorem ipsum dolor sit amet, consectetur adi piscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading-Three">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-Three" aria-expanded="false" aria-controls="collapse-Three">
                                    How many hours should a beginner gym?
                                </button>
                            </h2>
                            <div id="collapse-Three" class="accordion-collapse collapse" aria-labelledby="heading-Three" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    Lorem ipsum dolor sit amet, consectetur adi piscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading-Four">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-Four" aria-expanded="false" aria-controls="collapse-Four">
                                    What to do in the gym for the first time?
                                </button>
                            </h2>
                            <div id="collapse-Four" class="accordion-collapse collapse" aria-labelledby="heading-Four" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    Lorem ipsum dolor sit amet, consectetur adi piscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading-Five">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-Five" aria-expanded="false" aria-controls="collapse-Five">
                                    What is the best routine for the gym?
                                </button>
                            </h2>
                            <div id="collapse-Five" class="accordion-collapse collapse" aria-labelledby="heading-Five" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    Lorem ipsum dolor sit amet, consectetur adi piscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Contact Faqs End -->

<!-- Contact Map Start -->
<div class="contact-map">
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d14143117.941545919!2d60.32337114882688!3d30.068124090484673!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x38db52d2f8fd751f%3A0x46b7a1f7e614925c!2sPakistan!5e0!3m2!1sen!2s!4v1655124094484!5m2!1sen!2s" width="600" height="760" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
</div>
<!-- Contact Map End -->
@endsection

@push('scripts')
<script src="{{ asset('assets/js/sweetalert.min.js') }}"></script>
<script src="{{ asset('assets/js/contact.js') }}"></script>
@endpush 