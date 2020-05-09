<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Budget;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\BudgetTracking;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BudgetTrackingTest extends TestCase
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
            'role_id' => $role['id'],
            'email_verified_at' => now()
        ])->generateToken();

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

        $budgetTracking = factory(BudgetTracking::class, 5)->create([
           'spender' => $user['id'],
           'budget_id' => $budget['id']
        ]);

        $this->data = [
            'category' => $category,
            'sub_category' => $subCategory,
            'user' => $user,
            'budget' => $budget,
            'budgetTracking' => $budgetTracking->toArray()
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
        $budgetId = $this->data['budget']['id'];


        $response = $this->getResponse('GET', "api/v1/budget/{$budgetId}/tracking", $token);
        $response->assertStatus(200);
    }

    public function testStoreMethod()
    {
        $userId = $this->data['user']['id'];
        $budgetId = $this->data['budget']['id'];
        $data = factory(BudgetTracking::class)->make(['spender'=> $userId]);
        $token = $this->data['user']['api_token'];


        $response = $this->getResponse('POST', "api/v1/budget/{$budgetId}/tracking", $token, $data->toArray());
        $response->assertStatus(201);
    }

    public function testUpdateMethod()
    {
        $id = $this->data['budget']['id'];
        $trackingId = $this->data['budgetTracking'][0]['id'];
        $userId = $this->data['user']['id'];
        $data = [
            'amount_spent' => 1000.00,
            'reason_for_spend' => 'Updated Reason for spend',
            'spender' => $userId
        ];
        $token = $this->data['user']['api_token'];


        $response = $this->getResponse('PUT', "api/v1/budget/{$id}/tracking/{$trackingId}", $token, $data);
        $response->assertStatus(200);
    }

    public function testDeleteMethod()
    {
        $id = $this->data['budget']['id'];
        $trackingId = $this->data['budgetTracking'][0]['id'];
        $token = $this->data['user']['api_token'];


        $response = $this->getResponse('DELETE', "api/v1/budget/{$id}/tracking/{$trackingId}", $token);
        $response->assertStatus(200);
    }
}
