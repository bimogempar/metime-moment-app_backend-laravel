<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FeaturesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'feature' => $this->faker->sentence,
            'status' => $this->faker->numberBetween(0, 1),
            'additional_features' => 0,
        ];
    }
}
