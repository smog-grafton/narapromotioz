<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\NewsArticle;
use App\Models\NewsCategory;
use App\Models\NewsTag;
use Carbon\Carbon;

class NewsArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $articles = [
            [
                'title' => 'What mistakes you are making while muscle building',
                'slug' => 'what-mistakes-you-are-making-while-muscle-building',
                'excerpt' => 'Discover the common mistakes that prevent muscle growth and learn how to avoid them for better results.',
                'content' => '<p>Building muscle is a complex process that requires dedication, proper nutrition, and smart training. However, many fitness enthusiasts make critical mistakes that hinder their progress. Here are the most common muscle-building mistakes and how to avoid them.</p>

<h3>1. Not Eating Enough Protein</h3>
<p>Protein is the building block of muscle tissue. Without adequate protein intake, your muscles cannot repair and grow effectively. Aim for 1.6-2.2 grams of protein per kilogram of body weight daily.</p>

<h3>2. Inconsistent Training</h3>
<p>Muscle growth requires progressive overload and consistency. Skipping workouts or constantly changing your routine prevents your muscles from adapting and growing.</p>

<h3>3. Ignoring Compound Movements</h3>
<p>Isolation exercises have their place, but compound movements like squats, deadlifts, and bench presses should form the foundation of your routine.</p>

<h3>4. Not Getting Enough Sleep</h3>
<p>Sleep is when your body recovers and builds muscle. Aim for 7-9 hours of quality sleep each night to maximize your gains.</p>

<h3>5. Rushing the Process</h3>
<p>Muscle building takes time. Be patient and trust the process. Results typically become noticeable after 6-8 weeks of consistent training and proper nutrition.</p>',
                'featured_image' => null,
                'author_name' => 'Jakki James',
                'author_email' => 'jakki@example.com',
                'author_image' => null,
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(5),
                'is_featured' => true,
                'allow_comments' => true,
                'views_count' => 1250,
                'meta_title' => 'Common Muscle Building Mistakes to Avoid',
                'meta_description' => 'Learn about the most common muscle building mistakes that prevent progress and discover how to avoid them for better results.',
                'meta_keywords' => 'muscle building, fitness mistakes, bodybuilding, strength training',
                'categories' => ['Fitness Tips', 'Strength Training'],
                'tags' => ['Muscle Building', 'Training', 'Beginner'],
            ],
            [
                'title' => 'HOW A GOOD PERSONAL TRAINER CAN CHANGE THE WAY OF YOUR LIFE',
                'slug' => 'how-a-good-personal-trainer-can-change-your-life',
                'excerpt' => 'Discover how working with a qualified personal trainer can transform not just your body, but your entire lifestyle.',
                'content' => '<p>A skilled personal trainer is more than just someone who counts your reps. They are a catalyst for transformation, helping you unlock your potential and achieve goals you never thought possible.</p>

<h3>Personalized Approach</h3>
<p>Every body is different, and a good trainer understands this. They create customized workout plans based on your fitness level, goals, and any physical limitations you may have.</p>

<h3>Accountability and Motivation</h3>
<p>Having someone invested in your success makes all the difference. Personal trainers provide the accountability you need to stay consistent and the motivation to push through challenges.</p>

<h3>Proper Form and Injury Prevention</h3>
<p>Learning correct exercise form is crucial for both effectiveness and safety. A trainer ensures you perform exercises correctly, reducing injury risk and maximizing results.</p>

<h3>Nutritional Guidance</h3>
<p>Many trainers also provide nutritional advice, helping you understand how to fuel your body properly for your fitness goals.</p>

<h3>Long-term Lifestyle Changes</h3>
<p>The best trainers teach you to develop healthy habits that extend beyond the gym, creating lasting lifestyle changes that improve your overall quality of life.</p>',
                'featured_image' => null,
                'author_name' => 'Mike Johnson',
                'author_email' => 'mike@example.com',
                'author_image' => null,
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(3),
                'is_featured' => false,
                'allow_comments' => true,
                'views_count' => 890,
                'meta_title' => 'How a Personal Trainer Can Transform Your Life',
                'meta_description' => 'Learn how working with a qualified personal trainer can change not just your fitness but your entire lifestyle.',
                'meta_keywords' => 'personal trainer, fitness transformation, motivation, lifestyle change',
                'categories' => ['Motivation', 'Fitness Tips'],
                'tags' => ['Training', 'Motivation', 'Beginner'],
            ],
            [
                'title' => 'How To Make Cool Physique in Gym in 3 Months',
                'slug' => 'how-to-make-cool-physique-in-gym-in-3-months',
                'excerpt' => 'A comprehensive 3-month transformation guide to building an impressive physique through strategic training and nutrition.',
                'content' => '<p>Building an impressive physique in 3 months is challenging but achievable with the right approach. This guide outlines a strategic plan to maximize your transformation.</p>

<h3>Month 1: Foundation Building</h3>
<p>Focus on learning proper form and establishing consistency. Start with basic compound movements and gradually increase intensity.</p>
<ul>
<li>Full-body workouts 3 times per week</li>
<li>Focus on form over weight</li>
<li>Establish a consistent eating pattern</li>
<li>Track your progress with photos and measurements</li>
</ul>

<h3>Month 2: Intensity Increase</h3>
<p>Add more volume and introduce specialized techniques to accelerate progress.</p>
<ul>
<li>Transition to upper/lower split routine</li>
<li>Increase training frequency to 4-5 days</li>
<li>Fine-tune your nutrition for body composition goals</li>
<li>Add cardio for fat loss if needed</li>
</ul>

<h3>Month 3: Peak Performance</h3>
<p>Push your limits and make final adjustments for maximum results.</p>
<ul>
<li>Implement advanced training techniques</li>
<li>Optimize recovery and sleep</li>
<li>Consider a mini cut if fat loss is needed</li>
<li>Prepare for long-term maintenance</li>
</ul>

<h3>Nutrition Essentials</h3>
<p>Your diet is crucial for transformation. Focus on whole foods, adequate protein, and proper meal timing.</p>',
                'featured_image' => null,
                'author_name' => 'Sarah Williams',
                'author_email' => 'sarah@example.com',
                'author_image' => null,
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(7),
                'is_featured' => true,
                'allow_comments' => true,
                'views_count' => 2100,
                'meta_title' => '3-Month Physique Transformation Guide',
                'meta_description' => 'Complete guide to building an impressive physique in just 3 months with strategic training and nutrition.',
                'meta_keywords' => 'physique transformation, 3 month plan, body transformation, fitness goals',
                'categories' => ['Strength Training', 'Fitness Tips'],
                'tags' => ['Muscle Building', 'Training', 'Advanced', 'Workout'],
            ],
            [
                'title' => 'How Gym Cycling Can Help to Have Good Metabolism',
                'slug' => 'how-gym-cycling-can-help-good-metabolism',
                'excerpt' => 'Explore the metabolic benefits of cycling and how it can boost your overall health and fitness.',
                'content' => '<p>Cycling is one of the most effective forms of cardiovascular exercise for boosting metabolism and improving overall health. Here\'s how gym cycling can transform your metabolic health.</p>

<h3>Metabolic Benefits of Cycling</h3>
<p>Regular cycling sessions increase your metabolic rate both during and after exercise, leading to improved calorie burning throughout the day.</p>

<h3>HIIT Cycling for Maximum Impact</h3>
<p>High-Intensity Interval Training on a stationary bike can significantly boost your metabolism for hours after your workout.</p>

<h3>Building Lean Muscle</h3>
<p>Cycling engages multiple muscle groups, particularly in the lower body, helping to build lean muscle mass that burns more calories at rest.</p>

<h3>Improved Insulin Sensitivity</h3>
<p>Regular cycling improves your body\'s ability to process glucose, leading to better metabolic health and reduced diabetes risk.</p>

<h3>Sample Cycling Workouts</h3>
<p>Try these effective cycling workouts to boost your metabolism:</p>
<ul>
<li>30-second sprints with 90-second recovery (repeat 8-10 times)</li>
<li>Steady-state cycling for 45-60 minutes at moderate intensity</li>
<li>Pyramid intervals: 1, 2, 3, 4, 3, 2, 1 minute hard efforts</li>
</ul>',
                'featured_image' => null,
                'author_name' => 'David Lee',
                'author_email' => 'david@example.com',
                'author_image' => null,
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(10),
                'is_featured' => false,
                'allow_comments' => true,
                'views_count' => 750,
                'meta_title' => 'Cycling for Better Metabolism - Complete Guide',
                'meta_description' => 'Learn how gym cycling can boost your metabolism and improve your overall health with effective workout strategies.',
                'meta_keywords' => 'cycling, metabolism, cardio, HIIT, fat burning',
                'categories' => ['Cardio Workouts', 'Weight Loss'],
                'tags' => ['HIIT', 'Fat Loss', 'Equipment', 'Workout'],
            ],
            [
                'title' => 'Nutrition Guide: Building Muscle and Losing Fat',
                'slug' => 'nutrition-guide-building-muscle-losing-fat',
                'excerpt' => 'Master the art of body recomposition with this comprehensive nutrition guide for simultaneous muscle gain and fat loss.',
                'content' => '<p>Body recomposition - building muscle while losing fat - is one of the most challenging yet rewarding fitness goals. Success requires a strategic approach to nutrition.</p>

<h3>The Science of Body Recomposition</h3>
<p>While traditional wisdom suggests you can\'t build muscle and lose fat simultaneously, research shows it\'s possible with the right approach, especially for beginners and those returning to training.</p>

<h3>Protein: The Foundation</h3>
<p>Aim for 1.6-2.2g of protein per kg of body weight. High protein intake supports muscle growth while increasing satiety and metabolic rate.</p>

<h3>Strategic Carbohydrate Timing</h3>
<p>Time your carbohydrates around workouts to fuel performance while maintaining a slight caloric deficit for fat loss.</p>

<h3>Healthy Fats for Hormonal Health</h3>
<p>Include 20-30% of calories from healthy fats to support hormone production and vitamin absorption.</p>

<h3>Meal Timing and Frequency</h3>
<p>While total calories matter most, strategic meal timing can optimize your results:</p>
<ul>
<li>Pre-workout: Moderate carbs and protein</li>
<li>Post-workout: High protein with fast-digesting carbs</li>
<li>Before bed: Casein protein for overnight muscle recovery</li>
</ul>

<h3>Supplementation</h3>
<p>Consider these evidence-based supplements:</p>
<ul>
<li>Creatine monohydrate for strength and muscle gain</li>
<li>Whey protein for convenience</li>
<li>Caffeine for performance enhancement</li>
</ul>',
                'featured_image' => null,
                'author_name' => 'Dr. Emily Chen',
                'author_email' => 'emily@example.com',
                'author_image' => null,
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(2),
                'is_featured' => true,
                'allow_comments' => true,
                'views_count' => 1680,
                'meta_title' => 'Complete Nutrition Guide for Body Recomposition',
                'meta_description' => 'Learn how to build muscle and lose fat simultaneously with this comprehensive nutrition guide.',
                'meta_keywords' => 'nutrition, body recomposition, muscle building, fat loss, diet',
                'categories' => ['Nutrition', 'Weight Loss'],
                'tags' => ['Diet', 'Muscle Building', 'Fat Loss', 'Protein'],
            ],
        ];

        foreach ($articles as $articleData) {
            $categories = $articleData['categories'];
            $tags = $articleData['tags'];
            unset($articleData['categories'], $articleData['tags']);

            $article = NewsArticle::updateOrCreate(
                ['slug' => $articleData['slug']],
                $articleData
            );

            // Detach existing relationships to avoid duplicates
            $article->categories()->detach();
            $article->tags()->detach();

            // Attach categories
            foreach ($categories as $categoryName) {
                $category = NewsCategory::where('name', $categoryName)->first();
                if ($category) {
                    $article->categories()->attach($category->id);
                }
            }

            // Attach tags
            foreach ($tags as $tagName) {
                $tag = NewsTag::where('name', $tagName)->first();
                if ($tag) {
                    $article->tags()->attach($tag->id);
                }
            }
        }
    }
}
