<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Budget;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BudgetTest extends TestCase
{
    use RefreshDatabase;

    public function setUp():void
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
            'creator' =>$user['id']
        ]);

        $subCategory = SubCategory::firstOrCreate([
            'sub_title' => 'Test SubCategory',
            'category_id' => $category['id']
        ]);

        $budget = Budget::firstOrCreate([
            'title' => 'Test Budget Title',
            'category_id' => $category['id'],
            'sub_category_id' => $subCategory['id'],
            'user_id' => $user['id'],
            'dedicated_amount' => 900.00,
            'budget_for_month' => '2019-06-01'
        ]);

        $this->data = [
            'category' => $category,
            'sub_category' => $subCategory,
            'user' => $user,
            'budget' => $budget
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


        $response = $this->getResponse('GET', 'api/v1/budget', $token);
        $response->assertStatus(200);
    }

    public function testStoreMethod()
    {
        $budgetCategory = factory(Category::class)->create(['creator' => $this->data['user']['id']]);
        $budgetSubCategory = factory(SubCategory::class)->create(['category_id' => $budgetCategory['id']]);
        $userId = $this->data['user']['id'];
        $data = factory(Budget::class)->make(['user_id'=> $userId, 'category_id' => $budgetCategory, 'sub_category_id' => $budgetSubCategory['id']]);
        $token = $this->data['user']['api_token'];


        $response = $this->getResponse('POST', 'api/v1/budget', $token, $data->toArray());
        $response->assertStatus(201);
    }

    public function testUpdateMethod()
    {
        $id = $this->data['budget']['id'];
        $newCategory = factory(Category::class)->create(['creator' => $this->data['user']['id']]);
        $newSubCategory = factory(SubCategory::class)->create(['category_id' => $newCategory['id']]);
        $userId = $this->data['user']['id'];
        $data = [
            'title' => 'updated title',
            'category_id' => $newCategory['id'],
            'sub_category_id' => $newSubCategory['id'],
            'dedicated_amount' => 1000.00,
            'budget_for_month' => '2019-06-01',
            'user_id'=> $userId
        ];
        $token = $this->data['user']['api_token'];


        $response = $this->getResponse('PUT', "api/v1/budget/{$id}", $token, $data);
        $response->assertStatus(200);
    }

    public function testDeleteMethod()
    {
        $id = $this->data['budget']['id'];
        $token = $this->data['user']['api_token'];


        $response = $this->getResponse('DELETE', "api/v1/budget/{$id}", $token);
        $response->assertStatus(200);
    }
}
