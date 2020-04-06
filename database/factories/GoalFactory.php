<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Goal;
use App\Models\User;
use App\Models\GoalCategory;
use Faker\Generator as Faker;

$factory->define(Goal::class, function (Faker $faker) {

    $amount = $faker->randomFloat(2, 10, 9999999.99);

    // Calculate how many number of months between dates
    $timeStampNow = strtotime(now());
    $timeStampDueDate = rand( strtotime("+1years"), strtotime("+2years"));

    $yearNow = date('Y', $timeStampNow);
    $yearDueDate = date('Y', $timeStampDueDate);

    $monthNow = date('m', $timeStampNow);
    $monthDueDate = date('m', $timeStampDueDate);

    $numberOfMonths = (($yearDueDate - $yearNow) * 12) + ($monthDueDate - $monthNow);

    $monthlyContribution = $amount/$numberOfMonths;

    return [
        'title' => $faker->sentence(6, false),
        'description' => $faker->text(),
        'amount' => $amount,
        'monthly_contributions' => round($monthlyContribution, 2),
        'user_id' => factory(User::class)->make()['id'],
        'goal_categories_id' => factory(GoalCategory::class)->make()['id'],
        'due_date' => date('Y-m-d h:m:s', $timeStampDueDate),
        'auto_compute' => 0,
        'completed' => 0
    ];
});
