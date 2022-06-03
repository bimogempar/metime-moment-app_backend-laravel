<?php

namespace Database\Seeders;

use App\Models\Features;
use App\Models\Package;
use App\Models\Progress;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProjectTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Project::factory()->count(21)->create();

        // Seed many to many relationship
        $user = User::all();
        Project::all()->each(function ($project) use ($user) {
            $project->users()->attach(
                $user->except(5)->random(rand(1, 3))->pluck('id')->toArray()
            );
            $project->features()->saveMany(
                Features::factory(rand(0, 5))->make()
            );
        });
    }
}
