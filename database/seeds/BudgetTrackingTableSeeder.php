<?php

use App\Models\User;
use App\Models\Budget;
use App\Models\BudgetTracking;
use Illuminate\Database\Seeder;

class BudgetTrackingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Budget::take(1)->each(function ($budget) {
            $spender = User::inRandomOrder()->limit(1)->get('id');
            factory(BudgetTracking::class, 5)->create(['spender' => $spender[0]['id'], 'budget_id' => $budget['id']]);
        });
    }
}
