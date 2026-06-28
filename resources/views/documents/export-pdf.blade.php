<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $document->title }} — Writly</title>
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }

  body {
    font-family: 'Calibri', 'Arial', sans-serif;
    font-size: 12pt;
    line-height: 1.5;
    color: #000;
    background: #fff;
  }

  .page {
    width: 210mm;
    min-height: 297mm;
    margin: 0 auto;
    padding: 25.4mm 25.4mm 25.4mm 25.4mm; /* 1 inch margins */
  }

  h1 { font-size: 20pt; margin: 12pt 0 6pt; }
  h2 { font-size: 16pt; margin: 10pt 0 4pt; }
  h3 { font-size: 13pt; margin: 8pt 0 3pt; }
  p  { margin: 0 0 8pt; }
  ul, ol { margin: 0 0 8pt 24pt; }
  li { margin-bottom: 4pt; }

  /* Print styles */
  @media print {
    body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    .no-print { display: none !important; }
    .page { margin: 0; width: 100%; }
    @page { margin: 25.4mm; size: A4; }
  }

  @media screen {
    body { background: #f1f3f4; }
    .page {
      background: white;
      box-shadow: 0 1px 3px rgba(60,64,67,.3), 0 4px 8px rgba(60,64,67,.15);
      margin: 40px auto;
    }
  }

  /* Print toolbar */
  .print-bar {
    position: fixed;
    top: 0; left: 0; right: 0;
    height: 56px;
    background: #fff;
    border-bottom: 1px solid #dadce0;
    display: flex;
    align-items: center;
    padding: 0 24px;
    gap: 12px;
    z-index: 100;
    box-shadow: 0 1px 4px rgba(0,0,0,.1);
  }

  .print-bar-title {
    flex: 1;
    font-size: 16px;
    font-weight: 500;
    color: #202124;
    font-family: 'Roboto', sans-serif;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .print-btn {
    height: 36px;
    padding: 0 24px;
    border: none;
    border-radius: 4px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    font-family: 'Roboto', sans-serif;
  }

  .print-btn-primary {
    background: #1a73e8;
    color: #fff;
  }

  .print-btn-primary:hover { background: #1557b0; }

  .print-btn-secondary {
    background: none;
    color: #1a73e8;
  }

  .print-btn-secondary:hover { background: #e8f0fe; }

  @media screen {
    body { padding-top: 56px; }
  }
</style>
</head>
<body>

{{-- Print toolbar (tidak ikut di-print) --}}
<div class="print-bar no-print">
  <svg width="28" height="28" viewBox="0 0 40 40" style="flex-shrink:0">
    <path d="M25 2H9C7.35 2 6 3.35 6 5v30c0 1.65 1.35 3 3 3h22c1.65 0 3-1.35 3-3V15L25 2z" fill="#4285f4"/>
    <path d="M25 2v13h13L25 2z" fill="#a8c7fa" opacity=".8"/>
    <path d="M11 21h18v2H11zm0 5h18v2H11zm0-10h10v2H11z" fill="#fff"/>
  </svg>
  <span class="print-bar-title">{{ $document->title }}</span>
  <button class="print-btn print-btn-secondary" onclick="history.back()">Kembali</button>
  <button class="print-btn print-btn-primary" onclick="window.print()">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" style="vertical-align:middle;margin-right:6px">
      <path d="M19 8H5c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zm-3 11H8v-5h8v5zm3-7c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-1-9H6v4h12V3z"/>
    </svg>
    Cetak / Simpan PDF
  </button>
</div>

{{-- Konten dokumen --}}
<div class="page">
  {!! $document->content !!}
</div>

<script>
  // Auto buka dialog print setelah halaman load
  window.addEventListener('load', () => {
    setTimeout(() => window.print(), 300);
  });
</script>
</body>
</html>
