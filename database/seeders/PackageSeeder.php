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
        ]);
        Package::create([
            'name' => 'Premium Wedding Package',
        ]);
    }
}
