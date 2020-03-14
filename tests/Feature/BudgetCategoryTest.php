<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class BudgetCategoryTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;

    public function setUp() :void
    {
        parent::setUp();

        $category = Category::firstOrCreate([
            'title' => 'Test Category',
            'creator' => User::firstOrCreate([
                'email' => 'test@test.com',
                'password' => 'password123'
            ])['id']
        ]);

        $this->category = $category;
    }

    public function getResponse($method, $url, $data = [])
    {
        return $this->json($method, $url, $data);
    }

    public function testIndexMethod()
    {
        $response = $this->getResponse('GET', 'api/v1/category');
        $response->assertStatus(200);
    }

    public function testStoreMethod()
    {
        $data = [
            'title' => 'some test category',
            'creator' => User::first()['id']
        ];
        $response = $this->getResponse('POST', 'api/v1/category', $data);
        $response->assertStatus(201);
    }

    public function testShowMethod()
    {
        $id = $this->category->id;
        $response = $this->getResponse('GET', "api/v1/category/{$id}");
        $response->assertStatus(200);
    }

    public function testUpdateMethod()
    {
        $id = $this->category->id;
        $data = [
            'title' => 'updated category'
        ];

        $response = $this->getResponse('PUT', "api/v1/category/{$id}", $data);
        $response->assertStatus(200);
    }

    public function testDeleteMethod()
    {
        $id = $this->category->id;

        $response = $this->getResponse('DELETE', "api/v1/category/{$id}");
        $response->assertStatus(200);
    }
}
