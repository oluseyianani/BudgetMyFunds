<?php

// namespace Tests\Feature;

// use Tests\TestCase;
// use App\Models\Goal;
// use App\Models\Role;
// use App\Models\User;
// use App\Models\GoalCategory;
// use App\Models\GoalTracking;
// use Illuminate\Foundation\Testing\WithFaker;
// use Illuminate\Foundation\Testing\RefreshDatabase;

// class GoalTrackingTest extends TestCase
// {
//    public function setUp() :void
//    {
//         parent::setUp();

//         $role = Role::create([
//             'role' => 'Owner'
//         ]);

//         $user = factory(User::class)->create(['role_id' => $role['id']]);

//         $goalCategory = factory(GoalCategory::class)->create();

//         $goal = factory(Goal::class, 1)->create(['goal_categories_id' => $goalCategory['id'], 'user_id' => $user['id']]);

//         $goalTracking = factory(GoalTracking::class, 3)->create(['goal_id' => $goal[0]['id']]);
//         $this->data = [
//             'user' => $user->toArray(),
//             'goalTracking' => $goalTracking->toArray(),
//             'goalCategory' => $goalCategory->toArray(),
//             'goal' => $goal->toArray()
//         ];
//    }

//    public function getResponse($method, $url, $token, $data = [])
//    {
//        return $this->withHeaders([
//            'Authorization' => "Bearer " . $token,
//        ])->json($method, $url, $data);
//    }

//    public function testStoreMethod()
//     {
//         $goalId = $this->data['goal'][0]['id'];
//         $token = $this->data['user']['api_token'];
//         $data = ['amount_contributed' => 456676];
//         $response = $this->getResponse('POST', "api/v1/goal/{$goalId}/tracking", $token, $data);
//         $response->assertStatus(201);
//     }

//     public function testStoreMethodGoalNotForUser()
//     {
//         $role = Role::firstOrCreate(['role' => 'Owner']);
//         $anotherUser = factory(User::class)->create(['role_id' => $role['id']]);
//         $goalId = $this->data['goal'][0]['id'];
//         $data = ['amount_contributed' => 456676];
//         $response = $this->getResponse('POST', "api/v1/goal/{$goalId}/tracking", $anotherUser['api_token'], $data);
//         $response->assertStatus(403);
//     }

//     public function testUpdateMethod()
//     {
//         $goalId = $this->data['goal'][0]['id'];
//         $token = $this->data['user']['api_token'];
//         $goalTrackingId = $this->data['goalTracking'][0]['id'];
//         $data = ['amount_contributed' => 456676, 'date_contributed' => '2005-01-30 00:00:01'];
//         $response = $this->getResponse('PUT', "api/v1/goal/{$goalId}/tracking/{$goalTrackingId}", $token, $data);
//         $response->assertStatus(200);
//     }

//     public function testUpdateMethodGoalNotForUser()
//     {
//         $role = Role::firstOrCreate(['role' => 'Owner']);
//         $anotherUser = factory(User::class)->create(['role_id' => $role['id']]);
//         $goalId = $this->data['goal'][0]['id'];
//         $goalTrackingId = $this->data['goalTracking'][0]['id'];
//         $data = ['amount_contributed' => 456676, 'date_contributed' => '2005-01-30 00:00:01'];
//         $response = $this->getResponse('PUT', "api/v1/goal/{$goalId}/tracking/{$goalTrackingId}", $anotherUser['api_token'], $data);
//         $response->assertStatus(403);
//     }

//     public function testDeleteMethod()
//     {
//         $goalId = $this->data['goal'][0]['id'];
//         $token = $this->data['user']['api_token'];
//         $goalTrackingId = $this->data['goalTracking'][0]['id'];
//         $response = $this->getResponse('DELETE', "api/v1/goal/{$goalId}/tracking/{$goalTrackingId}", $token);
//         $response->assertStatus(200);
//     }

//     public function testDeleteMethodGoalNotForUser()
//     {
//         $role = Role::firstOrCreate(['role' => 'Owner']);
//         $anotherUser = factory(User::class)->create(['role_id' => $role['id']]);
//         $goalId = $this->data['goal'][0]['id'];
//         $goalTrackingId = $this->data['goalTracking'][0]['id'];
//         $response = $this->getResponse('DELETE', "api/v1/goal/{$goalId}/tracking/{$goalTrackingId}", $anotherUser['api_token']);
//         $response->assertStatus(403);
//     }
// }
