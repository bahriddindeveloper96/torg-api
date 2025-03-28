<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'phone' => '+998901234567',
            'email_verified_at' => now(),
        ]);

        // Create test users
        $users = [
            [
                'name' => 'Alisher',
                'email' => 'alisher@example.com',
                'phone' => '+998901234568',
            ],
            [
                'name' => 'Dilshod',
                'email' => 'dilshod@example.com',
                'phone' => '+998901234569',
            ],
            [
                'name' => 'Malika',
                'email' => 'malika@example.com',
                'phone' => '+998901234570',
            ],
            [
                'name' => 'Sarvar',
                'email' => 'sarvar@example.com',
                'phone' => '+998901234571',
            ],
        ];

        foreach ($users as $userData) {
            User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make('password'),
                'phone' => $userData['phone'],
                'email_verified_at' => now(),
            ]);
        }
    }
}
