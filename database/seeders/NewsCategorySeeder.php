<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\NewsCategory;

class NewsCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Fitness Tips',
                'slug' => 'fitness-tips',
                'description' => 'Expert advice and tips for effective workouts and fitness routines',
                'color' => '#FF6B6B',
                'icon' => 'fas fa-dumbbell',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Nutrition',
                'slug' => 'nutrition',
                'description' => 'Healthy eating guides, meal plans, and nutritional advice',
                'color' => '#4ECDC4',
                'icon' => 'fas fa-apple-alt',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Strength Training',
                'slug' => 'strength-training',
                'description' => 'Weight lifting techniques, muscle building, and strength programs',
                'color' => '#45B7D1',
                'icon' => 'fas fa-weight-hanging',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Cardio Workouts',
                'slug' => 'cardio-workouts',
                'description' => 'Cardiovascular exercises and endurance training',
                'color' => '#96CEB4',
                'icon' => 'fas fa-running',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Yoga & Flexibility',
                'slug' => 'yoga-flexibility',
                'description' => 'Yoga poses, stretching routines, and flexibility improvement',
                'color' => '#FFEAA7',
                'icon' => 'fas fa-peace',
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Weight Loss',
                'slug' => 'weight-loss',
                'description' => 'Effective strategies and tips for healthy weight management',
                'color' => '#DDA0DD',
                'icon' => 'fas fa-chart-line',
                'is_active' => true,
                'sort_order' => 6,
            ],
            [
                'name' => 'Supplements',
                'slug' => 'supplements',
                'description' => 'Information about fitness supplements and nutritional products',
                'color' => '#74B9FF',
                'icon' => 'fas fa-pills',
                'is_active' => true,
                'sort_order' => 7,
            ],
            [
                'name' => 'Motivation',
                'slug' => 'motivation',
                'description' => 'Inspirational stories and motivational content for fitness journey',
                'color' => '#FD79A8',
                'icon' => 'fas fa-fire',
                'is_active' => true,
                'sort_order' => 8,
            ],
        ];

        foreach ($categories as $category) {
            NewsCategory::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
