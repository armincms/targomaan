<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Armincms\Targomaan\Tests\Fixtures\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'price' => $faker->randomDigitNotNull,
    ];
});
