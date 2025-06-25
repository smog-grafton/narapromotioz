@props([
    'title' => null,
    'description' => null,
    'keywords' => null,
    'image' => null,
    'type' => 'website',
    'url' => null,
    'publishedTime' => null,
    'modifiedTime' => null,
    'author' => null,
    'videoDuration' => null,
    'videoReleaseDate' => null
])

@php
    // Default values
    $defaultTitle = 'Nara Promotionz - Professional Boxing Promotion & Events';
    $defaultDescription = 'Nara Promotionz is a premier boxing promotion company organizing world-class boxing events, managing professional boxers, and delivering exciting fight entertainment.';
    $defaultKeywords = 'boxing promotion, professional boxing, boxing events, boxing management, fight promotion, boxing news, boxing videos, championship fights';
    $defaultImage = asset('assets/images/logo.png');
    $currentUrl = $url ?: request()->url();
    
    // Use provided values or defaults
    $metaTitle = $title ?: $defaultTitle;
    $metaDescription = $description ?: $defaultDescription;
    $metaKeywords = $keywords ?: $defaultKeywords;
    $metaImage = $image ?: $defaultImage;
@endphp

<!-- Basic Meta Tags -->
<title>{{ $metaTitle }}</title>
<meta name="description" content="{{ $metaDescription }}">
<meta name="keywords" content="{{ $metaKeywords }}">
<meta name="author" content="{{ $author ?: 'Nara Promotionz' }}">
<meta name="robots" content="index, follow">
<link rel="canonical" href="{{ $currentUrl }}">

<!-- Open Graph Meta Tags -->
<meta property="og:title" content="{{ $metaTitle }}">
<meta property="og:description" content="{{ $metaDescription }}">
<meta property="og:type" content="{{ $type }}">
<meta property="og:url" content="{{ $currentUrl }}">
<meta property="og:image" content="{{ $metaImage }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:site_name" content="Nara Promotionz">
<meta property="og:locale" content="en_US">

<!-- Twitter Card Meta Tags -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="@narapromotionz">
<meta name="twitter:creator" content="@narapromotionz">
<meta name="twitter:title" content="{{ $metaTitle }}">
<meta name="twitter:description" content="{{ $metaDescription }}">
<meta name="twitter:image" content="{{ $metaImage }}">

<!-- Article Specific Meta Tags -->
@if($type === 'article' && $publishedTime)
<meta property="article:published_time" content="{{ $publishedTime }}">
@endif

@if($type === 'article' && $modifiedTime)
<meta property="article:modified_time" content="{{ $modifiedTime }}">
@endif

@if($type === 'article' && $author)
<meta property="article:author" content="{{ $author }}">
@endif

@if($type === 'article')
<meta property="article:publisher" content="https://facebook.com/narapromotionz">
<meta property="article:section" content="Boxing">
@endif

<!-- Video Specific Meta Tags -->
@if($type === 'video.other' && $videoDuration)
<meta property="video:duration" content="{{ $videoDuration }}">
@endif

@if($type === 'video.other' && $videoReleaseDate)
<meta property="video:release_date" content="{{ $videoReleaseDate }}">
@endif

<!-- Additional SEO Meta Tags -->
<meta name="theme-color" content="#121212">
<meta name="msapplication-TileColor" content="#121212">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

<!-- Structured Data -->
@if(isset($structuredData))
<script type="application/ld+json">
{!! json_encode($structuredData, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endif