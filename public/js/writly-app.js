// ================================================
// WRITLY — Dashboard JS
// ================================================
const csrf = document.querySelector('meta[name=csrf-token]').content;

// ── THEME ────────────────────────────────────────
function applyTheme(dark) {
  document.documentElement.setAttribute('data-theme', dark ? 'dark' : 'light');
  const icon  = document.getElementById('themeIcon');
  const label = document.getElementById('themeLabel');
  if (dark) {
    if (icon)  icon.innerHTML = '<path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z" stroke="currentColor" stroke-width="2" fill="none"/>';
    if (label) label.textContent = 'Mode Terang';
  } else {
    if (icon)  icon.innerHTML = '<circle cx="12" cy="12" r="5" stroke="currentColor" stroke-width="2" fill="none"/><line x1="12" y1="1" x2="12" y2="3" stroke="currentColor" stroke-width="2"/><line x1="12" y1="21" x2="12" y2="23" stroke="currentColor" stroke-width="2"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64" stroke="currentColor" stroke-width="2"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78" stroke="currentColor" stroke-width="2"/><line x1="1" y1="12" x2="3" y2="12" stroke="currentColor" stroke-width="2"/><line x1="21" y1="12" x2="23" y2="12" stroke="currentColor" stroke-width="2"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36" stroke="currentColor" stroke-width="2"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22" stroke="currentColor" stroke-width="2"/>';
    if (label) label.textContent = 'Mode Gelap';
  }
  localStorage.setItem('writly_theme', dark ? 'dark' : 'light');
}

function toggleTheme() {
  const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
  applyTheme(!isDark);
}

// Init theme on load
(function () {
  const saved = localStorage.getItem('writly_theme');
  if (saved === 'dark') applyTheme(true);
  else if (!saved && window.matchMedia('(prefers-color-scheme: dark)').matches) applyTheme(true);
})();

// ── KEYBOARD ─────────────────────────────────────
document.addEventListener('keydown', e => {
  if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
    e.preventDefault();
    document.getElementById('searchInput')?.focus();
  }
  if (e.key === 'Escape') {
    closeRenameModal();
    closeDeleteModal();
    closeAllOpts();
  }
});

// ── SEARCH ───────────────────────────────────────
document.getElementById('searchInput')?.addEventListener('input', function () {
  const q = this.value.toLowerCase().trim();
  let visible = 0;
  document.querySelectorAll('#docsGrid .doc-card').forEach(c => {
    const match = !q || c.dataset.title.includes(q);
    c.style.display = match ? '' : 'none';
    if (match) visible++;
  });
  const empty = document.getElementById('emptyState');
  if (empty) empty.style.display = visible === 0 && q ? 'flex' : '';
});

// ── OPTIONS MENU ─────────────────────────────────
function closeAllOpts() {
  document.querySelectorAll('.opts-menu.open').forEach(m => m.classList.remove('open'));
}

function toggleOpts(btn) {
  const menu = btn.nextElementSibling;
  const wasOpen = menu.classList.contains('open');
  closeAllOpts();
  if (!wasOpen) menu.classList.add('open');
}

document.addEventListener('click', e => {
  if (!e.target.closest('.doc-opts')) closeAllOpts();
});

// ── RENAME MODAL ─────────────────────────────────
let _renameId = null;

function openRenameModal(id, cur) {
  closeAllOpts();
  _renameId = id;
  const inp = document.getElementById('renameInput');
  const btn = document.getElementById('renameOkBtn');
  inp.value = cur;
  btn.disabled = !cur.trim();
  document.getElementById('backdropRename').classList.add('show');
  document.getElementById('renameDialog').classList.add('show');
  setTimeout(() => { inp.focus(); inp.select(); }, 80);
}

function closeRenameModal() {
  document.getElementById('backdropRename')?.classList.remove('show');
  document.getElementById('renameDialog')?.classList.remove('show');
  _renameId = null;
}

function submitRename() {
  const name = document.getElementById('renameInput').value.trim();
  if (!name || !_renameId) return closeRenameModal();
  const btn = document.getElementById('renameOkBtn');
  btn.disabled = true; btn.textContent = 'Menyimpan...';
  fetch(`/documents/${_renameId}/rename`, {
    method: 'POST', credentials: 'include',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
    body: JSON.stringify({ title: name }),
  }).then(r => {
    if (r.ok) { closeRenameModal(); location.reload(); }
    else { btn.disabled = false; btn.textContent = 'Simpan'; }
  }).catch(() => { btn.disabled = false; btn.textContent = 'Simpan'; });
}

document.getElementById('renameInput')?.addEventListener('keydown', e => {
  if (e.key === 'Enter') { e.preventDefault(); submitRename(); }
  if (e.key === 'Escape') closeRenameModal();
});
document.getElementById('renameInput')?.addEventListener('input', function () {
  document.getElementById('renameOkBtn').disabled = !this.value.trim();
});

// ── DELETE MODAL ─────────────────────────────────
let _deleteId = null;

function openDeleteModal(id, title) {
  closeAllOpts();
  _deleteId = id;
  document.getElementById('deleteMsg').textContent = `"${title}" akan dihapus permanen dan tidak bisa dikembalikan.`;
  document.getElementById('backdropDelete').classList.add('show');
  document.getElementById('deleteDialog').classList.add('show');
  setTimeout(() => document.getElementById('deleteOkBtn')?.focus(), 80);
}

function closeDeleteModal() {
  document.getElementById('backdropDelete')?.classList.remove('show');
  document.getElementById('deleteDialog')?.classList.remove('show');
  _deleteId = null;
}

function submitDelete() {
  if (!_deleteId) return;
  const btn = document.getElementById('deleteOkBtn');
  btn.disabled = true; btn.textContent = 'Menghapus...';
  document.getElementById(`delForm-${_deleteId}`)?.submit();
}

// ── VIEW TOGGLE ───────────────────────────────────
let _listMode = false;
function setView(mode) {
  _listMode = mode === 'list';
  document.getElementById('docsGrid')?.classList.toggle('list-view', _listMode);
  document.getElementById('btnGrid')?.classList.toggle('active', !_listMode);
  document.getElementById('btnList')?.classList.toggle('active', _listMode);
  localStorage.setItem('writly_view', mode);
}
// Restore view
(function() {
  if (localStorage.getItem('writly_view') === 'list') setView('list');
})();

// ── SORT ─────────────────────────────────────────
let _sortAZ = false;
function sortDocs() {
  _sortAZ = !_sortAZ;
  const label = document.getElementById('sortLabel');
  if (label) label.textContent = _sortAZ ? 'A–Z' : 'Terbaru';
  const grid  = document.getElementById('docsGrid');
  const cards = Array.from(grid?.querySelectorAll('.doc-card') ?? []);
  cards.sort((a, b) => _sortAZ
    ? (a.dataset.title ?? '').localeCompare(b.dataset.title ?? '')
    : parseInt(b.dataset.date ?? 0) - parseInt(a.dataset.date ?? 0));
  cards.forEach(c => grid?.appendChild(c));
}

// ── SCROLL TO SECTION ────────────────────────────
function scrollToSection(id) {
  document.getElementById(id)?.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

// ── EXPOSE GLOBALS ────────────────────────────────
window.toggleTheme      = toggleTheme;
window.toggleOpts       = toggleOpts;
window.openRenameModal  = openRenameModal;
window.closeRenameModal = closeRenameModal;
window.submitRename     = submitRename;
window.openDeleteModal  = openDeleteModal;
window.closeDeleteModal = closeDeleteModal;
window.submitDelete     = submitDelete;
window.setView          = setView;
window.sortDocs         = sortDocs;
window.scrollToSection  = scrollToSection;
