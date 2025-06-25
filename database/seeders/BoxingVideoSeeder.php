<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BoxingVideo;
use Carbon\Carbon;
use Illuminate\Support\Str;

class BoxingVideoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $videos = [
            [
                'title' => 'Championship Fight Highlights',
                'description' => 'Highlights from the epic championship bout that had fans on their feet.',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'thumbnail' => 'assets/images/videos/video1.webp',
                'duration' => '12:34',
                'video_type' => 'highlights',
                'is_premium' => false,
                'is_featured' => true,
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(5),
                'views_count' => 2541,
                'tags' => ['highlights', 'championship', 'knockout'],
            ],
            [
                'title' => 'Pre-Fight Interview - Main Event',
                'description' => 'Exclusive interview with both fighters before their highly anticipated showdown.',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'thumbnail' => 'assets/images/videos/video2.webp',
                'duration' => '08:45',
                'video_type' => 'interview',
                'is_premium' => false,
                'is_featured' => true,
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(7),
                'views_count' => 1876,
                'tags' => ['interview', 'pre-fight'],
            ],
            [
                'title' => 'Training Camp: Behind the Scenes',
                'description' => 'Exclusive access to the champion\'s training camp as they prepare for their title defense.',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'thumbnail' => 'assets/images/videos/video3.webp',
                'duration' => '15:20',
                'video_type' => 'documentary',
                'is_premium' => true,
                'is_featured' => true,
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(10),
                'views_count' => 942,
                'tags' => ['training', 'documentary', 'behind the scenes'],
            ],
            [
                'title' => 'Knockout of the Year Contender',
                'description' => 'The spectacular knockout that has everyone talking - a strong contender for KO of the year.',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'thumbnail' => 'assets/images/videos/video4.webp',
                'duration' => '02:15',
                'video_type' => 'highlights',
                'is_premium' => false,
                'is_featured' => true,
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(15),
                'views_count' => 5284,
                'tags' => ['knockout', 'highlights'],
            ],
            [
                'title' => 'Post-Fight Press Conference',
                'description' => 'Complete post-fight press conference with fighters and promoters discussing the event.',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'thumbnail' => 'assets/images/videos/video5.webp',
                'duration' => '42:18',
                'video_type' => 'press conference',
                'is_premium' => true,
                'is_featured' => true,
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(6),
                'views_count' => 1209,
                'tags' => ['press conference', 'post-fight'],
            ],
            [
                'title' => 'Fight Analysis with Boxing Experts',
                'description' => 'In-depth technical breakdown of the championship fight with expert commentary.',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'thumbnail' => 'assets/images/videos/video6.webp',
                'duration' => '18:45',
                'video_type' => 'analysis',
                'is_premium' => true,
                'is_featured' => true,
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(4),
                'views_count' => 873,
                'tags' => ['analysis', 'expert', 'technical'],
            ],
            [
                'title' => 'Rising Star Profile: Future Champion',
                'description' => 'Profile of the exciting prospect who\'s making waves in the boxing world.',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'thumbnail' => 'assets/images/videos/video7.webp',
                'duration' => '10:30',
                'video_type' => 'profile',
                'is_premium' => false,
                'is_featured' => true,
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(12),
                'views_count' => 1456,
                'tags' => ['profile', 'prospect', 'rising star'],
            ],
            [
                'title' => 'Historical Fight: Title Unification Classic',
                'description' => 'Relive one of boxing\'s most memorable unification bouts from the archives.',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'thumbnail' => 'assets/images/videos/video8.webp',
                'duration' => '28:15',
                'video_type' => 'full fight',
                'is_premium' => true,
                'is_featured' => true,
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(20),
                'views_count' => 2187,
                'tags' => ['classic', 'historical', 'unification'],
            ],
        ];

        foreach ($videos as $video) {
            // Generate slug from title
            $video['slug'] = Str::slug($video['title']);
            
            // Convert tags to JSON
            $video['tags'] = json_encode($video['tags']);
            
            // Create the video
            BoxingVideo::create($video);
        }
    }
} 