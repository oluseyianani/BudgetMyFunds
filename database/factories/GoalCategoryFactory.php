<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\GoalCategory;
use Faker\Generator as Faker;

$factory->define(GoalCategory::class, function (Faker $faker) {
    return [
        'title' => $faker->unique()->sentence(4, true),
    ];
});
