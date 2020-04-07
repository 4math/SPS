<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

class UsersController extends Controller
{
    public function index()
    {
        return response(User::all()->jsonSerialize(), Response::HTTP_OK);
    }

    public function show($id)
    {
        if ($user = User::find($id)) {
            return response($user->jsonSerialize(), Response::HTTP_FOUND);
        } else {
            return response(null, Response::HTTP_NO_CONTENT);
        }
    }

    public function create(Faker $faker, Request $request)
    {
        $user = new User([
            'name' => $faker->firstName(),
            'email' => $faker->unique()->safeEmail,
            'password' => $faker->password(),
            'api_token' => $faker->unique()->asciify(Str::random(64)),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $user->save();

        return response($user->jsonSerialize(), Response::HTTP_CREATED);
    }

    public function update(Request $request, $id)
    {
        if ($user = User::find($id)) {
            $user->name = $request->name;
            $user->save();
            return response($user->jsonSerialize(), Response::HTTP_OK);
        } else {
            return response('Not found', Response::HTTP_BAD_REQUEST);
        }
    }

    public function delete($id)
    {
        if (User::destroy($id))
            return response(null, Response::HTTP_OK);
        else
            return response('Not found', Response::HTTP_BAD_REQUEST);
    }
}
