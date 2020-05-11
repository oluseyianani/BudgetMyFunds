<?php

use App\Models\Budget;
use App\Models\MonthlyIncome;
use Illuminate\Database\Seeder;

class MonthlyIncomeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $budgets = Budget::all()->each(function ($budget) {
            factory(MonthlyIncome::class, 2)->create([
                'beneficiary' => $budget['user_id'],
                'creator' => $budget['user_id'],
                'date_received' => $budget['budget_for_month'],
            ]);
        });
    }
}
