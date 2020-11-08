<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use App\ {
    Post, Comment, User
};

$factory->define(Comment::class, function (Faker $faker) {
    return [
        'content' => $faker->sentence(),
        'reply_to' => function () {
            
            $comments = Comment::all();

            if ($comments->isEmpty()) {
                return null;
            }

            if (rand(0, 1) === 1) {
                return $comments->random();
            }

            return null;
        },
        'post_id' => Post::all()->random(),
        'user_id' => User::all()->random(),
        'approved' => rand(0, 1)
    ];
});
