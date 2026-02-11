<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class Product extends Model
{
    use HasFactory;

    protected static function booted()
    {
        static::saved(fn () => Cache::increment('products_cache_version'));
        static::updated(fn () => Cache::increment('products_cache_version'));
        static::deleted(fn () => Cache::increment('products_cache_version'));
    }

    public function sizes(): HasMany
    {
        return $this->hasMany(ProductSize::class);
    }

    public function colors(): HasMany
    {
        return $this->hasMany(ProductColor::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeBrandFilter($query, $brand)
    {
        return $query->when(request()->brand, function ($query) use ($brand){
            return $query->where('brand_id', $brand); 
        });
    }

    public function scopeCategoryFilter($query, $category)
    {
        return $query->when(request()->category, function ($query) use ($category){
            return $query->where('category_id', $category); 
        });
    }
}