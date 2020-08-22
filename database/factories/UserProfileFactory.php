<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\UserProfile;
use Faker\Generator as Faker;

$factory->define(UserProfile::class, function (Faker $faker) {
    $gender = array("M", "F", "Others");
    
    return [
        'photo_url' => $faker->imageUrl(200, 200),
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'gender' => $faker->randomElement($gender),
        'date_of_birth' => date('Y-m-d'),
        'postal_address' => $faker->streetAddress
    ];
});
