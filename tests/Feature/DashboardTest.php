<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    public function testDashboard()
    {
        // User must authenticated when load all projects
        $user = User::where('username', 'admin')->first();
        Sanctum::actingAs($user, ['*']);

        // Load all projects
        $response = $this->get('/api/projects');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'total',
            'page',
            'last_page',
            'data',
        ]);
    }
}
