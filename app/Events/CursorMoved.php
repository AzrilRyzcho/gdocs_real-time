<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CursorMoved implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly int    $documentId,
        public readonly string $editorId,
        public readonly string $editorName,
        public readonly string $color,
        public readonly int    $offset,      // posisi karakter dalam teks
        public readonly bool   $isTyping,
    ) {}

    public function broadcastOn(): array
    {
        return [
            new Channel("document.{$this->documentId}"),
        ];
    }

    public function broadcastAs(): string
    {
        return 'cursor.moved';
    }

    public function broadcastWith(): array
    {
        return [
            'editor_id'   => $this->editorId,
            'editor_name' => $this->editorName,
            'color'       => $this->color,
            'offset'      => $this->offset,
            'is_typing'   => $this->isTyping,
        ];
    }
}
