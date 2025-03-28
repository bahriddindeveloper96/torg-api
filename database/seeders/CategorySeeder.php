<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'image' => 'electronics.png',
                'slug' => 'electronics',
                'translations' => [
                    [
                        'locale' => 'uz',
                        'name' => 'Elektronika',
                        'description' => 'Elektronika mahsulotlari'
                    ],
                    [
                        'locale' => 'ru',
                        'name' => 'Электроника',
                        'description' => 'Электронные товары'
                    ],
                    [
                        'locale' => 'en',
                        'name' => 'Electronics',
                        'description' => 'Electronic products'
                    ]
                ]
            ],
            [
                'image' => 'vehicles.png',
                'slug' => 'vehicles',
                'translations' => [
                    [
                        'locale' => 'uz',
                        'name' => 'Transport',
                        'description' => 'Avtomobil va transport vositalari'
                    ],
                    [
                        'locale' => 'ru',
                        'name' => 'Транспорт',
                        'description' => 'Автомобили и транспортные средства'
                    ],
                    [
                        'locale' => 'en',
                        'name' => 'Vehicles',
                        'description' => 'Cars and vehicles'
                    ]
                ]
            ],
            [
                'image' => 'property.png',
                'slug' => 'property',
                'translations' => [
                    [
                        'locale' => 'uz',
                        'name' => 'Ko\'chmas mulk',
                        'description' => 'Uy-joy va ko\'chmas mulk'
                    ],
                    [
                        'locale' => 'ru',
                        'name' => 'Недвижимость',
                        'description' => 'Дома и недвижимость'
                    ],
                    [
                        'locale' => 'en',
                        'name' => 'Property',
                        'description' => 'Houses and real estate'
                    ]
                ]
            ],
            [
                'image' => 'fashion.png',
                'slug' => 'fashion',
                'translations' => [
                    [
                        'locale' => 'uz',
                        'name' => 'Moda',
                        'description' => 'Kiyim-kechak va aksessuarlar'
                    ],
                    [
                        'locale' => 'ru',
                        'name' => 'Мода',
                        'description' => 'Одежда и аксессуары'
                    ],
                    [
                        'locale' => 'en',
                        'name' => 'Fashion',
                        'description' => 'Clothing and accessories'
                    ]
                ]
            ]
        ];

        foreach ($categories as $category) {
            $translations = $category['translations'];
            unset($category['translations']);
            
            Category::create($category)->translations()->createMany($translations);
        }
    }
}
