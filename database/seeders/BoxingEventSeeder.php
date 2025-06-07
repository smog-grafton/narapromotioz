<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BoxingEvent;
use Carbon\Carbon;
use Illuminate\Support\Str;

class BoxingEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create upcoming events
        $upcomingEvents = [
            [
                'name' => 'Championship Fight Night',
                'tagline' => 'Battle for the Belt',
                'event_date' => Carbon::now()->addDays(30),
                'venue' => 'Madison Square Garden',
                'city' => 'New York',
                'country' => 'USA',
                'status' => 'upcoming',
                'event_type' => 'championship',
                'description' => 'A thrilling night of championship boxing featuring the best fighters in the world.',
                'network' => 'ESPN+',
                'broadcast_type' => 'PPV',
                'tickets_available' => true,
                'is_featured' => true,
                'weight_class' => 'heavyweight',
            ],
            [
                'name' => 'Summer Showdown',
                'tagline' => 'Legends Collide',
                'event_date' => Carbon::now()->addDays(45),
                'venue' => 'T-Mobile Arena',
                'city' => 'Las Vegas',
                'country' => 'USA',
                'status' => 'upcoming',
                'event_type' => 'title_defense',
                'description' => 'The biggest boxing event of the summer featuring multiple title fights.',
                'network' => 'DAZN',
                'broadcast_type' => 'Streaming',
                'tickets_available' => true,
                'is_featured' => true,
                'weight_class' => 'welterweight',
            ],
            [
                'name' => 'International Fight League',
                'tagline' => 'Global Boxing Showcase',
                'event_date' => Carbon::now()->addDays(60),
                'venue' => 'O2 Arena',
                'city' => 'London',
                'country' => 'UK',
                'status' => 'upcoming',
                'event_type' => 'tournament',
                'description' => 'International boxing stars compete in a special tournament format.',
                'network' => 'Sky Sports',
                'broadcast_type' => 'Cable',
                'tickets_available' => true,
                'is_featured' => false,
                'weight_class' => 'middleweight',
            ],
        ];

        // Create past events
        $pastEvents = [
            [
                'name' => 'Spring Knockout',
                'tagline' => 'Champions Rise',
                'event_date' => Carbon::now()->subDays(30),
                'venue' => 'Barclays Center',
                'city' => 'Brooklyn',
                'country' => 'USA',
                'status' => 'completed',
                'event_type' => 'championship',
                'description' => 'An action-packed night of boxing featuring multiple championship bouts.',
                'network' => 'ESPN+',
                'broadcast_type' => 'PPV',
                'tickets_available' => false,
                'is_featured' => false,
                'weight_class' => 'lightweight',
            ],
            [
                'name' => 'Winter Rumble',
                'tagline' => 'Battle of Champions',
                'event_date' => Carbon::now()->subDays(60),
                'venue' => 'American Airlines Center',
                'city' => 'Dallas',
                'country' => 'USA',
                'status' => 'completed',
                'event_type' => 'title_defense',
                'description' => 'Witness history as champions defend their titles against hungry challengers.',
                'network' => 'DAZN',
                'broadcast_type' => 'Streaming',
                'tickets_available' => false,
                'is_featured' => false,
                'weight_class' => 'cruiserweight',
            ],
            [
                'name' => 'New Year Showdown',
                'tagline' => 'New Year, New Champions',
                'event_date' => Carbon::now()->subDays(90),
                'venue' => 'MGM Grand',
                'city' => 'Las Vegas',
                'country' => 'USA',
                'status' => 'completed',
                'event_type' => 'regular',
                'description' => 'Kick off the new year with explosive boxing action.',
                'network' => 'Showtime',
                'broadcast_type' => 'Cable',
                'tickets_available' => false,
                'is_featured' => false,
                'weight_class' => 'super-middleweight',
            ],
        ];

        // Combine all events
        $allEvents = array_merge($upcomingEvents, $pastEvents);

        // Insert events into database
        foreach ($allEvents as $event) {
            // Generate slug from name
            $event['slug'] = Str::slug($event['name']);
            
            // Use default image paths for now
            $event['image_path'] = 'assets/images/events/event' . rand(1, 4) . '.webp';
            
            // Create the event
            BoxingEvent::create($event);
        }
    }
} 