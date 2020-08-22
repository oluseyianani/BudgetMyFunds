<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Budget;
use Tests\SetupHelper;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\BudgetTracking;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BudgetTrackingTest extends TestCase
{
    use RefreshDatabase, SetupHelper;

    public function setUp():void
    {
        parent::setUp();

        Artisan::call("passport:install");

        $user = $this->createUserWithRole('Admin');

        Passport::actingAs($user);

        $budget = $this->insertbudgetdata($user['id']);

        $budgetTracking = factory(BudgetTracking::class, 5)->create([
           'spender' => $user['id'],
           'budget_id' => $budget['id']
        ]);

        $this->data = [
            'user' => $user,
            'budget' => $budget,
            'budgetTracking' => $budgetTracking->toArray()
        ];
    }

    public function testIndexMethod()
    {
        $budgetId = $this->data['budget']['id'];


        $response = $this->get("api/v1/budget/{$budgetId}/tracking");
        $response->assertStatus(200);
    }

    public function testStoreMethod()
    {
        $userId = $this->data['user']['id'];
        $budgetId = $this->data['budget']['id'];
        $data = factory(BudgetTracking::class)->make(['spender'=> $userId]);

        $response = $this->post("api/v1/budget/{$budgetId}/tracking", $data->toArray());
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

        $response = $this->put("api/v1/budget/{$id}/tracking/{$trackingId}", $data);
        $response->assertStatus(200);
    }

    public function testDeleteMethod()
    {
        $id = $this->data['budget']['id'];
        $trackingId = $this->data['budgetTracking'][0]['id'];

        $response = $this->delete("api/v1/budget/{$id}/tracking/{$trackingId}");
        $response->assertStatus(200);
    }
}
