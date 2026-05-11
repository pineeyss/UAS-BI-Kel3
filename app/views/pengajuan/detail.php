pengajuan_detail = "<?php
                        /** @var array $item */
                        $item = $item ?? [];

                        require ROOT . '/app/views/partials/header.php';
                        ?>

<div class="row-2col">
    <div class="card">
        <div class="card-header">
            <h3><i class="fa-solid fa-file-lines"></i> Detail Laporan #<?= $item['id'] ?? '-' ?></h3>
            <div class="header-actions">
                <span class="badge status-<?= strtolower($item['statuslaporan'] ?? '') ?> badge-lg">
                    <?= ucfirst($item['statuslaporan'] ?? '') ?>
                </span>
                <a href="<?= BASE_URL ?>pengajuan" class="btn btn-outline btn-sm">
                    <i class="fa-solid fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="detail-grid">
                <div class="detail-item form-full">
                    <span class="detail-label"><i class="fa-solid fa-road"></i> Nama Jalan</span>
                    <span class="detail-value"><?= htmlspecialchars($item['nama_jalan'] ?? '') ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label"><i class="fa-solid fa-map-pin"></i> Latitude</span>
                    <span class="detail-value"><?= $item['latitude'] ?? '-' ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label"><i class="fa-solid fa-map-pin"></i> Longitude</span>
                    <span class="detail-value"><?= $item['longitude'] ?? '-' ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label"><i class="fa-solid fa-calendar"></i> Tanggal Masuk</span>
                    <span class="detail-value"><?= isset($item['created_at']) ? date('d F Y, H:i', strtotime($item['created_at'])) : '-' ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label"><i class="fa-solid fa-circle-dot"></i> Status</span>
                    <span class="detail-value">
                        <span class="badge status-<?= strtolower($item['statuslaporan'] ?? '') ?>">
                            <?= ucfirst($item['statuslaporan'] ?? '') ?>
                        </span>
                    </span>
                </div>
            </div>

            <?php if (!empty($item['foto_path'])): ?>
                <div class="foto-section">
                    <h4><i class="fa-solid fa-image"></i> Foto Dokumentasi</h4>
                    <img src="<?= BASE_URL ?><?= htmlspecialchars($item['foto_path']) ?>" alt="Foto jalan" class="foto-full">
                </div>
            <?php endif; ?>

            <div class="foto-section">
                <h4><i class="fa-solid fa-map-location-dot"></i> Lokasi di Peta</h4>
                <div id="minimap" style="height:260px;border-radius:8px;"></div>
            </div>
        </div>
    </div>

    <?php if (($_SESSION['role'] ?? '') === 'admin'): ?>
        <div class="card">
            <div class="card-header">
                <h3><i class="fa-solid fa-sliders"></i> Update Status</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="<?= BASE_URL ?>pengajuan/status/<?= $item['id'] ?? '' ?>">
                    <div class="form-group">
                        <label>Status Laporan</label>
                        <select name="status" class="form-control">
                            <?php foreach (['diterima', 'diperbaiki', 'selesai', 'ditolak'] as $s): ?>
                                <option value="<?= $s ?>" <?= ($item['statuslaporan'] ?? '') === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Status
                    </button>
                </form>
                <div style="margin-top:1rem;border-top:1px solid var(--border);padding-top:1rem">
                    <a href="<?= BASE_URL ?>pengajuan/edit/<?= $item['id'] ?? '' ?>" class="btn btn-warning btn-block">
                        <i class="fa-solid fa-pen"></i> Edit Data
                    </a>
                    <a href="<?= BASE_URL ?>pengajuan/delete/<?= $item['id'] ?? '' ?>" class="btn btn-danger btn-block"
                        style="margin-top:.5rem" onclick="return confirm('Yakin hapus data ini?')">
                        <i class="fa-solid fa-trash"></i> Hapus Data
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php
$lat = (float)($item['latitude']  ?? 0);
$lng = (float)($item['longitude'] ?? 0);
$namaJalan = addslashes(htmlspecialchars($item['nama_jalan'] ?? ''));
$extraScript = "
<link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css\">
<script src=\"https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js\"></script>
<script>
const lat = {$lat};
const lng = {$lng};
const map = L.map('minimap').setView([lat, lng], 14);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);
L.marker([lat, lng]).addTo(map)
    .bindPopup('<b>{$namaJalan}</b>')
    .openPopup();
</script>";
?>

<?php require ROOT . '/app/views/partials/footer.php'; ?>
"""