<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DocumentChanged implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public int    $documentId,
        public string $konten,
        public string $judul,
        public int    $editorId,
        public string $editorName,
        public ?int   $indeksKursor,
        public string $updatedAt,
        public int    $updatedAtTimestamp,
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PresenceChannel('document.' . $this->documentId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'document.changed';
    }

    public function broadcastWith(): array
    {
        return [
            'konten'              => $this->konten,
            'judul'               => $this->judul,
            'editor_id'           => $this->editorId,
            'editor_name'         => $this->editorName,
            'indeks_kursor'       => $this->indeksKursor,
            'updated_at'          => $this->updatedAt,
            'updated_at_timestamp'=> $this->updatedAtTimestamp,
        ];
    }
}
