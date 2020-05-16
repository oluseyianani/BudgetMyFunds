<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Category;
use App\Models\UserCategory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BudgetUserCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() :void
    {
        parent::setUp();

        $role = Role::create([
            'role' => 'Owner'
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
            'creator' => $user['id']
        ]);

        $this->data = [
            'user' => $user,
            'category' => $category
        ];
    }

    public function getResponse($method, $url, $token, $data = [])
    {
        return $this->withHeaders([
            'Authorization' => "Bearer " . $token,
        ])->json($method, $url, $data);
    }

    public function testStoreMethod()
    {
        $data = [
            'title' => 'My category test'
        ];

        $token = $this->data['user']['api_token'];
        $categoryId = $this->data['category']['id'];

        $response = $this->getResponse('POST', "api/v1/category/{$categoryId}/usercategory", $token, $data);
        $response->assertStatus(201);
    }

    public function testUpdateMethod()
    {
        $userCategoryId = $this->createUserCategory()["id"];
        $categoryId = $this->data['category']['id'];
        $token = $this->data['user']['api_token'];
        $data = [
            'title' => 'Updated User Category Test'
        ];

        $response = $this->getResponse('PUT', "api/v1/category/{$categoryId}/usercategory/{$userCategoryId}", $token, $data);
        $response->assertStatus(200);
        $this->forceDeleteSubCategory($userCategoryId);
    }

    public function testDeleteMethod()
    {
        $token = $this->data['user']['api_token'];
        $userCategoryId = $this->createUserCategory()['id'];
        $categoryId = $this->data['category']['id'];

        $response = $this->getResponse('DELETE', "api/v1/category/{$categoryId}/usercategory/{$userCategoryId}", $token);
        $response->assertStatus(200);
    }

    public function createUserCategory()
    {
        return UserCategory::create([
            'title' => 'Test UserCategory',
            'category_id' => $this->data['category']['id'],
            'user_id' => $this->data['user']['id']
        ]);
    }

    public function forceDeleteSubCategory($userCategoryId)
    {
        return UserCategory::find($userCategoryId)->forceDelete();
    }
}
