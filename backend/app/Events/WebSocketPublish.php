<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

use App\Console\Commands\WebSocketPublishInstance;

class WebSocketPublish implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $from;
    public $to;
    public $message;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct()
    {
        $redis = WebSocketPublishInstance::getRedisClient();
        $redis->publish('test', json_encode(['event' => 'messages.new', 'data' => 'hello, world!']));
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('test');
    }

    /**
     * Get the broadcast event name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'messages.new';
    }
}
