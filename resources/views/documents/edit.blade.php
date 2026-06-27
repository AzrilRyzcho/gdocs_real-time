<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ $document->title }} — ZenDocs</title>
<style>
*{margin:0;padding:0;box-sizing:border-box;}
:root{--ink:#111;--sub:#888;--border:#ebebeb;--bg:#f7f7f7;--white:#fff;}
html,body{height:100%;overflow:hidden;font-family:'Segoe UI',system-ui,sans-serif;font-size:13px;color:var(--ink);background:var(--bg);}

/* TOPBAR */
#top{background:var(--white);border-bottom:1px solid var(--border);height:54px;display:flex;align-items:center;padding:0 18px;gap:10px;flex-shrink:0;z-index:20;}
.back{width:32px;height:32px;border:none;background:none;cursor:pointer;border-radius:6px;display:flex;align-items:center;justify-content:center;color:var(--sub);transition:background .1s;}
.back:hover{background:var(--bg);color:var(--ink);}
.logo-mark{width:26px;height:26px;background:var(--ink);border-radius:5px;display:flex;align-items:center;justify-content:center;font-size:13px;color:#fff;font-weight:700;flex-shrink:0;}
#docTitle{flex:1;border:none;outline:none;font-size:15px;font-weight:500;color:var(--ink);background:transparent;padding:5px 8px;border-radius:5px;transition:background .1s;min-width:0;}
#docTitle:hover{background:var(--bg);}
#docTitle:focus{background:var(--bg);}
.save-pill{display:flex;align-items:center;gap:5px;font-size:11px;color:var(--sub);white-space:nowrap;}
.s-dot{width:6px;height:6px;border-radius:50%;background:#22c55e;flex-shrink:0;}
.s-dot.saving{background:#f59e0b;animation:blink 1s infinite;}
.s-dot.err{background:#ef4444;}
@keyframes blink{0%,100%{opacity:1}50%{opacity:.2}}
.top-r{display:flex;align-items:center;gap:8px;margin-left:auto;}
.av-stack{display:flex;}
.av{width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:700;color:#fff;border:2px solid #fff;margin-left:-5px;cursor:default;box-shadow:0 1px 3px rgba(0,0,0,.12);transition:transform .15s;}
.av:first-child{margin-left:0;}
.av:hover{transform:scale(1.2);z-index:5;}
.av.typing::after{content:'✏';position:absolute;bottom:-2px;right:-2px;font-size:7px;background:#fff;border-radius:50%;padding:1px;}
.online-pill{font-size:11px;color:var(--sub);background:var(--bg);padding:3px 8px;border-radius:10px;border:1px solid var(--border);}
.btn-share{background:var(--ink);color:#fff;border:none;padding:6px 14px;border-radius:6px;font-size:12px;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:5px;transition:opacity .1s;}
.btn-share:hover{opacity:.85;}
</style>
<style>
/* MENUBAR */
#menubar{background:var(--white);border-bottom:1px solid var(--border);height:30px;display:flex;align-items:center;padding:0 16px;gap:1px;flex-shrink:0;}
.mi{padding:3px 9px;border-radius:4px;font-size:12px;color:var(--sub);cursor:pointer;user-select:none;position:relative;transition:background .1s,color .1s;}
.mi:hover,.mi.open{background:var(--bg);color:var(--ink);}
.dropdown{position:absolute;top:calc(100%+3px);left:0;background:#fff;border:1px solid var(--border);border-radius:6px;box-shadow:0 6px 20px rgba(0,0,0,.08);min-width:190px;z-index:200;display:none;padding:3px 0;}
.dropdown.show{display:block;}
.ddi{padding:6px 16px;font-size:12px;cursor:pointer;color:var(--ink);display:flex;justify-content:space-between;transition:background .1s;}
.ddi:hover{background:var(--bg);}
.ddi .sc{color:var(--sub);font-size:10px;}
.dd-sep{border:none;border-top:1px solid var(--border);margin:2px 0;}

/* TOOLBAR */
#toolbar{background:var(--white);border-bottom:1px solid var(--border);padding:3px 14px;display:flex;align-items:center;gap:1px;flex-shrink:0;flex-wrap:wrap;}
.tb{background:none;border:none;cursor:pointer;padding:3px 6px;border-radius:4px;color:var(--sub);display:flex;align-items:center;justify-content:center;height:26px;min-width:26px;font-size:12px;transition:background .1s,color .1s;}
.tb:hover{background:var(--bg);color:var(--ink);}
.tb.active{background:#f0f0f0;color:var(--ink);}
.tb-div{width:1px;height:16px;background:var(--border);margin:0 3px;flex-shrink:0;}
.tb-sel{border:1px solid var(--border);background:var(--white);border-radius:4px;padding:2px 5px;font-size:11px;color:var(--ink);cursor:pointer;outline:none;}
.tb-sel:hover,.tb-sel:focus{border-color:#aaa;}
.fs-w{display:flex;align-items:center;border:1px solid var(--border);border-radius:4px;background:var(--white);overflow:hidden;}
.fs-w:hover,.fs-w:focus-within{border-color:#aaa;}
.fs-b{background:none;border:none;cursor:pointer;padding:1px 5px;font-size:11px;color:var(--sub);}
.fs-b:hover{color:var(--ink);}
.fs-i{width:26px;text-align:center;border:none;outline:none;background:transparent;font-size:11px;}
.cw{position:relative;}
.cb{position:absolute;bottom:2px;left:4px;right:4px;height:2px;border-radius:1px;}
</style>
<style>
/* RULER */
#ruler{background:var(--bg);border-bottom:1px solid var(--border);height:20px;display:flex;overflow:hidden;flex-shrink:0;}
#rl{width:200px;flex-shrink:0;border-right:2px solid var(--ink);}
#rt{flex:1;overflow:hidden;}
#rc{height:20px;display:block;}
#rr{width:38px;flex-shrink:0;border-left:2px solid var(--ink);}

/* MAIN */
#main{display:flex;flex:1;overflow:hidden;}
#pg-gutter{width:32px;background:var(--bg);flex-shrink:0;position:relative;overflow:hidden;}
.pg-n{position:absolute;right:5px;font-size:8px;color:#ccc;user-select:none;}
#editor-area{flex:1;overflow-y:auto;overflow-x:auto;background:var(--bg);padding:18px 0 80px;display:flex;flex-direction:column;align-items:center;}
.page{background:var(--white);width:816px;min-height:1056px;border-radius:2px;box-shadow:0 1px 3px rgba(0,0,0,.06),0 4px 16px rgba(0,0,0,.04);margin-bottom:18px;position:relative;flex-shrink:0;}
#editor{padding:96px;outline:none;min-height:864px;font-family:'Georgia',serif;font-size:11pt;line-height:1.7;color:#1a1a1a;word-break:break-word;caret-color:#111;}
#editor:empty::before{content:attr(data-placeholder);color:#ccc;font-style:italic;pointer-events:none;}

/* SHORTCUT BAR */
.sc-bar{position:absolute;top:130px;left:50%;transform:translateX(-50%);display:flex;gap:8px;white-space:nowrap;pointer-events:none;}
.sc-bar.hidden{display:none;}
.sc-pill{display:flex;align-items:center;gap:6px;padding:5px 14px 5px 10px;border-radius:18px;border:1px solid var(--border);background:var(--white);font-size:12px;color:var(--sub);cursor:pointer;pointer-events:all;box-shadow:0 1px 4px rgba(0,0,0,.05);transition:border-color .15s,color .15s;}
.sc-pill:hover{border-color:#aaa;color:var(--ink);}

/* SIDEBAR */
#sidebar{width:230px;background:var(--white);border-left:1px solid var(--border);display:flex;flex-direction:column;flex-shrink:0;overflow:hidden;}
.sb-head{padding:12px 14px 10px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:8px;}
.sb-title{font-size:12px;font-weight:600;color:var(--ink);}
.sb-badge{background:var(--ink);color:#fff;font-size:10px;font-weight:700;padding:1px 7px;border-radius:10px;}
.u-list{flex:1;overflow-y:auto;padding:4px 0;}
.u-item{display:flex;align-items:center;gap:9px;padding:7px 14px;transition:background .1s;}
.u-item:hover{background:var(--bg);}
.u-av{width:32px;height:32px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:#fff;flex-shrink:0;position:relative;}
.u-av .dot{position:absolute;bottom:-1px;right:-1px;width:8px;height:8px;border-radius:50%;background:#22c55e;border:2px solid #fff;}
.u-info{flex:1;min-width:0;}
.u-name{font-size:12px;font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.u-name.you::after{content:' (kamu)';color:var(--sub);font-weight:400;font-size:10px;}
.u-status{font-size:10px;color:var(--sub);margin-top:1px;}
.u-status.typing{color:#f59e0b;}
.act-sec{border-top:1px solid var(--border);max-height:170px;display:flex;flex-direction:column;}
.act-head{padding:7px 14px 3px;font-size:10px;font-weight:600;color:var(--sub);text-transform:uppercase;letter-spacing:.06em;flex-shrink:0;}
.act-log{overflow-y:auto;flex:1;padding:0 0 6px;}
.act-i{padding:4px 14px;font-size:11px;color:var(--sub);display:flex;justify-content:space-between;gap:4px;border-left:2px solid transparent;}
.act-i.join{border-color:#22c55e;}.act-i.leave{border-color:#ef4444;}.act-i.edit{border-color:#3b82f6;}
.act-t{font-size:9px;color:#ccc;white-space:nowrap;flex-shrink:0;}

/* REMOTE CURSORS */
.rc{position:absolute;pointer-events:none;z-index:50;}
.rc-c{position:absolute;width:2px;top:0;bottom:0;animation:rc 1s ease-in-out infinite;}
@keyframes rc{0%,100%{opacity:1}50%{opacity:.15}}
.rc-l{position:absolute;top:-18px;left:0;padding:1px 6px;border-radius:3px 3px 3px 0;font-size:10px;font-weight:600;color:#fff;white-space:nowrap;}

/* SNACKBAR */
#snackbar{position:fixed;bottom:24px;left:50%;transform:translateX(-50%) translateY(60px);background:var(--ink);color:#fff;padding:9px 18px;border-radius:6px;font-size:12px;transition:transform .2s;z-index:9999;pointer-events:none;}
#snackbar.show{transform:translateX(-50%) translateY(0);}

/* MODAL */
.modal-bg{position:fixed;inset:0;background:rgba(0,0,0,.35);display:none;align-items:center;justify-content:center;z-index:1000;}
.modal-box{background:#fff;border-radius:10px;padding:32px 26px;width:360px;box-shadow:0 8px 32px rgba(0,0,0,.12);text-align:center;}
.modal-box .mi{font-size:32px;margin-bottom:10px;}
.modal-box h2{font-size:18px;font-weight:600;margin-bottom:5px;}
.modal-box p{font-size:12px;color:var(--sub);margin-bottom:18px;line-height:1.5;}
.modal-box input{width:100%;padding:9px 12px;border:1.5px solid var(--border);border-radius:6px;font-size:13px;outline:none;margin-bottom:12px;color:var(--ink);}
.modal-box input:focus{border-color:var(--ink);}
.modal-box button{width:100%;background:var(--ink);color:#fff;border:none;padding:10px;border-radius:6px;font-size:13px;font-weight:600;cursor:pointer;transition:opacity .1s;}
.modal-box button:hover{opacity:.85;}

::-webkit-scrollbar{width:4px;height:4px;}
::-webkit-scrollbar-thumb{background:#ddd;border-radius:2px;}
</style>
</head>
<body style="display:flex;flex-direction:column;height:100vh;">

{{-- MODAL --}}
<div class="modal-bg" id="nameModal">
  <div class="modal-box">
    <div class="mi">✍️</div>
    <h2>Siapa nama kamu?</h2>
    <p>Nama kamu akan terlihat oleh semua orang yang membuka dokumen ini.</p>
    <input type="text" id="nameInput" placeholder="Nama kamu..." maxlength="30" autocomplete="off">
    <button id="nameSubmit">Mulai →</button>
  </div>
</div>

{{-- TOPBAR --}}
<div id="top">
  <a href="/" class="back" title="Kembali">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
  </a>
  <div class="logo-mark">Z</div>
  <input type="text" id="docTitle" value="{{ $document->title }}" maxlength="200" spellcheck="false" placeholder="Judul...">
  <div class="save-pill"><div class="s-dot" id="sDot"></div><span id="sTxt">Tersimpan</span></div>
  <div class="top-r">
    <div class="av-stack" id="usersBar"></div>
    <span class="online-pill" id="onlineCount">1 online</span>
    <button class="btn-share">
      <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><path d="M8.59 13.51l6.83 3.98M15.41 6.51l-6.82 3.98"/></svg>
      Bagikan
    </button>
  </div>
</div>

{{-- MENUBAR --}}
<div id="menubar">
  @foreach([
    'File'    =>[['Dokumen baru','Ctrl+N'],['Buka'],null,['Unduh'],['Cetak','Ctrl+P']],
    'Edit'    =>[['Undo','Ctrl+Z'],['Redo','Ctrl+Y'],null,['Potong','Ctrl+X'],['Salin','Ctrl+C'],['Tempel','Ctrl+V'],null,['Pilih Semua','Ctrl+A']],
    'Lihat'   =>[['100%'],['Tampilan cetak'],null,['Tampilkan penggaris']],
    'Sisipkan'=>[['Link','Ctrl+K'],['Gambar'],['Tabel'],null,['Komentar']],
    'Format'  =>[['Teks'],['Paragraf'],['Spasi baris'],null,['Hapus format','Ctrl+\\']],
    'Alat'    =>[['Periksa ejaan'],['Hitung kata'],null,['Preferensi']],
    'Bantuan' =>[['Pintasan keyboard','Ctrl+/']],
  ] as $lbl=>$items)
    <div class="mi" onclick="toggleMenu(this)">{{ $lbl }}
      <div class="dropdown">
        @foreach($items as $it)
          @if(is_null($it))<hr class="dd-sep">
          @else<div class="ddi" onclick="event.stopPropagation()"><span>{{$it[0]}}</span>@if(isset($it[1]))<span class="sc">{{$it[1]}}</span>@endif</div>@endif
        @endforeach
      </div>
    </div>
  @endforeach
</div>

{{-- TOOLBAR --}}
<div id="toolbar">
  <button class="tb" onclick="document.execCommand('undo')" title="Undo">
    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M3 7h11a5 5 0 0 1 0 10H9"/><path d="M3 7l4-4M3 7l4 4"/></svg>
  </button>
  <button class="tb" onclick="document.execCommand('redo')" title="Redo">
    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M21 7H10a5 5 0 0 0 0 10h4"/><path d="M21 7l-4-4M21 7l-4 4"/></svg>
  </button>
  <button class="tb" onclick="window.print()" title="Cetak">
    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="6" y="15" width="12" height="7" rx="1"/><path d="M6 9H4a1 1 0 0 0-1 1v6h4M18 9h2a1 1 0 0 1 1 1v6h-4"/><path d="M6 9V4h12v5"/></svg>
  </button>
  <div class="tb-div"></div>
  <select class="tb-sel" id="zoomSel" style="width:58px">
    <option>50%</option><option>75%</option><option selected>100%</option><option>125%</option><option>150%</option>
  </select>
  <div class="tb-div"></div>
  <select class="tb-sel" id="blockSel" style="width:110px">
    <option value="p">Teks normal</option><option value="h1">Judul 1</option>
    <option value="h2">Judul 2</option><option value="h3">Judul 3</option>
  </select>
  <div class="tb-div"></div>
  <select class="tb-sel" id="fontSel" style="width:96px">
    <option>Georgia</option><option>Arial</option><option>Times New Roman</option>
    <option>Calibri</option><option>Verdana</option><option>Courier New</option>
  </select>
  <div class="tb-div"></div>
  <div class="fs-w">
    <button class="fs-b" id="fsD">−</button>
    <input class="fs-i" type="text" id="fsV" value="11">
    <button class="fs-b" id="fsU">+</button>
  </div>
  <div class="tb-div"></div>
  <button class="tb" data-cmd="bold" title="Tebal"><b>B</b></button>
  <button class="tb" data-cmd="italic" title="Miring"><i>I</i></button>
  <button class="tb" data-cmd="underline" title="Garis bawah"><u>U</u></button>
  <button class="tb" data-cmd="strikeThrough" title="Coret"><s>S</s></button>
  <div class="tb-div"></div>
  <div class="cw">
    <button class="tb" id="btnTC" title="Warna teks" style="padding-bottom:7px"><b>A</b><div class="cb" id="tcBar" style="background:#111"></div></button>
    <input type="color" id="tcPick" style="opacity:0;position:absolute;width:0;height:0" value="#111111">
  </div>
  <div class="cw">
    <button class="tb" id="btnHL" title="Sorot" style="padding-bottom:7px">✏<div class="cb" id="hlBar" style="background:#fef08a"></div></button>
    <input type="color" id="hlPick" style="opacity:0;position:absolute;width:0;height:0" value="#fef08a">
  </div>
  <div class="tb-div"></div>
  <button class="tb" data-cmd="justifyLeft" title="Rata kiri"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="14" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg></button>
  <button class="tb" data-cmd="justifyCenter" title="Rata tengah"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="6" y1="12" x2="18" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg></button>
  <button class="tb" data-cmd="justifyRight" title="Rata kanan"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="10" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg></button>
  <div class="tb-div"></div>
  <button class="tb" data-cmd="insertUnorderedList" title="Bullet"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="9" y1="6" x2="20" y2="6"/><line x1="9" y1="12" x2="20" y2="12"/><line x1="9" y1="18" x2="20" y2="18"/><circle cx="4" cy="6" r="1.5" fill="currentColor" stroke="none"/><circle cx="4" cy="12" r="1.5" fill="currentColor" stroke="none"/><circle cx="4" cy="18" r="1.5" fill="currentColor" stroke="none"/></svg></button>
  <button class="tb" data-cmd="insertOrderedList" title="Nomor"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="10" y1="6" x2="20" y2="6"/><line x1="10" y1="12" x2="20" y2="12"/><line x1="10" y1="18" x2="20" y2="18"/><text x="2" y="9" font-size="7" fill="currentColor" stroke="none">1</text><text x="2" y="15" font-size="7" fill="currentColor" stroke="none">2</text><text x="2" y="21" font-size="7" fill="currentColor" stroke="none">3</text></svg></button>
  <div class="tb-div"></div>
  <button class="tb" data-cmd="outdent" title="Kurangi indent"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="11" y1="6" x2="21" y2="6"/><line x1="11" y1="12" x2="21" y2="12"/><line x1="11" y1="18" x2="21" y2="18"/><path d="M7 9l-4 3 4 3"/></svg></button>
  <button class="tb" data-cmd="indent" title="Tambah indent"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="11" y1="6" x2="21" y2="6"/><line x1="11" y1="12" x2="21" y2="12"/><line x1="11" y1="18" x2="21" y2="18"/><path d="M3 9l4 3-4 3"/></svg></button>
  <button class="tb" data-cmd="removeFormat" title="Hapus format"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 3h12M8 3l4 9M16 3l-4 9"/><line x1="4" y1="21" x2="20" y2="21"/><line x1="10" y1="12" x2="14" y2="21"/></svg></button>
</div>

{{-- RULER --}}
<div id="ruler"><div id="rl"></div><div id="rt"><canvas id="rc"></canvas></div><div id="rr"></div></div>

{{-- MAIN --}}
<div id="main">
  <div id="pg-gutter"></div>
  <div id="editor-area">
    <div class="page">
      <div id="editor" contenteditable="true" spellcheck="true"
           data-placeholder="Mulai menulis...">{!! $document->content !!}</div>
      <div class="sc-bar" id="scBar">
        <button class="sc-pill" onclick="insertTpl('catatan')">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
          Catatan rapat
        </button>
        <button class="sc-pill" onclick="insertTpl('email')">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><polyline points="2,4 12,13 22,4"/></svg>
          Draf email
        </button>
        <button class="sc-pill" onclick="window.location.href='/'">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="1"/><circle cx="19" cy="12" r="1"/><circle cx="5" cy="12" r="1"/></svg>
          Lainnya
        </button>
      </div>
    </div>
  </div>
  <div id="sidebar">
    <div class="sb-head">
      <span class="sb-title">Sedang Online</span>
      <span class="sb-badge" id="onlineBadge">1</span>
    </div>
    <div class="u-list" id="userList"></div>
    <div class="act-sec">
      <div class="act-head">Aktivitas</div>
      <div class="act-log" id="actLog"></div>
    </div>
  </div>
</div>
<div id="snackbar"></div>

<script>
// ── CONFIG ────────────────────────────────────────────────────────
const DOC_ID=={{$document->id}};
const CSRF=document.querySelector('meta[name="csrf-token"]').content;
const RK='{{env("REVERB_APP_KEY")}}',RH=window.location.hostname,RP={{env("REVERB_PORT",8080)}};
const U_SAVE='/documents/{{$document->id}}',U_BC='/documents/{{$document->id}}/broadcast',U_CUR='/documents/{{$document->id}}/cursor',U_PRE='/documents/{{$document->id}}/presence';

// ── UTILS ─────────────────────────────────────────────────────────
const COLORS=['#e74c3c','#3b82f6','#10b981','#f59e0b','#8b5cf6','#ec4899','#14b8a6','#f97316'];
let _ci=0;const nxtClr=()=>COLORS[_ci++%COLORS.length];
const ini=n=>n.trim().split(/\s+/).map(w=>w[0]).join('').toUpperCase().slice(0,2)||'??';
const tnow=()=>new Date().toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit'});
const $=id=>document.getElementById(id);
const snEl=$('snackbar');let snTmr=null;
function snack(m,d=3000){snEl.textContent=m;snEl.classList.add('show');clearTimeout(snTmr);snTmr=setTimeout(()=>snEl.classList.remove('show'),d);}

// ── STATE ─────────────────────────────────────────────────────────
let myId=null,myName=null,myColor=null;
const users={},typTmrs={},rCursors={},curTmrs={},offTmrs={};

// ── MODAL ─────────────────────────────────────────────────────────
const modal=$('nameModal'),nIn=$('nameInput');
$('nameSubmit').addEventListener('click',()=>{if(nIn.value.trim())boot(nIn.value.trim());});
nIn.addEventListener('keydown',e=>{if(e.key==='Enter'&&nIn.value.trim())boot(nIn.value.trim());});
function boot(name){
  myName=name;myId=localStorage.getItem('zdocs_uid')||('u_'+Math.random().toString(36).slice(2,10));
  myColor=localStorage.getItem('zdocs_color')||nxtClr();
  localStorage.setItem('zdocs_name',myName);localStorage.setItem('zdocs_uid',myId);localStorage.setItem('zdocs_color',myColor);
  modal.style.display='none';users[myId]={name:myName,color:myColor,isTyping:false};
  renderAll();logAct('join',myName,myColor,'bergabung');
  loadEcho();sendPre('join');window._hb=setInterval(()=>sendPre('ping'),8000);
}
function sendPre(a){if(!myId)return;fetch(U_PRE,{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},body:JSON.stringify({user_id:myId,user_name:myName,color:myColor,action:a})}).catch(()=>{});}
window.addEventListener('beforeunload',()=>{sendPre('leave');clearInterval(window._hb);});
const _sn=localStorage.getItem('zdocs_name');if(_sn)boot(_sn);else modal.style.display='flex';

// ── RENDER ────────────────────────────────────────────────────────
function renderAll(){renderAv();renderSB();updateBadge();}
function renderAv(){
  const b=$('usersBar');if(!b)return;
  b.innerHTML=Object.entries(users).slice(0,6).map(([id,u])=>`<div class="av${u.isTyping?' typing':''}" style="background:${u.color};position:relative" title="${u.name}${id===myId?' (kamu)':''}${u.isTyping?' ✏️':''}">${ini(u.name)}</div>`).join('');
  const c=$('onlineCount');if(c)c.textContent=Object.keys(users).length+' online';
}
function renderSB(){
  const ul=$('userList');if(!ul)return;
  ul.innerHTML=Object.entries(users).map(([id,u])=>`<div class="u-item"><div class="u-av" style="background:${u.color}">${ini(u.name)}<div class="dot"></div></div><div class="u-info"><div class="u-name${id===myId?' you':''}">${u.name}</div><div class="u-status${u.isTyping?' typing':''}">${u.isTyping?'✏️ Mengetik...':'● Online'}</div></div></div>`).join('')||'<div style="padding:12px 14px;color:#ccc;font-size:11px">Hanya kamu di sini</div>';
}
function updateBadge(){const b=$('onlineBadge');if(b)b.textContent=Object.keys(users).length;}
function setTyping(id,v){if(!users[id])return;users[id].isTyping=v;renderAll();}
function logAct(type,name,color,text){
  const log=$('actLog');if(!log)return;
  const d=document.createElement('div');d.className='act-i '+type;
  d.innerHTML=`<span>${type==='join'?'🟢':type==='leave'?'🔴':'✏️'} <b style="color:${color}">${name}</b> ${text}</span><span class="act-t">${tnow()}</span>`;
  log.prepend(d);while(log.children.length>30)log.removeChild(log.lastChild);
}

// ── EDITOR ────────────────────────────────────────────────────────
const editor=$('editor'),docTitle=$('docTitle');
let saveTmr=null,bcTmr=null,isRem=false;
function setSave(s){const d=$('sDot'),t=$('sTxt');d.className='s-dot'+(s==='saving'?' saving':s==='err'?' err':'');t.textContent=s==='saving'?'Menyimpan...':s==='err'?'Gagal':'Tersimpan';}
async function saveDoc(){
  if(!myId)return;setSave('saving');
  try{const r=await fetch(U_SAVE,{method:'PATCH',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},body:JSON.stringify({content:editor.innerHTML,title:docTitle.value,editor_id:myId,editor_name:myName})});setSave(r.ok?'saved':'err');}
  catch{setSave('err');}
}
function broadcastNow(){
  if(!myId)return;clearTimeout(bcTmr);
  bcTmr=setTimeout(()=>fetch(U_BC,{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},body:JSON.stringify({content:editor.innerHTML,title:docTitle.value,editor_id:myId,editor_name:myName})}).catch(()=>{}),50);
}
editor.addEventListener('input',()=>{
  if(isRem)return;broadcastNow();clearTimeout(saveTmr);saveTmr=setTimeout(saveDoc,3000);
  setTyping(myId,true);clearTimeout(typTmrs[myId]);typTmrs[myId]=setTimeout(()=>setTyping(myId,false),2000);updateSC();
});
docTitle.addEventListener('input',()=>{if(isRem)return;broadcastNow();clearTimeout(saveTmr);saveTmr=setTimeout(saveDoc,3000);document.title=docTitle.value+' — ZenDocs';});
function applyRemote(data){
  if(data.editor_id===myId)return;
  if(!users[data.editor_id]){const c=data.color||nxtClr();users[data.editor_id]={name:data.editor_name,color:c,isTyping:false};logAct('join',data.editor_name,c,'bergabung');renderAll();}
  const pos=saveCaret(editor);isRem=true;editor.innerHTML=data.content;isRem=false;restoreCaret(editor,pos);
  if(data.title&&data.title!==docTitle.value){docTitle.value=data.title;document.title=data.title+' — ZenDocs';}
  setSave('saved');updateSC();
  setTyping(data.editor_id,true);clearTimeout(typTmrs[data.editor_id]);typTmrs[data.editor_id]=setTimeout(()=>setTyping(data.editor_id,false),2500);
}

// ── FORMAT ────────────────────────────────────────────────────────
document.querySelectorAll('.tb[data-cmd]').forEach(b=>b.addEventListener('click',()=>{document.execCommand(b.dataset.cmd,false,null);editor.focus();updateFmt();broadcastNow();}));
$('blockSel')?.addEventListener('change',e=>{document.execCommand('formatBlock',false,'<'+e.target.value+'>');editor.focus();broadcastNow();});
$('fontSel')?.addEventListener('change',e=>{document.execCommand('fontName',false,e.target.value);editor.focus();broadcastNow();});
const fsV=$('fsV');
function applyFS(pt){if(fsV)fsV.value=pt;document.execCommand('fontSize',false,'7');document.querySelectorAll('font[size="7"]').forEach(el=>{el.removeAttribute('size');el.style.fontSize=pt+'pt';});editor.focus();broadcastNow();}
$('fsD')?.addEventListener('click',()=>applyFS(Math.max(6,parseInt(fsV?.value||11)-1)));
$('fsU')?.addEventListener('click',()=>applyFS(Math.min(400,parseInt(fsV?.value||11)+1)));
fsV?.addEventListener('change',()=>applyFS(parseInt(fsV.value)||11));
const tcp=$('tcPick'),hlp=$('hlPick');
$('btnTC')?.addEventListener('click',()=>tcp?.click());
tcp?.addEventListener('input',e=>{document.execCommand('foreColor',false,e.target.value);$('tcBar').style.background=e.target.value;broadcastNow();});
$('btnHL')?.addEventListener('click',()=>hlp?.click());
hlp?.addEventListener('input',e=>{document.execCommand('backColor',false,e.target.value);$('hlBar').style.background=e.target.value;broadcastNow();});
$('zoomSel')?.addEventListener('change',e=>document.querySelectorAll('.page').forEach(p=>p.style.transform=`scale(${parseInt(e.target.value)/100})`));
function updateFmt(){['bold','italic','underline','strikeThrough'].forEach(c=>{const b=document.querySelector(`.tb[data-cmd="${c}"]`);if(b)b.classList.toggle('active',document.queryCommandState(c));});}
editor.addEventListener('keyup',updateFmt);editor.addEventListener('mouseup',updateFmt);
document.addEventListener('keydown',e=>{if((e.ctrlKey||e.metaKey)&&e.key==='s'){e.preventDefault();clearTimeout(saveTmr);saveDoc();}if((e.ctrlKey||e.metaKey)&&e.key==='p'){e.preventDefault();window.print();}});

// ── SHORTCUT BAR ──────────────────────────────────────────────────
function updateSC(){const b=$('scBar');if(b)b.classList.toggle('hidden',editor.innerText.trim()!=='');}
setTimeout(updateSC,100);
const TPLS={catatan:`<h2>📋 Catatan Rapat</h2><p><strong>Tanggal:</strong> ${new Date().toLocaleDateString('id-ID',{weekday:'long',year:'numeric',month:'long',day:'numeric'})}</p><p><strong>Peserta:</strong> </p><p><strong>Agenda:</strong></p><ul><li></li></ul><p><strong>Catatan:</strong></p><p><br></p><p><strong>Tindak lanjut:</strong></p><ul><li></li></ul>`,email:`<p>Kepada: </p><p>Perihal: </p><p><br></p><p>Yth. [Nama],</p><p><br></p><p>Dengan hormat,</p><p><br></p><p>[Isi pesan]</p><p><br></p><p>Terima kasih.<br><br>Salam,<br>[Nama kamu]</p>`};
function insertTpl(t){if(!TPLS[t])return;editor.innerHTML=TPLS[t];updateSC();editor.focus();broadcastNow();clearTimeout(saveTmr);saveTmr=setTimeout(saveDoc,2000);}

// ── RULER ─────────────────────────────────────────────────────────
function drawRuler(){const trk=$('rt'),cv=$('rc');if(!trk||!cv)return;const W=trk.offsetWidth;cv.width=W;cv.height=20;const ctx=cv.getContext('2d');ctx.clearRect(0,0,W,20);ctx.fillStyle='#f7f7f7';ctx.fillRect(0,0,W,20);ctx.strokeStyle='#ddd';ctx.lineWidth=1;ctx.font='8px sans-serif';ctx.fillStyle='#aaa';ctx.textAlign='center';const pcm=37.8;for(let i=0;i*pcm<=W;i++){const x=Math.round(i*pcm)+.5;ctx.beginPath();ctx.moveTo(x,i%2===0?6:12);ctx.lineTo(x,20);ctx.stroke();if(i%2===0&&i>0)ctx.fillText(i,x,6);}}
window.addEventListener('resize',drawRuler);setTimeout(drawRuler,200);

// ── MENU ──────────────────────────────────────────────────────────
function toggleMenu(el){const o=el.classList.contains('open');document.querySelectorAll('.mi.open').forEach(m=>{m.classList.remove('open');m.querySelector('.dropdown')?.classList.remove('show');});if(!o){el.classList.add('open');el.querySelector('.dropdown')?.classList.add('show');}}
document.addEventListener('click',e=>{if(!e.target.closest('.mi'))document.querySelectorAll('.mi.open').forEach(m=>{m.classList.remove('open');m.querySelector('.dropdown')?.classList.remove('show');});});

// ── CARET ─────────────────────────────────────────────────────────
function saveCaret(c){const s=window.getSelection();if(!s||!s.rangeCount)return 0;const r=s.getRangeAt(0).cloneRange();r.selectNodeContents(c);r.setEnd(s.getRangeAt(0).endContainer,s.getRangeAt(0).endOffset);return r.toString().length;}
function restoreCaret(c,pos){if(!pos&&pos!==0)return;try{const r=document.createRange(),s=window.getSelection();let p=0,done=false;function w(n){if(done)return;if(n.nodeType===3){if(p+n.length>=pos){r.setStart(n,pos-p);r.collapse(true);s.removeAllRanges();s.addRange(r);done=true;return;}p+=n.length;}else for(const ch of n.childNodes)w(ch);}w(c);if(!done){r.selectNodeContents(c);r.collapse(false);s.removeAllRanges();s.addRange(r);}}catch(_){}}

// ── REMOTE CURSORS ────────────────────────────────────────────────
function getCoords(ed,offset){try{let p=0,f=false;const r=document.createRange();function w(n){if(f)return;if(n.nodeType===3){if(p+n.length>=offset){r.setStart(n,offset-p);r.collapse(true);f=true;return;}p+=n.length;}else for(const c of n.childNodes)w(c);}w(ed);if(!f){r.selectNodeContents(ed);r.collapse(false);}const rect=r.getBoundingClientRect(),er=ed.getBoundingClientRect();return{x:rect.left-er.left,y:rect.top-er.top,h:rect.height||18};}catch{return null;}}
function renderCursor(id,name,color,offset){const ed=$('editor'),page=document.querySelector('.page');if(!page||!ed)return;if(!rCursors[id]){const w=document.createElement('div');w.className='rc';const c=document.createElement('div');c.className='rc-c';c.style.background=color;const l=document.createElement('div');l.className='rc-l';l.style.background=color;l.textContent=name;w.appendChild(c);w.appendChild(l);page.appendChild(w);rCursors[id]={el:w};}const wrap=rCursors[id].el,co=getCoords(ed,offset);if(!co)return;const er=ed.getBoundingClientRect(),pr=page.getBoundingClientRect();wrap.style.cssText=`left:${er.left-pr.left+co.x}px;top:${er.top-pr.top+co.y}px;display:block;position:absolute;pointer-events:none;z-index:50;`;wrap.querySelector('.rc-c').style.height=co.h+'px';clearTimeout(curTmrs[id]);curTmrs[id]=setTimeout(()=>{if(wrap)wrap.style.display='none';},4000);}
let cbTmr=null;
function broadcastCursor(t){if(!myId)return;const o=saveCaret($('editor'))||0;clearTimeout(cbTmr);cbTmr=setTimeout(()=>fetch(U_CUR,{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},body:JSON.stringify({editor_id:myId,editor_name:myName,color:myColor,offset:o,is_typing:t})}).catch(()=>{}),80);}
editor.addEventListener('keyup',()=>broadcastCursor(true));editor.addEventListener('mouseup',()=>broadcastCursor(false));editor.addEventListener('click',()=>broadcastCursor(false));

// ── REVERB ────────────────────────────────────────────────────────
function loadEcho(){const s1=document.createElement('script');s1.src='https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.15.3/echo.iife.js';s1.onload=()=>{const s2=document.createElement('script');s2.src='https://cdnjs.cloudflare.com/ajax/libs/pusher/8.4.0-rc2/pusher.min.js';s2.onload=connectReverb;document.head.appendChild(s2);};document.head.appendChild(s1);}
function connectReverb(){
  try{
    window.Echo=new window.LaravelEcho({broadcaster:'reverb',key:RK,wsHost:RH,wsPort:RP,wssPort:RP,forceTLS:false,enabledTransports:['ws'],disableStats:true});
    const ch=window.Echo.channel(`document.${DOC_ID}`);
    ch.listen('.document.updated',data=>applyRemote(data));
    ch.listen('.user.presence',data=>{
      if(data.user_id===myId)return;
      if(data.action==='join'||data.action==='ping'){
        const isNew=!users[data.user_id];
        users[data.user_id]={name:data.user_name,color:data.color||nxtClr(),isTyping:users[data.user_id]?.isTyping||false};
        if(isNew){logAct('join',data.user_name,users[data.user_id].color,'bergabung');snack('👋 '+data.user_name+' bergabung');}
        renderAll();clearTimeout(offTmrs[data.user_id]);
        offTmrs[data.user_id]=setTimeout(()=>{if(users[data.user_id]){logAct('leave',users[data.user_id].name,users[data.user_id].color,'pergi');delete users[data.user_id];renderAll();}},20000);
      }else if(data.action==='leave'){if(users[data.user_id]){logAct('leave',users[data.user_id].name,users[data.user_id].color,'keluar');snack('👋 '+users[data.user_id].name+' keluar');delete users[data.user_id];renderAll();}clearTimeout(offTmrs[data.user_id]);}
    });
    ch.listen('.cursor.moved',data=>{
      if(data.editor_id===myId)return;
      if(!users[data.editor_id]){const c=data.color||nxtClr();users[data.editor_id]={name:data.editor_name,color:c,isTyping:false};logAct('join',data.editor_name,c,'bergabung');renderAll();}
      renderCursor(data.editor_id,data.editor_name,data.color,data.offset);
      setTyping(data.editor_id,data.is_typing);clearTimeout(typTmrs[data.editor_id]);typTmrs[data.editor_id]=setTimeout(()=>setTyping(data.editor_id,false),3000);
    });
    snack('✓ Terhubung — edit real-time aktif');
  }catch(e){snack('⚠ WebSocket gagal');}
}
</script>
</body>
</html>
