<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\NewsTag;

class NewsTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            ['name' => 'Workout', 'slug' => 'workout', 'description' => 'General workout and exercise content', 'color' => '#007bff'],
            ['name' => 'Gym', 'slug' => 'gym', 'description' => 'Gym-related content and equipment', 'color' => '#28a745'],
            ['name' => 'Training', 'slug' => 'training', 'description' => 'Training programs and techniques', 'color' => '#dc3545'],
            ['name' => 'Diet', 'slug' => 'diet', 'description' => 'Diet plans and nutritional guidance', 'color' => '#ffc107'],
            ['name' => 'Muscle Building', 'slug' => 'muscle-building', 'description' => 'Muscle growth and hypertrophy', 'color' => '#6f42c1'],
            ['name' => 'Fat Loss', 'slug' => 'fat-loss', 'description' => 'Fat burning and weight loss', 'color' => '#fd7e14'],
            ['name' => 'Beginner', 'slug' => 'beginner', 'description' => 'Content for fitness beginners', 'color' => '#20c997'],
            ['name' => 'Advanced', 'slug' => 'advanced', 'description' => 'Advanced fitness techniques', 'color' => '#6c757d'],
            ['name' => 'Equipment', 'slug' => 'equipment', 'description' => 'Gym equipment and tools', 'color' => '#17a2b8'],
            ['name' => 'Home Workout', 'slug' => 'home-workout', 'description' => 'Exercises that can be done at home', 'color' => '#e83e8c'],
            ['name' => 'HIIT', 'slug' => 'hiit', 'description' => 'High-Intensity Interval Training', 'color' => '#fd7e14'],
            ['name' => 'Protein', 'slug' => 'protein', 'description' => 'Protein intake and supplements', 'color' => '#6f42c1'],
            ['name' => 'Recovery', 'slug' => 'recovery', 'description' => 'Rest and recovery techniques', 'color' => '#28a745'],
            ['name' => 'Stretching', 'slug' => 'stretching', 'description' => 'Stretching and flexibility exercises', 'color' => '#ffc107'],
            ['name' => 'Wellness', 'slug' => 'wellness', 'description' => 'Overall health and wellness', 'color' => '#20c997'],
        ];

        foreach ($tags as $tag) {
            NewsTag::updateOrCreate(
                ['slug' => $tag['slug']],
                $tag
            );
        }
    }
}
