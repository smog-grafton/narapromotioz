<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    echo "Testing VideoController index method simulation...\n";
    
    // Simulate the controller logic
    $query = App\Models\BoxingVideo::published()->with(['boxer', 'event', 'boxers', 'events']);
    
    // Apply default sorting
    $query->orderBy('published_at', 'desc');
    
    // Paginate
    $videos = $query->paginate(12);
    echo "Videos paginated successfully: " . $videos->count() . " items\n";
    
    // Get boxers
    $boxers = App\Models\Boxer::orderBy('name')->get();
    echo "Boxers loaded: " . $boxers->count() . " items\n";
    
    // Get categories
    $categories = App\Models\BoxingVideo::select('category')
        ->whereNotNull('category')
        ->distinct()
        ->pluck('category')
        ->filter()
        ->sort();
    echo "Categories loaded: " . $categories->count() . " items\n";
    
    // Get video types
    $videoTypes = App\Models\BoxingVideo::select('video_type')
        ->whereNotNull('video_type')
        ->distinct()
        ->pluck('video_type')
        ->filter()
        ->sort();
    echo "Video types loaded: " . $videoTypes->count() . " items\n";
    
    echo "All controller data loaded successfully!\n";
    
    // Test a video's getThumbnailPathAttribute method
    $video = $videos->first();
    if ($video) {
        echo "Testing thumbnail path for video: " . $video->title . "\n";
        echo "Thumbnail: " . $video->getThumbnailPathAttribute() . "\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
} 