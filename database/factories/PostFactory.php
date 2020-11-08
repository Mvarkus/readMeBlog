<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\ {
    Post,
    User,
    Category
};

use Faker\Generator as Faker;

$factory->define(Post::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence(),
        'content' => function () use ($faker) {
            $content = str_split($faker->realText(2000), 500);
            $htmlContent = '';
            
            foreach ($content as $paragraph) {
                $htmlContent .= '_:p:o_' . $paragraph . '_:p:c_';
            }
            
            $htmlContent = htmlspecialchars($htmlContent, \ENT_NOQUOTES);

            return str_replace(
                ['_:p:o_', '_:p:c_'],
                ['<p>', '</p>'],
                $htmlContent
            );
        },
        'excerpt' => $faker->realText(),
        'user_id' => User::all()->random(),
        'category_id' => Category::all()->random(),
        'status' => rand(0, 1),
        'image' => function () {
            return rand(0, 1) === 1 ? 'default.jpg' : null;
        },
        'votes' => rand(0, 10000),
        'created_at' => $faker->dateTime($max = 'now', $timezone = null)
    ];
});
