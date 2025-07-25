<?php

namespace Database\Factories;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;

class BrandFactory extends Factory
{
    protected $model = Brand::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->company(),
            'slug' => $this->faker->unique()->slug,
            'description' => $this->faker->sentence(8),
        ];
    }
}
