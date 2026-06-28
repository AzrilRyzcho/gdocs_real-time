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

    // ── VERSION HISTORY ──────────────────────────────────────────────────────
    Route::get('/documents/{document}/versions',                      [DocumentController::class, 'versionHistory'])->name('documents.versions');
    Route::post('/documents/{document}/versions',                     [DocumentController::class, 'saveVersion'])->name('documents.versions.save');
    Route::get('/documents/{document}/versions/{version}/preview',    [DocumentController::class, 'previewVersion'])->name('documents.versions.preview');
    Route::post('/documents/{document}/versions/{version}/restore',   [DocumentController::class, 'restoreVersion'])->name('documents.versions.restore');

    // ── EXPORT ───────────────────────────────────────────────────────────────
    Route::get('/documents/{document}/export/pdf',  [DocumentController::class, 'exportPdf'])->name('documents.export.pdf');
    Route::get('/documents/{document}/export/docx', [DocumentController::class, 'exportDocx'])->name('documents.export.docx');
});
