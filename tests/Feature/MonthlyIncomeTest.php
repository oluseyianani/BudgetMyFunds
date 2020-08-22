<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use Tests\SetupHelper;
use App\Models\MonthlyIncome;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MonthlyIncomeTest extends TestCase
{
    use RefreshDatabase, SetupHelper;

    public function setUp():void
    {
        parent::setUp();

        Artisan::call("passport:install");

        $user = $this->createUserWithRole('Owner');

        Passport::actingAs($user);

        $monthlyIncome = factory(MonthlyIncome::class)->create([
            'beneficiary' => $user['id'],
            'creator' => $user['id'],
        ]);

        $this->data = [
            'user' => $user,
            'monthlyIncome' => $monthlyIncome->toArray()
        ];
    }

    public function testIndexMethod()
    {
        $response = $this->get('api/v1/income');
        $response->assertStatus(200);
    }

    public function testStoreMethod()
    {
        $monthlyIncome = factory(MonthlyIncome::class)->make([
            'beneficiary' => $this->data['user']['id'],
            'creator' => $this->data['user']['id']
        ]);


        $response = $this->post('api/v1/income', $monthlyIncome->toArray());
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

        $response = $this->put("api/v1/income/{$id}", $data);
        $response->assertStatus(200);
    }

    public function testDeleteMethod()
    {
        $id = $this->data['monthlyIncome']['id'];

        $response = $this->delete("api/v1/income/{$id}");
        $response->assertStatus(200);
    }
}
