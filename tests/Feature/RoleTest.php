<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class RoleTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;

    public function setUp() :void
    {
        parent::setUp();

        $role = Role::firstOrCreate([
            'role' => 'Test Role',

        ]);

        $this->role = $role;
    }

    public function getResponse($method, $url, $data = [])
    {
        return $this->json($method, $url, $data);
    }

    public function testIndexMethod()
    {
        $response = $this->getResponse('GET', 'api/v1/role');
        $response->assertStatus(200);
    }

    public function testStoreMethod()
    {
        $data = [
            'role' => 'some test role',
        ];
        $response = $this->getResponse('POST', 'api/v1/role', $data);
        $response->assertStatus(201);
    }

    public function testShowMethod()
    {
        $id = $this->role->id;
        $response = $this->getResponse('GET', "api/v1/role/{$id}");
        $response->assertStatus(200);
    }

    public function testUpdateMethod()
    {
        $id = $this->role->id;
        $data = [
            'role' => 'updated role'
        ];

        $response = $this->getResponse('PUT', "api/v1/role/{$id}", $data);
        $response->assertStatus(200);
    }
}
