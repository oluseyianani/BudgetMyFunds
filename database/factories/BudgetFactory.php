<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\User;
use App\Models\Budget;
use App\Models\Category;
use App\Models\SubCategory;
use Faker\Generator as Faker;

$factory->define(Budget::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence(3, true),
        'category_id' => factory(Category::class)->make()['id'],
        'sub_category_id' => factory(SubCategory::class)->make()['id'],
        'user_id' => factory(User::class)->make()['id'],
        'dedicated_amount' => $faker->randomFloat(2, 10, 9999999.99),
        'budget_for_month' => $faker->date('Y-m-d')
    ];
});
