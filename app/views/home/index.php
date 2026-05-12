<?php

/** @var array $kpi      */
/** @var array $byStatus */
/** @var array $trend    */
$kpi      = $kpi      ?? [];
$byStatus = $byStatus ?? [];
$trend    = $trend    ?? [];

$total      = (int)($kpi['total']      ?? 0);
$diterima   = (int)($kpi['diterima']   ?? 0);
$diperbaiki = (int)($kpi['diperbaiki'] ?? 0);
$selesai    = (int)($kpi['selesai']    ?? 0);
$progress   = $total > 0 ? round($selesai / $total * 100) : 0;

$statusLabels = array_map('ucfirst', array_column($byStatus, 'status'));
$statusData   = array_map('intval',  array_column($byStatus, 'jumlah'));
$trendLabels  = array_column($trend, 'bulan');
$trendData    = array_map('intval', array_column($trend, 'jumlah'));
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIJALAN — Sistem Informasi Manajemen Perbaikan Jalan</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>

    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --brand: #1d4ed8;
            --brand-dark: #1e3a8a;
            --brand-light: #eff6ff;
            --surface: #ffffff;
            --bg: #f5f7fb;
            --text: #0f172a;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --radius: 20px;
        }

        html {
            font-size: 15px;
            scroll-behavior: smooth;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* ── NAVBAR ── */
        .nav {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(255, 255, 255, .92);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            padding: 0 5%;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .nav-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-family: 'Space Grotesk', sans-serif;
            font-size: 22px;
            font-weight: 800;
            color: var(--brand);
        }

        .nav-logo .ico {
            width: 36px;
            height: 36px;
            background: var(--brand);
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 16px;
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 20px;
            border-radius: 10px;
            font-size: 13.5px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all .2s;
            text-decoration: none;
            font-family: 'DM Sans', sans-serif;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .btn-primary {
            background: var(--brand);
            color: white;
            box-shadow: 0 4px 14px rgba(29, 78, 216, .28);
        }

        .btn-primary:hover {
            background: var(--brand-dark);
        }

        .btn-outline {
            background: transparent;
            color: var(--text-muted);
            border: 1px solid var(--border);
        }

        .btn-outline:hover {
            background: var(--bg);
            color: var(--text);
        }

        /* ── HERO ── */
        .hero {
            background: linear-gradient(135deg, #0f2460 0%, #1d4ed8 50%, #2563eb 100%);
            padding: 100px 5% 80px;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: -120px;
            right: -120px;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .06);
        }

        .hero::after {
            content: '';
            position: absolute;
            bottom: -100px;
            left: -80px;
            width: 360px;
            height: 360px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .04);
        }

        .hero-inner {
            max-width: 1200px;
            margin: auto;
            position: relative;
            z-index: 2;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: rgba(255, 255, 255, .14);
            backdrop-filter: blur(6px);
            padding: 7px 16px;
            border-radius: 999px;
            font-size: 12.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 22px;
        }

        .hero h1 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: clamp(48px, 7vw, 82px);
            font-weight: 900;
            line-height: .95;
            letter-spacing: -3px;
            margin-bottom: 20px;
        }

        .hero h1 span {
            color: #93c5fd;
        }

        .hero p {
            font-size: 18px;
            line-height: 1.7;
            opacity: .88;
            max-width: 620px;
            margin-bottom: 36px;
        }

        .hero-cta {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 60px;
        }

        .btn-hero-primary {
            background: white;
            color: var(--brand);
            font-weight: 800;
            font-size: 15px;
            padding: 14px 28px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, .2);
        }

        .btn-hero-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, .25);
        }

        .btn-hero-ghost {
            background: rgba(255, 255, 255, .13);
            color: white;
            font-size: 15px;
            padding: 14px 28px;
            border: 1.5px solid rgba(255, 255, 255, .25);
            border-radius: 12px;
        }

        .btn-hero-ghost:hover {
            background: rgba(255, 255, 255, .2);
        }

        /* Hero KPI strip */
        .hero-kpi {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
        }

        .hero-kpi-item {
            background: rgba(255, 255, 255, .1);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, .15);
            border-radius: 16px;
            padding: 22px 20px;
            transition: transform .25s;
        }

        .hero-kpi-item:hover {
            transform: translateY(-4px);
        }

        .hero-kpi-item .val {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 38px;
            font-weight: 900;
            line-height: 1;
            margin-bottom: 6px;
        }

        .hero-kpi-item .lbl {
            font-size: 13.5px;
            opacity: .82;
            font-weight: 500;
        }

        /* ── CONTAINER ── */
        .wrap {
            max-width: 1200px;
            margin: auto;
            padding: 50px 5%;
        }

        /* ── SECTION TITLE ── */
        .section-title {
            text-align: center;
            margin-bottom: 40px;
        }

        .section-title h2 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 34px;
            font-weight: 800;
            margin-bottom: 10px;
        }

        .section-title p {
            color: var(--text-muted);
            max-width: 540px;
            margin: auto;
            line-height: 1.7;
        }

        /* ── CARDS ── */
        .card {
            background: white;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: 0 4px 20px rgba(15, 23, 42, .05);
        }

        .card-header {
            padding: 18px 24px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .card-header h3 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 16px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 9px;
        }

        .card-header h3 i {
            color: var(--brand);
        }

        .card-body {
            padding: 24px;
        }

        /* ── DASHBOARD GRID ── */
        .dash-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 22px;
            margin-bottom: 22px;
        }

        .full {
            grid-column: 1 / -1;
        }

        /* ── MAP ── */
        #publicMap {
            height: 500px;
            border-radius: 0 0 var(--radius) var(--radius);
        }

        /* ── FEATURES ── */
        .features {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 50px;
        }

        .feat-card {
            background: white;
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 28px 22px;
            text-align: center;
            transition: all .25s;
            box-shadow: 0 2px 10px rgba(15, 23, 42, .04);
        }

        .feat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(15, 23, 42, .08);
        }

        .feat-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            margin: 0 auto 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .feat-card h4 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 15px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .feat-card p {
            font-size: 13px;
            color: var(--text-muted);
            line-height: 1.65;
        }

        /* ── FOOTER ── */
        .footer {
            background: #0f172a;
            color: white;
            text-align: center;
            padding: 36px 20px;
            font-size: 14px;
        }

        .footer span {
            opacity: .5;
        }

        /* ── RESPONSIVE ── */
        @media(max-width:960px) {
            .hero-kpi {
                grid-template-columns: 1fr 1fr;
            }

            .dash-grid {
                grid-template-columns: 1fr;
            }

            .features {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media(max-width:600px) {
            .hero-kpi {
                grid-template-columns: 1fr;
            }

            .features {
                grid-template-columns: 1fr;
            }

            .hero h1 {
                letter-spacing: -1px;
            }
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <nav class="nav">
        <div class="nav-logo">
            <div class="ico"><i class="fa-solid fa-road"></i></div>
            SIJALAN
        </div>
        <div class="nav-right">
            <?php if (isset($_SESSION['user'])): ?>
                <a href="<?= BASE_URL ?>dashboard" class="btn btn-outline">
                    <i class="fa-solid fa-gauge"></i> Dashboard
                </a>
            <?php else: ?>
                <a href="<?= BASE_URL ?>auth/login" class="btn btn-outline">
                    <i class="fa-solid fa-right-to-bracket"></i> Login
                </a>
                <a href="<?= BASE_URL ?>auth/register" class="btn btn-primary">
                    <i class="fa-solid fa-user-plus"></i> Daftar
                </a>
            <?php endif; ?>
        </div>
    </nav>

    <!-- HERO -->
    <section class="hero">
        <div class="hero-inner">
            <div class="hero-badge">
                <i class="fa-solid fa-chart-line"></i>
                Business Intelligence Dashboard
            </div>
            <h1>
                SIJALAN<br>
                <span>MIS</span>
            </h1>
            <p>
                Platform Management Information System untuk monitoring, verifikasi,
                dan pengambilan keputusan perbaikan infrastruktur jalan secara real-time.
            </p>
            <div class="hero-cta">
                <a href="<?= BASE_URL ?>auth/register" class="btn btn-hero-primary">
                    <i class="fa-solid fa-user-plus"></i> Laporkan Sekarang
                </a>
                <a href="#dashboard" class="btn btn-hero-ghost">
                    <i class="fa-solid fa-chart-bar"></i> Lihat Data
                </a>
            </div>
            <div class="hero-kpi">
                <?php
                $kpis = [
                    ['val' => number_format($total),      'lbl' => 'Total Laporan'],
                    ['val' => number_format($diterima),   'lbl' => 'Antrian Verifikasi'],
                    ['val' => number_format($diperbaiki), 'lbl' => 'Sedang Diperbaiki'],
                    ['val' => $progress . '%',            'lbl' => 'Tingkat Selesai'],
                ];
                foreach ($kpis as $k): ?>
                    <div class="hero-kpi-item">
                        <div class="val"><?= $k['val'] ?></div>
                        <div class="lbl"><?= $k['lbl'] ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- FEATURES -->
    <section class="wrap">
        <div class="section-title">
            <h2>Sistem Terintegrasi 4 Peran</h2>
            <p>Setiap aktor memiliki dashboard khusus yang dirancang sesuai kebutuhan dan tanggung jawabnya dalam rantai pelaporan jalan.</p>
        </div>
        <div class="features">
            <?php
            $feats = [
                [
                    'icon' => 'fa-user',
                    'bg' => '#eff6ff',
                    'color' => '#1d4ed8',
                    'title' => 'Masyarakat',
                    'desc'  => 'Laporkan kerusakan jalan dengan foto, GPS, dan deskripsi. Pantau status laporan secara real-time.'
                ],
                [
                    'icon' => 'fa-shield-check',
                    'bg' => '#fef2f2',
                    'color' => '#dc2626',
                    'title' => 'Admin Verifikator',
                    'desc'  => 'Validasi laporan masuk, klasifikasikan tingkat kerusakan, dan disposisikan ke dinas terkait.'
                ],
                [
                    'icon' => 'fa-hard-hat',
                    'bg' => '#fff7ed',
                    'color' => '#d97706',
                    'title' => 'Dinas Teknis',
                    'desc'  => 'Update progres pengerjaan lapangan, upload bukti perbaikan, dan log catatan teknis.'
                ],
                [
                    'icon' => 'fa-crown',
                    'bg' => '#f5f3ff',
                    'color' => '#7c3aed',
                    'title' => 'Pimpinan',
                    'desc'  => 'Dashboard BI eksekutif dengan grafik tren, distribusi, dan KPI kinerja tim secara menyeluruh.'
                ],
            ];
            foreach ($feats as $f): ?>
                <div class="feat-card">
                    <div class="feat-icon" style="background:<?= $f['bg'] ?>;color:<?= $f['color'] ?>;">
                        <i class="fa-solid <?= $f['icon'] ?>"></i>
                    </div>
                    <h4><?= $f['title'] ?></h4>
                    <p><?= $f['desc'] ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- DASHBOARD PUBLIK -->
    <section id="dashboard" class="wrap" style="padding-top:0;">
        <div class="section-title">
            <h2>Dashboard Publik</h2>
            <p>Data terbaru kondisi pelaporan jalan — tersedia untuk semua kalangan tanpa perlu login.</p>
        </div>

        <div class="dash-grid">
            <div class="card">
                <div class="card-header">
                    <h3><i class="fa-solid fa-chart-pie"></i> Distribusi Status</h3>
                </div>
                <div class="card-body" style="display:flex;align-items:center;justify-content:center;min-height:240px;">
                    <canvas id="statusChart" style="max-height:240px;"></canvas>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h3><i class="fa-solid fa-chart-line"></i> Tren Laporan Bulanan</h3>
                </div>
                <div class="card-body">
                    <canvas id="trendChart" height="180"></canvas>
                </div>
            </div>
            <div class="card full">
                <div class="card-header">
                    <h3><i class="fa-solid fa-map-location-dot"></i> Peta Sebaran Kerusakan</h3>
                    <a href="<?= BASE_URL ?>peta" class="btn btn-outline" style="font-size:13px;padding:7px 14px;">
                        <i class="fa-solid fa-maximize"></i> Buka Peta Penuh
                    </a>
                </div>
                <div class="card-body" style="padding:0;">
                    <div id="publicMap"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section style="background:linear-gradient(135deg,var(--brand-dark),var(--brand));padding:70px 5%;text-align:center;color:white;">
        <div style="max-width:600px;margin:auto;">
            <h2 style="font-family:'Space Grotesk',sans-serif;font-size:32px;font-weight:800;margin-bottom:12px;">
                Siap Melaporkan Kerusakan Jalan?
            </h2>
            <p style="opacity:.85;font-size:16px;line-height:1.7;margin-bottom:30px;">
                Bergabung dan bantu pemerintah mengidentifikasi titik kerusakan lebih cepat.
                Laporan Anda membuat perbedaan nyata.
            </p>
            <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
                <a href="<?= BASE_URL ?>auth/register" style="background:white;color:var(--brand);font-weight:800;padding:14px 30px;border-radius:12px;font-size:15px;transition:.2s;"
                    onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='none'">
                    <i class="fa-solid fa-user-plus"></i> Daftar Gratis
                </a>
                <a href="<?= BASE_URL ?>auth/login" style="background:rgba(255,255,255,.15);color:white;border:1.5px solid rgba(255,255,255,.3);padding:14px 30px;border-radius:12px;font-size:15px;transition:.2s;"
                    onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='none'">
                    <i class="fa-solid fa-right-to-bracket"></i> Login
                </a>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="footer">
        © <?= date('Y') ?> <strong>SIJALAN</strong> &mdash; Sistem Informasi Manajemen Perbaikan Jalan
        <br><span>Management Information System berbasis Business Intelligence</span>
    </footer>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
    <script>
        Chart.defaults.font = {
            family: "DM Sans, sans-serif",
            size: 12
        };
        Chart.defaults.color = "#64748b";

        /* Status Chart */
        new Chart(document.getElementById("statusChart"), {
            type: "doughnut",
            data: {
                labels: <?= json_encode($statusLabels) ?>,
                datasets: [{
                    data: <?= json_encode($statusData) ?>,
                    backgroundColor: ["#3b82f6", "#f59e0b", "#22c55e", "#ef4444"],
                    borderWidth: 3,
                    borderColor: "#fff",
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                cutout: "64%",
                plugins: {
                    legend: {
                        position: "bottom",
                        labels: {
                            padding: 16,
                            usePointStyle: true
                        }
                    }
                }
            }
        });

        /* Trend Chart */
        new Chart(document.getElementById("trendChart"), {
            type: "line",
            data: {
                labels: <?= json_encode($trendLabels) ?>,
                datasets: [{
                    label: "Laporan",
                    data: <?= json_encode($trendData) ?>,
                    borderColor: "#1d4ed8",
                    backgroundColor: "rgba(29,78,216,.07)",
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: "#1d4ed8",
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: "#f1f5f9"
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        /* Public Map */
        const pubMap = L.map("publicMap").setView([-6.73, 108.57], 11);
        L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            attribution: "© OpenStreetMap contributors"
        }).addTo(pubMap);

        const cols = {
            diterima: "#3b82f6",
            diperbaiki: "#f59e0b",
            selesai: "#22c55e",
            ditolak: "#6b7280"
        };
        fetch("<?= BASE_URL ?>peta/data")
            .then(r => r.json())
            .then(rows => {
                rows.forEach(row => {
                    const lat = parseFloat(row.latitude),
                        lng = parseFloat(row.longitude);
                    if (isNaN(lat) || isNaN(lng)) return;
                    const color = cols[row.status] || "#64748b";
                    L.circleMarker([lat, lng], {
                        radius: 7,
                        fillColor: color,
                        color: "#fff",
                        weight: 2,
                        fillOpacity: .9
                    }).addTo(pubMap).bindPopup(
                        `<b>${row.nama_jalan}</b><br>Status: <b style="color:${color}">${row.status}</b>`
                    );
                });
            });
    </script>
</body>

</html>