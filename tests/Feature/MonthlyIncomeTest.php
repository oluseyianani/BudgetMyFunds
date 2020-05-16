<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\MonthlyIncome;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MonthlyIncomeTest extends TestCase
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

        $monthlyIncome = factory(MonthlyIncome::class)->create([
            'beneficiary' => $user['id'],
            'creator' => $user['id'],
        ]);

        $this->data = [
            'user' => $user,
            'monthlyIncome' => $monthlyIncome->toArray()
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


        $response = $this->getResponse('GET', 'api/v1/income', $token);
        $response->assertStatus(200);
    }

    public function testStoreMethod()
    {
        $monthlyIncome = factory(MonthlyIncome::class)->make([
            'beneficiary' => $this->data['user']['id'],
            'creator' => $this->data['user']['id']
        ]);
        $token = $this->data['user']['api_token'];


        $response = $this->getResponse('POST', 'api/v1/income', $token, $monthlyIncome->toArray());
        $response->assertStatus(201);
    }

    public function testUpdateMethod()
    {
        $id = $this->data['monthlyIncome']['id'];
        $userId = $this->data['user']['id'];
        $data = [
            'amount' => 1000.00,
            'date_received' => '2019-06-01',
            'beneficiary'=> $userId,
            'income_source' => 'Test Source'
        ];
        $token = $this->data['user']['api_token'];


        $response = $this->getResponse('PUT', "api/v1/income/{$id}", $token, $data);
        $response->assertStatus(200);
    }

    public function testDeleteMethod()
    {
        $id = $this->data['monthlyIncome']['id'];
        $token = $this->data['user']['api_token'];


        $response = $this->getResponse('DELETE', "api/v1/income/{$id}", $token);
        $response->assertStatus(200);
    }
}
