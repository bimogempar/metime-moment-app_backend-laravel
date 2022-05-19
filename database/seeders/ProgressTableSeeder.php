<?php

namespace Database\Seeders;

use App\Models\Progress;
use Illuminate\Database\Seeder;

class ProgressTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Progress::factory()->count(10)->create();
    }
}
