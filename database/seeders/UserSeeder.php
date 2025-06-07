<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@narapromotionz.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'bio' => 'System administrator with full access to manage the platform.',
                'is_active' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Dr. Sarah Johnson',
                'email' => 'sarah@example.com',
                'password' => Hash::make('password'),
                'role' => 'author',
                'bio' => 'Certified fitness trainer and nutritionist with over 10 years of experience in the health and wellness industry.',
                'is_active' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Mike Thompson',
                'email' => 'mike@example.com',
                'password' => Hash::make('password'),
                'role' => 'author',
                'bio' => 'Professional bodybuilder and strength training expert. Specializes in muscle building and powerlifting.',
                'is_active' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Emma Davis',
                'email' => 'emma@example.com',
                'password' => Hash::make('password'),
                'role' => 'editor',
                'bio' => 'Health and fitness editor with expertise in content creation and wellness journalism.',
                'is_active' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'John Smith',
                'email' => 'john@example.com',
                'password' => Hash::make('password'),
                'role' => 'author',
                'bio' => 'Yoga instructor and mindfulness coach focused on holistic wellness and mental health.',
                'is_active' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Lisa Anderson',
                'email' => 'lisa@example.com',
                'password' => Hash::make('password'),
                'role' => 'author',
                'bio' => 'Sports nutritionist and former Olympic athlete with expertise in performance nutrition.',
                'is_active' => true,
                'email_verified_at' => now(),
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }
    }
}
