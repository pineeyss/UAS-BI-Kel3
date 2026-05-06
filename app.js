// =============================================
//  SHARED UTILITIES — Monitoring Perbaikan Jalan
// =============================================

// ---- Auth ----
function getUser() {
  const u = sessionStorage.getItem('mpj_user');
  return u ? JSON.parse(u) : null;
}

function requireLogin() {
  if (!getUser()) {
    window.location.href = 'login.html';
    return false;
  }
  return true;
}

function logout() {
  sessionStorage.removeItem('mpj_user');
  window.location.href = 'login.html';
}

function isAdmin() {
  const u = getUser();
  return u && u.role === 'admin';
}

// ---- Data ----
function getData() {
  const d = localStorage.getItem('mpj_pengajuan');
  return d ? JSON.parse(d) : [];
}

function saveData(arr) {
  localStorage.setItem('mpj_pengajuan', JSON.stringify(arr));
}

function genNoPengajuan() {
  const now = new Date();
  const y = now.getFullYear();
  const m = String(now.getMonth() + 1).padStart(2, '0');
  const all = getData();
  const thisMonth = all.filter(x => {
    const d = new Date(x.created_at);
    return d.getFullYear() === y && d.getMonth() + 1 === parseInt(m);
  });
  const num = String(thisMonth.length + 1).padStart(4, '0');
  return `PBJ-${y}${m}-${num}`;
}

// ---- Render helpers ----
function renderBadgeStatus(status) {
  const map = {
    'Pending': 'badge-pending',
    'Diproses': 'badge-diproses',
    'Selesai': 'badge-selesai',
    'Ditolak': 'badge-ditolak'
  };
  return `<span class="badge ${map[status] || ''}">${status}</span>`;
}

function renderBadgeTingkat(tingkat) {
  const map = { 'Ringan': 'badge-ringan', 'Sedang': 'badge-sedang', 'Berat': 'badge-berat' };
  return `<span class="badge ${map[tingkat] || ''}">${tingkat}</span>`;
}

function fmtDate(iso) {
  if (!iso) return '-';
  const d = new Date(iso);
  return d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
}

function fmtDateFull(iso) {
  if (!iso) return '-';
  const d = new Date(iso);
  return d.toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' });
}

// ---- Sidebar renderer ----
function renderSidebar(activePage) {
  const user = getUser();
  if (!user) return;

  const links = [
    { href: 'dashboard.html', icon: `<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9,22 9,12 15,12 15,22"/>`, label: 'Dashboard', page: 'dashboard' },
    { href: 'pengajuan.html', icon: `<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14,2 14,8 20,8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10,9 9,9 8,9"/>`, label: 'Data Pengajuan', page: 'pengajuan' },
    { href: 'tambah.html', icon: `<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/>`, label: 'Tambah Pengajuan', page: 'tambah' },
  ];

  let adminLinks = '';
  if (user.role === 'admin') {
    adminLinks = `
      <div class="sb-section">Admin</div>
      <a href="users.html" class="sb-link ${activePage === 'users' ? 'active' : ''}">
        <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        Kelola Users
      </a>
    `;
  }

  const html = `
    <div class="sb-brand">
      <div class="sb-brand-icon">
        <svg viewBox="0 0 24 24"><path d="M3 17l3-10 3 5 3-8 3 6 3-3"/><rect x="1" y="17" width="22" height="4" rx="1"/></svg>
      </div>
      <div class="sb-brand-text">
        <h3>Monitoring<br>Perbaikan Jalan</h3>
        <span>MIS System</span>
      </div>
    </div>
    <div class="sb-nav">
      <div class="sb-section">Menu Utama</div>
      ${links.map(l => `
        <a href="${l.href}" class="sb-link ${activePage === l.page ? 'active' : ''}">
          <svg viewBox="0 0 24 24">${l.icon}</svg>
          ${l.label}
        </a>
      `).join('')}
      ${adminLinks}
    </div>
    <div class="sb-footer">
      <div class="sb-user">
        <div class="sb-avatar">${user.nama.charAt(0).toUpperCase()}</div>
        <div class="sb-user-info">
          <h4>${user.nama}</h4>
          <span>${user.role}</span>
        </div>
      </div>
      <button class="btn-logout" onclick="logout()">
        <svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16,17 21,12 16,7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
        Keluar dari Sistem
      </button>
    </div>
  `;
  document.getElementById('sidebar').innerHTML = html;
}

function renderTopbar(title, subtitle) {
  const user = getUser();
  const now = new Date().toLocaleDateString('id-ID', { weekday: 'short', day: '2-digit', month: 'long', year: 'numeric' });
  document.getElementById('topbar').innerHTML = `
    <div class="topbar-left">
      <h2>${title}</h2>
      ${subtitle ? `<p>${subtitle}</p>` : ''}
    </div>
    <div class="topbar-right">
      <span class="topbar-date">${now}</span>
      <span class="role-badge role-${user.role}">${user.role}</span>
    </div>
  `;
}