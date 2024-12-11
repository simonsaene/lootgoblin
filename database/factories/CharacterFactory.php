<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CharacterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'level' => $this->faker->numberBetween(1, 100),
            'name' => $this->faker->name(),
            'class' => $this->faker->randomElement(['Warrior', 'Mage', 'Archer', 'Thief']), 
            'profile_image' => $this->faker->imageUrl(200, 200, 'people'),
        ];
    }
}