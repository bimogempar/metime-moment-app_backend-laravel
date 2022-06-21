<?php

namespace Tests\Feature;

use App\Models\Package;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Storage;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    public function testStoreProject()
    {
        // User must authenticated when load all projects
        $user = User::where('username', 'admin')->first();
        Sanctum::actingAs($user, ['*']);

        // get package
        $package = Package::first();

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

    public function testSearchProject()
    {
        // User must authenticated when load all projects
        $user = User::where('username', 'admin')->first();
        Sanctum::actingAs($user, ['*']);

        $response = $this->get('/api/projects/?s=test')
            ->assertStatus(200)
            ->assertJsonStructure([
                'total',
                'page',
                'last_page',
                'data',
            ]);
    }

    public function testUpdateProject()
    {
        // User must authenticated when load all projects
        $user = User::where('username', 'admin')->first();
        Sanctum::actingAs($user, ['*']);

        $project = Project::find(25);

        $assignment_user = [1, 2, 4];
        $project->users()->sync($assignment_user);

        $response = $this->post('/api/projects/update/' . $project->slug, [
            'client' => 'Test new client project',
            'date' => '2022-05-28',
            'time' => '23:27:27',
            'location' => 'Test new location',
            'status' => '1',
            'phone_number' => '08819417402',
            'package_id' => 3,
        ])
            ->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'project',
            ]);
    }

    public function testGetListContentDrive()
    {
        // User must authenticated when load all projects
        $user = User::where('username', 'admin')->first();
        Sanctum::actingAs($user, ['*']);

        $project = Project::with('users', 'features', 'progress', 'package.package_list')->latest()->first();

        $response = $this->get('/api/projects/' . $project->slug)
            ->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'project',
                'dir',
                'files_gdrive'
            ]);
    }

    public function testPrintInvoice()
    {
        // User must authenticated when load all projects
        $user = User::where('username', 'admin')->first();
        Sanctum::actingAs($user, ['*']);

        $project = Project::with('users', 'features', 'progress', 'package.package_list')->latest()->first();

        $response = $this->get('/api/projects/' . $project->slug . '/get-project-pdf')
            ->assertStatus(200);
    }
}
