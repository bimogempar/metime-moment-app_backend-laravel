<?php

namespace Tests\Feature;

use App\Models\Package;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PackageTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCreatePackage()
    {
        $user = User::where('username', 'admin')->first();
        Sanctum::actingAs($user, ['*']);

        $response = $this->json('POST', '/api/packages/store', [
            'name' => 'Package 1',
            'price' => '100',
            'package_list' => [
                [
                    'name' => 'Package 1',
                    'price' => '100',
                ],
                [
                    'name' => 'Package 2',
                    'price' => '200',
                ],
            ],
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'package'
        ]);
    }

    public function testUpdatePackage()
    {
        $user = User::where('username', 'admin')->first();
        Sanctum::actingAs($user, ['*']);

        $idPackage = Package::latest()->first()->id;

        $response = $this->json('POST', '/api/packages/' . $idPackage . '/update/', [
            'name' => 'Package 1 Updated',
            'price' => '100',
            'package_list' => [
                [
                    'name' => 'Package 1',
                    'price' => '100',
                ],
                [
                    'name' => 'Package 2',
                    'price' => '200',
                ],
            ],
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
        ]);
    }

    public function testDestroyPackage()
    {
        $user = User::where('username', 'admin')->first();
        Sanctum::actingAs($user, ['*']);

        $idPackage = Package::latest()->first()->id;

        $response = $this->json('DELETE', '/api/packages/' . $idPackage . '/delete/');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
        ]);
    }
}
