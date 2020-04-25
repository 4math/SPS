<?php

namespace App\Http\Controllers;

use App\Socket;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class SocketsController extends Controller
{
    public function indexD()
    {
        return response(Socket::all()->jsonSerialize(), Response::HTTP_OK);
    }

    public function add(Request $request)
    {
        $v = Validator::make($request->all(), [
            'name' => 'required|min:3',
        ]);
        if ($v->fails()) {
            return response()->json([
                'errors' => $v->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $socket = new Socket;
        $socket->user_id = Auth::id();
        $socket->name = $request->name;
        $socket->description = $request->description;
        $socket->save();
        return response($socket->jsonSerialize(), Response::HTTP_OK);
    }

    public function list()
    {
        return response(
            Socket::all()->where('user_id', Auth::id())->jsonSerialize(), 
            Response::HTTP_OK);
    }

    public function show($id)
    {
        // $socket = Socket::all()->where('user_id', Auth::id())->where('id', $id);
        // if($socket != '[]'){
        //     return response($socket->jsonSerialize(), Response::HTTP_OK);
        // } else {
        //     return response($socket->jsonSerialize(), Response::HTTP_NO_CONTENT);
        // }

        try {
            $socket = socket::findOrFail($id);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }

        if($socket->user != Auth::id()){
            return response(null, Response::HTTP_FORBIDDEN);
        }

        return response($socket->jsonSerialize(), Response::HTTP_OK);
    }

    public function delete($id)
    {
        // if(Socket::all()->where('user_id', Auth::id())->where('id', $id) != '[]'){
        //     Socket::destroy($id);
        //     return response(null, Response::HTTP_OK);
        // } else
        //     return response()->json([
        //         'error' => 'Not found'
        //     ], Response::HTTP_BAD_REQUEST);

        try {
            $socket = socket::findOrFail($id);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }

        if ($socket->user != Auth::id()) {
            return response(null, Response::HTTP_FORBIDDEN);
        } else {
            Socket::destroy($id);
            return response(null, Response::HTTP_OK);
        }
    }
}
