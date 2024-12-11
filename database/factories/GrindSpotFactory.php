<?php

namespace Database\Factories;

use App\Models\GrindSpot;
use Illuminate\Database\Eloquent\Factories\Factory;

class GrindSpotFactory extends Factory
{
    protected $model = GrindSpot::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'location' => $this->faker->city,
            'description' => $this->faker->word,
            'suggested_level' => $this->faker->numberBetween(1, 100),
            'suggested_gearscore' => $this->faker->numberBetween(1000, 5000),
            'difficulty' => $this->faker->randomElement([1, 2, 3]),
            'mechanics' => $this->faker->sentence,
        ];
    }
}
