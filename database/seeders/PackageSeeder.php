<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Package::create([
            'name' => 'Simple Weeding Package',
            'price' => 4150
        ]);
        Package::create([
            'name' => 'Premium Wedding Package',
            'price' => 5499
        ]);
    }
}
