<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BudgetCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() :void
    {
        parent::setUp();

        $role = Role::create([
            'role' => 'Admin'
        ]);

        $user = User::firstOrCreate([
            'email' => 'test@test.com',
            'password' => 'password123',
            'phone' => '+23409012345534',
            'email_verified_at' => now()
        ])->generateToken();

        $user->roles()->attach($role['id'], ['approved' => 1]);

        $category = Category::firstOrCreate([
            'title' => 'Test Category',
            'creator' =>$user['id']
        ]);

        $this->data = [
            'category' => $category,
            'user' => $user
        ];
    }

    public function getResponse($method, $url, $token, $data = [])
    {
        return $this->withHeaders([
            'Authorization' => "Bearer " . $token,
        ])->json($method, $url, $data);
    }

    public function testIndexMethod()
    {
        $token = $this->data['user']['api_token'];


        $response = $this->getResponse('GET', 'api/v1/category', $token);
        $response->assertStatus(200);
    }

    public function testStoreMethod()
    {
        $data = [
            'title' => 'some test category',
            'creator' => $this->data['user']['id']
        ];
        $token = $this->data['user']['api_token'];


        $response = $this->getResponse('POST', 'api/v1/category', $token, $data);
        $response->assertStatus(201);
    }

    public function testShowMethod()
    {
        $id = $this->data['category']['id'];
        $token = $this->data['user']['api_token'];


        $response = $this->getResponse('GET', "api/v1/category/{$id}", $token);
        $response->assertStatus(200);
    }

    public function testUpdateMethod()
    {
        $id = $this->data['category']['id'];
        $data = [
            'title' => 'updated category'
        ];
        $token = $this->data['user']['api_token'];


        $response = $this->getResponse('PUT', "api/v1/category/{$id}", $token, $data);
        $response->assertStatus(200);
    }

    public function testDeleteMethod()
    {
        $id = $this->data['category']['id'];
        $token = $this->data['user']['api_token'];


        $response = $this->getResponse('DELETE', "api/v1/category/{$id}", $token);
        $response->assertStatus(200);
    }
}
