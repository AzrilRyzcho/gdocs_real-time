<?php

use App\Http\Controllers\DocumentController;
use Illuminate\Support\Facades\Route;

// Landing page
Route::get('/', [DocumentController::class, 'index'])->name('documents.index');

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
