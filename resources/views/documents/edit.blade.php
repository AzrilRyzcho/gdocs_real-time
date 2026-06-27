<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ $document->title }} - GDocs</title>
<style>
*{margin:0;padding:0;box-sizing:border-box;}
:root{
  --blue:#1a73e8;--dark:#202124;--grey:#5f6368;
  --border:#e0e0e0;--hover:#f1f3f4;--menubar:#f8f9fa;
}
html,body{height:100%;overflow:hidden;font-family:'Arial',sans-serif;font-size:13px;color:var(--dark);}

/* ── TOP BAR (logo + title + menu) ── */
#topbar{
  background:#fff;
  border-bottom:1px solid var(--border);
  padding:6px 16px 0;
  flex-shrink:0;
}
#toprow{display:flex;align-items:center;gap:10px;margin-bottom:2px;}
.docs-logo{
  width:40px;height:40px;flex-shrink:0;
  background:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 48 48'%3E%3Cpath fill='%234285f4' d='M30 2H10C7.8 2 6 3.8 6 6v36c0 2.2 1.8 4 4 4h28c2.2 0 4-1.8 4-4V14L30 2z'/%3E%3Cpath fill='%23fff' opacity='.3' d='M30 2l12 12H30z'/%3E%3Cpath fill='%23fff' d='M30 14h12L30 2z'/%3E%3Crect x='13' y='22' width='22' height='2' rx='1' fill='%23fff'/%3E%3Crect x='13' y='27' width='22' height='2' rx='1' fill='%23fff'/%3E%3Crect x='13' y='32' width='16' height='2' rx='1' fill='%23fff'/%3E%3C/svg%3E") center/contain no-repeat;
}
#titleInput{
  border:none;outline:none;font-size:18px;font-weight:400;
  color:var(--dark);padding:4px 6px;border-radius:4px;
  min-width:200px;max-width:500px;flex:1;
  transition:background .15s;
}
#titleInput:hover{background:var(--hover);}
#titleInput:focus{background:#e8f0fe;box-shadow:0 0 0 2px var(--blue) inset;}

.star-btn,
.history-btn{background:none;border:none;cursor:pointer;padding:4px;border-radius:50%;font-size:16px;color:var(--grey);transition:background .15s;}
.star-btn:hover,.history-btn:hover{background:var(--hover);}

.save-indicator{font-size:12px;color:var(--grey);display:flex;align-items:center;gap:5px;white-space:nowrap;}
.save-indicator svg{width:14px;height:14px;}

.top-right{display:flex;align-items:center;gap:8px;margin-left:auto;}
.users-bar{display:flex;align-items:center;gap:-4px;}
.uavatar{
  width:32px;height:32px;border-radius:50%;
  display:flex;align-items:center;justify-content:center;
  font-size:12px;font-weight:700;color:#fff;
  border:2px solid #fff;cursor:default;
  box-shadow:0 1px 3px rgba(0,0,0,.2);
  margin-left:-6px;position:relative;transition:transform .2s;
}
.uavatar:first-child{margin-left:0;}
.uavatar:hover{transform:scale(1.15);z-index:5;}
.uavatar.typing::after{content:'✏';position:absolute;bottom:-2px;right:-2px;font-size:9px;background:#fff;border-radius:50%;padding:1px;}

.btn-share{
  background:var(--blue);color:#fff;border:none;
  padding:7px 16px;border-radius:4px;font-size:13px;font-weight:500;
  cursor:pointer;display:flex;align-items:center;gap:6px;
  transition:background .15s;white-space:nowrap;
}
.btn-share:hover{background:#1557b0;}

/* ── MENU BAR ── */
#menubar{display:flex;align-items:center;gap:0;padding:0 2px 4px;margin-left:48px;}
.menu-item{
  padding:4px 10px;border-radius:4px;cursor:pointer;
  font-size:13px;color:var(--dark);transition:background .1s;
  position:relative;user-select:none;
}
.menu-item:hover{background:var(--hover);}
.menu-item.open{background:var(--hover);}

/* dropdown */
.dropdown{
  position:absolute;top:calc(100% + 2px);left:0;
  background:#fff;border:1px solid var(--border);
  border-radius:4px;box-shadow:0 4px 16px rgba(0,0,0,.15);
  min-width:220px;z-index:1000;display:none;
  padding:4px 0;
}
.dropdown.show{display:block;}
.dd-item{
  padding:6px 20px;font-size:13px;cursor:pointer;
  display:flex;align-items:center;justify-content:space-between;
  color:var(--dark);transition:background .1s;
}
.dd-item:hover{background:var(--hover);}
.dd-item .shortcut{color:var(--grey);font-size:11px;margin-left:24px;}
.dd-sep{border:none;border-top:1px solid var(--border);margin:4px 0;}

/* ── TOOLBAR ── */
#toolbar{
  background:#fff;border-bottom:1px solid #c7c7c7;
  padding:4px 8px;display:flex;align-items:center;
  gap:2px;flex-shrink:0;flex-wrap:wrap;
}
.tb-btn{
  background:none;border:none;cursor:pointer;
  padding:4px 6px;border-radius:3px;
  font-size:13px;color:var(--dark);
  display:flex;align-items:center;justify-content:center;
  min-width:26px;height:26px;
  transition:background .1s;position:relative;
}
.tb-btn:hover{background:var(--hover);}
.tb-btn.active{background:#c2d4fd;}
.tb-sep{width:1px;height:20px;background:#c7c7c7;margin:0 4px;flex-shrink:0;}
.tb-select{
  border:none;background:none;font-size:13px;color:var(--dark);
  cursor:pointer;outline:none;padding:3px 2px;border-radius:3px;
  max-width:120px;
}
.tb-select:hover{background:var(--hover);}
.tb-select-wrap{
  border:1px solid transparent;border-radius:3px;
  display:flex;align-items:center;padding:0 2px;
  transition:border-color .1s;
}
.tb-select-wrap:hover{border-color:var(--border);}
.font-size-wrap{
  display:flex;align-items:center;gap:1px;
  border:1px solid transparent;border-radius:3px;
  padding:0 2px;transition:border-color .1s;
}
.font-size-wrap:hover{border-color:var(--border);}
.font-size-input{
  width:28px;text-align:center;border:none;outline:none;
  font-size:13px;background:transparent;color:var(--dark);
}
.font-size-btn{background:none;border:none;cursor:pointer;padding:2px;font-size:11px;color:var(--grey);border-radius:2px;}
.font-size-btn:hover{background:var(--hover);}
.color-btn-wrap{position:relative;}
.color-underline{
  position:absolute;bottom:2px;left:4px;right:4px;
  height:3px;background:#000;border-radius:1px;pointer-events:none;
}

/* tooltip */
.tb-btn[title]:hover::after,.tb-select-wrap[title]:hover::after{
  content:attr(title);position:absolute;
  bottom:calc(100% + 6px);left:50%;transform:translateX(-50%);
  background:rgba(32,33,36,.85);color:#fff;
  font-size:11px;padding:4px 8px;border-radius:4px;
  white-space:nowrap;pointer-events:none;z-index:100;
}

/* ── RULER ── */
#ruler-wrap{
  background:#f8f9fa;border-bottom:1px solid #c7c7c7;
  height:24px;flex-shrink:0;display:flex;
  overflow:hidden;position:relative;
}
#ruler-left-margin{width:238px;flex-shrink:0;background:#f8f9fa;border-right:2px solid #4285f4;position:relative;}
#ruler-left-margin::after{content:'';position:absolute;right:-6px;top:50%;transform:translateY(-50%);
  width:0;height:0;border:6px solid transparent;border-right-color:#4285f4;}
#ruler-track{flex:1;overflow:hidden;position:relative;}
#ruler-canvas{height:24px;display:block;}
#ruler-right-margin{width:44px;flex-shrink:0;background:#f8f9fa;border-left:2px solid #4285f4;position:relative;}
#ruler-right-margin::before{content:'';position:absolute;left:-6px;top:50%;transform:translateY(-50%);
  width:0;height:0;border:6px solid transparent;border-left-color:#4285f4;}

/* ── MAIN ── */
#main{display:flex;flex:1;overflow:hidden;}

/* ── LEFT: page number ruler ── */
#page-ruler{
  width:40px;background:#f8f9fa;flex-shrink:0;
  overflow:hidden;position:relative;
  border-right:none;
}
.page-num{
  position:absolute;right:6px;font-size:10px;
  color:#bdc1c6;user-select:none;line-height:1;
}

/* ── CENTER: editor area ── */
#editor-area{
  flex:1;overflow-y:auto;overflow-x:auto;
  background:#f8f9fa;padding:16px 0 80px;
  display:flex;flex-direction:column;align-items:center;
  gap:0;
}
.page-sheet{
  background:#fff;width:816px;min-height:1056px;
  box-shadow:0 1px 3px rgba(0,0,0,.2),0 0 0 1px rgba(0,0,0,.05);
  margin-bottom:16px;position:relative;flex-shrink:0;
}
.page-content{
  padding:96px 96px 96px 96px;
  outline:none;min-height:864px;
  font-family:Arial,sans-serif;font-size:11pt;
  line-height:1.5;color:#000;
  word-break:break-word;white-space:pre-wrap;
  caret-color:#000;
}
.page-content:empty::before{
  content:attr(data-placeholder);
  color:#bdc1c6;pointer-events:none;
}
.page-content:focus{outline:none;}

/* ── RIGHT: sidebar ── */
#sidebar{
  width:260px;background:#fff;border-left:1px solid var(--border);
  display:flex;flex-direction:column;flex-shrink:0;
  overflow:hidden;
}
.sidebar-head{
  padding:12px 16px;border-bottom:1px solid var(--border);
  font-size:13px;font-weight:600;display:flex;
  align-items:center;gap:8px;
}
.online-pill{
  background:#34a853;color:#fff;font-size:11px;
  padding:1px 8px;border-radius:10px;font-weight:600;
}
.user-list-wrap{flex:1;overflow-y:auto;padding:4px 0;}
.u-item{display:flex;align-items:center;gap:10px;padding:8px 16px;transition:background .1s;}
.u-item:hover{background:#f8f9fa;}
.u-item-av{
  width:36px;height:36px;border-radius:50%;
  display:flex;align-items:center;justify-content:center;
  font-size:13px;font-weight:700;color:#fff;flex-shrink:0;position:relative;
}
.u-item-av .dot{
  position:absolute;bottom:0;right:0;
  width:10px;height:10px;border-radius:50%;
  background:#34a853;border:2px solid #fff;
}
.u-item-info{flex:1;min-width:0;}
.u-item-name{font-size:13px;font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.u-item-name.you::after{content:' (kamu)';color:var(--grey);font-weight:400;font-size:11px;}
.u-item-status{font-size:11px;color:var(--grey);margin-top:1px;}
.u-item-status.typing{color:var(--blue);}
.activity-section{border-top:1px solid var(--border);max-height:200px;display:flex;flex-direction:column;}
.activity-head{padding:8px 16px 4px;font-size:11px;font-weight:600;color:var(--grey);text-transform:uppercase;letter-spacing:.05em;flex-shrink:0;}
.activity-log{overflow-y:auto;flex:1;padding:0 0 8px;}
.act-item{padding:4px 16px;font-size:12px;color:var(--grey);display:flex;justify-content:space-between;gap:4px;border-left:3px solid transparent;}
.act-item.join{border-color:#34a853;}.act-item.leave{border-color:#ea4335;}.act-item.edit{border-color:var(--blue);}
.act-time{font-size:10px;color:#bdc1c6;white-space:nowrap;flex-shrink:0;}

/* ── SNACKBAR ── */
#snackbar{
  position:fixed;bottom:24px;left:50%;
  transform:translateX(-50%) translateY(80px);
  background:#323232;color:#fff;padding:10px 20px;
  border-radius:4px;font-size:13px;transition:transform .25s;
  z-index:9999;pointer-events:none;
}
#snackbar.show{transform:translateX(-50%) translateY(0);}

/* ── NAME MODAL ── */
.modal-bg{position:fixed;inset:0;background:rgba(0,0,0,.45);display:flex;align-items:center;justify-content:center;z-index:1000;}
.modal-box{background:#fff;border-radius:8px;padding:32px 28px;width:360px;box-shadow:0 8px 32px rgba(0,0,0,.25);text-align:center;}
.modal-box h2{font-size:20px;margin-bottom:8px;}
.modal-box p{font-size:13px;color:var(--grey);margin-bottom:20px;}
.modal-box input{width:100%;padding:10px 14px;border:1px solid var(--border);border-radius:4px;font-size:14px;outline:none;margin-bottom:16px;}
.modal-box input:focus{border-color:var(--blue);}
.modal-box button{width:100%;background:var(--blue);color:#fff;border:none;padding:11px;border-radius:4px;font-size:14px;font-weight:500;cursor:pointer;}
.modal-box button:hover{background:#1557b0;}

::-webkit-scrollbar{width:6px;height:6px;}
::-webkit-scrollbar-thumb{background:#c0c0c0;border-radius:3px;}
</style>
</head>
<body style="display:flex;flex-direction:column;height:100vh;">

{{-- ── NAME MODAL ── --}}
<div class="modal-bg" id="nameModal">
  <div class="modal-box">
    <h2>👋 Siapa nama kamu?</h2>
    <p>Nama akan terlihat oleh semua orang yang membuka dokumen ini.</p>
    <input type="text" id="nameInput" placeholder="Masukkan nama kamu..." maxlength="30" autocomplete="off">
    <button id="nameSubmit">Mulai Mengedit →</button>
  </div>
</div>

{{-- ── TOP BAR ── --}}
<div id="topbar">
  <div id="toprow">
    <div class="docs-logo"></div>
    <input type="text" id="titleInput" value="{{ $document->title }}" maxlength="200" spellcheck="false">
    <button class="star-btn" title="Beri bintang">☆</button>
    <button class="history-btn" title="Pindahkan ke Drive">📁</button>
    <span class="save-indicator" id="saveIndicator">
      <svg viewBox="0 0 24 24" fill="none" stroke="#5f6368" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>
      <span id="saveText">Tersimpan di Drive</span>
    </span>
    <div class="top-right">
      <div class="users-bar" id="usersBar"></div>
      <button class="btn-share">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M18 16c-.8 0-1.5.3-2 .8l-7.1-4.1c0-.2.1-.5.1-.7s0-.5-.1-.7L16 7.2c.5.6 1.2.8 2 .8 1.7 0 3-1.3 3-3s-1.3-3-3-3-3 1.3-3 3c0 .2 0 .5.1.7L8 9.8C7.5 9.3 6.8 9 6 9c-1.7 0-3 1.3-3 3s1.3 3 3 3c.8 0 1.5-.3 2-.8l7.1 4.1c0 .2-.1.4-.1.7 0 1.6 1.3 2.9 2.9 2.9s2.9-1.3 2.9-2.9S19.6 16 18 16z"/></svg>
        Bagikan
      </button>
    </div>
  </div>

  {{-- Menu bar --}}
  <div id="menubar">
    @foreach([
      'File'     => [['Baru','Ctrl+N'],['Buka','Ctrl+O'],null,['Unduh']],
      'Edit'     => [['Undo','Ctrl+Z'],['Redo','Ctrl+Y'],null,['Cut','Ctrl+X'],['Salin','Ctrl+C'],['Tempel','Ctrl+V'],null,['Pilih Semua','Ctrl+A']],
      'Tampilan' => [['Mode Cetak'],['Tampilkan Penggaris'],null,['Kompak']],
      'Sisipkan' => [['Gambar'],['Tabel'],['Link','Ctrl+K'],null,['Komentar','Ctrl+Alt+M']],
      'Format'   => [['Teks'],['Paragraf'],['Spasi Baris & Paragraf'],null,['Poin & Penomoran'],['Kelas'],null,['Hapus Pemformatan','Ctrl+\\']],
      'Alat'     => [['Periksa ejaan'],['Hitung kata'],null,['Preferensi']],
      'Ekstensi' => [['Add-on'],['Makro']],
      'Bantuan'  => [['Bantuan Dokumen'],['Pintasan keyboard','Ctrl+/']],
    ] as $label => $items)
      <div class="menu-item" data-menu="{{ $label }}" onclick="toggleMenu(this)">
        {{ $label }}
        <div class="dropdown" id="menu-{{ $label }}">
          @foreach($items as $item)
            @if($item === null)
              <hr class="dd-sep">
            @else
              <div class="dd-item" onclick="event.stopPropagation()">
                <span>{{ $item[0] }}</span>
                @if(isset($item[1]))<span class="shortcut">{{ $item[1] }}</span>@endif
              </div>
            @endif
          @endforeach
        </div>
      </div>
    @endforeach
  </div>
</div>

{{-- ── TOOLBAR ── --}}
<div id="toolbar">
  {{-- Undo / Redo / Print / Paint --}}
  <button class="tb-btn" onclick="document.execCommand('undo')" title="Undo (Ctrl+Z)">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 7h10a5 5 0 0 1 0 10H9"/><path d="M3 7l4-4M3 7l4 4"/></svg>
  </button>
  <button class="tb-btn" onclick="document.execCommand('redo')" title="Redo (Ctrl+Y)">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 7H11a5 5 0 0 0 0 10h4"/><path d="M21 7l-4-4M21 7l-4 4"/></svg>
  </button>
  <button class="tb-btn" title="Cetak (Ctrl+P)" onclick="window.print()">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="6" y="15" width="12" height="7" rx="1"/><path d="M6 9H4a1 1 0 0 0-1 1v6h4M18 9h2a1 1 0 0 1 1 1v6h-4"/><path d="M6 9V4h12v5"/><circle cx="18" cy="13" r="1" fill="currentColor"/></svg>
  </button>
  <button class="tb-btn" id="btnPaint" title="Format Paint">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 4v7a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V4"/><path d="M14 17v3H10v-3"/><rect x="2" y="2" width="20" height="4" rx="1"/></svg>
  </button>
  <div class="tb-sep"></div>

  {{-- Zoom --}}
  <div class="tb-select-wrap" title="Zoom">
    <select class="tb-select" id="zoomSelect" style="width:60px">
      <option>50%</option><option>75%</option><option selected>100%</option>
      <option>125%</option><option>150%</option><option>200%</option>
    </select>
  </div>
  <div class="tb-sep"></div>

  {{-- Paragraph style --}}
  <div class="tb-select-wrap" title="Gaya paragraf">
    <select class="tb-select" id="blockSelect" style="width:110px">
      <option value="p">Teks normal</option>
      <option value="h1">Judul</option>
      <option value="h2">Judul 2</option>
      <option value="h3">Judul 3</option>
      <option value="h4">Judul 4</option>
    </select>
  </div>
  <div class="tb-sep"></div>

  {{-- Font family --}}
  <div class="tb-select-wrap" title="Font">
    <select class="tb-select" id="fontSelect" style="width:90px">
      <option>Arial</option><option>Times New Roman</option>
      <option>Calibri</option><option>Georgia</option>
      <option>Verdana</option><option>Courier New</option>
      <option>Trebuchet MS</option>
    </select>
  </div>
  <div class="tb-sep"></div>

  {{-- Font size --}}
  <div class="font-size-wrap" title="Ukuran font">
    <button class="font-size-btn" id="btnFsDown" title="Perkecil">−</button>
    <input class="font-size-input" type="text" id="fontSize" value="11">
    <button class="font-size-btn" id="btnFsUp" title="Perbesar">+</button>
  </div>
  <div class="tb-sep"></div>

  {{-- Bold / Italic / Underline / Strikethrough --}}
  <button class="tb-btn" data-cmd="bold"          title="Tebal (Ctrl+B)"><b style="font-size:14px">B</b></button>
  <button class="tb-btn" data-cmd="italic"        title="Miring (Ctrl+I)"><i style="font-size:14px">I</i></button>
  <button class="tb-btn" data-cmd="underline"     title="Garis bawah (Ctrl+U)"><u style="font-size:13px">U</u></button>
  <button class="tb-btn" data-cmd="strikeThrough" title="Coret"><s style="font-size:13px">S</s></button>
  <div class="tb-sep"></div>

  {{-- Text color --}}
  <div class="color-btn-wrap">
    <button class="tb-btn" id="btnTextColor" title="Warna teks" style="position:relative;padding-bottom:7px">
      <span style="font-size:14px;font-weight:700">A</span>
      <div class="color-underline" id="textColorBar" style="background:#000"></div>
    </button>
    <input type="color" id="textColorPicker" style="opacity:0;position:absolute;width:0;height:0" value="#000000">
  </div>

  {{-- Highlight --}}
  <div class="color-btn-wrap">
    <button class="tb-btn" id="btnHighlight" title="Sorot teks" style="position:relative;padding-bottom:7px">
      <span style="font-size:14px">✏</span>
      <div class="color-underline" id="highlightBar" style="background:#ffff00"></div>
    </button>
    <input type="color" id="highlightPicker" style="opacity:0;position:absolute;width:0;height:0" value="#ffff00">
  </div>

  <div class="tb-sep"></div>

  {{-- Link --}}
  <button class="tb-btn" data-cmd="createLink" title="Sisipkan link (Ctrl+K)">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
  </button>

  {{-- Image --}}
  <button class="tb-btn" title="Sisipkan gambar">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
  </button>

  <div class="tb-sep"></div>

  {{-- Alignment --}}
  <button class="tb-btn" data-cmd="justifyLeft"   title="Rata kiri (Ctrl+Shift+L)">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="15" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
  </button>
  <button class="tb-btn" data-cmd="justifyCenter" title="Rata tengah (Ctrl+Shift+E)">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="6" y1="12" x2="18" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
  </button>
  <button class="tb-btn" data-cmd="justifyRight"  title="Rata kanan (Ctrl+Shift+R)">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="9" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
  </button>
  <button class="tb-btn" data-cmd="justifyFull"   title="Rata kiri-kanan (Ctrl+Shift+J)">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
  </button>
  <div class="tb-sep"></div>

  {{-- Line spacing --}}
  <button class="tb-btn" id="btnLineSpacing" title="Spasi baris">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M3 12h18M3 18h18"/><path d="M8 3l-3 3 3 3M8 21l-3-3 3-3"/></svg>
  </button>

  {{-- Lists --}}
  <button class="tb-btn" data-cmd="insertUnorderedList" title="Daftar poin">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="9" y1="6" x2="20" y2="6"/><line x1="9" y1="12" x2="20" y2="12"/><line x1="9" y1="18" x2="20" y2="18"/><circle cx="4" cy="6" r="1.5" fill="currentColor" stroke="none"/><circle cx="4" cy="12" r="1.5" fill="currentColor" stroke="none"/><circle cx="4" cy="18" r="1.5" fill="currentColor" stroke="none"/></svg>
  </button>
  <button class="tb-btn" data-cmd="insertOrderedList" title="Daftar bernomor">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="10" y1="6" x2="20" y2="6"/><line x1="10" y1="12" x2="20" y2="12"/><line x1="10" y1="18" x2="20" y2="18"/><text x="2" y="9" font-size="7" fill="currentColor" stroke="none">1</text><text x="2" y="15" font-size="7" fill="currentColor" stroke="none">2</text><text x="2" y="21" font-size="7" fill="currentColor" stroke="none">3</text></svg>
  </button>
  <div class="tb-sep"></div>

  {{-- Indent --}}
  <button class="tb-btn" data-cmd="outdent" title="Kurangi indentasi (Ctrl+[)">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="11" y1="6" x2="21" y2="6"/><line x1="11" y1="12" x2="21" y2="12"/><line x1="11" y1="18" x2="21" y2="18"/><path d="M7 9l-4 3 4 3"/></svg>
  </button>
  <button class="tb-btn" data-cmd="indent" title="Tambah indentasi (Ctrl+])">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="11" y1="6" x2="21" y2="6"/><line x1="11" y1="12" x2="21" y2="12"/><line x1="11" y1="18" x2="21" y2="18"/><path d="M3 9l4 3-4 3"/></svg>
  </button>

  <div class="tb-sep"></div>
  <button class="tb-btn" data-cmd="removeFormat" title="Hapus pemformatan (Ctrl+\)">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 3h12M8 3l4 9M16 3l-4 9"/><line x1="4" y1="21" x2="20" y2="21"/><line x1="10" y1="12" x2="14" y2="21"/></svg>
  </button>
</div>

{{-- ── RULER ── --}}
<div id="ruler-wrap">
  <div id="ruler-left-margin"></div>
  <div id="ruler-track">
    <canvas id="ruler-canvas"></canvas>
  </div>
  <div id="ruler-right-margin"></div>
</div>

{{-- ── MAIN ── --}}
<div id="main">
  {{-- Page number column --}}
  <div id="page-ruler" id="pageRuler"></div>

  {{-- Editor --}}
  <div id="editor-area" id="editorArea">
    <div class="page-sheet">
      <div id="editor" class="page-content" contenteditable="true"
           spellcheck="true"
           data-placeholder="Mulai mengetik di sini...">{!! $document->content !!}</div>
    </div>
  </div>

  {{-- Sidebar --}}
  <div id="sidebar">
    <div class="sidebar-head">
      👥 Sedang Online
      <span class="online-pill" id="onlineBadge">1</span>
    </div>
    <div class="user-list-wrap" id="userList"></div>
    <div class="activity-section">
      <div class="activity-head">📋 Aktivitas</div>
      <div class="activity-log" id="activityLog"></div>
    </div>
  </div>
</div>

<div id="snackbar"></div>

<script>
// ── CONFIG ────────────────────────────────────────────────────────
const DOC_ID     = {{ $document->id }};
const UPDATE_URL = '/documents/{{ $document->id }}';
const CSRF       = document.querySelector('meta[name="csrf-token"]').content;
const REVERB_KEY  = '{{ env("REVERB_APP_KEY") }}';
const REVERB_HOST = window.location.hostname;
const REVERB_PORT = {{ env("REVERB_PORT", 8080) }};

// ── COLORS ────────────────────────────────────────────────────────
const COLORS=['#e74c3c','#3498db','#2ecc71','#f39c12','#9b59b6','#1abc9c','#e67e22','#e91e63'];
let colorIdx=0;
const nextColor=()=>COLORS[colorIdx++%COLORS.length];
const initials=n=>n.trim().split(/\s+/).map(w=>w[0]).join('').toUpperCase().slice(0,2)||'??';
const timeNow=()=>new Date().toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit'});

// ── STATE ─────────────────────────────────────────────────────────
let myId=null,myName=null,myColor=null;
const onlineUsers={};
const typingTimers={};

// ── MODAL ─────────────────────────────────────────────────────────
const modal=document.getElementById('nameModal');
const nameInput=document.getElementById('nameInput');
document.getElementById('nameSubmit').onclick=()=>{if(nameInput.value.trim())startSession(nameInput.value);};
nameInput.addEventListener('keydown',e=>{if(e.key==='Enter'&&nameInput.value.trim())startSession(nameInput.value);});
const sn=localStorage.getItem('gdocs_name');if(sn)nameInput.value=sn;

function startSession(name){
  myName=name.trim()||'Anonim';
  myId='u_'+Math.random().toString(36).slice(2,10);
  myColor=nextColor();
  localStorage.setItem('gdocs_name',myName);
  modal.style.display='none';
  onlineUsers[myId]={name:myName,color:myColor,isTyping:false};
  renderAll();
  addActivity('join',myName,myColor,'bergabung ke dokumen');
  initEcho();
}

// ── RENDER ────────────────────────────────────────────────────────
function renderAll(){renderToolbar();renderSidebar();updateBadge();}

function renderToolbar(){
  const bar=document.getElementById('usersBar');
  bar.innerHTML=Object.entries(onlineUsers).slice(0,6).map(([id,u])=>
    `<div class="uavatar ${u.isTyping?'typing':''}" style="background:${u.color}"
      title="${u.name}${id===myId?' (kamu)':''}${u.isTyping?' — mengetik...':''}">${initials(u.name)}</div>`
  ).join('');
}

function renderSidebar(){
  document.getElementById('userList').innerHTML=Object.entries(onlineUsers).map(([id,u])=>
    `<div class="u-item">
      <div class="u-item-av" style="background:${u.color}">${initials(u.name)}<div class="dot"></div></div>
      <div class="u-item-info">
        <div class="u-item-name ${id===myId?'you':''}">${u.name}</div>
        <div class="u-item-status ${u.isTyping?'typing':''}">${u.isTyping?'✏️ Mengetik...':'● Online'}</div>
      </div>
    </div>`
  ).join('')||'<div style="padding:16px;color:#bdc1c6;font-size:12px">Tidak ada pengguna lain</div>';
}

function updateBadge(){
  const n=Object.keys(onlineUsers).length;
  document.getElementById('onlineBadge').textContent=n;
}

function setTyping(id,v){
  if(!onlineUsers[id])return;
  onlineUsers[id].isTyping=v;renderAll();
}

// ── ACTIVITY ──────────────────────────────────────────────────────
function addActivity(type,name,color,text){
  const log=document.getElementById('activityLog');
  const icon=type==='join'?'🟢':type==='leave'?'🔴':'✏️';
  const d=document.createElement('div');
  d.className=`act-item ${type}`;
  d.innerHTML=`<span>${icon} <b style="color:${color}">${name}</b> ${text}</span><span class="act-time">${timeNow()}</span>`;
  log.prepend(d);while(log.children.length>30)log.removeChild(log.lastChild);
}

// ── EDITOR ────────────────────────────────────────────────────────
const editor=document.getElementById('editor');
const titleInput=document.getElementById('titleInput');
let saveTimer=null,typingTimer=null,isRemoteEdit=false;

function setSave(s){
  const dot=document.getElementById('saveIndicator');
  const txt=document.getElementById('saveText');
  txt.textContent=s==='saving'?'Menyimpan...':s==='error'?'Gagal menyimpan':'Tersimpan di Drive';
}

async function saveDoc(){
  if(!myId)return;setSave('saving');
  try{
    const r=await fetch(UPDATE_URL,{method:'PATCH',
      headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},
      body:JSON.stringify({content:editor.innerHTML,title:titleInput.value,editor_id:myId,editor_name:myName})});
    if(!r.ok)throw 0;setSave('saved');
  }catch{setSave('error');}
}

function scheduleSave(){clearTimeout(saveTimer);saveTimer=setTimeout(saveDoc,1200);}

editor.addEventListener('input',()=>{
  if(isRemoteEdit)return;scheduleSave();
  setTyping(myId,true);clearTimeout(typingTimer);
  typingTimer=setTimeout(()=>setTyping(myId,false),2000);
});
titleInput.addEventListener('input',scheduleSave);

// ── FORMAT COMMANDS ───────────────────────────────────────────────
document.querySelectorAll('.tb-btn[data-cmd]').forEach(b=>{
  b.addEventListener('click',()=>{document.execCommand(b.dataset.cmd,false,null);editor.focus();updateFmtState();});
});

// Paragraph style
document.getElementById('blockSelect').addEventListener('change',e=>{
  document.execCommand('formatBlock',false,'<'+e.target.value+'>');editor.focus();
});

// Font
document.getElementById('fontSelect').addEventListener('change',e=>{
  document.execCommand('fontName',false,e.target.value);editor.focus();
});

// Font size
const fsInput=document.getElementById('fontSize');
fsInput.addEventListener('change',()=>applyFontSize(parseInt(fsInput.value)||11));
document.getElementById('btnFsDown').onclick=()=>applyFontSize(Math.max(6,parseInt(fsInput.value)-1));
document.getElementById('btnFsUp').onclick=()=>applyFontSize(Math.min(400,parseInt(fsInput.value)+1));
function applyFontSize(pt){
  fsInput.value=pt;
  document.execCommand('fontSize',false,'7');
  document.querySelectorAll('font[size="7"]').forEach(el=>{el.removeAttribute('size');el.style.fontSize=pt+'pt';});
  editor.focus();
}

// Color
const textColorPicker=document.getElementById('textColorPicker');
const highlightPicker=document.getElementById('highlightPicker');
document.getElementById('btnTextColor').onclick=()=>textColorPicker.click();
textColorPicker.oninput=e=>{document.execCommand('foreColor',false,e.target.value);document.getElementById('textColorBar').style.background=e.target.value;};
document.getElementById('btnHighlight').onclick=()=>highlightPicker.click();
highlightPicker.oninput=e=>{document.execCommand('backColor',false,e.target.value);document.getElementById('highlightBar').style.background=e.target.value;};

// Zoom
document.getElementById('zoomSelect').onchange=e=>{
  document.querySelectorAll('.page-sheet').forEach(p=>p.style.transform=`scale(${parseInt(e.target.value)/100})`);
};

function updateFmtState(){
  ['bold','italic','underline','strikeThrough'].forEach(c=>{
    const b=document.querySelector(`.tb-btn[data-cmd="${c}"]`);
    if(b)b.classList.toggle('active',document.queryCommandState(c));
  });
}
editor.addEventListener('keyup',updateFmtState);
editor.addEventListener('mouseup',updateFmtState);

// ── RULER ─────────────────────────────────────────────────────────
function drawRuler(){
  const track=document.getElementById('ruler-track');
  const canvas=document.getElementById('ruler-canvas');
  const W=track.offsetWidth; canvas.width=W; canvas.height=24;
  const ctx=canvas.getContext('2d');
  ctx.clearRect(0,0,W,24);
  ctx.fillStyle='#f8f9fa'; ctx.fillRect(0,0,W,24);
  ctx.strokeStyle='#9aa0a6'; ctx.lineWidth=1;
  ctx.font='9px Arial'; ctx.fillStyle='#9aa0a6'; ctx.textAlign='center';
  // 816px page width, 96px left margin — show cm marks
  const pxPerCm=37.8;
  for(let i=0;i<=W/pxPerCm;i++){
    const x=Math.round(i*pxPerCm)+.5;
    if(x>W)break;
    const major=(i%2===0);
    ctx.beginPath(); ctx.moveTo(x,major?10:15); ctx.lineTo(x,24); ctx.stroke();
    if(major&&i>0) ctx.fillText(i,x,9);
  }
}
window.addEventListener('resize',drawRuler);
setTimeout(drawRuler,100);

// ── PAGE NUMBERS ──────────────────────────────────────────────────
function updatePageNumbers(){
  const ruler=document.getElementById('page-ruler');
  ruler.innerHTML='';
  const sheets=document.querySelectorAll('.page-sheet');
  sheets.forEach((s,i)=>{
    const top=s.offsetTop+s.offsetHeight/2;
    const el=document.createElement('div');
    el.className='page-num'; el.style.top=(top)+'px'; el.textContent=i+1;
    ruler.appendChild(el);
  });
}
setTimeout(updatePageNumbers,200);

// ── MENU ──────────────────────────────────────────────────────────
function toggleMenu(el){
  document.querySelectorAll('.menu-item').forEach(m=>{
    if(m!==el){m.classList.remove('open');m.querySelector('.dropdown').classList.remove('show');}
  });
  const dd=el.querySelector('.dropdown');
  el.classList.toggle('open');dd.classList.toggle('show');
}
document.addEventListener('click',e=>{
  if(!e.target.closest('.menu-item')){
    document.querySelectorAll('.menu-item').forEach(m=>{m.classList.remove('open');m.querySelector('.dropdown').classList.remove('show');});
  }
});

// ── SNACKBAR ──────────────────────────────────────────────────────
function showSnack(msg){
  const el=document.getElementById('snackbar');
  el.textContent=msg;el.classList.add('show');
  setTimeout(()=>el.classList.remove('show'),3000);
}

// ── KEYBOARD SHORTCUTS ────────────────────────────────────────────
document.addEventListener('keydown',e=>{
  if((e.ctrlKey||e.metaKey)&&e.key==='s'){e.preventDefault();clearTimeout(saveTimer);saveDoc();}
  if((e.ctrlKey||e.metaKey)&&e.key==='p'){e.preventDefault();window.print();}
});

// ── REVERB / ECHO ─────────────────────────────────────────────────
function initEcho(){
  const s1=document.createElement('script');
  s1.src='https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.15.3/echo.iife.js';
  s1.onload=()=>{
    const s2=document.createElement('script');
    s2.src='https://cdnjs.cloudflare.com/ajax/libs/pusher/8.4.0-rc2/pusher.min.js';
    s2.onload=connectReverb; document.head.appendChild(s2);
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
    window.Echo.channel(`document.${DOC_ID}`).listen('.document.updated',data=>{
      if(data.editor_id===myId)return;
      if(!onlineUsers[data.editor_id]){
        onlineUsers[data.editor_id]={name:data.editor_name,color:nextColor(),isTyping:false};
        addActivity('join',data.editor_name,onlineUsers[data.editor_id].color,'bergabung dan mengedit');
      }
      setTyping(data.editor_id,true);
      clearTimeout(typingTimers[data.editor_id]);
      typingTimers[data.editor_id]=setTimeout(()=>setTyping(data.editor_id,false),3000);
      isRemoteEdit=true;
      const pos=saveCaretPos(editor);
      editor.innerHTML=data.content;
      restoreCaretPos(editor,pos);
      isRemoteEdit=false;
      if(data.title!==titleInput.value){titleInput.value=data.title;document.title=data.title+' - GDocs';}
      setSave('saved');
      addActivity('edit',data.editor_name,onlineUsers[data.editor_id].color,'mengedit dokumen');
      showSnack(`✏️ ${data.editor_name} sedang mengedit...`);
    });
    showSnack('🟢 Terhubung — siap kolaborasi!');
  }catch(e){showSnack('⚠️ Mode offline — perubahan tetap tersimpan');}
}

// ── CARET ─────────────────────────────────────────────────────────
function saveCaretPos(c){
  const s=window.getSelection();if(!s.rangeCount)return null;
  const r=s.getRangeAt(0).cloneRange();r.selectNodeContents(c);r.setEnd(s.getRangeAt(0).endContainer,s.getRangeAt(0).endOffset);return r.toString().length;
}
function restoreCaretPos(c,pos){
  if(pos===null)return;try{
    const r=document.createRange(),s=window.getSelection();let p=0;
    function w(n){if(n.nodeType===3){if(p+n.length>=pos){r.setStart(n,pos-p);r.collapse(true);s.removeAllRanges();s.addRange(r);throw 0;}p+=n.length;}else for(const ch of n.childNodes)w(ch);}
    try{w(c);}catch(e){if(e!==0)console.error(e);}
  }catch{}
}
</script>
</body>
</html>
