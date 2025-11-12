<?php

namespace Database\Seeders;

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
        // 1. Create the SUPER ADMIN User
        // This user gets the 'admin' role explicitly.
        $admin = User::firstOrCreate(
            ['email' => 'you@example.com'], // Find by email or create
            [
                'name' => ' Admin',
                'password' => Hash::make('password'), // Use a strong password in production!
            ]
        );
        $admin->assignRole('admin');

        // 2. Create a Project Manager/Lead User
        // This user gets the standard 'user' role.
        $lead_user = User::firstOrCreate(
            ['email' => 'ash@gmail.com'],
            [
                'name' => 'ash',
                'password' => Hash::make('password'),
            ]
        );
        $lead_user->assignRole('user');

        // 3. Create several regular 'user' accounts (Workers/Agents/Submitters)
        // These users will also get the 'user' role automatically.

        // Create 5 fake users using the User factory
        User::factory()->count(5)->create()->each(function ($user) {
            // Assign the default 'user' role to everyone not explicitly set above
            $user->assignRole('user');
        });

        $this->command->info('Users and roles seeded successfully!');
    }
}