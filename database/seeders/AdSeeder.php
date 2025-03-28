<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ad;

class AdSeeder extends Seeder
{
    public function run(): void
    {
        $ads = [
            [
                'title' => 'Premium e\'lonlar',
                'description' => 'E\'loningizni premium qiling va ko\'proq xaridorlarni jalb qiling!',
                'image' => 'ads/premium.jpg',
                'position' => 'top',
                'start_date' => now(),
                'end_date' => now()->addDays(30),
            ],
            [
                'title' => 'Yangi mahsulotlar',
                'description' => 'Eng so\'nggi yangi mahsulotlarni ko\'ring',
                'image' => 'ads/new.jpg',
                'position' => 'sidebar',
                'start_date' => now(),
                'end_date' => now()->addDays(15),
            ],
            [
                'title' => 'Maxsus takliflar',
                'description' => 'Chegirmalar va maxsus takliflarni qo\'ldan boy bermang!',
                'image' => 'ads/special.jpg',
                'position' => 'bottom',
                'start_date' => now(),
                'end_date' => now()->addDays(7),
            ],
        ];

        foreach ($ads as $adData) {
            Ad::create($adData);
        }
    }
}
