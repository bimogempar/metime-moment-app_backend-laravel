<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProgressTest extends TestCase
{
    public function testStoreProgress()
    {
        // User must authenticated when load all projects
        $user = User::where('username', 'admin')->first();
        Sanctum::actingAs($user, ['*']);

        $project = Project::first();
        $user = User::first();
        $response = $this->post('/api/projects/' . $project->id . '/progress/store', [
            'description' => 'Test create new progress',
            'user_id' => $user->id,
            'project_id' => $project->id,
        ]);

        $project = Project::with('progress', 'users')->find($project->id);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'project',
            ]);
    }

    public function testDestroyProgress()
    {
        // User must authenticated when load all projects
        $user = User::where('username', 'admin')->first();
        Sanctum::actingAs($user, ['*']);

        $project = Project::first();
        $progress = $project->progress->first();

        $response = $this->delete('/api/projects/' . $project->id . '/progress/' . $progress->id);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'project',
            ]);
    }
}
