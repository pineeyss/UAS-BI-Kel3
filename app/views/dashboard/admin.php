<?php

/** @var array $kpi      */
/** @var array $byStatus */
/** @var array $terbaru  */
$kpi      = $kpi      ?? [];
$byStatus = $byStatus ?? [];
$terbaru  = $terbaru  ?? [];

$total      = (int)($kpi['total']      ?? 0);
$diterima   = (int)($kpi['diterima']   ?? 0);
$diperbaiki = (int)($kpi['diperbaiki'] ?? 0);
$selesai    = (int)($kpi['selesai']    ?? 0);
$ditolak    = (int)($kpi['ditolak']    ?? 0);
$bulanIni   = (int)($kpi['bulan_ini']  ?? 0);
$pct        = $total > 0 ? round($selesai / $total * 100) : 0;

$extraHead = '<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>';
require ROOT . '/app/views/partials/header.php';
?>

<!-- KPI -->
<div class="kpi-grid">
    <div class="kpi-card">
        <div class="kpi-icon bg-blue"><i class="fa-solid fa-file-lines"></i></div>
        <div class="kpi-body">
            <div class="kpi-value"><?= number_format($total) ?></div>
            <div class="kpi-label">Total Laporan</div>
            <div class="kpi-sub"><?= $bulanIni ?> masuk bulan ini</div>
        </div>
    </div>
    <div class="kpi-card">
        <div class="kpi-icon bg-yellow"><i class="fa-solid fa-inbox"></i></div>
        <div class="kpi-body">
            <div class="kpi-value text-warning"><?= number_format($diterima) ?></div>
            <div class="kpi-label">Menunggu Tindakan</div>
            <div class="kpi-sub">Belum diverifikasi</div>
        </div>
    </div>
    <div class="kpi-card">
        <div class="kpi-icon bg-orange"><i class="fa-solid fa-hammer"></i></div>
        <div class="kpi-body">
            <div class="kpi-value text-info"><?= number_format($diperbaiki) ?></div>
            <div class="kpi-label">Sedang Diperbaiki</div>
            <div class="kpi-sub">Di lapangan</div>
        </div>
    </div>
    <div class="kpi-card">
        <div class="kpi-icon bg-green"><i class="fa-solid fa-circle-check"></i></div>
        <div class="kpi-body">
            <div class="kpi-value text-success"><?= number_format($selesai) ?></div>
            <div class="kpi-label">Selesai</div>
            <div class="kpi-sub"><?= $pct ?>% dari total laporan</div>
        </div>
    </div>
</div>

<!-- Chart + Tabel -->
<div class="row-2col">
    <div class="card">
        <div class="card-header">
            <h3><i class="fa-solid fa-chart-pie"></i> Distribusi Status</h3>
        </div>
        <div class="card-body" style="display:flex;align-items:center;justify-content:center;">
            <canvas id="statusChart" style="max-height:280px;"></canvas>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3><i class="fa-solid fa-list-check"></i> Laporan Terbaru</h3>
            <a href="<?= BASE_URL ?>pengajuan" class="btn btn-outline btn-sm">
                <i class="fa-solid fa-arrow-right"></i> Semua
            </a>
        </div>
        <div class="card-body p-0">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Jalan</th>
                        <th>Tingkat</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($terbaru)): ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted" style="padding:20px;">Belum ada data.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($terbaru as $row): ?>
                            <tr>
                                <td><a href="<?= BASE_URL ?>pengajuan/detail/<?= $row['id'] ?>">#<?= $row['id'] ?></a></td>
                                <td style="max-width:160px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                    <?= htmlspecialchars($row['nama_jalan']) ?>
                                </td>
                                <td>
                                    <?php if ($row['tingkat_kerusakan']): ?>
                                        <span class="badge" style="background:<?= match ($row['tingkat_kerusakan']) {
                                                                                    'berat'  => '#fef2f2',
                                                                                    'sedang' => '#fff7ed',
                                                                                    default => '#f0fdf4'
                                                                                } ?>;color:<?= match ($row['tingkat_kerusakan']) {
                                                                                                'berat'  => '#991b1b',
                                                                                                'sedang' => '#c2410c',
                                                                                                default => '#166534'
                                                                                            } ?>;"><?= ucfirst($row['tingkat_kerusakan']) ?></span>
                                    <?php else: ?>
                                        <span style="color:var(--text-light);font-size:12px;">—</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge status-<?= strtolower($row['statuslaporan']) ?>">
                                        <?= ucfirst($row['statuslaporan']) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Quick actions -->
<div class="card" style="background:linear-gradient(135deg,var(--brand-dark),var(--brand));color:white;border:none;">
    <div class="card-body" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
        <div>
            <div style="font-family:'Space Grotesk',sans-serif;font-size:18px;font-weight:800;">Tindakan Cepat</div>
            <div style="opacity:.8;font-size:13.5px;margin-top:4px;">Kelola laporan dan pantau progres perbaikan jalan</div>
        </div>
        <div style="display:flex;gap:10px;flex-wrap:wrap;">
            <a href="<?= BASE_URL ?>pengajuan?status=diterima" class="btn" style="background:rgba(255,255,255,.15);color:white;border:1px solid rgba(255,255,255,.2);">
                <i class="fa-solid fa-inbox"></i> Antrian (<?= $diterima ?>)
            </a>
            <a href="<?= BASE_URL ?>pengajuan" class="btn" style="background:white;color:var(--brand);font-weight:700;">
                <i class="fa-solid fa-clipboard-list"></i> Semua Laporan
            </a>
            <a href="<?= BASE_URL ?>peta" class="btn" style="background:rgba(255,255,255,.15);color:white;border:1px solid rgba(255,255,255,.2);">
                <i class="fa-solid fa-map-location-dot"></i> Lihat Peta
            </a>
        </div>
    </div>
</div>

<?php
$statusLabels = array_map('ucfirst', array_column($byStatus, 'status'));
$statusData   = array_map('intval',  array_column($byStatus, 'jumlah'));
$extraScript = '<script>
Chart.defaults.font = { family: "DM Sans, sans-serif", size: 12 };
new Chart(document.getElementById("statusChart"), {
    type: "doughnut",
    data: {
        labels: ' . json_encode($statusLabels) . ',
        datasets: [{
            data: ' . json_encode($statusData) . ',
            backgroundColor: ["#3b82f6","#f59e0b","#22c55e","#ef4444"],
            borderWidth: 3,
            borderColor: "#ffffff",
            hoverOffset: 8
        }]
    },
    options: {
        responsive: true,
        cutout: "65%",
        plugins: {
            legend: { position: "bottom", labels: { padding: 16, usePointStyle: true } }
        }
    }
});
</script>';
?>
<?php require ROOT . '/app/views/partials/footer.php'; ?>