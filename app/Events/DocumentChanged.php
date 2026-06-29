<?php

namespace App\Events;

use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

// ShouldBroadcastNow = langsung, tanpa queue
class DocumentChanged implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly int    $documentId,
        public readonly string $konten,
        public readonly string $judul,
        public readonly int    $editorId,
        public readonly string $editorName,
        public readonly ?int   $indeksKursor,
    ) {}

    public function broadcastOn(): array
    {
        return [new PresenceChannel('document.' . $this->documentId)];
    }

    public function broadcastAs(): string
    {
        return 'doc.changed';
    }

    // Payload sekecil mungkin
    public function broadcastWith(): array
    {
        return [
            'k'  => $this->konten,       // konten
            'j'  => $this->judul,        // judul
            'ei' => $this->editorId,     // editor id
            'en' => $this->editorName,   // editor name
            'c'  => $this->indeksKursor, // cursor index
            'ts' => time(),              // timestamp
        ];
    }
}
