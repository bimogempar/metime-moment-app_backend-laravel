<?php

namespace Tests\Feature;

use App\Models\Package;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function testLoginUser()
    {
        $response = $this->post('/api/login', [
            'username' => 'admin',
            'password' => 'password'
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'access_token',
            'message',
            'user_logged_in'
        ]);
    }

    public function testSetInitPass()
    {
        $user = User::first();
        $token = $user->TokenInitialPassword()->first();
        $response = $this->post('/api/set-pass', [
            'token_initial_password' => $token,
            'password' => 'password'
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
        ]);
    }

    public function testCreateNewUser()
    {
        $user = User::where('username', 'admin')->first();
        Sanctum::actingAs($user, ['*']);

        $response = $this->post('/api/register', [
            'name' => 'Test New User',
            'email' => 'testnewuser@example.com',
            'username' => 'testnewuser',
            'role' => 1
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'token_initial_password',
                'user'
            ]);
    }

    public function testUpdateUser()
    {
        $user = User::where('username', 'admin')->first();
        Sanctum::actingAs($user, ['*']);

        $response = $this->post('api/user/' . $user->username . '/updateprofile', [
            'name' => 'Admin Edited',
            'username' => 'admin',
            'no_hp' => '081234567890',
            'role' => 1,
            'email' => 'admin@example.com'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'user'
            ]);
    }

    public function testGetUserProfile()
    {
        $user = User::where('username', 'admin')->first();
        Sanctum::actingAs($user, ['*']);

        $response = $this->get('api/user/' . $user->username);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'user',
                'projects',
                'totalProject',
                'last_page',
                'authUser',
            ]);
    }

    public function testUserGotEmailNotification()
    {
        $user = User::where('username', 'admin')->first();
        Sanctum::actingAs($user, ['*']);

        // get package
        $package = Package::first();

        // User where id 1,2,3 got email notification
        $response = $this->post('/api/projects/store', [
            'client' => 'Test new client project',
            'date' => '2022-05-28',
            'time' => '23:27:27',
            'location' => 'Test new location',
            'status' => '1',
            'phone_number' => '08819417402',
            'package_id' => $package->id,
            'assignment_user' => json_encode([1, 2, 3])
        ])
            ->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'project',
                'gdrive_path'
            ]);
    }
}
