@extends('layouts.app')

@section('title', 'Unsubscribed - Nara Promotionz')

@section('content')
<!-- Page Title Section Start -->
<section class="page-title-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-title-content">
                    <h1>Newsletter Unsubscribed</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Newsletter Unsubscribed</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Page Title Section End -->

<!-- Unsubscribe Confirmation Start -->
<section class="gap">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="text-center">
                    <div class="mb-4">
                        <i class="fa-solid fa-envelope-circle-check" style="font-size: 4rem; color: #28a745;"></i>
                    </div>
                    
                    <h2 class="mb-4">You've Been Unsubscribed</h2>
                    
                    <p class="mb-4">
                        Sorry to see you go! <strong>{{ $subscription->email }}</strong> has been successfully removed from our newsletter.
                    </p>
                    
                    <p class="mb-4">
                        You will no longer receive boxing news, event updates, and promotional emails from Nara Promotionz.
                    </p>
                    
                    <div class="mb-4">
                        <p class="text-muted">
                            Changed your mind? You can always subscribe again by visiting our website or filling out the newsletter form in our footer.
                        </p>
                    </div>
                    
                    <div class="d-flex justify-content-center gap-3 flex-wrap">
                        <a href="{{ route('home') }}" class="theme-btn">
                            <i class="fa-solid fa-home me-2"></i>Back to Home
                        </a>
                        <a href="{{ route('news.index') }}" class="theme-btn theme-btn-outline">
                            <i class="fa-solid fa-newspaper me-2"></i>Latest Boxing News
                        </a>
                        <a href="{{ route('events.index') }}" class="theme-btn theme-btn-outline">
                            <i class="fa-solid fa-calendar me-2"></i>Upcoming Events
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Unsubscribe Confirmation End -->

<style>
.theme-btn-outline {
    background: transparent;
    border: 2px solid var(--theme-color);
    color: var(--theme-color);
}

.theme-btn-outline:hover {
    background: var(--theme-color);
    color: #fff;
}

.gap-3 {
    gap: 1rem;
}
</style>
@endsection 