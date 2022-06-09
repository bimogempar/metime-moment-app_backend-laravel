<?php

namespace Database\Seeders;

use App\Models\Features;
use App\Models\Progress;
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
        $this->call(PackageSeeder::class);
        $this->call(PackageListSeeder::class);
        $this->call(ProjectTableSeeder::class);
        $this->call(ProgressTableSeeder::class);
    }
}
