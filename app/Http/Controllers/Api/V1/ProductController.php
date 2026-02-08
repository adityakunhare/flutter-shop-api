<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\ProductResource;
use App\Models\Product;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    public function index(): JsonResource
    {
        $cursor = request('cursor', 'first');
        $version = Cache::rememberForever('products_cache_version', fn () => 1);

        $products = Cache::remember(
            "products_v{$version}_cursor_{$cursor}",
            now()->addMinutes(5),
            fn () => Product::with(['brand', 'category'])->cursorPaginate()
        );

        return ProductResource::collection($products);
    }

    public function show($productId): ProductResource
    {
        return ProductResource::make(
            Cache::remember(
                'product_' . $productId,
                now()->addHours(3),
                fn () => Product::with(
                    ['brand', 'category', 'sizes', 'colors']
                )->find($productId)
            )
        );
    }
}
