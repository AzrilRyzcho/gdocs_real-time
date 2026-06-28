<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DocumentUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly int     $documentId,
        public readonly string  $content,
        public readonly string  $title,
        public readonly string  $editorId,   // UUID dari user yang mengedit
        public readonly string  $editorName,
        public readonly ?string $color = null,
    ) {}

    /**
     * Channel broadcast: semua user di dokumen yang sama akan menerima event ini.
     */
    public function broadcastOn(): array
    {
        return [
            new Channel("document.{$this->documentId}"),
        ];
    }

    public function broadcastAs(): string
    {
        return 'document.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'document_id'  => $this->documentId,
            'content'      => $this->content,
            'title'        => $this->title,
            'editor_id'    => $this->editorId,
            'editor_name'  => $this->editorName,
            'color'        => $this->color,
            'updated_at'   => now()->toIso8601String(),
        ];
    }
}
