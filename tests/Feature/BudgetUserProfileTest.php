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

class BudgetUserProfileTest extends TestCase
{
    use RefreshDatabase, SetupHelper;

    public function setUp() :void
    {
        parent::setUp();

        Artisan::call("passport:install");

        $user = $this->createUserWithRole('Owner');

        Passport::actingAs($user);

        $this->data = [
            'user' => $user,
        ];
    }

    public function testStoreMethodWithPhotoUrl()
    {
        $response = $this->post("api/v1/profile/", ['photo_url' => 'https://picsum.photos/200/300.jpg']);
        $response->assertStatus(201);
    }


    public function testStoreMethodWithNames()
    {
        $data = ['first_name' => 'Oluseyi', 'last_name' => 'Test'];

        $response = $this->post("api/v1/profile/", $data);
        $response->assertStatus(201);
    }

    public function testStoreMethodWithOptionalData()
    {
        $data = ['gender' => 'M', 'date_of_birth' => '2001-01-13', 'postal_address' => 'sample address'];

        $response = $this->post("api/v1/profile/", $data);
        $response->assertStatus(201);
    }


    public function testShowMethod()
    {
        $userId = $this->data['user']['id'];
        $response = $this->get("api/v1/profile/{$userId}");
        $response->assertStatus(200);
    }
}
