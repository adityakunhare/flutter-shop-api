<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Support\Benchmark;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function index()
    {
    [$products, $duration] =   Benchmark::value(function (){

            $products = DB::table('products as p')
                ->leftJoin('brands as b', 'b.id', '=', 'p.brand_id')
                ->leftJoin('categories as c', 'c.id', '=', 'p.category_id')
                ->select(
                    'p.id',
                    'p.title',
                    'p.image',
                    'p.price',
                    'p.old_price',
                    'b.name as brand',
                    'c.name as category'
                )->get();

            $productColors = DB::table('product_colors')
                ->select('product_id', 'name')
                ->get()
                ->groupBy('product_id')->toArray();

            $productSizes = DB::table('product_sizes')
                ->select('product_id', 'number')
                ->get()
                ->groupBy('product_id')->toArray() ;

        $products = $products->map(function ($product) use ($productColors, $productSizes) {
            $product->colors = collect($productColors[$product->id])->pluck('name')->values();
            $product->sizes  = collect($productSizes[$product->id])->pluck('number')->values();
            return $product;
        });

        // $products =  Product::with(['brand:id,name', 'category:id,name', 'colors:product_id,name', 'sizes:product_id,number'])->get();
            
        return $products;

    });
        
        return response($products,headers:['x-duration' => $duration]);
    }
}
