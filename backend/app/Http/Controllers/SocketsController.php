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
            'unique_id' => 'required|unique:sockets',
        ]);
        if ($v->fails()) {
            return response()->json([
                'errors' => $v->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $socket = new Socket;
        $socket->unique_id = $request->unique_id;
        $socket->save();
        return response($socket->jsonSerialize(), Response::HTTP_OK);
    }

    // public function getState(Request $request)
    // {
    //     $v = Validator::make($request->all(), [
    //         'unique_id' => 'required|exists:sockets',
    //     ]);
    //     if ($v->fails()) {
    //         return response()->json([
    //             'errors' => $v->errors()
    //         ], Response::HTTP_UNPROCESSABLE_ENTITY);
    //     }

    //     $socket = Socket::whereUniqueId($request->unique_id)->first();
    //     return response()->json([
    //         "state" => $socket->switch_state,
    //     ], Response::HTTP_OK);
    // }

    public function connect(Request $request)
    {
        $v = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'unique_id' => 'required|exists:sockets'
        ]);
        if ($v->fails()) {
            return response()->json([
                'errors' => $v->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $socket = Socket::whereUniqueId($request->unique_id)->first();
        $socket->user_id = Auth::id();
        $socket->name = $request->name;
        $socket->description = $request->description;
        $socket->save();
        return response($socket->jsonSerialize(), Response::HTTP_OK);
    }

    public function list()
    {
        // $sockets = Socket::all()->where('user_id', Auth::id())->values();
        return response(Auth::user()->sockets, Response::HTTP_OK);
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
            $socket = Socket::findOrFail($id);
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

    public function put(Request $request)
    {
        $socket = Socket::all()->where('user_id', Auth::id())->where('id', $request->id)->first();
        // Did not understand how to check whether the first element is empty...
        if($socket){
            $socket->switch_state = $request->state;
            $socket->save();
            return response($socket->jsonSerialize(), Response::HTTP_OK);
        } else {
            // Does not work
            return response()->json(['error' => "Socket with such id does not exist"], Response::HTTP_NO_CONTENT);
        }
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

        if ($socket->user->id != Auth::id()) {
            return response(null, Response::HTTP_FORBIDDEN);
        } else {
            Socket::destroy($id);
            return response(null, Response::HTTP_OK);
        }
    }
}
