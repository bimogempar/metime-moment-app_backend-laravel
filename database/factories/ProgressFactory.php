<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProgressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'project_id' => $this->faker->numberBetween(1, 10),
            'user_id' => function (array $attributes) {
                return Project::with('users')->find($attributes['project_id'])->users->random()->id;
            },
            'description' => $this->faker->text,
            'img_url' => $this->faker->imageUrl(),
        ];
    }
}
