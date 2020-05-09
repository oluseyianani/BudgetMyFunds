<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\User;
use App\Models\Budget;
use Faker\Generator as Faker;
use App\Models\BudgetTracking;

$factory->define(BudgetTracking::class, function (Faker $faker) {
    return [
        'budget_id' => factory(Budget::class)->make()['id'],
        'spender' => factory(User::class)->make()['id'],
        'amount_spent' => $faker->randomFloat(2, 10, 9999999.99),
        'reason_for_spend' => $faker->sentence(10, true)
    ];
});
