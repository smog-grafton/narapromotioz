@props(['title', 'subtitle' => '', 'backgroundImage' => 'auth_banner.jpg', 'breadcrumbs' => []])

<!-- Banner Style One Start -->
<section class="banner-style-one">
    <div class="parallax" style="background-image: url({{ asset('assets/images/banner/' . $backgroundImage) }});"></div>
    <div class="container">
        <div class="row">
            <div class="banner-details">
                <h2>{{ $title }}</h2>
                @if($subtitle)
                    <p>{{ $subtitle }}</p>
                @endif
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
                    @foreach($breadcrumbs as $breadcrumb)
                        @if($loop->last)
                            <li class="current">
                                <p>{{ $breadcrumb['title'] }}</p>
                            </li>
                        @else
                            <li>
                                <a href="{{ $breadcrumb['url'] }}">
                                    <p>{{ $breadcrumb['title'] }}</p>
                                </a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</section>
<!-- Banner Style One End --> 