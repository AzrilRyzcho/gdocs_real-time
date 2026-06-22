<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GDocs — Real-Time Productivity</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: #f8f9fa;
            color: #202124;
            min-height: 100vh;
        }

        /* ── Header ── */
        header {
            background: #fff;
            border-bottom: 1px solid #e0e0e0;
            padding: 0 24px;
            height: 64px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,.08);
        }
        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }
        .logo-icon {
            width: 40px; height: 40px;
            background: linear-gradient(135deg, #4285f4, #0d47a1);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
        }
        .logo-text { font-size: 22px; font-weight: 500; color: #202124; }
        .logo-text span { color: #4285f4; }

        /* ── Hero ── */
        .hero {
            background: linear-gradient(135deg, #4285f4 0%, #0d47a1 100%);
            color: #fff;
            padding: 60px 24px 80px;
            text-align: center;
        }
        .hero h1 { font-size: 2.6rem; font-weight: 600; margin-bottom: 12px; }
        .hero p  { font-size: 1.1rem; opacity: .88; margin-bottom: 36px; }

        .btn-new {
            background: #fff;
            color: #4285f4;
            border: none;
            padding: 14px 32px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 50px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex; align-items: center; gap: 8px;
            transition: transform .15s, box-shadow .15s;
            box-shadow: 0 4px 15px rgba(0,0,0,.15);
        }
        .btn-new:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,.2); }

        /* ── Dokumen list ── */
        .container { max-width: 900px; margin: 0 auto; padding: 40px 24px; }
        .section-title {
            font-size: 1rem;
            font-weight: 600;
            color: #5f6368;
            text-transform: uppercase;
            letter-spacing: .05em;
            margin-bottom: 16px;
        }

        .doc-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 16px;
        }

        .doc-card {
            background: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            overflow: hidden;
            text-decoration: none;
            color: inherit;
            transition: box-shadow .15s, border-color .15s;
            display: block;
        }
        .doc-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,.12); border-color: #c7c7c7; }

        .doc-preview {
            height: 140px;
            background: linear-gradient(135deg, #e8f0fe, #c2d4fd);
            display: flex; align-items: center; justify-content: center;
            font-size: 48px;
            border-bottom: 1px solid #e0e0e0;
        }
        .doc-info { padding: 12px; }
        .doc-title {
            font-size: 14px;
            font-weight: 500;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-bottom: 4px;
        }
        .doc-date { font-size: 12px; color: #80868b; }

        .empty-state {
            text-align: center;
            padding: 60px 0;
            color: #80868b;
        }
        .empty-state .icon { font-size: 64px; margin-bottom: 16px; }
        .empty-state p { font-size: 15px; }

        /* ── New doc form ── */
        .new-doc-bar {
            display: flex; gap: 8px; margin-bottom: 32px;
        }
        .new-doc-bar input {
            flex: 1;
            padding: 10px 16px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            outline: none;
            transition: border-color .2s;
        }
        .new-doc-bar input:focus { border-color: #4285f4; }
        .btn-create {
            background: #4285f4;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            display: flex; align-items: center; gap: 6px;
            transition: background .15s;
        }
        .btn-create:hover { background: #1a73e8; }
    </style>
</head>
<body>

<header>
    <a href="{{ route('documents.index') }}" class="logo">
        <div class="logo-icon">📝</div>
        <span class="logo-text">G<span>Docs</span> Lite</span>
    </a>
</header>

<div class="hero">
    <h1>📄 Real-Time Productivity</h1>
    <p>Buat, edit, dan kolaborasi dokumen secara real-time bersama tim kamu di jaringan yang sama.</p>
</div>

<div class="container">

    {{-- Form buat dokumen baru --}}
    <form method="POST" action="{{ route('documents.store') }}" class="new-doc-bar">
        @csrf
        <input type="text" name="title" placeholder="Nama dokumen baru..." maxlength="200" autocomplete="off">
        <button type="submit" class="btn-create">
            ✚ Buat Dokumen
        </button>
    </form>

    @if($documents->isNotEmpty())
        <div class="section-title">📁 Dokumen Terbaru</div>
        <div class="doc-grid">
            @foreach($documents as $doc)
                <a href="{{ route('documents.edit', $doc->id) }}" class="doc-card">
                    <div class="doc-preview">📄</div>
                    <div class="doc-info">
                        <div class="doc-title">{{ $doc->title ?: 'Tanpa Judul' }}</div>
                        <div class="doc-date">{{ $doc->updated_at->diffForHumans() }}</div>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <div class="icon">📂</div>
            <p>Belum ada dokumen. Buat dokumen pertama kamu di atas!</p>
        </div>
    @endif

</div>

</body>
</html>
