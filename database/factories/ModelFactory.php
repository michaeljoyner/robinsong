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
        'name'           => $faker->name,
        'email'          => $faker->email,
        'password'       => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Stock\Collection::class, function (Faker\Generator $faker) {
    return [
        'name'        => $faker->name,
        'description' => $faker->paragraph(),
    ];
});

$factory->define(App\Stock\Category::class, function (Faker\Generator $faker) {
    return [
        'collection_id' => factory(\App\Stock\Collection::class)->create()->id,
        'name'          => $faker->name,
        'description'   => $faker->paragraph(),
    ];
});

$factory->define(App\Stock\Product::class, function (Faker\Generator $faker) {
    return [
        'category_id' => factory(\App\Stock\Category::class)->create()->id,
        'name'        => $faker->name,
        'description' => $faker->paragraph(),
        'writeup'     => $faker->paragraphs(3, true),
        'price'       => $faker->numberBetween(500, 5000),
        'weight'      => $faker->numberBetween(20, 5000),
        'available'   => 0
    ];
});

$factory->define(App\Stock\ProductOption::class, function (Faker\Generator $faker) {
    return [
        'product_id' => factory(\App\Stock\Product::class)->create()->id,
        'name'       => $faker->words(2, true),
    ];
});

$factory->define(App\Stock\OptionValue::class, function (Faker\Generator $faker) {
    return [
        'product_option_id' => factory(\App\Stock\ProductOption::class)->create()->id,
        'name'              => $faker->words(2, true),
    ];
});

$factory->define(App\Stock\Customisation::class, function (Faker\Generator $faker) {
    return [
        'product_id' => factory(\App\Stock\Product::class)->create()->id,
        'name'       => $faker->words(2, true),
    ];
});

$factory->define(App\Shipping\ShippingLocation::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->country,
    ];
});

$factory->define(App\Shipping\WeightClass::class, function (Faker\Generator $faker) {
    return [
        'shipping_location_id' => factory(App\Shipping\ShippingLocation::class)->create()->id,
        'weight_limit'         => $faker->numberBetween(1, 10000),
        'price'                => $faker->numberBetween(0, 10000)
    ];
});

$factory->define(App\Orders\Order::class, function (Faker\Generator $faker) {
    return [
        'customer_name'   => $faker->name,
        'customer_email'  => $faker->email,
        'order_number'    => str_random(10),
        'address_line1'   => $faker->streetName,
        'address_line2'   => $faker->streetAddress,
        'address_city'    => $faker->city,
        'address_state'   => $faker->word,
        'address_zip'     => $faker->numberBetween(100, 999),
        'address_country' => $faker->country
    ];
});

$factory->define(App\Orders\OrderItem::class, function (Faker\Generator $faker) {
    return [
        'order_id'    => factory(App\Orders\Order::class)->create()->id,
        'product_id'  => factory(\App\Stock\Product::class)->create()->id,
        'description' => $faker->sentence,
        'quantity'    => 1,
        'price'       => 100
    ];
});

$factory->define(App\Orders\OrderItemOption::class, function (Faker\Generator $faker) {
    return [
        'order_item_id' => factory(App\Orders\OrderItem::class)->create()->id,
        'name'          => $faker->words(2, true),
        'value'         => $faker->word
    ];
});

$factory->define(App\Orders\OrderItemCustomisation::class, function (Faker\Generator $faker) {
    return [
        'order_item_id' => factory(App\Orders\OrderItem::class)->create()->id,
        'name'          => $faker->words(2, true),
        'value'         => $faker->paragraph
    ];
});

$factory->define(App\Blog\Post::class, function (Faker\Generator $faker) {
    return [
        'user_id'     => 1,
        'title'       => $faker->sentence,
        'description' => $faker->paragraph,
        'content'     => $faker->paragraphs(8, true)
    ];
});




