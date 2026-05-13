<?php

/** @var array $kpi    */
/** @var array $myData */
$kpi    = $kpi    ?? [];
$myData = $myData ?? [];

$total      = (int)($kpi['total']      ?? 0);
$selesai    = (int)($kpi['selesai']    ?? 0);
$diperbaiki = (int)($kpi['diperbaiki'] ?? 0);
$diterima   = (int)($kpi['diterima']   ?? 0);

require ROOT . '/app/views/partials/header.php';
?>

<!-- Welcome Banner -->
<div style="background:linear-gradient(135deg,#1e3a8a,#2563eb);border-radius:var(--radius-lg);padding:28px 32px;margin-bottom:24px;color:white;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:18px;">
    <div>
        <div style="font-size:13px;opacity:.75;font-weight:500;margin-bottom:6px;">
            <i class="fa-solid fa-hand-wave"></i> &nbsp;Selamat datang,
        </div>
        <div style="font-family:'Space Grotesk',sans-serif;font-size:24px;font-weight:800;">
            <?= htmlspecialchars($_SESSION['nama'] ?? 'Pengguna') ?>
        </div>
        <div style="font-size:13.5px;opacity:.8;margin-top:6px;">
            Laporkan kerusakan jalan di sekitar Anda dan pantau statusnya secara real-time.
        </div>
    </div>
    <a href="<?= BASE_URL ?>pengajuan/create"
        style="background:white;color:#1d4ed8;font-weight:700;padding:14px 24px;border-radius:12px;display:flex;align-items:center;gap:8px;font-size:14px;white-space:nowrap;transition:.2s;"
        onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='none'">
        <i class="fa-solid fa-plus"></i> Buat Laporan Baru
    </a>
</div>

<!-- KPI Publik -->
<div class="kpi-grid" style="margin-bottom:24px;">
    <div class="kpi-card">
        <div class="kpi-icon bg-blue"><i class="fa-solid fa-city"></i></div>
        <div class="kpi-body">
            <div class="kpi-value"><?= number_format($total) ?></div>
            <div class="kpi-label">Total Laporan Kota</div>
            <div class="kpi-sub">Seluruh wilayah</div>
        </div>
    </div>
    <div class="kpi-card">
        <div class="kpi-icon bg-yellow"><i class="fa-solid fa-clock"></i></div>
        <div class="kpi-body">
            <div class="kpi-value text-warning"><?= number_format($diterima) ?></div>
            <div class="kpi-label">Antrian</div>
            <div class="kpi-sub">Menunggu diproses</div>
        </div>
    </div>
    <div class="kpi-card">
        <div class="kpi-icon bg-orange"><i class="fa-solid fa-hammer"></i></div>
        <div class="kpi-body">
            <div class="kpi-value text-info"><?= number_format($diperbaiki) ?></div>
            <div class="kpi-label">Diperbaiki</div>
            <div class="kpi-sub">Sedang dikerjakan</div>
        </div>
    </div>
    <div class="kpi-card">
        <div class="kpi-icon bg-green"><i class="fa-solid fa-circle-check"></i></div>
        <div class="kpi-body">
            <div class="kpi-value text-success"><?= number_format($selesai) ?></div>
            <div class="kpi-label">Selesai</div>
            <div class="kpi-sub">Jalan sudah diperbaiki</div>
        </div>
    </div>
</div>

<!-- Laporan saya -->
<div class="card">
    <div class="card-header">
        <h3><i class="fa-solid fa-clipboard-user"></i> Laporan Terakhir Saya</h3>
        <a href="<?= BASE_URL ?>pengajuan" class="btn btn-outline btn-sm">
            <i class="fa-solid fa-arrow-right"></i> Semua
        </a>
    </div>
    <div class="card-body p-0">
        <?php if (empty($myData)): ?>
            <div style="padding:40px;text-align:center;">
                <i class="fa-solid fa-file-circle-plus" style="font-size:48px;color:var(--border);margin-bottom:16px;display:block;"></i>
                <div style="font-weight:600;color:var(--text-muted);margin-bottom:8px;">Anda belum memiliki laporan</div>
                <a href="<?= BASE_URL ?>pengajuan/create" class="btn btn-primary btn-sm">
                    <i class="fa-solid fa-plus"></i> Buat Laporan Pertama
                </a>
            </div>
        <?php else: ?>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Jalan</th>
                        <th>Tingkat</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($myData as $row): ?>
                        <tr>
                            <td><a href="<?= BASE_URL ?>pengajuan/detail/<?= $row['id'] ?>">#<?= $row['id'] ?></a></td>
                            <td style="max-width:200px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= htmlspecialchars($row['nama_jalan']) ?></td>
                            <td>
                                <?php $t = $row['tingkat_kerusakan'] ?? null; ?>
                                <?php if ($t): ?>
                                    <span class="badge" style="background:<?= match ($t) {
                                                                                'berat' => '#fef2f2',
                                                                                'sedang' => '#fff7ed',
                                                                                default => '#f0fdf4'
                                                                            } ?>;color:<?= match ($t) {
                                                                                            'berat' => '#991b1b',
                                                                                            'sedang' => '#c2410c',
                                                                                            default => '#166534'
                                                                                        } ?>;"><?= ucfirst($t) ?></span>
                                <?php else: ?><span style="color:var(--text-light);font-size:12px;">Belum dinilai</span><?php endif; ?>
                            </td>
                            <td><span class="badge status-<?= strtolower($row['statuslaporan']) ?>"><?= ucfirst($row['statuslaporan']) ?></span></td>
                            <td style="font-size:12.5px;color:var(--text-muted);"><?= date('d/m/Y', strtotime($row['created_at'])) ?></td>
                            <td><a href="<?= BASE_URL ?>pengajuan/detail/<?= $row['id'] ?>" class="btn btn-sm btn-info"><i class="fa-solid fa-eye"></i></a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<?php require ROOT . '/app/views/partials/footer.php'; ?>