<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductSize;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Storage::disk('public')->deleteDirectory('images');
        Storage::disk('public')->makeDirectory('images');

        $brands = Brand::factory(5)->create();
        $categoryTitles  = ['running', 'trekking', 'hiking', 'cycling', 'canoeing', 'fishing'];

        $categories = []; 
        foreach ($categoryTitles as $category) {
            $categories[] = Category::factory()->create([ 
                'name' => $category
            ]);
        }

        $products = Product::factory(1000)->recycle([
            $brands, collect($categories)
        ])->create();

        $productSizes = ['6', '7', '8', '9', '10', '11', '12'];
        $productColors = [
            'Black', 'Blue', 'Green','Red', 'White', 'Yellow', 'Gray', 'Pink', 'Purple', 'Orange'
        ];

        foreach ($products as $product) {
            foreach ($productSizes as $productSize) {            
                ProductSize::factory()->create([
                    'product_id' => $product->id,
                    'number' => $productSize
                ]);
            }
            foreach($productColors as $productColor){
                ProductColor::factory()->create([
                    'product_id' => $product->id,
                    'name' => $productColor
                ]);
            }
        }
    }
}
