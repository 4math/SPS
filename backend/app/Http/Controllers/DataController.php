<?php

namespace App\Http\Controllers;

use App\Data;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Faker\Generator as Faker;

class DataController extends Controller
{
    public function indexD()
    {
        return response(Data::all()->jsonSerialize(), Response::HTTP_OK);
    }

    public function show($id)
    {
        if ($data = Data::find($id)) {
            return response($data->jsonSerialize(), Response::HTTP_FOUND);
        } else {
            return response(null, Response::HTTP_NO_CONTENT);
        }
    }

    public function create(Faker $faker, Request $request)
    {
        $data = new Data([
            'power' => $request->power,
            'created_at' =>now(),
            'updated_at' => now(),
        ]);
        $data->save();

        return response($data->jsonSerialize(), Response::HTTP_CREATED);
    }

    public function update(Request $request, $id)
    {
        if ($data = Data::find($id)) {
            $data->name = $request->name;
            $data->save();
            return response($data->jsonSerialize(), Response::HTTP_OK);
        } else {
            return response('Not found', Response::HTTP_BAD_REQUEST);
        }
    }

    public function delete($id)
    {
        if (Data::destroy($id))
            return response(null, Response::HTTP_OK);
        else
            return response('Not found', Response::HTTP_BAD_REQUEST);
    }
}
