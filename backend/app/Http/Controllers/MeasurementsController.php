<?php

namespace App\Http\Controllers;

use App\Measurements;
use App\Socket;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MeasurementsController extends Controller
{
    public function indexD()
    {
        return response(Measurements::all()->jsonSerialize(), Response::HTTP_OK);
    }

    public function add($request)
    {
        $v = Validator::make($request->all(), [
            'unique_id' => 'required|exists:sockets'
        ]);
        if ($v->fails()) {
            return response()->json([
                'errors' => $v->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $socket = Socket::whereUniqueId($request->unique_id)->first();
        $measurement = new Measurements();
        $measurement->socket_id = $socket->id;
        $measurement->power = $request->power;
        $measurement->created_at = now();
        $measurement->save();
        return response($measurement->jsonSerialize(), Response::HTTP_OK);
    }

    public function list($socket_id)
    {
        try{
            $socket = Socket::findOrFail($socket_id);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }

        if($socket->user->id != Auth::id())
        {
            return response(null, Response::HTTP_FORBIDDEN);
        }

        // return response(
        //     Measurements::all()->where('socket_id', $socket_id)->toArray()->jsonSerialize(),
        //     Response::HTTP_OK
        // );

        // We can get socket Measurements without filtering entire Measurements table
        return response($socket->measurements, Response::HTTP_OK);
    }

    public function delete($id)
    {
        try {
            $measurements = Measurements::findOrFail($id);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
        
        if($measurements->socket->user->id != Auth::id()){
            return response(null, Response::HTTP_FORBIDDEN);
        }
        Measurements::destroy($id);
        return response(null, Response::HTTP_OK);
        
    }
}
