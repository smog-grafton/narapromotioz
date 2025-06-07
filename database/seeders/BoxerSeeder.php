<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Boxer;
use Carbon\Carbon;
use Illuminate\Support\Str;

class BoxerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $boxers = [
            [
                'name' => 'Mike "The Thunder" Johnson',
                'weight_class' => 'Heavyweight',
                'wins' => 28,
                'losses' => 2,
                'draws' => 1,
                'knockouts' => 24,
                'kos_lost' => 1,
                'age' => 29,
                'height' => '6\'4"',
                'reach' => '78"',
                'stance' => 'Orthodox',
                'hometown' => 'Brooklyn',
                'country' => 'USA',
                'bio' => 'Known for his devastating knockout power, Mike "The Thunder" Johnson has been dominating the heavyweight division with his explosive fighting style.',
                'titles' => ['WBC Heavyweight Champion', 'IBF Heavyweight Champion'],
                'years_pro' => 8,
                'status' => 'active',
                'global_ranking' => 1,
                'debut_date' => Carbon::now()->subYears(8),
                'knockout_rate' => 85.7,
                'win_rate' => 90.3,
                'image_path' => 'assets/images/boxers/boxer1.jpg',
            ],
            [
                'name' => 'Carlos "Lightning" Rodriguez',
                'weight_class' => 'Welterweight',
                'wins' => 32,
                'losses' => 1,
                'draws' => 0,
                'knockouts' => 18,
                'kos_lost' => 0,
                'age' => 27,
                'height' => '5\'10"',
                'reach' => '72"',
                'stance' => 'Southpaw',
                'hometown' => 'Mexico City',
                'country' => 'Mexico',
                'bio' => 'A technical boxer with lightning-fast combinations, Carlos Rodriguez is known for his precision and ring IQ.',
                'titles' => ['WBA Welterweight Champion'],
                'years_pro' => 9,
                'status' => 'active',
                'global_ranking' => 1,
                'debut_date' => Carbon::now()->subYears(9),
                'knockout_rate' => 54.5,
                'win_rate' => 97.0,
                'image_path' => 'assets/images/boxers/boxer2.jpg',
            ],
            [
                'name' => 'Anthony "The Beast" Williams',
                'weight_class' => 'Light Heavyweight',
                'wins' => 25,
                'losses' => 3,
                'draws' => 2,
                'knockouts' => 20,
                'kos_lost' => 2,
                'age' => 31,
                'height' => '6\'1"',
                'reach' => '74"',
                'stance' => 'Orthodox',
                'hometown' => 'London',
                'country' => 'UK',
                'bio' => 'A power puncher with an aggressive style, Anthony Williams has built his reputation on delivering spectacular knockouts.',
                'titles' => ['WBO Light Heavyweight Champion'],
                'years_pro' => 10,
                'status' => 'active',
                'global_ranking' => 2,
                'debut_date' => Carbon::now()->subYears(10),
                'knockout_rate' => 80.0,
                'win_rate' => 83.3,
                'image_path' => 'assets/images/boxers/boxer3.jpg',
            ],
            [
                'name' => 'David "The Ugandan Lion" Ssemujju',
                'weight_class' => 'Middleweight',
                'wins' => 22,
                'losses' => 1,
                'draws' => 1,
                'knockouts' => 16,
                'kos_lost' => 0,
                'age' => 26,
                'height' => '5\'11"',
                'reach' => '73"',
                'stance' => 'Orthodox',
                'hometown' => 'Kampala',
                'country' => 'Uganda',
                'bio' => 'Pride of Uganda, David Ssemujju has quickly risen through the ranks with his combination of power and technical skill.',
                'titles' => ['East African Middleweight Champion'],
                'years_pro' => 6,
                'status' => 'active',
                'global_ranking' => 5,
                'debut_date' => Carbon::now()->subYears(6),
                'knockout_rate' => 72.7,
                'win_rate' => 91.7,
                'image_path' => 'assets/images/boxers/boxer4.jpg',
            ],
            [
                'name' => 'Tommy "The Machine" Chen',
                'weight_class' => 'Featherweight',
                'wins' => 30,
                'losses' => 2,
                'draws' => 0,
                'knockouts' => 12,
                'kos_lost' => 0,
                'age' => 28,
                'height' => '5\'7"',
                'reach' => '68"',
                'stance' => 'Southpaw',
                'hometown' => 'Shanghai',
                'country' => 'China',
                'bio' => 'Known for his relentless pace and conditioning, Tommy Chen overwhelms opponents with his work rate and precision.',
                'titles' => ['WBC Featherweight Champion'],
                'years_pro' => 7,
                'status' => 'active',
                'global_ranking' => 1,
                'debut_date' => Carbon::now()->subYears(7),
                'knockout_rate' => 40.0,
                'win_rate' => 93.8,
                'image_path' => 'assets/images/boxers/boxer5.jpg',
            ],
            [
                'name' => 'Roberto "El Matador" Fernandez',
                'weight_class' => 'Super Welterweight',
                'wins' => 27,
                'losses' => 4,
                'draws' => 1,
                'knockouts' => 19,
                'kos_lost' => 2,
                'age' => 30,
                'height' => '5\'11"',
                'reach' => '74"',
                'stance' => 'Orthodox',
                'hometown' => 'Madrid',
                'country' => 'Spain',
                'bio' => 'A crowd favorite known for his exciting fighting style and never-say-die attitude.',
                'titles' => ['European Super Welterweight Champion'],
                'years_pro' => 9,
                'status' => 'active',
                'global_ranking' => 3,
                'debut_date' => Carbon::now()->subYears(9),
                'knockout_rate' => 70.4,
                'win_rate' => 84.4,
                'image_path' => 'assets/images/boxers/boxer6.jpg',
            ],
        ];

        foreach ($boxers as $boxer) {
            // Generate slug from name
            $boxer['slug'] = Str::slug($boxer['name']);
            
            // Convert titles to JSON
            $boxer['titles'] = json_encode($boxer['titles']);
            
            // Calculate total_fighters_in_division (mock data)
            $boxer['total_fighters_in_division'] = rand(150, 300);
            
            // Create the boxer
            Boxer::create($boxer);
        }
    }
}
