<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    use HasFactory;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'admin',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'no_hp' => '12345678910',
            'img' => 'https://i.pravatar.cc/',
            'role' => 2,
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'bimo',
            'username' => 'bimo',
            'img' => 'https://i.pravatar.cc/',
            'email' => 'bimo@example.com',
            'no_hp' => '1234567891011',
            'password' => Hash::make('password'),
        ]);

        User::factory()->count(10)->create();
    }
}
