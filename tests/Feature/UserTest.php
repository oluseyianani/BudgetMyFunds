<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use Tests\SetupHelper;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase, SetupHelper;

    public function setUp() :void
    {
        parent::setUp();

        Artisan::call("passport:install");

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
            'password_confirmation' => 'password123'
        ];


        $response = $this->getResponse('POST', 'api/v1/auth/register', $userData);
        $response->assertStatus(201);
    }

    public function testLoginMethod()
    {
        $user = $this->createUserWithRole('Owner');
     
        $userData = [
            'email' => $user['email'],
            'password' => 'password123'
        ];

        
        $response = $this->post('api/v1/auth/login', $userData);
        $response->assertStatus(200);
    }

    public function testLogoutMethod()
    {
        Passport::actingAs($this->data['user']);

        $response = $this->post('api/v1/auth/logout');
        $response->assertStatus(200);
    }

    public function testLogoutAllDevicesMethod()
    {
        Passport::actingAs($this->data['user']);

        $response = $this->post('api/v1/auth/logout/all-device');
        $response->assertStatus(200);
    }
}
