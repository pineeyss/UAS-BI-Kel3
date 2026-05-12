<?php

/** @var array $item */
$item = $item ?? [];

$role = $_SESSION['role'] ?? '';
$lat  = (float)($item['latitude']  ?? 0);
$lng  = (float)($item['longitude'] ?? 0);

$statusColor = match ($item['statuslaporan'] ?? '') {
    'selesai'    => ['bg' => '#f0fdf4', 'color' => '#166534'],
    'diperbaiki' => ['bg' => '#fff7ed', 'color' => '#c2410c'],
    'ditolak'    => ['bg' => '#fef2f2', 'color' => '#991b1b'],
    default      => ['bg' => '#eff6ff', 'color' => '#1e40af'],
};

// $extraHead HARUS didefinisikan sebelum require header.php agar CSS termuat
$extraHead = '
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css">
<style>#minimap{height:260px;border-radius:var(--radius-sm);border:1px solid var(--border);}</style>';

require ROOT . '/app/views/partials/header.php';
?>

<!-- Breadcrumb -->
<div style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--text-muted);margin-bottom:20px;">
    <a href="<?= BASE_URL ?>pengajuan" style="color:var(--brand);">Laporan</a>
    <i class="fa-solid fa-chevron-right" style="font-size:10px;"></i>
    <span>Detail #<?= $item['id'] ?? '-' ?></span>
</div>

<div class="row-2col" style="align-items:start;">

    <!-- Kolom Kiri: Info Laporan -->
    <div>
        <div class="card">
            <div class="card-header">
                <h3><i class="fa-solid fa-file-lines"></i> Detail Laporan #<?= $item['id'] ?? '-' ?></h3>
                <div class="header-actions">
                    <span class="badge badge-lg" style="background:<?= $statusColor['bg'] ?>;color:<?= $statusColor['color'] ?>;">
                        <?= ucfirst($item['statuslaporan'] ?? '') ?>
                    </span>
                </div>
            </div>
            <div class="card-body">
                <!-- Info dasar -->
                <div class="detail-grid" style="margin-bottom:20px;">
                    <div class="detail-item" style="grid-column:1/-1;">
                        <span class="detail-label"><i class="fa-solid fa-road"></i> Nama Jalan</span>
                        <span class="detail-value" style="font-size:16px;font-weight:700;"><?= htmlspecialchars($item['nama_jalan'] ?? '') ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label"><i class="fa-solid fa-triangle-exclamation"></i> Tingkat Kerusakan</span>
                        <span class="detail-value">
                            <?php if (!empty($item['tingkat_kerusakan'])): ?>
                                <span class="badge" style="background:<?= match ($item['tingkat_kerusakan']) {
                                                                            'berat' => '#fef2f2',
                                                                            'sedang' => '#fff7ed',
                                                                            default => '#f0fdf4'
                                                                        } ?>;color:<?= match ($item['tingkat_kerusakan']) {
                                                                                        'berat' => '#991b1b',
                                                                                        'sedang' => '#c2410c',
                                                                                        default => '#166534'
                                                                                    } ?>;font-size:13px;padding:4px 12px;">
                                    <?= ucfirst($item['tingkat_kerusakan']) ?>
                                </span>
                            <?php else: ?>
                                <span style="color:var(--text-light);">Belum diklasifikasikan</span>
                            <?php endif; ?>
                        </span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label"><i class="fa-solid fa-calendar"></i> Tanggal Laporan</span>
                        <span class="detail-value"><?= isset($item['created_at']) ? date('d F Y, H:i', strtotime($item['created_at'])) : '—' ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label"><i class="fa-solid fa-map-pin"></i> Koordinat</span>
                        <span class="detail-value" style="font-size:13px;font-family:monospace;"><?= $lat ?>, <?= $lng ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label"><i class="fa-solid fa-clock-rotate-left"></i> Diverifikasi</span>
                        <span class="detail-value"><?= $item['verified_at'] ? date('d F Y, H:i', strtotime($item['verified_at'])) : '—' ?></span>
                    </div>
                </div>

                <!-- Deskripsi -->
                <?php if (!empty($item['deskripsi'])): ?>
                    <div style="background:var(--surface-alt);border:1px solid var(--border-soft);border-radius:var(--radius-sm);padding:16px;margin-bottom:20px;">
                        <div style="font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--text-light);margin-bottom:8px;">
                            <i class="fa-solid fa-align-left"></i> Deskripsi Pelapor
                        </div>
                        <div style="font-size:14px;line-height:1.7;color:var(--text);"><?= nl2br(htmlspecialchars($item['deskripsi'])) ?></div>
                    </div>
                <?php endif; ?>

                <!-- Catatan Admin -->
                <?php if (!empty($item['catatan_admin'])): ?>
                    <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:var(--radius-sm);padding:16px;margin-bottom:20px;">
                        <div style="font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#1e40af;margin-bottom:8px;">
                            <i class="fa-solid fa-shield-check"></i> Catatan Admin
                        </div>
                        <div style="font-size:13.5px;line-height:1.6;color:#1e3a8a;"><?= nl2br(htmlspecialchars($item['catatan_admin'])) ?></div>
                    </div>
                <?php endif; ?>

                <!-- Catatan Dinas -->
                <?php if (!empty($item['catatan_dinas'])): ?>
                    <div style="background:#fff7ed;border:1px solid #fed7aa;border-radius:var(--radius-sm);padding:16px;margin-bottom:20px;">
                        <div style="font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#c2410c;margin-bottom:8px;">
                            <i class="fa-solid fa-hard-hat"></i> Catatan Dinas Teknis
                        </div>
                        <div style="font-size:13.5px;line-height:1.6;color:#9a3412;"><?= nl2br(htmlspecialchars($item['catatan_dinas'])) ?></div>
                    </div>
                <?php endif; ?>

                <!-- Foto laporan -->
                <?php if (!empty($item['foto_path'])): ?>
                    <div class="foto-section">
                        <h4><i class="fa-solid fa-image"></i> Foto Kerusakan</h4>
                        <img src="<?= BASE_URL ?><?= htmlspecialchars($item['foto_path']) ?>" alt="Foto kerusakan" class="foto-full">
                    </div>
                <?php endif; ?>

                <!-- Foto perbaikan -->
                <?php if (!empty($item['foto_perbaikan'])): ?>
                    <div class="foto-section" style="margin-top:16px;">
                        <h4><i class="fa-solid fa-image" style="color:var(--success);"></i> Bukti Perbaikan</h4>
                        <img src="<?= BASE_URL ?><?= htmlspecialchars($item['foto_perbaikan']) ?>" alt="Bukti perbaikan" class="foto-full">
                    </div>
                <?php endif; ?>

                <!-- Mini Map -->
                <?php if ($lat !== 0.0 && $lng !== 0.0): ?>
                    <div class="foto-section" style="margin-top:20px;">
                        <h4><i class="fa-solid fa-map-location-dot"></i> Lokasi di Peta</h4>
                        <div id="minimap"></div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Tombol kembali -->
        <a href="<?= BASE_URL ?>pengajuan" class="btn btn-outline">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar
        </a>
    </div>

    <!-- Kolom Kanan: Panel Aksi per Role -->
    <div>

        <?php if ($role === 'admin'): ?>
            <!-- ── PANEL ADMIN: Verifikasi ── -->
            <div class="card" style="margin-bottom:16px;">
                <div class="card-header">
                    <h3><i class="fa-solid fa-shield-check"></i> Verifikasi Laporan</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= BASE_URL ?>pengajuan/status/<?= $item['id'] ?>">
                        <div class="form-group" style="margin-bottom:14px;">
                            <label>Status Laporan</label>
                            <select name="status" class="form-control">
                                <?php foreach (['diterima' => 'Diterima', 'diperbaiki' => 'Diperbaiki', 'selesai' => 'Selesai', 'ditolak' => 'Ditolak'] as $val => $lbl): ?>
                                    <option value="<?= $val ?>" <?= ($item['statuslaporan'] ?? '') === $val ? 'selected' : '' ?>><?= $lbl ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group" style="margin-bottom:14px;">
                            <label>Tingkat Kerusakan</label>
                            <select name="tingkat_kerusakan" class="form-control">
                                <?php foreach (['ringan' => 'Ringan', 'sedang' => 'Sedang', 'berat' => 'Berat'] as $val => $lbl): ?>
                                    <option value="<?= $val ?>" <?= ($item['tingkat_kerusakan'] ?? '') === $val ? 'selected' : '' ?>><?= $lbl ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group" style="margin-bottom:16px;">
                            <label>Catatan Admin</label>
                            <textarea name="catatan_admin" class="form-control" rows="3"
                                placeholder="Catatan verifikasi..."><?= htmlspecialchars($item['catatan_admin'] ?? '') ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fa-solid fa-floppy-disk"></i> Simpan Verifikasi
                        </button>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <a href="<?= BASE_URL ?>pengajuan/edit/<?= $item['id'] ?>" class="btn btn-warning btn-block">
                        <i class="fa-solid fa-pen"></i> Edit Data Laporan
                    </a>
                    <a href="<?= BASE_URL ?>pengajuan/delete/<?= $item['id'] ?>" class="btn btn-danger btn-block"
                        onclick="return confirm('Yakin ingin menghapus laporan ini? Tindakan tidak bisa dibatalkan.')">
                        <i class="fa-solid fa-trash"></i> Hapus Laporan
                    </a>
                </div>
            </div>

        <?php elseif ($role === 'dinas'): ?>
            <!-- ── PANEL DINAS: Update Pengerjaan ── -->
            <div class="card">
                <div class="card-header">
                    <h3><i class="fa-solid fa-hard-hat"></i> Update Pengerjaan</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= BASE_URL ?>pengajuan/statusDinas/<?= $item['id'] ?>" enctype="multipart/form-data">
                        <div class="form-group" style="margin-bottom:14px;">
                            <label>Status Pengerjaan</label>
                            <select name="status" class="form-control">
                                <option value="diperbaiki" <?= ($item['statuslaporan'] ?? '') === 'diperbaiki' ? 'selected' : '' ?>>Sedang Diperbaiki</option>
                                <option value="selesai" <?= ($item['statuslaporan'] ?? '') === 'selesai'    ? 'selected' : '' ?>>Selesai Diperbaiki</option>
                            </select>
                        </div>
                        <div class="form-group" style="margin-bottom:14px;">
                            <label>Catatan Pengerjaan</label>
                            <textarea name="catatan_dinas" class="form-control" rows="3"
                                placeholder="Log progres pengerjaan..."><?= htmlspecialchars($item['catatan_dinas'] ?? '') ?></textarea>
                        </div>
                        <div class="form-group" style="margin-bottom:16px;">
                            <label>Foto Bukti Perbaikan <span style="color:var(--text-light);font-weight:400;">(opsional)</span></label>
                            <input type="file" name="foto_perbaikan" class="form-control" accept="image/jpeg,image/png">
                        </div>
                        <button type="submit" class="btn btn-success btn-block">
                            <i class="fa-solid fa-floppy-disk"></i> Update Status
                        </button>
                    </form>
                </div>
            </div>

        <?php else: ?>
            <!-- ── PANEL READONLY: Masyarakat / Pimpinan ── -->
            <div class="card">
                <div class="card-header">
                    <h3><i class="fa-solid fa-circle-info"></i> Informasi Status</h3>
                </div>
                <div class="card-body">
                    <?php
                    $statusInfo = [
                        'diterima'   => ['icon' => 'fa-clock',         'color' => '#1e40af', 'bg' => '#eff6ff',   'title' => 'Laporan Diterima',   'desc' => 'Laporan Anda sudah masuk dan menunggu verifikasi oleh admin.'],
                        'diperbaiki' => ['icon' => 'fa-person-digging', 'color' => '#c2410c', 'bg' => '#fff7ed',   'title' => 'Sedang Diperbaiki',  'desc' => 'Tim dinas teknis sedang mengerjakan perbaikan di lokasi Anda.'],
                        'selesai'    => ['icon' => 'fa-circle-check',   'color' => '#166534', 'bg' => '#f0fdf4',   'title' => 'Perbaikan Selesai',  'desc' => 'Jalan sudah berhasil diperbaiki. Terima kasih atas laporan Anda!'],
                        'ditolak'    => ['icon' => 'fa-circle-xmark',   'color' => '#991b1b', 'bg' => '#fef2f2',   'title' => 'Laporan Ditolak',    'desc' => 'Laporan tidak dapat diproses. Lihat catatan admin untuk detailnya.'],
                    ];
                    $si = $statusInfo[$item['statuslaporan'] ?? 'diterima'] ?? $statusInfo['diterima'];
                    ?>
                    <div style="background:<?= $si['bg'] ?>;border-radius:var(--radius-sm);padding:20px;text-align:center;">
                        <i class="fa-solid <?= $si['icon'] ?>" style="font-size:36px;color:<?= $si['color'] ?>;margin-bottom:12px;display:block;"></i>
                        <div style="font-weight:700;font-size:15px;color:<?= $si['color'] ?>;margin-bottom:8px;"><?= $si['title'] ?></div>
                        <div style="font-size:13px;color:var(--text-muted);line-height:1.6;"><?= $si['desc'] ?></div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>

<?php
$namaJalanJs = addslashes(htmlspecialchars($item['nama_jalan'] ?? ''));
$extraScript = '';
if ($lat !== 0.0 && $lng !== 0.0) {
    $extraScript = "
<script src=\"https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js\"></script>
<script>
const map = L.map('minimap').setView([{$lat}, {$lng}], 15);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
}).addTo(map);
L.marker([{$lat}, {$lng}]).addTo(map)
    .bindPopup('<b>{$namaJalanJs}</b>')
    .openPopup();
</script>";
}
?>
<?php require ROOT . '/app/views/partials/footer.php'; ?>