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

        // User::factory()->count(4)->create()->each(function ($user) {
        //     $tokeninitialpassword = Str::random(30);
        //     $user->TokenInitialPassword()->save(
        //         new TokenInitialPassword([
        //             'token_initial_password' => $tokeninitialpassword,
        //             'status' => 0,
        //         ])
        //     );
        // $user->sendEmailRegister($user, $tokeninitialpassword);
        // });

        User::create([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'no_hp' => '12345678910',
            'role' => 3,
            'password' => Hash::make('password'),
        ])->TokenInitialPassword()->save(
            new TokenInitialPassword([
                'token_initial_password' => Str::random(30),
                'status' => 1,
            ])
        );
        // )->sendemailregister('admin@example.com', $tokeninitialpassword);

        User::create([
            'name' => 'Bimo Gempar Buono',
            'username' => 'bimogempar',
            'email' => 'bimo@example.com',
            'no_hp' => '08819417402',
            'role' => 2,
            'password' => Hash::make('password'),
        ])->TokenInitialPassword()->save(
            new TokenInitialPassword([
                'token_initial_password' => Str::random(30),
                'status' => 1,
            ])
        );
        // )->sendemailregister('bimo@example.com', $tokeninitialpassword);

        User::create([
            'name' => 'Asyraf Shalahudin',
            'username' => 'asyraf',
            'email' => 'asyraf@example.com',
            'no_hp' => '085606986725',
            'role' => 2,
            'password' => Hash::make('password'),
        ])->TokenInitialPassword()->save(
            new TokenInitialPassword([
                'token_initial_password' => Str::random(30),
                'status' => 1,
            ])
        );
        // )->sendemailregister('asyraf@example.com', $tokeninitialpassword);

        User::create([
            'name' => 'Firly Yoesfi',
            'username' => 'firly',
            'email' => 'firly@example.com',
            'no_hp' => '1234567891011',
            'role' => 2,
            'password' => Hash::make('password'),
        ])->TokenInitialPassword()->save(
            new TokenInitialPassword([
                'token_initial_password' => Str::random(30),
                'status' => 1,
            ])
        );
        // )->sendemailregister('firly@example.com', $tokeninitialpassword);

        User::create([
            'name' => 'Ade Novan Guliano',
            'username' => 'adenovan',
            'email' => 'adenovan@example.com',
            'no_hp' => '085607612503',
            'role' => 1,
            'password' => Hash::make('password'),
        ])->TokenInitialPassword()->save(
            new TokenInitialPassword([
                'token_initial_password' => Str::random(30),
                'status' => 1,
            ])
        );
        // )->sendemailregister('firly@example.com', $tokeninitialpassword);
    }
}
