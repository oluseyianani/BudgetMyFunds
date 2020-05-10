<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\GoalCategory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GoalCategoryTest extends TestCase
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
            'email_verified_at' => now()
        ])->generateToken();

        $user->roles()->attach($role['id'], ['approved' => 1]);

        $goalCategory = GoalCategory::firstOrCreate([
            'title' => 'Test Business'
        ]);

        $this->data = [
            'user' => $user->toArray(),
            'goalCategory' => $goalCategory->toArray()
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


        $response = $this->getResponse('GET', 'api/v1/goal/category', $token);
        $response->assertStatus(200);
    }

    public function testIndexMethodNonAdminUser()
    {
        $role = Role::create(['role' => 'Owner']);
        $user = factory(User::class)->create();
        $user->roles()->attach($role['id'], ['approved' => 1]);


        $response = $this->getResponse('GET', 'api/v1/goal/category', $user['api_token']);
        $response->assertStatus(403);
    }

    public function testStoreMethod()
    {
        $data = [
            'title' => 'Test Goal Category',
        ];
        $token = $this->data['user']['api_token'];


        $response = $this->getResponse('POST', 'api/v1/goal/category', $token, $data);
        $response->assertStatus(201);
    }

    public function testStoreMethodNonAdminUser()
    {
        $role = Role::create(['role' => 'Owner']);
        $user = factory(User::class)->create();
        $user->roles()->attach($role['id'], ['approved' => 1]);

        $data = [
            'title' => 'Test Goal Category2',
        ];


        $response = $this->getResponse('POST', 'api/v1/goal/category', $user['api_token'], $data);
        $response->assertStatus(403);
    }

    public function testShowMethod()
    {
        $id = $this->data['goalCategory']['id'];
        $token = $this->data['user']['api_token'];


        $response = $this->getResponse('GET', "api/v1/goal/category/{$id}", $token);
        $response->assertStatus(200);
    }

    public function testShowMethodNonAdminUser()
    {
        $role = Role::create(['role' => 'Owner']);
        $user = factory(User::class)->create();
        $user->roles()->attach($role['id'], ['approved' => 1]);
        $id = $this->data['goalCategory']['id'];


        $response = $this->getResponse('GET', "api/v1/goal/category/{$id}", $user['api_token']);
        $response->assertStatus(403);
    }

    public function testUpdateMethod()
    {
        $id = $this->data['goalCategory']['id'];
        $data = [
            'title' => 'updated Goal category'
        ];
        $token = $this->data['user']['api_token'];


        $response = $this->getResponse('PUT', "api/v1/goal/category/{$id}",$token, $data);
        $response->assertStatus(200);
    }

    public function testUpdateMethodNonAdminUser()
    {
        $id = $this->data['goalCategory']['id'];
        $data = [
            'title' => 'updated Goal category'
        ];
        $role = Role::create(['role' => 'Owner']);
        $user = factory(User::class)->create();
        $user->roles()->attach($role['id'], ['approved' => 1]);


        $response = $this->getResponse('PUT', "api/v1/goal/category/{$id}",$user['api_token'], $data);
        $response->assertStatus(403);
    }

    public function testDeleteMethod()
    {
        $id = $this->data['goalCategory']['id'];
        $token = $this->data['user']['api_token'];
        $response = $this->getResponse('DELETE', "api/v1/goal/category/{$id}", $token);
        $response->assertStatus(200);
    }

    public function testDeleteMethodNonAdminUser()
    {
        $id = $this->data['goalCategory']['id'];
        $role = Role::create(['role' => 'Owner']);
        $user = factory(User::class)->create();
        $user->roles()->attach($role['id'], ['approved' => 1]);

        
        $response = $this->getResponse('DELETE', "api/v1/goal/category/{$id}", $user['api_token']);
        $response->assertStatus(403);
    }

}
