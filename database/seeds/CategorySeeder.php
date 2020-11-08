<?php

use Illuminate\Database\Seeder;
use App\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create([
            'title' => 'Web Development'
        ]);

        Category::create([
            'title' => 'Back-end',
            'parent_id' => 1
        ]);

        Category::create([
            'title' => 'Front-end',
            'parent_id' => 1
        ]);

        Category::create([
            'title' => 'Cooking'
        ]);
    }
}
