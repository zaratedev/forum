<?php

use Faker\Generator as Faker;

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
        'confirmed' => false
    ];
});


$factory->define(App\Thread::class, function (Faker $faker) {
    $title = $faker->sentence;
    return [
        'user_id' => function() {
            return factory(App\User::class)->create()->id;
        },
        'channel_id' => function() {
            return factory(App\Channel::class)->create()->id;
        },
        'title' => $title,
        'body' => $faker->paragraph,
        'visits' => 0,
        'slug' => str_slug($title),
    ];
});

$factory->define(App\Channel::class, function (Faker $faker) {
    $name = $faker->word;
    return [
        'name' => $name,
        'slug' => $name
    ];
});

$factory->define(App\Reply::class, function (Faker $faker) {
    return [
        'thread_id' => function() {
            return factory(App\Thread::class)->create()->id;
        },
        'user_id' => function() {
            return factory(App\User::class)->create()->id;
        },
        'body' => $faker->paragraph
    ];
});
