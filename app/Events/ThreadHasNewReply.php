<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ThreadHasNewReply
{
    use SerializesModels;

    public $thread;
    public $reply;

    /**
     * Create a new event instance.
     *
     * @param $thread
     * @param $reply
     */
    public function __construct($thread, $reply)
    {
        $this->thread = $thread;
        $this->reply = $reply;
    }
}
