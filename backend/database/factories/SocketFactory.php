<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Socket;
use Faker\Generator as Faker;


$factory->define(Socket::class, function (Faker $faker) {
    return [
        'name'=> $faker->firstName(),
        'description'=> $faker->text(32),
    ];
});
