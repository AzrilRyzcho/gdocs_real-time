<?php

use Illuminate\Support\Facades\Broadcast;

/**
 * Presence channel untuk dokumen.
 * Karena app ini tidak pakai auth login, kita return data user
 * berdasarkan data yang dikirim dari client (nama + id dari localStorage).
 */
Broadcast::channel('presence.document.{docId}', function ($user, $docId) {
    // Presence channel butuh return array (bukan null/false)
    // Karena kita tidak pakai Laravel Auth, gunakan "guest" user
    return [
        'id'    => $user->id ?? 'guest',
        'name'  => $user->name ?? 'Anonim',
    ];
});
