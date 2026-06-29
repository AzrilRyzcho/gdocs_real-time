<?php

use App\Models\Document;
use App\Models\DocumentShare;
use Illuminate\Support\Facades\Broadcast;

// Presence channel untuk dokumen — return user info jika punya akses
Broadcast::channel('document.{documentId}', function ($user, $documentId) {
    $document = Document::find($documentId);
    if (!$document) return null;

    // Pemilik atau yang diberi akses
    if ($document->owner_id === $user->id) {
        return ['id' => $user->id, 'name' => $user->name];
    }

    $share = DocumentShare::where('document_id', $documentId)
        ->where('user_id', $user->id)
        ->first();

    if ($share) {
        return ['id' => $user->id, 'name' => $user->name];
    }

    return null;
});
