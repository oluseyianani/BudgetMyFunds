<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Goal;
use App\Models\Role;
use App\Models\User;
use App\Models\GoalCategory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GoalTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() :void
    {
        parent::setUp();

        $role = Role::create(['role' => 'Owner']);

        $user = factory(User::class)->create(['role_id' => $role['id']]);
        $goalCategory = factory(GoalCategory::class)->create();

        $goal = factory(Goal::class, 1)->create(['goal_categories_id' => $goalCategory['id'], 'user_id' => $user['id']]);

        $this-> data= [
            'user' => $user->toArray(),
            'goal' => $goal->toArray()
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
        $response = $this->getResponse('GET', 'api/v1/goal', $token);
        $response->assertStatus(200);
    }

    public function testIndexMethodDifferentUserAccess()
    {
        $role = Role::firstOrCreate(['role' => 'Owner']);
        $user2 = factory(User::class)->create(['role_id' => $role['id']]);
        $response = $this->getResponse('GET', 'api/v1/goal', $user2['api_token']);
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
        $superAdminUser = factory(User::class)->create(['role_id' => $role['id']]);
        $id = $this->data['user']['id'];
        $response = $this->getResponse('GET', "api/v1/goal?user_id={$id}", $superAdminUser['api_token']);
        $response->assertJson([]); //put in the json structure
        $response->assertStatus(200);
    }

    public function testStoreMethod()
    {
        $goalCategory = factory(GoalCategory::class)->create();
        $userId = $this->data['user']['id'];
        $data = factory(Goal::class)->make(['user_id' => $userId, 'goal_categories_id' => $goalCategory['id']]);
        $token = $this->data['user']['api_token'];
        $response = $this->getResponse('POST', 'api/v1/goal', $token, $data->toArray());
        //assert that this user has 2 goals in the database
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
        $token = $this->data['user']['api_token'];
        $response = $this->getResponse('PUT', "api/v1/goal/{$id}", $token, $data);
        $response->assertStatus(200);
    }

    public function testUpdateMethodUserDoesNotOwnThisGoal()
    {
        $role = Role::firstOrCreate(['role' => 'Owner']);
        $anotherUser = factory(User::class)->create(['role_id' => $role['id']]);
        $newGoalCategory = factory(GoalCategory::class)->create();
        $data = [
            'title' => 'updated title',
            'description' => 'update description',
            'goal_categories_id' => $newGoalCategory['id']
        ];
        $id = $this->data['goal'][0]['id'];
        $response = $this->getResponse('PUT', "api/v1/goal/{$id}",$anotherUser['api_token'], $data);
        $response->assertStatus(403);
    }

    public function testShowMethod()
    {
        $id = $this->data['goal'][0]['id'];
        $token = $this->data['user']['api_token'];
        $response = $this->getResponse('GET', "api/v1/goal/{$id}", $token);
        $response->assertStatus(200);

    }

    public function testShowMethodSuperAdminCanGetSpecificUserGoal()
    {
        $role = Role::firstOrCreate(['role' => 'Super admin']);
        $superAdmin = factory(User::class)->create(['role_id' => $role['id']]);
        $id = $this->data['goal'][0]['id'];
        $userId = $this->data['user']['id'];
        $response = $this->getResponse('GET', "api/v1/goal/{$id}?user_id=$userId", $superAdmin['api_token']);
        $response->assertStatus(200);
    }

    public function testShowMethodGoalNotFound()
    {
        $id = 99999999;
        $token = $this->data['user']['api_token'];
        $response = $this->getResponse('GET', "api/v1/goal/{$id}", $token);
        $response->assertStatus(404);
    }


    public function testDeleteMethod()
    {
        $id = $this->data['goal'][0]['id'];
        $token = $this->data['user']['api_token'];
        $response = $this->getResponse('DELETE', "api/v1/goal/{$id}", $token);
        $response->assertStatus(200);
    }

    public function testDeleteMethodUserDoesNotOwnThisGoal()
    {
        $id = $this->data['goal'][0]['id'];
        $role = Role::firstOrCreate(['role' => 'Owner']);
        $anotherUser = factory(User::class)->create(['role_id' => $role['id']]);
        $response = $this->getResponse('DELETE', "api/v1/goal/{$id}", $anotherUser['api_token']);
        $response->assertStatus(403);
    }

}
