<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Docs</title>
<style>
*{margin:0;padding:0;box-sizing:border-box;}
:root{
  --blue:#1a73e8;--dark:#202124;--grey:#5f6368;
  --border:#e0e0e0;--hover:#f1f3f4;--bg:#f8f9fa;
}
body{font-family:'Google Sans',Arial,sans-serif;background:var(--bg);color:var(--dark);min-height:100vh;}

/* ── TOP NAV ── */
nav{
  background:#fff;height:64px;
  display:flex;align-items:center;
  padding:0 16px;gap:8px;
  border-bottom:1px solid var(--border);
  position:sticky;top:0;z-index:100;
}
.nav-menu-btn{
  background:none;border:none;cursor:pointer;
  padding:8px;border-radius:50%;
  color:var(--grey);font-size:20px;
  transition:background .15s;
}
.nav-menu-btn:hover{background:var(--hover);}
.nav-logo{display:flex;align-items:center;gap:6px;text-decoration:none;margin-right:4px;}
.nav-logo-icon{
  width:40px;height:40px;flex-shrink:0;
}
.nav-logo-text{font-size:22px;color:#5f6368;font-weight:400;letter-spacing:-.5px;}
.nav-search{
  flex:1;max-width:720px;margin:0 16px;
  background:#f1f3f4;border:1px solid transparent;
  border-radius:24px;display:flex;align-items:center;
  padding:0 16px;gap:12px;height:44px;
  transition:background .2s,box-shadow .2s,border-color .2s;
}
.nav-search:focus-within{
  background:#fff;border-color:var(--border);
  box-shadow:0 1px 6px rgba(32,33,36,.28);
}
.nav-search svg{color:var(--grey);flex-shrink:0;}
.nav-search input{
  flex:1;border:none;outline:none;background:transparent;
  font-size:16px;color:var(--dark);
}
.nav-search input::placeholder{color:var(--grey);}
.nav-right{margin-left:auto;display:flex;align-items:center;gap:8px;}
.nav-grid-btn{
  background:none;border:none;cursor:pointer;
  padding:8px;border-radius:50%;color:var(--grey);
  transition:background .15s;
}
.nav-grid-btn:hover{background:var(--hover);}
.nav-avatar{
  width:36px;height:36px;border-radius:50%;
  background:linear-gradient(135deg,#4285f4,#0d47a1);
  display:flex;align-items:center;justify-content:center;
  font-size:14px;font-weight:700;color:#fff;cursor:pointer;
}
</style>
</head>
<body>
<style>
/* ── TEMPLATE SECTION ── */
.template-section{
  background:#fff;padding:24px 0 16px;
  border-bottom:1px solid var(--border);
}
.template-inner{max-width:1200px;margin:0 auto;padding:0 40px;}
.section-header{
  display:flex;align-items:center;justify-content:space-between;
  margin-bottom:16px;
}
.section-title{font-size:16px;font-weight:500;color:var(--dark);}
.template-gallery-btn{
  display:flex;align-items:center;gap:6px;
  background:none;border:none;cursor:pointer;
  font-size:14px;color:var(--grey);padding:6px 10px;
  border-radius:4px;transition:background .15s;
}
.template-gallery-btn:hover{background:var(--hover);}
.tg-actions{display:flex;align-items:center;gap:4px;}
.tg-more{background:none;border:none;cursor:pointer;padding:8px;border-radius:50%;color:var(--grey);transition:background .15s;}
.tg-more:hover{background:var(--hover);}

.templates-row{display:flex;gap:16px;flex-wrap:nowrap;overflow-x:auto;padding-bottom:8px;}
.templates-row::-webkit-scrollbar{height:0;}

.tmpl-card{
  display:flex;flex-direction:column;align-items:center;
  gap:8px;cursor:pointer;flex-shrink:0;width:148px;
}
.tmpl-thumb{
  width:148px;height:192px;
  background:#fff;border:1px solid var(--border);
  border-radius:2px;overflow:hidden;
  transition:border-color .15s,box-shadow .15s;
  position:relative;
}
.tmpl-card:hover .tmpl-thumb{
  border-color:#1a73e8;
  box-shadow:0 1px 4px rgba(0,0,0,.2);
}
.tmpl-name{font-size:13px;font-weight:500;color:var(--dark);text-align:center;width:100%;}
.tmpl-sub{font-size:12px;color:var(--grey);text-align:center;width:100%;margin-top:-4px;}

/* Blank card */
.tmpl-blank{
  display:flex;align-items:center;justify-content:center;
  font-size:52px;
}
.plus-icon{
  width:56px;height:56px;position:relative;display:flex;align-items:center;justify-content:center;
}
.plus-icon::before,.plus-icon::after{content:'';position:absolute;border-radius:2px;}
.plus-icon::before{width:4px;height:48px;background:linear-gradient(#ea4335 25%,#4285f4 25%,#4285f4 50%,#34a853 50%,#34a853 75%,#fbbc04 75%);}
.plus-icon::after{width:48px;height:4px;background:linear-gradient(to right,#ea4335 25%,#4285f4 25%,#4285f4 50%,#34a853 50%,#34a853 75%,#fbbc04 75%);}

/* Template preview SVGs */
.tmpl-preview{width:100%;height:100%;padding:12px 10px;display:flex;flex-direction:column;gap:4px;}
.tp-line{height:4px;background:#e0e0e0;border-radius:2px;}
.tp-line.dark{background:#bdc1c6;}
.tp-line.blue{background:#4285f4;}
.tp-line.red{background:#ea4335;}
.tp-line.teal{background:#009688;}
.tp-name-block{
  font-size:10px;font-weight:700;color:#202124;
  margin-bottom:4px;letter-spacing:.5px;
}
.tp-header{height:6px;margin-bottom:6px;}
.tp-hero{height:48px;margin-bottom:6px;border-radius:2px;}
</style>

{{-- ── NAV ── --}}
<nav>
  <button class="nav-menu-btn">☰</button>
  <a href="/" class="nav-logo">
    <svg class="nav-logo-icon" viewBox="0 0 48 48" fill="none">
      <path d="M30 2H10C7.8 2 6 3.8 6 6v36c0 2.2 1.8 4 4 4h28c2.2 0 4-1.8 4-4V14L30 2z" fill="#4285f4"/>
      <path d="M30 14h12L30 2z" fill="#fff" opacity=".3"/>
      <path d="M30 14h12L30 2z" fill="#1967d2"/>
      <rect x="13" y="22" width="22" height="2" rx="1" fill="#fff"/>
      <rect x="13" y="27" width="22" height="2" rx="1" fill="#fff"/>
      <rect x="13" y="32" width="16" height="2" rx="1" fill="#fff"/>
    </svg>
    <span class="nav-logo-text">Docs</span>
  </a>
  <div class="nav-search">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z" stroke="currentColor" stroke-width="2" fill="none"/></svg>
    <input type="text" placeholder="Search" id="searchInput">
  </div>
  <div class="nav-right">
    <button class="nav-grid-btn" title="Google apps">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><circle cx="5" cy="5" r="1.5"/><circle cx="12" cy="5" r="1.5"/><circle cx="19" cy="5" r="1.5"/><circle cx="5" cy="12" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="19" cy="12" r="1.5"/><circle cx="5" cy="19" r="1.5"/><circle cx="12" cy="19" r="1.5"/><circle cx="19" cy="19" r="1.5"/></svg>
    </button>
    <div class="nav-avatar">A</div>
  </div>
</nav>

{{-- ── TEMPLATE SECTION ── --}}
<div class="template-section">
  <div class="template-inner">
    <div class="section-header">
      <span class="section-title">Start a new document</span>
      <div class="tg-actions">
        <button class="template-gallery-btn">
          Template gallery
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
        </button>
        <button class="tg-more" title="Opsi lain">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="5" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="12" cy="19" r="1.5"/></svg>
        </button>
      </div>
    </div>

    <div class="templates-row">

      {{-- Blank --}}
      <form method="POST" action="{{ route('documents.store') }}" style="display:contents">
        @csrf
        <input type="hidden" name="title" value="Dokumen tanpa judul">
        <button type="submit" style="background:none;border:none;padding:0;cursor:pointer;display:contents;">
          <div class="tmpl-card">
            <div class="tmpl-thumb tmpl-blank"><div class="plus-icon"></div></div>
            <div class="tmpl-name">Blank</div>
          </div>
        </button>
      </form>

      {{-- Resume Serif --}}
      <form method="POST" action="{{ route('documents.store') }}" style="display:contents">
        @csrf
        <input type="hidden" name="title" value="Resume - Serif">
        <button type="submit" style="background:none;border:none;padding:0;cursor:pointer;display:contents;">
          <div class="tmpl-card">
            <div class="tmpl-thumb">
              <div class="tmpl-preview">
                <div class="tp-name-block">Your Name</div>
                <div class="tp-line dark" style="width:70%;height:2px;margin-bottom:6px;"></div>
                @for($i=0;$i<3;$i++)
                  <div class="tp-line" style="width:90%;margin-bottom:2px;"></div>
                  <div class="tp-line" style="width:75%;margin-bottom:6px;"></div>
                @endfor
                <div class="tp-line blue" style="width:50%;height:2px;margin-bottom:4px;"></div>
                @for($i=0;$i<4;$i++)
                  <div class="tp-line" style="width:{{ 60+$i*5 }}%;margin-bottom:2px;"></div>
                @endfor
              </div>
            </div>
            <div class="tmpl-name">Resume</div>
            <div class="tmpl-sub">Serif</div>
          </div>
        </button>
      </form>

      {{-- Resume Coral --}}
      <form method="POST" action="{{ route('documents.store') }}" style="display:contents">
        @csrf
        <input type="hidden" name="title" value="Resume - Coral">
        <button type="submit" style="background:none;border:none;padding:0;cursor:pointer;display:contents;">
          <div class="tmpl-card">
            <div class="tmpl-thumb">
              <div style="background:#ea4335;height:8px;width:100%;"></div>
              <div class="tmpl-preview" style="padding-top:8px;">
                <div class="tp-name-block" style="color:#ea4335;">NAMA KAMU</div>
                @for($i=0;$i<2;$i++)
                  <div class="tp-line" style="width:80%;margin-bottom:2px;"></div>
                @endfor
                <div style="height:8px;"></div>
                <div class="tp-line red" style="width:40%;height:2px;margin-bottom:4px;"></div>
                @for($i=0;$i<5;$i++)
                  <div class="tp-line" style="width:{{ 55+$i*6 }}%;margin-bottom:2px;"></div>
                @endfor
              </div>
            </div>
            <div class="tmpl-name">Resume</div>
            <div class="tmpl-sub">Coral</div>
          </div>
        </button>
      </form>

      {{-- Letter Spearmint --}}
      <form method="POST" action="{{ route('documents.store') }}" style="display:contents">
        @csrf
        <input type="hidden" name="title" value="Surat - Spearmint">
        <button type="submit" style="background:none;border:none;padding:0;cursor:pointer;display:contents;">
          <div class="tmpl-card">
            <div class="tmpl-thumb">
              <div style="background:#009688;height:6px;width:100%;"></div>
              <div class="tmpl-preview">
                <div class="tp-line teal" style="width:50%;height:2px;margin-bottom:8px;"></div>
                @for($i=0;$i<3;$i++)
                  <div class="tp-line" style="width:{{ 70+$i*5 }}%;margin-bottom:2px;"></div>
                @endfor
                <div style="height:10px;"></div>
                @for($i=0;$i<6;$i++)
                  <div class="tp-line" style="width:90%;margin-bottom:2px;"></div>
                @endfor
              </div>
            </div>
            <div class="tmpl-name">Letter</div>
            <div class="tmpl-sub">Spearmint</div>
          </div>
        </button>
      </form>

      {{-- Project Proposal Tropic --}}
      <form method="POST" action="{{ route('documents.store') }}" style="display:contents">
        @csrf
        <input type="hidden" name="title" value="Project Proposal - Tropic">
        <button type="submit" style="background:none;border:none;padding:0;cursor:pointer;display:contents;">
          <div class="tmpl-card">
            <div class="tmpl-thumb">
              <div style="height:96px;background:linear-gradient(135deg,#4db6ac 0%,#009688 40%,#795548 40%,#5d4037 70%,#ff8a65 70%);position:relative;overflow:hidden;">
                <div style="position:absolute;bottom:8px;left:10px;font-size:9px;font-weight:700;color:#fff;">Project Name</div>
              </div>
              <div class="tmpl-preview" style="padding-top:8px;">
                @for($i=0;$i<5;$i++)
                  <div class="tp-line" style="width:{{ 60+$i*6 }}%;margin-bottom:3px;"></div>
                @endfor
              </div>
            </div>
            <div class="tmpl-name">Project proposal</div>
            <div class="tmpl-sub">Tropic</div>
          </div>
        </button>
      </form>

    </div>
  </div>
</div>

<style>
/* ── RECENT DOCUMENTS ── */
.recent-section{max-width:1200px;margin:0 auto;padding:24px 40px 40px;}
.recent-header{
  display:flex;align-items:center;justify-content:space-between;
  margin-bottom:16px;
}
.recent-title{font-size:16px;font-weight:500;color:var(--dark);}
.recent-controls{display:flex;align-items:center;gap:8px;}
.owned-select{
  border:1px solid var(--border);background:#fff;
  border-radius:4px;padding:6px 10px;font-size:13px;
  color:var(--dark);cursor:pointer;outline:none;
  display:flex;align-items:center;gap:4px;
}
.view-btn{
  background:none;border:none;cursor:pointer;
  padding:6px;border-radius:4px;color:var(--grey);
  transition:background .15s;
}
.view-btn:hover,.view-btn.active{background:var(--hover);color:var(--dark);}

/* Doc grid */
.docs-grid{
  display:grid;
  grid-template-columns:repeat(auto-fill,minmax(200px,1fr));
  gap:16px;
}
/* Doc list */
.docs-list{display:flex;flex-direction:column;gap:0;}
.docs-list .doc-item{
  display:flex;align-items:center;gap:16px;
  padding:10px 12px;border-radius:4px;
  text-decoration:none;color:var(--dark);
  transition:background .1s;
}
.docs-list .doc-item:hover{background:var(--hover);}
.docs-list .doc-thumb{width:32px;height:40px;flex-shrink:0;}
.docs-list .doc-info{flex:1;min-width:0;}
.docs-list .doc-title{font-size:13px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.docs-list .doc-date{font-size:12px;color:var(--grey);}

/* Doc card */
.doc-card{
  display:flex;flex-direction:column;
  text-decoration:none;color:var(--dark);
  border-radius:4px;overflow:hidden;
  border:1px solid var(--border);
  transition:box-shadow .15s,border-color .15s;
  background:#fff;cursor:pointer;
}
.doc-card:hover{box-shadow:0 4px 12px rgba(0,0,0,.12);border-color:#c7c7c7;}
.doc-thumb-area{
  height:160px;background:#fff;
  border-bottom:1px solid var(--border);
  overflow:hidden;padding:12px 10px;
  display:flex;flex-direction:column;gap:3px;
  position:relative;
}
.doc-thumb-line{height:4px;background:#e0e0e0;border-radius:2px;}
.doc-thumb-line.dark{background:#c0c0c0;}
.doc-card-footer{
  padding:10px 12px 10px;
  display:flex;align-items:center;gap:10px;
}
.doc-file-icon{width:18px;height:18px;flex-shrink:0;}
.doc-card-info{flex:1;min-width:0;}
.doc-card-title{
  font-size:13px;font-weight:500;
  white-space:nowrap;overflow:hidden;text-overflow:ellipsis;
}
.doc-card-date{font-size:11px;color:var(--grey);margin-top:1px;}
.doc-card-menu{
  background:none;border:none;cursor:pointer;
  padding:4px;border-radius:50%;color:var(--grey);
  transition:background .15s;opacity:0;
}
.doc-card:hover .doc-card-menu{opacity:1;}
.doc-card-menu:hover{background:var(--hover);}

/* empty state */
.empty-state{
  text-align:center;padding:60px 0;color:var(--grey);
  grid-column:1/-1;
}
.empty-state .ei{font-size:64px;margin-bottom:12px;}
.empty-state p{font-size:14px;}

/* search highlight */
.doc-card.hidden,.docs-list .doc-item.hidden{display:none;}
</style>

{{-- ── RECENT DOCUMENTS ── --}}
<div class="recent-section">
  <div class="recent-header">
    <span class="recent-title">Recent documents</span>
    <div class="recent-controls">
      <div class="owned-select">
        Owned by anyone
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
      </div>
      <button class="view-btn active" id="btnGrid" title="Grid view" onclick="setView('grid')">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
      </button>
      <button class="view-btn" id="btnAZ" title="Sort A-Z" onclick="sortDocs()">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><text x="2" y="16" font-size="11" font-weight="700" fill="#5f6368">AZ</text></svg>
      </button>
      <button class="view-btn" id="btnList" title="List view" onclick="setView('list')">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="4"/><rect x="3" y="10" width="18" height="4"/><rect x="3" y="16" width="18" height="4"/></svg>
      </button>
    </div>
  </div>

  {{-- Grid view --}}
  <div class="docs-grid" id="docsGrid">
    @forelse($documents as $doc)
    <a href="{{ route('documents.edit', $doc->id) }}" class="doc-card" data-title="{{ strtolower($doc->title) }}" data-date="{{ $doc->updated_at->timestamp }}">
      <div class="doc-thumb-area">
        @php $lines = [90,75,85,60,80,70,90,65,75,55,80,70,60,85,75,65]; @endphp
        @foreach($lines as $w)
          <div class="doc-thumb-line {{ $loop->index < 2 ? 'dark' : '' }}" style="width:{{ $w }}%;"></div>
        @endforeach
      </div>
      <div class="doc-card-footer">
        <svg class="doc-file-icon" viewBox="0 0 24 24"><path d="M14 2H6C4.9 2 4 2.9 4 4v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6z" fill="#4285f4"/><path d="M14 2v6h6" fill="#aecbfa"/></svg>
        <div class="doc-card-info">
          <div class="doc-card-title">{{ $doc->title ?: 'Dokumen tanpa judul' }}</div>
          <div class="doc-card-date">{{ $doc->updated_at->diffForHumans() }}</div>
        </div>
        <form method="POST" action="{{ route('documents.destroy', $doc->id) }}" onsubmit="return confirm('Hapus dokumen ini?')">
          @csrf @method('DELETE')
          <button type="submit" class="doc-card-menu" title="Hapus" onclick="event.preventDefault();event.stopPropagation();this.closest('form').submit()">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="5" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="12" cy="19" r="1"/></svg>
          </button>
        </form>
      </div>
    </a>
    @empty
    <div class="empty-state">
      <div class="ei">📂</div>
      <p>Belum ada dokumen. Buat yang pertama di atas!</p>
    </div>
    @endforelse
  </div>
</div>

<script>
// ── SEARCH ────────────────────────────────────────────────────────
document.getElementById('searchInput').addEventListener('input', function() {
  const q = this.value.toLowerCase().trim();
  document.querySelectorAll('.doc-card').forEach(card => {
    card.classList.toggle('hidden', q && !card.dataset.title.includes(q));
  });
});

// ── VIEW TOGGLE ───────────────────────────────────────────────────
function setView(mode) {
  const grid = document.getElementById('docsGrid');
  document.getElementById('btnGrid').classList.toggle('active', mode === 'grid');
  document.getElementById('btnList').classList.toggle('active', mode === 'list');
  if (mode === 'list') {
    grid.className = 'docs-list';
    grid.querySelectorAll('.doc-card').forEach(card => {
      card.classList.remove('doc-card');
      card.classList.add('doc-item');
    });
  } else {
    grid.className = 'docs-grid';
    grid.querySelectorAll('.doc-item').forEach(card => {
      card.classList.remove('doc-item');
      card.classList.add('doc-card');
    });
  }
}

// ── SORT ──────────────────────────────────────────────────────────
let sortAZ = false;
function sortDocs() {
  sortAZ = !sortAZ;
  document.getElementById('btnAZ').classList.toggle('active', sortAZ);
  const grid = document.getElementById('docsGrid');
  const cards = Array.from(grid.querySelectorAll('.doc-card,.doc-item'));
  cards.sort((a, b) => {
    const ta = a.dataset.title || '', tb = b.dataset.title || '';
    return sortAZ ? ta.localeCompare(tb) : (parseInt(b.dataset.date)||0) - (parseInt(a.dataset.date)||0);
  });
  cards.forEach(c => grid.appendChild(c));
}
</script>
</body>
</html>
