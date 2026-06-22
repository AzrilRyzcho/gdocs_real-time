<?php

namespace App\Http\Controllers;

use App\Events\DocumentUpdated;
use App\Models\Document;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    /**
     * Tampilkan halaman daftar dokumen / landing page.
     */
    public function index()
    {
        $documents = Document::latest()->take(20)->get(['id', 'title', 'share_token', 'updated_at']);
        return view('documents.index', compact('documents'));
    }

    /**
     * Buat dokumen baru, redirect ke editor.
     */
    public function store(Request $request)
    {
        $doc = Document::create([
            'title'   => $request->input('title', 'Dokumen Tanpa Judul'),
            'content' => '',
        ]);

        return redirect()->route('documents.edit', $doc->id);
    }

    /**
     * Halaman editor untuk dokumen tertentu.
     */
    public function edit(Document $document)
    {
        return view('documents.edit', compact('document'));
    }

    /**
     * Simpan konten dokumen (AJAX) dan broadcast ke semua user di channel.
     */
    public function update(Request $request, Document $document): JsonResponse
    {
        $validated = $request->validate([
            'content'     => 'nullable|string',
            'title'       => 'nullable|string|max:200',
            'editor_id'   => 'required|string',
            'editor_name' => 'required|string|max:50',
        ]);

        $document->update([
            'content' => $validated['content'] ?? $document->content,
            'title'   => $validated['title'] ?? $document->title,
        ]);

        // Broadcast perubahan ke semua listener di channel dokumen ini
        broadcast(new DocumentUpdated(
            documentId:  $document->id,
            content:     $document->content,
            title:       $document->title,
            editorId:    $validated['editor_id'],
            editorName:  $validated['editor_name'],
        ));

        return response()->json([
            'status'     => 'ok',
            'saved_at'   => now()->toIso8601String(),
        ]);
    }

    /**
     * Ambil konten terbaru dokumen (polling fallback / inital load).
     */
    public function show(Document $document): JsonResponse
    {
        return response()->json($document->only('id', 'title', 'content', 'updated_at'));
    }

    /**
     * Hapus dokumen.
     */
    public function destroy(Document $document)
    {
        $document->delete();
        return redirect()->route('documents.index');
    }
}
