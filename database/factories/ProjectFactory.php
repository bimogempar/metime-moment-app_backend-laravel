<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $client = $this->faker->name();
        $eachclient = $this->faker->name();
        $slug = Str::slug($client . "-" . $eachclient);
        return [
            'client' => $client . " & " . $eachclient,
            'date' => $this->faker->datetime(),
            'time' => $this->faker->time(),
            'slug' => $slug,
        ];
    }
}
