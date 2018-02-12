<?php

namespace Thanatos\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ThanatosNotify
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    //public $permission;
    //public $content;
    //public $sender;

    public function __construct(array $payload)
    {
        $this->permission = $payload['permission'];
        $this->content = $payload['content'];
        $this->sender = $payload['sender'];
    }

    public function broadCastWith(){
        return ['data'=>'mak'];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        //return new PrivateChannel('thanatosNotify');
        return ['thanatos'];
    }
}
