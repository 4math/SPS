<?php

namespace App\Http\Controllers;

use App\Classes\Model\Measurements;
use App\Classes\Model\Socket;
use App\Events\WebSocketPublish;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Console\Commands\WebSocketPublishInstance;

class MeasurementsController extends Controller
{
    public function indexD()
    {
        return response(Measurements::all()->jsonSerialize(), Response::HTTP_OK);
    }

    public function add(Request $request)
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
        if(!$socket->is_connected)
        {
            return response()->json(['is_connected' => $socket->is_connected], Response::HTTP_FORBIDDEN);
        }
        $measurement = new Measurements();
        $measurement->socket_id = $socket->id;
        $measurement->power = $request->power;
        $measurement->created_at = now();
        $measurement->save();

        // $redis = WebSocketPublishInstance::getRedisClient();
        // $redis->publish('test', json_encode(['event' => 'messages.new', 'data' => 'hello, world!']));

        $redis = new \Predis\Client([
            'scheme' => 'tcp',
            // 'host' => env('REDIS_HOST', '127.0.0.1'),
            // 'port' => env('REDIS_PORT', 6379), 
            'host' => '127.0.0.1',
            'port' => 6379,
            'persistent' => true,
        ]);
        $redis->publish('test', json_encode(['event' => 'messages.new', 'data' => $request->power]));

        return response()->json(['state' => $socket->switch_state], Response::HTTP_OK);
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
