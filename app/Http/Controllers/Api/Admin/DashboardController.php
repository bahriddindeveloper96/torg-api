<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Message;

class DashboardController extends Controller
{
    public function stats()
    {
        $stats = [
            'total_users' => User::count(),
            'total_products' => Product::count(),
            'total_categories' => Category::count(),
            'total_messages' => Message::count(),
            'recent_users' => User::latest()->take(5)->get(),
            'recent_products' => Product::with('translations')->latest()->take(5)->get(),
        ];

        return response()->json($stats);
    }
}
