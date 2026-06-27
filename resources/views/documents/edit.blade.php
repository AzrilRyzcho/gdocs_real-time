<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ $document->title }} — ZenDocs</title>
<style>
/* ════════════════════════════════════════
   RESET & VARIABLES
════════════════════════════════════════ */
*{margin:0;padding:0;box-sizing:border-box;}
:root{
  --primary:#6c63ff;
  --primary-dark:#5a52d5;
  --primary-light:#ede9ff;
  --accent:#ff6584;
  --dark:#1e1e2e;
  --text:#2d2d3a;
  --grey:#6b7280;
  --border:#e5e7eb;
  --bg:#f3f4f8;
  --white:#ffffff;
  --shadow:0 2px 12px rgba(108,99,255,.10);
  --radius:10px;
}
html,body{height:100%;overflow:hidden;font-family:'Segoe UI',system-ui,sans-serif;font-size:13px;color:var(--text);background:var(--bg);}

/* ════════════════════════════════════════
   TOPBAR
════════════════════════════════════════ */
#topbar{
  background:var(--white);
  border-bottom:2px solid var(--primary-light);
  padding:0 20px;height:58px;
  display:flex;align-items:center;gap:12px;
  flex-shrink:0;
  box-shadow:var(--shadow);
  z-index:20;position:relative;
}
.zen-logo{
  display:flex;align-items:center;gap:8px;
  text-decoration:none;
}
.zen-logo-icon{
  width:34px;height:34px;
  background:linear-gradient(135deg,var(--primary),var(--accent));
  border-radius:8px;
  display:flex;align-items:center;justify-content:center;
  font-size:18px;flex-shrink:0;
  box-shadow:0 2px 8px rgba(108,99,255,.3);
}
.zen-logo-text{
  font-size:18px;font-weight:700;
  background:linear-gradient(135deg,var(--primary),var(--accent));
  -webkit-background-clip:text;-webkit-text-fill-color:transparent;
  letter-spacing:-.3px;
}
#docTitle{
  flex:1;border:none;outline:none;
  font-size:16px;font-weight:500;color:var(--text);
  background:transparent;padding:6px 10px;
  border-radius:6px;transition:background .15s;min-width:0;
}
#docTitle:hover{background:var(--primary-light);}
#docTitle:focus{background:var(--primary-light);box-shadow:0 0 0 2px var(--primary);}
.save-badge{
  display:flex;align-items:center;gap:5px;
  font-size:12px;color:var(--grey);white-space:nowrap;
  padding:4px 10px;border-radius:20px;
  background:var(--bg);
  transition:background .2s;
}
.save-badge .dot{
  width:7px;height:7px;border-radius:50%;
  background:#10b981;flex-shrink:0;
}
.save-badge .dot.saving{background:#f59e0b;animation:blink 1s infinite;}
.save-badge .dot.error{background:var(--accent);}
@keyframes blink{0%,100%{opacity:1}50%{opacity:.2}}
.top-right{display:flex;align-items:center;gap:8px;margin-left:auto;}
.avatar-stack{display:flex;align-items:center;}
.uavatar{
  width:30px;height:30px;border-radius:50%;
  display:flex;align-items:center;justify-content:center;
  font-size:11px;font-weight:700;color:#fff;
  border:2px solid var(--white);
  margin-left:-6px;cursor:default;
  box-shadow:0 1px 4px rgba(0,0,0,.15);
  transition:transform .2s;position:relative;
}
.uavatar:first-child{margin-left:0;}
.uavatar:hover{transform:scale(1.2);z-index:5;}
.uavatar.typing::after{
  content:'✏';position:absolute;bottom:-2px;right:-2px;
  font-size:8px;background:#fff;border-radius:50%;padding:1px;
}
.online-count{
  font-size:11px;color:var(--grey);
  background:var(--bg);padding:3px 8px;
  border-radius:12px;border:1px solid var(--border);
  white-space:nowrap;margin-left:6px;
}
.btn-share{
  background:linear-gradient(135deg,var(--primary),var(--primary-dark));
  color:#fff;border:none;padding:7px 16px;
  border-radius:20px;font-size:13px;font-weight:600;
  cursor:pointer;display:flex;align-items:center;gap:6px;
  box-shadow:0 2px 8px rgba(108,99,255,.3);
  transition:transform .15s,box-shadow .15s;
}
.btn-share:hover{transform:translateY(-1px);box-shadow:0 4px 14px rgba(108,99,255,.4);}

/* ════════════════════════════════════════
   MENUBAR
════════════════════════════════════════ */
#menubar{
  background:var(--white);
  border-bottom:1px solid var(--border);
  padding:0 20px;height:32px;
  display:flex;align-items:center;gap:2px;
  flex-shrink:0;
}
.mi{
  padding:4px 10px;border-radius:5px;
  font-size:13px;color:var(--grey);
  cursor:pointer;user-select:none;position:relative;
  transition:background .1s,color .1s;
}
.mi:hover,.mi.open{background:var(--primary-light);color:var(--primary);}
.dropdown{
  position:absolute;top:calc(100%+4px);left:0;
  background:#fff;border:1px solid var(--border);
  border-radius:8px;box-shadow:0 8px 24px rgba(0,0,0,.12);
  min-width:200px;z-index:200;display:none;padding:4px 0;
}
.dropdown.show{display:block;}
.ddi{
  padding:7px 18px;font-size:13px;cursor:pointer;
  color:var(--text);display:flex;justify-content:space-between;
  transition:background .1s;
}
.ddi:hover{background:var(--primary-light);color:var(--primary);}
.ddi .sc{color:var(--grey);font-size:11px;}
.dd-sep{border:none;border-top:1px solid var(--border);margin:3px 0;}

/* ════════════════════════════════════════
   TOOLBAR
════════════════════════════════════════ */
#toolbar{
  background:var(--white);
  border-bottom:1px solid var(--border);
  padding:4px 16px;
  display:flex;align-items:center;gap:1px;
  flex-shrink:0;flex-wrap:wrap;
}
.tb{
  background:none;border:none;cursor:pointer;
  padding:4px 7px;border-radius:6px;
  color:var(--grey);display:flex;align-items:center;
  justify-content:center;height:28px;min-width:28px;
  font-size:13px;transition:background .1s,color .1s;
}
.tb:hover{background:var(--primary-light);color:var(--primary);}
.tb.active{background:var(--primary-light);color:var(--primary);}
.tb-div{width:1px;height:18px;background:var(--border);margin:0 4px;flex-shrink:0;}
.tb-sel{
  border:1px solid var(--border);background:var(--bg);
  border-radius:6px;padding:3px 6px;font-size:12px;
  color:var(--text);cursor:pointer;outline:none;
  transition:border-color .1s;
}
.tb-sel:hover,.tb-sel:focus{border-color:var(--primary);}
.fs-wrap{
  display:flex;align-items:center;gap:0;
  border:1px solid var(--border);border-radius:6px;
  background:var(--bg);overflow:hidden;
  transition:border-color .1s;
}
.fs-wrap:hover,.fs-wrap:focus-within{border-color:var(--primary);}
.fs-btn{background:none;border:none;cursor:pointer;padding:2px 5px;font-size:12px;color:var(--grey);}
.fs-btn:hover{color:var(--primary);}
.fs-in{width:28px;text-align:center;border:none;outline:none;background:transparent;font-size:12px;color:var(--text);}
.color-wrap{position:relative;}
.color-bar{position:absolute;bottom:2px;left:4px;right:4px;height:3px;border-radius:1px;}

/* ════════════════════════════════════════
   RULER
════════════════════════════════════════ */
#ruler-wrap{
  background:var(--bg);border-bottom:1px solid var(--border);
  height:22px;flex-shrink:0;display:flex;overflow:hidden;
}
#ruler-left-m{width:210px;background:var(--bg);flex-shrink:0;border-right:2px solid var(--primary);}
#ruler-track{flex:1;overflow:hidden;}
#ruler-canvas{height:22px;display:block;}
#ruler-right-m{width:40px;background:var(--bg);flex-shrink:0;border-left:2px solid var(--primary);}

/* ════════════════════════════════════════
   MAIN LAYOUT
════════════════════════════════════════ */
#main{display:flex;flex:1;overflow:hidden;}

/* page number gutter */
#pg-gutter{
  width:36px;background:var(--bg);
  flex-shrink:0;position:relative;overflow:hidden;
}
.pg-num{
  position:absolute;right:6px;font-size:9px;
  color:#c0c0c0;user-select:none;line-height:1;
}

/* editor scroll area */
#editor-area{
  flex:1;overflow-y:auto;overflow-x:auto;
  background:var(--bg);
  padding:20px 0 80px;
  display:flex;flex-direction:column;align-items:center;
  gap:0;
}
.page-sheet{
  background:var(--white);width:816px;min-height:1056px;
  border-radius:2px;
  box-shadow:0 1px 4px rgba(0,0,0,.08),0 8px 24px rgba(0,0,0,.04);
  margin-bottom:20px;position:relative;flex-shrink:0;
}
#editor{
  padding:80px 96px;
  outline:none;min-height:900px;
  font-family:'Georgia',serif;font-size:11pt;
  line-height:1.65;color:#1a1a2e;
  word-break:break-word;caret-color:var(--primary);
}
#editor:empty::before{
  content:attr(data-placeholder);
  color:#c4c4d4;pointer-events:none;font-style:italic;
}

/* shortcut bar */
.sc-bar{
  position:absolute;top:128px;left:50%;
  transform:translateX(-50%);
  display:flex;align-items:center;gap:8px;
  white-space:nowrap;pointer-events:none;
}
.sc-bar.hidden{display:none;}
.sc-pill{
  display:flex;align-items:center;gap:6px;
  padding:6px 14px 6px 10px;border-radius:20px;
  border:1px solid var(--border);background:var(--white);
  font-size:13px;color:var(--grey);cursor:pointer;
  pointer-events:all;box-shadow:var(--shadow);
  transition:border-color .15s,box-shadow .15s;
}
.sc-pill:hover{border-color:var(--primary);color:var(--primary);box-shadow:0 4px 16px rgba(108,99,255,.15);}

/* ════════════════════════════════════════
   SIDEBAR — online users
════════════════════════════════════════ */
#sidebar{
  width:240px;background:var(--white);
  border-left:1px solid var(--border);
  display:flex;flex-direction:column;
  flex-shrink:0;overflow:hidden;
}
.sb-head{
  padding:14px 16px 10px;
  border-bottom:1px solid var(--border);
  display:flex;align-items:center;gap:8px;
}
.sb-head-title{font-size:13px;font-weight:700;color:var(--text);}
.sb-badge{
  background:linear-gradient(135deg,var(--primary),var(--accent));
  color:#fff;font-size:11px;font-weight:700;
  padding:1px 8px;border-radius:10px;
}
.user-list{flex:1;overflow-y:auto;padding:6px 0;}
.u-item{display:flex;align-items:center;gap:10px;padding:8px 16px;transition:background .1s;}
.u-item:hover{background:var(--primary-light);}
.u-av{
  width:36px;height:36px;border-radius:10px;
  display:flex;align-items:center;justify-content:center;
  font-size:13px;font-weight:700;color:#fff;
  flex-shrink:0;position:relative;
}
.u-av .dot{
  position:absolute;bottom:-2px;right:-2px;
  width:10px;height:10px;border-radius:50%;
  background:#10b981;border:2px solid #fff;
}
.u-info{flex:1;min-width:0;}
.u-name{font-size:13px;font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.u-name.you::after{content:' (kamu)';color:var(--grey);font-weight:400;font-size:11px;}
.u-status{font-size:11px;color:var(--grey);margin-top:1px;}
.u-status.typing{color:var(--primary);}

/* activity */
.act-section{border-top:1px solid var(--border);max-height:180px;display:flex;flex-direction:column;}
.act-head{padding:8px 16px 4px;font-size:10px;font-weight:700;color:var(--grey);text-transform:uppercase;letter-spacing:.06em;flex-shrink:0;}
.act-log{overflow-y:auto;flex:1;padding:0 0 6px;}
.act-item{padding:4px 16px;font-size:11px;color:var(--grey);display:flex;justify-content:space-between;gap:4px;border-left:2px solid transparent;}
.act-item.join{border-color:#10b981;}.act-item.leave{border-color:var(--accent);}.act-item.edit{border-color:var(--primary);}
.act-time{font-size:10px;color:#c4c4d4;white-space:nowrap;flex-shrink:0;}

/* ════════════════════════════════════════
   REMOTE CURSORS
════════════════════════════════════════ */
.rc-wrap{position:absolute;pointer-events:none;z-index:50;}
.rc-caret{position:absolute;width:2px;top:0;bottom:0;animation:rc-blink 1s ease-in-out infinite;}
@keyframes rc-blink{0%,100%{opacity:1}50%{opacity:.2}}
.rc-label{
  position:absolute;top:-20px;left:0;
  padding:2px 6px;border-radius:4px 4px 4px 0;
  font-size:10px;font-weight:700;color:#fff;
  white-space:nowrap;box-shadow:0 1px 4px rgba(0,0,0,.2);
}

/* ════════════════════════════════════════
   SNACKBAR
════════════════════════════════════════ */
#snackbar{
  position:fixed;bottom:28px;left:50%;
  transform:translateX(-50%) translateY(80px);
  background:var(--dark);color:#fff;
  padding:10px 20px;border-radius:8px;font-size:13px;
  transition:transform .25s;z-index:9999;pointer-events:none;
  box-shadow:0 4px 16px rgba(0,0,0,.2);
}
#snackbar.show{transform:translateX(-50%) translateY(0);}

/* ════════════════════════════════════════
   NAME MODAL
════════════════════════════════════════ */
.modal-bg{
  position:fixed;inset:0;background:rgba(30,30,46,.5);
  display:none;align-items:center;justify-content:center;z-index:1000;
  backdrop-filter:blur(4px);
}
.modal-box{
  background:#fff;border-radius:16px;padding:36px 30px;
  width:380px;box-shadow:0 16px 48px rgba(108,99,255,.2);
  text-align:center;
}
.modal-icon{font-size:40px;margin-bottom:12px;}
.modal-box h2{font-size:22px;font-weight:700;color:var(--text);margin-bottom:6px;}
.modal-box p{font-size:13px;color:var(--grey);margin-bottom:20px;line-height:1.5;}
.modal-box input{
  width:100%;padding:11px 14px;
  border:2px solid var(--border);border-radius:8px;
  font-size:14px;outline:none;margin-bottom:14px;
  transition:border-color .2s;color:var(--text);
}
.modal-box input:focus{border-color:var(--primary);}
.modal-box button{
  width:100%;
  background:linear-gradient(135deg,var(--primary),var(--primary-dark));
  color:#fff;border:none;padding:12px;border-radius:8px;
  font-size:14px;font-weight:600;cursor:pointer;
  box-shadow:0 4px 14px rgba(108,99,255,.3);
  transition:transform .15s;
}
.modal-box button:hover{transform:translateY(-1px);}

::-webkit-scrollbar{width:5px;height:5px;}
::-webkit-scrollbar-thumb{background:#d4d4e8;border-radius:3px;}
::-webkit-scrollbar-thumb:hover{background:var(--primary);}
</style>
</head>
<body style="display:flex;flex-direction:column;height:100vh;">

{{-- ── NAME MODAL ── --}}
<div class="modal-bg" id="nameModal">
  <div class="modal-box">
    <div class="modal-icon">✍️</div>
    <h2>Masuk sebagai siapa?</h2>
    <p>Nama kamu akan terlihat oleh semua orang yang sedang membuka dokumen ini.</p>
    <input type="text" id="nameInput" placeholder="Nama kamu..." maxlength="30" autocomplete="off">
    <button id="nameSubmit">Mulai Mengedit →</button>
  </div>
</div>

{{-- ── TOPBAR ── --}}
<div id="topbar">
  <a href="/" class="zen-logo">
    <div class="zen-logo-icon">⚡</div>
    <span class="zen-logo-text">ZenDocs</span>
  </a>

  <input type="text" id="docTitle" value="{{ $document->title }}" maxlength="200" spellcheck="false" placeholder="Judul dokumen...">

  <div class="save-badge">
    <div class="dot" id="saveDot"></div>
    <span id="saveText">Tersimpan</span>
  </div>

  <div class="top-right">
    <div class="avatar-stack" id="usersBar"></div>
    <span class="online-count" id="onlineCount">1 online</span>
    <button class="btn-share">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><path d="M8.59 13.51l6.83 3.98M15.41 6.51l-6.82 3.98"/></svg>
      Bagikan
    </button>
  </div>
</div>

{{-- ── MENUBAR ── --}}
<div id="menubar">
  @foreach([
    'File'    => [['Dokumen baru','Ctrl+N'],['Buka'],null,['Unduh'],['Cetak','Ctrl+P']],
    'Edit'    => [['Undo','Ctrl+Z'],['Redo','Ctrl+Y'],null,['Potong','Ctrl+X'],['Salin','Ctrl+C'],['Tempel','Ctrl+V'],null,['Pilih Semua','Ctrl+A']],
    'Lihat'   => [['100%'],['Tampilan cetak'],null,['Tampilkan penggaris']],
    'Sisipkan'=> [['Link','Ctrl+K'],['Gambar'],['Tabel'],null,['Komentar','Ctrl+Alt+M']],
    'Format'  => [['Teks'],['Paragraf'],['Spasi baris'],null,['Hapus format','Ctrl+\\']],
    'Alat'    => [['Periksa ejaan'],['Hitung kata'],null,['Preferensi']],
    'Bantuan' => [['Pintasan keyboard','Ctrl+/'],['Tentang ZenDocs']],
  ] as $label => $items)
    <div class="mi" data-menu="{{ $label }}" onclick="toggleMenu(this)">
      {{ $label }}
      <div class="dropdown" id="menu-{{ $label }}">
        @foreach($items as $item)
          @if(is_null($item))
            <hr class="dd-sep">
          @else
            <div class="ddi" onclick="event.stopPropagation()">
              <span>{{ $item[0] }}</span>
              @if(isset($item[1]))<span class="sc">{{ $item[1] }}</span>@endif
            </div>
          @endif
        @endforeach
      </div>
    </div>
  @endforeach
</div>

{{-- ── TOOLBAR ── --}}
<div id="toolbar">
  <button class="tb" onclick="document.execCommand('undo')" title="Undo">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M3 7h11a5 5 0 0 1 0 10H9"/><path d="M3 7l4-4M3 7l4 4"/></svg>
  </button>
  <button class="tb" onclick="document.execCommand('redo')" title="Redo">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M21 7H10a5 5 0 0 0 0 10h4"/><path d="M21 7l-4-4M21 7l-4 4"/></svg>
  </button>
  <button class="tb" onclick="window.print()" title="Cetak">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="6" y="15" width="12" height="7" rx="1"/><path d="M6 9H4a1 1 0 0 0-1 1v6h4M18 9h2a1 1 0 0 1 1 1v6h-4"/><path d="M6 9V4h12v5"/></svg>
  </button>
  <div class="tb-div"></div>

  <select class="tb-sel" id="zoomSel" style="width:62px" title="Zoom">
    <option>50%</option><option>75%</option><option selected>100%</option>
    <option>125%</option><option>150%</option>
  </select>
  <div class="tb-div"></div>

  <select class="tb-sel" id="blockSel" style="width:118px" title="Gaya paragraf">
    <option value="p">Teks normal</option>
    <option value="h1">Judul 1</option><option value="h2">Judul 2</option>
    <option value="h3">Judul 3</option><option value="h4">Judul 4</option>
  </select>
  <div class="tb-div"></div>

  <select class="tb-sel" id="fontSel" style="width:100px" title="Font">
    <option>Georgia</option><option>Arial</option>
    <option>Times New Roman</option><option>Calibri</option>
    <option>Verdana</option><option>Courier New</option>
  </select>
  <div class="tb-div"></div>

  <div class="fs-wrap">
    <button class="fs-btn" id="fsDown">−</button>
    <input class="fs-in" type="text" id="fsVal" value="11" title="Ukuran font">
    <button class="fs-btn" id="fsUp">+</button>
  </div>
  <div class="tb-div"></div>

  <button class="tb" data-cmd="bold"          title="Tebal (Ctrl+B)"><b>B</b></button>
  <button class="tb" data-cmd="italic"        title="Miring (Ctrl+I)"><i>I</i></button>
  <button class="tb" data-cmd="underline"     title="Garis bawah (Ctrl+U)"><u>U</u></button>
  <button class="tb" data-cmd="strikeThrough" title="Coret"><s>S</s></button>
  <div class="tb-div"></div>

  <div class="color-wrap">
    <button class="tb" id="btnTC" title="Warna teks" style="position:relative;padding-bottom:8px">
      <b>A</b><div class="color-bar" id="tcBar" style="background:#1a1a2e"></div>
    </button>
    <input type="color" id="tcPick" style="opacity:0;position:absolute;width:0;height:0" value="#1a1a2e">
  </div>
  <div class="color-wrap">
    <button class="tb" id="btnHL" title="Sorot" style="position:relative;padding-bottom:8px">
      <span>✏</span><div class="color-bar" id="hlBar" style="background:#fef08a"></div>
    </button>
    <input type="color" id="hlPick" style="opacity:0;position:absolute;width:0;height:0" value="#fef08a">
  </div>
  <div class="tb-div"></div>

  <button class="tb" data-cmd="justifyLeft"   title="Rata kiri">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="14" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
  </button>
  <button class="tb" data-cmd="justifyCenter" title="Rata tengah">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="6" y1="12" x2="18" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
  </button>
  <button class="tb" data-cmd="justifyRight"  title="Rata kanan">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="10" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
  </button>
  <button class="tb" data-cmd="justifyFull"   title="Rata penuh">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
  </button>
  <div class="tb-div"></div>

  <button class="tb" data-cmd="insertUnorderedList" title="Bullet list">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="9" y1="6" x2="20" y2="6"/><line x1="9" y1="12" x2="20" y2="12"/><line x1="9" y1="18" x2="20" y2="18"/><circle cx="4" cy="6" r="1.5" fill="currentColor" stroke="none"/><circle cx="4" cy="12" r="1.5" fill="currentColor" stroke="none"/><circle cx="4" cy="18" r="1.5" fill="currentColor" stroke="none"/></svg>
  </button>
  <button class="tb" data-cmd="insertOrderedList" title="Daftar bernomor">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="10" y1="6" x2="20" y2="6"/><line x1="10" y1="12" x2="20" y2="12"/><line x1="10" y1="18" x2="20" y2="18"/><text x="2" y="9" font-size="7" fill="currentColor" stroke="none">1</text><text x="2" y="15" font-size="7" fill="currentColor" stroke="none">2</text><text x="2" y="21" font-size="7" fill="currentColor" stroke="none">3</text></svg>
  </button>
  <div class="tb-div"></div>

  <button class="tb" data-cmd="outdent" title="Kurangi indentasi">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="11" y1="6" x2="21" y2="6"/><line x1="11" y1="12" x2="21" y2="12"/><line x1="11" y1="18" x2="21" y2="18"/><path d="M7 9l-4 3 4 3"/></svg>
  </button>
  <button class="tb" data-cmd="indent" title="Tambah indentasi">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="11" y1="6" x2="21" y2="6"/><line x1="11" y1="12" x2="21" y2="12"/><line x1="11" y1="18" x2="21" y2="18"/><path d="M3 9l4 3-4 3"/></svg>
  </button>
  <button class="tb" data-cmd="removeFormat" title="Hapus format">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 3h12M8 3l4 9M16 3l-4 9"/><line x1="4" y1="21" x2="20" y2="21"/><line x1="10" y1="12" x2="14" y2="21"/></svg>
  </button>
</div>

{{-- ── RULER ── --}}
<div id="ruler-wrap">
  <div id="ruler-left-m"></div>
  <div id="ruler-track"><canvas id="ruler-canvas"></canvas></div>
  <div id="ruler-right-m"></div>
</div>

{{-- ── MAIN ── --}}
<div id="main">
  <div id="pg-gutter"></div>

  <div id="editor-area">
    <div class="page-sheet">
      <div id="editor" contenteditable="true" spellcheck="true"
           data-placeholder="Mulai menulis di sini...">{!! $document->content !!}</div>

      {{-- Shortcut bar --}}
      <div class="sc-bar" id="scBar">
        <button class="sc-pill" onclick="insertTpl('catatan')">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
          Catatan rapat
        </button>
        <button class="sc-pill" onclick="insertTpl('email')">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><polyline points="2,4 12,13 22,4"/></svg>
          Draf email
        </button>
        <button class="sc-pill" onclick="window.location.href='/'">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="1"/><circle cx="19" cy="12" r="1"/><circle cx="5" cy="12" r="1"/></svg>
          Lainnya
        </button>
      </div>
    </div>
  </div>

  {{-- Sidebar --}}
  <div id="sidebar">
    <div class="sb-head">
      <span class="sb-head-title">⚡ Sedang Online</span>
      <span class="sb-badge" id="onlineBadge">1</span>
    </div>
    <div class="user-list" id="userList"></div>
    <div class="act-section">
      <div class="act-head">Aktivitas</div>
      <div class="act-log" id="actLog"></div>
    </div>
  </div>
</div>

<div id="snackbar"></div>

<script>
// ════════════════════════════════════════════════════════
//  CONFIG
// ════════════════════════════════════════════════════════
const DOC_ID       = {{ $document->id }};
const CSRF         = document.querySelector('meta[name="csrf-token"]').content;
const REVERB_KEY   = '{{ env("REVERB_APP_KEY") }}';
const REVERB_HOST  = window.location.hostname;
const REVERB_PORT  = {{ env("REVERB_PORT", 8080) }};
const URL_SAVE     = '/documents/{{ $document->id }}';
const URL_BCAST    = '/documents/{{ $document->id }}/broadcast';
const URL_CURSOR   = '/documents/{{ $document->id }}/cursor';
const URL_PRESENCE = '/documents/{{ $document->id }}/presence';

// ════════════════════════════════════════════════════════
//  UTILS
// ════════════════════════════════════════════════════════
const COLORS=['#6c63ff','#ff6584','#10b981','#f59e0b','#3b82f6','#ec4899','#14b8a6','#8b5cf6'];
let _ci=0;
const nextColor = () => COLORS[_ci++ % COLORS.length];
const initials  = n => n.trim().split(/\s+/).map(w=>w[0]).join('').toUpperCase().slice(0,2)||'??';
const timeNow   = () => new Date().toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit'});
const $         = id => document.getElementById(id);

const snackEl = $('snackbar');
let snackTmr  = null;
function snack(msg, dur=3000) {
  snackEl.textContent = msg;
  snackEl.classList.add('show');
  clearTimeout(snackTmr);
  snackTmr = setTimeout(() => snackEl.classList.remove('show'), dur);
}

// ════════════════════════════════════════════════════════
//  STATE
// ════════════════════════════════════════════════════════
let myId=null, myName=null, myColor=null;
const users         = {};
const typingTmrs    = {};
const remoteCursors = {};
const cursorTmrs    = {};
let _offTimers      = {};

// ════════════════════════════════════════════════════════
//  NAME MODAL
// ════════════════════════════════════════════════════════
const modal     = $('nameModal');
const nameInput = $('nameInput');

$('nameSubmit').addEventListener('click', () => { if(nameInput.value.trim()) boot(nameInput.value.trim()); });
nameInput.addEventListener('keydown', e => { if(e.key==='Enter'&&nameInput.value.trim()) boot(nameInput.value.trim()); });

function boot(name) {
  myName  = name;
  myId    = localStorage.getItem('zdocs_uid')   || ('u_'+Math.random().toString(36).slice(2,10));
  myColor = localStorage.getItem('zdocs_color') || nextColor();
  localStorage.setItem('zdocs_name', myName);
  localStorage.setItem('zdocs_uid',  myId);
  localStorage.setItem('zdocs_color',myColor);
  modal.style.display = 'none';
  users[myId] = { name:myName, color:myColor, isTyping:false };
  renderAll();
  logAct('join', myName, myColor, 'bergabung');
  loadEcho();
  sendPresence('join');
  window._hb = setInterval(() => sendPresence('ping'), 8000);
}

// Kirim presence
function sendPresence(action) {
  if (!myId) return;
  fetch(URL_PRESENCE,{
    method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},
    body:JSON.stringify({user_id:myId,user_name:myName,color:myColor,action}),
  }).catch(()=>{});
}

window.addEventListener('beforeunload',()=>{ sendPresence('leave'); clearInterval(window._hb); });

// Auto-boot
const _sn = localStorage.getItem('zdocs_name');
if (_sn) boot(_sn); else modal.style.display='flex';

// ════════════════════════════════════════════════════════
//  RENDER
// ════════════════════════════════════════════════════════
function renderAll() { renderAvatars(); renderSidebar(); updateBadge(); }

function renderAvatars() {
  const bar = $('usersBar');
  if (!bar) return;
  bar.innerHTML = Object.entries(users).slice(0,6).map(([id,u]) =>
    `<div class="uavatar${u.isTyping?' typing':''}" style="background:${u.color}"
      title="${u.name}${id===myId?' (kamu)':''}${u.isTyping?' ✏️':''}">${initials(u.name)}</div>`
  ).join('');
  const cnt = $('onlineCount');
  if (cnt) cnt.textContent = Object.keys(users).length + ' online';
}

function renderSidebar() {
  const ul = $('userList');
  if (!ul) return;
  ul.innerHTML = Object.entries(users).map(([id,u]) => `
    <div class="u-item">
      <div class="u-av" style="background:${u.color}">${initials(u.name)}<div class="dot"></div></div>
      <div class="u-info">
        <div class="u-name${id===myId?' you':''}">${u.name}</div>
        <div class="u-status${u.isTyping?' typing':''}">${u.isTyping?'✏️ Mengetik...':'● Online'}</div>
      </div>
    </div>`).join('')||'<div style="padding:14px 16px;color:#c4c4d4;font-size:12px">Hanya kamu di sini</div>';
}

function updateBadge() {
  const b = $('onlineBadge');
  if (b) b.textContent = Object.keys(users).length;
}

function setTyping(id, val) {
  if (!users[id]) return;
  users[id].isTyping = val;
  renderAll();
}

function logAct(type, name, color, text) {
  const log = $('actLog');
  if (!log) return;
  const d = document.createElement('div');
  d.className = 'act-item '+type;
  d.innerHTML = `<span>${type==='join'?'🟢':type==='leave'?'🔴':'✏️'} <b style="color:${color}">${name}</b> ${text}</span><span class="act-time">${timeNow()}</span>`;
  log.prepend(d);
  while(log.children.length>30) log.removeChild(log.lastChild);
}

// ════════════════════════════════════════════════════════
//  EDITOR  — BROADCAST REAL-TIME + SAVE
// ════════════════════════════════════════════════════════
const editor  = $('editor');
const docTitle= $('docTitle');
let saveTmr=null, bcastTmr=null, isRemote=false;

function setSave(s) {
  const dot = $('saveDot'), txt = $('saveText');
  dot.className = 'dot'+(s==='saving'?' saving':s==='error'?' error':'');
  txt.textContent = s==='saving'?'Menyimpan...':s==='error'?'Gagal':'Tersimpan';
}

async function saveDoc() {
  if (!myId) return;
  setSave('saving');
  try {
    const r = await fetch(URL_SAVE,{
      method:'PATCH',
      headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},
      body:JSON.stringify({content:editor.innerHTML,title:docTitle.value,editor_id:myId,editor_name:myName}),
    });
    setSave(r.ok?'saved':'error');
  } catch { setSave('error'); }
}

// Broadcast tiap keystroke (50ms debounce)
function broadcastNow() {
  if (!myId) return;
  clearTimeout(bcastTmr);
  bcastTmr = setTimeout(() => {
    fetch(URL_BCAST,{
      method:'POST',
      headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},
      body:JSON.stringify({content:editor.innerHTML,title:docTitle.value,editor_id:myId,editor_name:myName}),
    }).catch(()=>{});
  }, 50);
}

editor.addEventListener('input', () => {
  if (isRemote) return;
  broadcastNow();
  clearTimeout(saveTmr); saveTmr = setTimeout(saveDoc, 3000);
  setTyping(myId, true);
  clearTimeout(typingTmrs[myId]);
  typingTmrs[myId] = setTimeout(()=>setTyping(myId,false), 2000);
  updateScBar();
});

docTitle.addEventListener('input', () => {
  if (isRemote) return;
  broadcastNow();
  clearTimeout(saveTmr); saveTmr = setTimeout(saveDoc, 3000);
  document.title = docTitle.value + ' — ZenDocs';
});

// Terima perubahan dari device lain
function applyRemote(data) {
  if (data.editor_id === myId) return;
  if (!users[data.editor_id]) {
    const c = data.color || nextColor();
    users[data.editor_id] = {name:data.editor_name,color:c,isTyping:false};
    logAct('join',data.editor_name,c,'bergabung');
    snack(`👋 ${data.editor_name} bergabung`);
    renderAll();
  }
  const before = saveCaret(editor);
  isRemote = true;
  editor.innerHTML = data.content;
  isRemote = false;
  restoreCaret(editor, before);
  if (data.title && data.title !== docTitle.value) {
    docTitle.value = data.title;
    document.title = data.title + ' — ZenDocs';
  }
  setSave('saved');
  updateScBar();
  setTyping(data.editor_id, true);
  clearTimeout(typingTmrs[data.editor_id]);
  typingTmrs[data.editor_id] = setTimeout(()=>setTyping(data.editor_id,false), 2500);
}

// ════════════════════════════════════════════════════════
//  FORMAT TOOLBAR
// ════════════════════════════════════════════════════════
document.querySelectorAll('.tb[data-cmd]').forEach(b => {
  b.addEventListener('click', () => {
    document.execCommand(b.dataset.cmd, false, null);
    editor.focus(); updateFmt(); broadcastNow();
  });
});

$('blockSel')?.addEventListener('change', e => {
  document.execCommand('formatBlock',false,'<'+e.target.value+'>');
  editor.focus(); broadcastNow();
});
$('fontSel')?.addEventListener('change', e => {
  document.execCommand('fontName',false,e.target.value);
  editor.focus(); broadcastNow();
});

const fsVal = $('fsVal');
function applyFS(pt) {
  if(fsVal) fsVal.value=pt;
  document.execCommand('fontSize',false,'7');
  document.querySelectorAll('font[size="7"]').forEach(el=>{el.removeAttribute('size');el.style.fontSize=pt+'pt';});
  editor.focus(); broadcastNow();
}
$('fsDown')?.addEventListener('click',()=>applyFS(Math.max(6,parseInt(fsVal?.value||11)-1)));
$('fsUp')?.addEventListener('click',  ()=>applyFS(Math.min(400,parseInt(fsVal?.value||11)+1)));
fsVal?.addEventListener('change',()=>applyFS(parseInt(fsVal.value)||11));

const tcp=$('tcPick'), hlp=$('hlPick');
$('btnTC')?.addEventListener('click',()=>tcp?.click());
tcp?.addEventListener('input', e=>{document.execCommand('foreColor',false,e.target.value);$('tcBar').style.background=e.target.value;broadcastNow();});
$('btnHL')?.addEventListener('click',()=>hlp?.click());
hlp?.addEventListener('input', e=>{document.execCommand('backColor',false,e.target.value);$('hlBar').style.background=e.target.value;broadcastNow();});

$('zoomSel')?.addEventListener('change', e=>{
  const s=parseInt(e.target.value)/100;
  document.querySelectorAll('.page-sheet').forEach(p=>p.style.transform=`scale(${s})`);
});

function updateFmt() {
  ['bold','italic','underline','strikeThrough'].forEach(c=>{
    const b=document.querySelector(`.tb[data-cmd="${c}"]`);
    if(b)b.classList.toggle('active',document.queryCommandState(c));
  });
}
editor.addEventListener('keyup',updateFmt);
editor.addEventListener('mouseup',updateFmt);

document.addEventListener('keydown',e=>{
  if((e.ctrlKey||e.metaKey)&&e.key==='s'){e.preventDefault();clearTimeout(saveTmr);saveDoc();}
  if((e.ctrlKey||e.metaKey)&&e.key==='p'){e.preventDefault();window.print();}
});

// ════════════════════════════════════════════════════════
//  SHORTCUT BAR
// ════════════════════════════════════════════════════════
function updateScBar(){
  const b=$('scBar');if(b)b.classList.toggle('hidden',editor.innerText.trim()!=='');
}
setTimeout(updateScBar,100);

const TPLS={
  catatan:`<h2>📋 Catatan Rapat</h2>
<p><strong>Tanggal:</strong> ${new Date().toLocaleDateString('id-ID',{weekday:'long',year:'numeric',month:'long',day:'numeric'})}</p>
<p><strong>Peserta:</strong> </p>
<p><strong>Agenda:</strong></p><ul><li></li></ul>
<p><strong>Catatan:</strong></p><p><br></p>
<p><strong>Tindak lanjut:</strong></p><ul><li></li></ul>`,
  email:`<p>Kepada: </p><p>Perihal: </p><p><br></p>
<p>Yth. [Nama],</p><p><br></p><p>Dengan hormat,</p><p><br></p>
<p>[Isi pesan]</p><p><br></p><p>Terima kasih.</p>
<p><br></p><p>Salam,<br>[Nama kamu]</p>`,
};
function insertTpl(t){
  if(!TPLS[t])return;
  editor.innerHTML=TPLS[t];updateScBar();editor.focus();
  broadcastNow();clearTimeout(saveTmr);saveTmr=setTimeout(saveDoc,2000);
}

// ════════════════════════════════════════════════════════
//  RULER
// ════════════════════════════════════════════════════════
function drawRuler(){
  const trk=$('ruler-track'),cv=$('ruler-canvas');if(!trk||!cv)return;
  const W=trk.offsetWidth;cv.width=W;cv.height=22;
  const ctx=cv.getContext('2d');
  ctx.clearRect(0,0,W,22);
  ctx.fillStyle='#f3f4f8';ctx.fillRect(0,0,W,22);
  ctx.strokeStyle='#c4c4d4';ctx.lineWidth=1;
  ctx.font='9px sans-serif';ctx.fillStyle='#9ca3af';ctx.textAlign='center';
  const pcm=37.8;
  for(let i=0;i*pcm<=W;i++){
    const x=Math.round(i*pcm)+.5;
    ctx.beginPath();ctx.moveTo(x,i%2===0?8:13);ctx.lineTo(x,22);ctx.stroke();
    if(i%2===0&&i>0)ctx.fillText(i,x,7);
  }
}
window.addEventListener('resize',drawRuler);
setTimeout(drawRuler,200);

// ════════════════════════════════════════════════════════
//  MENU DROPDOWN
// ════════════════════════════════════════════════════════
function toggleMenu(el){
  const open=el.classList.contains('open');
  document.querySelectorAll('.mi.open').forEach(m=>{m.classList.remove('open');m.querySelector('.dropdown')?.classList.remove('show');});
  if(!open){el.classList.add('open');el.querySelector('.dropdown')?.classList.add('show');}
}
document.addEventListener('click',e=>{
  if(!e.target.closest('.mi'))
    document.querySelectorAll('.mi.open').forEach(m=>{m.classList.remove('open');m.querySelector('.dropdown')?.classList.remove('show');});
});

// ════════════════════════════════════════════════════════
//  CARET SAVE / RESTORE
// ════════════════════════════════════════════════════════
function saveCaret(ctx){
  const s=window.getSelection();if(!s||!s.rangeCount)return 0;
  const r=s.getRangeAt(0).cloneRange();r.selectNodeContents(ctx);
  r.setEnd(s.getRangeAt(0).endContainer,s.getRangeAt(0).endOffset);
  return r.toString().length;
}
function restoreCaret(ctx,pos){
  if(!pos&&pos!==0)return;
  try{
    const r=document.createRange(),s=window.getSelection();
    let p=0,done=false;
    function w(n){
      if(done)return;
      if(n.nodeType===3){
        if(p+n.length>=pos){r.setStart(n,pos-p);r.collapse(true);s.removeAllRanges();s.addRange(r);done=true;return;}
        p+=n.length;
      }else for(const c of n.childNodes)w(c);
    }
    w(ctx);
    if(!done){r.selectNodeContents(ctx);r.collapse(false);s.removeAllRanges();s.addRange(r);}
  }catch(_){}
}

// ════════════════════════════════════════════════════════
//  REMOTE CURSORS
// ════════════════════════════════════════════════════════
function getCoords(edEl,offset){
  try{
    let p=0,found=false;const r=document.createRange();
    function w(n){if(found)return;if(n.nodeType===3){if(p+n.length>=offset){r.setStart(n,offset-p);r.collapse(true);found=true;return;}p+=n.length;}else for(const c of n.childNodes)w(c);}
    w(edEl);if(!found){r.selectNodeContents(edEl);r.collapse(false);}
    const rect=r.getBoundingClientRect(),er=edEl.getBoundingClientRect();
    return{x:rect.left-er.left,y:rect.top-er.top,h:rect.height||18};
  }catch{return null;}
}
function renderCursor(id,name,color,offset){
  const edEl=$('editor'),page=document.querySelector('.page-sheet');
  if(!page||!edEl)return;
  if(!remoteCursors[id]){
    const wrap=document.createElement('div');wrap.className='rc-wrap';
    const caret=document.createElement('div');caret.className='rc-caret';caret.style.background=color;
    const label=document.createElement('div');label.className='rc-label';label.style.background=color;label.textContent=name;
    wrap.appendChild(caret);wrap.appendChild(label);page.appendChild(wrap);
    remoteCursors[id]={el:wrap};
  }
  const wrap=remoteCursors[id].el,coords=getCoords(edEl,offset);if(!coords)return;
  const er=edEl.getBoundingClientRect(),pr=page.getBoundingClientRect();
  wrap.style.cssText=`left:${er.left-pr.left+coords.x}px;top:${er.top-pr.top+coords.y}px;display:block;position:absolute;pointer-events:none;z-index:50;`;
  wrap.querySelector('.rc-caret').style.height=coords.h+'px';
  clearTimeout(cursorTmrs[id]);
  cursorTmrs[id]=setTimeout(()=>{if(wrap)wrap.style.display='none';},4000);
}

let curBTmr=null;
function broadcastCursor(typing){
  if(!myId)return;
  const offset=saveCaret($('editor'))||0;
  clearTimeout(curBTmr);
  curBTmr=setTimeout(()=>{
    fetch(URL_CURSOR,{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},
      body:JSON.stringify({editor_id:myId,editor_name:myName,color:myColor,offset,is_typing:typing}),
    }).catch(()=>{});
  },80);
}
editor.addEventListener('keyup',  ()=>broadcastCursor(true));
editor.addEventListener('mouseup',()=>broadcastCursor(false));
editor.addEventListener('click',  ()=>broadcastCursor(false));

// ════════════════════════════════════════════════════════
//  WEBSOCKET — LARAVEL REVERB
// ════════════════════════════════════════════════════════
function loadEcho(){
  const s1=document.createElement('script');
  s1.src='https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.15.3/echo.iife.js';
  s1.onload=()=>{
    const s2=document.createElement('script');
    s2.src='https://cdnjs.cloudflare.com/ajax/libs/pusher/8.4.0-rc2/pusher.min.js';
    s2.onload=connectReverb;
    document.head.appendChild(s2);
  };
  document.head.appendChild(s1);
}

function connectReverb(){
  try{
    window.Echo=new window.LaravelEcho({
      broadcaster:'reverb',key:REVERB_KEY,
      wsHost:REVERB_HOST,wsPort:REVERB_PORT,wssPort:REVERB_PORT,
      forceTLS:false,enabledTransports:['ws'],disableStats:true,
    });
    const ch=window.Echo.channel(`document.${DOC_ID}`);

    // Terima perubahan konten
    ch.listen('.document.updated', data => applyRemote(data));

    // Terima presence (join/leave/ping)
    ch.listen('.user.presence', data => {
      if(data.user_id===myId)return;
      if(data.action==='join'||data.action==='ping'){
        const isNew=!users[data.user_id];
        users[data.user_id]={
          name:data.user_name,
          color:data.color||nextColor(),
          isTyping:users[data.user_id]?.isTyping||false,
        };
        if(isNew){
          logAct('join',data.user_name,users[data.user_id].color,'bergabung');
          snack(`👋 ${data.user_name} bergabung ke dokumen`);
        }
        renderAll();
        // Auto-remove kalau 20 detik tidak ada ping
        clearTimeout(_offTimers[data.user_id]);
        _offTimers[data.user_id]=setTimeout(()=>{
          if(users[data.user_id]){
            logAct('leave',users[data.user_id].name,users[data.user_id].color,'pergi');
            delete users[data.user_id];renderAll();
          }
        },20000);
      } else if(data.action==='leave'){
        if(users[data.user_id]){
          logAct('leave',users[data.user_id].name,users[data.user_id].color,'meninggalkan dokumen');
          snack(`👋 ${users[data.user_id].name} keluar`);
          delete users[data.user_id];renderAll();
        }
        clearTimeout(_offTimers[data.user_id]);
      }
    });

    // Terima kursor
    ch.listen('.cursor.moved', data => {
      if(data.editor_id===myId)return;
      if(!users[data.editor_id]){
        const c=data.color||nextColor();
        users[data.editor_id]={name:data.editor_name,color:c,isTyping:false};
        logAct('join',data.editor_name,c,'bergabung');renderAll();
      }
      renderCursor(data.editor_id,data.editor_name,data.color,data.offset);
      setTyping(data.editor_id,data.is_typing);
      clearTimeout(typingTmrs[data.editor_id]);
      typingTmrs[data.editor_id]=setTimeout(()=>setTyping(data.editor_id,false),3000);
    });

    snack('⚡ Terhubung — edit real-time aktif!');

  }catch(e){
    console.warn('Reverb error:',e);
    snack('⚠️ WebSocket gagal — mode offline');
  }
}
</script>
</body>
</html>
