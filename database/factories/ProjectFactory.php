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
        $client = $this->faker->name('male');
        $eachclient = $this->faker->name('female');
        $slug = Str::slug($client . "-" . $eachclient);
        return [
            'client' => $client . " & " . $eachclient,
            'date' => $this->faker->datetime(),
            'time' => $this->faker->time(),
            'location' => $this->faker->streetName(),
            'location' => $this->faker->streetName(),
            'status' => $this->faker->numberBetween(1, 3),
            'phone_number' => $this->faker->phoneNumber(),
            'img' => $this->faker->imageUrl(640, 480, 'animals', true, 'cats'),
            'slug' => $slug,
        ];
    }
}
