<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use Tests\SetupHelper;
use App\Models\Category;
use App\Models\UserCategory;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BudgetUserCategoryTest extends TestCase
{
    use RefreshDatabase, SetupHelper;

    public function setUp() :void
    {
        parent::setUp();

        Artisan::call("passport:install");

        $user = $this->createUserWithRole('Owner');

        Passport::actingAs($user);

        $category = Category::firstOrCreate([
            'title' => 'Test Category',
            'creator' => $user['id']
        ]);

        $this->data = [
            'user' => $user,
            'category' => $category
        ];
    }

    public function testStoreMethod()
    {
        $data = [
            'title' => 'My category test'
        ];

        $categoryId = $this->data['category']['id'];

        $response = $this->post("api/v1/category/{$categoryId}/usercategory", $data);
        $response->assertStatus(201);
    }

    public function testUpdateMethod()
    {
        $userCategoryId = $this->createUserCategory()["id"];
        $categoryId = $this->data['category']['id'];
        $data = [
            'title' => 'Updated User Category Test'
        ];

        $response = $this->put("api/v1/category/{$categoryId}/usercategory/{$userCategoryId}", $data);
        $response->assertStatus(200);
        $this->forceDeleteSubCategory($userCategoryId);
    }

    public function testDeleteMethod()
    {
        $userCategoryId = $this->createUserCategory()['id'];
        $categoryId = $this->data['category']['id'];

        $response = $this->delete("api/v1/category/{$categoryId}/usercategory/{$userCategoryId}");
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
