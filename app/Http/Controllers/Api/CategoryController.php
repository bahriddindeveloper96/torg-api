<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        return Category::with('children')
            ->whereNull('parent_id')
            ->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('categories', 'public');
        }

        $category = Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'parent_id' => $request->parent_id,
            'image' => $imagePath
        ]);

        return response()->json($category, 201);
    }

    public function show(Category $category)
    {
        $category->load(['children', 'products', 'translations', 'children.translations']);
        
        return response()->json([
            'category' => $category,
            'breadcrumbs' => $category->getBreadcrumbs()
        ]);
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            
            // Store new image
            $imagePath = $request->file('image')->store('categories', 'public');
            $request->merge(['image' => $imagePath]);
        }

        $category->update($request->all());

        return response()->json($category);
    }

    public function destroy(Category $category)
    {
        // Check if category has children
        if ($category->children()->exists()) {
            return response()->json([
                'message' => 'Cannot delete category with subcategories'
            ], 422);
        }

        // Check if category has products
        if ($category->products()->exists()) {
            return response()->json([
                'message' => 'Cannot delete category with products'
            ], 422);
        }

        // Delete category image
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return response()->json(['message' => 'Category deleted successfully']);
    }

    public function products(Category $category)
    {
        $products = $category->products()
            ->with('translations')
            ->paginate(20);

        return response()->json([
            'products' => $products,
            'breadcrumbs' => $category->getBreadcrumbs()
        ]);
    }
}
