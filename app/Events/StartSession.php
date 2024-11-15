<?php

namespace App\Events;

use App\Models\StudySession;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StartSession
{
    use Dispatchable, InteractsWithSockets, SerializesModels, ShouldBroadcast;

    private $timer;
    /**
     * Create a new event instance.
     */
    public function __construct(private User $user,public StudySession $studySession)
    {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('users.'.$this->user->id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'session.started';
    }

    public function broadcastWith(): array
    {
        $stopWatch = date("H:i:s",time());
        return [$stopWatch];
    }
}
