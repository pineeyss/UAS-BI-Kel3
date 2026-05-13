<?php

/** @var array $kpi         */
/** @var array $byStatus    */
/** @var array $trend       */
/** @var array $byTingkat   */
/** @var array $topJalan    */
/** @var float $responsRate */
$kpi         = $kpi         ?? [];
$byStatus    = $byStatus    ?? [];
$trend       = $trend       ?? [];
$byTingkat   = $byTingkat   ?? [];
$topJalan    = $topJalan    ?? [];
$responsRate = round((float)($responsRate ?? 0), 1);

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

<!-- Executive KPI Banner -->
<div style="background:linear-gradient(135deg,#1e3a8a,#1d4ed8,#2563eb);border-radius:var(--radius-lg);padding:32px 36px;margin-bottom:24px;color:white;position:relative;overflow:hidden;">
    <div style="position:absolute;right:-60px;top:-60px;width:220px;height:220px;border-radius:50%;background:rgba(255,255,255,.07);"></div>
    <div style="position:relative;z-index:1;">
        <div style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:1.5px;opacity:.7;margin-bottom:8px;">
            <i class="fa-solid fa-crown"></i> &nbsp; Executive Dashboard — Business Intelligence
        </div>
        <div style="font-family:'Space Grotesk',sans-serif;font-size:28px;font-weight:800;margin-bottom:20px;">
            Ringkasan Kinerja Sistem Pelaporan Jalan
        </div>
        <div style="display:grid;grid-template-columns:repeat(5,1fr);gap:20px;">
            <?php
            $exKpi = [
                ['val' => number_format($total),      'label' => 'Total Laporan',      'icon' => 'fa-file-lines'],
                ['val' => number_format($diterima),   'label' => 'Antrian',            'icon' => 'fa-inbox'],
                ['val' => number_format($diperbaiki), 'label' => 'Dikerjakan',         'icon' => 'fa-hammer'],
                ['val' => number_format($selesai),    'label' => 'Selesai',            'icon' => 'fa-circle-check'],
                ['val' => $responsRate . '%',         'label' => 'Response Rate',      'icon' => 'fa-gauge-high'],
            ];
            foreach ($exKpi as $k): ?>
                <div style="background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.15);border-radius:14px;padding:18px 16px;backdrop-filter:blur(6px);">
                    <i class="fa-solid <?= $k['icon'] ?>" style="font-size:18px;opacity:.75;margin-bottom:10px;display:block;"></i>
                    <div style="font-family:'Space Grotesk',sans-serif;font-size:26px;font-weight:800;line-height:1;"><?= $k['val'] ?></div>
                    <div style="font-size:12px;opacity:.8;margin-top:5px;font-weight:500;"><?= $k['label'] ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Tren + Status -->
<div class="row-2col">
    <div class="card">
        <div class="card-header">
            <h3><i class="fa-solid fa-chart-line"></i> Tren Laporan 12 Bulan</h3>
        </div>
        <div class="card-body">
            <canvas id="trendChart" height="180"></canvas>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h3><i class="fa-solid fa-chart-pie"></i> Distribusi Status</h3>
        </div>
        <div class="card-body" style="display:flex;align-items:center;justify-content:center;">
            <canvas id="statusChart" style="max-height:260px;"></canvas>
        </div>
    </div>
</div>

<!-- Tingkat + Top Jalan -->
<div class="row-2col">
    <div class="card">
        <div class="card-header">
            <h3><i class="fa-solid fa-triangle-exclamation"></i> Distribusi Tingkat Kerusakan</h3>
        </div>
        <div class="card-body" style="display:flex;align-items:center;justify-content:center;">
            <canvas id="tingkatChart" style="max-height:260px;"></canvas>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3><i class="fa-solid fa-ranking-star"></i> Ruas Jalan Terbanyak Dilaporkan</h3>
        </div>
        <div class="card-body p-0">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Jalan</th>
                        <th>Laporan</th>
                        <th>Bar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $maxVal = !empty($topJalan) ? max(array_column($topJalan, 'jumlah')) : 1;
                    $i = 1;
                    foreach ($topJalan as $row):
                        $pctBar = round($row['jumlah'] / $maxVal * 100);
                    ?>
                        <tr>
                            <td style="font-weight:700;color:var(--text-muted);width:30px;"><?= $i++ ?></td>
                            <td style="max-width:160px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;font-size:13px;"><?= htmlspecialchars($row['nama_jalan']) ?></td>
                            <td style="font-family:'Space Grotesk',sans-serif;font-weight:700;color:var(--brand);"><?= $row['jumlah'] ?></td>
                            <td style="width:100px;">
                                <div style="background:var(--border-soft);border-radius:999px;height:6px;overflow:hidden;">
                                    <div style="width:<?= $pctBar ?>%;background:var(--brand);height:100%;border-radius:999px;transition:.4s;"></div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Insight cards -->
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:18px;margin-bottom:22px;">
    <?php
    $insights = [
        [
            'icon'  => 'fa-gauge-high',
            'color' => '#7c3aed',
            'bg'    => '#f5f3ff',
            'title' => 'Response Rate',
            'value' => $responsRate . '%',
            'desc'  => 'Persentase laporan yang berhasil diselesaikan dari total masuk',
        ],
        [
            'icon'  => 'fa-calendar-check',
            'color' => '#0284c7',
            'bg'    => '#eff6ff',
            'title' => 'Masuk Bulan Ini',
            'value' => number_format($bulanIni),
            'desc'  => 'Jumlah laporan baru yang masuk pada bulan berjalan',
        ],
        [
            'icon'  => 'fa-ban',
            'color' => '#dc2626',
            'bg'    => '#fef2f2',
            'title' => 'Laporan Ditolak',
            'value' => number_format($ditolak),
            'desc'  => 'Laporan yang tidak memenuhi kriteria verifikasi',
        ],
    ];
    foreach ($insights as $ins): ?>
        <div class="card" style="margin-bottom:0;">
            <div class="card-body" style="display:flex;align-items:flex-start;gap:16px;">
                <div style="width:46px;height:46px;border-radius:12px;background:<?= $ins['bg'] ?>;color:<?= $ins['color'] ?>;display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0;">
                    <i class="fa-solid <?= $ins['icon'] ?>"></i>
                </div>
                <div>
                    <div style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--text-light);margin-bottom:4px;"><?= $ins['title'] ?></div>
                    <div style="font-family:'Space Grotesk',sans-serif;font-size:28px;font-weight:800;color:var(--text);line-height:1;margin-bottom:6px;"><?= $ins['value'] ?></div>
                    <div style="font-size:12.5px;color:var(--text-muted);line-height:1.5;"><?= $ins['desc'] ?></div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php
$trendLabels  = array_column($trend, 'bulan');
$trendData    = array_map('intval', array_column($trend, 'jumlah'));
$statusLabels = array_map('ucfirst', array_column($byStatus, 'status'));
$statusData   = array_map('intval',  array_column($byStatus, 'jumlah'));
$tingkatLabels = array_map('ucfirst', array_column($byTingkat, 'tingkat'));
$tingkatData   = array_map('intval',  array_column($byTingkat, 'jumlah'));

$extraScript = '<script>
Chart.defaults.font = { family: "DM Sans, sans-serif", size: 12 };
Chart.defaults.color = "#64748b";
new Chart(document.getElementById("trendChart"), {
    type: "line",
    data: {
        labels: ' . json_encode($trendLabels) . ',
        datasets: [{
            label: "Jumlah Laporan",
            data: ' . json_encode($trendData) . ',
            borderColor: "#1d4ed8",
            backgroundColor: "rgba(29,78,216,.08)",
            fill: true,
            tension: 0.4,
            pointBackgroundColor: "#1d4ed8",
            pointRadius: 4,
            pointHoverRadius: 6
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, grid: { color: "#f1f5f9" } }, x: { grid: { display: false } } }
    }
});
new Chart(document.getElementById("statusChart"), {
    type: "doughnut",
    data: {
        labels: ' . json_encode($statusLabels) . ',
        datasets: [{
            data: ' . json_encode($statusData) . ',
            backgroundColor: ["#3b82f6","#f59e0b","#22c55e","#ef4444"],
            borderWidth: 3, borderColor: "#ffffff", hoverOffset: 8
        }]
    },
    options: { responsive: true, cutout: "65%", plugins: { legend: { position: "bottom", labels: { padding: 16, usePointStyle: true } } } }
});
new Chart(document.getElementById("tingkatChart"), {
    type: "doughnut",
    data: {
        labels: ' . json_encode($tingkatLabels) . ',
        datasets: [{
            data: ' . json_encode($tingkatData) . ',
            backgroundColor: ["#22c55e","#f59e0b","#ef4444"],
            borderWidth: 3, borderColor: "#ffffff", hoverOffset: 8
        }]
    },
    options: { responsive: true, cutout: "65%", plugins: { legend: { position: "bottom", labels: { padding: 16, usePointStyle: true } } } }
});
</script>';
?>
<?php require ROOT . '/app/views/partials/footer.php'; ?>