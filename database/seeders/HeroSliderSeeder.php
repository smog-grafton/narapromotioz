<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\HeroSlider;

class HeroSliderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sliders = [
            [
                'title' => 'Get your <span>body fitness</span>',
                'subtitle' => 'Achieve your health and fitness goals at your stage',
                'cta_text' => 'Discover Classes',
                'cta_link' => '#',
                'order' => 1,
                'is_active' => true,
                'image_path' => null, // Will use placeholder for now
            ],
            [
                'title' => 'Ultimate <span>Crossfit Facility</span>',
                'subtitle' => 'We dive headfirst into your brand, content, and goals.',
                'cta_text' => 'Discover Classes',
                'cta_link' => '#',
                'order' => 2,
                'is_active' => true,
                'image_path' => null, // Will use placeholder for now
            ],
            [
                'title' => 'Start of Body <span>Transformation</span>',
                'subtitle' => 'We deliver personalized fitness & nutrition',
                'cta_text' => 'Discover Classes',
                'cta_link' => '#',
                'order' => 3,
                'is_active' => true,
                'image_path' => null, // Will use placeholder for now
            ],
            [
                'title' => 'Build Your <span>Dream Physique</span>',
                'subtitle' => 'Professional trainers and state-of-the-art equipment await you',
                'cta_text' => 'Start Today',
                'cta_link' => '#exampleModal',
                'order' => 4,
                'is_active' => true,
                'image_path' => null, // Will use placeholder for now
            ],
        ];

        foreach ($sliders as $slider) {
            HeroSlider::updateOrCreate(
                ['title' => $slider['title']],
                $slider
            );
        }
    }
}
