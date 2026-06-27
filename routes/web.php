<?php

use App\Http\Controllers\DocumentController;
use Illuminate\Support\Facades\Route;

// Landing page → list dokumen
Route::get('/', [DocumentController::class, 'index'])->name('documents.index');

// Buat dokumen baru
Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');

// Editor halaman
Route::get('/documents/{document}/edit', [DocumentController::class, 'edit'])->name('documents.edit');

// API: ambil isi dokumen (initial load)
Route::get('/documents/{document}', [DocumentController::class, 'show'])->name('documents.show');

// API: simpan + broadcast perubahan
Route::patch('/documents/{document}', [DocumentController::class, 'update'])->name('documents.update');

// Hapus dokumen
Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');

// Broadcast posisi kursor
Route::post('/documents/{document}/cursor', [DocumentController::class, 'cursor'])->name('documents.cursor');
