<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() :void
    {
        parent::setUp();

        $role = Role::create([
            'role' => 'Owner'
        ]);

        $user = factory(User::class)->create();
        $user->roles()->attach($role['id'], ['approved' => 1]);

        $this->data = [
            'user' => $user
        ];

    }

    public function getResponse($method, $url, $data = [])
    {
        return $this->json($method, $url, $data);
    }

    public function testRegisterMethod()
    {
        $userData = [
            'email' => 'test@email.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'phone_number' => 9012345678
        ];


        $response = $this->getResponse('POST', 'api/v1/auth/register', $userData);
        $response->assertStatus(201);
    }

    public function testLoginMethod()
    {
        $userData = [
            'email' => $this->data['user']['email'],
            'password' => 'password' // from factory
        ];

        $response = $this->getResponse('POST', 'api/v1/auth/login', $userData);
        $response->assertStatus(200);
    }
}
