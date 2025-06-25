<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BoxingVideo;
use App\Models\Boxer;
use App\Models\BoxingEvent;
use Illuminate\Support\Facades\DB;
use App\Services\SeoService;

class VideoController extends Controller
{
    protected $seoService;
    
    public function __construct(SeoService $seoService)
    {
        $this->seoService = $seoService;
    }

    /**
     * Display the videos index page with filtering and search
     */
    public function index(Request $request)
    {
        $query = BoxingVideo::published()
            ->with(['boxer', 'event', 'boxers', 'events']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhereJsonContains('tags', $search);
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Video type filter
        if ($request->filled('type')) {
            $query->where('video_type', $request->type);
        }

        // Boxer filter
        if ($request->filled('boxer')) {
            $query->where('boxer_id', $request->boxer);
        }

        // Premium filter
        if ($request->filled('premium')) {
            if ($request->premium === 'free') {
                $query->where('is_premium', false);
            } elseif ($request->premium === 'premium') {
                $query->where('is_premium', true);
            }
        }

        // Sorting
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'popular':
                $query->orderBy('views_count', 'desc');
                break;
            case 'liked':
                $query->orderBy('likes_count', 'desc');
                break;
            case 'alphabetical':
                $query->orderBy('title', 'asc');
                break;
            case 'oldest':
                $query->orderBy('published_at', 'asc');
                break;
            default: // latest
                $query->orderBy('published_at', 'desc');
                break;
        }

        $videos = $query->paginate(12)->withQueryString();

        // Get filter options for the UI
        $boxers = Boxer::active()->orderBy('name')->get(['id', 'name', 'slug']);
        $categories = BoxingVideo::select('category')
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category')
            ->filter()
            ->sort();
        
        $videoTypes = BoxingVideo::select('video_type')
            ->whereNotNull('video_type')
            ->distinct()
            ->pluck('video_type')
            ->filter()
            ->sort();

        // Build SEO data based on current filters
        $title = 'Boxing Videos - Nara Promotionz';
        $description = 'Watch exclusive boxing videos from Nara Promotionz. Fight highlights, training footage, interviews, and behind-the-scenes content.';
        $keywords = 'boxing videos, fight highlights, boxing training, boxer interviews, boxing footage, professional boxing';

        if ($request->filled('search')) {
            $title = "Boxing Videos: {$request->search} - Nara Promotionz";
            $description = "Watch boxing videos related to '{$request->search}' from Nara Promotionz.";
            $keywords .= ', ' . $request->search;
        }

        if ($request->filled('category')) {
            $title = "Boxing Videos: {$request->category} - Nara Promotionz";
            $description = "Watch {$request->category} boxing videos from Nara Promotionz.";
            $keywords .= ', ' . $request->category;
        }

        if ($request->filled('boxer')) {
            $boxer = Boxer::find($request->boxer);
            if ($boxer) {
                $title = "Boxing Videos: {$boxer->name} - Nara Promotionz";
                $description = "Watch exclusive boxing videos featuring {$boxer->name} from Nara Promotionz.";
                $keywords .= ', ' . $boxer->name;
            }
        }

        $seoData = [
            'title' => $title,
            'description' => $description,
            'keywords' => $keywords,
            'type' => 'website',
            'url' => route('videos.index', $request->query())
        ];

        // Generate structured data for videos listing
        $structuredData = [
            '@context' => 'https://schema.org',
            '@type' => 'ItemList',
            'name' => 'Boxing Videos',
            'description' => 'Boxing videos from Nara Promotionz',
            'itemListElement' => []
        ];

        foreach ($videos->take(10) as $index => $video) {
            $structuredData['itemListElement'][] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'item' => $this->seoService->generateStructuredData('VideoObject', $video)
            ];
        }

        return view('videos.index', compact('videos', 'boxers', 'categories', 'videoTypes', 'seoData', 'structuredData'));
    }

    /**
     * Display a specific video with related content
     */
    public function show(Request $request, BoxingVideo $video)
    {
        try {
            // Load relationships
            $video->load(['boxer', 'event', 'boxers', 'events']);

            // Increment view count if this is not an AJAX request for loading more videos
            if (!$request->ajax() || !$request->has('load_more')) {
                $video->incrementViews();
            }

            // Handle AJAX request for loading more related videos
            if ($request->ajax() && $request->has('load_more')) {
                $offset = (int) $request->get('offset', 0);
                $relatedVideos = $this->getRelatedVideos($video, $offset, 5);
                
                $html = '';
                foreach ($relatedVideos as $relatedVideo) {
                    $html .= view('videos.partials._related_video_item', compact('relatedVideo'))->render();
                }
                
                return response()->json([
                    'html' => $html,
                    'count' => $relatedVideos->count(),
                    'has_more' => $relatedVideos->count() === 5
                ]);
            }

            // Get related videos for initial load
            $relatedVideos = $this->getRelatedVideos($video, 0, 10);
            
            // Get featured videos for sidebar
            $featuredVideos = BoxingVideo::published()
                ->featured()
                ->where('id', '!=', $video->id)
                ->with(['boxer'])
                ->orderBy('published_at', 'desc')
                ->limit(6)
                ->get();

            // Handle AJAX request for modal display
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'video' => [
                        'id' => $video->id,
                        'title' => $video->title,
                        'description' => $video->description,
                        'embed_code' => $video->embed_code,
                        'video_url' => $video->video_url,
                        'video_id' => $video->video_id,
                        'video_path' => $video->video_path,
                        'source_type' => $video->source_type,
                        'video_type' => $video->video_type,
                        'views_count' => $video->views_count,
                        'likes_count' => $video->likes_count,
                        'published_at' => $video->published_at->format('M j, Y'),
                        'duration' => $video->duration,
                        'is_premium' => $video->is_premium,
                        'tags' => $video->tags ?? [],
                        'thumbnail' => asset($video->getThumbnailPathAttribute()),
                        'boxer' => $video->boxer ? [
                            'id' => $video->boxer->id,
                            'name' => $video->boxer->name,
                            'slug' => $video->boxer->slug,
                            'image' => $video->boxer->image ? asset('storage/' . $video->boxer->image) : null,
                            'nationality' => $video->boxer->nationality,
                            'weight_class' => $video->boxer->weight_class,
                        ] : null,
                        'event' => $video->event ? [
                            'id' => $video->event->id,
                            'title' => $video->event->title,
                            'slug' => $video->event->slug,
                            'event_date' => $video->event->event_date ? $video->event->event_date->format('F j, Y') : null,
                            'venue' => $video->event->venue,
                        ] : null
                    ],
                    'related_videos' => $relatedVideos->map(function ($relatedVideo) {
                        return [
                            'id' => $relatedVideo->id,
                            'slug' => $relatedVideo->slug,
                            'title' => $relatedVideo->title,
                            'thumbnail' => asset($relatedVideo->getThumbnailPathAttribute()),
                            'duration' => $relatedVideo->duration,
                            'views_count' => $relatedVideo->views_count,
                            'published_at' => $relatedVideo->published_at->diffForHumans(),
                            'is_premium' => $relatedVideo->is_premium,
                            'boxer' => $relatedVideo->boxer ? [
                                'name' => $relatedVideo->boxer->name,
                                'slug' => $relatedVideo->boxer->slug
                            ] : null
                        ];
                    })
                ]);
            }

            // Build SEO data for the video
            $seoData = [
                'title' => $video->title . ' - Boxing Video | Nara Promotionz',
                'description' => $video->description ?: "Watch {$video->title} - exclusive boxing content from Nara Promotionz.",
                'keywords' => $this->getVideoKeywords($video),
                'type' => 'video.other',
                'url' => route('videos.show', $video->slug),
                'image' => asset($video->getThumbnailPathAttribute()),
                'video:duration' => $video->duration,
                'video:release_date' => $video->published_at->toISOString(),
                'published_time' => $video->published_at->toISOString(),
                'modified_time' => $video->updated_at->toISOString()
            ];

            // Add boxer-specific SEO data if available
            if ($video->boxer) {
                $seoData['keywords'] .= ', ' . $video->boxer->name . ', ' . $video->boxer->weight_class;
                $seoData['description'] = "Watch {$video->title} featuring {$video->boxer->name}. " . ($video->description ?: "Exclusive boxing content from Nara Promotionz.");
            }

            // Add event-specific SEO data if available
            if ($video->event) {
                $seoData['keywords'] .= ', ' . $video->event->name;
                $seoData['description'] = "Watch {$video->title} from {$video->event->name}. " . ($video->description ?: "Exclusive boxing content from Nara Promotionz.");
            }

            // Generate structured data for the video
            $structuredData = $this->seoService->generateStructuredData('VideoObject', $video);

            return view('videos.show', compact('video', 'relatedVideos', 'featuredVideos', 'seoData', 'structuredData'));
            
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Video not found'
                ], 404);
            }
            
            abort(404);
        }
    }

    /**
     * Handle video like functionality
     */
    public function like(Request $request, BoxingVideo $video)
    {
        try {
            // For now, just increment likes (later you can add user-specific like tracking)
            $video->incrementLikes();
            
            return response()->json([
                'success' => true,
                'likes_count' => $video->fresh()->likes_count,
                'liked' => true // This would be dynamic based on user's like status
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to like video'
            ], 500);
        }
    }

    /**
     * Get related videos based on various criteria
     */
    private function getRelatedVideos(BoxingVideo $video, $offset = 0, $limit = 10)
    {
        $relatedQuery = BoxingVideo::published()
            ->where('id', '!=', $video->id)
            ->with(['boxer']);

        // Build a score-based query for better related video matching
        $relatedQuery->select('*')
            ->selectRaw('
                CASE
                    WHEN boxer_id = ? THEN 3
                    WHEN category = ? THEN 2
                    WHEN video_type = ? THEN 1
                    ELSE 0
                END as relevance_score
            ', [$video->boxer_id, $video->category, $video->video_type])
            ->orderBy('relevance_score', 'desc')
            ->orderBy('published_at', 'desc');

        // Add tag matching if video has tags
        if (!empty($video->tags)) {
            $tags = $this->getVideoTagsAsArray($video);
            if (!empty($tags)) {
                $relatedQuery->orWhere(function ($query) use ($tags) {
                    foreach ($tags as $tag) {
                        $query->orWhereJsonContains('tags', $tag);
                    }
                });
            }
        }

        return $relatedQuery->offset($offset)->limit($limit)->get();
    }

    /**
     * Get video keywords safely handling different tag formats
     */
    private function getVideoKeywords(BoxingVideo $video)
    {
        $keywords = 'boxing video, fight footage, boxing content';
        
        // Safely handle tags field
        if (!empty($video->tags)) {
            if (is_array($video->tags)) {
                // Tags is already an array
                $keywords = implode(', ', $video->tags) . ', ' . $keywords;
            } elseif (is_string($video->tags)) {
                // Tags is a string, try to parse as JSON first
                $tagsArray = json_decode($video->tags, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($tagsArray)) {
                    $keywords = implode(', ', $tagsArray) . ', ' . $keywords;
                } else {
                    // Treat as comma-separated string
                    $keywords = $video->tags . ', ' . $keywords;
                }
            }
        }
        
        return $keywords;
    }

    /**
     * Get video tags as array safely handling different formats
     */
    private function getVideoTagsAsArray(BoxingVideo $video)
    {
        if (empty($video->tags)) {
            return [];
        }
        
        if (is_array($video->tags)) {
            return $video->tags;
        } 
        
        if (is_string($video->tags)) {
            // Try to decode as JSON first
            $tagsArray = json_decode($video->tags, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($tagsArray)) {
                return $tagsArray;
            }
            
            // If not JSON, treat as comma-separated string
            return array_map('trim', explode(',', $video->tags));
        }
        
        return [];
    }
} 