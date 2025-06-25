<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BoxingVideo;
use App\Models\Boxer;
use App\Models\BoxingEvent;
use Carbon\Carbon;

class VideoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some boxers for relationships
        $boxers = Boxer::all();
        $events = BoxingEvent::all();

        $videos = [
            [
                'title' => 'Mike Tyson vs Roy Jones Jr - Full Fight Highlights',
                'slug' => 'mike-tyson-vs-roy-jones-jr-full-fight-highlights',
                'description' => 'Watch the complete highlights from the legendary exhibition fight between Mike Tyson and Roy Jones Jr. An unforgettable night of boxing featuring two heavyweight legends.',
                'video_type' => 'highlight',
                'category' => 'heavyweight',
                'status' => 'published',
                'duration' => '15:32',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'source_type' => 'youtube',
                'thumbnail' => 'videos/thumbnails/tyson-jones-highlight.jpg',
                'featured' => true,
                'premium' => false,
                'views_count' => 125000,
                'likes_count' => 8500,
                'publish_date' => Carbon::now()->subDays(7),
                'tags' => ['heavyweight', 'exhibition', 'legends', 'tyson', 'jones'],
                'metadata' => [
                    'quality' => '1080p',
                    'language' => 'English',
                    'country' => 'USA'
                ]
            ],
            [
                'title' => 'Training Session - Speed Bag Techniques',
                'slug' => 'training-session-speed-bag-techniques',
                'description' => 'Learn essential speed bag techniques used by professional boxers. Master the rhythm and timing required for effective speed bag training.',
                'video_type' => 'training',
                'category' => 'technique',
                'status' => 'published',
                'duration' => '8:45',
                'video_url' => 'https://www.youtube.com/watch?v=abc123def456',
                'source_type' => 'youtube',
                'thumbnail' => 'videos/thumbnails/speed-bag-training.jpg',
                'featured' => false,
                'premium' => true,
                'views_count' => 45000,
                'likes_count' => 2800,
                'publish_date' => Carbon::now()->subDays(3),
                'tags' => ['training', 'speed-bag', 'technique', 'workout'],
                'metadata' => [
                    'quality' => '720p',
                    'language' => 'English',
                    'difficulty' => 'Intermediate'
                ]
            ],
            [
                'title' => 'Boxing Documentary - Rise of Champions',
                'slug' => 'boxing-documentary-rise-of-champions',
                'description' => 'A comprehensive documentary exploring the journey of boxing champions from amateur to professional levels. Featuring interviews with legendary fighters and trainers.',
                'video_type' => 'documentary',
                'category' => 'general',
                'status' => 'published',
                'duration' => '45:12',
                'video_url' => 'https://vimeo.com/123456789',
                'source_type' => 'vimeo',
                'thumbnail' => 'videos/thumbnails/rise-of-champions.jpg',
                'featured' => true,
                'premium' => true,
                'views_count' => 78000,
                'likes_count' => 5200,
                'publish_date' => Carbon::now()->subDays(14),
                'tags' => ['documentary', 'champions', 'history', 'interviews'],
                'metadata' => [
                    'quality' => '1080p',
                    'language' => 'English',
                    'runtime' => '45 minutes'
                ]
            ],
            [
                'title' => 'Basic Boxing Footwork for Beginners',
                'slug' => 'basic-boxing-footwork-for-beginners',
                'description' => 'Learn fundamental boxing footwork patterns that every beginner should master. Step-by-step instructions for proper stance and movement.',
                'video_type' => 'tutorial',
                'category' => 'beginner',
                'status' => 'published',
                'duration' => '12:30',
                'video_url' => 'https://www.youtube.com/watch?v=footwork123',
                'source_type' => 'youtube',
                'thumbnail' => 'videos/thumbnails/footwork-basics.jpg',
                'featured' => false,
                'premium' => false,
                'views_count' => 89000,
                'likes_count' => 6700,
                'publish_date' => Carbon::now()->subDays(10),
                'tags' => ['beginner', 'footwork', 'tutorial', 'basics'],
                'metadata' => [
                    'quality' => '720p',
                    'language' => 'English',
                    'level' => 'Beginner'
                ]
            ],
            [
                'title' => 'World Championship Final Round',
                'slug' => 'world-championship-final-round',
                'description' => 'The dramatic final round of the World Boxing Championship. Witness the climactic moments that decided the champion.',
                'video_type' => 'fight',
                'category' => 'championship',
                'status' => 'published',
                'duration' => '3:00',
                'video_url' => 'https://www.youtube.com/watch?v=championship123',
                'source_type' => 'youtube',
                'thumbnail' => 'videos/thumbnails/championship-final.jpg',
                'featured' => true,
                'premium' => false,
                'views_count' => 200000,
                'likes_count' => 15000,
                'publish_date' => Carbon::now()->subDays(2),
                'tags' => ['championship', 'final', 'dramatic', 'victory'],
                'metadata' => [
                    'quality' => '1080p',
                    'language' => 'English',
                    'event' => 'World Championship 2024'
                ]
            ],
            [
                'title' => 'Heavy Bag Workout Routine',
                'slug' => 'heavy-bag-workout-routine',
                'description' => 'Complete heavy bag workout routine for building strength and technique. Perfect for intermediate to advanced boxers.',
                'video_type' => 'workout',
                'category' => 'training',
                'status' => 'published',
                'duration' => '25:15',
                'video_url' => 'https://www.youtube.com/watch?v=heavybag456',
                'source_type' => 'youtube',
                'thumbnail' => 'videos/thumbnails/heavy-bag-workout.jpg',
                'featured' => false,
                'premium' => true,
                'views_count' => 67000,
                'likes_count' => 4200,
                'publish_date' => Carbon::now()->subDays(5),
                'tags' => ['workout', 'heavy-bag', 'strength', 'training'],
                'metadata' => [
                    'quality' => '1080p',
                    'language' => 'English',
                    'duration' => '25 minutes'
                ]
            ]
        ];

        foreach ($videos as $videoData) {
            // Assign random boxer and event if available
            if ($boxers->count() > 0) {
                $videoData['boxer_id'] = $boxers->random()->id;
            }
            
            if ($events->count() > 0 && in_array($videoData['video_type'], ['fight', 'highlight'])) {
                $videoData['event_id'] = $events->random()->id;
            }

            BoxingVideo::create($videoData);
        }
    }
}
