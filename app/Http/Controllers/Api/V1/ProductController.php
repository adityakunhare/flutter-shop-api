<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    public function index(Request $request): JsonResource
    {
        // $cursor = request('cursor', 'first');
        // $version = Cache::rememberForever('products_cache_version', fn () => 1);

        // $products = Cache::remember(
        //     "products_v{$version}_cursor_{$cursor}",
        //     now()->addMinutes(5),
        //     fn () => Product::when(
        //                 $request->category, 
        //                 fn ($q, $category) => $q->where('category_id', $category)
        //             )->with(['brand', 'category'])
        //             ->cursorPaginate()
        // );

        return ProductResource::collection(
            Product::with(['brand', 'category'])
                ->when(
                    $request->category,
                    fn($q, $category) => $q->where('category_id', $category)
                )
                ->when(
                    $request->brand,
                    fn($q, $brand) => $q->where('brand_id', $brand)
                )->cursorPaginate()
        );
    }

    public function show($productId): ProductResource
    {
        return ProductResource::make(
            Product::with(
                ['brand', 'category', 'sizes', 'colors']
            )->find($productId)
        );
    }
}
