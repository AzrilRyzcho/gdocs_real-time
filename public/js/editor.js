const WARNA_PENGGUNA = ['#ea4335', '#4285f4', '#fbbc05', '#34a853', '#9c27b0', '#ff5722', '#00bcd4', '#e91e63'];

function dapatkanWarnaUser(idUser) {
    return WARNA_PENGGUNA[idUser % WARNA_PENGGUNA.length];
}

const editorEl     = document.getElementById('editor');
const inputJudul   = document.getElementById('doc-title');
const statusSimpan = document.getElementById('save-status');
const daftarOnline = document.getElementById('online-list');
const toast        = document.getElementById('toast');

const conflictCard      = document.getElementById('conflict-card');
const conflictCardUser  = document.getElementById('conflict-card-user-name');
const conflictUserEdit  = document.getElementById('conflict-card-user-edit');
const conflictMyEdit    = document.getElementById('conflict-card-my-edit');
const btnResolveTheirs  = document.getElementById('btn-resolve-theirs');
const btnResolveMine    = document.getElementById('btn-resolve-mine');
const btnResolveMerge   = document.getElementById('btn-resolve-merge');

const urlPerbarui     = editorEl.dataset.updateUrl;
const urlPoll         = editorEl.dataset.pollUrl;
const urlVersi        = editorEl.dataset.versionUrl;
const urlBagikan      = editorEl.dataset.shareUrl;
const urlHapusBagikan = editorEl.dataset.removeShareUrl;
const tokenCsrf       = editorEl.dataset.csrf;
const idUserSekarang  = parseInt(editorEl.dataset.currentUser);
const idDokumen       = parseInt(editorEl.dataset.documentId);
const bisaEdit        = editorEl.dataset.canEdit === '1';

let sedangMengetik      = false;
let timerMengetik       = null;
let kontenTerakhir      = editorEl.value;
let judulTerakhir       = inputJudul.value;
let waktuSimpanTerakhir = 0;
let timestampTerakhir   = parseInt(editorEl.dataset.updatedAt) || 0;
let userTerkini         = {};
let indeksKursorSaya    = null;

let konflikSedangAktif = false;
let dataKonflikServer  = null;

// ─── Cursor tracking ──────────────────────────────────────────────────────────

function perbaruiIndeksSaya() {
    if (editorEl.selectionEnd !== undefined && editorEl.selectionEnd !== null) {
        indeksKursorSaya = editorEl.selectionEnd;
    }
}

if (bisaEdit) {
    editorEl.addEventListener('click',  perbaruiIndeksSaya);
    editorEl.addEventListener('keyup',  perbaruiIndeksSaya);
    editorEl.addEventListener('focus',  perbaruiIndeksSaya);
    editorEl.addEventListener('input',  perbaruiIndeksSaya);
}

// ─── Autosave (ke DB — berjalan di background) ────────────────────────────────

if (bisaEdit) {
    editorEl.addEventListener('input', () => {
        sedangMengetik = true;
        clearTimeout(timerMengetik);
        const sekarang = Date.now();
        if (sekarang - waktuSimpanTerakhir > 500) {
            simpanOtomatis();
            waktuSimpanTerakhir = sekarang;
        }
        timerMengetik = setTimeout(() => {
            sedangMengetik = false;
            simpanOtomatis();
            waktuSimpanTerakhir = Date.now();
        }, 200);
    });
}

inputJudul.addEventListener('input', () => {
    sedangMengetik = true;
    clearTimeout(timerMengetik);
    timerMengetik = setTimeout(() => {
        sedangMengetik = false;
        simpanOtomatis();
    }, 200);
});

async function simpanOtomatis() {
    if (konflikSedangAktif) return;
    const konten = editorEl.value;
    const judul  = inputJudul.value;
    if (konten === kontenTerakhir && judul === judulTerakhir) return;
    statusSimpan.textContent = 'Menyimpan...';
    statusSimpan.className   = 'save-status saving';
    try {
        const res  = await fetch(urlPerbarui, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': tokenCsrf },
            body: JSON.stringify({ konten, judul, indeks_kursor: indeksKursorSaya })
        });
        const data = await res.json();
        if (data.success) {
            kontenTerakhir = konten;
            judulTerakhir  = judul;
            if (data.updated_at_timestamp) timestampTerakhir = data.updated_at_timestamp;
            statusSimpan.textContent = '✓ Tersimpan ' + data.updated_at;
            statusSimpan.className   = 'save-status saved';
        }
    } catch (e) {
        statusSimpan.textContent = 'Gagal menyimpan';
        statusSimpan.className   = 'save-status';
    }
}

// ─── WebSocket via Laravel Echo + Reverb ──────────────────────────────────────

let channelDokumen = null;

function terapkanPerubahanRemote(kontenBaru, judulBaru, editorId, updatedAt, updatedAtTimestamp) {
    if (editorId === idUserSekarang) return; // abaikan perubahan dari diri sendiri

    if (updatedAtTimestamp && updatedAtTimestamp <= timestampTerakhir) return; // sudah up-to-date
    timestampTerakhir = updatedAtTimestamp;

    // Deteksi konflik — saya sedang mengetik dan konten berbeda
    if (sedangMengetik && editorEl.value !== kontenTerakhir && editorEl.value !== kontenBaru) {
        if (!konflikSedangAktif) {
            konflikSedangAktif = true;
            dataKonflikServer  = { konten: kontenBaru, judul: judulBaru, editor_id: editorId };

            const editUser = dapatkanPerubahanTeks(kontenTerakhir, kontenBaru);
            const editSaya = dapatkanPerubahanTeks(kontenTerakhir, editorEl.value);

            const namaEditor = userTerkini[editorId]?.name || 'Pengguna lain';
            conflictCardUser.textContent = namaEditor;
            conflictUserEdit.textContent = editUser;
            conflictMyEdit.textContent   = editSaya;
            conflictCard.style.display   = 'flex';

            tampilkanToast('⚠️ Konflik ketikan terdeteksi!', 'error');
        }
        return;
    }

    // Tidak konflik — terapkan langsung
    if (kontenBaru !== editorEl.value) {
        const posisiAwal       = editorEl.selectionStart;
        const posisiAkhir      = editorEl.selectionEnd;
        const sedangFokus      = document.activeElement === editorEl;
        const scrollTopSebelum = editorEl.scrollTop;

        // Hitung pergeseran kursor
        let selisih = 0;
        let i = 0;
        const lenLama = editorEl.value.length;
        const lenBaru = kontenBaru.length;
        while (i < lenLama && i < lenBaru && editorEl.value[i] === kontenBaru[i]) i++;
        if (i < posisiAkhir) selisih = lenBaru - lenLama;

        editorEl.value = kontenBaru;
        kontenTerakhir = kontenBaru;

        editorEl.setSelectionRange(
            Math.max(0, posisiAwal + selisih),
            Math.max(0, posisiAkhir + selisih)
        );
        if (sedangFokus) editorEl.scrollTop = scrollTopSebelum;
        if (indeksKursorSaya !== null) indeksKursorSaya = Math.max(0, indeksKursorSaya + selisih);
    }

    if (judulBaru && judulBaru !== inputJudul.value) {
        inputJudul.value = judulBaru;
        judulTerakhir    = judulBaru;
    }

    statusSimpan.textContent = '↺ Diperbarui ' + updatedAt;
    statusSimpan.className   = 'save-status saved';
}

function sambungkanWebSocket() {
    if (typeof window.Echo === 'undefined') {
        console.warn('Laravel Echo belum siap, fallback ke polling.');
        return;
    }

    channelDokumen = window.Echo.join('document.' + idDokumen)
        .here((users) => {
            // Inisialisasi daftar user online saat pertama join
            userTerkini = {};
            users.forEach(u => { userTerkini[u.id] = u; });
            renderDaftarOnline();
        })
        .joining((user) => {
            userTerkini[user.id] = user;
            renderDaftarOnline();
        })
        .leaving((user) => {
            delete userTerkini[user.id];
            delete userTerkini[user.id];
            renderDaftarOnline();
            // Hapus kursor user yang keluar
            document.getElementById('kursor-pengguna-' + user.id)?.remove();
        })
        .listen('.document.changed', (data) => {
            // ← Ini yang membuat update INSTAN tanpa polling
            terapkanPerubahanRemote(
                data.konten,
                data.judul,
                data.editor_id,
                data.updated_at,
                data.updated_at_timestamp
            );
        })
        .listen('.cursor.moved', (data) => {
            if (data.user_id === idUserSekarang) return;
            // Update posisi kursor remote secara realtime
            if (userTerkini[data.user_id]) {
                userTerkini[data.user_id].indeks_kursor = data.indeks_kursor;
            }
            tampilkanKursorSatuUser(data.user_id, data.user_name, data.indeks_kursor);
        })
        .error((err) => {
            console.error('WebSocket error:', err);
        });

    console.log('✓ WebSocket terhubung ke channel document.' + idDokumen);
}

// ─── Polling ringan — HANYA untuk presence (siapa online), bukan konten ──────
// Polling tetap jalan tapi intervalnya lebih jarang karena konten sudah via WS

async function pollPresence() {
    try {
        const res  = await fetch(urlPoll, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': tokenCsrf },
            body: JSON.stringify({ indeks_kursor: indeksKursorSaya })
        });
        const data = await res.json();

        // Update presence list dari poll (sebagai fallback/sinkronisasi)
        if (data.pengguna_online) {
            data.pengguna_online.forEach(u => {
                if (!userTerkini[u.id]) userTerkini[u.id] = { id: u.id, name: u.name };
            });
            renderDaftarOnline();
        }

        // Fallback: jika WebSocket tidak konek, polling masih handle konten
        if (typeof window.Echo === 'undefined' || !channelDokumen) {
            if (data.updated_at_timestamp && data.updated_at_timestamp > timestampTerakhir) {
                terapkanPerubahanRemote(
                    data.konten,
                    data.judul,
                    data.pengedit_terakhir?.id,
                    data.updated_at,
                    data.updated_at_timestamp
                );
            }
        }
    } catch (e) {}
}

// ─── Render UI ────────────────────────────────────────────────────────────────

function renderDaftarOnline() {
    const users = Object.values(userTerkini);
    if (users.length === 0) {
        daftarOnline.innerHTML = '<div class="empty-sidebar">Tidak ada yang online</div>';
        return;
    }
    daftarOnline.innerHTML = users.map(p => {
        const warna = dapatkanWarnaUser(p.id);
        return `<div class="online-user">
            <div class="online-dot" style="background:${warna}"></div>
            <span>${p.name}${p.id === idUserSekarang ? ' <em style="color:#888;font-size:11px">(kamu)</em>' : ''}</span>
        </div>`;
    }).join('');
}

function tampilkanKursorSatuUser(userId, userName, indeksKursor) {
    const overlay = document.getElementById('cursor-overlay');
    if (!overlay) return;
    if (userId === idUserSekarang || !indeksKursor) return;

    const koordinat = dapatkanKoordinatKursorDiIndeks(editorEl, indeksKursor);
    let elKursor = document.getElementById('kursor-pengguna-' + userId);
    if (!elKursor) {
        const warna = dapatkanWarnaUser(userId);
        elKursor = document.createElement('div');
        elKursor.id        = 'kursor-pengguna-' + userId;
        elKursor.className = 'remote-cursor';
        elKursor.innerHTML = `<div class="remote-cursor-label" style="background:${warna}">${userName}</div><div class="remote-cursor-caret" style="background:${warna}"></div>`;
        overlay.appendChild(elKursor);
    }
    elKursor.style.top  = (koordinat.atas  - editorEl.scrollTop)  + 'px';
    elKursor.style.left = (koordinat.kiri  - editorEl.scrollLeft) + 'px';
}

function dapatkanKoordinatKursorDiIndeks(element, indeks) {
    const div   = document.createElement('div');
    const style = window.getComputedStyle(element);
    document.body.appendChild(div);
    div.style.cssText = `position:absolute;top:0;left:-9999px;white-space:pre-wrap;word-wrap:break-word;width:${element.clientWidth}px;box-sizing:border-box;`;
    ['fontFamily','fontSize','fontWeight','lineHeight','paddingTop','paddingRight','paddingBottom','paddingLeft'].forEach(p => div.style[p] = style[p]);
    div.textContent = element.value.substring(0, indeks);
    const span = document.createElement('span');
    span.textContent = element.value.substring(indeks, indeks + 1) || '.';
    div.appendChild(span);
    const koordinat = { atas: span.offsetTop, kiri: span.offsetLeft };
    document.body.removeChild(div);
    return koordinat;
}

editorEl.addEventListener('scroll', () => {
    Object.values(userTerkini).forEach(u => {
        if (u.id !== idUserSekarang && u.indeks_kursor) {
            tampilkanKursorSatuUser(u.id, u.name, u.indeks_kursor);
        }
    });
});

// ─── Conflict resolution ──────────────────────────────────────────────────────

function dapatkanPerubahanTeks(base, current) {
    if (base === current) return "(Tidak ada perubahan)";
    let start = 0;
    while (start < base.length && start < current.length && base[start] === current[start]) start++;
    let end = 0;
    while (end < base.length - start && end < current.length - start &&
           base[base.length - 1 - end] === current[current.length - 1 - end]) end++;
    const perubahan = current.substring(start, current.length - end).trim();
    return perubahan || "(Penghapusan teks)";
}

function selesaikanMergeKolaboratif(base, local, remote) {
    if (local === base) return remote;
    if (remote === base) return local;
    let startR = 0;
    while (startR < base.length && startR < remote.length && base[startR] === remote[startR]) startR++;
    let endR = 0;
    while (endR < base.length - startR && endR < remote.length - startR &&
           base[base.length - 1 - endR] === remote[remote.length - 1 - endR]) endR++;
    const remoteInsert    = remote.substring(startR, remote.length - endR);
    const remoteDeleteLen = base.length - endR - startR;
    let startL = 0;
    while (startL < base.length && startL < local.length && base[startL] === local[startL]) startL++;
    const localShift   = startL < startR ? local.length - base.length : 0;
    const targetIndex  = Math.max(0, startR + localShift);
    return local.substring(0, targetIndex) + remoteInsert + local.substring(Math.max(0, targetIndex + remoteDeleteLen));
}

btnResolveTheirs?.addEventListener('click', () => {
    if (!dataKonflikServer) return;
    const pos = editorEl.selectionStart;
    const scroll = editorEl.scrollTop;
    editorEl.value = dataKonflikServer.konten;
    kontenTerakhir = dataKonflikServer.konten;
    editorEl.setSelectionRange(pos, pos);
    editorEl.scrollTop = scroll;
    tutupKartuKonflik();
    tampilkanToast('✓ Menggunakan ketikan mereka!');
});

btnResolveMine?.addEventListener('click', async () => {
    if (!dataKonflikServer) return;
    kontenTerakhir = editorEl.value;
    tutupKartuKonflik();
    statusSimpan.textContent = 'Menyimpan...';
    statusSimpan.className   = 'save-status saving';
    try {
        const res  = await fetch(urlPerbarui, { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': tokenCsrf }, body: JSON.stringify({ konten: editorEl.value, judul: inputJudul.value }) });
        const data = await res.json();
        if (data.success) {
            timestampTerakhir = data.updated_at_timestamp;
            statusSimpan.textContent = '✓ Tersimpan ' + data.updated_at;
            statusSimpan.className   = 'save-status saved';
            tampilkanToast('✓ Ketikan Anda berhasil dipertahankan!');
        }
    } catch (e) {}
});

btnResolveMerge?.addEventListener('click', async () => {
    if (!dataKonflikServer) return;
    const pos    = editorEl.selectionStart;
    const scroll = editorEl.scrollTop;
    const merged = selesaikanMergeKolaboratif(kontenTerakhir, editorEl.value, dataKonflikServer.konten);
    const selisih = merged.length - editorEl.value.length;
    editorEl.value = merged;
    kontenTerakhir = merged;
    editorEl.setSelectionRange(Math.max(0, pos + selisih), Math.max(0, pos + selisih));
    editorEl.scrollTop = scroll;
    if (indeksKursorSaya !== null) indeksKursorSaya = Math.max(0, indeksKursorSaya + selisih);
    tutupKartuKonflik();
    statusSimpan.textContent = 'Menyimpan...';
    statusSimpan.className   = 'save-status saving';
    try {
        const res  = await fetch(urlPerbarui, { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': tokenCsrf }, body: JSON.stringify({ konten: merged, judul: inputJudul.value }) });
        const data = await res.json();
        if (data.success) {
            timestampTerakhir = data.updated_at_timestamp;
            statusSimpan.textContent = '✓ Tersimpan ' + data.updated_at;
            statusSimpan.className   = 'save-status saved';
            tampilkanToast('✓ Kedua ketikan berhasil digabungkan!');
        }
    } catch (e) {}
});

function tutupKartuKonflik() {
    konflikSedangAktif = false;
    dataKonflikServer  = null;
    conflictCard.style.display = 'none';
}

// ─── Version & Toast ──────────────────────────────────────────────────────────

async function simpanVersi() {
    await simpanOtomatis();
    try {
        const res  = await fetch(urlVersi, { method: 'POST', headers: { 'X-CSRF-TOKEN': tokenCsrf } });
        const data = await res.json();
        if (data.success) { tampilkanToast('✓ Versi tersimpan!'); setTimeout(() => location.reload(), 1500); }
    } catch (e) { tampilkanToast('Gagal menyimpan versi', 'error'); }
}

function tampilkanToast(pesan, tipe = 'success') {
    toast.textContent      = pesan;
    toast.style.background = tipe === 'success' ? '#34a853' : '#ea4335';
    toast.style.color      = '#fff';
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 3000);
}

// ─── Share modal ──────────────────────────────────────────────────────────────

function bukaModalShare()  { document.getElementById('share-modal').classList.add('open');    document.getElementById('share-email').focus(); }
function tutupModalShare() { document.getElementById('share-modal').classList.remove('open'); }
document.getElementById('share-modal')?.addEventListener('click', function(e) { if (e.target === this) tutupModalShare(); });
document.addEventListener('keydown', e => { if (e.key === 'Escape') tutupModalShare(); });

async function tambahAkses() {
    const email  = document.getElementById('share-email').value;
    const izin   = document.getElementById('share-perm').value;
    const errEl  = document.getElementById('share-error');
    const sucEl  = document.getElementById('share-success');
    const btn    = document.querySelector('.btn-add-share');
    errEl.style.display = sucEl.style.display = 'none';
    if (!email) return;
    btn.disabled = true; btn.textContent = 'Memproses...';
    try {
        const res  = await fetch(urlBagikan, { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': tokenCsrf }, body: JSON.stringify({ email, permission: izin }) });
        const data = await res.json();
        btn.disabled = false; btn.textContent = 'Bagikan';
        if (!res.ok) { errEl.textContent = data.error || 'Terjadi kesalahan.'; errEl.style.display = 'block'; return; }
        sucEl.textContent = data.pesan; sucEl.style.display = 'block';
        document.getElementById('share-email').value = '';
        document.getElementById('no-shares-msg')?.remove();
        const kelasBadge = data.permission === 'edit' ? 'perm-edit' : 'perm-view';
        const teksIzin   = data.permission === 'edit' ? 'Bisa Edit' : 'Hanya Lihat';
        const existing   = document.getElementById('share-item-' + data.user.id);
        if (existing) {
            existing.querySelector('.share-perm').textContent = teksIzin;
            existing.querySelector('.share-perm').className   = 'share-perm ' + kelasBadge;
        } else {
            document.getElementById('share-list').insertAdjacentHTML('beforeend',
                `<div class="share-item" id="share-item-${data.user.id}">
                    <div class="share-avatar" style="background:${data.user.color}">${data.user.initial}</div>
                    <div class="share-user-info"><div class="sname">${data.user.name}</div><div class="semail">${data.user.email}</div></div>
                    <span class="share-perm ${kelasBadge}">${teksIzin}</span>
                    <button class="btn-remove-share" onclick="hapusAkses(${data.user.id},this)" title="Hapus akses">&times;</button>
                </div>`);
        }
    } catch (e) { btn.disabled = false; btn.textContent = 'Bagikan'; errEl.textContent = 'Gagal menghubungi server.'; errEl.style.display = 'block'; }
}

async function hapusAkses(userId) {
    if (!confirm('Hapus akses untuk pengguna ini?')) return;
    try {
        const res = await fetch(urlHapusBagikan + '/' + userId, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': tokenCsrf } });
        if (res.ok) {
            document.getElementById('share-item-' + userId)?.remove();
            const list = document.getElementById('share-list');
            if (list && list.children.length === 0) list.innerHTML = '<div id="no-shares-msg" style="font-size:13px;color:#bbb;text-align:center;padding:16px 0">Belum ada yang diberi akses.</div>';
        }
    } catch (e) { tampilkanToast('Gagal menghapus akses'); }
}

// ─── Init ─────────────────────────────────────────────────────────────────────

// Tunggu Echo siap (di-load via Vite build)
function init() {
    sambungkanWebSocket();
    // Polling presence tetap jalan sebagai fallback, interval lebih jarang
    setInterval(pollPresence, 3000);
    pollPresence();
}

// Echo di-load oleh Vite (app.js), tunggu sebentar agar terdefinisi
if (typeof window.Echo !== 'undefined') {
    init();
} else {
    window.addEventListener('load', () => {
        setTimeout(init, 300);
    });
}
