<?php

namespace Database\Seeders;

use App\Models\PackageList;
use Illuminate\Database\Seeder;

class PackageListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $simple = [
            "6 - 7 hours session include akad / pemberkatan - resepsi",
            "1 professional videographer",
            "2 professional photographers",
            "1 minute teaser video",
            "Candid wedding photo",
            "Ceremonial wedding photo",
            "Magnetic Album 120 photos",
            "150 edited photos",
            "All file master photos in USB"
        ];

        foreach ($simple as $index) {
            PackageList::create([
                'name' => $index,
                'package_id' => 1
            ]);
        }

        $premium = [
            "6 - 7 hours session include akad / pemberkatan - resepsi",
            "2 professional videographer",
            "2 professional photographers",
            "3 - 5 minute highlight video",
            "1 minute teaser video",
            "Candid wedding photo",
            "Ceremonial wedding photo",
            "Magnetic Album 120 photos",
            "Magazine (20x30cm)",
            "16 R + premium frame (2)",
            "350 edited photos",
            "All file master photos in USB"
        ];

        foreach ($premium as $index) {
            PackageList::create([
                'name' => $index,
                'package_id' => 2
            ]);
        }
    }
}
