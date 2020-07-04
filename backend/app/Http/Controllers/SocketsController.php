<?php

namespace App\Http\Controllers;

use App\Classes\Model\Socket;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class SocketsController extends Controller
{
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

        $socket = Socket::whereUniqueId($request->unique_id)
            ->first();
        $socket->user_id = Auth::id();
        $socket->name = $request->name;
        $socket->description = $request->description;
        $socket->is_connected = true;
        $socket->save();
        return response($socket->jsonSerialize(), Response::HTTP_OK);
    }

    public function list()
    {
        return response(Auth::user()->sockets, Response::HTTP_OK);
    }

    public function show($id)
    {
        try {
            $socket = Socket::findOrFail($id);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }

        if ($socket->user->id != Auth::id()) {
            return response(null, Response::HTTP_FORBIDDEN);
        }

        return response($socket->jsonSerialize(), Response::HTTP_OK);
    }

    public function put(Request $request)
    {
        try {
            $socket = Socket::findOrFail($request->id);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }

        if ($socket->user->id != Auth::id()) {
            return response(null, Response::HTTP_FORBIDDEN);
        }

        $socket->switch_state = $request->state;
        $socket->save();
        return response($socket->jsonSerialize(), Response::HTTP_OK);
    }

    public function updateInfo(Request $request)
    {
        try {
            $socket = Socket::findOrFail($request->id);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }

        if ($socket->user->id != Auth::id()) {
            return response(null, Response::HTTP_FORBIDDEN);
        }

        $socket->name = $request->name;
        $socket->description = $request->description;
        $socket->save();
        return response($socket->jsonSerialize(), Response::HTTP_OK);
    }

    public function delete($id)
    {
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
