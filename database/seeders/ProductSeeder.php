<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();
        $categories = Category::all();

        foreach ($categories as $category) {
            // Create 5 products for each category
            for ($i = 0; $i < 5; $i++) {
                $product = Product::create([
                    'price' => rand(100000, 10000000),
                    'category_id' => $category->id,
                    'user_id' => $users->random()->id,
                    'is_premium' => rand(0, 1),
                    'views' => rand(0, 1000),
                    'status' => 'active',
                    'slug' => Str::slug($category->translate('en')->name . '-' . Str::random(6))
                ]);

                // Add translations
                $product->translateOrNew('uz')->title = "O'zbek mahsulot " . ($i + 1) . " - " . $category->translate('uz')->name;
                $product->translateOrNew('uz')->description = "O'zbek tildagi mahsulot tavsifi " . ($i + 1);

                $product->translateOrNew('ru')->title = "Русский продукт " . ($i + 1) . " - " . $category->translate('ru')->name;
                $product->translateOrNew('ru')->description = "Описание продукта на русском " . ($i + 1);

                $product->translateOrNew('en')->title = "English product " . ($i + 1) . " - " . $category->translate('en')->name;
                $product->translateOrNew('en')->description = "Product description in English " . ($i + 1);

                $product->save();
            }
        }
    }
}
