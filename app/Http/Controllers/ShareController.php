<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentShare;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShareController extends Controller
{
    /**
     * Get share info for a document
     */
    public function index(Document $document): JsonResponse
    {
        $shares = $document->shares()->with('user:id,name,email')->get()->map(fn($s) => [
            'id'    => $s->id,
            'user'  => ['id' => $s->user->id, 'name' => $s->user->name, 'email' => $s->user->email],
            'role'  => $s->role,
        ]);

        return response()->json([
            'shares'      => $shares,
            'share_token' => $document->share_token,
            'share_link'  => url('/shared/' . $document->share_token),
        ]);
    }

    /**
     * Share document to a user by email
     */
    public function store(Request $request, Document $document): JsonResponse
    {
        if ($document->user_id !== auth()->id()) {
            return response()->json(['error' => 'Hanya pemilik yang bisa membagikan'], 403);
        }

        $validated = $request->validate([
            'email' => 'required|email',
            'role'  => 'required|in:editor,viewer',
        ]);

        $user = User::where('email', $validated['email'])->first();
        if (!$user) {
            return response()->json(['error' => 'User dengan email tersebut tidak ditemukan'], 404);
        }
        if ($user->id === auth()->id()) {
            return response()->json(['error' => 'Tidak bisa berbagi ke diri sendiri'], 422);
        }

        // Upsert share
        DocumentShare::updateOrCreate(
            ['document_id' => $document->id, 'user_id' => $user->id],
            ['role' => $validated['role']]
        );

        // Notify
        Notification::create([
            'user_id' => $user->id,
            'type'    => 'share',
            'title'   => auth()->user()->name . ' membagikan dokumen',
            'body'    => '"' . $document->title . '" — Anda sebagai ' . $validated['role'],
            'link'    => route('documents.edit', $document->id),
        ]);

        return response()->json(['status' => 'ok', 'message' => 'Berhasil dibagikan ke ' . $user->name]);
    }

    /**
     * Remove share
     */
    public function destroy(DocumentShare $share): JsonResponse
    {
        if ($share->document->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $share->delete();
        return response()->json(['status' => 'ok']);
    }

    /**
     * Access shared document via token (public link)
     */
    public function accessByToken(string $token)
    {
        $document = Document::where('share_token', $token)->firstOrFail();

        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Auto-add as viewer if not already shared
        if ($document->user_id !== auth()->id()) {
            DocumentShare::firstOrCreate(
                ['document_id' => $document->id, 'user_id' => auth()->id()],
                ['role' => 'viewer']
            );
        }

        return redirect()->route('documents.edit', $document->id);
    }
}
