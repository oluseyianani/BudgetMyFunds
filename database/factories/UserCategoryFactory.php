<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\User;
use App\Models\Category;
use App\Models\UserCategory;
use Faker\Generator as Faker;

$factory->define(UserCategory::class, function (Faker $faker) {
    return [
        'category_id' => factory(Category::class)->make(),
        'user_id' => factory(User::class)->make(),
        'title' => $faker->word
    ];
});
