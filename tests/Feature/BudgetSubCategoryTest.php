<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class BudgetSubCategoryTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;

    public function setUp() :void
    {
        parent::setUp();

        $user = User::firstOrCreate([
            'email' => 'test@test.com',
            'password' => 'password123'
        ]);

        $category = Category::firstOrCreate([
            'title' => 'Test Category',
            'creator' => $user['id']
        ]);

        $this->data = [
            'user' => $user,
            'category' => $category
        ];

    }

    public function getResponse($method, $url, $data = [])
    {
        return $this->json($method, $url, $data);
    }

    public function testStoreMethod()
    {
        $data = [
            'sub_title' => 'Budget Sub Category Test'
        ];

        $categoryId = $this->data['category']['id'];

        $response = $this->getResponse('POST', "api/v1/category/{$categoryId}/subcategory", $data);
        $response->assertStatus(201);
    }

    public function testUpdateMethod()
    {
        $subCategoryId = $this->createSubCategory()["id"];
        $categoryId = $this->data['category']['id'];
        $data = [
            'sub_title' => 'Updated Sub Category Test'
        ];

        $response = $this->getResponse('PUT', "api/v1/category/{$categoryId}/subcategory/{$subCategoryId}", $data);
        $response->assertStatus(201);
        $this->forceDeleteSubCategory($subCategoryId);

    }

    public function testDeleteMethod()
    {
        $subCategoryId = $this->createSubCategory()['id'];
        $categoryId = $this->data['category']['id'];

        $response = $this->getResponse('DELETE', "api/v1/category/{$categoryId}/subcategory/{$subCategoryId}");
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
