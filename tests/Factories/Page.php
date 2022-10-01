<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Armincms\Targomaan\Tests\Fixtures\Page;
use Faker\Generator as Faker;

$factory->define(Page::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'title' => $faker->title,
        'locale' => 'en',
        'sequence_key' => $faker->md5,
    ];
});
