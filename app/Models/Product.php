<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use App\Models\Category;
use App\Models\User;

class Product extends Model implements TranslatableContract
{
    use HasFactory, Translatable;

    public $translatedAttributes = ['title', 'description'];

    protected $fillable = [
        'price',
        'category_id',
        'user_id',
        'is_premium',
        'views',
        'status'
    ];

    protected $casts = [
        'is_premium' => 'boolean',
        'price' => 'float',
        'views' => 'integer'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
