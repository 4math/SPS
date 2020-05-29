<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class WebSocketPublishInstance extends Command
{
    private $redis;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publisher:serve';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $this->redis = new \Predis\Client([
                'scheme' => 'tcp',
                // 'host' => env('REDIS_HOST', '127.0.0.1'),
                // 'port' => env('REDIS_PORT', 6379), 
                'host' => '127.0.0.1',
                'port' => 6379,
                'persistent' => true,
            ]);
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }

        $this->redis->publish('test', json_encode(['event' => 'messages.new', 'data' => 'hello, world!']));
        
    }

    public function getRedisClient()
    {
        return $this->redis;
    }
}
