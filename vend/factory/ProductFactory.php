<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $categories = ['Elektronik', 'Furniture', 'Kitchenware','Clothes', 'Otomotif', 'Sport','Stationery', 'Others'];

        return [
            'name' => $this->faker->words(2, true),
            'category' => $this->faker->randomElement($categories),
            'price' => $this->faker->numberBetween(10000, 300000),
            'stock' => $this->faker->numberBetween(1, 20),
            'image' => 'default.jpg', 
            'description' => $this->faker->sentence(12)
        ];
    }
}
