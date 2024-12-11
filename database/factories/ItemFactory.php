<?php

namespace Database\Factories;

use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'market_value' => $this->faker->randomNumber(3), // Random value between 0-999
            'vendor_value' => $this->faker->randomNumber(2), // Random value between 0-99
            'image' => $this->faker->imageUrl(640, 480, 'items'), // Fake image URL
            'is_trash' => $this->faker->boolean(), // Random boolean (true or false)
        ];
    }
}
