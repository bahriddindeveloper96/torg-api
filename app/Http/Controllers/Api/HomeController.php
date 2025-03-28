<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $locale = app()->getLocale();

        // Top kategoriyalar (mahsulotlar soni bo'yicha)
        $topCategories = Category::withCount('products')
            ->with(['translations' => function($query) use ($locale) {
                $query->where('locale', $locale);
            }])
            ->orderBy('products_count', 'desc')
            ->take(8)
            ->get();

        // So'nggi qo'shilgan mahsulotlar
        $latestProducts = Product::with([
                'category.translations' => function($query) use ($locale) {
                    $query->where('locale', $locale);
                },
                'translations' => function($query) use ($locale) {
                    $query->where('locale', $locale);
                },
                'user'
            ])
            ->latest()
            ->take(12)
            ->get();

        // Premium mahsulotlar
        $premiumProducts = Product::with([
                'category.translations' => function($query) use ($locale) {
                    $query->where('locale', $locale);
                },
                'translations' => function($query) use ($locale) {
                    $query->where('locale', $locale);
                },
                'user'
            ])
            ->where('is_premium', true)
            ->inRandomOrder()
            ->take(6)
            ->get();

        // Ko'p ko'rilgan mahsulotlar
        $popularProducts = Product::with([
                'category.translations' => function($query) use ($locale) {
                    $query->where('locale', $locale);
                },
                'translations' => function($query) use ($locale) {
                    $query->where('locale', $locale);
                },
                'user'
            ])
            ->orderBy('views', 'desc')
            ->take(8)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'locale' => $locale,
                'top_categories' => $topCategories,
                'latest_products' => $latestProducts,
                'premium_products' => $premiumProducts,
                'popular_products' => $popularProducts,
                'stats' => [
                    'total_products' => Product::count(),
                    'total_users' => \App\Models\User::count(),
                    'total_categories' => Category::count(),
                ]
            ]
        ]);
    }
}
