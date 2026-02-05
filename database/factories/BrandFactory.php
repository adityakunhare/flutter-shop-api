<?php

namespace Database\Factories;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Brand>
 */
class BrandFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $brands = ['Nike', 'Adidas', 'Reebok', 'Sketchers', 'New Balance', 'Bacca Bucci', 'RedTape', 'HRX'];

        return [
            'name' => Arr::random($brands),
        ];
    }
}
