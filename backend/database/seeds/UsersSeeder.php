<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

class UsersSeeder extends Seeder
{
    public function run(Faker $faker)
    {
        $user = new User
        ([
            'name' => 'root',
            'email' => 'root@example.net',
            'password' => bcrypt('root'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $user->save();
        //factory(User::class, 2)->create();
        // factory(User::class, 2)->create()->each(function ($user){
        //     $user->socket()->save()
        // });
    }
}
