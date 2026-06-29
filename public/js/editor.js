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
const bisaEdit        = editorEl.dataset.canEdit === '1';

let sedangMengetik     = false;
let timerMengetik      = null;
let kontenTerakhir     = editorEl.value;
let judulTerakhir      = inputJudul.value;
let waktuSimpanTerakhir = 0;
let userTerkini        = null;
let indeksKursorSaya   = null;

let konflikSedangAktif = false;
let dataKonflikServer  = null;

function perbaruiIndeksSaya() {
    if (editorEl.selectionEnd !== undefined && editorEl.selectionEnd !== null) {
        indeksKursorSaya = editorEl.selectionEnd;
    }
}

if (bisaEdit) {
    editorEl.addEventListener('click', perbaruiIndeksSaya);
    editorEl.addEventListener('keyup', perbaruiIndeksSaya);
    editorEl.addEventListener('focus', perbaruiIndeksSaya);
    editorEl.addEventListener('input', perbaruiIndeksSaya);

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
    statusSimpan.className = 'save-status saving';
    try {
        const res  = await fetch(urlPerbarui, { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': tokenCsrf }, body: JSON.stringify({ konten, judul }) });
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

function dapatkanPerubahanTeks(base, current) {
    if (base === current) return "(Tidak ada perubahan)";
    let start = 0;
    while (start < base.length && start < current.length && base[start] === current[start]) {
        start++;
    }
    let end = 0;
    while (end < base.length - start && end < current.length - start && 
           base[base.length - 1 - end] === current[current.length - 1 - end]) {
        end++;
    }
    const perubahan = current.substring(start, current.length - end).trim();
    return perubahan || "(Penghapusan teks)";
}

function selesaikanMergeKolaboratif(base, local, remote) {
    if (local === base) return remote;
    if (remote === base) return local;

    let startR = 0;
    while (startR < base.length && startR < remote.length && base[startR] === remote[startR]) {
        startR++;
    }
    let endR = 0;
    while (endR < base.length - startR && endR < remote.length - startR && 
           base[base.length - 1 - endR] === remote[remote.length - 1 - endR]) {
        endR++;
    }

    const remoteInsert = remote.substring(startR, remote.length - endR);
    const remoteDeleteLen = base.length - endR - startR;

    let localShift = 0;
    let startL = 0;
    while (startL < base.length && startL < local.length && base[startL] === local[startL]) {
        startL++;
    }
    if (startL < startR) {
        localShift = local.length - base.length;
    }

    const targetIndex = Math.max(0, startR + localShift);
    const prefix = local.substring(0, targetIndex);
    const suffix = local.substring(Math.max(0, targetIndex + remoteDeleteLen));
    
    return prefix + remoteInsert + suffix;
}

async function cekPembaruanServer() {
    try {
        const res  = await fetch(urlPoll, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': tokenCsrf },
            body: JSON.stringify({ indeks_kursor: indeksKursorSaya })
        });
        const data = await res.json();
        perbaruiDaftarOnline(data.pengguna_online, data.id_user_saya);
        tampilkanKursorRemote(data.pengguna_online, data.id_user_saya);

        if (data.updated_at_timestamp && data.updated_at_timestamp > timestampTerakhir) {
            if (data.pengedit_terakhir && data.pengedit_terakhir.id !== idUserSekarang) {
                if (editorEl.value !== data.konten && editorEl.value !== kontenTerakhir) {
                    if (!konflikSedangAktif) {
                        konflikSedangAktif = true;
                        dataKonflikServer  = data;

                        const editUser = dapatkanPerubahanTeks(kontenTerakhir, data.konten);
                        const editSaya = dapatkanPerubahanTeks(kontenTerakhir, editorEl.value);

                        conflictCardUser.textContent = data.pengedit_terakhir.nama;
                        conflictUserEdit.textContent = editUser;
                        conflictMyEdit.textContent   = editSaya;
                        conflictCard.style.display   = 'flex';

                        tampilkanToast('⚠️ Konflik ketikan terdeteksi! Gunakan panel di sudut kanan bawah untuk menyelesaikannya.', 'error');
                    }
                    timestampTerakhir = data.updated_at_timestamp;
                    return;
                }
            }
            timestampTerakhir = data.updated_at_timestamp;
        }

        if (konflikSedangAktif) return;

        const editOrangLain = data.pengedit_terakhir && data.pengedit_terakhir.id !== idUserSekarang;
        if (editOrangLain && !sedangMengetik && data.konten !== editorEl.value) {
            const posisiAwal     = editorEl.selectionStart;
            const posisiAkhir    = editorEl.selectionEnd;
            const sedangFokus    = document.activeElement === editorEl;
            const scrollTopSebelum = editorEl.scrollTop;
            
            let selisih = 0;
            let i = 0;
            const valLama = editorEl.value;
            const valBaru = data.konten;
            const lenLama = valLama.length;
            const lenBaru = valBaru.length;
            while (i < lenLama && i < lenBaru && valLama[i] === valBaru[i]) {
                i++;
            }
            if (i < posisiAkhir) {
                selisih = lenBaru - lenLama;
            }

            editorEl.value  = data.konten;
            kontenTerakhir  = data.konten;
            if (data.judul !== inputJudul.value) { inputJudul.value = data.judul; judulTerakhir = data.judul; }

            const indeksBaruAwal  = Math.max(0, posisiAwal + selisih);
            const indeksBaruAkhir = Math.max(0, posisiAkhir + selisih);
            editorEl.setSelectionRange(indeksBaruAwal, indeksBaruAkhir);
            
            if (sedangFokus) {
                editorEl.scrollTop = scrollTopSebelum;
            }
            
            if (indeksKursorSaya !== null) {
                indeksKursorSaya = Math.max(0, indeksKursorSaya + selisih);
            }

            statusSimpan.textContent = '↺ Diperbarui ' + data.updated_at;
            statusSimpan.className   = 'save-status saved';
        }
    } catch (e) {}
}

btnResolveTheirs?.addEventListener('click', () => {
    if (!dataKonflikServer) return;
    
    const posisiAwal     = editorEl.selectionStart;
    const posisiAkhir    = editorEl.selectionEnd;
    const scrollTopSebelum = editorEl.scrollTop;
    
    editorEl.value = dataKonflikServer.konten;
    kontenTerakhir = dataKonflikServer.konten;
    
    editorEl.setSelectionRange(posisiAwal, posisiAkhir);
    editorEl.scrollTop = scrollTopSebelum;
    
    tutupKartuKonflik();
    tampilkanToast('✓ Menggunakan ketikan mereka!');
});

btnResolveMine?.addEventListener('click', async () => {
    if (!dataKonflikServer) return;
    
    kontenTerakhir = editorEl.value;
    
    tutupKartuKonflik();
    statusSimpan.textContent = 'Menyimpan...';
    statusSimpan.className = 'save-status saving';
    
    try {
        const res  = await fetch(urlPerbarui, { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': tokenCsrf }, body: JSON.stringify({ konten: editorEl.value, judul: inputJudul.value }) });
        const data = await res.json();
        if (data.success) {
            timestampTerakhir = data.updated_at_timestamp;
            statusSimpan.textContent = '✓ Tersimpan ' + data.updated_at;
            statusSimpan.className   = 'save-status saved';
            tampilkanToast('✓ Ketikan Anda berhasil dipertahankan!');
        }
    } catch (e) {
        statusSimpan.textContent = 'Gagal menyimpan';
        statusSimpan.className   = 'save-status';
    }
});

btnResolveMerge?.addEventListener('click', async () => {
    if (!dataKonflikServer) return;
    
    const posisiAwal     = editorEl.selectionStart;
    const posisiAkhir    = editorEl.selectionEnd;
    const sedangFokus    = document.activeElement === editorEl;
    const scrollTopSebelum = editorEl.scrollTop;

    const kontenTergabung = selesaikanMergeKolaboratif(kontenTerakhir, editorEl.value, dataKonflikServer.konten);
    const selisih = kontenTergabung.length - editorEl.value.length;

    editorEl.value = kontenTergabung;
    kontenTerakhir = kontenTergabung;
    
    const indeksBaruAwal  = Math.max(0, posisiAwal + selisih);
    const indeksBaruAkhir = Math.max(0, posisiAkhir + selisih);
    editorEl.setSelectionRange(indeksBaruAwal, indeksBaruAkhir);
    
    if (sedangFokus) {
        editorEl.scrollTop = scrollTopSebelum;
    }
    
    if (indeksKursorSaya !== null) {
        indeksKursorSaya = Math.max(0, indeksKursorSaya + selisih);
    }
    
    tutupKartuKonflik();
    statusSimpan.textContent = 'Menyimpan...';
    statusSimpan.className = 'save-status saving';

    try {
        const res  = await fetch(urlPerbarui, { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': tokenCsrf }, body: JSON.stringify({ konten: kontenTergabung, judul: inputJudul.value }) });
        const data = await res.json();
        if (data.success) {
            timestampTerakhir = data.updated_at_timestamp;
            statusSimpan.textContent = '✓ Tersimpan ' + data.updated_at;
            statusSimpan.className   = 'save-status saved';
            tampilkanToast('✓ Kedua ketikan berhasil digabungkan dengan rapi!');
        }
    } catch (e) {
        statusSimpan.textContent = 'Gagal menyimpan';
        statusSimpan.className   = 'save-status';
    }
});

function tutupKartuKonflik() {
    konflikSedangAktif = false;
    dataKonflikServer  = null;
    conflictCard.style.display = 'none';
}

function perbaruiDaftarOnline(daftarPengguna, idSaya) {
    if (!daftarPengguna || daftarPengguna.length === 0) {
        daftarOnline.innerHTML = '<div class="empty-sidebar">Tidak ada yang online</div>';
        return;
    }
    daftarOnline.innerHTML = daftarPengguna.map(p => {
        const warna = dapatkanWarnaUser(p.id);
        return `<div class="online-user"><div class="online-dot" style="background:${warna}"></div><span>${p.name}${p.id === idSaya ? ' <em style="color:#888;font-size:11px">(kamu)</em>' : ''}</span></div>`;
    }).join('');
}

function tampilkanKursorRemote(daftarPengguna, idSaya) {
    const overlay = document.getElementById('cursor-overlay');
    if (!overlay) return;
    const idAktif = new Set();
    (daftarPengguna || []).forEach(p => {
        if (p.id === idSaya || typeof p.indeks_kursor === 'undefined' || p.indeks_kursor === null || p.indeks_kursor === 0) return;
        idAktif.add(p.id);
        
        const koordinat = dapatkanKoordinatKursorDiIndeks(editorEl, p.indeks_kursor);
        let elKursor = document.getElementById('kursor-pengguna-' + p.id);
        if (!elKursor) {
            const warna = dapatkanWarnaUser(p.id);
            elKursor = document.createElement('div');
            elKursor.id = 'kursor-pengguna-' + p.id;
            elKursor.className = 'remote-cursor';
            elKursor.innerHTML = `<div class="remote-cursor-label" style="background:${warna}">${p.name}</div><div class="remote-cursor-caret" style="background:${warna}"></div>`;
            overlay.appendChild(elKursor);
        }
        
        elKursor.style.top  = (koordinat.atas - editorEl.scrollTop)  + 'px';
        elKursor.style.left = (koordinat.kiri - editorEl.scrollLeft) + 'px';
    });
    userTerkini = daftarPengguna;
    Array.from(overlay.children).forEach(anak => {
        if (anak.id?.startsWith('kursor-pengguna-') && !idAktif.has(parseInt(anak.id.replace('kursor-pengguna-', '')))) overlay.removeChild(anak);
    });
}

editorEl.addEventListener('scroll', () => { if (userTerkini) tampilkanKursorRemote(userTerkini, idUserSekarang); });

setInterval(cekPembaruanServer, 500);
cekPembaruanServer();

async function simpanVersi() {
    await simpanOtomatis();
    try {
        const res  = await fetch(urlVersi, { method: 'POST', headers: { 'X-CSRF-TOKEN': tokenCsrf } });
        const data = await res.json();
        if (data.success) { tampilkanToast('✓ Versi tersimpan!'); setTimeout(() => location.reload(), 1500); }
    } catch (e) { tampilkanToast('Gagal menyimpan versi', 'error'); }
}

function tampilkanToast(pesan, tipe = 'success') {
    toast.textContent = pesan;
    toast.style.background = tipe === 'success' ? '#34a853' : '#ea4335';
    toast.style.color = '#fff';
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 3000);
}

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
            document.getElementById('share-list').insertAdjacentHTML('beforeend', `<div class="share-item" id="share-item-${data.user.id}"><div class="share-avatar" style="background:${data.user.color}">${data.user.initial}</div><div class="share-user-info"><div class="sname">${data.user.name}</div><div class="semail">${data.user.email}</div></div><span class="share-perm ${kelasBadge}">${teksIzin}</span><button class="btn-remove-share" onclick="hapusAkses(${data.user.id},this)" title="Hapus akses">&times;</button></div>`);
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
