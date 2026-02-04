<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductSize;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $brands = Brand::factory(5)->create();
        $categories = Category::factory(5)->create();

        $products = Product::factory(20)->recycle([
            $brands, $categories
        ])->create();

        ProductSize::factory(100)->recycle($products);
        ProductColor::factory(100)->recycle($products);

    }
}
