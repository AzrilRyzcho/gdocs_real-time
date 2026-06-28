<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ $document->title }} — Writly</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Lora:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">
<style>
/* ================================================
   WRITLY EDITOR — Complete Styles
   ================================================ */
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
:root{
  --v:#1a73e8;--v-dark:#1557b0;--v-soft:#e8f0fe;--v-ring:rgba(26,115,232,.18);
  --ink:#202124;--ink-2:#3c4043;--ink-3:#5f6368;--ink-4:#80868b;--ink-5:#bdc1c6;
  --bg:#f1f3f4;--bg-card:#fff;--bg-sidebar:#fff;
  --border:#e8eaed;--border-2:#f1f3f4;
  --green:#1e8e3e;--red:#d93025;--amber:#f9ab00;
  --sh:0 1px 3px rgba(60,64,67,.15),0 4px 8px rgba(60,64,67,.1);
  --sh-lg:0 8px 30px rgba(60,64,67,.2);
  --r:4px;--r-sm:4px;--r-xs:4px;
  --font:'Google Sans','Inter',system-ui,sans-serif;
  --font-doc:'Arial',sans-serif;
  --trans:.15s ease;
}
/* Dark mode disabled untuk editor — selalu light */
html,body{height:100%;overflow:hidden;font-family:var(--font);color:var(--ink);background:var(--bg);-webkit-font-smoothing:antialiased;}
</style>
</head>
<style>
/* ── TOPBAR ── */
#topbar{height:64px;background:var(--bg-card);border-bottom:1px solid var(--border);display:flex;align-items:center;padding:0 8px 0 8px;gap:4px;flex-shrink:0;z-index:100;position:relative;}
.tb-back{width:40px;height:40px;border:none;background:none;cursor:pointer;border-radius:50%;display:flex;align-items:center;justify-content:center;color:var(--ink-3);transition:background var(--trans);}
.tb-back:hover{background:var(--bg);}
.tb-brand{display:flex;align-items:center;gap:6px;text-decoration:none;padding:0 2px;}
.tb-logo{width:32px;height:32px;background:var(--v);border-radius:var(--r);display:flex;align-items:center;justify-content:center;}
.tb-appname{font-size:13px;font-weight:500;color:var(--ink-3);}
.tb-sep{width:1px;height:20px;background:var(--border);flex-shrink:0;margin:0 4px;}
.tb-title-wrap{flex:1;min-width:0;display:flex;flex-direction:column;justify-content:center;}
#docTitle{border:none;outline:none;font-size:18px;font-weight:400;color:var(--ink);background:transparent;padding:4px 6px;border-radius:var(--r);width:100%;transition:background var(--trans);font-family:var(--font);}
#docTitle:hover{background:var(--bg);}
#docTitle:focus{background:var(--v-soft);outline:2px solid var(--v);outline-offset:-2px;}
.tb-status{display:flex;align-items:center;gap:5px;font-size:12px;color:var(--ink-3);padding:0 6px;}
.tb-status-dot{width:6px;height:6px;border-radius:50%;background:var(--ink-5);transition:background var(--trans);}
.tb-status-dot.saving{background:var(--amber);animation:pulse .8s infinite;}
.tb-status-dot.saved{background:var(--green);}
.tb-status-dot.err{background:var(--red);}
@keyframes pulse{0%,100%{opacity:1}50%{opacity:.4}}
.tb-right{display:flex;align-items:center;gap:6px;flex-shrink:0;}
.tb-btn{height:36px;padding:0 16px;border:1px solid var(--border);background:var(--bg-card);border-radius:var(--r);font-size:14px;font-weight:500;color:var(--ink-2);cursor:pointer;font-family:var(--font);display:flex;align-items:center;gap:6px;transition:background var(--trans),box-shadow var(--trans);}
.tb-btn:hover{background:var(--bg);box-shadow:0 1px 2px rgba(60,64,67,.2);}
.tb-btn-primary{background:var(--v);color:#fff;border-color:var(--v);}
.tb-btn-primary:hover{background:var(--v-dark);border-color:var(--v-dark);}
.tb-av{width:32px;height:32px;border-radius:50%;background:var(--v);color:#fff;font-size:13px;font-weight:500;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;}
.online-stack{display:flex;margin-right:2px;}
.o-av{width:28px;height:28px;border-radius:50%;border:2px solid var(--bg-card);margin-left:-6px;font-size:10px;font-weight:700;color:#fff;display:flex;align-items:center;justify-content:center;cursor:pointer;}
.o-av:first-child{margin-left:0;}
</style>
<style>
/* ── TOOLBAR ── */
#toolbar{height:42px;background:var(--bg-card);border-bottom:1px solid var(--border);display:flex;align-items:center;padding:0 16px;gap:2px;flex-shrink:0;overflow-x:auto;}
#toolbar::-webkit-scrollbar{display:none;}
.t{height:28px;min-width:28px;padding:0 5px;border:none;background:none;cursor:pointer;border-radius:var(--r-xs);color:var(--ink-3);display:flex;align-items:center;justify-content:center;font-size:13px;transition:background var(--trans),color var(--trans);}
.t:hover{background:var(--border);color:var(--ink);}
.t.on{background:var(--v-soft);color:var(--v);}
.t-div{width:1px;height:18px;background:var(--border);margin:0 4px;flex-shrink:0;}
.t-sel{height:28px;border:none;background:none;border-radius:var(--r-xs);padding:0 6px;font-size:12.5px;color:var(--ink-2);cursor:pointer;outline:none;font-family:var(--font);}
.t-sel:hover{background:var(--border);}
.t-fs{display:flex;align-items:center;border:1.5px solid var(--border);border-radius:var(--r-xs);height:28px;overflow:hidden;}
.t-fs:focus-within{border-color:var(--v);}
.t-fs-btn{background:none;border:none;cursor:pointer;padding:0 5px;color:var(--ink-3);height:100%;display:flex;align-items:center;font-size:14px;}
.t-fs-btn:hover{background:var(--border);}
.t-fs-input{width:26px;text-align:center;border:none;outline:none;background:transparent;font-size:12.5px;color:var(--ink);}
</style>
<style>
/* ── EDITOR AREA ── */
#wrap{display:flex;flex:1;overflow:hidden;}
#editor-area{flex:1;overflow-y:auto;background:var(--bg);padding:32px 24px 80px;display:flex;flex-direction:column;align-items:center;}
#editor-area::-webkit-scrollbar{width:6px;}
#editor-area::-webkit-scrollbar-thumb{background:var(--border);border-radius:3px;}
.page{background:var(--bg-card);width:min(760px,100%);min-height:900px;border-radius:var(--r);box-shadow:var(--sh);padding:72px 80px;position:relative;}
@media(max-width:700px){.page{padding:40px 24px;}}
#editor{outline:none;min-height:600px;font-family:var(--font-doc);font-size:11.5pt;line-height:1.7;color:var(--ink);word-break:break-word;caret-color:var(--v);}
#editor:empty::before{content:attr(data-placeholder);color:var(--ink-5);pointer-events:none;font-style:italic;}
#editor p{margin:0 0 10px;}
#editor h1{font-size:22pt;margin:14pt 0 6pt;font-weight:700;}
#editor h2{font-size:17pt;margin:11pt 0 5pt;font-weight:600;}
#editor h3{font-size:14pt;margin:9pt 0 4pt;font-weight:600;}
#editor ul,#editor ol{margin:0 0 10px 24px;}
#editor li{margin-bottom:4px;}
/* Checklist */
#editor .checklist-item{display:flex;align-items:flex-start;gap:8px;margin:6px 0;list-style:none;}
#editor .checklist-item input[type="checkbox"]{width:16px;height:16px;margin-top:3px;accent-color:var(--v);cursor:pointer;flex-shrink:0;}
#editor .checklist-item.done span{text-decoration:line-through;color:var(--ink-4);}
#editor .checklist-item span{flex:1;outline:none;}
/* ── RIGHT SIDEBAR ── */
#sidebar{width:260px;background:var(--bg-card);border-left:1px solid var(--border);display:flex;flex-direction:column;flex-shrink:0;overflow:hidden;transition:width var(--trans);}
#sidebar.collapsed{width:0;border:none;}
.sb-tab-bar{display:flex;border-bottom:1px solid var(--border);flex-shrink:0;}
.sb-tab{flex:1;height:38px;border:none;background:none;cursor:pointer;font-size:12px;font-weight:600;color:var(--ink-3);font-family:var(--font);border-bottom:2px solid transparent;transition:color var(--trans),border-color var(--trans);}
.sb-tab.active{color:var(--v);border-bottom-color:var(--v);}
.sb-tab-pane{display:none;flex:1;overflow-y:auto;flex-direction:column;}
.sb-tab-pane.active{display:flex;}
.sb-section{padding:14px 16px;border-bottom:1px solid var(--border-2);}
.sb-label{font-size:10.5px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:var(--ink-4);margin-bottom:10px;}
/* Online users */
.online-list{display:flex;flex-direction:column;gap:2px;}
.ol-item{display:flex;align-items:center;gap:10px;padding:6px 0;}
.ol-av{width:30px;height:30px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:#fff;flex-shrink:0;position:relative;}
.ol-dot{position:absolute;bottom:0;right:0;width:8px;height:8px;border-radius:50%;background:var(--green);border:2px solid var(--bg-card);}
.ol-name{font-size:13px;color:var(--ink-2);flex:1;min-width:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.ol-tag{font-size:10px;color:var(--ink-4);font-style:italic;}
.ol-typing{font-size:11px;color:var(--v);margin-top:1px;}
/* Activity log */
.act-log{overflow-y:auto;flex:1;padding:8px 0;}
.act-i{padding:5px 16px;font-size:12px;color:var(--ink-3);display:flex;justify-content:space-between;gap:4px;border-left:2px solid transparent;margin:1px 0;}
.act-i.join{border-color:var(--green);}.act-i.leave{border-color:var(--red);}.act-i.edit{border-color:var(--v);}
.act-t{font-size:10px;color:var(--ink-5);white-space:nowrap;flex-shrink:0;}
</style>
<style>
/* ── VERSION HISTORY PANEL ── */
#historyPanel{position:fixed;top:0;right:0;bottom:0;width:300px;background:var(--bg-card);border-left:1px solid var(--border);z-index:400;display:none;flex-direction:column;box-shadow:-4px 0 20px rgba(15,23,42,.1);}
#historyPanel.open{display:flex;}
.hp-head{height:56px;display:flex;align-items:center;padding:0 16px;gap:10px;border-bottom:1px solid var(--border);flex-shrink:0;}
.hp-title{font-size:14px;font-weight:700;color:var(--ink);flex:1;}
.hp-close{width:30px;height:30px;border:none;background:none;cursor:pointer;border-radius:var(--r-xs);color:var(--ink-4);display:flex;align-items:center;justify-content:center;}
.hp-close:hover{background:var(--border);}
.hp-list{flex:1;overflow-y:auto;padding:8px 0;}
.hp-item{padding:10px 16px;cursor:pointer;border-left:3px solid transparent;transition:background var(--trans),border-color var(--trans);}
.hp-item:hover{background:var(--border-2);}
.hp-item.active{border-left-color:var(--v);background:var(--v-soft);}
.hp-item-top{display:flex;align-items:center;gap:8px;margin-bottom:3px;}
.hp-av{width:20px;height:20px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:9px;font-weight:700;color:#fff;flex-shrink:0;}
.hp-name{font-size:12.5px;font-weight:600;color:var(--ink);}
.hp-time{font-size:11.5px;color:var(--ink-4);}
.hp-doc-title{font-size:11px;color:var(--ink-4);font-style:italic;margin-top:2px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
.hp-foot{padding:12px 16px;border-top:1px solid var(--border);display:flex;gap:8px;flex-shrink:0;}
.hp-btn{flex:1;height:34px;border-radius:var(--r-xs);font-size:12.5px;font-weight:600;cursor:pointer;border:none;font-family:var(--font);}
.hp-btn-restore{background:var(--v);color:#fff;}
.hp-btn-restore:hover{background:var(--v-dark);}
.hp-btn-restore:disabled{opacity:.4;cursor:default;}
.hp-btn-cancel{background:none;border:1.5px solid var(--border);color:var(--ink-2);}
.hp-empty{padding:32px 16px;text-align:center;color:var(--ink-4);font-size:13px;line-height:1.6;}
/* ── SNACKBAR ── */
#snackbar{position:fixed;bottom:24px;left:50%;transform:translateX(-50%) translateY(60px);background:var(--ink);color:#fff;padding:10px 20px;border-radius:99px;font-size:13px;transition:transform .25s;z-index:9999;pointer-events:none;box-shadow:var(--sh);white-space:nowrap;}
#snackbar.show{transform:translateX(-50%) translateY(0);}
/* ── MODAL NAME ── */
.nm-overlay{position:fixed;inset:0;background:rgba(15,23,42,.5);backdrop-filter:blur(4px);display:none;align-items:center;justify-content:center;z-index:1000;}
.nm-overlay.show{display:flex;}
.nm-card{background:var(--bg-card);border-radius:var(--r);padding:32px;width:min(400px,92vw);box-shadow:var(--sh-lg);}
.nm-icon{width:48px;height:48px;background:var(--v-soft);border-radius:50%;display:flex;align-items:center;justify-content:center;color:var(--v);margin:0 auto 16px;}
.nm-title{font-size:18px;font-weight:700;text-align:center;color:var(--ink);margin-bottom:6px;}
.nm-sub{font-size:13.5px;text-align:center;color:var(--ink-3);margin-bottom:24px;line-height:1.5;}
.nm-input{width:100%;height:44px;border:1.5px solid var(--border);border-radius:var(--r-sm);padding:0 14px;font-size:14px;color:var(--ink);font-family:var(--font);background:var(--bg);outline:none;margin-bottom:16px;}
.nm-input:focus{border-color:var(--v);box-shadow:0 0 0 3px var(--v-ring);}
.nm-btn{width:100%;height:44px;background:var(--v);color:#fff;border:none;border-radius:var(--r-sm);font-size:14px;font-weight:600;cursor:pointer;font-family:var(--font);transition:background var(--trans);}
.nm-btn:hover{background:var(--v-dark);}
/* ── REMOTE CURSORS ── */
.rc{position:absolute;pointer-events:none;z-index:50;}
.rc-c{position:absolute;width:2px;top:0;bottom:0;animation:blink .9s infinite;}
@keyframes blink{0%,100%{opacity:1}50%{opacity:.15}}
.rc-l{position:absolute;top:-22px;left:0;padding:2px 8px;border-radius:4px 4px 4px 0;font-size:11px;font-weight:600;color:#fff;white-space:nowrap;box-shadow:0 1px 4px rgba(0,0,0,.2);}

/* ── REMOTE USER TYPING INDICATOR ── */
#remoteTypingBar{
  position:absolute;bottom:12px;left:50%;transform:translateX(-50%);
  display:none;align-items:center;gap:8px;
  background:#fff;border:1.5px solid var(--border);border-radius:99px;
  padding:6px 16px;box-shadow:0 2px 12px rgba(0,0,0,.1);
  z-index:60;font-size:12px;color:var(--ink-2);white-space:nowrap;
  animation:fadeSlide .2s ease;
}
#remoteTypingBar.show{display:flex;}
@keyframes fadeSlide{from{opacity:0;transform:translateX(-50%) translateY(8px);}to{opacity:1;transform:translateX(-50%) translateY(0);}}
.rtb-dot{width:8px;height:8px;border-radius:50%;animation:pulse .8s infinite;}
.rtb-dots{display:flex;gap:3px;}
.rtb-dots span{width:5px;height:5px;border-radius:50%;background:var(--v);animation:bounce .6s infinite;}
.rtb-dots span:nth-child(2){animation-delay:.1s;}
.rtb-dots span:nth-child(3){animation-delay:.2s;}
@keyframes bounce{0%,100%{transform:translateY(0)}50%{transform:translateY(-3px)}}
</style>

{{-- ── NAME MODAL ── --}}
<div class="nm-overlay" id="nameModal">
  <div class="nm-card">
    <div class="nm-icon">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
    </div>
    <div class="nm-title">Siapa namamu?</div>
    <div class="nm-sub">Nama kamu akan terlihat oleh semua kolaborator yang sedang membuka dokumen ini.</div>
    <input class="nm-input" type="text" id="nameInput" placeholder="Masukkan nama..." maxlength="30" autocomplete="off">
    <button class="nm-btn" id="nameSubmit">Mulai mengedit</button>
  </div>
</div>

{{-- ── TOPBAR ── --}}
<div style="display:flex;flex-direction:column;height:100vh;">
<div id="topbar">
  <a href="{{ route('documents.index') }}" class="tb-back" title="Kembali">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
  </a>
  <a href="{{ route('documents.index') }}" class="tb-brand">
    <div class="tb-logo">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
    </div>
    <span class="tb-appname">Writly</span>
  </a>
  <div class="tb-sep"></div>
  <div class="tb-title-wrap">
    <input type="text" id="docTitle" value="{{ $document->title }}" maxlength="200" spellcheck="false" placeholder="Judul dokumen">
    <div class="tb-status">
      <div class="tb-status-dot" id="statusDot"></div>
      <span id="statusTxt">Tersimpan</span>
    </div>
  </div>
  <div class="tb-right">
    <div class="online-stack" id="onlineStack"></div>
    <button class="tb-btn" onclick="openHistoryPanel()">
      <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M12 7v5l4 2"/></svg>
      Riwayat
    </button>
    <button class="tb-btn" onclick="exportPDF()">
      <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
      Ekspor
    </button>
    <button class="tb-btn" onclick="openShareModal()">
      <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
      Bagikan
    </button>
    <button class="tb-btn tb-btn-primary" onclick="toggleSidebar()">
      <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
      Kolaborator
    </button>
    <div class="tb-av" id="myAvatar">A</div>
  </div>
</div>

{{-- ── TOOLBAR ── --}}
<div id="toolbar">
  <button class="t" onclick="document.execCommand('undo')" title="Undo (Ctrl+Z)">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 7v6h6"/><path d="M21 17a9 9 0 0 0-9-9 9 9 0 0 0-6 2.3L3 13"/></svg>
  </button>
  <button class="t" onclick="document.execCommand('redo')" title="Redo (Ctrl+Y)">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 7v6h-6"/><path d="M3 17a9 9 0 0 1 9-9 9 9 0 0 1 6 2.3l3 2.7"/></svg>
  </button>
  <div class="t-div"></div>
  <select class="t-sel" id="blockSel" style="width:120px">
    <option value="p">Teks normal</option>
    <option value="h1">Judul 1</option>
    <option value="h2">Judul 2</option>
    <option value="h3">Judul 3</option>
  </select>
  <select class="t-sel" id="fontSel" style="width:100px">
    <option value="Lora" selected>Lora</option>
    <option value="Arial">Arial</option>
    <option value="Georgia">Georgia</option>
    <option value="Courier New">Courier New</option>
    <option value="Times New Roman">Times New Roman</option>
  </select>
  <div class="t-fs">
    <button class="t-fs-btn" id="fsD">−</button>
    <input class="t-fs-input" type="text" id="fsV" value="11">
    <button class="t-fs-btn" id="fsU">+</button>
  </div>
  <div class="t-div"></div>
  <button class="t" data-cmd="bold" title="Bold (Ctrl+B)"><b>B</b></button>
  <button class="t" data-cmd="italic" title="Italic (Ctrl+I)"><i>I</i></button>
  <button class="t" data-cmd="underline" title="Underline (Ctrl+U)"><u>U</u></button>
  <button class="t" data-cmd="strikeThrough" title="Strikethrough" style="text-decoration:line-through">S</button>
  <div class="t-div"></div>
  <button class="t" data-cmd="justifyLeft" title="Rata kiri">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="17" y1="10" x2="3" y2="10"/><line x1="21" y1="6" x2="3" y2="6"/><line x1="21" y1="14" x2="3" y2="14"/><line x1="17" y1="18" x2="3" y2="18"/></svg>
  </button>
  <button class="t" data-cmd="justifyCenter" title="Rata tengah">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="21" y1="10" x2="3" y2="10"/><line x1="21" y1="6" x2="3" y2="6"/><line x1="21" y1="14" x2="3" y2="14"/><line x1="21" y1="18" x2="3" y2="18"/></svg>
  </button>
  <button class="t" data-cmd="justifyRight" title="Rata kanan">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="21" y1="10" x2="7" y2="10"/><line x1="21" y1="6" x2="3" y2="6"/><line x1="21" y1="14" x2="7" y2="14"/><line x1="21" y1="18" x2="3" y2="18"/></svg>
  </button>
  <div class="t-div"></div>
  <button class="t" data-cmd="insertUnorderedList" title="Bullet list">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="9" y1="6" x2="20" y2="6"/><line x1="9" y1="12" x2="20" y2="12"/><line x1="9" y1="18" x2="20" y2="18"/><circle cx="4" cy="6" r="1" fill="currentColor"/><circle cx="4" cy="12" r="1" fill="currentColor"/><circle cx="4" cy="18" r="1" fill="currentColor"/></svg>
  </button>
  <button class="t" data-cmd="insertOrderedList" title="Numbered list">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="10" y1="6" x2="21" y2="6"/><line x1="10" y1="12" x2="21" y2="12"/><line x1="10" y1="18" x2="21" y2="18"/><path d="M4 6h1v4"/><path d="M4 10h2"/><path d="M6 18H4c0-1 2-2 2-3s-1-1.5-2-1"/></svg>
  </button>
  <button class="t" onclick="insertChecklist()" title="To-Do Checklist">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="6" height="6" rx="1"/><path d="M5 8l1.5 1.5L9 6"/><line x1="13" y1="8" x2="21" y2="8"/><rect x="3" y="14" width="6" height="6" rx="1"/><line x1="13" y1="17" x2="21" y2="17"/></svg>
  </button>
  <div class="t-div"></div>
  <button class="t" data-cmd="removeFormat" title="Hapus format">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 19l7-7 3 3-7 7-3-3z"/><path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z"/><path d="M2 2l7.586 7.586"/><circle cx="11" cy="11" r="2"/></svg>
  </button>
  <div class="t-div"></div>
  <button class="t" onclick="window.print()" title="Cetak (Ctrl+P)">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
  </button>
</div>

{{-- ── MAIN AREA ── --}}
<div id="wrap">
  <div id="editor-area">
    <div class="page">
      <div id="editor" contenteditable="true" spellcheck="true" data-placeholder="Mulai menulis sesuatu yang luar biasa...">{!! $document->content !!}</div>
      <div id="rcContainer"></div>
      <div id="remoteTypingBar">
        <div class="rtb-dots"><span></span><span></span><span></span></div>
        <span id="rtbName">Seseorang sedang mengetik...</span>
      </div>
    </div>
  </div>

  {{-- ── RIGHT SIDEBAR ── --}}
  <div id="sidebar">
    <div class="sb-tab-bar">
      <button class="sb-tab active" onclick="switchTab('collab')">Online</button>
      <button class="sb-tab" onclick="switchTab('comments')">Komentar</button>
      <button class="sb-tab" onclick="switchTab('activity')">Aktivitas</button>
    </div>

    {{-- Collaborators tab --}}
    <div class="sb-tab-pane active" id="paneCollab">
      <div class="sb-section">
        <div class="sb-label">Sedang Online</div>
        <div class="online-list" id="onlineList">
          <div style="font-size:12px;color:var(--ink-4);padding:4px 0">Belum ada kolaborator</div>
        </div>
      </div>
      @if($document->last_editor_name)
      <div class="sb-section">
        <div class="sb-label">Terakhir Diedit</div>
        <div class="ol-item">
          <div class="ol-av" style="background:{{ $document->last_editor_color ?? '#6366f1' }}">
            {{ strtoupper(mb_substr($document->last_editor_name,0,2)) }}
          </div>
          <div>
            <div class="ol-name">{{ $document->last_editor_name }}</div>
            <div class="ol-tag">{{ ($document->last_edited_at ?? $document->updated_at)->locale('id')->diffForHumans() }}</div>
          </div>
        </div>
      </div>
      @endif
    </div>

    {{-- Activity tab --}}
    <div class="sb-tab-pane" id="paneActivity">
      <div class="act-log" id="actLog"></div>
    </div>

    {{-- Comments tab --}}
    <div class="sb-tab-pane" id="paneComments">
      <div class="sb-section" style="padding-bottom:8px">
        <form id="commentForm" onsubmit="postComment(event)">
          <textarea id="commentInput" rows="2" placeholder="Tulis komentar... (@nama untuk mention)" style="width:100%;border:1.5px solid var(--border);border-radius:var(--r);padding:8px 10px;font-size:13px;font-family:var(--font);resize:none;outline:none;"></textarea>
          <button type="submit" style="margin-top:6px;height:30px;padding:0 14px;background:var(--v);color:#fff;border:none;border-radius:var(--r);font-size:12px;font-weight:600;cursor:pointer;font-family:var(--font);">Kirim</button>
        </form>
      </div>
      <div id="commentsList" style="overflow-y:auto;flex:1;padding:0 12px 12px;"></div>
    </div>
  </div>
</div>

{{-- ── VERSION HISTORY PANEL ── --}}
<div id="historyPanel">
  <div class="hp-head">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--v)" stroke-width="2"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
    <span class="hp-title">Riwayat Versi</span>
    <button class="hp-close" onclick="closeHistoryPanel()">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>
  </div>
  <div class="hp-list" id="hpList"><div class="hp-empty">Memuat...</div></div>
  <div class="hp-foot">
    <button class="hp-btn hp-btn-cancel" onclick="closeHistoryPanel()">Tutup</button>
    <button class="hp-btn hp-btn-restore" id="hpRestore" onclick="confirmRestore()" disabled>Pulihkan</button>
  </div>
</div>

<div id="snackbar"></div>

{{-- SHARE MODAL --}}
<div class="nm-overlay" id="shareModal">
  <div class="nm-card" style="max-width:480px;width:92vw" onclick="event.stopPropagation()">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
      <h3 style="font-size:18px;font-weight:600;color:var(--ink)">Bagikan Dokumen</h3>
      <button onclick="closeShareModal()" style="border:none;background:none;cursor:pointer;color:var(--ink-4);padding:4px">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <div style="display:flex;gap:8px;margin-bottom:16px;">
      <input type="email" id="shareEmail" class="nm-input" placeholder="Email pengguna..." style="margin:0;flex:1">
      <select id="shareRole" style="height:44px;border:1.5px solid var(--border);border-radius:var(--r-sm);padding:0 10px;font-size:13px;font-family:var(--font);color:var(--ink);background:var(--bg);">
        <option value="editor">Editor</option>
        <option value="viewer">Viewer</option>
      </select>
      <button onclick="submitShare()" style="height:44px;padding:0 18px;background:var(--v);color:#fff;border:none;border-radius:var(--r-sm);font-size:13px;font-weight:600;cursor:pointer;font-family:var(--font);">Kirim</button>
    </div>
    <div id="shareMsg" style="font-size:13px;margin-bottom:12px;display:none;padding:8px 12px;border-radius:6px;"></div>
    <div style="border-top:1px solid var(--border);padding-top:12px;">
      <div style="font-size:12px;font-weight:600;color:var(--ink-3);margin-bottom:8px">Orang yang punya akses:</div>
      <div id="shareList" style="max-height:150px;overflow-y:auto;"></div>
    </div>
  </div>
</div>
</div>{{-- end flex column --}}

<script>
// ── CONFIG ────────────────────────────────────────
const DOC_ID = {{ $document->id }};
const CSRF   = document.querySelector('meta[name="csrf-token"]').content;
const RK='{{env("REVERB_APP_KEY")}}', RH='{{env("REVERB_HOST","127.0.0.1")}}', RP={{env("REVERB_PORT",8080)}};
const U_SAVE = '/documents/{{ $document->id }}';
const U_BC   = '/documents/{{ $document->id }}/broadcast';
const U_CUR  = '/documents/{{ $document->id }}/cursor';
const U_PRE  = '/documents/{{ $document->id }}/presence';
const U_VER  = '/documents/{{ $document->id }}/versions';

// ── THEME ─────────────────────────────────────────
// Editor selalu putih/light — clear dark mode jika tersimpan
localStorage.removeItem('writly_theme');
document.documentElement.setAttribute('data-theme','light');

// ── UTILS ─────────────────────────────────────────
const COLORS=['#6366f1','#10b981','#f59e0b','#ec4899','#3b82f6','#8b5cf6','#ef4444','#14b8a6'];
let _ci=0; const nxtClr=()=>COLORS[_ci++%COLORS.length];
const ini=n=>{if(!n)return'?';const p=n.trim().split(/\s+/);return(p.length>=2?(p[0][0]+p[p.length-1][0]):n.slice(0,2)).toUpperCase();};
const tnow=()=>new Date().toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit'});
const $=id=>document.getElementById(id);
const snEl=$('snackbar'); let snTmr=null;
function snack(m,d=3000){snEl.textContent=m;snEl.classList.add('show');clearTimeout(snTmr);snTmr=setTimeout(()=>snEl.classList.remove('show'),d);}

// ── STATE ─────────────────────────────────────────
let myId=null,myName=null,myColor=null;
const users={},typTmrs={},rCursors={},curTmrs={},offTmrs={};

// ── NAME MODAL ────────────────────────────────────
const modal=$('nameModal'), nIn=$('nameInput');
$('nameSubmit').onclick=()=>{if(nIn.value.trim())boot(nIn.value.trim());};
nIn.onkeydown=e=>{if(e.key==='Enter'&&nIn.value.trim())boot(nIn.value.trim());};
function boot(name){
  myName=name; myId=localStorage.getItem('writly_uid')||('u_'+Math.random().toString(36).slice(2,10));
  myColor=localStorage.getItem('writly_color')||nxtClr();
  localStorage.setItem('writly_name',myName);localStorage.setItem('writly_uid',myId);localStorage.setItem('writly_color',myColor);
  modal.classList.remove('show'); $('myAvatar').textContent=ini(myName); $('myAvatar').style.background=myColor;
  users[myId]={name:myName,color:myColor,isTyping:false};
  renderOnline(); logAct('join',myName,myColor,'bergabung');
  loadEcho(); sendPre('join');
  window._hb=setInterval(()=>sendPre('ping'),8000);
}
function sendPre(a){if(!myId)return;fetch(U_PRE,{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},body:JSON.stringify({user_id:myId,user_name:myName,color:myColor,action:a})}).catch(()=>{});}
window.addEventListener('beforeunload',()=>{sendPre('leave');clearInterval(window._hb);});
const _sn=localStorage.getItem('writly_name');
if(_sn)boot(_sn);else{modal.classList.add('show');setTimeout(()=>nIn.focus(),100);}
</script>

<script>
// ── RENDER ONLINE USERS ───────────────────────────
function renderOnline(){
  const list=$('onlineList'),stack=$('onlineStack'); if(!list)return;
  const entries=Object.entries(users);
  // Sidebar list
  list.innerHTML=entries.length===0?'<div style="font-size:12px;color:var(--ink-4);padding:4px 0">Hanya kamu di sini</div>':
    entries.map(([id,u])=>`<div class="ol-item">
      <div class="ol-av" style="background:${u.color}">${ini(u.name)}<div class="ol-dot"></div></div>
      <div style="flex:1;min-width:0">
        <div class="ol-name">${u.name}${id===myId?' <span style="font-size:10px;color:var(--ink-4)">(kamu)</span>':''}</div>
        <div class="${u.isTyping?'ol-typing':'ol-tag'}">${u.isTyping?'Sedang mengetik...':'Aktif'}</div>
      </div></div>`).join('');
  // Topbar avatar stack
  if(stack)stack.innerHTML=entries.slice(0,4).map(([id,u])=>`<div class="o-av" style="background:${u.color}" title="${u.name}">${ini(u.name)}</div>`).join('');
}
function setTyping(id,v){if(!users[id])return;users[id].isTyping=v;renderOnline();}
function logAct(type,name,color,text){
  const log=$('actLog');if(!log)return;
  const d=document.createElement('div');d.className='act-i '+type;
  const icon=type==='join'?'🟢':type==='leave'?'🔴':'✏️';
  d.innerHTML=`<span>${icon} <b style="color:${color}">${name}</b> ${text}</span><span class="act-t">${tnow()}</span>`;
  log.prepend(d); while(log.children.length>40)log.removeChild(log.lastChild);
}
// Tab switcher
function switchTab(name){
  document.querySelectorAll('.sb-tab').forEach((t,i)=>{
    const names=['collab','comments','activity'];
    t.classList.toggle('active',names[i]===name);
  });
  $('paneCollab').classList.toggle('active',name==='collab');
  $('paneComments').classList.toggle('active',name==='comments');
  $('paneActivity').classList.toggle('active',name==='activity');
  if(name==='comments') loadComments();
}
// Sidebar toggle
function toggleSidebar(){$('sidebar').classList.toggle('collapsed');}
</script>

<script>
// ── EDITOR & SAVE ─────────────────────────────────
const editor=$('editor'), docTitle=$('docTitle');
let saveTmr=null, bcTmr=null, isRem=false, _lastVerSave=0;

function setSave(s){
  const dot=$('statusDot'),txt=$('statusTxt');
  if(s==='saving'){dot.className='tb-status-dot saving';txt.textContent='Menyimpan...';}
  else if(s==='err'){dot.className='tb-status-dot err';txt.textContent='Gagal menyimpan';}
  else{dot.className='tb-status-dot saved';txt.textContent='Tersimpan';}
}

async function saveDoc(){
  if(!myId)return; setSave('saving');
  try{
    const r=await fetch(U_SAVE,{method:'PATCH',credentials:'include',
      headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'Accept':'application/json'},
      body:JSON.stringify({content:editor.innerHTML,title:docTitle.value,editor_id:myId,editor_name:myName,color:myColor})});
    if(r.ok){
      setSave('saved');
      _lastSaveTime=Date.now();
      const now=Date.now();
      if(now-_lastVerSave>60000){_lastVerSave=now;saveVersion();}
    }else setSave('err');
  }catch{setSave('err');}
}

function broadcastNow(){
  if(!myId)return; clearTimeout(bcTmr);
  bcTmr=setTimeout(()=>fetch(U_BC,{method:'POST',credentials:'include',
    headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},
    body:JSON.stringify({content:editor.innerHTML,title:docTitle.value,editor_id:myId,editor_name:myName,color:myColor})}).catch(()=>{}),30);
}

editor.addEventListener('input',()=>{
  if(isRem)return; broadcastNow(); clearTimeout(saveTmr); saveTmr=setTimeout(saveDoc,300);
  setTyping(myId,true); clearTimeout(typTmrs[myId]);
  typTmrs[myId]=setTimeout(()=>setTyping(myId,false),1800);
  setSave('saving');
  // Tandai user sedang aktif mengetik — pause polling
  _userTyping=true;
  clearTimeout(_typingTimeout);
  _typingTimeout=setTimeout(()=>{_userTyping=false;},2000);
});

docTitle.addEventListener('input',()=>{
  document.title=docTitle.value+' — Writly';
  clearTimeout(saveTmr);
  saveTmr=setTimeout(()=>{
    const eid=myId||'guest'; const ename=myName||'Anonim'; const ecolor=myColor||'#6366f1';
    fetch(U_SAVE,{method:'PATCH',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},
      body:JSON.stringify({content:editor.innerHTML,title:docTitle.value,editor_id:eid,editor_name:ename,color:ecolor})})
      .then(r=>setSave(r.ok?'saved':'err')).catch(()=>setSave('err'));
    setSave('saving');
  },1500);
});

docTitle.addEventListener('blur',()=>{
  if(!docTitle.value.trim())return; clearTimeout(saveTmr);
  const eid=myId||'guest'; const ename=myName||'Anonim'; const ecolor=myColor||'#6366f1';
  setSave('saving');
  fetch(U_SAVE,{method:'PATCH',credentials:'include',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'Accept':'application/json'},
    body:JSON.stringify({content:editor.innerHTML,title:docTitle.value,editor_id:eid,editor_name:ename,color:ecolor})})
    .then(r=>setSave(r.ok?'saved':'err')).catch(()=>setSave('err'));
});

function applyRemote(data){
  if(data.editor_id===myId)return;
  if(!users[data.editor_id]){const c=data.color||nxtClr();users[data.editor_id]={name:data.editor_name,color:c,isTyping:false};logAct('edit',data.editor_name,c,'mengedit');renderOnline();}
  const pos=saveCaret(editor);isRem=true;editor.innerHTML=data.content;isRem=false;restoreCaret(editor,pos);
  if(data.title&&data.title!==docTitle.value){docTitle.value=data.title;document.title=data.title+' — Writly';}
  setSave('saved'); setTyping(data.editor_id,true); clearTimeout(typTmrs[data.editor_id]);
  typTmrs[data.editor_id]=setTimeout(()=>setTyping(data.editor_id,false),2500);
}

// ── SHORTCUTS ─────────────────────────────────────
document.addEventListener('keydown',e=>{
  if((e.ctrlKey||e.metaKey)&&e.key==='s'){e.preventDefault();clearTimeout(saveTmr);saveDoc();}
  if((e.ctrlKey||e.metaKey)&&e.key==='p'){e.preventDefault();window.print();}
});
</script>

<script>
// ── FORMAT TOOLBAR ────────────────────────────────
document.querySelectorAll('.t[data-cmd]').forEach(b=>{
  b.addEventListener('click',()=>{document.execCommand(b.dataset.cmd,false,null);editor.focus();updateFmt();broadcastNow();});
});
$('blockSel')?.addEventListener('change',e=>{
  document.execCommand('formatBlock',false,'<'+e.target.value+'>');editor.focus();broadcastNow();
});
$('fontSel')?.addEventListener('change',e=>{
  document.execCommand('fontName',false,e.target.value);editor.focus();broadcastNow();
});
const fsV=$('fsV');
function applyFS(pt){
  if(fsV)fsV.value=pt;
  document.execCommand('fontSize',false,'7');
  document.querySelectorAll('font[size="7"]').forEach(el=>{el.removeAttribute('size');el.style.fontSize=pt+'pt';});
  editor.focus();broadcastNow();
}
$('fsD')?.addEventListener('click',()=>applyFS(Math.max(6,parseInt(fsV?.value||11)-1)));
$('fsU')?.addEventListener('click',()=>applyFS(Math.min(96,parseInt(fsV?.value||11)+1)));
fsV?.addEventListener('change',()=>applyFS(parseInt(fsV.value)||11));
function updateFmt(){
  ['bold','italic','underline','strikeThrough'].forEach(c=>{
    const b=document.querySelector(`.t[data-cmd="${c}"]`);
    if(b)b.classList.toggle('on',document.queryCommandState(c));
  });
}
editor.addEventListener('keyup',updateFmt);
editor.addEventListener('mouseup',updateFmt);
</script>

<script>
// ── CARET SAVE/RESTORE ────────────────────────────
function saveCaret(c){
  const s=window.getSelection();if(!s||!s.rangeCount)return 0;
  const r=s.getRangeAt(0).cloneRange();r.selectNodeContents(c);
  r.setEnd(s.getRangeAt(0).endContainer,s.getRangeAt(0).endOffset);
  return r.toString().length;
}
function restoreCaret(c,pos){
  if(!pos&&pos!==0)return;
  try{
    const r=document.createRange(),s=window.getSelection();let p=0,done=false;
    function w(n){if(done)return;if(n.nodeType===3){if(p+n.length>=pos){r.setStart(n,pos-p);r.collapse(true);s.removeAllRanges();s.addRange(r);done=true;return;}p+=n.length;}else for(const ch of n.childNodes)w(ch);}
    w(c);if(!done){r.selectNodeContents(c);r.collapse(false);s.removeAllRanges();s.addRange(r);}
  }catch(_){}
}

// ── REMOTE CURSORS ────────────────────────────────
function getCoords(ed,offset){
  try{
    let p=0,f=false;const r=document.createRange();
    function w(n){if(f)return;if(n.nodeType===3){if(p+n.length>=offset){r.setStart(n,offset-p);r.collapse(true);f=true;return;}p+=n.length;}else for(const c of n.childNodes)w(c);}
    w(ed);if(!f){r.selectNodeContents(ed);r.collapse(false);}
    const rect=r.getBoundingClientRect(),er=ed.getBoundingClientRect();
    return{x:rect.left-er.left,y:rect.top-er.top,h:rect.height||20};
  }catch{return null;}
}
function renderCursor(id,name,color,offset){
  const ed=$('editor'),container=$('rcContainer');if(!container||!ed)return;
  if(!rCursors[id]){
    const w=document.createElement('div');w.className='rc';
    const c=document.createElement('div');c.className='rc-c';c.style.background=color;
    const l=document.createElement('div');l.className='rc-l';l.style.background=color;l.textContent=name;
    w.appendChild(c);w.appendChild(l);container.appendChild(w);rCursors[id]={el:w};
  }
  const wrap=rCursors[id].el,co=getCoords(ed,offset);if(!co)return;
  const er=ed.getBoundingClientRect(),pr=ed.closest('.page').getBoundingClientRect();
  wrap.style.cssText=`left:${er.left-pr.left+co.x}px;top:${er.top-pr.top+co.y}px;display:block;position:absolute;pointer-events:none;z-index:50;`;
  wrap.querySelector('.rc-c').style.height=co.h+'px';
  clearTimeout(curTmrs[id]);curTmrs[id]=setTimeout(()=>{if(wrap)wrap.style.display='none';},4000);
}
let cbTmr=null;
function broadcastCursor(t){
  if(!myId)return;const o=saveCaret($('editor'))||0;
  clearTimeout(cbTmr);cbTmr=setTimeout(()=>fetch(U_CUR,{method:'POST',
    headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},
    body:JSON.stringify({editor_id:myId,editor_name:myName,color:myColor,offset:o,is_typing:t})}).catch(()=>{}),80);
}
editor.addEventListener('keyup',()=>broadcastCursor(true));
editor.addEventListener('mouseup',()=>broadcastCursor(false));
editor.addEventListener('click',()=>broadcastCursor(false));
</script>

<script>
// ── VERSION HISTORY ───────────────────────────────
async function saveVersion(){
  if(!myName)return;
  fetch(U_VER,{method:'POST',credentials:'include',
    headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},
    body:JSON.stringify({content:editor.innerHTML,title:docTitle.value,editor_name:myName,editor_color:myColor})}).catch(()=>{});
}
let _hpSelected=null;
function openHistoryPanel(){
  $('historyPanel').classList.add('open');
  _hpSelected=null; $('hpRestore').disabled=true;
  loadHistory();
}
function closeHistoryPanel(){
  $('historyPanel').classList.remove('open');
  _hpSelected=null;
}
async function loadHistory(){
  const list=$('hpList');list.innerHTML='<div class="hp-empty">⏳ Memuat...</div>';
  try{
    const r=await fetch(U_VER,{credentials:'include',headers:{'Accept':'application/json','X-CSRF-TOKEN':CSRF}});
    const data=await r.json();
    if(!data.versions||data.versions.length===0){list.innerHTML='<div class="hp-empty">📄 Belum ada riwayat.<br><small>Versi disimpan tiap ~60 detik.</small></div>';return;}
    list.innerHTML=data.versions.map(v=>`
      <div class="hp-item" id="hpi-${v.id}" onclick="selectVersion(${v.id})">
        <div class="hp-item-top">
          <div class="hp-av" style="background:${v.editor_color||'#6366f1'}">${(v.editor_name||'?').slice(0,2).toUpperCase()}</div>
          <span class="hp-name">${v.editor_name||'Anonim'}</span>
        </div>
        <div class="hp-time">${v.created_at_full}</div>
        <div class="hp-time" style="color:var(--ink-5)">${v.created_at}</div>
        <div class="hp-doc-title">${v.title}</div>
      </div>`).join('');
  }catch{list.innerHTML='<div class="hp-empty">❌ Gagal memuat.</div>';}
}
async function selectVersion(id){
  document.querySelectorAll('.hp-item').forEach(e=>e.classList.remove('active'));
  document.getElementById('hpi-'+id)?.classList.add('active');
  _hpSelected=id; $('hpRestore').disabled=false;
  try{
    const r=await fetch(`${U_VER}/${id}/preview`,{credentials:'include',headers:{'Accept':'application/json','X-CSRF-TOKEN':CSRF}});
    const v=await r.json();
    isRem=true;editor.innerHTML=v.content||'';isRem=false;
    snack('👁 Melihat versi — klik Pulihkan untuk menerapkan');
  }catch{snack('❌ Gagal memuat preview');}
}
async function confirmRestore(){
  if(!_hpSelected)return;
  const btn=$('hpRestore');btn.disabled=true;btn.textContent='Memulihkan...';
  try{
    const r=await fetch(`${U_VER}/${_hpSelected}/restore`,{method:'POST',credentials:'include',
      headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':CSRF}});
    const data=await r.json();
    if(data.status==='ok'){
      isRem=true;editor.innerHTML=data.content||'';
      docTitle.value=data.title||docTitle.value;
      document.title=docTitle.value+' — Writly';
      isRem=false;setSave('saved');closeHistoryPanel();snack('✅ Versi berhasil dipulihkan!');
      _lastVerSave=Date.now();
    }else snack('❌ Gagal memulihkan');
  }catch{snack('❌ Gagal memulihkan');}
  btn.disabled=false;btn.textContent='Pulihkan';
}

// ── EXPORT PDF ────────────────────────────────────
function exportPDF(){
  window.open('/documents/{{ $document->id }}/export/pdf','_blank');
}
</script>

<script>
// ── REVERB WEBSOCKET ──────────────────────────────
function loadEcho(){
  const s1=document.createElement('script');
  s1.src='https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.15.3/echo.iife.js';
  s1.onload=()=>{
    const s2=document.createElement('script');
    s2.src='https://cdnjs.cloudflare.com/ajax/libs/pusher/8.4.0-rc2/pusher.min.js';
    s2.onload=connectReverb;document.head.appendChild(s2);
  };document.head.appendChild(s1);
}
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
        renderOnline();clearTimeout(offTmrs[data.user_id]);
        offTmrs[data.user_id]=setTimeout(()=>{if(users[data.user_id]){logAct('leave',users[data.user_id].name,users[data.user_id].color,'keluar');delete users[data.user_id];renderOnline();}},20000);
      }else if(data.action==='leave'){
        if(users[data.user_id]){logAct('leave',users[data.user_id].name,users[data.user_id].color,'keluar');snack('👋 '+users[data.user_id].name+' keluar');delete users[data.user_id];renderOnline();}
        clearTimeout(offTmrs[data.user_id]);
      }
    });
    ch.listen('.cursor.moved',data=>{
      if(data.editor_id===myId)return;
      if(!users[data.editor_id]){const c=data.color||nxtClr();users[data.editor_id]={name:data.editor_name,color:c,isTyping:false};renderOnline();}
      renderCursor(data.editor_id,data.editor_name,data.color,data.offset);
      setTyping(data.editor_id,data.is_typing);clearTimeout(typTmrs[data.editor_id]);
      typTmrs[data.editor_id]=setTimeout(()=>setTyping(data.editor_id,false),3000);
    });
    snack('✓ Real-time aktif');
  }catch(e){
    console.error('Reverb error:',e);
    snack('⚠ WebSocket gagal — menggunakan polling');
    startPolling();
  }
}

// ── POLLING FALLBACK ──────────────────────────────
let _pollInterval=null;
let _userTyping=false;
let _typingTimeout=null;
let _lastSaveTime=0;

function startPolling(){
  if(_pollInterval)return;
  _pollInterval=setInterval(async()=>{
    if(_userTyping) return;
    if(Date.now()-_lastSaveTime < 2000) return;
    try{
      const r=await fetch('/api/documents/'+DOC_ID+'/poll',{headers:{'Accept':'application/json'}});
      if(!r.ok)return;
      const data=await r.json();
      // Hanya update jika editor LAIN yang mengubah
      if(data.content && data.content!==editor.innerHTML && data.last_editor_name && data.last_editor_name!==myName){
        const pos=saveCaret(editor);
        isRem=true;editor.innerHTML=data.content;isRem=false;
        restoreCaret(editor,pos);
        setSave('saved');
        checkRemoteChanges(data);
      }
    }catch(e){}
  },500);
}
// Selalu mulai polling sebagai backup
startPolling();

// ── LIVE EDITING INDICATOR ────────────────────────
// Saat konten berubah dari polling, update sidebar Online
let _lastPolledContent='';
let _rtbTimer=null;
function checkRemoteChanges(data){
  if(data.content && data.content!==_lastPolledContent){
    _lastPolledContent=data.content;
    // Tampilkan indicator di sidebar bahwa ada yg mengedit
    if(data.last_editor_name && data.last_editor_name !== myName){
      // Show typing bar
      const bar=$('remoteTypingBar');
      const nameEl=$('rtbName');
      if(bar&&nameEl){
        nameEl.textContent=data.last_editor_name+' sedang mengetik...';
        bar.classList.add('show');
        clearTimeout(_rtbTimer);
        _rtbTimer=setTimeout(()=>bar.classList.remove('show'),4000);
      }
      // Update sidebar online
      const uid = 'remote_'+data.last_editor_name;
      users[uid]={name:data.last_editor_name, color:data.last_editor_color||nxtClr(), isTyping:true};
      renderOnline();
      clearTimeout(typTmrs[uid]);
      typTmrs[uid]=setTimeout(()=>{
        if(users[uid])users[uid].isTyping=false;
        renderOnline();
      },5000);
    }
  }
}

// expose globals
window.openHistoryPanel=openHistoryPanel;
window.closeHistoryPanel=closeHistoryPanel;
window.confirmRestore=confirmRestore;
window.selectVersion=selectVersion;
window.toggleSidebar=toggleSidebar;
window.switchTab=switchTab;
window.exportPDF=exportPDF;

// ── TO-DO CHECKLIST ───────────────────────────────
function insertChecklist(){
  const html=`<div class="checklist-item"><input type="checkbox" onchange="toggleCheckItem(this)"><span contenteditable="true">Tugas baru</span></div>`;
  document.execCommand('insertHTML',false,html);
  editor.focus(); broadcastNow();
}
function toggleCheckItem(cb){
  const item=cb.closest('.checklist-item');
  if(item){item.classList.toggle('done',cb.checked);}
  broadcastNow(); clearTimeout(saveTmr); saveTmr=setTimeout(saveDoc,2000);
}
// Delegate click for checkboxes loaded from saved content
editor.addEventListener('click',e=>{
  if(e.target.type==='checkbox'&&e.target.closest('.checklist-item')){
    const item=e.target.closest('.checklist-item');
    item.classList.toggle('done',e.target.checked);
    broadcastNow(); clearTimeout(saveTmr); saveTmr=setTimeout(saveDoc,2000);
  }
});
window.insertChecklist=insertChecklist;
window.toggleCheckItem=toggleCheckItem;

// ── SHARE ─────────────────────────────────────────
function openShareModal(){
  $('shareModal').classList.add('show');
  loadShares();
}
function closeShareModal(){$('shareModal').classList.remove('show');}
$('shareModal')?.addEventListener('click',e=>{if(e.target===$('shareModal'))closeShareModal();});

async function loadShares(){
  try{
    const r=await fetch('/documents/'+DOC_ID+'/shares',{credentials:'include',headers:{'Accept':'application/json','X-CSRF-TOKEN':CSRF}});
    const d=await r.json();
    $('shareLinkInput').value=d.share_link||'';
    const list=$('shareList');
    if(!d.shares||d.shares.length===0){list.innerHTML='<div style="font-size:12px;color:var(--ink-4)">Belum dibagikan</div>';return;}
    list.innerHTML=d.shares.map(s=>`<div style="display:flex;align-items:center;gap:8px;padding:6px 0;border-bottom:1px solid var(--border-2)">
      <span style="flex:1;font-size:13px;color:var(--ink)">${s.user.name} <span style="color:var(--ink-4)">(${s.user.email})</span></span>
      <span style="font-size:11px;padding:2px 8px;border-radius:99px;background:${s.role==='editor'?'#e8f0fe':'#f1f3f4'};color:${s.role==='editor'?'#1a73e8':'#5f6368'}">${s.role}</span>
      <button onclick="removeShare(${s.id})" style="border:none;background:none;cursor:pointer;color:var(--red);font-size:12px">×</button>
    </div>`).join('');
  }catch{}
}

async function submitShare(){
  const email=$('shareEmail').value.trim();
  const role=$('shareRole').value;
  const msg=$('shareMsg');
  if(!email){msg.style.display='block';msg.style.background='#fce8e6';msg.style.color='#d93025';msg.textContent='Masukkan email';return;}
  try{
    const r=await fetch('/documents/'+DOC_ID+'/shares',{method:'POST',credentials:'include',
      headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'Accept':'application/json'},
      body:JSON.stringify({email,role})});
    const d=await r.json();
    if(d.status==='ok'){
      msg.style.display='block';msg.style.background='#e6f4ea';msg.style.color='#137333';msg.textContent=d.message;
      $('shareEmail').value='';loadShares();
    }else{
      msg.style.display='block';msg.style.background='#fce8e6';msg.style.color='#d93025';msg.textContent=d.error||'Gagal';
    }
  }catch{msg.style.display='block';msg.style.background='#fce8e6';msg.style.color='#d93025';msg.textContent='Gagal mengirim';}
  setTimeout(()=>msg.style.display='none',4000);
}

async function removeShare(id){
  await fetch('/shares/'+id,{method:'DELETE',credentials:'include',headers:{'X-CSRF-TOKEN':CSRF}});
  loadShares();
}

function copyShareLink(){
  const inp=$('shareLinkInput');
  inp.select();document.execCommand('copy');
  snack('✓ Link disalin ke clipboard');
}

window.openShareModal=openShareModal;
window.closeShareModal=closeShareModal;
window.submitShare=submitShare;
window.removeShare=removeShare;
window.copyShareLink=copyShareLink;

// ── COMMENTS ──────────────────────────────────────
async function loadComments(){
  const list=$('commentsList');
  list.innerHTML='<div style="text-align:center;color:var(--ink-4);padding:16px;font-size:12px">Memuat...</div>';
  try{
    const r=await fetch('/documents/'+DOC_ID+'/comments',{credentials:'include',headers:{'Accept':'application/json','X-CSRF-TOKEN':CSRF}});
    const data=await r.json();
    if(!data.comments||data.comments.length===0){
      list.innerHTML='<div style="text-align:center;color:var(--ink-4);padding:24px;font-size:12px">Belum ada komentar</div>';
      return;
    }
    list.innerHTML=data.comments.map(c=>renderComment(c)).join('');
  }catch{list.innerHTML='<div style="color:var(--red);padding:12px;font-size:12px">Gagal memuat</div>';}
}

function renderComment(c){
  const replies=c.replies?c.replies.map(r=>`
    <div style="margin-left:16px;padding:6px 0;border-left:2px solid var(--border);padding-left:10px;margin-top:6px;">
      <div style="font-size:12px;font-weight:600;color:var(--ink-2)">${r.user_name}</div>
      <div style="font-size:12.5px;color:var(--ink);margin-top:2px">${r.body}</div>
      <div style="font-size:10px;color:var(--ink-4);margin-top:2px">${r.created_at}</div>
    </div>`).join(''):'';

  return `<div style="padding:10px 0;border-bottom:1px solid var(--border-2);${c.resolved?'opacity:.5':''}">
    <div style="display:flex;justify-content:space-between;align-items:center">
      <span style="font-size:12.5px;font-weight:600;color:var(--ink-2)">${c.user_name}</span>
      <span style="font-size:10px;color:var(--ink-4)">${c.created_at}</span>
    </div>
    <div style="font-size:13px;color:var(--ink);margin-top:4px;line-height:1.5">${formatMentions(c.body)}</div>
    ${replies}
    <div style="display:flex;gap:8px;margin-top:6px">
      <button onclick="replyTo(${c.id})" style="font-size:11px;color:var(--v);background:none;border:none;cursor:pointer;font-family:var(--font)">Balas</button>
      <button onclick="resolveComment(${c.id})" style="font-size:11px;color:var(--green);background:none;border:none;cursor:pointer;font-family:var(--font)">${c.resolved?'Buka kembali':'Resolve'}</button>
      ${c.is_mine?'<button onclick="deleteComment('+c.id+')" style="font-size:11px;color:var(--red);background:none;border:none;cursor:pointer;font-family:var(--font)">Hapus</button>':''}
    </div>
  </div>`;
}

function formatMentions(text){
  return text.replace(/@(\w+)/g,'<span style="color:var(--v);font-weight:600">@$1</span>');
}

let _replyParent=null;
function replyTo(id){_replyParent=id;$('commentInput').placeholder='Balas komentar...';$('commentInput').focus();}

async function postComment(e){
  e.preventDefault();
  const body=$('commentInput').value.trim();
  if(!body)return;
  try{
    await fetch('/documents/'+DOC_ID+'/comments',{method:'POST',credentials:'include',
      headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'Accept':'application/json'},
      body:JSON.stringify({body:body,parent_id:_replyParent})});
    $('commentInput').value='';_replyParent=null;
    $('commentInput').placeholder='Tulis komentar... (@nama untuk mention)';
    loadComments();
  }catch{snack('❌ Gagal mengirim komentar');}
}

async function resolveComment(id){
  await fetch('/comments/'+id+'/resolve',{method:'POST',credentials:'include',headers:{'X-CSRF-TOKEN':CSRF}});
  loadComments();
}

async function deleteComment(id){
  if(!confirm('Hapus komentar ini?'))return;
  await fetch('/comments/'+id,{method:'DELETE',credentials:'include',headers:{'X-CSRF-TOKEN':CSRF}});
  loadComments();
}

window.postComment=postComment;
window.replyTo=replyTo;
window.resolveComment=resolveComment;
window.deleteComment=deleteComment;
</script>
</body>
</html>
