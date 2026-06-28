<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Document;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Get all comments for a document (nested replies)
     */
    public function index(Document $document): JsonResponse
    {
        $comments = $document->comments()
            ->whereNull('parent_id')
            ->with(['user:id,name', 'replies.user:id,name'])
            ->orderByDesc('created_at')
            ->get()
            ->map(fn($c) => $this->formatComment($c));

        return response()->json(['comments' => $comments]);
    }

    /**
     * Store a new comment
     */
    public function store(Request $request, Document $document): JsonResponse
    {
        $validated = $request->validate([
            'body'      => 'required|string|max:2000',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $comment = $document->comments()->create([
            'user_id'   => auth()->id(),
            'body'      => $validated['body'],
            'parent_id' => $validated['parent_id'] ?? null,
        ]);

        $comment->load('user:id,name');

        // Kirim notifikasi ke pemilik dokumen (jika bukan diri sendiri)
        if ($document->user_id && $document->user_id !== auth()->id()) {
            Notification::create([
                'user_id' => $document->user_id,
                'type'    => 'comment',
                'title'   => auth()->user()->name . ' mengomentari dokumen',
                'body'    => '"' . mb_substr($validated['body'], 0, 80) . '"',
                'link'    => route('documents.edit', $document->id),
            ]);
        }

        // Cek mention (@username) di body
        preg_match_all('/@(\w+)/', $validated['body'], $matches);
        if (!empty($matches[1])) {
            $mentionedUsers = \App\Models\User::whereIn('name', $matches[1])->get();
            foreach ($mentionedUsers as $mu) {
                if ($mu->id === auth()->id()) continue;
                Notification::create([
                    'user_id' => $mu->id,
                    'type'    => 'mention',
                    'title'   => auth()->user()->name . ' menyebut Anda',
                    'body'    => '"' . mb_substr($validated['body'], 0, 80) . '"',
                    'link'    => route('documents.edit', $document->id),
                ]);
            }
        }

        return response()->json([
            'status'  => 'ok',
            'comment' => $this->formatComment($comment),
        ]);
    }

    /**
     * Resolve / unresolve a comment
     */
    public function resolve(Comment $comment): JsonResponse
    {
        $comment->update(['resolved' => !$comment->resolved]);
        return response()->json(['status' => 'ok', 'resolved' => $comment->resolved]);
    }

    /**
     * Delete a comment
     */
    public function destroy(Comment $comment): JsonResponse
    {
        if ($comment->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $comment->delete();
        return response()->json(['status' => 'ok']);
    }

    private function formatComment($c): array
    {
        return [
            'id'         => $c->id,
            'body'       => $c->body,
            'resolved'   => $c->resolved,
            'user_name'  => $c->user->name ?? 'Anonim',
            'user_id'    => $c->user_id,
            'is_mine'    => $c->user_id === auth()->id(),
            'created_at' => $c->created_at->locale('id')->diffForHumans(),
            'replies'    => $c->relationLoaded('replies')
                ? $c->replies->map(fn($r) => $this->formatComment($r))->toArray()
                : [],
        ];
    }
}
