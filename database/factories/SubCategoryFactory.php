<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Category;
use App\Models\SubCategory;
use Faker\Generator as Faker;

$factory->define(SubCategory::class, function (Faker $faker) {
    return [
        'sub_title' => $faker->unique()->word,
        'category_id' => factory(Category::class)->make()->id
    ];
});
