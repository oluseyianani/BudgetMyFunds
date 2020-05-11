<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\User;
use App\Models\MonthlyIncome;
use Faker\Generator as Faker;

$factory->define(MonthlyIncome::class, function (Faker $faker) {
    return [
        'beneficiary' => factory(User::class)->make()['id'],
        'income_source' => $faker->sentence(3, true),
        'amount' => $faker->randomFloat(2, 10, 9999999.99),
        'date_received' => $faker->date('Y-m-d'),
        'creator' => factory(User::class)->make()['id']
    ];
});
