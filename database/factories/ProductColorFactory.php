<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductColor>
 */
class ProductColorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $colors = ['Black', 'Blue', 'Green','Red', 'White', 'Yellow', 'Gray', 'Pink', 'Purple', 'Orange'];

        return [
            'product_id' => Product::factory(),
            'name' => Arr::random($colors),
        ];
    }
}
