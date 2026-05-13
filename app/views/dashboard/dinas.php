<?php

/** @var array $kpi        */
/** @var array $antrian    */
/** @var array $onProgress */
/** @var array $recentDone */
/** @var array $byTingkat  */
$kpi        = $kpi        ?? [];
$antrian    = $antrian    ?? [];
$onProgress = $onProgress ?? [];
$recentDone = $recentDone ?? [];
$byTingkat  = $byTingkat  ?? [];

$total      = (int)($kpi['total']      ?? 0);
$diterima   = (int)($kpi['diterima']   ?? 0);
$diperbaiki = (int)($kpi['diperbaiki'] ?? 0);
$selesai    = (int)($kpi['selesai']    ?? 0);

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
            <div class="kpi-sub">Semua status</div>
        </div>
    </div>
    <div class="kpi-card">
        <div class="kpi-icon bg-yellow"><i class="fa-solid fa-inbox"></i></div>
        <div class="kpi-body">
            <div class="kpi-value text-warning"><?= number_format($diterima) ?></div>
            <div class="kpi-label">Antrian Masuk</div>
            <div class="kpi-sub">Menunggu pengerjaan</div>
        </div>
    </div>
    <div class="kpi-card">
        <div class="kpi-icon bg-orange"><i class="fa-solid fa-person-digging"></i></div>
        <div class="kpi-body">
            <div class="kpi-value text-info"><?= number_format($diperbaiki) ?></div>
            <div class="kpi-label">Sedang Dikerjakan</div>
            <div class="kpi-sub">Tim di lapangan</div>
        </div>
    </div>
    <div class="kpi-card">
        <div class="kpi-icon bg-green"><i class="fa-solid fa-road-circle-check"></i></div>
        <div class="kpi-body">
            <div class="kpi-value text-success"><?= number_format($selesai) ?></div>
            <div class="kpi-label">Selesai Diperbaiki</div>
            <div class="kpi-sub">Jalan sudah baik</div>
        </div>
    </div>
</div>

<!-- Grafik tingkat + antrian -->
<div class="row-2col">
    <div class="card">
        <div class="card-header">
            <h3><i class="fa-solid fa-chart-bar"></i> Distribusi Tingkat Kerusakan</h3>
        </div>
        <div class="card-body" style="display:flex;align-items:center;justify-content:center;">
            <canvas id="tingkatChart" style="max-height:260px;"></canvas>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3><i class="fa-solid fa-inbox"></i> Antrian — Perlu Dikerjakan</h3>
            <a href="<?= BASE_URL ?>pengajuan?status=diterima" class="btn btn-outline btn-sm">Lihat Semua</a>
        </div>
        <div class="card-body p-0">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Jalan</th>
                        <th>Tingkat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($antrian)): ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted" style="padding:20px;">
                                <i class="fa-solid fa-circle-check" style="color:var(--success);"></i> Tidak ada antrian!
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($antrian as $row): ?>
                            <tr>
                                <td><a href="<?= BASE_URL ?>pengajuan/detail/<?= $row['id'] ?>">#<?= $row['id'] ?></a></td>
                                <td style="max-width:140px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= htmlspecialchars($row['nama_jalan']) ?></td>
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
                                    <?php else: ?><span style="color:var(--text-light);font-size:12px;">—</span><?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?= BASE_URL ?>pengajuan/detail/<?= $row['id'] ?>" class="btn btn-sm btn-primary">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- On progress + recently done -->
<div class="row-2col">
    <div class="card">
        <div class="card-header">
            <h3><i class="fa-solid fa-person-digging"></i> Sedang Dikerjakan</h3>
            <a href="<?= BASE_URL ?>pengajuan?status=diperbaiki" class="btn btn-outline btn-sm">Lihat Semua</a>
        </div>
        <div class="card-body p-0">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Jalan</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($onProgress)): ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted" style="padding:20px;">Tidak ada.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($onProgress as $row): ?>
                            <tr>
                                <td><a href="<?= BASE_URL ?>pengajuan/detail/<?= $row['id'] ?>">#<?= $row['id'] ?></a></td>
                                <td style="max-width:150px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= htmlspecialchars($row['nama_jalan']) ?></td>
                                <td style="font-size:12.5px;color:var(--text-muted);"><?= date('d/m/Y', strtotime($row['created_at'])) ?></td>
                                <td><a href="<?= BASE_URL ?>pengajuan/detail/<?= $row['id'] ?>" class="btn btn-sm btn-warning"><i class="fa-solid fa-pen"></i></a></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3><i class="fa-solid fa-circle-check" style="color:var(--success);"></i> Baru Selesai</h3>
            <a href="<?= BASE_URL ?>pengajuan?status=selesai" class="btn btn-outline btn-sm">Lihat Semua</a>
        </div>
        <div class="card-body p-0">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Jalan</th>
                        <th>Selesai</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($recentDone)): ?>
                        <tr>
                            <td colspan="3" class="text-center text-muted" style="padding:20px;">Belum ada.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($recentDone as $row): ?>
                            <tr>
                                <td><a href="<?= BASE_URL ?>pengajuan/detail/<?= $row['id'] ?>">#<?= $row['id'] ?></a></td>
                                <td style="max-width:160px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= htmlspecialchars($row['nama_jalan']) ?></td>
                                <td style="font-size:12.5px;color:var(--text-muted);"><?= $row['updated_at'] ? date('d/m/Y', strtotime($row['updated_at'])) : '—' ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
$tingkatLabels = array_map('ucfirst', array_column($byTingkat, 'tingkat'));
$tingkatData   = array_map('intval',  array_column($byTingkat, 'jumlah'));
$extraScript = '<script>
Chart.defaults.font = { family: "DM Sans, sans-serif", size: 12 };
new Chart(document.getElementById("tingkatChart"), {
    type: "bar",
    data: {
        labels: ' . json_encode($tingkatLabels) . ',
        datasets: [{
            label: "Jumlah Laporan",
            data: ' . json_encode($tingkatData) . ',
            backgroundColor: ["#22c55e","#f59e0b","#ef4444"],
            borderRadius: 8,
            borderSkipped: false
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, grid: { color: "#f1f5f9" } } }
    }
});
</script>';
?>
<?php require ROOT . '/app/views/partials/footer.php'; ?>