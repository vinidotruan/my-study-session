<?php

namespace App\Events;

use App\Models\StudySession;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EndSession implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $timer;
    /**
     * Create a new event instance.
     */
    public function __construct(private readonly User $user, public StudySession $studySession)
    {
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('session.'.$this->studySession->id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'session.ended';
    }

    public function broadcastWith(): array
    {
       return ["Aa"];
    }

}
