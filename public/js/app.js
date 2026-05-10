// public/js/app.js — MIS Jalan Global JS

/* ====================================================
   PENGAJUAN — Tabel actions
   ==================================================== */

/**
 * Buka modal detail pengajuan via AJAX
 */
async function openDetail(id) {
  openModal(
    "Memuat…",
    '<div class="state-empty"><div class="spinner"></div></div>',
  );
  try {
    const res = await fetch(`${APP_URL}/api/pengajuan.php?id=${id}`);
    const data = await res.json();
    if (!res.ok) throw new Error(data.error || "Gagal memuat data.");

    const fotoHtml = data.foto
      ? `<div class="detail-item full"><span class="detail-label">Foto</span>
           <img src="${APP_URL}/assets/uploads/${data.foto}" class="modal-foto" alt="Foto kerusakan" /></div>`
      : "";

    const adminActions = IS_ADMIN
      ? `
      <div class="modal-actions">
        <div class="status-select-wrap">
          <select id="statusSelect" style="flex:1">
            ${["Pending", "Diproses", "Selesai", "Ditolak"]
              .map(
                (s) =>
                  `<option value="${s}" ${data.status === s ? "selected" : ""}>${s}</option>`,
              )
              .join("")}
          </select>
          <input type="text" id="catatanInput" placeholder="Catatan admin (opsional)" style="flex:2" value="${data.catatan_admin || ""}" />
          <button class="btn btn-primary btn-sm" onclick="updateStatus(${data.id})">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <polyline points="20,6 9,17 4,12" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Simpan
          </button>
          <button class="btn btn-danger btn-sm" onclick="deleteItem(${data.id})">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <polyline points="3,6 5,6 21,6"/><path d="M19,6l-1,14H6L5,6" stroke-linecap="round"/>
              <path d="M10,11v6M14,11v6" stroke-linecap="round"/>
              <path d="M9,6V4h6v2" stroke-linecap="round"/>
            </svg>
            Hapus
          </button>
        </div>
      </div>`
      : "";

    openModal(
      `Detail — ${data.no_pengajuan}`,
      `
      <div class="detail-grid">
        <div class="detail-item">
          <span class="detail-label">No. Pengajuan</span>
          <span class="detail-value text-mono text-blue">${data.no_pengajuan}</span>
        </div>
        <div class="detail-item">
          <span class="detail-label">Status</span>
          <span class="detail-value">${statusBadge(data.status)}</span>
        </div>
        <div class="detail-item">
          <span class="detail-label">Nama Pelapor</span>
          <span class="detail-value">${data.nama_pelapor}</span>
        </div>
        <div class="detail-item">
          <span class="detail-label">No. HP</span>
          <span class="detail-value">${data.no_hp || "—"}</span>
        </div>
        <div class="detail-item full">
          <span class="detail-label">Lokasi</span>
          <span class="detail-value">${data.lokasi}</span>
        </div>
        <div class="detail-item">
          <span class="detail-label">Kecamatan</span>
          <span class="detail-value">${data.kecamatan}</span>
        </div>
        <div class="detail-item">
          <span class="detail-label">Kelurahan</span>
          <span class="detail-value">${data.kelurahan}</span>
        </div>
        <div class="detail-item">
          <span class="detail-label">Jenis Kerusakan</span>
          <span class="detail-value">${data.jenis_kerusakan}</span>
        </div>
        <div class="detail-item">
          <span class="detail-label">Tingkat Kerusakan</span>
          <span class="detail-value">${tingkatBadge(data.tingkat_kerusakan)}</span>
        </div>
        <div class="detail-item">
          <span class="detail-label">Jumlah Lubang</span>
          <span class="detail-value">${data.num_potholes || 0} lubang</span>
        </div>
        <div class="detail-item">
          <span class="detail-label">Tanggal Pengajuan</span>
          <span class="detail-value">${formatDate(data.created_at)}</span>
        </div>
        ${
          data.deskripsi
            ? `<div class="detail-item full">
          <span class="detail-label">Deskripsi</span>
          <span class="detail-value">${data.deskripsi}</span>
        </div>`
            : ""
        }
        ${
          data.catatan_admin
            ? `<div class="detail-item full">
          <span class="detail-label">Catatan Admin</span>
          <span class="detail-value">${data.catatan_admin}</span>
        </div>`
            : ""
        }
        ${fotoHtml}
        <div class="detail-item">
          <span class="detail-label">Diajukan Oleh</span>
          <span class="detail-value">${data.nama_user || "—"}</span>
        </div>
      </div>
      ${adminActions}
    `,
    );
  } catch (e) {
    openModal(
      "Error",
      `<div class="alert alert-error"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="9"/><path d="M12 8v4M12 16h.01" stroke-linecap="round"/></svg>${e.message}</div>`,
    );
  }
}

async function updateStatus(id) {
  const status = document.getElementById("statusSelect")?.value;
  const catatan = document.getElementById("catatanInput")?.value || "";
  if (!status) return;
  try {
    const fd = new FormData();
    fd.append("id", id);
    fd.append("status", status);
    fd.append("catatan", catatan);
    fd.append("csrf_token", CSRF);
    const res = await fetch(`${APP_URL}/api/status.php`, {
      method: "POST",
      body: fd,
    });
    const data = await res.json();
    if (!res.ok) throw new Error(data.error);
    closeModal();
    showToast("Status berhasil diperbarui!", "success");
    setTimeout(() => location.reload(), 900);
  } catch (e) {
    showToast(e.message || "Gagal memperbarui status.", "error");
  }
}

async function deleteItem(id) {
  if (!confirm("Yakin ingin menghapus pengajuan ini?")) return;
  try {
    const fd = new FormData();
    fd.append("id", id);
    fd.append("csrf_token", CSRF);
    const res = await fetch(`${APP_URL}/api/delete.php`, {
      method: "POST",
      body: fd,
    });
    const data = await res.json();
    if (!res.ok) throw new Error(data.error);
    closeModal();
    showToast("Pengajuan berhasil dihapus.", "success");
    setTimeout(() => location.reload(), 900);
  } catch (e) {
    showToast(e.message || "Gagal menghapus.", "error");
  }
}

/* ====================================================
   FORM TAMBAH — file preview
   ==================================================== */

function previewFoto(input) {
  const file = input.files[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = (e) => {
    let img = document.getElementById("fotoPreview");
    if (!img) {
      img = document.createElement("img");
      img.id = "fotoPreview";
      img.className = "file-preview";
      input.closest(".file-upload").appendChild(img);
    }
    img.src = e.target.result;
    document.querySelector(".file-upload-text").innerHTML =
      `<strong>${file.name}</strong>`;
  };
  reader.readAsDataURL(file);
}

/* ====================================================
   FORM TAMBAH — submit AJAX
   ==================================================== */

async function submitPengajuan(e) {
  e.preventDefault();
  const form = e.target;
  const btn = form.querySelector('button[type="submit"]');
  btn.disabled = true;
  btn.innerHTML = '<span class="spinner"></span> Menyimpan…';

  try {
    const fd = new FormData(form);
    fd.append("csrf_token", CSRF);
    const res = await fetch(`${APP_URL}/tambah.php`, {
      method: "POST",
      body: fd,
      headers: { "X-Requested-With": "XMLHttpRequest" },
    });
    const data = await res.json();
    if (!res.ok) throw new Error(data.error);

    showToast(`Pengajuan ${data.no_pengajuan} berhasil dikirim!`, "success");
    form.reset();
    document.getElementById("fotoPreview")?.remove();
    document.querySelector(".file-upload-text").innerHTML =
      '<strong>Klik untuk upload</strong> atau drag & drop<br><span style="font-size:.72rem">JPG, PNG, WebP • Maks 5MB</span>';
  } catch (e) {
    showToast(e.message || "Gagal menyimpan.", "error");
  } finally {
    btn.disabled = false;
    btn.innerHTML =
      '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round"/></svg> Kirim Pengajuan';
  }
}

/* ====================================================
   UTILS
   ==================================================== */

function formatDate(str) {
  if (!str) return "—";
  const d = new Date(str);
  return d.toLocaleDateString("id-ID", {
    day: "2-digit",
    month: "short",
    year: "numeric",
  });
}

// Mobile sidebar toggle
document.addEventListener("DOMContentLoaded", () => {
  // Create mobile toggle if needed
  if (window.innerWidth <= 768) {
    const btn = document.createElement("button");
    btn.id = "sidebarToggle";
    btn.style.cssText =
      "position:fixed;top:14px;left:14px;z-index:150;width:38px;height:38px;border-radius:8px;background:var(--surface);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;cursor:pointer;box-shadow:var(--shadow-sm)";
    btn.innerHTML =
      '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12h18M3 6h18M3 18h18" stroke-linecap="round"/></svg>';
    btn.onclick = () =>
      document.getElementById("sidebar")?.classList.toggle("open");
    document.body.appendChild(btn);

    // Close on overlay click
    document.addEventListener("click", (e) => {
      const sidebar = document.getElementById("sidebar");
      const toggle = document.getElementById("sidebarToggle");
      if (
        sidebar?.classList.contains("open") &&
        !sidebar.contains(e.target) &&
        e.target !== toggle
      ) {
        sidebar.classList.remove("open");
      }
    });
  }
});
