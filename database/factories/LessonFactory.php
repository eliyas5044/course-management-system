<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Lesson;
use Faker\Generator as Faker;

$factory->define(Lesson::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'excerpt' => $faker->text,
        'content' => $faker->text(1000)
    ];
});
