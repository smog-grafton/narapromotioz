@extends('layouts.app')

@section('title', 'Contact Us - ' . config('app.name'))

@section('content')
<!-- Banner Style One Start -->
<section class="banner-style-one">
    <div class="parallax" style="background-image: url({{ asset('assets/images/banner/banner-style-one.jpg') }});"></div>
    <div class="container">
        <div class="row">
            <div class="banner-details">
                <h2>Contact Us</h2>
                <p>Get in touch with Uganda's premier boxing promotion company for professional boxing events and opportunities.</p>
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
        <span>Get in Touch</span>
        <h2>Ready to be Part of the Sweet Science? Contact Nara Promotionz</h2>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-7">
                <div class="data">
                    <p>Whether you're a boxer looking for opportunities, a sponsor interested in partnerships, or a fan with questions about upcoming events, we'd love to hear from you. Fill out our contact form and we'll get back to you as soon as possible.</p>
                    
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    <form class="content-form" id="contact-form" method="post" action="{{ route('contact.store') }}">
                        @csrf
                        <div class="row g-0">
                            <input type="text" name="name" placeholder="Full Name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="row g-0">
                            <input type="email" name="email" placeholder="Email Address" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="row g-0">
                            <input type="tel" name="phone" placeholder="Phone Number" value="{{ old('phone') }}">
                            @error('phone')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="row g-0">
                            <input type="text" name="subject" placeholder="Subject (Optional)" value="{{ old('subject') }}">
                            @error('subject')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="row g-0">
                            <textarea name="message" placeholder="Your Message" required>{{ old('message') }}</textarea>
                            @error('message')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
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
                                <p>Kawaala Road, Kampala, Uganda</p>
                            </div>
                        </li>
                        <li>
                            <i class="flaticon-iphone"></i>
                            <div>
                                <h3>Telephone:</h3>
                                <a href="tel:+256752463322">+256 752 463322 (Airtel)</a>
                                <a href="tel:+256702093354">+256 702 093354</a>
                                <a href="tel:+256785988997">+256 785 988997</a>
                            </div>
                        </li>
                        <li class="pb-0">
                            <i class="flaticon-mail"></i>
                            <div>
                                <h3>Email:</h3>
                                <a href="mailto:info@narapromotionz.com">info@narapromotionz.com</a>
                                <a href="mailto:lubowabh@gmail.com">lubowabh@gmail.com</a>
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
        <span>Frequently asked questions</span>
        <h2>Boxing Promotion FAQs</h2>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="acc2">
                    <div class="accordion" id="accordionExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading-Two">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-Two" aria-expanded="false" aria-controls="collapse-Two">
                                    How can I get featured in a Nara Promotionz event?
                                </button>
                            </h2>
                            <div id="collapse-Two" class="accordion-collapse collapse" aria-labelledby="heading-Two" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    Contact us through this form or our phone numbers with your boxing credentials, fight record, and training background. We scout talent regularly for our Sweet Science boxing series and other professional events.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading-One">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-One" aria-expanded="true" aria-controls="collapse-One">
                                    When is the next Sweet Science boxing event?
                                </button>
                            </h2>
                            <div id="collapse-One" class="accordion-collapse collapse show" aria-labelledby="heading-One" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    We regularly organize Sweet Science events throughout the year. Check our events page for upcoming dates and venues. Follow our social media for the latest announcements and fighter lineups.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading-Three">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-Three" aria-expanded="false" aria-controls="collapse-Three">
                                    Do you organize events outside Uganda?
                                </button>
                            </h2>
                            <div id="collapse-Three" class="accordion-collapse collapse" aria-labelledby="heading-Three" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    Yes, we feature fighters from across East Africa including Kenya and Tanzania. We're actively expanding our reach across the region to promote boxing talent and organize international title fights.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading-Four">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-Four" aria-expanded="false" aria-controls="collapse-Four">
                                    How can I sponsor a Nara Promotionz event?
                                </button>
                            </h2>
                            <div id="collapse-Four" class="accordion-collapse collapse" aria-labelledby="heading-Four" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    We welcome partnerships with businesses and organizations that want to support professional boxing in Uganda. Contact us to discuss sponsorship packages and promotional opportunities for your brand.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading-Five">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-Five" aria-expanded="false" aria-controls="collapse-Five">
                                    What makes Nara Promotionz different from other promoters?
                                </button>
                            </h2>
                            <div id="collapse-Five" class="accordion-collapse collapse" aria-labelledby="heading-Five" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    We focus on professional, high-quality events that elevate the sport of boxing in Uganda. Our Sweet Science series has become a premier platform for fighters to gain recognition and build their careers in the sport.
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
    <iframe src="https://www.google.com/maps/embed?pb=!1m23!1m12!1m3!1d127671.91876425422!2d32.471537924847134!3d0.3414751468581652!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m8!3e6!4m0!4m5!1s0x177dbb4135d30785%3A0xdcc7bd11f3ed62d0!2sKawaala%20Rd%2C%20Kampala!3m2!1d0.3414755!2d32.553939899999996!5e0!3m2!1sen!2sug!4v1750802079967!5m2!1sen!2sug" width="600" height="760" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
</div>
<!-- Contact Map End -->
@endsection

@push('scripts')
<script src="{{ asset('assets/js/sweetalert.min.js') }}"></script>
<script>
// Enhanced contact form handling
jQuery('#contact-form').on('submit', function(e) {
    e.preventDefault();
    
    // Get form data
    var formData = new FormData(this);
    
    // Show loading state
    var submitBtn = $(this).find('button[type="submit"]');
    var originalText = submitBtn.text();
    submitBtn.prop('disabled', true).text('Sending...');
    
    // Submit form via AJAX
    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success) {
                swal("Success!", response.message, "success");
                $('#contact-form')[0].reset();
            } else {
                swal("Error!", response.message || "Something went wrong", "error");
            }
        },
        error: function(xhr) {
            var errors = xhr.responseJSON?.errors;
            var message = xhr.responseJSON?.message || "Something went wrong. Please try again.";
            
            if (errors) {
                var errorList = Object.values(errors).flat().join('\n');
                swal("Validation Error!", errorList, "error");
            } else {
                swal("Error!", message, "error");
            }
        },
        complete: function() {
            // Reset button state
            submitBtn.prop('disabled', false).text(originalText);
        }
    });
});
</script>
@endpush 