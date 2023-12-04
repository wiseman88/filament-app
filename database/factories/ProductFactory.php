<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = fake()->name();

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'sku' => fake()->regexify('[A-Z]{5}[0-4]{3}'),
            'description' => fake()->optional()->paragraph(),
            'visible' => fake()->boolean(),
            'image' => fake()->imageUrl(),
            'quantity' => fake()->numberBetween(0, 100),
            'price' => fake()->randomFloat(2, 0, 1000),
        ];
    }
}
