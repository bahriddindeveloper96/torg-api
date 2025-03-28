<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'user'])
            ->when($request->search, function ($q) use ($request) {
                return $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            })
            ->when($request->category_id, function ($q) use ($request) {
                return $q->where('category_id', $request->category_id);
            })
            ->when($request->price_min, function ($q) use ($request) {
                return $q->where('price', '>=', $request->price_min);
            })
            ->when($request->price_max, function ($q) use ($request) {
                return $q->where('price', '<=', $request->price_max);
            })
            ->when($request->condition, function ($q) use ($request) {
                return $q->where('condition', $request->condition);
            })
            ->when($request->location, function ($q) use ($request) {
                return $q->where('location', 'like', '%' . $request->location . '%');
            });

        return $query->latest()->paginate(20);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'condition' => 'required|in:new,used',
            'location' => 'required|string',
            'images.*' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $images[] = $path;
            }
        }

        $product = Product::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'description' => $request->description,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'user_id' => auth()->id(),
            'condition' => $request->condition,
            'location' => $request->location,
            'images' => $images,
            'status' => 'active'
        ]);

        return response()->json($product->load('category', 'user'), 201);
    }

    public function show(Product $product)
    {
        $product->increment('views');
        return response()->json($product->load('category', 'user'));
    }

    public function update(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        $request->validate([
            'title' => 'string|max:255',
            'description' => 'string',
            'price' => 'numeric|min:0',
            'category_id' => 'exists:categories,id',
            'condition' => 'in:new,used',
            'location' => 'string',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($request->hasFile('images')) {
            // Delete old images
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image);
            }

            // Upload new images
            $images = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $images[] = $path;
            }
            $request->merge(['images' => $images]);
        }

        $product->update($request->all());

        return response()->json($product->load('category', 'user'));
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);

        // Delete product images
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image);
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }

    public function search(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:2'
        ]);

        return Product::with(['category', 'user'])
            ->where('title', 'like', '%' . $request->q . '%')
            ->orWhere('description', 'like', '%' . $request->q . '%')
            ->paginate(20);
    }

    public function byCategory($category)
    {
        return Product::with(['category', 'user'])
            ->whereHas('category', function ($query) use ($category) {
                $query->where('id', $category)
                    ->orWhere('slug', $category);
            })
            ->paginate(20);
    }
}
