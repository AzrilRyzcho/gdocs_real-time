/**
 * ZyDocs — Realtime Editor
 * Alur: ketik → broadcast WS langsung → client lain update instan
 *        (terpisah) autosave ke DB setiap 3 detik
 */

const WARNA = ['#ea4335','#4285f4','#fbbc05','#34a853','#9c27b0','#ff5722','#00bcd4','#e91e63'];
const warna = id => WARNA[id % WARNA.length];

// ── DOM refs ──────────────────────────────────────────────────────────────────
const editorEl    = document.getElementById('editor');
const inputJudul  = document.getElementById('doc-title');
const statusEl    = document.getElementById('save-status');
const onlineEl    = document.getElementById('online-list');
const toastEl     = document.getElementById('toast');
const overlay     = document.getElementById('cursor-overlay');

const conflictCard    = document.getElementById('conflict-card');
const conflictUser    = document.getElementById('conflict-card-user-name');
const conflictTheir   = document.getElementById('conflict-card-user-edit');
const conflictMine    = document.getElementById('conflict-card-my-edit');
const btnTheirs       = document.getElementById('btn-resolve-theirs');
const btnMine         = document.getElementById('btn-resolve-mine');
const btnMerge        = document.getElementById('btn-resolve-merge');

// ── Dataset ───────────────────────────────────────────────────────────────────
const URL_BROADCAST        = editorEl.dataset.broadcastUrl;
const URL_BROADCAST_CURSOR = editorEl.dataset.broadcastCursorUrl;
const URL_AUTOSAVE         = editorEl.dataset.updateUrl;
const URL_POLL             = editorEl.dataset.pollUrl;
const URL_VERSI            = editorEl.dataset.versionUrl;
const URL_BAGIKAN          = editorEl.dataset.shareUrl;
const URL_HAPUS_BAGIKAN    = editorEl.dataset.removeShareUrl;
const CSRF                 = editorEl.dataset.csrf;
const ID_USER              = parseInt(editorEl.dataset.currentUser);
const ID_DOK               = parseInt(editorEl.dataset.documentId);
const BISA_EDIT            = editorEl.dataset.canEdit === '1';

// ── State ─────────────────────────────────────────────────────────────────────
let kontenTerakhir   = editorEl.value;
let judulTerakhir    = inputJudul.value;
let tsDb             = parseInt(editorEl.dataset.updatedAt) || 0;
let kursor           = null;
let usersOnline      = {};          // { id: { id, name } }
let konflikAktif     = false;
let dataKonflik      = null;

// throttle broadcast — kirim max 1x per 30ms
let tBroadcast = null;
let tAutosave  = null;
let tCursor    = null;

// ── Helpers ───────────────────────────────────────────────────────────────────
const post = (url, body) => fetch(url, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
    body: JSON.stringify(body),
});

// ── Cursor tracking ───────────────────────────────────────────────────────────
const updateKursor = () => { kursor = editorEl.selectionEnd; };

if (BISA_EDIT) {
    editorEl.addEventListener('click',  updateKursor);
    editorEl.addEventListener('keyup',  updateKursor);
    editorEl.addEventListener('focus',  updateKursor);
    editorEl.addEventListener('input',  updateKursor);
}

// ── INPUT: broadcast WS dulu, autosave DB terpisah ───────────────────────────
if (BISA_EDIT) {
    editorEl.addEventListener('input', () => {
        if (konflikAktif) return;

        // 1) Broadcast WS — throttle 30ms, tidak tunggu DB
        clearTimeout(tBroadcast);
        tBroadcast = setTimeout(() => kirimBroadcast(), 30);

        // 2) Autosave DB — debounce 3 detik
        clearTimeout(tAutosave);
        tAutosave = setTimeout(() => simpanDB(), 3000);
    });

    // Kursor bergerak tanpa input
    editorEl.addEventListener('keyup', () => {
        clearTimeout(tCursor);
        tCursor = setTimeout(() => kirimKursor(), 50);
    });
    editorEl.addEventListener('click', () => {
        clearTimeout(tCursor);
        tCursor = setTimeout(() => kirimKursor(), 50);
    });
}

inputJudul.addEventListener('input', () => {
    clearTimeout(tBroadcast);
    tBroadcast = setTimeout(() => kirimBroadcast(), 30);
    clearTimeout(tAutosave);
    tAutosave = setTimeout(() => simpanDB(), 3000);
});

// ── Broadcast ke WebSocket (ringan, tidak tunggu DB) ─────────────────────────
function kirimBroadcast() {
    if (konflikAktif) return;
    post(URL_BROADCAST, {
        konten:        editorEl.value,
        judul:         inputJudul.value,
        indeks_kursor: kursor,
    }).catch(() => {});
}

// ── Broadcast kursor saja ────────────────────────────────────────────────────
function kirimKursor() {
    post(URL_BROADCAST_CURSOR, { indeks_kursor: kursor }).catch(() => {});
}

// ── Autosave ke DB (terpisah, lebih jarang) ───────────────────────────────────
async function simpanDB() {
    if (konflikAktif) return;
    const konten = editorEl.value;
    const judul  = inputJudul.value;
    if (konten === kontenTerakhir && judul === judulTerakhir) return;

    statusEl.textContent = 'Menyimpan...';
    statusEl.className   = 'save-status saving';
    try {
        const r = await post(URL_AUTOSAVE, { konten, judul });
        const d = await r.json();
        if (d.success) {
            kontenTerakhir = konten;
            judulTerakhir  = judul;
            if (d.updated_at_timestamp) tsDb = d.updated_at_timestamp;
            statusEl.textContent = '✓ Tersimpan ' + d.updated_at;
            statusEl.className   = 'save-status saved';
        }
    } catch {
        statusEl.textContent = 'Gagal menyimpan';
        statusEl.className   = 'save-status';
    }
}

// ── Terima perubahan dari WebSocket ──────────────────────────────────────────
function terapkan(k, j, editorId, ts) {
    if (editorId === ID_USER) return;          // abaikan diri sendiri
    if (ts && ts < tsDb) return;               // data lama, abaikan
    if (ts) tsDb = ts;

    // Deteksi konflik — user ini juga sedang mengetik konten berbeda
    if (editorEl.value !== kontenTerakhir && editorEl.value !== k) {
        if (!konflikAktif) {
            konflikAktif = true;
            dataKonflik  = { k, j, editorId };
            const namaMereka = usersOnline[editorId]?.name ?? 'Pengguna lain';
            conflictUser.textContent  = namaMereka;
            conflictTheir.textContent = diffLabel(kontenTerakhir, k);
            conflictMine.textContent  = diffLabel(kontenTerakhir, editorEl.value);
            conflictCard.style.display = 'flex';
            toast('⚠️ Konflik ketikan terdeteksi!', 'error');
        }
        return;
    }

    // Terapkan langsung tanpa blokir UI
    if (k !== editorEl.value) {
        const s0    = editorEl.selectionStart;
        const s1    = editorEl.selectionEnd;
        const sc    = editorEl.scrollTop;
        const fokus = document.activeElement === editorEl;

        // Hitung pergeseran kursor
        let i = 0;
        while (i < editorEl.value.length && i < k.length && editorEl.value[i] === k[i]) i++;
        const shift = i < s1 ? k.length - editorEl.value.length : 0;

        editorEl.value = k;
        kontenTerakhir = k;
        editorEl.setSelectionRange(Math.max(0, s0 + shift), Math.max(0, s1 + shift));
        if (fokus) editorEl.scrollTop = sc;
        if (kursor !== null) kursor = Math.max(0, kursor + shift);
    }

    if (j && j !== inputJudul.value) {
        inputJudul.value = j;
        judulTerakhir    = j;
    }

    statusEl.textContent = '↺ Diperbarui';
    statusEl.className   = 'save-status saved';
}

// ── WebSocket via Laravel Echo + Reverb ───────────────────────────────────────
function sambungWS() {
    if (!window.Echo) { console.warn('Echo belum siap'); return; }

    window.Echo.join('document.' + ID_DOK)
        .here(users => {
            usersOnline = {};
            users.forEach(u => { usersOnline[u.id] = u; });
            renderOnline();
        })
        .joining(u => { usersOnline[u.id] = u; renderOnline(); })
        .leaving(u => {
            delete usersOnline[u.id];
            renderOnline();
            document.getElementById('kursor-' + u.id)?.remove();
        })
        .listen('.doc.changed', d => {
            // d.k=konten, d.j=judul, d.ei=editorId, d.ts=timestamp, d.c=cursor
            terapkan(d.k, d.j, d.ei, d.ts);
            if (d.c !== undefined && d.ei !== ID_USER) {
                renderKursorUser(d.ei, usersOnline[d.ei]?.name ?? '?', d.c);
            }
        })
        .listen('.cursor.moved', d => {
            // d.uid=userId, d.n=name, d.c=cursor
            if (d.uid === ID_USER) return;
            if (usersOnline[d.uid]) usersOnline[d.uid].kursor = d.c;
            renderKursorUser(d.uid, d.n, d.c);
        })
        .error(e => console.error('WS error:', e));

    console.log('✓ WS terhubung — document.' + ID_DOK);
}

// ── Poll presence (presence only, bukan konten) — setiap 5 detik ─────────────
async function pollPresence() {
    try {
        const r = await post(URL_POLL, { indeks_kursor: kursor });
        const d = await r.json();
        if (d.pengguna_online) {
            d.pengguna_online.forEach(u => {
                if (!usersOnline[u.id]) { usersOnline[u.id] = { id: u.id, name: u.name }; }
            });
            renderOnline();
        }
        // Fallback konten hanya jika WS belum konek
        if (!window.Echo && d.konten !== undefined) {
            terapkan(d.konten, d.judul, d.id_user_saya === ID_USER ? -1 : 0, d.updated_at_timestamp);
        }
    } catch {}
}

// ── Render online list ────────────────────────────────────────────────────────
function renderOnline() {
    const list = Object.values(usersOnline);
    if (!list.length) { onlineEl.innerHTML = '<div class="empty-sidebar">Tidak ada yang online</div>'; return; }
    onlineEl.innerHTML = list.map(u =>
        `<div class="online-user">
            <div class="online-dot" style="background:${warna(u.id)}"></div>
            <span>${u.name}${u.id === ID_USER ? ' <em style="color:#888;font-size:11px">(kamu)</em>' : ''}</span>
        </div>`
    ).join('');
}

// ── Render kursor remote ──────────────────────────────────────────────────────
function renderKursorUser(userId, name, idx) {
    if (!overlay || userId === ID_USER || !idx) return;
    const pos  = kursorPos(editorEl, idx);
    let el = document.getElementById('kursor-' + userId);
    if (!el) {
        const c = warna(userId);
        el = document.createElement('div');
        el.id        = 'kursor-' + userId;
        el.className = 'remote-cursor';
        el.innerHTML = `<div class="remote-cursor-label" style="background:${c}">${name}</div><div class="remote-cursor-caret" style="background:${c}"></div>`;
        overlay.appendChild(el);
    }
    el.style.top  = (pos.top  - editorEl.scrollTop)  + 'px';
    el.style.left = (pos.left - editorEl.scrollLeft) + 'px';
}

function kursorPos(el, idx) {
    const div = document.createElement('div');
    const s   = getComputedStyle(el);
    document.body.appendChild(div);
    div.style.cssText = `position:absolute;top:0;left:-9999px;white-space:pre-wrap;word-wrap:break-word;width:${el.clientWidth}px;box-sizing:border-box;`;
    ['fontFamily','fontSize','fontWeight','lineHeight','paddingTop','paddingRight','paddingBottom','paddingLeft'].forEach(p => div.style[p] = s[p]);
    div.textContent = el.value.substring(0, idx);
    const sp = document.createElement('span');
    sp.textContent = el.value[idx] || '.';
    div.appendChild(sp);
    const r = { top: sp.offsetTop, left: sp.offsetLeft };
    document.body.removeChild(div);
    return r;
}

editorEl.addEventListener('scroll', () => {
    Object.values(usersOnline).forEach(u => {
        if (u.id !== ID_USER && u.kursor) renderKursorUser(u.id, u.name, u.kursor);
    });
});

// ── Conflict resolution ───────────────────────────────────────────────────────
function diffLabel(base, cur) {
    if (base === cur) return '(tidak ada perubahan)';
    let s = 0; while (s < base.length && s < cur.length && base[s] === cur[s]) s++;
    let e = 0; while (e < base.length-s && e < cur.length-s && base[base.length-1-e] === cur[cur.length-1-e]) e++;
    return cur.substring(s, cur.length-e).trim() || '(penghapusan)';
}

function merge3(base, local, remote) {
    if (local === base) return remote;
    if (remote === base) return local;
    let sR = 0; while (sR < base.length && sR < remote.length && base[sR] === remote[sR]) sR++;
    let eR = 0; while (eR < base.length-sR && eR < remote.length-sR && base[base.length-1-eR] === remote[remote.length-1-eR]) eR++;
    const ins = remote.substring(sR, remote.length-eR);
    const del = base.length - eR - sR;
    let sL = 0; while (sL < base.length && sL < local.length && base[sL] === local[sL]) sL++;
    const shift = sL < sR ? local.length - base.length : 0;
    const ti = Math.max(0, sR + shift);
    return local.substring(0, ti) + ins + local.substring(Math.max(0, ti + del));
}

btnTheirs?.addEventListener('click', () => {
    if (!dataKonflik) return;
    const sc = editorEl.scrollTop;
    editorEl.value = dataKonflik.k;
    kontenTerakhir = dataKonflik.k;
    editorEl.scrollTop = sc;
    tutupKonflik(); toast('✓ Menggunakan ketikan mereka!');
});

btnMine?.addEventListener('click', async () => {
    if (!dataKonflik) return;
    kontenTerakhir = editorEl.value;
    tutupKonflik();
    await simpanDB();
    toast('✓ Ketikan Anda dipertahankan!');
});

btnMerge?.addEventListener('click', async () => {
    if (!dataKonflik) return;
    const s0 = editorEl.selectionStart;
    const merged = merge3(kontenTerakhir, editorEl.value, dataKonflik.k);
    const shift  = merged.length - editorEl.value.length;
    editorEl.value = merged; kontenTerakhir = merged;
    editorEl.setSelectionRange(Math.max(0, s0+shift), Math.max(0, s0+shift));
    if (kursor !== null) kursor = Math.max(0, kursor + shift);
    tutupKonflik();
    await simpanDB();
    toast('✓ Kedua ketikan digabungkan!');
});

function tutupKonflik() { konflikAktif = false; dataKonflik = null; conflictCard.style.display = 'none'; }

// ── Version save ──────────────────────────────────────────────────────────────
async function simpanVersi() {
    await simpanDB();
    try {
        const r = await fetch(URL_VERSI, { method: 'POST', headers: { 'X-CSRF-TOKEN': CSRF } });
        const d = await r.json();
        if (d.success) { toast('✓ Versi tersimpan!'); setTimeout(() => location.reload(), 1500); }
    } catch { toast('Gagal menyimpan versi', 'error'); }
}

// ── Toast ─────────────────────────────────────────────────────────────────────
function toast(msg, type = 'success') {
    toastEl.textContent = msg;
    toastEl.style.background = type === 'success' ? '#34a853' : '#ea4335';
    toastEl.style.color = '#fff';
    toastEl.classList.add('show');
    setTimeout(() => toastEl.classList.remove('show'), 3000);
}

// ── Share modal ───────────────────────────────────────────────────────────────
function bukaModalShare()  { document.getElementById('share-modal').classList.add('open'); document.getElementById('share-email').focus(); }
function tutupModalShare() { document.getElementById('share-modal').classList.remove('open'); }
document.getElementById('share-modal')?.addEventListener('click', e => { if (e.target === e.currentTarget) tutupModalShare(); });
document.addEventListener('keydown', e => { if (e.key === 'Escape') tutupModalShare(); });

async function tambahAkses() {
    const email = document.getElementById('share-email').value;
    const izin  = document.getElementById('share-perm').value;
    const errEl = document.getElementById('share-error');
    const sucEl = document.getElementById('share-success');
    const btn   = document.querySelector('.btn-add-share');
    errEl.style.display = sucEl.style.display = 'none';
    if (!email) return;
    btn.disabled = true; btn.textContent = 'Memproses...';
    try {
        const r = await post(URL_BAGIKAN, { email, permission: izin });
        const d = await r.json();
        btn.disabled = false; btn.textContent = 'Bagikan';
        if (!r.ok) { errEl.textContent = d.error || 'Terjadi kesalahan.'; errEl.style.display = 'block'; return; }
        sucEl.textContent = d.pesan; sucEl.style.display = 'block';
        document.getElementById('share-email').value = '';
        document.getElementById('no-shares-msg')?.remove();
        const kb = d.permission === 'edit' ? 'perm-edit' : 'perm-view';
        const ti = d.permission === 'edit' ? 'Bisa Edit' : 'Hanya Lihat';
        const ex = document.getElementById('share-item-' + d.user.id);
        if (ex) { ex.querySelector('.share-perm').textContent = ti; ex.querySelector('.share-perm').className = 'share-perm ' + kb; }
        else document.getElementById('share-list').insertAdjacentHTML('beforeend',
            `<div class="share-item" id="share-item-${d.user.id}">
                <div class="share-avatar" style="background:${d.user.color}">${d.user.initial}</div>
                <div class="share-user-info"><div class="sname">${d.user.name}</div><div class="semail">${d.user.email}</div></div>
                <span class="share-perm ${kb}">${ti}</span>
                <button class="btn-remove-share" onclick="hapusAkses(${d.user.id})" title="Hapus akses">&times;</button>
            </div>`);
    } catch { btn.disabled = false; btn.textContent = 'Bagikan'; errEl.textContent = 'Gagal menghubungi server.'; errEl.style.display = 'block'; }
}

async function hapusAkses(userId) {
    if (!confirm('Hapus akses untuk pengguna ini?')) return;
    try {
        const r = await fetch(URL_HAPUS_BAGIKAN + '/' + userId, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': CSRF } });
        if (r.ok) {
            document.getElementById('share-item-' + userId)?.remove();
            const list = document.getElementById('share-list');
            if (list && !list.children.length) list.innerHTML = '<div id="no-shares-msg" style="font-size:13px;color:#bbb;text-align:center;padding:16px 0">Belum ada yang diberi akses.</div>';
        }
    } catch { toast('Gagal menghapus akses', 'error'); }
}

// ── Init ──────────────────────────────────────────────────────────────────────
sambungWS();
setInterval(pollPresence, 5000);   // presence only, bukan konten
pollPresence();
