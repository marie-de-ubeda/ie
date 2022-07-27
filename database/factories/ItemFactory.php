<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Item;
use Faker\Generator as Faker;

$factory->define(Item::class, function (Faker $faker) {
    return [
        'category_id'=> function () {
            return factory(\App\Category::class)->create()->id;
        },
        'sale_id'=>function () {
            return factory(\App\Sale::class)->create()->id;
        },
        'description'=>$faker->text,
        'auction_type'=>$faker->name,
        'pricing' =>
            [
                'estimates'=>
                    [
                        'max'=>500,
                        'min'=>300,
                        'currency'=>'euro'
                    ],
            ]
    ];
});
