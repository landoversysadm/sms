<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use JakubOnderka\PhpConsoleColor\ConsoleColor;

class NewEnrollmentEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $enrollment;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($enrollment)
    {
      $this->enrollment = $enrollment;
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
