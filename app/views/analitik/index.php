<?php

/** @var array $trend    */
/** @var array $byJenis  */
/** @var array $byKec    */
/** @var array $byStatus */
$trend    = $trend    ?? [];
$byJenis  = $byJenis  ?? [];
$byKec    = $byKec    ?? [];
$byStatus = $byStatus ?? [];

$extraHead = '<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>';
require ROOT . '/app/views/partials/header.php';

/* Prepare data for JS */
$trendLabels  = array_column($trend,    'bulan');
$trendData    = array_map('intval', array_column($trend,    'jumlah'));
$jenisLabels  = array_column($byJenis,  'tingkat');   // mapped to tingkat_kerusakan
$jenisData    = array_map('intval', array_column($byJenis,  'jumlah'));
$kecLabels    = array_column($byKec,    'nama_jalan'); // top jalan
$kecData      = array_map('intval', array_column($byKec,    'jumlah'));
$statusLabels = array_map('ucfirst', array_column($byStatus, 'status'));
$statusData   = array_map('intval', array_column($byStatus,  'jumlah'));

/* Totals for summary strip */
$grandTotal = array_sum($statusData);
$pctSelesai = $grandTotal > 0
    ? round(array_sum(array_map(fn($s, $j) => $s === 'Selesai' ? $j : 0, $statusLabels, $statusData)) / $grandTotal * 100)
    : 0;
?>

<!-- Summary strip -->
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:24px;">
    <?php
    $strips = [
        ['label' => 'Total Laporan',        'val' => number_format($grandTotal),  'icon' => 'fa-chart-bar',   'color' => '#1d4ed8', 'bg' => '#eff6ff'],
        ['label' => 'Tren 12 Bulan',        'val' => count($trendData) . ' bln',  'icon' => 'fa-chart-line',  'color' => '#0284c7', 'bg' => '#f0f9ff'],
        ['label' => 'Ruas Jalan Dipantau',  'val' => count($kecData),             'icon' => 'fa-road',        'color' => '#7c3aed', 'bg' => '#f5f3ff'],
        ['label' => 'Response Rate',        'val' => $pctSelesai . '%',           'icon' => 'fa-gauge-high',  'color' => '#16a34a', 'bg' => '#f0fdf4'],
    ];
    foreach ($strips as $s): ?>
        <div style="background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);padding:18px 20px;display:flex;align-items:center;gap:14px;box-shadow:var(--shadow-sm);">
            <div style="width:42px;height:42px;border-radius:10px;background:<?= $s['bg'] ?>;color:<?= $s['color'] ?>;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0;">
                <i class="fa-solid <?= $s['icon'] ?>"></i>
            </div>
            <div>
                <div style="font-family:'Space Grotesk',sans-serif;font-size:22px;font-weight:800;line-height:1;color:var(--text);"><?= $s['val'] ?></div>
                <div style="font-size:12px;font-weight:600;color:var(--text-muted);margin-top:4px;"><?= $s['label'] ?></div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Tren bulanan — full width -->
<div class="card" style="margin-bottom:22px;">
    <div class="card-header">
        <h3><i class="fa-solid fa-chart-line"></i> Tren Laporan 12 Bulan Terakhir</h3>
        <span style="font-size:12px;color:var(--text-muted);">Jumlah laporan masuk per bulan</span>
    </div>
    <div class="card-body">
        <canvas id="trendChart" height="90"></canvas>
    </div>
</div>

<!-- Tingkat + Status side by side -->
<div class="row-2col" style="margin-bottom:22px;">
    <div class="card">
        <div class="card-header">
            <h3><i class="fa-solid fa-triangle-exclamation"></i> Distribusi Tingkat Kerusakan</h3>
        </div>
        <div class="card-body" style="display:flex;align-items:center;justify-content:center;min-height:280px;">
            <?php if (empty($byJenis)): ?>
                <div style="text-align:center;color:var(--text-light);">
                    <i class="fa-solid fa-chart-pie" style="font-size:40px;margin-bottom:12px;display:block;"></i>
                    Data belum tersedia
                </div>
            <?php else: ?>
                <canvas id="jenisChart" style="max-height:280px;"></canvas>
            <?php endif; ?>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3><i class="fa-solid fa-chart-pie"></i> Distribusi Status Laporan</h3>
        </div>
        <div class="card-body" style="display:flex;align-items:center;justify-content:center;min-height:280px;">
            <canvas id="statusChart" style="max-height:280px;"></canvas>
        </div>
    </div>
</div>

<!-- Top ruas jalan — full width horizontal bar -->
<div class="card" style="margin-bottom:22px;">
    <div class="card-header">
        <h3><i class="fa-solid fa-ranking-star"></i> Top 10 Ruas Jalan Terbanyak Dilaporkan</h3>
        <span style="font-size:12px;color:var(--text-muted);">Berdasarkan frekuensi laporan</span>
    </div>
    <div class="card-body">
        <?php if (empty($byKec)): ?>
            <div style="text-align:center;padding:40px;color:var(--text-light);">Data belum tersedia.</div>
        <?php else: ?>
            <canvas id="kecChart" height="<?= min(80, count($kecLabels) * 8) ?>"></canvas>
        <?php endif; ?>
    </div>
</div>

<!-- Tabel ringkasan status -->
<div class="card">
    <div class="card-header">
        <h3><i class="fa-solid fa-table-list"></i> Tabel Ringkasan Status</h3>
    </div>
    <div class="card-body p-0">
        <table class="table">
            <thead>
                <tr>
                    <th>Status</th>
                    <th>Jumlah</th>
                    <th>Persentase</th>
                    <th style="width:40%;">Proporsi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $colors = ['Diterima' => '#3b82f6', 'Diperbaiki' => '#f59e0b', 'Selesai' => '#22c55e', 'Ditolak' => '#ef4444'];
                foreach ($byStatus as $row):
                    $lbl = ucfirst($row['status']);
                    $pct = $grandTotal > 0 ? round($row['jumlah'] / $grandTotal * 100, 1) : 0;
                    $clr = $colors[$lbl] ?? '#64748b';
                ?>
                    <tr>
                        <td>
                            <span class="badge status-<?= strtolower($row['status']) ?>"><?= $lbl ?></span>
                        </td>
                        <td style="font-family:'Space Grotesk',sans-serif;font-weight:700;color:var(--text);">
                            <?= number_format($row['jumlah']) ?>
                        </td>
                        <td style="font-weight:600;color:var(--text-muted);"><?= $pct ?>%</td>
                        <td>
                            <div style="background:var(--border-soft);border-radius:999px;height:8px;overflow:hidden;">
                                <div style="width:<?= $pct ?>%;background:<?= $clr ?>;height:100%;border-radius:999px;transition:.6s ease;"></div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
$extraScript = '<script>
Chart.defaults.font  = { family: "DM Sans, sans-serif", size: 12 };
Chart.defaults.color = "#64748b";

/* Tren */
new Chart(document.getElementById("trendChart"), {
    type: "line",
    data: {
        labels: ' . json_encode($trendLabels) . ',
        datasets: [{
            label: "Laporan Masuk",
            data: ' . json_encode($trendData) . ',
            borderColor: "#1d4ed8",
            backgroundColor: "rgba(29,78,216,.07)",
            fill: true,
            tension: 0.4,
            pointBackgroundColor: "#1d4ed8",
            pointRadius: 5,
            pointHoverRadius: 7,
            borderWidth: 2.5
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: "#f1f5f9" } },
            x: { grid: { display: false } }
        }
    }
});

/* Tingkat kerusakan (donut) */
' . (!empty($byJenis) ? '
new Chart(document.getElementById("jenisChart"), {
    type: "doughnut",
    data: {
        labels: ' . json_encode(array_map('ucfirst', $jenisLabels)) . ',
        datasets: [{
            data: ' . json_encode($jenisData) . ',
            backgroundColor: ["#22c55e","#f59e0b","#ef4444"],
            borderWidth: 3, borderColor: "#ffffff", hoverOffset: 8
        }]
    },
    options: {
        responsive: true,
        cutout: "62%",
        plugins: { legend: { position: "bottom", labels: { padding: 16, usePointStyle: true } } }
    }
});' : '') . '

/* Status (donut) */
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
    options: {
        responsive: true,
        cutout: "62%",
        plugins: { legend: { position: "bottom", labels: { padding: 16, usePointStyle: true } } }
    }
});

/* Top ruas jalan (horizontal bar) */
' . (!empty($byKec) ? '
new Chart(document.getElementById("kecChart"), {
    type: "bar",
    data: {
        labels: ' . json_encode($kecLabels) . ',
        datasets: [{
            label: "Jumlah Laporan",
            data: ' . json_encode($kecData) . ',
            backgroundColor: "rgba(29,78,216,.75)",
            borderRadius: 6,
            borderSkipped: false
        }]
    },
    options: {
        indexAxis: "y",
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            x: { beginAtZero: true, grid: { color: "#f1f5f9" } },
            y: { grid: { display: false }, ticks: { font: { size: 11 } } }
        }
    }
});' : '') . '
</script>';
?>
<?php require ROOT . '/app/views/partials/footer.php'; ?>