<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\NewsComment;
use App\Models\NewsArticle;
use Carbon\Carbon;

class NewsCommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $comments = [
            [
                'article_slug' => 'what-mistakes-you-are-making-while-muscle-building',
                'comments' => [
                    [
                        'name' => 'John Smith',
                        'email' => 'john@example.com',
                        'comment' => 'Great article! I\'ve been making mistake #2 for months. Time to get consistent with my training.',
                        'status' => 'approved',
                        'created_at' => Carbon::now()->subDays(4),
                        'replies' => [
                            [
                                'name' => 'Jakki James',
                                'email' => 'jakki@example.com',
                                'comment' => 'Thanks John! Consistency is definitely key. Keep at it and you\'ll see great results.',
                                'status' => 'approved',
                                'created_at' => Carbon::now()->subDays(3),
                            ]
                        ]
                    ],
                    [
                        'name' => 'Maria Garcia',
                        'email' => 'maria@example.com',
                        'comment' => 'I had no idea about the protein requirements. I was only eating about half of what I should be!',
                        'status' => 'approved',
                        'created_at' => Carbon::now()->subDays(3),
                        'replies' => []
                    ],
                    [
                        'name' => 'Alex Johnson',
                        'email' => 'alex@example.com',
                        'comment' => 'The sleep point is so underrated. I started getting 8 hours and my recovery improved dramatically.',
                        'status' => 'approved',
                        'created_at' => Carbon::now()->subDays(2),
                        'replies' => []
                    ]
                ]
            ],
            [
                'article_slug' => 'how-a-good-personal-trainer-can-change-your-life',
                'comments' => [
                    [
                        'name' => 'Lisa Brown',
                        'email' => 'lisa@example.com',
                        'comment' => 'I was skeptical about hiring a trainer, but after reading this, I think it\'s time to invest in one.',
                        'status' => 'approved',
                        'created_at' => Carbon::now()->subDays(2),
                        'replies' => [
                            [
                                'name' => 'Mike Johnson',
                                'email' => 'mike@example.com',
                                'comment' => 'It\'s definitely an investment worth making! Look for certified trainers with good reviews.',
                                'status' => 'approved',
                                'created_at' => Carbon::now()->subDays(1),
                            ]
                        ]
                    ],
                    [
                        'name' => 'Robert Wilson',
                        'email' => 'robert@example.com',
                        'comment' => 'My trainer helped me lose 30 pounds and gain so much confidence. This article is spot on!',
                        'status' => 'approved',
                        'created_at' => Carbon::now()->subDays(1),
                        'replies' => []
                    ]
                ]
            ],
            [
                'article_slug' => 'how-to-make-cool-physique-in-gym-in-3-months',
                'comments' => [
                    [
                        'name' => 'David Kim',
                        'email' => 'david@example.com',
                        'comment' => 'This is exactly what I needed! Starting month 1 tomorrow. Wish me luck!',
                        'status' => 'approved',
                        'created_at' => Carbon::now()->subDays(5),
                        'replies' => [
                            [
                                'name' => 'Sarah Williams',
                                'email' => 'sarah@example.com',
                                'comment' => 'Good luck David! Remember, consistency is more important than perfection.',
                                'status' => 'approved',
                                'created_at' => Carbon::now()->subDays(4),
                            ]
                        ]
                    ],
                    [
                        'name' => 'Jennifer Taylor',
                        'email' => 'jennifer@example.com',
                        'comment' => 'I\'m halfway through month 2 following this plan. Already seeing amazing results!',
                        'status' => 'approved',
                        'created_at' => Carbon::now()->subDays(3),
                        'replies' => []
                    ],
                    [
                        'name' => 'Chris Anderson',
                        'email' => 'chris@example.com',
                        'comment' => 'The nutrition section is gold. Finally understand how to eat for my goals.',
                        'status' => 'approved',
                        'created_at' => Carbon::now()->subDays(2),
                        'replies' => []
                    ]
                ]
            ],
            [
                'article_slug' => 'how-gym-cycling-can-help-good-metabolism',
                'comments' => [
                    [
                        'name' => 'Amanda White',
                        'email' => 'amanda@example.com',
                        'comment' => 'I love cycling! Never realized how much it was helping my metabolism.',
                        'status' => 'approved',
                        'created_at' => Carbon::now()->subDays(8),
                        'replies' => []
                    ],
                    [
                        'name' => 'Michael Davis',
                        'email' => 'michael@example.com',
                        'comment' => 'Those HIIT cycling workouts are killer but so effective!',
                        'status' => 'approved',
                        'created_at' => Carbon::now()->subDays(7),
                        'replies' => [
                            [
                                'name' => 'David Lee',
                                'email' => 'david@example.com',
                                'comment' => 'They really are! Start with shorter intervals if you\'re new to HIIT.',
                                'status' => 'approved',
                                'created_at' => Carbon::now()->subDays(6),
                            ]
                        ]
                    ]
                ]
            ],
            [
                'article_slug' => 'nutrition-guide-building-muscle-losing-fat',
                'comments' => [
                    [
                        'name' => 'Rachel Green',
                        'email' => 'rachel@example.com',
                        'comment' => 'This is the most comprehensive nutrition guide I\'ve read. Bookmarking for sure!',
                        'status' => 'approved',
                        'created_at' => Carbon::now()->subHours(12),
                        'replies' => []
                    ],
                    [
                        'name' => 'Tom Wilson',
                        'email' => 'tom@example.com',
                        'comment' => 'The meal timing section really opened my eyes. I\'ve been doing it all wrong!',
                        'status' => 'approved',
                        'created_at' => Carbon::now()->subHours(8),
                        'replies' => [
                            [
                                'name' => 'Dr. Emily Chen',
                                'email' => 'emily@example.com',
                                'comment' => 'Glad it helped, Tom! Small changes in timing can make a big difference.',
                                'status' => 'approved',
                                'created_at' => Carbon::now()->subHours(6),
                            ]
                        ]
                    ]
                ]
            ]
        ];

        foreach ($comments as $articleComments) {
            $article = NewsArticle::where('slug', $articleComments['article_slug'])->first();
            
            if ($article) {
                foreach ($articleComments['comments'] as $commentData) {
                    $replies = $commentData['replies'];
                    unset($commentData['replies']);
                    
                    $commentData['news_id'] = $article->id;
                    $comment = NewsComment::create($commentData);

                    // Create replies
                    foreach ($replies as $replyData) {
                        $replyData['news_id'] = $article->id;
                        $replyData['parent_id'] = $comment->id;
                        NewsComment::create($replyData);
                    }
                }
                
                // Update article comments count
                $article->updateCommentsCount();
            }
        }
    }
}
