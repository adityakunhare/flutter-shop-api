<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

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
        return [
            'category_id' => Category::factory(),
            'brand_id' => Brand::factory(),
            'title' => $this->faker->words(3, true),
            'image' => $this->faker->imageUrl(),
            'price' => $this->faker->numberBetween(100, 1000),
            'old_price' => $this->faker->numberBetween(300, 1000),
        ];
    }
}
