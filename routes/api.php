<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\UserController as AdminUserController;
use App\Http\Controllers\Api\Admin\ProductController as AdminProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Homepage route
Route::get('home', [HomeController::class, 'index']);

// Auth routes
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Public routes
Route::get('categories', [CategoryController::class, 'index']);
Route::get('categories/{category}', [CategoryController::class, 'show']);
Route::get('categories/{category}/products', [CategoryController::class, 'products']);
Route::get('products', [ProductController::class, 'index']);
Route::get('products/{product}', [ProductController::class, 'show']);
Route::get('products/search', [ProductController::class, 'search']);
Route::get('products/category/{category}', [ProductController::class, 'byCategory']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // User routes
    Route::get('user/profile', [UserController::class, 'profile']);
    Route::put('user/profile', [UserController::class, 'update']);
    Route::get('user/products', [UserController::class, 'products']);
    
    // Product routes
    Route::post('products', [ProductController::class, 'store']);
    Route::put('products/{product}', [ProductController::class, 'update']);
    Route::delete('products/{product}', [ProductController::class, 'destroy']);
    
    // Category management (admin only)
    Route::post('categories', [CategoryController::class, 'store']);
    Route::put('categories/{category}', [CategoryController::class, 'update']);
    Route::delete('categories/{category}', [CategoryController::class, 'destroy']);
    
    // Messages
    Route::get('messages', [MessageController::class, 'index']);
    Route::get('messages/{user}', [MessageController::class, 'show']);
    Route::post('messages/{user}', [MessageController::class, 'store']);
    Route::delete('messages/{message}', [MessageController::class, 'destroy']);
    
    // Favorites
    Route::get('favorites', [FavoriteController::class, 'index']);
    Route::post('favorites/{product}', [FavoriteController::class, 'store']);
    Route::delete('favorites/{product}', [FavoriteController::class, 'destroy']);
    
    // Logout
    Route::post('logout', [AuthController::class, 'logout']);
});

// Admin routes
Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    // Dashboard
    Route::get('dashboard/stats', [DashboardController::class, 'stats']);
    
    // Users management
    Route::get('users', [AdminUserController::class, 'index']);
    Route::get('users/{user}', [AdminUserController::class, 'show']);
    Route::put('users/{user}', [AdminUserController::class, 'update']);
    Route::delete('users/{user}', [AdminUserController::class, 'destroy']);
    
    // Products management
    Route::get('products', [AdminProductController::class, 'index']);
    Route::get('products/{product}', [AdminProductController::class, 'show']);
    Route::put('products/{product}', [AdminProductController::class, 'update']);
    Route::delete('products/{product}', [AdminProductController::class, 'destroy']);
    Route::post('products/{product}/moderate', [AdminProductController::class, 'moderate']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
