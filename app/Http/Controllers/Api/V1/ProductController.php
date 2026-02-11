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
        return ProductResource::collection(
            Product::with(['brand', 'category'])
                ->categoryFilter($request->category)
                ->brandFilter($request->brand)
                ->cursorPaginate()
                ->withQueryString()
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
