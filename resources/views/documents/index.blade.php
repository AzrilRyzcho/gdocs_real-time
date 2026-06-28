<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Writly</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Google+Sans:wght@400;500&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box;}
:root{
  --blue:#1a73e8;--blue-h:#1557b0;--blue-s:#e8f0fe;
  --gray1:#f8f9fa;--gray2:#f1f3f4;--gray3:#e8eaed;--gray4:#dadce0;
  --gray5:#bdc1c6;--gray6:#80868b;--gray7:#5f6368;--gray8:#3c4043;--gray9:#202124;
  --red:#d93025;--green:#1e8e3e;--white:#fff;
  --font:'Google Sans','Inter',sans-serif;
}
body{font-family:var(--font);background:var(--white);color:var(--gray9);min-height:100vh;}

/* ── TOPBAR ── */
.topbar{height:64px;display:flex;align-items:center;padding:0 16px;gap:8px;position:sticky;top:0;background:var(--white);z-index:100;border-bottom:1px solid var(--gray3);}
.top-ham{width:48px;height:48px;border:none;background:none;cursor:pointer;border-radius:50%;display:flex;align-items:center;justify-content:center;color:var(--gray7);}
.top-ham:hover{background:var(--gray2);}
.top-logo{display:flex;align-items:center;gap:8px;text-decoration:none;}
.top-logo-icon{width:40px;height:40px;}
.top-logo-text{font-size:22px;color:var(--gray7);font-weight:400;letter-spacing:-.2px;}
.top-search{flex:1;max-width:720px;margin:0 auto;position:relative;}
.top-search input{width:100%;height:46px;background:var(--gray2);border:1px solid transparent;border-radius:24px;padding:0 48px 0 52px;font-size:16px;color:var(--gray9);outline:none;font-family:var(--font);transition:all .2s;}
.top-search input:focus{background:var(--white);border-color:var(--gray4);box-shadow:0 1px 3px rgba(60,64,67,.3);}
.top-search input::placeholder{color:var(--gray6);}
.search-ico{position:absolute;left:16px;top:50%;transform:translateY(-50%);color:var(--gray6);}
.top-right{display:flex;align-items:center;gap:4px;margin-left:auto;}
.top-icon-btn{width:40px;height:40px;border:none;background:none;cursor:pointer;border-radius:50%;display:flex;align-items:center;justify-content:center;color:var(--gray7);transition:background .15s;}
.top-icon-btn:hover{background:var(--gray2);}
.user-wrap{position:relative;}
.user-av{width:32px;height:32px;border-radius:50%;background:#1a73e8;color:#fff;font-size:14px;font-weight:500;display:flex;align-items:center;justify-content:center;cursor:pointer;border:none;}
.user-dropdown{position:absolute;top:calc(100%+8px);right:0;background:var(--white);border-radius:8px;box-shadow:0 4px 20px rgba(0,0,0,.2);width:280px;z-index:300;display:none;overflow:hidden;}
.user-dropdown.show{display:block;}
.ud-top{display:flex;align-items:center;gap:14px;padding:18px 18px 14px;}
.ud-av{width:48px;height:48px;border-radius:50%;background:#1a73e8;color:#fff;font-size:18px;font-weight:500;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.ud-name{font-size:15px;font-weight:500;color:var(--gray9);}
.ud-email{font-size:13px;color:var(--gray7);margin-top:2px;}
.ud-sep{height:1px;background:var(--gray3);}
.ud-item{display:flex;align-items:center;gap:12px;padding:12px 18px;font-size:14px;color:var(--gray8);cursor:pointer;width:100%;border:none;background:none;font-family:var(--font);text-align:left;}
.ud-item:hover{background:var(--gray1);}
.ud-item.logout{color:var(--red);}

/* ── MODAL ── */
.m-overlay{position:fixed;inset:0;background:rgba(0,0,0,.4);display:none;align-items:center;justify-content:center;z-index:900;}
.m-overlay.show{display:flex;}
.m-box{background:var(--white);border-radius:8px;padding:0;width:448px;box-shadow:0 11px 15px rgba(0,0,0,.2),0 9px 46px rgba(0,0,0,.12);overflow:hidden;}
.m-title{font-size:22px;font-weight:400;padding:24px 24px 20px;color:var(--gray9);}
.m-body{padding:0 24px 16px;}
.m-body p{font-size:14px;color:var(--gray8);margin-bottom:16px;line-height:1.5;}
.m-input{width:100%;height:40px;border:1px solid #1a73e8;border-radius:4px;padding:0 12px;font-size:14px;color:var(--gray9);outline:none;font-family:var(--font);}
.m-input:focus{border-width:2px;}
.m-actions{display:flex;justify-content:flex-end;gap:8px;padding:16px 24px 20px;}
.m-btn{height:36px;padding:0 24px;border:none;border-radius:4px;font-size:14px;font-weight:500;cursor:pointer;font-family:var(--font);}
.m-btn-cancel{background:none;color:var(--blue);}
.m-btn-cancel:hover{background:var(--blue-s);}
.m-btn-ok{background:var(--blue);color:#fff;}
.m-btn-ok:hover{background:var(--blue-h);}
.m-btn-ok:disabled{background:var(--gray5);cursor:default;}
.m-btn-danger{background:var(--red);color:#fff;}
.m-icon{width:48px;height:48px;border-radius:50%;background:#fce8e6;color:var(--red);display:flex;align-items:center;justify-content:center;margin:24px auto 16px;}

/* ── TEMPLATE SECTION ── */
.tpl-sec{background:var(--gray1);border-bottom:1px solid var(--gray3);padding:16px 0 20px;}
.inner{max-width:1200px;margin:0 auto;padding:0 24px;}
.tpl-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;}
.tpl-header h2{font-size:14px;font-weight:500;color:var(--gray8);}
.tpl-btn{font-size:13px;color:var(--blue);background:none;border:none;cursor:pointer;display:flex;align-items:center;gap:4px;font-family:var(--font);}
.tpl-row{display:flex;gap:16px;overflow-x:auto;padding-bottom:4px;}
.tpl-row::-webkit-scrollbar{height:6px;}
.tpl-row::-webkit-scrollbar-thumb{background:var(--gray4);border-radius:3px;}
.tpl-card{flex-shrink:0;cursor:pointer;background:none;border:none;padding:0;text-align:left;width:168px;}
.tpl-thumb{width:168px;height:218px;background:var(--white);border:1px solid var(--gray4);border-radius:4px;overflow:hidden;transition:border-color .15s,box-shadow .15s;position:relative;}
.tpl-card:hover .tpl-thumb{border-color:#1967d2;box-shadow:0 2px 8px rgba(60,64,67,.3);}
.tpl-label{font-size:13px;color:var(--gray8);margin-top:10px;font-weight:400;}
.tpl-sub{font-size:12px;color:var(--gray6);margin-top:3px;}
/* Plus icon inside blank */
.plus-wrap{display:flex;align-items:center;justify-content:center;height:100%;}
.plus-icon{width:52px;height:52px;position:relative;}
.plus-icon::before,.plus-icon::after{content:'';position:absolute;border-radius:3px;}
.plus-icon::before{width:6px;height:42px;left:23px;top:5px;background:linear-gradient(#ea4335 25%,#4285f4 25%,#4285f4 50%,#34a853 50%,#34a853 75%,#fbbc04 75%);}
.plus-icon::after{width:42px;height:6px;left:5px;top:23px;background:linear-gradient(to right,#ea4335 25%,#4285f4 25%,#4285f4 50%,#34a853 50%,#34a853 75%,#fbbc04 75%);}
/* Template preview lines */
.tp{padding:16px 12px;display:flex;flex-direction:column;gap:4px;}
.tl{height:4px;border-radius:2px;background:#e8eaed;margin-bottom:0;}
.tl.d{background:#bbb;height:5px;}.tl.b{background:#4285f4;height:3px;}.tl.g{background:#34a853;height:3px;}
.tpl-accent{height:72px;display:flex;align-items:flex-end;padding:10px;}
.tpl-accent-txt{font-size:10px;font-weight:700;color:#fff;letter-spacing:.8px;}

/* ── RECENT SECTION ── */
.recent-sec{padding:20px 0 80px;}
.recent-header{display:flex;align-items:center;gap:8px;margin-bottom:16px;}
.recent-header h2{font-size:14px;font-weight:500;color:var(--gray8);flex:1;}
.r-btn{border:none;background:none;cursor:pointer;color:var(--gray7);border-radius:50%;width:36px;height:36px;display:flex;align-items:center;justify-content:center;font-size:13px;transition:background .15s;}
.r-btn:hover,.r-btn.on{background:var(--blue-s);color:var(--blue);}
.sort-btn{display:flex;align-items:center;gap:4px;height:36px;padding:0 10px;border:none;background:none;cursor:pointer;border-radius:4px;font-size:13px;color:var(--gray7);font-family:var(--font);}
.sort-btn:hover{background:var(--gray2);}

/* ── DOC GRID ── */
.doc-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:16px;}
.doc-card{background:var(--white);border:1px solid var(--gray4);border-radius:8px;overflow:visible;position:relative;transition:border-color .15s,box-shadow .15s;}
.doc-card:hover{border-color:#bdc1c6;box-shadow:0 1px 3px rgba(60,64,67,.2),0 4px 8px rgba(60,64,67,.1);}
.doc-link{display:block;text-decoration:none;color:inherit;border-radius:8px;overflow:hidden;}
.doc-preview{height:160px;background:var(--white);border-bottom:1px solid var(--gray3);padding:12px 10px;display:flex;flex-direction:column;gap:3px;position:relative;overflow:hidden;}
.dl{height:3px;border-radius:1px;background:#e8eaed;}
.dl.d{background:#bdc1c6;height:5px;margin-bottom:2px;}
.doc-footer{padding:10px 44px 12px 12px;display:flex;align-items:center;gap:10px;}
.doc-file-icon{width:18px;height:22px;flex-shrink:0;}
.doc-meta{flex:1;min-width:0;}
.doc-title{font-size:13px;font-weight:400;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;color:var(--gray9);}
.doc-date{font-size:11px;color:var(--gray7);margin-top:2px;}
.doc-more-wrap{position:absolute;bottom:8px;right:6px;z-index:10;}
.doc-more-btn{width:32px;height:32px;border:none;background:none;cursor:pointer;border-radius:50%;color:var(--gray7);display:flex;align-items:center;justify-content:center;opacity:0;transition:background .15s,opacity .15s;}
.doc-card:hover .doc-more-btn{opacity:1;}
.doc-more-btn:hover{background:rgba(60,64,67,.1);}
.ctx-menu{position:absolute;right:0;bottom:36px;background:var(--white);border-radius:4px;box-shadow:0 2px 10px rgba(60,64,67,.3);min-width:180px;z-index:200;display:none;padding:6px 0;}
.ctx-menu.open{display:block;}
.ctx-i{padding:10px 20px;font-size:14px;cursor:pointer;color:var(--gray9);display:flex;align-items:center;gap:14px;transition:background .1s;white-space:nowrap;}
.ctx-i:hover{background:var(--gray1);}
.ctx-i.del{color:var(--red);}
.editor-chip{position:absolute;bottom:8px;left:10px;display:flex;align-items:center;gap:5px;}
.editor-av{width:18px;height:18px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:8px;font-weight:700;color:#fff;}
.empty-state{grid-column:1/-1;text-align:center;padding:64px 0;color:var(--gray6);}
.empty-state p{font-size:14px;margin-top:12px;}
/* list view */
.doc-grid.lv{display:flex;flex-direction:column;gap:0;}
.doc-grid.lv .doc-card{border-radius:0;border-left:none;border-right:none;border-bottom:none;box-shadow:none;}
.doc-grid.lv .doc-card:first-child{border-radius:4px 4px 0 0;}
.doc-grid.lv .doc-card:last-child{border-radius:0 0 4px 4px;border-bottom:1px solid var(--gray4);}
.doc-grid.lv .doc-preview{display:none;}
.doc-grid.lv .doc-footer{padding:14px 44px 14px 16px;}
.doc-grid.lv .doc-more-btn{opacity:1;}
</style>
</head>
<body>

{{-- MODALS --}}
<div class="m-overlay" id="renameOverlay" onclick="closeRename()"></div>
<div class="m-overlay" id="renameModal" style="pointer-events:none">
  <div class="m-box" style="pointer-events:all" onclick="event.stopPropagation()">
    <div class="m-title">Ganti nama</div>
    <div class="m-body">
      <p>Harap masukkan nama baru untuk item ini:</p>
      <input class="m-input" type="text" id="renameInput" maxlength="200" autocomplete="off" spellcheck="false">
    </div>
    <div class="m-actions">
      <button class="m-btn m-btn-cancel" onclick="closeRename()">Batal</button>
      <button class="m-btn m-btn-ok" id="renameOk" onclick="submitRename()">Oke</button>
    </div>
  </div>
</div>

<div class="m-overlay" id="deleteOverlay" onclick="closeDelete()"></div>
<div class="m-overlay" id="deleteModal" style="pointer-events:none">
  <div class="m-box" style="pointer-events:all" onclick="event.stopPropagation()">
    <div class="m-icon">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/></svg>
    </div>
    <div class="m-title" style="text-align:center;padding-top:0">Hapus dokumen?</div>
    <div class="m-body"><p id="deleteMsg" style="text-align:center">Dokumen ini akan dihapus permanen.</p></div>
    <div class="m-actions">
      <button class="m-btn m-btn-cancel" onclick="closeDelete()">Batal</button>
      <button class="m-btn m-btn-danger" id="deleteOk" onclick="submitDelete()">Hapus</button>
    </div>
  </div>
</div>

{{-- TOPBAR --}}
<div class="topbar">
  <button class="top-ham">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/></svg>
  </button>
  <a href="{{ route('documents.index') }}" class="top-logo">
    <svg class="top-logo-icon" viewBox="0 0 40 40">
      <path d="M25 2H9C7.35 2 6 3.35 6 5v30c0 1.65 1.35 3 3 3h22c1.65 0 3-1.35 3-3V15L25 2z" fill="#4285f4"/>
      <path d="M25 2v13h13L25 2z" fill="#a8c7fa" opacity=".8"/>
      <path d="M11 21h18v2H11zm0 5h18v2H11zm0-10h10v2H11z" fill="#fff"/>
    </svg>
    <span class="top-logo-text">Writly</span>
  </a>
  <div class="top-search">
    <svg class="search-ico" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M15.5 14h-.79l-.28-.27A6.47 6.47 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
    <input type="text" id="searchInput" placeholder="Telusuri">
  </div>
  <div class="top-right">
    <button class="top-icon-btn">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M6 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm6-8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm6-14c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 4c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg>
    </button>
    <div class="user-wrap">
      <button class="user-av" onclick="toggleUserMenu()">{{ strtoupper(mb_substr(auth()->user()->name,0,1)) }}</button>
      <div class="user-dropdown" id="userDropdown">
        <div class="ud-top">
          <div class="ud-av">{{ strtoupper(mb_substr(auth()->user()->name,0,1)) }}</div>
          <div>
            <div class="ud-name">{{ auth()->user()->name }}</div>
            <div class="ud-email">{{ auth()->user()->email }}</div>
          </div>
        </div>
        <div class="ud-sep"></div>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="ud-item logout">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            Keluar
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

{{-- TEMPLATE SECTION --}}
<div class="tpl-sec">
  <div class="inner">
    <div class="tpl-header">
      <h2>Mulai dokumen baru</h2>
      <button class="tpl-btn">Galeri template <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M16.59 8.59L12 13.17 7.41 8.59 6 10l6 6 6-6z"/></svg></button>
    </div>
    <div class="tpl-row">
      {{-- Kosong --}}
      <form method="POST" action="{{ route('documents.store') }}" style="display:contents">
        @csrf<input type="hidden" name="title" value="Dokumen tanpa judul">
        <button type="submit" class="tpl-card">
          <div class="tpl-thumb"><div class="plus-wrap"><div class="plus-icon"></div></div></div>
          <div class="tpl-label">Dokumen kosong</div>
        </button>
      </form>
      {{-- Resume --}}
      <form method="POST" action="{{ route('documents.store') }}" style="display:contents">
        @csrf<input type="hidden" name="title" value="Resume">
        <button type="submit" class="tpl-card">
          <div class="tpl-thumb"><div class="tp">
            <div class="tl d" style="width:60%;margin-bottom:5px"></div>
            <div class="tl" style="width:42%;margin-bottom:8px"></div>
            <div class="tl b" style="width:35%"></div>
            @for($i=0;$i<7;$i++)<div class="tl" style="width:{{88-$i*6}}%"></div>@endfor
            <div class="tl b" style="width:35%;margin-top:4px"></div>
            @for($i=0;$i<6;$i++)<div class="tl" style="width:{{80+$i*2}}%"></div>@endfor
          </div></div>
          <div class="tpl-label">Resume</div><div class="tpl-sub">Serif</div>
        </button>
      </form>
      {{-- Proposal --}}
      <form method="POST" action="{{ route('documents.store') }}" style="display:contents">
        @csrf<input type="hidden" name="title" value="Proposal Proyek">
        <button type="submit" class="tpl-card">
          <div class="tpl-thumb">
            <div class="tpl-accent" style="background:linear-gradient(135deg,#1a73e8,#0d47a1)">
              <span class="tpl-accent-txt">PROPOSAL</span>
            </div>
            <div class="tp">@for($i=0;$i<9;$i++)<div class="tl" style="width:{{70+($i%3)*10}}%"></div>@endfor</div>
          </div>
          <div class="tpl-label">Proposal Proyek</div>
        </button>
      </form>
      {{-- Catatan Rapat --}}
      <form method="POST" action="{{ route('documents.store') }}" style="display:contents">
        @csrf<input type="hidden" name="title" value="Catatan Rapat">
        <button type="submit" class="tpl-card">
          <div class="tpl-thumb"><div class="tp">
            <div class="tl d" style="width:70%;margin-bottom:6px"></div>
            <div class="tl g" style="width:38%;margin-bottom:8px"></div>
            @for($i=0;$i<4;$i++)<div class="tl" style="width:{{84-$i*7}}%"></div>@endfor
            <div class="tl g" style="width:38%;margin:6px 0"></div>
            @for($i=0;$i<4;$i++)<div class="tl" style="width:{{76+$i*3}}%"></div>@endfor
          </div></div>
          <div class="tpl-label">Catatan Rapat</div>
        </button>
      </form>
      {{-- Laporan --}}
      <form method="POST" action="{{ route('documents.store') }}" style="display:contents">
        @csrf<input type="hidden" name="title" value="Laporan">
        <button type="submit" class="tpl-card">
          <div class="tpl-thumb">
            <div class="tpl-accent" style="background:#202124">
              <span class="tpl-accent-txt" style="letter-spacing:1.5px">LAPORAN</span>
            </div>
            <div class="tp">@for($i=0;$i<8;$i++)<div class="tl" style="width:{{78-$i*3}}%"></div>@endfor</div>
          </div>
          <div class="tpl-label">Laporan</div>
        </button>
      </form>
    </div>
  </div>
</div>

{{-- RECENT SECTION --}}
<div class="recent-sec">
  <div class="inner">
    <div class="recent-header">
      <h2>Dokumen terbaru</h2>
      <button class="sort-btn" id="sortBtn" onclick="sortDocs()">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M10 18h4v-2h-4v2zM3 6v2h18V6H3zm3 7h12v-2H6v2z"/></svg>
        <span id="sortLabel">Terakhir dibuka</span>
      </button>
      <button class="r-btn on" id="btnGrid" onclick="setView('grid')" title="Grid">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M3 3h8v8H3zm10 0h8v8h-8zM3 13h8v8H3zm10 0h8v8h-8z"/></svg>
      </button>
      <button class="r-btn" id="btnList" onclick="setView('list')" title="List">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M3 13h2v-2H3v2zm0 4h2v-2H3v2zm0-8h2V7H3v2zm4 4h14v-2H7v2zm0 4h14v-2H7v2zM7 7v2h14V7H7z"/></svg>
      </button>
    </div>

    <div class="doc-grid" id="docsGrid">
      @forelse($documents as $doc)
      <div class="doc-card" data-title="{{ strtolower($doc->title) }}" data-date="{{ $doc->updated_at->timestamp }}">
        <a class="doc-link" href="{{ route('documents.edit',$doc->id) }}">
          <div class="doc-preview">
            @php
              $raw = strip_tags($doc->content ?? '');
              $hasContent = !empty(trim($raw));
              if ($hasContent) {
                $lines = array_values(array_filter(preg_split('/\r\n|\r|\n/',$raw),fn($l)=>trim($l)!==''));
                $plines = array_slice($lines,0,14);
              }
            @endphp
            @if($hasContent && count($plines??[])>0)
              @foreach($plines as $idx=>$line)
                @php $w=min(92,max(25,25+mb_strlen(trim($line))*1.4)); @endphp
                <div class="dl {{ $idx<2?'d':'' }}" style="width:{{$w}}%"></div>
              @endforeach
              @for($f=count($plines);$f<14;$f++)
                <div class="dl" style="width:{{38+($f*4)%50}}%"></div>
              @endfor
            @else
              @foreach([85,58,78,50,70,90,62,74,46,82,68,54,78,62] as $i=>$w)
                <div class="dl {{ $i<2?'d':'' }}" style="width:{{$w}}%"></div>
              @endforeach
            @endif
            @if($doc->last_editor_name)
            <div class="editor-chip">
              <div class="editor-av" style="background:{{ $doc->last_editor_color??'#4285f4' }}">{{ strtoupper(mb_substr($doc->last_editor_name,0,1)) }}</div>
              <span style="font-size:10px;color:#5f6368">{{ $doc->last_editor_name }}</span>
            </div>
            @endif
          </div>
          <div class="doc-footer">
            <svg class="doc-file-icon" viewBox="0 0 18 22">
              <path d="M11 0H2C.9 0 0 .9 0 2v18c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V6l-7-6z" fill="#4285f4"/>
              <path d="M11 0v6h6" fill="#a8c7fa" opacity=".8"/>
              <path d="M3 11h12v1.5H3zm0 3.5h12V16H3zm0-7h7v1.5H3z" fill="#fff"/>
            </svg>
            <div class="doc-meta">
              <div class="doc-title">{{ $doc->title ?: 'Dokumen tanpa judul' }}</div>
              <div class="doc-date">
                @if($doc->last_editor_name)
                  Dibuka oleh {{ $doc->last_editor_name }} • {{ ($doc->last_edited_at??$doc->updated_at)->locale('id')->diffForHumans() }}
                @else
                  {{ $doc->updated_at->locale('id')->diffForHumans() }}
                @endif
              </div>
            </div>
          </div>
        </a>
        <div class="doc-more-wrap">
          <button class="doc-more-btn" onclick="toggleCtx(this)">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg>
          </button>
          <div class="ctx-menu">
            <div class="ctx-i" onclick="openRename({{ $doc->id }},'{{ addslashes($doc->title) }}')">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="#5f6368"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
              Ganti nama
            </div>
            <form id="df-{{ $doc->id }}" method="POST" action="{{ route('documents.destroy',$doc->id) }}">@csrf @method('DELETE')</form>
            <div class="ctx-i del" onclick="openDelete({{ $doc->id }},'{{ addslashes($doc->title) }}')">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="#d93025"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg>
              Hapus
            </div>
          </div>
        </div>
      </div>
      @empty
      <div class="empty-state">
        <svg width="64" height="64" viewBox="0 0 24 24" fill="#bdc1c6"><path d="M14 2H6C4.9 2 4 2.9 4 4v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/></svg>
        <p>Belum ada dokumen. Buat yang pertama!</p>
      </div>
      @endforelse
    </div>
  </div>
</div>

<script>
const CSRF=document.querySelector('meta[name=csrf-token]').content;

// user dropdown
function toggleUserMenu(){document.getElementById('userDropdown').classList.toggle('show');}
document.addEventListener('click',e=>{
  if(!e.target.closest('.user-wrap'))document.getElementById('userDropdown').classList.remove('show');
  if(!e.target.closest('.doc-more-wrap'))document.querySelectorAll('.ctx-menu.open').forEach(m=>m.classList.remove('open'));
});

// ctx menu
function toggleCtx(btn){
  const m=btn.nextElementSibling,was=m.classList.contains('open');
  document.querySelectorAll('.ctx-menu.open').forEach(x=>x.classList.remove('open'));
  if(!was)m.classList.add('open');
}

// search
document.getElementById('searchInput').addEventListener('input',function(){
  const q=this.value.toLowerCase();
  document.querySelectorAll('#docsGrid .doc-card').forEach(c=>{c.style.display=(!q||c.dataset.title.includes(q))?'':'none';});
});

// sort
let az=false;
function sortDocs(){
  az=!az;
  document.getElementById('sortLabel').textContent=az?'A–Z':'Terakhir dibuka';
  const g=document.getElementById('docsGrid');
  Array.from(g.querySelectorAll('.doc-card')).sort((a,b)=>az?(a.dataset.title||'').localeCompare(b.dataset.title||''):parseInt(b.dataset.date||0)-parseInt(a.dataset.date||0)).forEach(c=>g.appendChild(c));
}

// view toggle
function setView(v){
  const lv=v==='list';
  document.getElementById('docsGrid').classList.toggle('lv',lv);
  document.getElementById('btnGrid').classList.toggle('on',!lv);
  document.getElementById('btnList').classList.toggle('on',lv);
}

// rename
let _rid=null;
function openRename(id,cur){
  document.querySelectorAll('.ctx-menu.open').forEach(x=>x.classList.remove('open'));
  _rid=id;
  const inp=document.getElementById('renameInput');
  inp.value=cur;
  document.getElementById('renameOk').disabled=false;
  document.getElementById('renameOverlay').classList.add('show');
  document.getElementById('renameModal').classList.add('show');
  setTimeout(()=>{inp.focus();inp.select();},80);
}
function closeRename(){
  document.getElementById('renameOverlay').classList.remove('show');
  document.getElementById('renameModal').classList.remove('show');
  _rid=null;
}
function submitRename(){
  const n=document.getElementById('renameInput').value.trim();
  if(!n||!_rid)return closeRename();
  const btn=document.getElementById('renameOk');btn.disabled=true;
  fetch('/documents/'+_rid+'/rename',{method:'POST',credentials:'include',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'Accept':'application/json'},body:JSON.stringify({title:n})})
    .then(r=>{if(r.ok){closeRename();location.reload();}else btn.disabled=false;}).catch(()=>btn.disabled=false);
}
document.getElementById('renameInput').addEventListener('keydown',e=>{if(e.key==='Enter'){e.preventDefault();submitRename();}if(e.key==='Escape')closeRename();});
document.getElementById('renameInput').addEventListener('input',function(){document.getElementById('renameOk').disabled=!this.value.trim();});

// delete
let _did=null;
function openDelete(id,title){
  document.querySelectorAll('.ctx-menu.open').forEach(x=>x.classList.remove('open'));
  _did=id;
  document.getElementById('deleteMsg').textContent='"'+title+'" akan dihapus secara permanen.';
  document.getElementById('deleteOverlay').classList.add('show');
  document.getElementById('deleteModal').classList.add('show');
  setTimeout(()=>document.getElementById('deleteOk').focus(),80);
}
function closeDelete(){
  document.getElementById('deleteOverlay').classList.remove('show');
  document.getElementById('deleteModal').classList.remove('show');
  _did=null;
}
function submitDelete(){
  if(!_did)return;
  const btn=document.getElementById('deleteOk');btn.disabled=true;btn.textContent='Menghapus...';
  document.getElementById('df-'+_did).submit();
}
document.getElementById('deleteModal').addEventListener('click',e=>{if(e.target===document.getElementById('deleteOverlay'))closeDelete();});
document.addEventListener('keydown',e=>{if(e.key==='Escape'){closeRename();closeDelete();}});
</script>
</body>
</html>
