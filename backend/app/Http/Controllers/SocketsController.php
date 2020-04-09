<?php

namespace App\Http\Controllers;

use App\Socket;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Faker\Generator as Faker;

class SocketsController extends Controller
{
    public function index()
    {
        return response(Socket::all()->jsonSerialize(), Response::HTTP_OK);
    }

    public function show($id)
    {
        if ($socket = Socket::find($id)) {
            return response($socket->jsonSerialize(), Response::HTTP_FOUND);
        } else {
            return response(null, Response::HTTP_NO_CONTENT);
        }
    }

    public function create(Faker $faker, Request $request)
    {
        $socket = new Socket([
            'user_id' => $request->user_id,
            'name' => $faker->firstName(),
            'description' => $faker->text(60),
        ]);
        $socket->save();
        return response($socket->jsonSerialize(), Response::HTTP_CREATED);
    }

    public function update(Request $request, $id)
    {
        if ($socket = Socket::find($id)) {
            $socket->name = $request->name;
            $socket->save();
            return response($socket->jsonSerialize(), Response::HTTP_OK);
        } else {
            return response('Not found', Response::HTTP_BAD_REQUEST);
        }
    }

    public function delete($id)
    {
        if (Socket::destroy($id))
            return response(null, Response::HTTP_OK);
        else
            return response('Not found', Response::HTTP_BAD_REQUEST);
    }
}
