@php
    $heroSliders = \App\Models\HeroSlider::active()->ordered()->get();
@endphp

@if($heroSliders->isNotEmpty())
<!-- Hero Slider Start -->
<div class="f-slider-three owl-carousel">
    @foreach($heroSliders as $slider)
    <div class="hero-section-three gap item" style="background-image:url({{ $slider->image_url ?? asset('assets/images/silders/herbat-matovu.jpg') }});">
        <div class="container">
            <div class="hero-three">
                <h1>{!! $slider->title ?? 'Get your <span>body fitness</span>' !!}</h1>
                <p>{{ $slider->subtitle ?? 'Achieve your health and fitness goals at your stage' }}</p>
                @if($slider->cta_text || $slider->cta_link)
                    <a href="{{ $slider->cta_link ?? '#' }}" 
                       @if(str_contains($slider->cta_link ?? '', '#modal') || str_contains($slider->cta_link ?? '', '#exampleModal'))
                       data-bs-toggle="modal" data-bs-target="#exampleModal"
                       @endif
                       class="theme-btn">{{ $slider->cta_text ?? 'Discover Classes' }}</a>
                @else
                    <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal" class="theme-btn">Discover Classes</a>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>
<!-- Hero Slider End -->
@else
<!-- Fallback Hero Slider (Original Static Version) -->
<div class="f-slider-three owl-carousel">
    <div class="hero-section-three gap item" style="background-image:url({{ asset('assets/images/silders/herbat-matovu.jpg') }});">
        <div class="container">
            <div class="hero-three">
                <h1>Get your <span>body fitness</span></h1>
                <p>Achieve your health and fitness goals at your stage</p>
                <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal" class="theme-btn">Discover Classes</a>
            </div>
        </div>
    </div>
    <div class="hero-section-three gap item" style="background-image:url({{ asset('assets/images/silders/herbat-matovu.jpg') }});">
        <div class="container">
            <div class="hero-three">
                <h1>Ultimate <span>Crossfit Facility</span></h1>
                <p>We dive headfirst into your brand, content, and goals.</p>
                <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal" class="theme-btn">Discover Classes</a>
            </div>
        </div>
    </div>
    <div class="hero-section-three gap item" style="background-image:url({{ asset('assets/images/silders/herbat-matovu.jpg') }});">
        <div class="container">
            <div class="hero-three">
                <h1>Start of Body<span>Transformation</span></h1>
                <p>We deliver personalized fitness & nutrition</p>
                <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal" class="theme-btn">Discover Classes</a>
            </div>
        </div>
    </div>
</div>
<!-- Fallback Hero Slider End -->
@endif 