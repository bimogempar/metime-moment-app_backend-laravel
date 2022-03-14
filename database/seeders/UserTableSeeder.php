<?php

namespace Database\Seeders;

use App\Models\TokenInitialPassword;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
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

        User::factory()->count(5)->create()->each(function ($user) {
            $tokeninitialpassword = Str::random(30);
            $user->TokenInitialPassword()->save(
                new TokenInitialPassword([
                    'token_initial_password' => $tokeninitialpassword,
                    'status' => 0,
                ])
            );
            // $user->sendEmailRegister($user, $tokeninitialpassword);
        });

        $tokeninitialpassword = Str::random(30);
        User::create([
            'name' => 'admin',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'no_hp' => '12345678910',
            'role' => 2,
            'password' => Hash::make('password'),
        ])->TokenInitialPassword()->save(
            new TokenInitialPassword([
                'token_initial_password' => $tokeninitialpassword,
                'status' => 0,
            ])
        );
        // )->sendemailregister('admin@example.com', $tokeninitialpassword);

        User::create([
            'name' => 'bimo',
            'username' => 'bimo',
            'email' => 'bimo@example.com',
            'no_hp' => '1234567891011',
            'password' => Hash::make('password'),
        ])->TokenInitialPassword()->save(
            TokenInitialPassword::factory()->make()
        );
        // )->sendemailregister('bimo@example.com', $tokeninitialpassword);
    }
}
