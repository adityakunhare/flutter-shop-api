<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $sourcePath = storage_path('app/product-seed-images');

        $image = collect(glob($sourcePath . '/*.*'))->random();
        $extension =  pathinfo($image, PATHINFO_EXTENSION); 
        $filename = 'images/' . \Illuminate\Support\Str::uuid() . $extension;

        Storage::disk('public')->put($filename, file_get_contents($image));
        
        return [
            'category_id' => Category::factory(),
            'brand_id' => Brand::factory(),
            'title' => $this->faker->words(3, true),
            'image' => $filename,
            'price' => $this->faker->numberBetween(100, 1000),
            'old_price' => $this->faker->numberBetween(300, 1000),
        ];
    }
}
