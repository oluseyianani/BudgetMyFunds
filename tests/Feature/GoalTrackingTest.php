<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Goal;
use App\Models\Role;
use App\Models\User;
use Tests\SetupHelper;
use App\Models\GoalCategory;
use App\Models\GoalTracking;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GoalTrackingTest extends TestCase
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

        $goalTracking = factory(GoalTracking::class, 3)->create(['goal_id' => $goal[0]['id']]);
        $this->data = [
            'user' => $user->toArray(),
            'goalTracking' => $goalTracking->toArray(),
            'goalCategory' => $goalCategory->toArray(),
            'goal' => $goal->toArray()
        ];
   }

   public function testStoreMethod()
    {
        $goalId = $this->data['goal'][0]['id'];

        $data = ['amount_contributed' => 456676];
        $response = $this->post("api/v1/goal/{$goalId}/tracking", $data);
        $response->assertStatus(201);
    }

    public function testStoreMethodGoalNotForUser()
    {
        $role = Role::firstOrCreate(['role' => 'Owner']);
        $anotherUser = factory(User::class)->create();
        $anotherUser->roles()->attach($role['id'], ['approved' => 1]);

        Passport::actingAs($anotherUser);

        $goalId = $this->data['goal'][0]['id'];
        $data = ['amount_contributed' => 456676];


        $response = $this->post("api/v1/goal/{$goalId}/tracking", $data);
        $response->assertStatus(403);
    }

    public function testUpdateMethod()
    {
        $goalId = $this->data['goal'][0]['id'];
        $goalTrackingId = $this->data['goalTracking'][0]['id'];
        $data = ['amount_contributed' => 456676, 'date_contributed' => '2005-01-30 00:00:01'];

        $response = $this->put("api/v1/goal/{$goalId}/tracking/{$goalTrackingId}", $data);
        $response->assertStatus(200);
    }

    public function testUpdateMethodGoalNotForUser()
    {
        $role = Role::firstOrCreate(['role' => 'Owner']);
        $anotherUser = factory(User::class)->create();
        $anotherUser->roles()->attach($role['id'], ['approved' => 1]);

        Passport::actingAs($anotherUser);
        $goalId = $this->data['goal'][0]['id'];
        $goalTrackingId = $this->data['goalTracking'][0]['id'];
        $data = ['amount_contributed' => 456676, 'date_contributed' => '2005-01-30 00:00:01'];


        $response = $this->put("api/v1/goal/{$goalId}/tracking/{$goalTrackingId}", $data);
        $response->assertStatus(403);
    }

    public function testDeleteMethod()
    {
        $goalId = $this->data['goal'][0]['id'];
        $goalTrackingId = $this->data['goalTracking'][0]['id'];
        
        $response = $this->delete("api/v1/goal/{$goalId}/tracking/{$goalTrackingId}");
        $response->assertStatus(200);
    }

    public function testDeleteMethodGoalNotForUser()
    {
        $role = Role::firstOrCreate(['role' => 'Owner']);
        $anotherUser = factory(User::class)->create();
        $anotherUser->roles()->attach($role['id'], ['approved' => 1]);

        Passport::actingAs($anotherUser);
        $goalId = $this->data['goal'][0]['id'];
        $goalTrackingId = $this->data['goalTracking'][0]['id'];

        $response = $this->delete("api/v1/goal/{$goalId}/tracking/{$goalTrackingId}");
        $response->assertStatus(403);
    }
}
