<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $document->title }} — GDocs Lite</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --blue:   #4285f4;
            --dark:   #202124;
            --grey:   #5f6368;
            --border: #e0e0e0;
            --green:  #34a853;
            --red:    #ea4335;
            --yellow: #fbbc04;
        }
        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            background: #f8f9fa;
            height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* ── Toolbar ── */
        .toolbar {
            background: #fff;
            border-bottom: 1px solid var(--border);
            padding: 0 16px;
            height: 56px;
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
            box-shadow: 0 1px 3px rgba(0,0,0,.08);
            z-index: 10;
        }
        .back-btn {
            text-decoration: none; color: var(--grey);
            font-size: 20px; padding: 6px; border-radius: 50%;
            transition: background .15s;
        }
        .back-btn:hover { background: #f1f3f4; }
        .doc-logo { font-size: 26px; }
        #titleInput {
            flex: 1; border: none; outline: none;
            font-size: 18px; font-weight: 500; color: var(--dark);
            background: transparent; padding: 6px 8px; border-radius: 4px;
            transition: background .15s; min-width: 0;
        }
        #titleInput:hover { background: #f1f3f4; }
        #titleInput:focus { background: #e8f0fe; }
        .save-status {
            font-size: 13px; color: var(--grey);
            white-space: nowrap; display: flex; align-items: center; gap: 6px;
        }
        .save-dot {
            width: 8px; height: 8px; border-radius: 50%;
            background: var(--green); flex-shrink: 0;
        }
        .save-dot.saving { background: var(--yellow); animation: pulse 1s infinite; }
        .save-dot.error  { background: var(--red); }
        @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.3} }

        /* ── Online users panel ── */
        .users-section {
            display: flex; align-items: center; gap: 8px;
        }
        .users-label {
            font-size: 12px; color: var(--grey); white-space: nowrap;
        }
        .users-bar { display: flex; align-items: center; gap: 4px; }
        .user-avatar {
            width: 34px; height: 34px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; font-weight: 700; color: #fff;
            border: 2px solid #fff; cursor: default;
            box-shadow: 0 1px 4px rgba(0,0,0,.2);
            position: relative; flex-shrink: 0;
            transition: transform .2s;
        }
        .user-avatar:hover { transform: scale(1.15); z-index: 5; }
        .user-avatar.typing::after {
            content: '✏️';
            position: absolute; bottom: -4px; right: -4px;
            font-size: 10px; background: #fff; border-radius: 50%;
            line-height: 1; padding: 1px;
        }
        .user-count {
            font-size: 12px; color: var(--grey);
            background: #f1f3f4; padding: 4px 8px;
            border-radius: 12px; white-space: nowrap;
        }

        /* ── Format bar ── */
        .format-bar {
            background: #fff; border-bottom: 1px solid var(--border);
            padding: 0 16px; height: 40px;
            display: flex; align-items: center; gap: 2px; flex-shrink: 0;
        }
        .fmt-btn {
            background: none; border: none; padding: 5px 8px;
            border-radius: 4px; cursor: pointer; font-size: 14px;
            font-weight: 700; color: var(--grey); transition: background .1s; line-height: 1;
        }
        .fmt-btn:hover  { background: #f1f3f4; color: var(--dark); }
        .fmt-btn.active { background: #e8f0fe; color: var(--blue); }
        .fmt-sep { width: 1px; height: 20px; background: var(--border); margin: 0 4px; }
        select.fmt-select {
            border: 1px solid var(--border); border-radius: 4px;
            padding: 2px 6px; font-size: 13px; background: #fff;
            color: var(--dark); cursor: pointer; outline: none;
        }

        /* ── Activity sidebar ── */
        .main-layout {
            flex: 1; display: flex; overflow: hidden;
        }
        .editor-container {
            flex: 1; overflow-y: auto;
            display: flex; justify-content: center;
            padding: 32px 16px 80px; background: #f8f9fa;
        }
        .page {
            background: #fff; width: 100%; max-width: 816px; min-height: 1056px;
            box-shadow: 0 1px 3px rgba(0,0,0,.12), 0 4px 8px rgba(0,0,0,.04);
            border-radius: 2px; padding: 72px 96px;
        }
        #editor {
            outline: none; font-family: Arial, sans-serif; font-size: 11pt;
            line-height: 1.6; color: #000; min-height: 800px;
            white-space: pre-wrap; word-break: break-word;
        }

        /* ── Activity sidebar ── */
        .activity-sidebar {
            width: 260px; background: #fff;
            border-left: 1px solid var(--border);
            display: flex; flex-direction: column;
            flex-shrink: 0; overflow: hidden;
        }
        .sidebar-header {
            padding: 14px 16px 10px;
            border-bottom: 1px solid var(--border);
            font-size: 13px; font-weight: 600; color: var(--dark);
            display: flex; align-items: center; gap: 8px;
        }
        .online-badge {
            background: var(--green); color: #fff;
            font-size: 11px; padding: 2px 7px;
            border-radius: 10px; font-weight: 600;
        }

        /* ── User list in sidebar ── */
        .user-list { overflow-y: auto; flex: 1; padding: 8px 0; }
        .user-item {
            display: flex; align-items: center; gap: 10px;
            padding: 8px 16px; transition: background .1s;
        }
        .user-item:hover { background: #f8f9fa; }
        .user-item-avatar {
            width: 38px; height: 38px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 14px; font-weight: 700; color: #fff;
            flex-shrink: 0; position: relative;
        }
        .user-item-avatar .status-dot {
            position: absolute; bottom: 0; right: 0;
            width: 10px; height: 10px; border-radius: 50%;
            background: var(--green); border: 2px solid #fff;
        }
        .user-item-info { flex: 1; min-width: 0; }
        .user-item-name {
            font-size: 13px; font-weight: 500; color: var(--dark);
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .user-item-name.you::after {
            content: ' (kamu)'; color: var(--grey); font-weight: 400;
        }
        .user-item-status {
            font-size: 11px; color: var(--grey); margin-top: 1px;
        }
        .user-item-status.typing { color: var(--blue); }

        /* ── Activity log ── */
        .activity-log {
            border-top: 1px solid var(--border);
            max-height: 220px; overflow-y: auto;
            padding: 8px 0;
        }
        .activity-log-header {
            padding: 8px 16px 4px;
            font-size: 11px; font-weight: 600;
            color: var(--grey); text-transform: uppercase; letter-spacing: .05em;
        }
        .activity-item {
            padding: 5px 16px; font-size: 12px; color: var(--grey);
            display: flex; gap: 6px; align-items: flex-start;
            border-left: 3px solid transparent;
        }
        .activity-item.join  { border-color: var(--green); }
        .activity-item.leave { border-color: var(--red); }
        .activity-item.edit  { border-color: var(--blue); }
        .activity-item .act-time { color: #bdc1c6; font-size: 10px; white-space: nowrap; }

        /* ── Snackbar ── */
        #snackbar {
            position: fixed; bottom: 24px; left: 50%;
            transform: translateX(-50%) translateY(80px);
            background: #323232; color: #fff;
            padding: 10px 20px; border-radius: 6px; font-size: 14px;
            transition: transform .3s; z-index: 9999; pointer-events: none;
        }
        #snackbar.show { transform: translateX(-50%) translateY(0); }

        /* ── Name modal ── */
        .modal-overlay {
            position: fixed; inset: 0; background: rgba(0,0,0,.45);
            display: flex; align-items: center; justify-content: center; z-index: 1000;
        }
        .modal-box {
            background: #fff; border-radius: 12px; padding: 32px 28px;
            width: 360px; box-shadow: 0 8px 32px rgba(0,0,0,.2); text-align: center;
        }
        .modal-box h2 { font-size: 20px; margin-bottom: 8px; }
        .modal-box p  { font-size: 14px; color: var(--grey); margin-bottom: 20px; }
        .modal-box input {
            width: 100%; padding: 10px 14px;
            border: 1px solid var(--border); border-radius: 8px;
            font-size: 15px; outline: none; margin-bottom: 16px;
            transition: border-color .2s;
        }
        .modal-box input:focus { border-color: var(--blue); }
        .modal-box button {
            width: 100%; background: var(--blue); color: #fff;
            border: none; padding: 12px; border-radius: 8px;
            font-size: 15px; font-weight: 600; cursor: pointer;
        }
        .modal-box button:hover { background: #1a73e8; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-thumb { background: #c0c0c0; border-radius: 3px; }
    </style>
</head>
<body>

{{-- ── Name modal ── --}}
<div class="modal-overlay" id="nameModal">
    <div class="modal-box">
        <h2>👋 Siapa nama kamu?</h2>
        <p>Nama ini akan terlihat oleh semua orang yang membuka dokumen ini secara real-time.</p>
        <input type="text" id="nameInput" placeholder="Masukkan nama kamu..." maxlength="30" autocomplete="off">
        <button id="nameSubmit">Mulai Mengedit →</button>
    </div>
</div>

{{-- ── Toolbar ── --}}
<div class="toolbar">
    <a href="{{ route('documents.index') }}" class="back-btn" title="Kembali ke daftar dokumen">🏠</a>
    <span class="doc-logo">📄</span>
    <input type="text" id="titleInput" value="{{ $document->title }}" maxlength="200" spellcheck="false">
    <div class="save-status">
        <div class="save-dot" id="saveDot"></div>
        <span id="saveText">Tersimpan</span>
    </div>
    <div class="users-section">
        <span class="users-label">Online:</span>
        <div class="users-bar" id="usersBar"></div>
        <span class="user-count" id="userCount">1 orang</span>
    </div>
</div>

{{-- ── Format bar ── --}}
<div class="format-bar">
    <select class="fmt-select" id="fontSizeSelect" title="Ukuran font">
        @foreach([8,9,10,11,12,14,16,18,20,24,28,32,36,48,72] as $s)
            <option value="{{ $s }}" {{ $s == 11 ? 'selected' : '' }}>{{ $s }}</option>
        @endforeach
    </select>
    <div class="fmt-sep"></div>
    <button class="fmt-btn" data-cmd="bold"          title="Bold (Ctrl+B)"><b>B</b></button>
    <button class="fmt-btn" data-cmd="italic"        title="Italic (Ctrl+I)"><i>I</i></button>
    <button class="fmt-btn" data-cmd="underline"     title="Underline (Ctrl+U)"><u>U</u></button>
    <button class="fmt-btn" data-cmd="strikeThrough" title="Strikethrough"><s>S</s></button>
    <div class="fmt-sep"></div>
    <button class="fmt-btn" data-cmd="justifyLeft"   title="Rata kiri">⬛◻◻</button>
    <button class="fmt-btn" data-cmd="justifyCenter" title="Rata tengah">◻⬛◻</button>
    <button class="fmt-btn" data-cmd="justifyRight"  title="Rata kanan">◻◻⬛</button>
    <button class="fmt-btn" data-cmd="justifyFull"   title="Justify">☰</button>
    <div class="fmt-sep"></div>
    <button class="fmt-btn" data-cmd="insertUnorderedList" title="Bullet list">• ≡</button>
    <button class="fmt-btn" data-cmd="insertOrderedList"   title="Numbered list">1.≡</button>
    <div class="fmt-sep"></div>
    <button class="fmt-btn" data-cmd="undo" title="Undo (Ctrl+Z)">↩</button>
    <button class="fmt-btn" data-cmd="redo" title="Redo (Ctrl+Y)">↪</button>
</div>

{{-- ── Main layout (editor + sidebar) ── --}}
<div class="main-layout">

    {{-- Editor --}}
    <div class="editor-container">
        <div class="page">
            <div id="editor" contenteditable="true" spellcheck="true">{!! $document->content !!}</div>
        </div>
    </div>

    {{-- Sidebar: who's online + activity log --}}
    <div class="activity-sidebar">
        <div class="sidebar-header">
            👥 Sedang Online
            <span class="online-badge" id="onlineBadge">1</span>
        </div>

        <div class="user-list" id="userList">
            {{-- diisi JavaScript --}}
        </div>

        <div class="activity-log">
            <div class="activity-log-header">📋 Aktivitas</div>
            <div id="activityLog">
                {{-- diisi JavaScript --}}
            </div>
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
const COLORS = [
    '#e74c3c','#3498db','#2ecc71','#f39c12',
    '#9b59b6','#1abc9c','#e67e22','#e91e63',
    '#00bcd4','#8bc34a','#ff5722','#607d8b',
];
let colorIdx = 0;
function nextColor() { return COLORS[colorIdx++ % COLORS.length]; }
function initials(name) {
    return name.trim().split(/\s+/).map(w=>w[0]).join('').toUpperCase().slice(0,2) || '??';
}
function timeNow() {
    return new Date().toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit'});
}

// ── STATE ─────────────────────────────────────────────────────────
let myId   = null;
let myName = null;
let myColor = null;

// onlineUsers: { id: { name, color, isTyping } }
const onlineUsers = {};

// ── NAME MODAL ────────────────────────────────────────────────────
const modal      = document.getElementById('nameModal');
const nameInput  = document.getElementById('nameInput');
const nameSubmit = document.getElementById('nameSubmit');

function startSession(name) {
    myName  = name.trim() || 'Anonim';
    myId    = 'u_' + Math.random().toString(36).slice(2,10);
    myColor = nextColor();
    localStorage.setItem('gdocs_name', myName);
    modal.style.display = 'none';
    // Tambahkan diri sendiri
    onlineUsers[myId] = { name: myName, color: myColor, isTyping: false };
    renderAll();
    addActivity('join', myName, myColor, 'Bergabung ke dokumen');
    initEcho();
}

nameSubmit.addEventListener('click', () => { if(nameInput.value.trim()) startSession(nameInput.value); });
nameInput.addEventListener('keydown', e => { if(e.key==='Enter' && nameInput.value.trim()) startSession(nameInput.value); });

const savedName = localStorage.getItem('gdocs_name');
if (savedName) nameInput.value = savedName;

// ── RENDER FUNCTIONS ──────────────────────────────────────────────
function renderAll() {
    renderToolbarAvatars();
    renderSidebarUsers();
    updateCounts();
}

function renderToolbarAvatars() {
    const bar = document.getElementById('usersBar');
    bar.innerHTML = Object.entries(onlineUsers).slice(0,5).map(([id, u]) => `
        <div class="user-avatar ${u.isTyping ? 'typing' : ''}"
             style="background:${u.color}"
             title="${u.name}${id===myId?' (kamu)':''}${u.isTyping?' — mengetik...':''}">
            ${initials(u.name)}
        </div>
    `).join('');
}

function renderSidebarUsers() {
    const list = document.getElementById('userList');
    list.innerHTML = Object.entries(onlineUsers).map(([id, u]) => `
        <div class="user-item">
            <div class="user-item-avatar" style="background:${u.color}">
                ${initials(u.name)}
                <div class="status-dot"></div>
            </div>
            <div class="user-item-info">
                <div class="user-item-name ${id===myId?'you':''}">${u.name}</div>
                <div class="user-item-status ${u.isTyping?'typing':''}">
                    ${u.isTyping ? '✏️ Sedang mengetik...' : '● Online'}
                </div>
            </div>
        </div>
    `).join('') || '<div style="padding:16px;color:#bdc1c6;font-size:13px">Belum ada pengguna lain</div>';
}

function updateCounts() {
    const n = Object.keys(onlineUsers).length;
    document.getElementById('onlineBadge').textContent = n;
    document.getElementById('userCount').textContent   = n + ' orang';
}

// ── ACTIVITY LOG ──────────────────────────────────────────────────
function addActivity(type, name, color, text) {
    const log  = document.getElementById('activityLog');
    const icon = type==='join'?'🟢':type==='leave'?'🔴':'✏️';
    const item = document.createElement('div');
    item.className = `activity-item ${type}`;
    item.innerHTML = `
        <span>${icon} <strong style="color:${color}">${name}</strong> ${text}</span>
        <span class="act-time">${timeNow()}</span>
    `;
    log.prepend(item);
    // Batasi 30 item
    while(log.children.length > 30) log.removeChild(log.lastChild);
}

// ── TYPING INDICATOR ──────────────────────────────────────────────
const typingTimers = {};
function setTyping(id, typing) {
    if (!onlineUsers[id]) return;
    onlineUsers[id].isTyping = typing;
    renderAll();
}

// ── EDITOR ────────────────────────────────────────────────────────
const editor     = document.getElementById('editor');
const titleInput = document.getElementById('titleInput');
const saveDot    = document.getElementById('saveDot');
const saveText   = document.getElementById('saveText');

let saveTimer    = null;
let typingTimer  = null;
let isRemoteEdit = false;

function setSaveState(state) {
    saveDot.className = 'save-dot'+(state==='saving'?' saving':state==='error'?' error':'');
    saveText.textContent = state==='saving'?'Menyimpan...':state==='error'?'Gagal simpan':'Tersimpan';
}

async function saveDocument() {
    if (!myId) return;
    setSaveState('saving');
    try {
        const res = await fetch(UPDATE_URL, {
            method: 'PATCH',
            headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({
                content:     editor.innerHTML,
                title:       titleInput.value,
                editor_id:   myId,
                editor_name: myName,
            }),
        });
        if (!res.ok) throw new Error();
        setSaveState('saved');
    } catch { setSaveState('error'); }
}

function scheduleSave() {
    clearTimeout(saveTimer);
    saveTimer = setTimeout(saveDocument, 1200);
}

editor.addEventListener('input', () => {
    if (isRemoteEdit) return;
    scheduleSave();
    // Set diri sendiri "typing"
    setTyping(myId, true);
    clearTimeout(typingTimer);
    typingTimer = setTimeout(() => setTyping(myId, false), 2000);
});

titleInput.addEventListener('input', scheduleSave);

// ── FORMAT BUTTONS ────────────────────────────────────────────────
document.querySelectorAll('.fmt-btn[data-cmd]').forEach(btn => {
    btn.addEventListener('click', () => {
        document.execCommand(btn.dataset.cmd, false, null);
        editor.focus();
    });
});
document.getElementById('fontSizeSelect').addEventListener('change', e => {
    document.execCommand('fontSize', false, '7');
    document.querySelectorAll('font[size="7"]').forEach(el => {
        el.removeAttribute('size');
        el.style.fontSize = e.target.value + 'pt';
    });
    editor.focus();
});
editor.addEventListener('keyup',   updateFmt);
editor.addEventListener('mouseup', updateFmt);
function updateFmt() {
    ['bold','italic','underline','strikeThrough'].forEach(c => {
        const b = document.querySelector(`.fmt-btn[data-cmd="${c}"]`);
        if (b) b.classList.toggle('active', document.queryCommandState(c));
    });
}

// ── SNACKBAR ──────────────────────────────────────────────────────
function showSnack(msg) {
    const el = document.getElementById('snackbar');
    el.textContent = msg; el.classList.add('show');
    setTimeout(() => el.classList.remove('show'), 3000);
}

// ── LARAVEL ECHO + REVERB ─────────────────────────────────────────
function initEcho() {
    const s1 = document.createElement('script');
    s1.src = 'https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.15.3/echo.iife.js';
    s1.onload = loadPusher;
    document.head.appendChild(s1);
}

function loadPusher() {
    const s2 = document.createElement('script');
    s2.src = 'https://cdnjs.cloudflare.com/ajax/libs/pusher/8.4.0-rc2/pusher.min.js';
    s2.onload = connectReverb;
    document.head.appendChild(s2);
}

function connectReverb() {
    try {
        window.Echo = new window.LaravelEcho({
            broadcaster: 'reverb',
            key:  REVERB_KEY,
            wsHost: REVERB_HOST,
            wsPort: REVERB_PORT,
            wssPort: REVERB_PORT,
            forceTLS: false,
            enabledTransports: ['ws'],
            disableStats: true,
        });

        window.Echo.channel(`document.${DOC_ID}`)
            .listen('.document.updated', (data) => {
                if (data.editor_id === myId) return;

                // Daftarkan user jika belum ada
                if (!onlineUsers[data.editor_id]) {
                    onlineUsers[data.editor_id] = {
                        name:     data.editor_name,
                        color:    nextColor(),
                        isTyping: false,
                    };
                    addActivity('join', data.editor_name, onlineUsers[data.editor_id].color, 'Bergabung dan mengedit');
                }

                // Tandai sedang mengetik
                setTyping(data.editor_id, true);
                clearTimeout(typingTimers[data.editor_id]);
                typingTimers[data.editor_id] = setTimeout(() => {
                    setTyping(data.editor_id, false);
                }, 3000);

                // Update konten
                isRemoteEdit = true;
                const pos = saveCaretPos(editor);
                editor.innerHTML = data.content;
                restoreCaretPos(editor, pos);
                isRemoteEdit = false;

                if (data.title !== titleInput.value) {
                    titleInput.value = data.title;
                    document.title   = data.title + ' — GDocs Lite';
                }

                setSaveState('saved');
                addActivity('edit', data.editor_name, onlineUsers[data.editor_id].color, 'mengedit dokumen');
                showSnack(`✏️ ${data.editor_name} sedang mengedit...`);
            });

        showSnack('🟢 Terhubung — siap kolaborasi!');

    } catch (err) {
        console.warn('Reverb tidak tersedia:', err.message);
        showSnack('⚠️ Mode offline — perubahan tetap tersimpan');
    }
}

// ── CARET SAVE/RESTORE ────────────────────────────────────────────
function saveCaretPos(ctx) {
    const sel = window.getSelection();
    if (!sel.rangeCount) return null;
    const r = sel.getRangeAt(0).cloneRange();
    r.selectNodeContents(ctx); r.setEnd(sel.getRangeAt(0).endContainer, sel.getRangeAt(0).endOffset);
    return r.toString().length;
}
function restoreCaretPos(ctx, pos) {
    if (pos === null) return;
    try {
        const range = document.createRange(), sel = window.getSelection();
        let p = 0;
        function walk(node) {
            if (node.nodeType === 3) {
                if (p + node.length >= pos) {
                    range.setStart(node, pos - p); range.collapse(true);
                    sel.removeAllRanges(); sel.addRange(range); throw 0;
                }
                p += node.length;
            } else { for (const c of node.childNodes) walk(c); }
        }
        try { walk(ctx); } catch(e) { if(e!==0) console.error(e); }
    } catch {}
}

// ── KEYBOARD SHORTCUTS ────────────────────────────────────────────
document.addEventListener('keydown', e => {
    if ((e.ctrlKey||e.metaKey) && e.key==='s') {
        e.preventDefault();
        clearTimeout(saveTimer);
        saveDocument();
    }
});
</script>
</body>
</html>
