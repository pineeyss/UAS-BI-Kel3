<?php

/** @var array $trend */
/** @var array $byJenis */
/** @var array $byKec */
/** @var array $byStatus */
$trend    = $trend    ?? [];
$byJenis  = $byJenis  ?? [];
$byKec    = $byKec    ?? [];
$byStatus = $byStatus ?? [];

$extraHead = '<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>';
require ROOT . '/app/views/partials/header.php';

$trendLabels  = array_column($trend, 'bulan');
$trendData    = array_map('intval', array_column($trend, 'jumlah'));
$jenisLabels  = array_column($byJenis, 'jenis_kerusakan');
$jenisData    = array_map('intval', array_column($byJenis, 'jumlah'));
$kecLabels    = array_column($byKec, 'kecamatan');
$kecData      = array_map('intval', array_column($byKec, 'jumlah'));
$statusLabels = array_map('ucfirst', array_column($byStatus, 'status'));
$statusData   = array_map('intval', array_column($byStatus, 'jumlah'));
?>

<div class="analitik-grid">
    <div class="card analitik-full">
        <div class="card-header">
            <h3><i class="fa-solid fa-chart-line"></i> Tren Laporan 12 Bulan Terakhir</h3>
        </div>
        <div class="card-body">
            <canvas id="trendChart" height="100"></canvas>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h3><i class="fa-solid fa-chart-bar"></i> Distribusi Nama Jalan</h3>
        </div>
        <div class="card-body">
            <canvas id="jenisChart" height="220"></canvas>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h3><i class="fa-solid fa-chart-pie"></i> Status Laporan</h3>
        </div>
        <div class="card-body">
            <canvas id="statusChart" height="220"></canvas>
        </div>
    </div>
    <div class="card analitik-full">
        <div class="card-header">
            <h3><i class="fa-solid fa-ranking-star"></i> Distribusi Lokasi (Top 10)</h3>
        </div>
        <div class="card-body">
            <canvas id="kecChart" height="80"></canvas>
        </div>
    </div>
</div>

<?php
$extraScript = "<script>
Chart.defaults.font  = { family: 'Inter, sans-serif', size: 12 };
Chart.defaults.color = '#64748b';
new Chart(document.getElementById('trendChart'), {
    type: 'line',
    data: { labels: " . json_encode($trendLabels) . ", datasets: [{ label: 'Jumlah Laporan', data: " . json_encode($trendData) . ", borderColor: '#3b82f6', backgroundColor: 'rgba(59,130,246,.1)', fill: true, tension: 0.4, pointBackgroundColor: '#3b82f6', pointRadius: 4 }] },
    options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
});
new Chart(document.getElementById('jenisChart'), {
    type: 'bar',
    data: { labels: " . json_encode($jenisLabels) . ", datasets: [{ label: 'Jumlah', data: " . json_encode($jenisData) . ", backgroundColor: ['#3b82f6','#f59e0b','#ef4444','#22c55e','#8b5cf6','#06b6d4','#ec4899','#14b8a6','#f97316','#6366f1'], borderRadius: 4 }] },
    options: { responsive: true, indexAxis: 'y', plugins: { legend: { display: false } } }
});
new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: { labels: " . json_encode($statusLabels) . ", datasets: [{ data: " . json_encode($statusData) . ", backgroundColor: ['#3b82f6','#f59e0b','#22c55e','#ef4444'], borderWidth: 2 }] },
    options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
});
new Chart(document.getElementById('kecChart'), {
    type: 'bar',
    data: { labels: " . json_encode($kecLabels) . ", datasets: [{ label: 'Jumlah Laporan', data: " . json_encode($kecData) . ", backgroundColor: '#3b82f6', borderRadius: 4 }] },
    options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
});
</script>";
?>
<?php require ROOT . '/app/views/partials/footer.php'; ?>