// ===================================
// GOOGLE DOCS CLONE - INDEX PAGE JS
// ===================================

// Search functionality
document.getElementById('searchInput').addEventListener('input', function () {
  const q = this.value.toLowerCase();
  document.querySelectorAll('#docsGrid>.doc-card').forEach((c) => {
    c.style.display = !q || c.dataset.title.includes(q) ? '' : 'none';
  });
});

// Toggle context menu
function toggleCtx(btn) {
  const wrap = btn.closest('.doc-more-wrap');
  const m = wrap.querySelector('.ctx-menu');
  const open = m.classList.contains('on');
  document.querySelectorAll('.ctx-menu.on').forEach((x) => x.classList.remove('on'));
  if (!open) {
    m.classList.add('on');
  }
}

// Close context menu when clicking outside
document.addEventListener('click', function (e) {
  if (!e.target.closest('.doc-more-wrap'))
    document.querySelectorAll('.ctx-menu.on').forEach((x) => x.classList.remove('on'));
});

// ── RENAME MODAL ──────────────────────────────────────────────────
let _renameId = null,
  _renameCur = null;

function openRenameModal(id, cur) {
  document.querySelectorAll('.ctx-menu.on').forEach((x) => x.classList.remove('on'));
  _renameId = id;
  _renameCur = cur;
  const inp = document.getElementById('renameInput');
  const btn = document.getElementById('renameOkBtn');
  inp.value = cur;
  btn.disabled = false;
  document.getElementById('renameModal').classList.add('show');
  // Pastikan input langsung ter-select dan fokus
  setTimeout(() => {
    inp.focus();
    inp.select();
  }, 100);
}

function closeRenameModal() {
  document.getElementById('renameModal').classList.remove('show');
  _renameId = null;
}

function submitRename() {
  const n = document.getElementById('renameInput').value.trim();
  if (!n) {
    closeRenameModal();
    return;
  }
  if (n === _renameCur) {
    closeRenameModal();
    return;
  }
  const btn = document.getElementById('renameOkBtn');
  btn.disabled = true;
  btn.textContent = 'Oke';
  const csrf = document.querySelector('meta[name=csrf-token]').content;
  fetch('/documents/' + _renameId + '/rename', {
    method: 'POST',
    credentials: 'include',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': csrf,
      Accept: 'application/json',
    },
    body: JSON.stringify({ title: n }),
  })
    .then((r) => {
      if (r.ok) {
        closeRenameModal();
        location.reload();
      } else {
        btn.disabled = false;
        btn.textContent = 'Oke';
        alert('Gagal menyimpan.');
      }
    })
    .catch(() => {
      btn.disabled = false;
      btn.textContent = 'Oke';
    });
}

document.getElementById('renameInput').addEventListener('keydown', function (e) {
  if (e.key === 'Enter') {
    e.preventDefault();
    submitRename();
  }
  if (e.key === 'Escape') {
    e.preventDefault();
    closeRenameModal();
  }
});

document.getElementById('renameInput').addEventListener('input', function () {
  const btn = document.getElementById('renameOkBtn');
  btn.disabled = !this.value.trim();
});

document.getElementById('renameModal').addEventListener('click', function (e) {
  if (e.target === this) closeRenameModal();
});

// ── DELETE MODAL ──────────────────────────────────────────────────
let _deleteId = null;

function openDeleteModal(id, title) {
  document.querySelectorAll('.ctx-menu.on').forEach((x) => x.classList.remove('on'));
  _deleteId = id;
  document.getElementById('deleteModalMsg').textContent =
    '"' + title + '" akan dihapus secara permanen.';
  document.getElementById('deleteModal').classList.add('show');
  setTimeout(() => document.getElementById('deleteOkBtn').focus(), 50);
}

function closeDeleteModal() {
  document.getElementById('deleteModal').classList.remove('show');
  _deleteId = null;
}

function submitDelete() {
  if (!_deleteId) return;
  const btn = document.getElementById('deleteOkBtn');
  btn.disabled = true;
  btn.textContent = 'Menghapus...';
  document.getElementById('delForm-' + _deleteId).submit();
}

document.getElementById('deleteModal').addEventListener('click', function (e) {
  if (e.target === this) closeDeleteModal();
});

document.getElementById('deleteModal').addEventListener('keydown', function (e) {
  if (e.key === 'Escape') {
    e.preventDefault();
    closeDeleteModal();
  }
});

// ── VIEW & SORT ───────────────────────────────────────────────────
let listMode = false;

function setView(m) {
  listMode = m === 'list';
  document.getElementById('btnGrid').classList.toggle('on', !listMode);
  document.getElementById('btnList').classList.toggle('on', listMode);
  const g = document.getElementById('docsGrid');
  if (listMode) {
    g.style.cssText = 'display:flex;flex-direction:column;gap:0;';
    document.querySelectorAll('.doc-card').forEach((c) => {
      c.style.cssText =
        'display:flex;border-radius:0;border-left:none;border-right:none;border-top:none;';
      const p = c.querySelector('.doc-preview');
      if (p) p.style.display = 'none';
    });
  } else {
    g.style.cssText = '';
    document.querySelectorAll('.doc-card').forEach((c) => {
      c.style.cssText = '';
      const p = c.querySelector('.doc-preview');
      if (p) p.style.display = '';
    });
  }
}

let az = false;

function sortDocs() {
  az = !az;
  const g = document.getElementById('docsGrid');
  const btn = document.getElementById('btnAZ');
  btn.innerHTML = `<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M10 18h4v-2h-4v2zM3 6v2h18V6H3zm3 7h12v-2H6v2z"/></svg> ${az ? 'A → Z' : 'Terakhir dibuka'}`;
  Array.from(g.querySelectorAll('.doc-card'))
    .sort((a, b) =>
      az
        ? (a.dataset.title || '').localeCompare(b.dataset.title || '')
        : parseInt(b.dataset.date || 0) - parseInt(a.dataset.date || 0)
    )
    .forEach((c) => g.appendChild(c));
}

// Export functions for inline onclick handlers
window.toggleCtx = toggleCtx;
window.openRenameModal = openRenameModal;
window.closeRenameModal = closeRenameModal;
window.submitRename = submitRename;
window.openDeleteModal = openDeleteModal;
window.closeDeleteModal = closeDeleteModal;
window.submitDelete = submitDelete;
window.setView = setView;
window.sortDocs = sortDocs;
