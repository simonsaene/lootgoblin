<?php

namespace Database\Factories;
use App\Models\Item;
use App\Models\GrindSpot;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AppModelsGrindSpotItem>
 */
class GrindSpotItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'item_id' => Item::factory(),
            'grind_spot_id' => GrindSpot::factory(),
        ];
    }
}
