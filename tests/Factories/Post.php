<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Armincms\Targomaan\Tests\Fixtures\Post;
use Faker\Generator as Faker;

$factory->define(Post::class, function (Faker $faker) {
    return [
        'name' => [
        	'fa' => $faker->name,
        	'en' => $faker->name,
        ]
    ];
});
