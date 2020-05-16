<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BudgetUserProfileTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() :void
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

        $this->data = [
            'user' => $user,
        ];
    }

    public function getResponse($method, $url, $token, $data = [])
    {
        return $this->withHeaders([
            'Authorization' => "Bearer " . $token,
        ])->json($method, $url, $data);
    }

    public function testStoreMethodWithPhotoUrl()
    {
        $token = $this->data['user']['api_token'];

        $response = $this->getResponse('POST', "api/v1/profile/", $token, ['photo_url' => 'https://picsum.photos/200/300.jpg']);
        $response->assertStatus(201);
    }


    public function testStoreMethodWithNames()
    {
        $data = ['first_name' => 'Oluseyi', 'last_name' => 'Test'];
        $token = $this->data['user']['api_token'];

        $response = $this->getResponse('POST', "api/v1/profile/", $token, $data);
        $response->assertStatus(201);
    }

    public function testStoreMethodWithOptionalData()
    {
        $data = ['gender' => 'M', 'date_of_birth' => '2001-01-13', 'postal_address' => 'sample address'];
        $token = $this->data['user']['api_token'];

        $response = $this->getResponse('POST', "api/v1/profile/", $token, $data);
        $response->assertStatus(201);
    }

    public function testStoreMethodWithInCorrectData()
    {
        $data = ['gender' => 'MF', 'date_of_birth' => '01-13-2001', 'postal_address' => 'sample address'];
        $token = $this->data['user']['api_token'];

        $response = $this->getResponse('POST', "api/v1/profile/", $token, $data);
        $response->assertStatus(422);
    }

    public function testShowMethod()
    {
        $token = $this->data['user']['api_token'];
        $userId = $this->data['user']['id'];
        $response = $this->getResponse('GET', "api/v1/profile/{$userId}", $token);
        $response->assertStatus(200);
    }
}
