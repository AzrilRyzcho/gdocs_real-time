<?php

use App\Models\Document;
use App\Models\DocumentShare;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('document.{documentId}', function ($user, $documentId) {
    $doc = Document::find($documentId);
    if (!$doc) return null;

    if ($doc->owner_id === $user->id)
        return ['id' => $user->id, 'name' => $user->name];

    $share = DocumentShare::where('document_id', $documentId)
        ->where('user_id', $user->id)->first();

    return $share ? ['id' => $user->id, 'name' => $user->name] : null;
});
