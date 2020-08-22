<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Budget;
use Tests\SetupHelper;
use App\Models\Category;
use App\Models\SubCategory;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BudgetTest extends TestCase
{
    use RefreshDatabase, SetupHelper;

    public function setUp():void
    {
        parent::setUp();

        Artisan::call("passport:install");

        $user = $this->createUserWithRole('Admin');

        Passport::actingAs($user);
        
        $data = $this->insertCategoryAndSubcategory($user['id']);

        $budget = Budget::firstOrCreate([
            'title' => 'Test Budget Title',
            'category_id' => $data['category']['id'],
            'sub_category_id' => $data['sub']['id'],
            'user_id' => $user['id'],
            'dedicated_amount' => 900.00,
            'budget_for_month' => '2019-06-01'
        ]);

        $this->data = [
            'user' => $user,
            'budget' => $budget
        ];
    }

    public function testIndexMethod()
    {

        $response = $this->get('api/v1/budget');
        $response->assertStatus(200);
    }

    public function testStoreMethod()
    {
        $budgetCategory = factory(Category::class)->create(['creator' => $this->data['user']['id']]);
        $budgetSubCategory = factory(SubCategory::class)->create(['category_id' => $budgetCategory['id']]);
        $userId = $this->data['user']['id'];
        $data = factory(Budget::class)->make(['user_id'=> $userId, 'category_id' => $budgetCategory, 'sub_category_id' => $budgetSubCategory['id']]);

        $response = $this->post('api/v1/budget', $data->toArray());
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

        $response = $this->put("api/v1/budget/{$id}", $data);
        $response->assertStatus(200);
    }

    public function testDeleteMethod()
    {
        $id = $this->data['budget']['id'];

        $response = $this->delete("api/v1/budget/{$id}");
        $response->assertStatus(200);
    }
}
