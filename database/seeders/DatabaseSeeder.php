<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\ProductSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create users first
        $this->call(UserSeeder::class);
        
        // Then categories
        $this->call(CategorySeeder::class);
        
        // Finally products
        $this->call(ProductSeeder::class);
    }
}
