<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Character;
use App\Models\GrindSpot;
use App\Models\Favourite;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Favourite>
 */
class FavouriteFactory extends Factory
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
            'character_id' => Character::factory(),
            'grind_spot_id' => GrindSpot::factory(),
        ];
    }
}
