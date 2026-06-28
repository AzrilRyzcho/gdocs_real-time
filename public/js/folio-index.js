// ====================================================
// FOLIO — Index Page JavaScript
// ====================================================

const csrf = document.querySelector('meta[name=csrf-token]').content;

// ── KEYBOARD SHORTCUT: ⌘K / Ctrl+K untuk search ────
document.addEventListener('keydown', e => {
  if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
    e.preventDefault();
    document.getElementById('searchInput')?.focus();
  }
  if (e.key === 'Escape') {
    closeRenameModal();
    closeDeleteModal();
    closeAllCtx();
    const panel = document.getElementById('userPanel');
    if (panel) panel.classList.remove('show');
  }
});

// ── SEARCH ──────────────────────────────────────────
document.getElementById('searchInput')?.addEventListener('input', function () {
  const q = this.value.toLowerCase().trim();
  document.querySelectorAll('#docsGrid .doc-card').forEach(card => {
    const match = !q || card.dataset.title.includes(q);
    card.style.display = match ? '' : 'none';
  });

  const empty   = document.getElementById('emptyState');
  const visible = document.querySelectorAll('#docsGrid .doc-card:not([style*="none"])').length;
  if (empty) empty.style.display = visible === 0 ? 'flex' : '';
});

// ── CONTEXT MENU ─────────────────────────────────────
function closeAllCtx() {
  document.querySelectorAll('.ctx-menu.open').forEach(m => m.classList.remove('open'));
}

function toggleCtx(btn) {
  const menu = btn.nextElementSibling;
  const isOpen = menu.classList.contains('open');
  closeAllCtx();
  if (!isOpen) menu.classList.add('open');
}

document.addEventListener('click', e => {
  if (!e.target.closest('.doc-more')) closeAllCtx();
  if (!e.target.closest('#avatarWrap')) {
    document.getElementById('userPanel')?.classList.remove('show');
  }
});

// ── USER PANEL ───────────────────────────────────────
function toggleUserMenu() {
  document.getElementById('userPanel')?.classList.toggle('show');
}

// ── RENAME MODAL ─────────────────────────────────────
let _renameId = null;

function openRenameModal(id, current) {
  closeAllCtx();
  _renameId = id;
  const input = document.getElementById('renameInput');
  const btn   = document.getElementById('renameOkBtn');
  input.value = current;
  btn.disabled = false;
  document.getElementById('renameOverlay').classList.add('show');
  document.getElementById('renameModal').classList.add('show');
  setTimeout(() => { input.focus(); input.select(); }, 80);
}

function closeRenameModal() {
  document.getElementById('renameOverlay').classList.remove('show');
  document.getElementById('renameModal').classList.remove('show');
  _renameId = null;
}

function submitRename() {
  const name = document.getElementById('renameInput').value.trim();
  if (!name || !_renameId) { closeRenameModal(); return; }
  const btn = document.getElementById('renameOkBtn');
  btn.disabled = true;
  fetch(`/documents/${_renameId}/rename`, {
    method: 'POST',
    credentials: 'include',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
    body: JSON.stringify({ title: name })
  }).then(r => {
    if (r.ok) { closeRenameModal(); location.reload(); }
    else { btn.disabled = false; }
  }).catch(() => { btn.disabled = false; });
}

document.getElementById('renameInput')?.addEventListener('keydown', e => {
  if (e.key === 'Enter') { e.preventDefault(); submitRename(); }
  if (e.key === 'Escape') closeRenameModal();
});
document.getElementById('renameInput')?.addEventListener('input', function () {
  document.getElementById('renameOkBtn').disabled = !this.value.trim();
});

// ── DELETE MODAL ─────────────────────────────────────
let _deleteId = null;

function openDeleteModal(id, title) {
  closeAllCtx();
  _deleteId = id;
  document.getElementById('deleteModalMsg').textContent =
    `"${title}" akan dihapus permanen dan tidak bisa dikembalikan.`;
  document.getElementById('deleteOverlay').classList.add('show');
  document.getElementById('deleteModal').classList.add('show');
  setTimeout(() => document.getElementById('deleteOkBtn')?.focus(), 80);
}

function closeDeleteModal() {
  document.getElementById('deleteOverlay').classList.remove('show');
  document.getElementById('deleteModal').classList.remove('show');
  _deleteId = null;
}

function submitDelete() {
  if (!_deleteId) return;
  const btn = document.getElementById('deleteOkBtn');
  btn.disabled = true;
  btn.textContent = 'Menghapus...';
  document.getElementById(`delForm-${_deleteId}`)?.submit();
}

document.getElementById('deleteModal')?.addEventListener('keydown', e => {
  if (e.key === 'Escape') closeDeleteModal();
});

// ── VIEW TOGGLE ───────────────────────────────────────
let _listMode = false;

function setView(mode) {
  _listMode = mode === 'list';
  const grid    = document.getElementById('docsGrid');
  const btnGrid = document.getElementById('btnGrid');
  const btnList = document.getElementById('btnList');
  grid?.classList.toggle('list-view', _listMode);
  btnGrid?.classList.toggle('active', !_listMode);
  btnList?.classList.toggle('active', _listMode);
}

// ── SORT ──────────────────────────────────────────────
let _sortAZ = false;

function sortDocs() {
  _sortAZ = !_sortAZ;
  const grid  = document.getElementById('docsGrid');
  const label = document.getElementById('sortLabel');
  if (label) label.textContent = _sortAZ ? 'A–Z' : 'Terbaru';

  const cards = Array.from(grid?.querySelectorAll('.doc-card') ?? []);
  cards.sort((a, b) => _sortAZ
    ? (a.dataset.title ?? '').localeCompare(b.dataset.title ?? '')
    : parseInt(b.dataset.date ?? 0) - parseInt(a.dataset.date ?? 0)
  );
  cards.forEach(c => grid?.appendChild(c));
}

// ── EXPOSE GLOBALS ────────────────────────────────────
window.toggleCtx       = toggleCtx;
window.toggleUserMenu  = toggleUserMenu;
window.openRenameModal = openRenameModal;
window.closeRenameModal= closeRenameModal;
window.submitRename    = submitRename;
window.openDeleteModal = openDeleteModal;
window.closeDeleteModal= closeDeleteModal;
window.submitDelete    = submitDelete;
window.setView         = setView;
window.sortDocs        = sortDocs;
