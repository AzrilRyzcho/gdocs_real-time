<?php

namespace App\Events;

use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CursorMoved implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly int    $documentId,
        public readonly int    $userId,
        public readonly string $userName,
        public readonly ?int   $indeksKursor,
    ) {}

    public function broadcastOn(): array
    {
        return [new PresenceChannel('document.' . $this->documentId)];
    }

    public function broadcastAs(): string
    {
        return 'cursor.moved';
    }

    public function broadcastWith(): array
    {
        return [
            'uid' => $this->userId,
            'n'   => $this->userName,
            'c'   => $this->indeksKursor,
        ];
    }
}
