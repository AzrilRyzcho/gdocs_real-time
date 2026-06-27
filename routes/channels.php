<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('presence.document.{docId}', function ($user, $docId) {
    return ['id' => $user->id ?? 'guest', 'name' => $user->name ?? 'Anonim'];
});
