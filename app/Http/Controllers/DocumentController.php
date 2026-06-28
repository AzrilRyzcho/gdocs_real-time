<?php

namespace App\Http\Controllers;

use App\Events\DocumentUpdated;
use App\Models\Document;
use App\Models\DocumentVersion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use ZipArchive;

class DocumentController extends Controller
{
    use AuthorizesRequests;

    /**
     * Tampilkan halaman daftar dokumen / landing page.
     */
    public function index()
    {
        // Dokumen milik sendiri
        $ownDocs = auth()->user()->documents()
            ->where('is_archived', false)
            ->latest()
            ->get(['id', 'title', 'content', 'share_token', 'is_favorite', 'is_archived', 'updated_at', 'last_editor_name', 'last_editor_color', 'last_edited_at']);

        // Dokumen yang di-share ke user ini
        $sharedDocs = auth()->user()->sharedDocuments()
            ->where('is_archived', false)
            ->latest()
            ->get(['documents.id', 'title', 'content', 'share_token', 'is_favorite', 'is_archived', 'updated_at', 'last_editor_name', 'last_editor_color', 'last_edited_at']);

        // Gabungkan, sort by updated_at desc
        $documents = $ownDocs->merge($sharedDocs)->sortByDesc('updated_at')->take(50)->values();

        return view('documents.index', compact('documents'));
    }

    /**
     * Buat dokumen baru, redirect ke editor.
     */
    public function store(Request $request)
    {
        $doc = auth()->user()->documents()->create([
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
        // Cek ownership ATAU shared access
        $isOwner  = $document->user_id === null || $document->user_id === auth()->id();
        $isShared = $document->shares()->where('user_id', auth()->id())->exists();

        if (!$isOwner && !$isShared) {
            abort(403, 'Anda tidak punya akses ke dokumen ini.');
        }

        $shareRole = $isOwner ? 'owner' : $document->shares()->where('user_id', auth()->id())->value('role');

        return view('documents.edit', compact('document', 'shareRole'));
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
            'color'       => 'nullable|string|max:20',
        ]);

        $document->update([
            'content'          => $validated['content'] ?? $document->content,
            'title'            => $validated['title'] ?? $document->title,
            'last_editor_id'   => $validated['editor_id'],
            'last_editor_name' => $validated['editor_name'],
            'last_editor_color'=> $validated['color'] ?? null,
            'last_edited_at'   => now(),
        ]);

        // Broadcast perubahan ke semua listener di channel dokumen ini
        broadcast(new DocumentUpdated(
            documentId:  $document->id,
            content:     $document->content,
            title:       $document->title,
            editorId:    $validated['editor_id'],
            editorName:  $validated['editor_name'],
            color:       $validated['color'] ?? null,
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
        if ($document->user_id !== null && $document->user_id !== auth()->id()) {
            abort(403);
        }
        $document->delete();
        return redirect()->route('documents.index');
    }

    /**
     * Rename dokumen saja (tanpa mengubah content).
     */
    public function rename(Request $request, Document $document): JsonResponse
    {
        if ($document->user_id !== null && $document->user_id !== auth()->id()) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:200',
        ]);

        $document->update(['title' => $validated['title']]);

        return response()->json([
            'status' => 'ok',
            'title'  => $document->title,
        ]);
    }

    /**
     * Toggle favorite status.
     */
    public function toggleFavorite(Document $document): JsonResponse
    {
        if ($document->user_id !== null && $document->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $document->update(['is_favorite' => !$document->is_favorite]);
        return response()->json(['status' => 'ok', 'is_favorite' => $document->is_favorite]);
    }

    /**
     * Toggle archive status.
     */
    public function toggleArchive(Document $document): JsonResponse
    {
        if ($document->user_id !== null && $document->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $document->update(['is_archived' => !$document->is_archived]);
        return response()->json(['status' => 'ok', 'is_archived' => $document->is_archived]);
    }

    /**
     * Duplikasi dokumen.
     */
    public function duplicate(Document $document)
    {
        if ($document->user_id !== null && $document->user_id !== auth()->id()) {
            abort(403);
        }
        $new = auth()->user()->documents()->create([
            'title'   => $document->title . ' (Salinan)',
            'content' => $document->content,
        ]);
        return redirect()->route('documents.edit', $new->id);
    }

    /**
     * Presence: broadcast status join/leave/ping user.
     * Dipanggil saat user buka dokumen, tiap 10 detik (ping), dan saat tutup tab.
     */
    public function presence(Request $request, Document $document): JsonResponse
    {
        $validated = $request->validate([
            'user_id'   => 'required|string',
            'user_name' => 'required|string|max:50',
            'color'     => 'required|string|max:20',
            'action'    => 'required|in:join,leave,ping',
        ]);

        broadcast(new \App\Events\UserPresence(
            documentId: $document->id,
            userId:     $validated['user_id'],
            userName:   $validated['user_name'],
            color:      $validated['color'],
            action:     $validated['action'],
        ));

        return response()->json(['status' => 'ok']);
    }

    /**
     * Broadcast konten real-time ke semua listener TANPA menyimpan ke DB.
     * Dipanggil setiap keystroke (~50ms debounce) untuk efek real-time.
     */
    public function broadcastOnly(Request $request, Document $document): JsonResponse
    {
        $validated = $request->validate([
            'content'     => 'nullable|string',
            'title'       => 'nullable|string|max:200',
            'editor_id'   => 'required|string',
            'editor_name' => 'required|string|max:50',
        ]);

        broadcast(new DocumentUpdated(
            documentId:  $document->id,
            content:     $validated['content'] ?? '',
            title:       $validated['title'] ?? $document->title,
            editorId:    $validated['editor_id'],
            editorName:  $validated['editor_name'],
        ));

        return response()->json(['status' => 'ok']);
    }

    /**
     * Broadcast posisi kursor user ke semua listener.
     */
    public function cursor(Request $request, Document $document): JsonResponse
    {
        $validated = $request->validate([
            'editor_id'   => 'required|string',
            'editor_name' => 'required|string|max:50',
            'color'       => 'required|string|max:20',
            'offset'      => 'required|integer|min:0',
            'is_typing'   => 'boolean',
        ]);

        broadcast(new \App\Events\CursorMoved(
            documentId: $document->id,
            editorId:   $validated['editor_id'],
            editorName: $validated['editor_name'],
            color:      $validated['color'],
            offset:     $validated['offset'],
            isTyping:   $validated['is_typing'] ?? false,
        ));

        return response()->json(['status' => 'ok']);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // VERSION HISTORY
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Simpan snapshot versi baru (dipanggil tiap auto-save dari JS).
     * Hanya simpan jika konten benar-benar berbeda dari versi terakhir.
     */
    public function saveVersion(Request $request, Document $document): JsonResponse
    {
        $validated = $request->validate([
            'content'     => 'nullable|string',
            'title'       => 'nullable|string|max:200',
            'editor_name' => 'nullable|string|max:50',
            'editor_color'=> 'nullable|string|max:20',
        ]);

        $content = $validated['content'] ?? $document->content ?? '';
        $title   = $validated['title']   ?? $document->title;

        // Cek apakah konten berbeda dari versi terakhir — hindari duplikasi
        $last = $document->versions()->first();
        if ($last && $last->content === $content && $last->title === $title) {
            return response()->json(['status' => 'skip', 'message' => 'Tidak ada perubahan']);
        }

        // Maksimal 50 versi per dokumen — hapus yang paling lama
        $count = $document->versions()->count();
        if ($count >= 50) {
            $document->versions()->orderBy('created_at')->first()?->delete();
        }

        DocumentVersion::create([
            'document_id'  => $document->id,
            'title'        => $title,
            'content'      => $content,
            'editor_name'  => $validated['editor_name'] ?? null,
            'editor_color' => $validated['editor_color'] ?? null,
            'created_at'   => now(),
        ]);

        return response()->json(['status' => 'ok']);
    }

    /**
     * Ambil daftar versi dokumen (untuk sidebar history).
     */
    public function versionHistory(Document $document): JsonResponse
    {
        if ($document->user_id !== null && $document->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $versions = $document->versions()
            ->select('id', 'title', 'editor_name', 'editor_color', 'created_at')
            ->get()
            ->map(fn($v) => [
                'id'           => $v->id,
                'title'        => $v->title,
                'editor_name'  => $v->editor_name,
                'editor_color' => $v->editor_color ?? '#4285f4',
                'created_at'   => $v->created_at->locale('id')->diffForHumans(),
                'created_at_full' => $v->created_at->setTimezone('Asia/Jakarta')->format('d M Y, H:i'),
            ]);

        return response()->json(['versions' => $versions]);
    }

    /**
     * Preview konten versi tertentu.
     */
    public function previewVersion(Document $document, DocumentVersion $version): JsonResponse
    {
        if ($version->document_id !== $document->id) {
            return response()->json(['error' => 'Not found'], 404);
        }

        return response()->json([
            'id'      => $version->id,
            'title'   => $version->title,
            'content' => $version->content,
        ]);
    }

    /**
     * Restore ke versi tertentu — simpan versi saat ini dulu, lalu ganti.
     */
    public function restoreVersion(Request $request, Document $document, DocumentVersion $version): JsonResponse
    {
        if ($document->user_id !== null && $document->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        if ($version->document_id !== $document->id) {
            return response()->json(['error' => 'Not found'], 404);
        }

        // Simpan versi saat ini sebelum di-restore
        DocumentVersion::create([
            'document_id'  => $document->id,
            'title'        => $document->title,
            'content'      => $document->content,
            'editor_name'  => auth()->user()->name ?? 'Unknown',
            'editor_color' => '#ea4335',
            'created_at'   => now(),
        ]);

        // Restore ke versi yang dipilih
        $document->update([
            'title'            => $version->title,
            'content'          => $version->content,
            'last_editor_name' => auth()->user()->name ?? 'Unknown',
            'last_edited_at'   => now(),
        ]);

        return response()->json([
            'status'  => 'ok',
            'title'   => $document->title,
            'content' => $document->content,
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // EXPORT
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Export dokumen sebagai PDF (via Blade → HTML → print-friendly response).
     */
    public function exportPdf(Document $document)
    {
        if ($document->user_id !== null && $document->user_id !== auth()->id()) {
            abort(403);
        }

        return view('documents.export-pdf', compact('document'));
    }

    /**
     * Export dokumen sebagai DOCX (Open XML format via ZipArchive).
     * Tidak membutuhkan Composer package — murni PHP built-in.
     */
    public function exportDocx(Document $document)
    {
        if ($document->user_id !== null && $document->user_id !== auth()->id()) {
            abort(403);
        }

        $title   = $document->title ?: 'Dokumen';
        $content = $document->content ?? '';

        // Konversi HTML ke teks plain dengan formatting dasar
        $body = $this->htmlToDocxXml($content);

        // Buat file DOCX (format ZIP berisi XML)
        $tmpFile = tempnam(sys_get_temp_dir(), 'docx_') . '.docx';
        $zip     = new ZipArchive();
        $zip->open($tmpFile, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        // [Content_Types].xml
        $zip->addFromString('[Content_Types].xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">
  <Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>
  <Default Extension="xml"  ContentType="application/xml"/>
  <Override PartName="/word/document.xml"
    ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.document.main+xml"/>
  <Override PartName="/word/styles.xml"
    ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.styles+xml"/>
</Types>');

        // _rels/.rels
        $zip->addFromString('_rels/.rels', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1"
    Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument"
    Target="word/document.xml"/>
</Relationships>');

        // word/_rels/document.xml.rels
        $zip->addFromString('word/_rels/document.xml.rels', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1"
    Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles"
    Target="styles.xml"/>
</Relationships>');

        // word/styles.xml
        $zip->addFromString('word/styles.xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<w:styles xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main">
  <w:style w:type="paragraph" w:styleId="Normal" w:default="1">
    <w:name w:val="Normal"/>
    <w:rPr><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr>
  </w:style>
  <w:style w:type="paragraph" w:styleId="Heading1">
    <w:name w:val="heading 1"/>
    <w:pPr><w:outlineLvl w:val="0"/></w:pPr>
    <w:rPr><w:b/><w:sz w:val="40"/><w:szCs w:val="40"/></w:rPr>
  </w:style>
  <w:style w:type="paragraph" w:styleId="Heading2">
    <w:name w:val="heading 2"/>
    <w:pPr><w:outlineLvl w:val="1"/></w:pPr>
    <w:rPr><w:b/><w:sz w:val="32"/><w:szCs w:val="32"/></w:rPr>
  </w:style>
</w:styles>');

        // word/document.xml
        $docXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<w:document xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main">
  <w:body>
    ' . $body . '
    <w:sectPr>
      <w:pgSz w:w="12240" w:h="15840"/>
      <w:pgMar w:top="1440" w:right="1440" w:bottom="1440" w:left="1440"/>
    </w:sectPr>
  </w:body>
</w:document>';
        $zip->addFromString('word/document.xml', $docXml);
        $zip->close();

        $filename = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $title) . '.docx';

        return response()->download($tmpFile, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ])->deleteFileAfterSend(true);
    }

    /**
     * Konversi HTML ke Open XML paragraphs untuk DOCX.
     */
    private function htmlToDocxXml(string $html): string
    {
        if (empty(trim($html))) {
            return '<w:p><w:r><w:t/></w:r></w:p>';
        }

        // Strip script/style tags
        $html = preg_replace('/<(script|style)[^>]*>.*?<\/(script|style)>/is', '', $html);

        $xml     = '';
        $dom     = new \DOMDocument();
        $charset = '<?xml encoding="UTF-8">';
        @$dom->loadHTML($charset . '<div>' . $html . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        $body = $dom->getElementsByTagName('div')->item(0);
        if (!$body) {
            return '<w:p><w:r><w:t>' . htmlspecialchars(strip_tags($html)) . '</w:t></w:r></w:p>';
        }

        foreach ($body->childNodes as $node) {
            $xml .= $this->nodeToDocxXml($node);
        }

        return $xml ?: '<w:p><w:r><w:t/></w:r></w:p>';
    }

    private function nodeToDocxXml(\DOMNode $node): string
    {
        if ($node->nodeType === XML_TEXT_NODE) {
            $text = trim($node->nodeValue);
            if ($text === '') return '';
            return '<w:p><w:r><w:t xml:space="preserve">' . htmlspecialchars($text) . '</w:t></w:r></w:p>';
        }

        if ($node->nodeType !== XML_ELEMENT_NODE) return '';

        $tag = strtolower($node->nodeName);

        // Headings
        if (in_array($tag, ['h1', 'h2', 'h3'])) {
            $styleId = ['h1' => 'Heading1', 'h2' => 'Heading2', 'h3' => 'Heading2'][$tag];
            $text    = htmlspecialchars($node->textContent);
            return "<w:p><w:pPr><w:pStyle w:val=\"{$styleId}\"/></w:pPr><w:r><w:t xml:space=\"preserve\">{$text}</w:t></w:r></w:p>";
        }

        // List items
        if ($tag === 'li') {
            $text = htmlspecialchars($node->textContent);
            return "<w:p><w:pPr><w:ind w:left=\"720\"/></w:pPr><w:r><w:t xml:space=\"preserve\">• {$text}</w:t></w:r></w:p>";
        }

        // Line break
        if ($tag === 'br') {
            return '<w:p><w:r><w:t/></w:r></w:p>';
        }

        // Paragraph / div — rekursif konten inline
        if (in_array($tag, ['p', 'div'])) {
            $runs = $this->inlineNodesToRuns($node);
            if (empty(trim(strip_tags($node->textContent)))) {
                return '<w:p><w:r><w:t/></w:r></w:p>';
            }
            return "<w:p><w:r>{$runs}</w:r></w:p>";
        }

        // ul/ol — rekursif ke children
        if (in_array($tag, ['ul', 'ol'])) {
            $xml = '';
            foreach ($node->childNodes as $child) {
                $xml .= $this->nodeToDocxXml($child);
            }
            return $xml;
        }

        // Fallback — rekursif
        $xml = '';
        foreach ($node->childNodes as $child) {
            $xml .= $this->nodeToDocxXml($child);
        }
        return $xml;
    }

    private function inlineNodesToRuns(\DOMNode $node): string
    {
        $xml = '';
        foreach ($node->childNodes as $child) {
            if ($child->nodeType === XML_TEXT_NODE) {
                $text = htmlspecialchars($child->nodeValue);
                if ($text !== '') {
                    $xml .= "<w:t xml:space=\"preserve\">{$text}</w:t>";
                }
            } elseif ($child->nodeType === XML_ELEMENT_NODE) {
                $tag  = strtolower($child->nodeName);
                $text = htmlspecialchars($child->textContent);
                if (in_array($tag, ['strong', 'b'])) {
                    $xml .= "<w:rPr><w:b/></w:rPr><w:t xml:space=\"preserve\">{$text}</w:t>";
                } elseif (in_array($tag, ['em', 'i'])) {
                    $xml .= "<w:rPr><w:i/></w:rPr><w:t xml:space=\"preserve\">{$text}</w:t>";
                } elseif ($tag === 'u') {
                    $xml .= "<w:rPr><w:u w:val=\"single\"/></w:rPr><w:t xml:space=\"preserve\">{$text}</w:t>";
                } else {
                    $xml .= "<w:t xml:space=\"preserve\">{$text}</w:t>";
                }
            }
        }
        return $xml;
    }
}
