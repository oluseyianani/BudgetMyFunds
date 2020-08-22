<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use Tests\SetupHelper;
use App\Models\GoalCategory;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GoalCategoryTest extends TestCase
{
    use RefreshDatabase, SetupHelper;

    public function setUp() :void
    {
        parent::setUp();

        Artisan::call("passport:install");

        $user = $this->createUserWithRole('Admin');

        Passport::actingAs($user);

        $goalCategory = GoalCategory::firstOrCreate([
            'title' => 'Test Business'
        ]);

        $this->data = [
            'user' => $user->toArray(),
            'goalCategory' => $goalCategory->toArray()
        ];
    }

    public function testIndexMethod()
    {

        $response = $this->get('api/v1/goal/category');
        $response->assertStatus(200);
    }

    public function testIndexMethodNonAdminUser()
    {
        $role = Role::create(['role' => 'Owner']);
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $user->roles()->attach($role['id'], ['approved' => 1]);


        $response = $this->get('api/v1/goal/category');
        $response->assertStatus(403);
    }

    public function testStoreMethod()
    {
        $data = [
            'title' => 'Test Goal Category',
        ];

        $response = $this->post('api/v1/goal/category', $data);
        $response->assertStatus(201);
    }

    public function testStoreMethodNonAdminUser()
    {
        $role = Role::create(['role' => 'Owner']);
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $user->roles()->attach($role['id'], ['approved' => 1]);

        $data = [
            'title' => 'Test Goal Category2',
        ];


        $response = $this->post('api/v1/goal/category', $data);
        $response->assertStatus(403);
    }

    public function testShowMethod()
    {
        $id = $this->data['goalCategory']['id'];

        $response = $this->get("api/v1/goal/category/{$id}");
        $response->assertStatus(200);
    }

    public function testShowMethodNonAdminUser()
    {
        $role = Role::create(['role' => 'Owner']);
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $user->roles()->attach($role['id'], ['approved' => 1]);
        $id = $this->data['goalCategory']['id'];


        $response = $this->get("api/v1/goal/category/{$id}");
        $response->assertStatus(403);
    }

    public function testUpdateMethod()
    {
        $id = $this->data['goalCategory']['id'];
        $data = [
            'title' => 'updated Goal category'
        ];

        $response = $this->put("api/v1/goal/category/{$id}", $data);
        $response->assertStatus(200);
    }

    public function testUpdateMethodNonAdminUser()
    {
        $id = $this->data['goalCategory']['id'];
        $data = [
            'title' => 'updated Goal category'
        ];
        $role = Role::create(['role' => 'Owner']);
        $user = factory(User::class)->create();

        Passport::actingAs($user);
        $user->roles()->attach($role['id'], ['approved' => 1]);


        $response = $this->put("api/v1/goal/category/{$id}", $data);
        $response->assertStatus(403);
    }

    public function testDeleteMethod()
    {
        $id = $this->data['goalCategory']['id'];
        $response = $this->delete("api/v1/goal/category/{$id}");
        $response->assertStatus(200);
    }

    public function testDeleteMethodNonAdminUser()
    {
        $id = $this->data['goalCategory']['id'];
        $role = Role::create(['role' => 'Owner']);
        $user = factory(User::class)->create();

        Passport::actingAs($user);
        $user->roles()->attach($role['id'], ['approved' => 1]);

        $response = $this->delete("api/v1/goal/category/{$id}");
        $response->assertStatus(403);
    }
}
