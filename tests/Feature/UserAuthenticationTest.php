<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
// use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->data = [
            'email' => 'test@budgetmyfunds.com',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ];
    }


    public function getResponse($method, $url, $data)
    {
        return $this->json($method, $url, $data);
    }


    public function testUserRegistration()
    {
        $response = $this->getResponse('POST', 'api/v1/auth/register', $this->data);
        $response->assertStatus(201);
    }
}
