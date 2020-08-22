<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use Tests\SetupHelper;
use App\Models\Category;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BudgetCategoryTest extends TestCase
{
    use RefreshDatabase, SetupHelper;

    public function setUp() :void
    {
        parent::setUp();

        Artisan::call("passport:install");

        $user = $this->createUserWithRole('Admin');

        Passport::actingAs($user);

        $category = Category::firstOrCreate([
            'title' => 'Test Category',
            'creator' =>$user['id']
        ]);
        $this->data = [
            'category' => $category,
            'user' => $user
        ];
       
    }

    public function testIndexMethod()
    {
        $response = $this->get('api/v1/category');
        $response->assertStatus(200);
    }

    public function testStoreMethod()
    {
        $data = [
            'title' => 'some test category',
            'creator' => $this->data['user']['id']
        ];

        $response = $this->post('api/v1/category', $data);
        $response->assertStatus(201);
    }

    public function testShowMethod()
    {
        $id = $this->data['category']['id'];

        $response = $this->get("api/v1/category/{$id}");
        $response->assertStatus(200);
    }

    public function testUpdateMethod()
    {
        $id = $this->data['category']['id'];
        $data = [
            'title' => 'updated category'
        ];

        $response = $this->put("api/v1/category/{$id}", $data);
        $response->assertStatus(200);
    }

    public function testDeleteMethod()
    {
        $id = $this->data['category']['id'];

        $response = $this->delete("api/v1/category/{$id}");
        $response->assertStatus(200);
    }
}
