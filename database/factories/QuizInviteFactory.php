<?php

use Faker\Generator as Faker;

$factory->define(App\QuizInvite::class, function (Faker $faker) {
    return [
        'email' => $faker->unique()->safeEmail,
        'quiz_id' => $faker->numberBetween(1, 10)
    ];
});
