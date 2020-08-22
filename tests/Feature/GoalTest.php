<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Goal;
use App\Models\Role;
use App\Models\User;
use Tests\SetupHelper;
use App\Models\GoalCategory;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GoalTest extends TestCase
{
    use RefreshDatabase, SetupHelper;

    public function setUp() :void
    {
        parent::setUp();

        Artisan::call("passport:install");

        $user = $this->createUserWithRole('Owner');

        Passport::actingAs($user);
        $goalCategory = factory(GoalCategory::class)->create();

        $goal = factory(Goal::class, 1)->create(['goal_categories_id' => $goalCategory['id'], 'user_id' => $user['id']]);

        $this-> data= [
            'user' => $user->toArray(),
            'goal' => $goal->toArray()
        ];
    }

    public function testIndexMethod()
    {


        $response = $this->get('api/v1/goal');
        $response->assertStatus(200);
    }

    public function testIndexMethodDifferentUserAccess()
    {
        $role = Role::firstOrCreate(['role' => 'Owner']);
        $user2 = factory(User::class)->create();
        $user2->roles()->attach($role['id'], ['approved' => 1]);

        Passport::actingAs($user2);

        $response = $this->get('api/v1/goal');
        $response->assertStatus(200);
        $response->assertExactJson([
            'data' => [],
            'message' => 'Ok',
            'status_code' => 200,
            'success' => true
        ]);
    }

    public function testIndexMethodSuperAdminCanViewSpecificUserGoal()
    {
        $role = Role::firstOrCreate(['role' => 'Super admin']);
        $superAdminUser = factory(User::class)->create();
        $superAdminUser->roles()->attach($role['id'], ['approved' => 1]);

        Passport::actingAs($superAdminUser);
        $id = $this->data['user']['id'];


        $response = $this->get("api/v1/goal?user_id={$id}");
        $response->assertJson([]); //put in the json structure
        $response->assertStatus(200);
    }

    public function testStoreMethod()
    {
        $goalCategory = factory(GoalCategory::class)->create();
        $userId = $this->data['user']['id'];
        $data = factory(Goal::class)->make(['user_id' => $userId, 'goal_categories_id' => $goalCategory['id']]);


        $response = $this->post('api/v1/goal', $data->toArray());
        $response->assertStatus(201);
    }

    public function testUpdateMethod()
    {
        $id = $this->data['goal'][0]['id'];
        $newGoalCategory = factory(GoalCategory::class)->create();
        $data = [
            'title' => 'updated title',
            'description' => 'update description',
            'goal_categories_id' => $newGoalCategory['id']
        ];


        $response = $this->put("api/v1/goal/{$id}", $data);
        $response->assertStatus(200);
    }

    public function testUpdateMethodUserDoesNotOwnThisGoal()
    {
        $role = Role::firstOrCreate(['role' => 'Owner']);
        $anotherUser = factory(User::class)->create();
        $anotherUser->roles()->attach($role['id'], ['approved' => 1]);

        Passport::actingAs($anotherUser);
        $newGoalCategory = factory(GoalCategory::class)->create();
        $data = [
            'title' => 'updated title',
            'description' => 'update description',
            'goal_categories_id' => $newGoalCategory['id']
        ];
        $id = $this->data['goal'][0]['id'];


        $response = $this->put("api/v1/goal/{$id}", $data);
        $response->assertStatus(403);
    }

    public function testShowMethod()
    {
        $id = $this->data['goal'][0]['id'];

        $response = $this->get("api/v1/goal/{$id}");
        $response->assertStatus(200);

    }

    public function testShowMethodSuperAdminCanGetSpecificUserGoal()
    {
        $role = Role::firstOrCreate(['role' => 'Super admin']);
        $superAdmin = factory(User::class)->create();
        $superAdmin->roles()->attach($role['id'], ['approved' => 1]);

        Passport::actingAs($superAdmin);
        $id = $this->data['goal'][0]['id'];
        $userId = $this->data['user']['id'];


        $response = $this->get("api/v1/goal/{$id}?user_id=$userId");
        $response->assertStatus(200);
    }

    public function testShowMethodGoalNotFound()
    {
        $id = 99999999;


        $response = $this->get("api/v1/goal/{$id}");
        $response->assertStatus(404);
    }


    public function testDeleteMethod()
    {
        $id = $this->data['goal'][0]['id'];

        $response = $this->delete("api/v1/goal/{$id}");
        $response->assertStatus(200);
    }

    public function testDeleteMethodUserDoesNotOwnThisGoal()
    {
        $id = $this->data['goal'][0]['id'];
        $role = Role::firstOrCreate(['role' => 'Owner']);
        $anotherUser = factory(User::class)->create();
        $anotherUser->roles()->attach($role['id'], ['approved' => 1]);

        Passport::actingAs($anotherUser);

        $response = $this->delete("api/v1/goal/{$id}");
        $response->assertStatus(403);
    }

}
