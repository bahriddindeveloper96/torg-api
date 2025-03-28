<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Product;

class Category extends Model implements TranslatableContract
{
    use Translatable;

    public array $translatedAttributes = ['name', 'description'];
    
    protected $fillable = ['image', 'slug', 'parent_id'];

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function getBreadcrumbs(): array
    {
        $breadcrumbs = [];
        $current = $this;

        while ($current !== null) {
            array_unshift($breadcrumbs, [
                'id' => $current->id,
                'name' => $current->name,
                'slug' => $current->slug
            ]);
            $current = $current->parent;
        }

        return $breadcrumbs;
    }
}
