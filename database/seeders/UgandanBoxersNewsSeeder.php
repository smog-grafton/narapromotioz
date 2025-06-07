<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\NewsArticle;
use App\Models\NewsCategory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;

class UgandanBoxersNewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the admin user for authorship
        $adminUser = User::where('email', 'admin@narapromotionz.com')->first() ?? User::first();
        
        // Get or create the boxing category
        $boxingCategory = NewsCategory::firstOrCreate(
            ['slug' => 'boxing'],
            [
                'name' => 'Boxing',
                'description' => 'News and updates about boxing events and boxers',
                'color' => '#dc3545',
                'is_active' => true,
                'sort_order' => 1
            ]
        );

        // Get or create the Uganda category
        $ugandaCategory = NewsCategory::firstOrCreate(
            ['slug' => 'uganda'],
            [
                'name' => 'Uganda',
                'description' => 'News and updates about Uganda',
                'color' => '#ffc107',
                'is_active' => true,
                'sort_order' => 2
            ]
        );

        // Array of Ugandan boxer news articles
        $articles = [
            [
                'title' => 'Rising Star: John Mugabi Jr Makes Professional Debut',
                'content' => '<p>John Mugabi Jr, son of Uganda\'s legendary boxer John "The Beast" Mugabi, has made his professional debut with a stunning first-round knockout victory. The young fighter, who has been training under some of the best coaches in the country, showed exceptional skill and power reminiscent of his father.</p>
                <p>Mugabi Sr, who won a silver medal at the 1980 Olympics and challenged for world titles during his illustrious career, was ringside to witness his son\'s impressive debut. "I\'m very proud of him. He has worked hard and has natural talent. I believe he can go even further than I did," said the proud father.</p>
                <p>The boxing community in Uganda has high hopes for Mugabi Jr, seeing him as a potential future world champion who could bring glory to Ugandan boxing once again.</p>',
                'excerpt' => 'John Mugabi Jr, son of Uganda\'s legendary boxer, makes a spectacular professional debut with a first-round knockout victory, showcasing the potential to follow in his famous father\'s footsteps.',
                'featured_image' => 'news-images/ugandan-boxer-1.jpg',
                'is_featured' => true,
                'published_at' => Carbon::now()->subDays(2)
            ],
            [
                'title' => 'Uganda Boxing Federation Announces New Development Program',
                'content' => '<p>The Uganda Boxing Federation (UBF) has unveiled an ambitious new development program aimed at identifying and nurturing young boxing talent across the country. The initiative, funded by a combination of government support and private sponsorships, will establish training centers in all major regions of Uganda.</p>
                <p>UBF President Moses Muhangi announced the program at a press conference in Kampala, stating: "This program represents a new chapter for Ugandan boxing. We are committed to building on our rich boxing heritage by providing young fighters with the resources, coaching, and opportunities they need to succeed on the international stage."</p>
                <p>The program will include regular national competitions, scholarships for promising boxers, and partnerships with international boxing organizations to provide exposure and experience for Ugandan fighters.</p>',
                'excerpt' => 'The Uganda Boxing Federation launches a comprehensive national development program to discover and nurture young boxing talent, establishing training centers throughout the country.',
                'featured_image' => 'news-images/ugandan-boxer-2.jpg',
                'is_featured' => true,
                'published_at' => Carbon::now()->subDays(4)
            ],
            [
                'title' => 'Kassim Ouma Launches Boxing Academy in Kampala',
                'content' => '<p>Former IBF junior middleweight champion Kassim "The Dream" Ouma has returned to his roots by launching a state-of-the-art boxing academy in Kampala. The academy, named "Ouma\'s Champions," aims to provide professional training to aspiring boxers from underprivileged backgrounds.</p>
                <p>Ouma, who had a remarkable journey from child soldier to world champion, expressed his motivation for starting the academy: "I want to give back to my country and create opportunities I didn\'t have. Boxing saved my life, and I believe it can transform the lives of many young Ugandans who are facing challenges."</p>
                <p>The academy features modern equipment, experienced trainers, and a comprehensive program that addresses not only the technical aspects of boxing but also education, nutrition, and personal development. Several international boxing figures attended the launch, pledging their support for Ouma\'s initiative.</p>',
                'excerpt' => 'Former world champion Kassim "The Dream" Ouma establishes a modern boxing academy in Kampala to train young talent from disadvantaged backgrounds, providing both boxing skills and educational support.',
                'featured_image' => 'news-images/ugandan-boxer-3.jpg',
                'is_featured' => true,
                'published_at' => Carbon::now()->subDays(6)
            ],
            [
                'title' => 'Ugandan Women\'s Boxing Team Secures Olympic Qualification',
                'content' => '<p>In a historic achievement for Ugandan sport, the national women\'s boxing team has secured three qualification spots for the upcoming Olympic Games. The team, led by coach Rebecca Amongin, impressed at the African Olympic Qualifying Tournament with standout performances in multiple weight categories.</p>
                <p>Hellen Baleke (69kg), Doreen Nassali (57kg), and Catherine Nanziri (51kg) all qualified for the Olympics, marking the first time Uganda will send multiple female boxers to the Games. "This is a breakthrough moment for women\'s boxing in Uganda," said Amongin. "These women have overcome tremendous obstacles and social barriers to reach this level."</p>
                <p>The achievement has been celebrated nationwide, with government officials pledging additional support for the team\'s Olympic preparation. The boxers will now enter an intensive training camp, including international sparring opportunities, as they prepare to compete on the world\'s biggest sporting stage.</p>',
                'excerpt' => 'Uganda\'s women\'s boxing team makes history by securing three Olympic qualification spots, highlighting the growing strength of female boxing in the country despite numerous challenges.',
                'featured_image' => 'news-images/ugandan-boxer-4.jpg',
                'is_featured' => true,
                'published_at' => Carbon::now()->subDays(8)
            ],
            [
                'title' => 'Ugandan Boxing Legends Honored in National Sports Hall of Fame',
                'content' => '<p>Uganda\'s rich boxing heritage was celebrated as five boxing legends were inducted into the National Sports Hall of Fame. The ceremony, held at the Serena Hotel in Kampala, honored John "The Beast" Mugabi, Ayub Kalule, Cornelius Boza-Edwards, Kassim Ouma, and Justin Juuko for their outstanding contributions to Ugandan sports.</p>
                <p>The inductees, who collectively held multiple world titles and represented Uganda on the global stage during the 1970s, 80s, and 90s, received commemorative plaques and lifetime achievement awards. President Yoweri Museveni, who attended the ceremony, praised the boxers for raising Uganda\'s flag high and inspiring generations of athletes.</p>
                <p>"These champions emerged during challenging times for our country, yet they persevered and conquered the world," Museveni stated. "Their stories of determination and excellence should be taught to our youth."</p>
                <p>The induction ceremony also featured the announcement of a new boxing museum to be established in Kampala, which will document the history of Ugandan boxing and display memorabilia from the careers of these legendary fighters.</p>',
                'excerpt' => 'Five Ugandan boxing legends are inducted into the National Sports Hall of Fame in recognition of their world-class achievements and contributions to the country\'s sporting legacy.',
                'featured_image' => 'news-images/ugandan-boxer-5.jpg',
                'is_featured' => true,
                'published_at' => Carbon::now()->subDays(10)
            ],
        ];

        foreach ($articles as $articleData) {
            $slug = Str::slug($articleData['title']);
            
            // Check if article already exists
            $exists = NewsArticle::where('slug', $slug)->exists();
            
            if (!$exists) {
                $article = NewsArticle::create([
                    'title' => $articleData['title'],
                    'slug' => $slug,
                    'excerpt' => $articleData['excerpt'],
                    'content' => $articleData['content'],
                    'user_id' => $adminUser->id,
                    'featured_image' => $articleData['featured_image'],
                    'status' => 'published',
                    'published_at' => $articleData['published_at'],
                    'is_featured' => $articleData['is_featured'],
                    'allow_comments' => true,
                    'views_count' => rand(50, 500),
                    'meta_title' => $articleData['title'],
                    'meta_description' => $articleData['excerpt'],
                ]);

                // Attach categories
                $article->categories()->attach([$boxingCategory->id, $ugandaCategory->id]);
            }
        }
    }
}
