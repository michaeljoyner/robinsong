<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Stock\Collection::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'description' => $faker->paragraph(),
    ];
});

$factory->define(App\Stock\Category::class, function (Faker\Generator $faker) {
    return [
        'collection_id' => factory(\App\Stock\Collection::class)->create()->id,
        'name' => $faker->name,
        'description' => $faker->paragraph(),
    ];
});

$factory->define(App\Stock\Product::class, function (Faker\Generator $faker) {
    return [
        'category_id' => factory(\App\Stock\Category::class)->create()->id,
        'name' => $faker->name,
        'description' => $faker->paragraph(),
        'price' => $faker->numberBetween(500, 5000),
        'weight' => $faker->numberBetween(20, 5000)
    ];
});

$factory->define(App\Stock\ProductOption::class, function (Faker\Generator $faker) {
    return [
        'product_id' => factory(\App\Stock\Product::class)->create()->id,
        'name' => $faker->words(2, true),
    ];
});

$factory->define(App\Stock\OptionValue::class, function (Faker\Generator $faker) {
    return [
        'product_option_id' => factory(\App\Stock\ProductOption::class)->create()->id,
        'name' => $faker->words(2, true),
    ];
});

$factory->define(App\Stock\Customisation::class, function (Faker\Generator $faker) {
    return [
        'product_id' => factory(\App\Stock\Product::class)->create()->id,
        'name' => $faker->words(2, true),
    ];
});
