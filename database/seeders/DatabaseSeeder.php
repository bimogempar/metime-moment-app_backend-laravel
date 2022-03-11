<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserTableSeeder::class);
        $this->call(ProjectTableSeeder::class);

        // Seed many to many relationship
        $user = User::all();
        Project::all()->each(function ($project) use ($user) {
            $project->users()->attach(
                $user->random(rand(1, 3))->pluck('id')->toArray()
            );
        });
    }
}
