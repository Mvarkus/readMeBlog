<?php

use Illuminate\Database\Seeder;
use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class)->create([
            'email' => 'okarin3@mail.ru',
            'role_id' => 1
        ]);

        factory(User::class, 20)->create();
    }
}
