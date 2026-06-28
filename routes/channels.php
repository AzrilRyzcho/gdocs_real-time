<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('document.{docId}', function ($user, $docId) {
    return true; // semua authenticated user boleh join
});

Broadcast::channel('presence.document.{docId}', function ($user, $docId) {
    return ['id' => $user->id ?? 'guest', 'name' => $user->name ?? 'Anonim'];
});
