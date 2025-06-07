<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin role if it doesn't exist
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        
        // Create admin user
        $admin = User::firstOrCreate(
            ['email' => 'smoggrafton@gmail.com'],
            [
                'name' => 'Mulinda Akiibu',
                'password' => Hash::make('9898@Morgan21@9898'),
                'email_verified_at' => now(),
            ]
        );
        
        // Assign admin role to the user
        if (!$admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }
        
        $this->command->info('Admin user created successfully!');
        $this->command->info('Email: smoggrafton@gmail.com');
        $this->command->info('Password: 9898@Morgan21@9898');
    }
}
