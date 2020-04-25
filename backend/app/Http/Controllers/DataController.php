<?php

namespace App\Http\Controllers;

use App\Data;
use App\Socket;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class DataController extends Controller
{
    public function indexD()
    {
        return response(Data::all()->jsonSerialize(), Response::HTTP_OK);
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

        if($socket->user_id != Auth::id())
        {
            return response(null, Response::HTTP_FORBIDDEN);
        }

        return response(
            Data::all()->where('socket_id', $socket_id)->jsonSerialize(),
            Response::HTTP_OK
        );
    }

    public function delete($id)
    {
        try {
            $data = Data::findOrFail($id);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
        
        if($data->socket->user_id != Auth::id()){
            return response(null, Response::HTTP_FORBIDDEN);
        } else {
            Data::destroy($id);
            return response(null, Response::HTTP_OK);
        }
    }
}
