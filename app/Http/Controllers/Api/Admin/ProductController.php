<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['translations', 'category', 'user'])
            ->latest()
            ->paginate(15);
        return response()->json($products);
    }

    public function show(Product $product)
    {
        return response()->json($product->load(['translations', 'category', 'user']));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => 'sometimes|exists:categories,id',
            'price' => 'sometimes|numeric|min:0',
            'status' => 'sometimes|in:active,inactive,pending',
            'translations' => 'sometimes|array',
            'translations.*.locale' => 'required_with:translations|string|size:2',
            'translations.*.name' => 'required_with:translations|string|max:255',
            'translations.*.description' => 'required_with:translations|string',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
        }

        $product->update($validated);

        if (isset($validated['translations'])) {
            foreach ($validated['translations'] as $translation) {
                $product->translations()->updateOrCreate(
                    ['locale' => $translation['locale']],
                    [
                        'name' => $translation['name'],
                        'description' => $translation['description']
                    ]
                );
            }
        }

        return response()->json($product->load(['translations', 'category']));
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();
        return response()->json(['message' => 'Product deleted successfully']);
    }

    public function moderate(Product $product, Request $request)
    {
        $validated = $request->validate([
            'status' => 'required|in:active,inactive',
            'moderation_note' => 'nullable|string|max:500'
        ]);

        $product->update($validated);
        return response()->json($product);
    }
}
