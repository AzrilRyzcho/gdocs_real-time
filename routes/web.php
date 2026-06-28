<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DocumentController;
use Illuminate\Support\Facades\Route;

// ── ROOT: redirect sesuai status login ───────────────────────────────────────
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('documents.index')
        : redirect()->route('login');
});

// ── GUEST ROUTES ─────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',       [LoginController::class,    'showLoginForm'])->name('login');
    Route::post('/login',      [LoginController::class,    'login']);
    Route::get('/register',    [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register',   [RegisterController::class, 'register']);
});

// ── AUTH ROUTES ───────────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');

    // Buat dokumen baru
    Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');

    // Halaman editor
    Route::get('/documents/{document}/edit', [DocumentController::class, 'edit'])->name('documents.edit');

    // API: ambil isi dokumen
    Route::get('/documents/{document}', [DocumentController::class, 'show'])->name('documents.show');

    // API: simpan + broadcast
    Route::patch('/documents/{document}', [DocumentController::class, 'update'])->name('documents.update');

    // Hapus dokumen
    Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');

    // Broadcast konten real-time (tanpa save DB)
    Route::post('/documents/{document}/broadcast', [DocumentController::class, 'broadcastOnly'])->name('documents.broadcast');

    // Broadcast kursor
    Route::post('/documents/{document}/cursor', [DocumentController::class, 'cursor'])->name('documents.cursor');

    // Presence (join/leave/ping)
    Route::post('/documents/{document}/presence', [DocumentController::class, 'presence'])->name('documents.presence');

    // Rename dokumen
    Route::post('/documents/{document}/rename', [DocumentController::class, 'rename'])->name('documents.rename');

    // Favorite / Archive / Duplicate
    Route::post('/documents/{document}/favorite', [DocumentController::class, 'toggleFavorite'])->name('documents.favorite');
    Route::post('/documents/{document}/archive',  [DocumentController::class, 'toggleArchive'])->name('documents.archive');
    Route::post('/documents/{document}/duplicate', [DocumentController::class, 'duplicate'])->name('documents.duplicate');

    // Settings
    Route::get('/settings',          [\App\Http\Controllers\SettingsController::class, 'index'])->name('settings');
    Route::post('/settings/profile', [\App\Http\Controllers\SettingsController::class, 'updateProfile'])->name('settings.profile');
    Route::post('/settings/password',[\App\Http\Controllers\SettingsController::class, 'updatePassword'])->name('settings.password');

    // Comments
    Route::get('/documents/{document}/comments',         [\App\Http\Controllers\CommentController::class, 'index'])->name('comments.index');
    Route::post('/documents/{document}/comments',        [\App\Http\Controllers\CommentController::class, 'store'])->name('comments.store');
    Route::post('/comments/{comment}/resolve',           [\App\Http\Controllers\CommentController::class, 'resolve'])->name('comments.resolve');
    Route::delete('/comments/{comment}',                 [\App\Http\Controllers\CommentController::class, 'destroy'])->name('comments.destroy');

    // Notifications
    Route::get('/notifications',                         [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/read-all',               [\App\Http\Controllers\NotificationController::class, 'markAllRead'])->name('notifications.readAll');
    Route::post('/notifications/{notification}/read',    [\App\Http\Controllers\NotificationController::class, 'markRead'])->name('notifications.read');

    // ── VERSION HISTORY ──────────────────────────────────────────────────────
    Route::get('/documents/{document}/versions',                      [DocumentController::class, 'versionHistory'])->name('documents.versions');
    Route::post('/documents/{document}/versions',                     [DocumentController::class, 'saveVersion'])->name('documents.versions.save');
    Route::get('/documents/{document}/versions/{version}/preview',    [DocumentController::class, 'previewVersion'])->name('documents.versions.preview');
    Route::post('/documents/{document}/versions/{version}/restore',   [DocumentController::class, 'restoreVersion'])->name('documents.versions.restore');

    // ── EXPORT ───────────────────────────────────────────────────────────────
    Route::get('/documents/{document}/export/pdf',  [DocumentController::class, 'exportPdf'])->name('documents.export.pdf');
    Route::get('/documents/{document}/export/docx', [DocumentController::class, 'exportDocx'])->name('documents.export.docx');

    // ── SHARE ────────────────────────────────────────────────────────────────
    Route::get('/documents/{document}/shares',  [\App\Http\Controllers\ShareController::class, 'index'])->name('shares.index');
    Route::post('/documents/{document}/shares', [\App\Http\Controllers\ShareController::class, 'store'])->name('shares.store');
    Route::delete('/shares/{share}',            [\App\Http\Controllers\ShareController::class, 'destroy'])->name('shares.destroy');
});

// Public share link
Route::get('/shared/{token}', [\App\Http\Controllers\ShareController::class, 'accessByToken'])->name('shared.access');

// Public polling endpoint (untuk sync lintas device)
Route::get('/api/documents/{document}/poll', function(\App\Models\Document $document, \Illuminate\Http\Request $request) {
    $since = $request->query('since');
    $timeout = 25;
    $start = time();
    while (time() - $start < $timeout) {
        $doc = \App\Models\Document::find($document->id);
        $docTime = $doc->updated_at->timestamp;
        if ($since && $docTime > (int)$since) {
            return response()->json(['content'=>$doc->content,'title'=>$doc->title,'updated_at'=>$docTime,'last_editor_name'=>$doc->last_editor_name,'last_editor_color'=>$doc->last_editor_color,'changed'=>true]);
        }
        if (!$since) {
            return response()->json(['content'=>$doc->content,'title'=>$doc->title,'updated_at'=>$docTime,'last_editor_name'=>$doc->last_editor_name,'last_editor_color'=>$doc->last_editor_color,'changed'=>false]);
        }
        usleep(50000); // 50ms — sangat cepat
    }
    return response()->json(['changed'=>false,'updated_at'=>$document->updated_at->timestamp]);
});
