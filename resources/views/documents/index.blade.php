<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dokumen</title>
<style>
*{margin:0;padding:0;box-sizing:border-box;}
:root{
  --blue:#1a73e8;--dark:#202124;--grey:#5f6368;
  --border:#e0e0e0;--hover:#f1f3f4;--bg:#f0f4f9;
}
body{font-family:'Google Sans',Arial,sans-serif;background:var(--bg);color:var(--dark);min-height:100vh;}

/* ── TOP NAV ── */
nav{
  background:#fff;height:64px;
  display:flex;align-items:center;
  padding:0 16px;gap:4px;
  border-bottom:1px solid var(--border);
  position:sticky;top:0;z-index:100;
}
.nav-menu-btn{
  background:none;border:none;cursor:pointer;
  padding:8px;border-radius:50%;color:var(--grey);
  font-size:18px;line-height:1;transition:background .15s;
  display:flex;align-items:center;justify-content:center;width:40px;height:40px;
}
.nav-menu-btn:hover{background:var(--hover);}
.nav-logo{display:flex;align-items:center;gap:4px;text-decoration:none;margin:0 8px;}
.nav-logo-icon{width:40px;height:40px;flex-shrink:0;}
.nav-logo-text{font-size:22px;color:#5f6368;font-weight:400;letter-spacing:-.3px;}
.nav-search{
  flex:1;max-width:720px;margin:0 24px;
  background:#f1f3f4;border:1px solid transparent;
  border-radius:24px;display:flex;align-items:center;
  padding:0 20px;gap:12px;height:46px;
  transition:background .2s,box-shadow .2s;
}
.nav-search:focus-within{background:#fff;box-shadow:0 1px 6px rgba(32,33,36,.28);}
.nav-search input{
  flex:1;border:none;outline:none;background:transparent;
  font-size:16px;color:var(--dark);
}
.nav-search input::placeholder{color:var(--grey);}
.nav-right{margin-left:auto;display:flex;align-items:center;gap:4px;}
.nav-icon-btn{
  background:none;border:none;cursor:pointer;
  padding:8px;border-radius:50%;color:var(--grey);
  display:flex;align-items:center;justify-content:center;
  width:40px;height:40px;transition:background .15s;
}
.nav-icon-btn:hover{background:var(--hover);}
.nav-avatar{
  width:36px;height:36px;border-radius:50%;
  background:linear-gradient(135deg,#4285f4,#0d47a1);
  display:flex;align-items:center;justify-content:center;
  font-size:14px;font-weight:700;color:#fff;cursor:pointer;margin-left:4px;
}

/* ── TEMPLATE SECTION ── */
.template-section{
  background:#fff;padding:20px 0 20px;
  border-bottom:1px solid var(--border);
}
.template-inner{max-width:1100px;margin:0 auto;padding:0 24px;}
.section-header{
  display:flex;align-items:center;justify-content:space-between;
  margin-bottom:14px;
}
.section-title{font-size:15px;font-weight:500;color:var(--dark);}
.tg-right{display:flex;align-items:center;gap:2px;}
.tg-btn{
  background:none;border:none;cursor:pointer;font-size:13px;
  color:var(--grey);padding:6px 10px;border-radius:4px;
  display:flex;align-items:center;gap:4px;transition:background .15s;
}
.tg-btn:hover{background:var(--hover);}
.tg-more-btn{
  background:none;border:none;cursor:pointer;
  padding:6px;border-radius:50%;color:var(--grey);
  display:flex;align-items:center;justify-content:center;
  width:32px;height:32px;transition:background .15s;
}
.tg-more-btn:hover{background:var(--hover);}

.templates-row{
  display:flex;gap:10px;
  overflow-x:auto;padding-bottom:4px;
}
.templates-row::-webkit-scrollbar{height:0;}

.tmpl-card{
  display:flex;flex-direction:column;align-items:flex-start;
  gap:6px;cursor:pointer;flex-shrink:0;width:128px;
  background:none;border:none;padding:0;text-align:left;
}
.tmpl-thumb{
  width:128px;height:166px;
  background:#fff;
  border:1px solid #dadce0;
  border-radius:2px;overflow:hidden;
  transition:border-color .15s,box-shadow .15s;
  position:relative;flex-shrink:0;
}
.tmpl-card:hover .tmpl-thumb{
  border-color:#1a73e8;
  box-shadow:0 1px 6px rgba(0,0,0,.2);
}
.tmpl-name{font-size:13px;font-weight:500;color:var(--dark);line-height:1.3;}
.tmpl-sub{font-size:12px;color:var(--grey);line-height:1.3;}

/* Plus icon (Blank) */
.blank-thumb{display:flex;align-items:center;justify-content:center;}
.gplus{width:52px;height:52px;position:relative;}
.gplus-h{position:absolute;top:50%;left:0;transform:translateY(-50%);width:100%;height:8px;background:linear-gradient(to right,#4285f4 0%,#4285f4 50%,#34a853 50%);}
.gplus-v{position:absolute;left:50%;top:0;transform:translateX(-50%);width:8px;height:100%;background:linear-gradient(to bottom,#ea4335 0%,#ea4335 50%,#fbbc04 50%);}

/* Template line previews */
.tp{padding:10px 8px;display:flex;flex-direction:column;gap:3px;height:100%;}
.tl{height:3px;border-radius:1px;background:#e0e0e0;}
.tl.h{height:5px;background:#bdc1c6;}
.tl.b{background:#4285f4;}.tl.r{background:#ea4335;}
.tl.t{background:#009688;}.tl.o{background:#ff7043;}
</style>

{{-- ── NAV ── --}}
<nav>
  <button class="nav-menu-btn">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><rect y="5" width="24" height="2" rx="1"/><rect y="11" width="24" height="2" rx="1"/><rect y="17" width="24" height="2" rx="1"/></svg>
  </button>
  <a href="/" class="nav-logo">
    <svg class="nav-logo-icon" viewBox="0 0 48 48">
      <path d="M30 2H10C7.8 2 6 3.8 6 6v36c0 2.2 1.8 4 4 4h28c2.2 0 4-1.8 4-4V14L30 2z" fill="#4285f4"/>
      <path d="M30 14h12L30 2z" fill="#1967d2"/>
      <rect x="13" y="22" width="22" height="2" rx="1" fill="#fff"/>
      <rect x="13" y="27" width="22" height="2" rx="1" fill="#fff"/>
      <rect x="13" y="32" width="16" height="2" rx="1" fill="#fff"/>
    </svg>
    <span class="nav-logo-text">Dokumen</span>
  </a>
  <div class="nav-search">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#5f6368" stroke-width="2.5"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.35-4.35"/></svg>
    <input type="text" placeholder="Telusuri" id="searchInput">
  </div>
  <div class="nav-right">
    <button class="nav-icon-btn" title="Aplikasi Google">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="#5f6368"><circle cx="5" cy="5" r="1.8"/><circle cx="12" cy="5" r="1.8"/><circle cx="19" cy="5" r="1.8"/><circle cx="5" cy="12" r="1.8"/><circle cx="12" cy="12" r="1.8"/><circle cx="19" cy="12" r="1.8"/><circle cx="5" cy="19" r="1.8"/><circle cx="12" cy="19" r="1.8"/><circle cx="19" cy="19" r="1.8"/></svg>
    </button>
    <div class="nav-avatar">A</div>
  </div>
</nav>

{{-- ── TEMPLATE SECTION ── --}}
<div class="template-section">
  <div class="template-inner">
    <div class="section-header">
      <span class="section-title">Mulai dokumen baru</span>
      <div class="tg-right">
        <button class="tg-btn">
          Galeri template
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>
        </button>
        <button class="tg-more-btn">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="#5f6368"><circle cx="12" cy="5" r="1.8"/><circle cx="12" cy="12" r="1.8"/><circle cx="12" cy="19" r="1.8"/></svg>
        </button>
      </div>
    </div>

    <div class="templates-row">

      {{-- Dokumen kosong --}}
      <form method="POST" action="{{ route('documents.store') }}" style="display:contents">
        @csrf
        <input type="hidden" name="title" value="Dokumen tanpa judul">
        <button type="submit" class="tmpl-card">
          <div class="tmpl-thumb blank-thumb">
            <div class="gplus"><div class="gplus-v"></div><div class="gplus-h"></div></div>
          </div>
          <div class="tmpl-name">Dokumen kosong</div>
        </button>
      </form>

      {{-- Resume Serif --}}
      <form method="POST" action="{{ route('documents.store') }}" style="display:contents">
        @csrf
        <input type="hidden" name="title" value="Resume">
        <button type="submit" class="tmpl-card">
          <div class="tmpl-thumb">
            <div class="tp">
              <div class="tl h" style="width:60%;margin-bottom:4px;"></div>
              <div class="tl" style="width:40%;margin-bottom:6px;"></div>
              @for($i=0;$i<3;$i++)<div class="tl" style="width:90%;"></div><div class="tl" style="width:70%;margin-bottom:4px;"></div>@endfor
              <div class="tl b" style="width:45%;height:2px;margin-bottom:4px;"></div>
              @for($i=0;$i<4;$i++)<div class="tl" style="width:{{85-$i*5}}%;"></div>@endfor
            </div>
          </div>
          <div class="tmpl-name">Resume</div>
          <div class="tmpl-sub">Serif</div>
        </button>
      </form>

      {{-- Resume Koral --}}
      <form method="POST" action="{{ route('documents.store') }}" style="display:contents">
        @csrf
        <input type="hidden" name="title" value="Resume - Koral">
        <button type="submit" class="tmpl-card">
          <div class="tmpl-thumb">
            <div style="background:#e53935;height:7px;width:100%;"></div>
            <div class="tp">
              <div class="tl h" style="width:65%;color:#e53935;margin-bottom:4px;"></div>
              <div class="tl r" style="width:35%;height:2px;margin-bottom:6px;"></div>
              @for($i=0;$i<5;$i++)<div class="tl" style="width:{{80+$i*2}}%;margin-bottom:2px;"></div>@endfor
              <div style="height:6px;"></div>
              @for($i=0;$i<4;$i++)<div class="tl" style="width:{{70-$i*4}}%;margin-bottom:2px;"></div>@endfor
            </div>
          </div>
          <div class="tmpl-name">Resume</div>
          <div class="tmpl-sub">Koral</div>
        </button>
      </form>

      {{-- Surat Hijau daun mint --}}
      <form method="POST" action="{{ route('documents.store') }}" style="display:contents">
        @csrf
        <input type="hidden" name="title" value="Surat">
        <button type="submit" class="tmpl-card">
          <div class="tmpl-thumb">
            <div style="background:#00897b;height:5px;width:100%;"></div>
            <div class="tp">
              <div class="tl t" style="width:50%;height:2px;margin-bottom:8px;"></div>
              @for($i=0;$i<3;$i++)<div class="tl" style="width:{{60+$i*10}}%;margin-bottom:2px;"></div>@endfor
              <div style="height:8px;"></div>
              @for($i=0;$i<7;$i++)<div class="tl" style="width:{{88-($i%3)*5}}%;margin-bottom:2px;"></div>@endfor
            </div>
          </div>
          <div class="tmpl-name">Surat</div>
          <div class="tmpl-sub">Hijau daun mint</div>
        </button>
      </form>

      {{-- Proposal Proyek Tropis --}}
      <form method="POST" action="{{ route('documents.store') }}" style="display:contents">
        @csrf
        <input type="hidden" name="title" value="Proposal Proyek">
        <button type="submit" class="tmpl-card">
          <div class="tmpl-thumb">
            <div style="height:70px;background:linear-gradient(135deg,#4db6ac 0%,#009688 40%,#795548 40%,#5d4037 70%,#ff8a65 70%);position:relative;">
              <div style="position:absolute;bottom:6px;left:8px;font-size:8px;font-weight:700;color:#fff;">Nama Proyek</div>
            </div>
            <div class="tp" style="padding-top:8px;">
              @for($i=0;$i<6;$i++)<div class="tl" style="width:{{75+$i*3}}%;margin-bottom:3px;"></div>@endfor
            </div>
          </div>
          <div class="tmpl-name">Proposal Proyek</div>
          <div class="tmpl-sub">Tropis</div>
        </button>
      </form>

      {{-- Brosur Geometrik --}}
      <form method="POST" action="{{ route('documents.store') }}" style="display:contents">
        @csrf
        <input type="hidden" name="title" value="Brosur">
        <button type="submit" class="tmpl-card">
          <div class="tmpl-thumb">
            <div style="height:80px;background:linear-gradient(160deg,#1565c0 50%,#e91e63 50%);display:flex;align-items:center;justify-content:center;">
              <div style="width:30px;height:30px;background:#fff;border-radius:50%;opacity:.8;"></div>
            </div>
            <div class="tp" style="padding-top:8px;">
              <div class="tl h" style="width:55%;margin-bottom:4px;"></div>
              @for($i=0;$i<5;$i++)<div class="tl" style="width:{{80-$i*4}}%;margin-bottom:2px;"></div>@endfor
            </div>
          </div>
          <div class="tmpl-name">Brosur</div>
          <div class="tmpl-sub">Geometrik</div>
        </button>
      </form>

      {{-- Laporan Luks --}}
      <form method="POST" action="{{ route('documents.store') }}" style="display:contents">
        @csrf
        <input type="hidden" name="title" value="Laporan">
        <button type="submit" class="tmpl-card">
          <div class="tmpl-thumb">
            <div style="background:#212121;height:72px;display:flex;align-items:flex-end;padding:8px;">
              <div>
                <div style="font-size:7px;font-weight:700;color:#fff;letter-spacing:1px;">LAPORAN</div>
                <div style="font-size:6px;color:#bdbdbd;">TAHUNAN</div>
              </div>
            </div>
            <div class="tp" style="padding-top:8px;">
              <div class="tl h" style="width:70%;margin-bottom:4px;"></div>
              @for($i=0;$i<5;$i++)<div class="tl" style="width:{{85-$i*5}}%;margin-bottom:2px;"></div>@endfor
            </div>
          </div>
          <div class="tmpl-name">Laporan</div>
          <div class="tmpl-sub">Luks</div>
        </button>
      </form>

    </div>
  </div>
</div>

<style>
/* ── RECENT SECTION ── */
.recent-section{max-width:1100px;margin:0 auto;padding:20px 24px 48px;}
.recent-header{display:flex;align-items:center;gap:8px;margin-bottom:14px;}
.recent-title{font-size:15px;font-weight:500;color:var(--dark);flex:1;}
.filter-btn{
  background:none;border:none;cursor:pointer;font-size:13px;
  color:var(--grey);display:flex;align-items:center;gap:4px;
  padding:5px 8px;border-radius:4px;transition:background .15s;
}
.filter-btn:hover{background:var(--hover);}
.view-ctrl{display:flex;align-items:center;gap:2px;}
.vc-btn{
  background:none;border:none;cursor:pointer;padding:6px;
  border-radius:4px;color:var(--grey);display:flex;
  align-items:center;justify-content:center;
  transition:background .15s;
}
.vc-btn:hover,.vc-btn.active{background:var(--hover);color:var(--dark);}

/* ── DOC CARDS ── */
.docs-grid{
  display:grid;
  grid-template-columns:repeat(auto-fill,minmax(190px,1fr));
  gap:12px;
}
.doc-card{
  background:#fff;border:1px solid var(--border);
  border-radius:4px;overflow:hidden;cursor:pointer;
  text-decoration:none;color:var(--dark);
  transition:box-shadow .15s,border-color .15s;
  display:block;position:relative;
}
.doc-card:hover{box-shadow:0 2px 8px rgba(0,0,0,.15);border-color:#c7c7c7;}
.doc-thumb-area{
  height:152px;background:#fff;
  border-bottom:1px solid var(--border);
  overflow:hidden;padding:12px 10px 8px;
  display:flex;flex-direction:column;gap:3px;
}
.dtl{height:3px;border-radius:1px;background:#e8eaed;}
.dtl.dk{background:#c0c0c0;}
.dtl.bk{background:#000;height:4px;}
.doc-footer{
  padding:8px 10px 8px;
  display:flex;align-items:center;gap:8px;
}
.doc-icon{width:16px;height:16px;flex-shrink:0;}
.doc-footer-info{flex:1;min-width:0;}
.doc-footer-title{
  font-size:12px;font-weight:500;
  white-space:nowrap;overflow:hidden;text-overflow:ellipsis;
  color:var(--dark);
}
.doc-footer-meta{
  font-size:11px;color:var(--grey);
  display:flex;align-items:center;gap:4px;margin-top:1px;
}
.doc-more-btn{
  background:none;border:none;cursor:pointer;
  padding:4px;border-radius:50%;color:var(--grey);
  opacity:0;transition:background .15s,opacity .15s;
  display:flex;align-items:center;justify-content:center;
  width:28px;height:28px;flex-shrink:0;
}
.doc-card:hover .doc-more-btn{opacity:1;}
.doc-more-btn:hover{background:var(--hover);}

/* dropdown context menu */
.ctx-menu{
  position:absolute;right:8px;bottom:36px;
  background:#fff;border:1px solid var(--border);
  border-radius:4px;box-shadow:0 4px 16px rgba(0,0,0,.15);
  min-width:160px;z-index:50;display:none;padding:4px 0;
}
.ctx-menu.show{display:block;}
.ctx-item{
  padding:8px 16px;font-size:13px;cursor:pointer;
  color:var(--dark);display:flex;align-items:center;gap:10px;
  transition:background .1s;
}
.ctx-item:hover{background:var(--hover);}
.ctx-item.danger{color:#c62828;}

/* empty */
.empty-state{
  text-align:center;padding:60px 0;color:var(--grey);
  grid-column:1/-1;
}
.empty-state .ei{font-size:56px;margin-bottom:12px;}
.empty-state p{font-size:14px;}
</style>

{{-- ── RECENT SECTION ── --}}
<div class="recent-section">
  <div class="recent-header">
    <span class="recent-title">Dokumen terbaru</span>
    <button class="filter-btn">
      Milik siapa saja
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>
    </button>
    <div class="view-ctrl">
      <button class="vc-btn active" id="btnGrid" title="Tampilan grid" onclick="setView('grid')">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
      </button>
      <button class="vc-btn" id="btnAZ" title="Urutkan A-Z" onclick="sortDocs()">
        <svg width="20" height="18" viewBox="0 0 24 20"><text x="0" y="14" font-size="11" font-weight="700" fill="#5f6368" font-family="Arial">AZ</text></svg>
      </button>
      <button class="vc-btn" id="btnList" title="Tampilan daftar" onclick="setView('list')">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="3.5"/><rect x="3" y="10" width="18" height="3.5"/><rect x="3" y="16" width="18" height="3.5"/></svg>
      </button>
    </div>
  </div>

  <div class="docs-grid" id="docsGrid">
    @forelse($documents as $doc)
    <div class="doc-card" data-title="{{ strtolower($doc->title) }}" data-date="{{ $doc->updated_at->timestamp }}" onclick="window.location='{{ route('documents.edit', $doc->id) }}'">
      <div class="doc-thumb-area">
        @php
          $lines = [92,78,88,65,80,70,90,60,75,55,82,68,58,85,72,62];
        @endphp
        @foreach($lines as $idx => $w)
          <div class="dtl {{ $idx < 2 ? 'bk' : ($idx % 5 === 0 ? 'dk' : '') }}" style="width:{{ $w }}%;{{ $idx < 2 ? 'margin-bottom:5px;' : '' }}"></div>
        @endforeach
      </div>
      <div class="doc-footer">
        <svg class="doc-icon" viewBox="0 0 24 24">
          <path d="M14 2H6C4.9 2 4 2.9 4 4v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6z" fill="#4285f4"/>
          <path d="M14 2v6h6" fill="#a8c7fa" opacity=".8"/>
        </svg>
        <div class="doc-footer-info">
          <div class="doc-footer-title">{{ $doc->title ?: 'Dokumen tanpa judul' }}</div>
          <div class="doc-footer-meta">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="#5f6368"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
            Dibuka {{ $doc->updated_at->locale('id')->diffForHumans() }}
          </div>
        </div>
        <button class="doc-more-btn" title="Opsi lainnya"
          onclick="event.stopPropagation();toggleCtx(this)">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="#5f6368"><circle cx="12" cy="5" r="1.8"/><circle cx="12" cy="12" r="1.8"/><circle cx="12" cy="19" r="1.8"/></svg>
        </button>
        {{-- Context menu --}}
        <div class="ctx-menu">
          <div class="ctx-item" onclick="event.stopPropagation();window.location='{{ route('documents.edit', $doc->id) }}'">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
            Buka
          </div>
          <div class="ctx-item" onclick="event.stopPropagation();renameDoc({{ $doc->id }}, '{{ addslashes($doc->title) }}')">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.83 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/></svg>
            Ganti nama
          </div>
          <form method="POST" action="{{ route('documents.destroy', $doc->id) }}" onsubmit="return confirm('Hapus \'{{ addslashes($doc->title) }}\'?')" onclick="event.stopPropagation()">
            @csrf @method('DELETE')
            <button type="submit" class="ctx-item danger" style="width:100%;text-align:left;background:none;border:none;cursor:pointer;">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>
              Hapus
            </button>
          </form>
        </div>
      </div>
    </div>
    @empty
    <div class="empty-state">
      <div class="ei">📂</div>
      <p>Belum ada dokumen. Buat yang pertama di atas!</p>
    </div>
    @endforelse
  </div>
</div>

<script>
// Search
document.getElementById('searchInput').addEventListener('input', function() {
  const q = this.value.toLowerCase().trim();
  document.querySelectorAll('#docsGrid > .doc-card').forEach(c => {
    c.style.display = (!q || c.dataset.title.includes(q)) ? '' : 'none';
  });
});

// Context menu toggle
function toggleCtx(btn) {
  const menu = btn.nextElementSibling;
  const isOpen = menu.classList.contains('show');
  document.querySelectorAll('.ctx-menu.show').forEach(m => m.classList.remove('show'));
  if (!isOpen) menu.classList.add('show');
}
document.addEventListener('click', () => {
  document.querySelectorAll('.ctx-menu.show').forEach(m => m.classList.remove('show'));
});

// Rename
function renameDoc(id, current) {
  const name = prompt('Ganti nama dokumen:', current);
  if (name && name.trim() && name !== current) {
    fetch('/documents/' + id, {
      method: 'PATCH',
      headers: {'Content-Type':'application/json','X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || ''},
      body: JSON.stringify({title: name.trim(), content: '', editor_id:'sys', editor_name:'User'})
    }).then(() => location.reload());
  }
}

// View toggle
function setView(mode) {
  const grid = document.getElementById('docsGrid');
  document.getElementById('btnGrid').classList.toggle('active', mode==='grid');
  document.getElementById('btnList').classList.toggle('active', mode==='list');
  if (mode === 'list') {
    grid.style.gridTemplateColumns = '1fr';
    grid.querySelectorAll('.doc-card').forEach(c => {
      c.style.display = 'flex';
      c.style.flexDirection = 'row';
      c.style.height = '56px';
      c.style.alignItems = 'center';
      const thumb = c.querySelector('.doc-thumb-area');
      if (thumb) thumb.style.display = 'none';
    });
  } else {
    grid.style.gridTemplateColumns = '';
    grid.querySelectorAll('.doc-card').forEach(c => {
      c.style.display = '';c.style.flexDirection = '';c.style.height = '';
      const thumb = c.querySelector('.doc-thumb-area');
      if (thumb) thumb.style.display = '';
    });
  }
}

// Sort A-Z
let sortAZ = false;
function sortDocs() {
  sortAZ = !sortAZ;
  document.getElementById('btnAZ').classList.toggle('active', sortAZ);
  const grid = document.getElementById('docsGrid');
  const cards = Array.from(grid.querySelectorAll('.doc-card'));
  cards.sort((a,b) => sortAZ
    ? (a.dataset.title||'').localeCompare(b.dataset.title||'')
    : parseInt(b.dataset.date||0) - parseInt(a.dataset.date||0)
  );
  cards.forEach(c => grid.appendChild(c));
}
</script>
<meta name="csrf-token" content="{{ csrf_token() }}">
</body>
</html>
