<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class WriteAudit
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public $subject, $type, $status, $user_id;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($subject, $status, $type, $user_id = null)
    {
        $this->subject = $subject;
        $this->status = $status;
        $this->type = $type;
        $this->user_id = $user_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
