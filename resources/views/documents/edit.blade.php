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
            text-decoration: none;
            color: var(--grey);
            font-size: 20px;
            padding: 6px;
            border-radius: 50%;
            transition: background .15s;
        }
        .back-btn:hover { background: #f1f3f4; }

        .doc-logo { font-size: 26px; }

        #titleInput {
            flex: 1;
            border: none;
            outline: none;
            font-size: 18px;
            font-weight: 500;
            color: var(--dark);
            background: transparent;
            padding: 6px 8px;
            border-radius: 4px;
            transition: background .15s;
            min-width: 0;
        }
        #titleInput:hover { background: #f1f3f4; }
        #titleInput:focus { background: #e8f0fe; }

        .save-status {
            font-size: 13px;
            color: var(--grey);
            white-space: nowrap;
            display: flex; align-items: center; gap: 6px;
        }
        .save-dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            background: #34a853;
            flex-shrink: 0;
        }
        .save-dot.saving { background: #fbbc04; animation: pulse 1s infinite; }
        .save-dot.error  { background: #ea4335; }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50%       { opacity: .3; }
        }

        /* ── Online users ── */
        .users-bar {
            display: flex; align-items: center; gap: 6px;
        }
        .user-avatar {
            width: 32px; height: 32px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 13px;
            font-weight: 600;
            color: #fff;
            border: 2px solid #fff;
            cursor: default;
            position: relative;
            flex-shrink: 0;
        }
        .user-avatar[title]:hover::after {
            content: attr(title);
            position: absolute;
            bottom: calc(100% + 6px);
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0,0,0,.75);
            color: #fff;
            font-size: 11px;
            padding: 4px 8px;
            border-radius: 4px;
            white-space: nowrap;
            pointer-events: none;
        }

        /* ── Format bar ── */
        .format-bar {
            background: #fff;
            border-bottom: 1px solid var(--border);
            padding: 0 16px;
            height: 40px;
            display: flex;
            align-items: center;
            gap: 2px;
            flex-shrink: 0;
        }
        .fmt-btn {
            background: none;
            border: none;
            padding: 5px 8px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 700;
            color: var(--grey);
            transition: background .1s;
            line-height: 1;
        }
        .fmt-btn:hover   { background: #f1f3f4; color: var(--dark); }
        .fmt-btn.active  { background: #e8f0fe; color: var(--blue); }

        .fmt-sep {
            width: 1px;
            height: 20px;
            background: var(--border);
            margin: 0 4px;
        }

        select.fmt-select {
            border: 1px solid var(--border);
            border-radius: 4px;
            padding: 2px 6px;
            font-size: 13px;
            background: #fff;
            color: var(--dark);
            cursor: pointer;
            outline: none;
        }

        /* ── Editor area ── */
        .editor-container {
            flex: 1;
            overflow-y: auto;
            display: flex;
            justify-content: center;
            padding: 32px 16px 80px;
            background: #f8f9fa;
        }

        .page {
            background: #fff;
            width: 100%;
            max-width: 816px;   /* A4-like width */
            min-height: 1056px; /* A4-like height */
            box-shadow: 0 1px 3px rgba(0,0,0,.12), 0 4px 8px rgba(0,0,0,.04);
            border-radius: 2px;
            padding: 72px 96px;
        }

        #editor {
            outline: none;
            font-family: Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.6;
            color: #000;
            min-height: 800px;
            white-space: pre-wrap;
            word-break: break-word;
        }

        /* ── Remote cursors ── */
        .remote-cursor {
            position: relative;
            display: inline-block;
        }
        .remote-cursor::before {
            content: '';
            position: absolute;
            left: 0; top: 0;
            width: 2px;
            height: 1.2em;
            background: var(--cursor-color, #4285f4);
        }
        .remote-cursor-label {
            position: absolute;
            top: -22px;
            left: 0;
            background: var(--cursor-color, #4285f4);
            color: #fff;
            font-size: 11px;
            padding: 2px 6px;
            border-radius: 3px;
            white-space: nowrap;
            pointer-events: none;
            z-index: 100;
        }

        /* ── Snackbar ── */
        #snackbar {
            position: fixed;
            bottom: 24px;
            left: 50%;
            transform: translateX(-50%) translateY(80px);
            background: #323232;
            color: #fff;
            padding: 10px 20px;
            border-radius: 6px;
            font-size: 14px;
            transition: transform .3s;
            z-index: 9999;
            pointer-events: none;
        }
        #snackbar.show { transform: translateX(-50%) translateY(0); }

        /* ── Name modal ── */
        .modal-overlay {
            position: fixed; inset: 0;
            background: rgba(0,0,0,.45);
            display: flex; align-items: center; justify-content: center;
            z-index: 1000;
        }
        .modal-box {
            background: #fff;
            border-radius: 12px;
            padding: 32px 28px;
            width: 360px;
            box-shadow: 0 8px 32px rgba(0,0,0,.2);
            text-align: center;
        }
        .modal-box h2 { font-size: 20px; margin-bottom: 8px; }
        .modal-box p  { font-size: 14px; color: var(--grey); margin-bottom: 20px; }
        .modal-box input {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 15px;
            outline: none;
            margin-bottom: 16px;
            transition: border-color .2s;
        }
        .modal-box input:focus { border-color: var(--blue); }
        .modal-box button {
            width: 100%;
            background: var(--blue);
            color: #fff;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: background .15s;
        }
        .modal-box button:hover { background: #1a73e8; }

        /* ── Scrollbar ── */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f3f4; }
        ::-webkit-scrollbar-thumb { background: #c0c0c0; border-radius: 4px; }
    </style>
</head>
<body>

{{-- ── Name modal ── --}}
<div class="modal-overlay" id="nameModal">
    <div class="modal-box">
        <h2>👋 Siapa nama kamu?</h2>
        <p>Nama ini akan terlihat oleh orang lain yang membuka dokumen ini.</p>
        <input type="text" id="nameInput" placeholder="Masukkan nama kamu..." maxlength="30" autocomplete="off">
        <button id="nameSubmit">Mulai Mengedit →</button>
    </div>
</div>

{{-- ── Toolbar ── --}}
<div class="toolbar">
    <a href="{{ route('documents.index') }}" class="back-btn" title="Kembali">🏠</a>
    <span class="doc-logo">📄</span>
    <input type="text" id="titleInput" value="{{ $document->title }}" maxlength="200" spellcheck="false">
    <div class="save-status">
        <div class="save-dot" id="saveDot"></div>
        <span id="saveText">Tersimpan</span>
    </div>
    <div class="users-bar" id="usersBar"></div>
</div>

{{-- ── Format bar ── --}}
<div class="format-bar">
    <select class="fmt-select" id="fontSizeSelect" title="Ukuran font">
        <option value="8">8</option><option value="9">9</option><option value="10">10</option>
        <option value="11" selected>11</option><option value="12">12</option><option value="14">14</option>
        <option value="16">16</option><option value="18">18</option><option value="20">20</option>
        <option value="24">24</option><option value="28">28</option><option value="32">32</option>
        <option value="36">36</option><option value="48">48</option><option value="72">72</option>
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
    <button class="fmt-btn" data-cmd="justifyFull"   title="Rata penuh">☰</button>
    <div class="fmt-sep"></div>
    <button class="fmt-btn" data-cmd="insertUnorderedList" title="Bullet list">• ≡</button>
    <button class="fmt-btn" data-cmd="insertOrderedList"   title="Numbered list">1. ≡</button>
    <div class="fmt-sep"></div>
    <button class="fmt-btn" data-cmd="undo"  title="Undo (Ctrl+Z)">↩</button>
    <button class="fmt-btn" data-cmd="redo"  title="Redo (Ctrl+Y)">↪</button>
</div>

{{-- ── Editor ── --}}
<div class="editor-container">
    <div class="page">
        <div id="editor" contenteditable="true" spellcheck="true">{!! $document->content !!}</div>
    </div>
</div>

{{-- Snackbar --}}
<div id="snackbar"></div>

<script>
// ────────────────────────────────────────────────────────────────
//  CONFIG
// ────────────────────────────────────────────────────────────────
const DOC_ID      = {{ $document->id }};
const UPDATE_URL  = '/documents/{{ $document->id }}';
const CSRF        = document.querySelector('meta[name="csrf-token"]').content;
const REVERB_KEY  = '{{ config("broadcasting.connections.reverb.key", env("REVERB_APP_KEY")) }}';
const REVERB_HOST = window.location.hostname;   // otomatis pakai IP/host saat ini
const REVERB_PORT = {{ env("REVERB_PORT", 8080) }};

// ────────────────────────────────────────────────────────────────
//  STATE
// ────────────────────────────────────────────────────────────────
let myId   = null;
let myName = null;
let onlineUsers = {};   // { id: { name, color, avatar } }

const COLORS = [
    '#e74c3c','#3498db','#2ecc71','#f39c12',
    '#9b59b6','#1abc9c','#e67e22','#e91e63',
];

function randomColor() {
    return COLORS[Math.floor(Math.random() * COLORS.length)];
}
function initials(name) {
    return name.split(' ').map(w => w[0]).join('').toUpperCase().slice(0, 2);
}

// ────────────────────────────────────────────────────────────────
//  NAME MODAL
// ────────────────────────────────────────────────────────────────
const modal       = document.getElementById('nameModal');
const nameInput   = document.getElementById('nameInput');
const nameSubmit  = document.getElementById('nameSubmit');

function startSession(name) {
    myName = name.trim() || 'Anonim';
    myId   = 'u_' + Math.random().toString(36).slice(2, 10);
    localStorage.setItem('gdocs_name', myName);
    modal.style.display = 'none';
    initEcho();
}

nameSubmit.addEventListener('click', () => {
    if (nameInput.value.trim()) startSession(nameInput.value);
});
nameInput.addEventListener('keydown', e => {
    if (e.key === 'Enter' && nameInput.value.trim()) startSession(nameInput.value);
});

// Pre-fill dari localStorage
const savedName = localStorage.getItem('gdocs_name');
if (savedName) {
    nameInput.value = savedName;
}

// ────────────────────────────────────────────────────────────────
//  EDITOR
// ────────────────────────────────────────────────────────────────
const editor     = document.getElementById('editor');
const titleInput = document.getElementById('titleInput');
const saveDot    = document.getElementById('saveDot');
const saveText   = document.getElementById('saveText');

let saveTimer    = null;
let isRemoteEdit = false;

function setSaveState(state) {
    saveDot.className = 'save-dot' + (state === 'saving' ? ' saving' : state === 'error' ? ' error' : '');
    saveText.textContent = state === 'saving' ? 'Menyimpan...' : state === 'error' ? 'Gagal menyimpan' : 'Tersimpan';
}

async function saveDocument() {
    setSaveState('saving');
    try {
        const res = await fetch(UPDATE_URL, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF,
            },
            body: JSON.stringify({
                content:     editor.innerHTML,
                title:       titleInput.value,
                editor_id:   myId,
                editor_name: myName,
            }),
        });
        if (!res.ok) throw new Error('HTTP ' + res.status);
        setSaveState('saved');
    } catch {
        setSaveState('error');
    }
}

// Debounced save — 1.2 detik setelah berhenti mengetik
function scheduleSave() {
    clearTimeout(saveTimer);
    saveTimer = setTimeout(saveDocument, 1200);
}

editor.addEventListener('input', () => {
    if (!isRemoteEdit) scheduleSave();
});

titleInput.addEventListener('input', scheduleSave);

// ── Formatting buttons ──
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

// ── Track format state ──
editor.addEventListener('keyup', updateFormatState);
editor.addEventListener('mouseup', updateFormatState);
function updateFormatState() {
    ['bold','italic','underline','strikeThrough'].forEach(cmd => {
        const btn = document.querySelector(`.fmt-btn[data-cmd="${cmd}"]`);
        if (btn) btn.classList.toggle('active', document.queryCommandState(cmd));
    });
}

// ────────────────────────────────────────────────────────────────
//  ONLINE USERS
// ────────────────────────────────────────────────────────────────
const usersBar = document.getElementById('usersBar');

function renderUsers() {
    usersBar.innerHTML = Object.values(onlineUsers).map(u => `
        <div class="user-avatar" style="background:${u.color}" title="${u.name}">
            ${initials(u.name)}
        </div>
    `).join('');
}

function addUser(id, name, color) {
    onlineUsers[id] = { name, color };
    renderUsers();
}
function removeUser(id) {
    delete onlineUsers[id];
    renderUsers();
}

// ────────────────────────────────────────────────────────────────
//  SNACKBAR
// ────────────────────────────────────────────────────────────────
function showSnack(msg) {
    const el = document.getElementById('snackbar');
    el.textContent = msg;
    el.classList.add('show');
    setTimeout(() => el.classList.remove('show'), 3000);
}

// ────────────────────────────────────────────────────────────────
//  LARAVEL ECHO + REVERB
// ────────────────────────────────────────────────────────────────
function initEcho() {
    // Tambahkan diri sendiri ke online users
    const myColor = randomColor();
    addUser(myId, myName, myColor);

    // Dinamis load Echo & Pusher dari node_modules via Vite
    // Fallback: CDN jika build belum ada
    const script = document.createElement('script');
    script.src = 'https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.15.3/echo.iife.js';
    script.onload = () => loadPusher(myColor);
    script.onerror = () => {
        // Coba dari path build lokal
        const s2 = document.createElement('script');
        s2.src = '/build/assets/echo.js';
        s2.onload = () => loadPusher(myColor);
        document.head.appendChild(s2);
    };
    document.head.appendChild(script);
}

function loadPusher(myColor) {
    const script = document.createElement('script');
    script.src = 'https://cdnjs.cloudflare.com/ajax/libs/pusher/8.4.0-rc2/pusher.min.js';
    script.onload = () => connectReverb(myColor);
    script.onerror = connectReverb.bind(null, myColor); // pusher mungkin sudah ada
    document.head.appendChild(script);
}

function connectReverb(myColor) {
    try {
        window.Echo = new window.LaravelEcho({
            broadcaster: 'reverb',
            key:         REVERB_KEY,
            wsHost:      REVERB_HOST,
            wsPort:      REVERB_PORT,
            wssPort:     REVERB_PORT,
            forceTLS:    false,
            enabledTransports: ['ws'],
            disableStats: true,
        });

        window.Echo.channel(`document.${DOC_ID}`)
            .listen('.document.updated', (data) => {
                // Abaikan jika kita sendiri yang mengedit
                if (data.editor_id === myId) return;

                // Update konten tanpa memicu save ulang
                isRemoteEdit = true;
                const sel = saveCaretPosition(editor);
                editor.innerHTML = data.content;
                restoreCaretPosition(editor, sel);
                isRemoteEdit = false;

                // Update judul
                if (data.title !== titleInput.value) {
                    titleInput.value = data.title;
                    document.title = data.title + ' — GDocs Lite';
                }

                // Tandai user sebagai online
                addUser(data.editor_id, data.editor_name, onlineUsers[data.editor_id]?.color || COLORS[Object.keys(onlineUsers).length % COLORS.length]);

                setSaveState('saved');
                showSnack(`✏️ ${data.editor_name} sedang mengedit...`);
            });

        showSnack('🟢 Terhubung ke server real-time');

    } catch (err) {
        console.warn('Reverb tidak tersedia, mode offline aktif:', err.message);
        showSnack('⚠️ Mode offline — perubahan tetap tersimpan');
    }
}

// ────────────────────────────────────────────────────────────────
//  CURSOR SAVE/RESTORE (agar posisi kursor tidak loncat saat
//  innerHTML diupdate oleh remote edit)
// ────────────────────────────────────────────────────────────────
function saveCaretPosition(context) {
    const selection = window.getSelection();
    if (!selection.rangeCount) return null;
    const range = selection.getRangeAt(0);
    const preCaretRange = range.cloneRange();
    preCaretRange.selectNodeContents(context);
    preCaretRange.setEnd(range.endContainer, range.endOffset);
    return preCaretRange.toString().length;
}

function restoreCaretPosition(context, savedPos) {
    if (savedPos === null) return;
    try {
        const range = document.createRange();
        const sel   = window.getSelection();
        let   pos   = 0;

        function traverseNodes(node) {
            if (node.nodeType === Node.TEXT_NODE) {
                const nextPos = pos + node.length;
                if (savedPos <= nextPos) {
                    range.setStart(node, savedPos - pos);
                    range.collapse(true);
                    sel.removeAllRanges();
                    sel.addRange(range);
                    throw 'done';
                }
                pos = nextPos;
            } else {
                for (const child of node.childNodes) traverseNodes(child);
            }
        }
        try { traverseNodes(context); } catch (e) { if (e !== 'done') console.error(e); }
    } catch {}
}

// ────────────────────────────────────────────────────────────────
//  KEYBOARD SHORTCUTS
// ────────────────────────────────────────────────────────────────
document.addEventListener('keydown', e => {
    if ((e.ctrlKey || e.metaKey) && e.key === 's') {
        e.preventDefault();
        clearTimeout(saveTimer);
        saveDocument();
    }
});
</script>

</body>
</html>
