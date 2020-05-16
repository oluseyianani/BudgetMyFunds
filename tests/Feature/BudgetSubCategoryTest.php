<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class BudgetSubCategoryTest extends TestCase
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
            'sub_title' => 'Budget Sub Category Test'
        ];

        $token = $this->data['user']['api_token'];
        $categoryId = $this->data['category']['id'];


        $response = $this->getResponse('POST', "api/v1/category/{$categoryId}/subcategory", $token, $data);
        $response->assertStatus(201);
    }

    public function testUpdateMethod()
    {
        $subCategoryId = $this->createSubCategory()["id"];
        $categoryId = $this->data['category']['id'];
        $token = $this->data['user']['api_token'];
        $data = [
            'sub_title' => 'Updated Sub Category Test'
        ];


        $response = $this->getResponse('PUT', "api/v1/category/{$categoryId}/subcategory/{$subCategoryId}", $token, $data);
        $response->assertStatus(200);
        $this->forceDeleteSubCategory($subCategoryId);
    }

    public function testDeleteMethod()
    {
        $token = $this->data['user']['api_token'];
        $subCategoryId = $this->createSubCategory()['id'];
        $categoryId = $this->data['category']['id'];


        $response = $this->getResponse('DELETE', "api/v1/category/{$categoryId}/subcategory/{$subCategoryId}", $token);
        $response->assertStatus(200);
    }

    public function createSubCategory()
    {
        return SubCategory::create([
            'sub_title' => 'Test SubCategory',
            'category_id' => $this->data['category']['id']
        ]);
    }

    public function forceDeleteSubCategory($subCategoryId)
    {
        return SubCategory::find($subCategoryId)->forceDelete();
    }
}
