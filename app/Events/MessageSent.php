<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * @var \App\Models\User
     */
    public $user;

    /**
     * @var string
     */
    public $room;

    /**
     * @var string
     */
    public $message;

    public $isBot;

    /**
     * Create a new event instance.
     *
     * @param  \App\Models\User  $user
     * @param  string  $room
     * @param  string  $message
     * @return void
     */
    public function __construct(User $user, string $room, string $message, bool $isBot = false)
    {
        $this->user = $user;
        $this->room = $room;
        $this->message = $message;
        $this->isBot = $isBot;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PresenceChannel("room.{$this->room}");
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'user' => $this->user->only('id', 'name'),
            'message' => $this->message,
            'is_bot' => $this->isBot,
            'room' => $this->room,
        ];
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'room.message';
    }
}
