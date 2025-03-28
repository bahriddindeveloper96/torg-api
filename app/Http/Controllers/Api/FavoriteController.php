<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $favorites = auth()->user()->favorites()->with(['product.category', 'product.user'])->get();
        return response()->json($favorites);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Product $product)
    {
        $user = auth()->user();
        
        $favorite = $user->favorites()->where('product_id', $product->id)->first();
        
        if ($favorite) {
            return response()->json([
                'message' => 'Product is already in favorites'
            ], 422);
        }

        $favorite = Favorite::create([
            'user_id' => $user->id,
            'product_id' => $product->id
        ]);

        return response()->json($favorite->load('product'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $user = auth()->user();
        
        $favorite = $user->favorites()->where('product_id', $product->id)->first();
        
        if (!$favorite) {
            return response()->json([
                'message' => 'Product is not in favorites'
            ], 404);
        }

        $favorite->delete();

        return response()->json(['message' => 'Product removed from favorites']);
    }
}
