<?php

namespace Database\Factories;

use App\Models\GrindSpot; 
use App\Models\GrindSession;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class GrindSessionFactory extends Factory
{
    protected $model = GrindSession::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'grind_spot_id' => GrindSpot::factory(),
            'user_id' => User::factory(),
            'loot_image' => $this->faker->imageUrl(350, 250),
            'video_link' => $this->faker->url,
            'notes' => $this->faker->sentence(),
            'hours' => $this->faker->numberBetween(1, 10),
            'is_video_verified' => $this->faker->boolean,
            'is_image_verified' => $this->faker->boolean,
        ];
    }

    /**
     * Indicate that the grind session has verified video and image.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function verified()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_video_verified' => true,
                'is_image_verified' => true,
            ];
        });
    }

    /**
     * Indicate that the grind session has no video or image verification.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_video_verified' => false,
                'is_image_verified' => false,
            ];
        });
    }
}
