dashboard = r"""<?php
                /** @var array  $kpi */
                /** @var array  $byStatus */
                /** @var array  $terbaru */
                $kpi      = $kpi      ?? [];
                $byStatus = $byStatus ?? [];
                $terbaru  = $terbaru  ?? [];

                require ROOT . '/app/views/partials/header.php';

                $total      = $kpi['total']      ?? 0;
                $diterima   = $kpi['diterima']   ?? 0;
                $selesai    = $kpi['selesai']    ?? 0;
                $diperbaiki = $kpi['diperbaiki'] ?? 0;
                $bulanIni   = $kpi['bulan_ini']  ?? 0;
                $pct        = $total > 0 ? round($selesai / $total * 100) : 0;
                ?>

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
        <div class="kpi-icon bg-yellow"><i class="fa-solid fa-clock"></i></div>
        <div class="kpi-body">
            <div class="kpi-value text-warning"><?= number_format($diterima) ?></div>
            <div class="kpi-label">Diterima</div>
            <div class="kpi-sub">Menunggu tindakan</div>
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
            <div class="kpi-sub"><?= $pct ?>% dari total</div>
        </div>
    </div>
</div>

<div class="row-2col">
    <div class="card">
        <div class="card-header">
            <h3><i class="fa-solid fa-chart-pie"></i> Distribusi Status</h3>
        </div>
        <div class="card-body">
            <canvas id="statusChart" height="220"></canvas>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h3><i class="fa-solid fa-list"></i> Laporan Terbaru</h3>
            <a href="<?= BASE_URL ?>pengajuan" class="btn btn-sm btn-outline">Lihat Semua</a>
        </div>
        <div class="card-body p-0">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Jalan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($terbaru as $row): ?>
                        <tr>
                            <td><a href="<?= BASE_URL ?>pengajuan/detail/<?= $row['id'] ?>">#<?= $row['id'] ?></a></td>
                            <td><?= htmlspecialchars($row['nama_jalan']) ?></td>
                            <td><span class="badge status-<?= strtolower($row['statuslaporan']) ?>"><?= ucfirst($row['statuslaporan']) ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
$statusLabels = [];
$statusData   = [];
foreach ($byStatus as $s) {
    $statusLabels[] = ucfirst($s['status']);
    $statusData[]   = (int)$s['jumlah'];
}
$extraScript = '<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
new Chart(document.getElementById("statusChart"), {
    type: "doughnut",
    data: {
        labels: ' . json_encode($statusLabels) . ',
        datasets: [{ data: ' . json_encode($statusData) . ', backgroundColor: ["#3b82f6","#f59e0b","#22c55e","#ef4444"], borderWidth: 2 }]
    },
    options: { responsive: true, plugins: { legend: { position: "bottom" } } }
});
</script>';
?>
<?php require ROOT . '/app/views/partials/footer.php'; ?>