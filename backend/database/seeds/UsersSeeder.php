<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UsersSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'root',
            'email' => 'root@example.net',
            'password' => 'root',
            'api_token' => Str::random(32),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        factory(User::class, 2)->create();
        // factory(User::class, 2)->create()->each(function ($user){
        //     $user->socket()->save()
        // });
    }
}
