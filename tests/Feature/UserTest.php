<?php

namespace Tests\Feature;

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
}
