<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserPresence implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly int    $documentId,
        public readonly string $userId,
        public readonly string $userName,
        public readonly string $color,
        public readonly string $action,   // 'join' | 'leave' | 'ping'
    ) {}

    public function broadcastOn(): array
    {
        return [new Channel("document.{$this->documentId}")];
    }

    public function broadcastAs(): string
    {
        return 'user.presence';
    }

    public function broadcastWith(): array
    {
        return [
            'user_id'   => $this->userId,
            'user_name' => $this->userName,
            'color'     => $this->color,
            'action'    => $this->action,
        ];
    }
}
