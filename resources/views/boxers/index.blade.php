@extends('layouts.app')

@section('title', 'Our Professional Boxers')

@section('content')
  <!-- Banner Style One Start -->
  <section class="banner-style-one">
    <div class="parallax" style="background-image: url({{ asset('assets/images/page-title-bg.jpg') }});"></div>
    <div class="container">
      <div class="row">
        <div class="banner-details">
          <h2>Our Boxers</h2>
          <p>Meet our professional boxing champions and rising stars.</p>
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
              <p>Our Boxers</p>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </section>
  <!-- Banner Style One End -->

  <!-- Boxer Cards Section Start -->
  <section id="our-boxers" class="py-5">
    <div class="container-fluid">
      <div class="row justify-content-center">
        <div class="col-12">
          <h2 class="text-center text-white mb-5">Our Boxers</h2>
        </div>
      </div>
      <div class="row g-3 justify-content-center">
        @forelse ($boxers as $boxer)
          <div class="col-12 col-sm-6 col-md-4 col-lg-3 d-flex justify-content-center">
            <div class="boxer-card-wrapper w-100">
              @include('boxers.card.card', ['boxer' => $boxer])
            </div>
          </div>
        @empty
          <div class="col-12">
            <div class="alert alert-info text-center">
              No boxers found. Check back soon for updates.
            </div>
          </div>
        @endforelse
      </div>
      
      {{-- Pagination --}}
      <div class="row mt-5">
        <div class="col-12 d-flex justify-content-center">
          {{ $boxers->links() }}
        </div>
      </div>
    </div>
  </section> 
  <!-- Boxer Cards Section End -->
@endsection 