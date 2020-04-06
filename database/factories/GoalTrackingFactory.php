<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Goal;
use App\Models\GoalTracking;
use Faker\Generator as Faker;

$factory->define(GoalTracking::class, function (Faker $faker) {
    return [
        'goal_id' => factory(Goal::class)->make(),
        'amount_contributed' => $faker->randomFloat(2, 10, 99999.00),
        'date_contributed' => $faker->dateTime()
    ];
});
