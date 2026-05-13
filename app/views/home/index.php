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
    <title>RoadReport — Sistem Informasi Manajemen Perbaikan Jalan</title>
    <meta name="description" content="Platform MIS untuk monitoring, verifikasi, dan pengambilan keputusan perbaikan infrastruktur jalan secara real-time.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/style.css">
</head>

<body>

    <!-- ── ANNOUNCEMENT BAR ── -->
    <div class="ann-wrap" id="annBar">
        <div class="ann-bar">
            <i class="fa-solid fa-circle-info" style="font-size:12px;"></i>
            Sistem RoadReport kini mendukung upload foto kerusakan langsung dari smartphone.
            <a href="<?= BASE_URL ?>auth/register">Coba sekarang →</a>
        </div>
        <button class="ann-close" onclick="document.getElementById('annBar').remove()" aria-label="Tutup">×</button>
    </div>

    <!-- ── NAVBAR ── -->
    <nav class="nav" id="mainNav">
        <div class="nav-logo">
            <div class="logo-mark"><i class="fa-solid fa-road"></i></div>
            <div class="logo-text-group">
                <span class="logo-name">RoadReport</span>
                <span class="logo-sub">Manajemen Perbaikan Jalan</span>
            </div>
        </div>

        <div class="nav-links">
            <a href="#fitur" class="nav-link">Fitur</a>
            <a href="#alur" class="nav-link">Cara Kerja</a>
            <a href="#dashboard" class="nav-link">Dashboard</a>
        </div>

        <div class="nav-right">
            <?php if (isset($_SESSION['user'])): ?>
                <a href="<?= BASE_URL ?>dashboard" class="btn btn-outline">
                    <i class="fa-solid fa-gauge"></i> Dashboard
                </a>
            <?php else: ?>
                <a href="<?= BASE_URL ?>auth/login" class="btn btn-outline"><i class="fa-solid fa-right-to-bracket"></i> Login</a>
                <a href="<?= BASE_URL ?>auth/register" class="btn btn-teal"><i class="fa-solid fa-user-plus"></i> Daftar</a>
            <?php endif; ?>
        </div>
    </nav>

    <!-- ================================================================
     HERO
================================================================ -->
    <section class="hero">
        <!-- Left: text content -->
        <div class="hero-left">
            <div class="hero-left-inner">

                <div class="hero-eyebrow anim">
                    <span class="pulse"></span>
                    Platform Aktif &mdash; Business Intelligence Dashboard
                </div>

                <h1 class="hero-title anim anim-d1">
                    Kelola Infrastruktur<br>
                    Jalan Lebih <em>Cerdas</em>
                </h1>

                <p class="hero-desc anim anim-d2">
                    RoadReport adalah platform MIS terpadu untuk monitoring kerusakan,
                    verifikasi laporan warga, koordinasi dinas teknis, dan pengambilan
                    keputusan berbasis data secara real-time.
                </p>

                <div class="hero-cta anim anim-d3">
                    <a href="<?= BASE_URL ?>auth/register" class="btn btn-teal btn-lg">
                        <i class="fa-solid fa-circle-plus"></i> Buat Laporan
                    </a>
                    <a href="#dashboard" class="btn btn-outline btn-lg">
                        <i class="fa-solid fa-chart-bar"></i> Lihat Data Publik
                    </a>
                </div>

                <!-- Mobile KPI (fallback when right panel hidden) -->
                <div class="hero-kpi-mobile anim anim-d4">
                    <div class="hero-kpi-mobile-card">
                        <div class="val"><?= number_format($total) ?></div>
                        <div class="lbl">Total Laporan</div>
                    </div>
                    <div class="hero-kpi-mobile-card">
                        <div class="val"><?= $progress ?>%</div>
                        <div class="lbl">Tingkat Selesai</div>
                    </div>
                    <div class="hero-kpi-mobile-card">
                        <div class="val"><?= number_format($diterima) ?></div>
                        <div class="lbl">Antrian</div>
                    </div>
                    <div class="hero-kpi-mobile-card">
                        <div class="val"><?= number_format($diperbaiki) ?></div>
                        <div class="lbl">Diperbaiki</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: teal KPI panel -->
        <div class="hero-right">
            <div class="hero-right-label">Statistik Real-Time</div>

            <?php
            $kpiCards = [
                ['icon' => 'fa-file-lines',    'val' => number_format($total),      'lbl' => 'Total Laporan Masuk',   'badge' => '+12% bulan ini', 'up' => true],
                ['icon' => 'fa-hourglass-half', 'val' => number_format($diterima),   'lbl' => 'Antrian Verifikasi',    'badge' => null,             'up' => false],
                ['icon' => 'fa-helmet-safety', 'val' => number_format($diperbaiki), 'lbl' => 'Sedang Diperbaiki',     'badge' => 'Aktif lapangan', 'up' => false],
                ['icon' => 'fa-circle-check',  'val' => $progress . '%',            'lbl' => 'Tingkat Penyelesaian',  'badge' => number_format($selesai) . ' selesai', 'up' => true],
            ];
            foreach ($kpiCards as $k): ?>
                <div class="kpi-card">
                    <div class="kpi-icon-wrap">
                        <i class="fa-solid <?= $k['icon'] ?>"></i>
                    </div>
                    <div class="kpi-text">
                        <div class="kpi-val"><?= $k['val'] ?></div>
                        <div class="kpi-lbl"><?= $k['lbl'] ?></div>
                    </div>
                    <?php if ($k['badge']): ?>
                        <span class="kpi-badge <?= $k['up'] ? 'up' : '' ?>"><?= $k['badge'] ?></span>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </section>


    <!-- ================================================================
     ROLES / FEATURES
================================================================ -->
    <section id="fitur" class="roles-section section">
        <div class="section-inner">
            <div class="section-head center">
                <div class="eyebrow eyebrow-teal"><i class="fa-solid fa-users"></i> Sistem Multi-Peran</div>
                <h2 class="h2">Dirancang untuk <em>Setiap Aktor</em></h2>
                <p class="lead">
                    Empat dashboard khusus yang disesuaikan dengan alur kerja dan tanggung jawab
                    masing-masing pihak dalam rantai penanganan kerusakan jalan.
                </p>
            </div>

            <div class="roles-grid">
                <?php
                $roles = [
                    [
                        'cls'   => 'role-teal',
                        'icon'  => 'fa-user',
                        'title' => 'Masyarakat',
                        'desc'  => 'Portal pelaporan mudah — foto, lokasi GPS, deskripsi singkat. Pantau status laporan kapan saja.',
                        'feats' => [
                            ['fa-camera',       'Upload foto & tandai lokasi GPS'],
                            ['fa-clock-rotate-left', 'Pantau status laporan real-time'],
                            ['fa-bell',         'Notifikasi setiap update progres'],
                        ],
                    ],
                    [
                        'cls'   => 'role-blue',
                        'icon'  => 'fa-shield-halved',
                        'title' => 'Admin Verifikator',
                        'desc'  => 'Validasi dan klasifikasi laporan masuk sebelum diteruskan ke dinas terkait.',
                        'feats' => [
                            ['fa-check-double',  'Validasi & triage laporan'],
                            ['fa-layer-group',   'Klasifikasi tingkat kerusakan'],
                            ['fa-paper-plane',   'Disposisi ke dinas teknis'],
                        ],
                    ],
                    [
                        'cls'   => 'role-amber',
                        'icon'  => 'fa-helmet-safety',
                        'title' => 'Dinas Teknis',
                        'desc'  => 'Kelola pekerjaan lapangan, dokumentasi progres, dan pelaporan hasil perbaikan secara digital.',
                        'feats' => [
                            ['fa-list-check',    'Update status pekerjaan'],
                            ['fa-images',        'Upload bukti dokumentasi'],
                            ['fa-pen-to-square', 'Catatan teknis lapangan'],
                        ],
                    ],
                    [
                        'cls'   => 'role-green',
                        'icon'  => 'fa-crown',
                        'title' => 'Pimpinan',
                        'desc'  => 'Dashboard eksekutif dengan KPI, tren, dan analitik kinerja untuk keputusan strategis.',
                        'feats' => [
                            ['fa-chart-line',    'KPI & scorecard real-time'],
                            ['fa-chart-area',    'Analitik tren & komparasi'],
                            ['fa-file-export',   'Ekspor laporan PDF/Excel'],
                        ],
                    ],
                ];
                foreach ($roles as $r): ?>
                    <div class="role-card <?= $r['cls'] ?>">
                        <div class="role-icon-wrap">
                            <i class="fa-solid <?= $r['icon'] ?>"></i>
                        </div>
                        <h3><?= $r['title'] ?></h3>
                        <p class="role-desc"><?= $r['desc'] ?></p>
                        <div class="role-divider"></div>
                        <ul class="role-feats">
                            <?php foreach ($r['feats'] as [$ico, $txt]): ?>
                                <li>
                                    <i class="fa-solid <?= $ico ?>"></i>
                                    <?= $txt ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>


    <!-- ================================================================
     STATS STRIP
================================================================ -->
    <div class="stats-strip">
        <?php
        $stats = [
            ['val' => number_format($total),   'lbl' => 'Laporan Terdaftar',    'cls' => 's1'],
            ['val' => $progress . '%',         'lbl' => 'Tingkat Penyelesaian', 'cls' => 's2'],
            ['val' => '4',                     'lbl' => 'Peran Terintegrasi',   'cls' => 's3'],
            ['val' => '24/7',                  'lbl' => 'Sistem Aktif',         'cls' => 's4'],
        ];
        foreach ($stats as $s): ?>
            <div class="stat-seg <?= $s['cls'] ?>">
                <div class="stat-val"><?= $s['val'] ?></div>
                <div class="stat-lbl"><?= $s['lbl'] ?></div>
            </div>
        <?php endforeach; ?>
    </div>


    <!-- ================================================================
     HOW IT WORKS
================================================================ -->
    <section id="alur" class="process-section section">
        <div class="section-inner">
            <div class="section-head center">
                <div class="eyebrow eyebrow-teal"><i class="fa-solid fa-diagram-project"></i> Alur Kerja</div>
                <h2 class="h2">Dari Laporan hingga <em>Perbaikan</em></h2>
                <p class="lead">
                    Proses transparan dan terstruktur memastikan setiap laporan warga
                    ditangani secara akuntabel dan terukur.
                </p>
            </div>

            <div class="process-grid">
                <?php
                $steps = [
                    ['num' => '01', 'title' => 'Laporkan',   'desc' => 'Warga memfoto kerusakan, tandai lokasi GPS, dan kirim laporan lewat platform dengan mudah.'],
                    ['num' => '02', 'title' => 'Verifikasi',  'desc' => 'Admin memvalidasi laporan, mengklasifikasi tingkat keparahan, dan mendisposisi ke dinas.'],
                    ['num' => '03', 'title' => 'Perbaikan',   'desc' => 'Dinas teknis menangani di lapangan dan mendokumentasikan progres secara real-time.'],
                    ['num' => '04', 'title' => 'Selesai',     'desc' => 'Laporan ditutup, pelapor mendapat notifikasi, data masuk ke statistik BI eksekutif.'],
                ];
                foreach ($steps as $s): ?>
                    <div class="process-step">
                        <div class="process-num"><?= $s['num'] ?></div>
                        <h4><?= $s['title'] ?></h4>
                        <p><?= $s['desc'] ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>


    <!-- ================================================================
     PUBLIC DASHBOARD
================================================================ -->
    <section id="dashboard" class="dash-section section">
        <div class="section-inner">
            <div class="section-head">
                <div class="eyebrow eyebrow-teal"><i class="fa-solid fa-gauge"></i> Dashboard Publik</div>
                <h2 class="h2">Data Terkini — <em>Terbuka untuk Semua</em></h2>
                <p class="lead">Semua data tersedia tanpa perlu login. Transparansi adalah komitmen kami.</p>
            </div>

            <div class="dash-grid">

                <!-- Status Donut -->
                <div class="card">
                    <div class="card-head">
                        <span class="card-head-title">
                            <i class="fa-solid fa-chart-pie"></i> Distribusi Status
                        </span>
                        <span class="pill pill-teal"><?= $total ?> laporan</span>
                    </div>
                    <div class="card-body" style="display:flex;flex-direction:column;align-items:center;gap:18px;min-height:280px;justify-content:center;">
                        <canvas id="statusChart" style="max-height:200px;max-width:200px;"></canvas>
                        <div style="display:flex;flex-wrap:wrap;gap:10px;justify-content:center;">
                            <?php
                            $statusColors = ['#0d7377', '#d97706', '#059669', '#dc2626'];
                            $statusNames  = ['Diterima', 'Diperbaiki', 'Selesai', 'Ditolak'];
                            foreach ($statusNames as $i => $name): ?>
                                <span class="legend-item">
                                    <span class="legend-dot" style="background:<?= $statusColors[$i] ?>;"></span>
                                    <?= $name ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Trend Line -->
                <div class="card">
                    <div class="card-head">
                        <span class="card-head-title">
                            <i class="fa-solid fa-chart-line"></i> Tren Laporan Bulanan
                        </span>
                        <a href="<?= BASE_URL ?>laporan" class="btn btn-outline btn-sm">
                            Semua Laporan <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                    <div class="card-body">
                        <canvas id="trendChart" height="190"></canvas>
                    </div>
                </div>

                <!-- Map -->
                <div class="card dash-full">
                    <div class="card-head">
                        <span class="card-head-title">
                            <i class="fa-solid fa-map-location-dot"></i> Peta Sebaran Kerusakan
                        </span>
                        <div style="display:flex;align-items:center;gap:14px;flex-wrap:wrap;">
                            <div class="map-legend">
                                <span class="legend-item"><span class="legend-dot" style="background:#0d7377;"></span>Diterima</span>
                                <span class="legend-item"><span class="legend-dot" style="background:#d97706;"></span>Diperbaiki</span>
                                <span class="legend-item"><span class="legend-dot" style="background:#059669;"></span>Selesai</span>
                                <span class="legend-item"><span class="legend-dot" style="background:#6b7280;"></span>Ditolak</span>
                            </div>
                            <a href="<?= BASE_URL ?>peta" class="btn btn-outline btn-sm">
                                <i class="fa-solid fa-expand"></i> Peta Penuh
                            </a>
                        </div>
                    </div>
                    <div class="card-flush">
                        <div id="publicMap"></div>
                    </div>
                </div>

            </div>
        </div>
    </section>


    <!-- ================================================================
     CTA BAND
================================================================ -->
    <section class="cta-band" style="padding:80px 6%;">
        <div class="cta-inner" style="max-width:1160px;margin:auto;width:100%;display:grid;grid-template-columns:1fr auto;gap:48px;align-items:center;">
            <div class="cta-text">
                <h2>Temukan Kerusakan Jalan<br>di Sekitar Anda?</h2>
                <p>
                    Laporkan sekarang dan bantu pemerintah memprioritaskan perbaikan secara tepat sasaran.
                    Setiap laporan berkontribusi nyata.
                </p>
            </div>
            <div class="cta-btns">
                <a href="<?= BASE_URL ?>auth/register" class="btn btn-white btn-lg">
                    <i class="fa-solid fa-user-plus"></i> Daftar Gratis
                </a>
                <a href="<?= BASE_URL ?>auth/login" class="btn btn-ghost-dark btn-lg">
                    <i class="fa-solid fa-right-to-bracket"></i> Masuk
                </a>
            </div>
        </div>
    </section>


    <!-- ================================================================
     FOOTER
================================================================ -->
    <footer class="footer">
        <div class="footer-inner">
            <div class="footer-top">
                <div class="footer-brand">
                    <div class="footer-logo">
                        <div class="logo-mark"><i class="fa-solid fa-road"></i></div>
                        <span class="logo-name">RoadReport</span>
                    </div>
                    <p>
                        Sistem Informasi Manajemen Perbaikan Jalan berbasis
                        Business Intelligence untuk tata kelola infrastruktur
                        yang transparan dan efisien.
                    </p>
                </div>
                <div class="footer-col">
                    <h5>Platform</h5>
                    <ul>
                        <li><a href="<?= BASE_URL ?>auth/register">Buat Laporan</a></li>
                        <li><a href="<?= BASE_URL ?>peta">Peta Kerusakan</a></li>
                        <li><a href="#dashboard">Dashboard Publik</a></li>
                        <li><a href="<?= BASE_URL ?>auth/login">Login</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h5>Informasi</h5>
                    <ul>
                        <li><a href="#">Tentang RoadReport</a></li>
                        <li><a href="#">Panduan Pengguna</a></li>
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Hubungi Kami</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h5>Hukum</h5>
                    <ul>
                        <li><a href="#">Kebijakan Privasi</a></li>
                        <li><a href="#">Syarat Layanan</a></li>
                        <li><a href="#">Aksesibilitas</a></li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <span>© <?= date('Y') ?> <strong>RoadReport</strong> — Sistem Informasi Manajemen Perbaikan Jalan</span>
                <div class="badge-strip">
                    <span class="badge badge-teal">MIS</span>
                    <span class="badge badge-blue">Open Data</span>
                    <span class="badge badge-amber">BI Dashboard</span>
                </div>
            </div>
        </div>
    </footer>


    <!-- ================================================================
     SCRIPTS
================================================================ -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            /* Navbar scroll shadow */
            const nav = document.getElementById('mainNav');
            window.addEventListener('scroll', () => nav.classList.toggle('scrolled', scrollY > 10), {
                passive: true
            });

            /* Chart defaults */
            Chart.defaults.font = {
                family: "'DM Sans', sans-serif",
                size: 12
            };
            Chart.defaults.color = '#5a6f8a';

            /* ── Doughnut: Status ── */
            new Chart(document.getElementById('statusChart'), {
                type: 'doughnut',
                data: {
                    labels: <?= json_encode($statusLabels) ?>,
                    datasets: [{
                        data: <?= json_encode($statusData) ?>,
                        backgroundColor: ['#0d7377', '#d97706', '#059669', '#dc2626'],
                        borderWidth: 3,
                        borderColor: '#ffffff',
                        hoverOffset: 8,
                        borderRadius: 3,
                    }]
                },
                options: {
                    responsive: true,
                    cutout: '72%',
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: c => `  ${c.label}: ${c.parsed} laporan`
                            },
                            backgroundColor: '#0b1524',
                            padding: 12,
                            cornerRadius: 8,
                        }
                    }
                }
            });

            /* ── Line: Trend ── */
            new Chart(document.getElementById('trendChart'), {
                type: 'line',
                data: {
                    labels: <?= json_encode($trendLabels) ?>,
                    datasets: [{
                        label: 'Laporan',
                        data: <?= json_encode($trendData) ?>,
                        borderColor: '#0d7377',
                        backgroundColor: ctx => {
                            const g = ctx.chart.ctx.createLinearGradient(0, 0, 0, 220);
                            g.addColorStop(0, 'rgba(13,115,119,.15)');
                            g.addColorStop(1, 'rgba(13,115,119,0)');
                            return g;
                        },
                        fill: true,
                        tension: 0.42,
                        pointBackgroundColor: '#0d7377',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        borderWidth: 2.5,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#0b1524',
                            padding: 12,
                            cornerRadius: 8,
                            callbacks: {
                                label: c => `  ${c.parsed.y} laporan`
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#eef2f7'
                            },
                            border: {
                                display: false
                            },
                            ticks: {
                                precision: 0
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            border: {
                                display: false
                            }
                        }
                    }
                }
            });

            /* ── Leaflet Map ── */
            const map = L.map('publicMap', {
                    scrollWheelZoom: false
                })
                .setView([-6.73, 108.57], 11);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© <a href="https://openstreetmap.org/copyright">OpenStreetMap</a>',
                maxZoom: 18,
            }).addTo(map);

            const mapColors = {
                diterima: '#0d7377',
                diperbaiki: '#d97706',
                selesai: '#059669',
                ditolak: '#6b7280',
            };
            const mapLabels = {
                diterima: 'Diterima',
                diperbaiki: 'Sedang Diperbaiki',
                selesai: 'Selesai',
                ditolak: 'Ditolak',
            };

            fetch('<?= BASE_URL ?>peta/data')
                .then(r => r.json())
                .then(rows => {
                    rows.forEach(row => {
                        const lat = parseFloat(row.latitude),
                            lng = parseFloat(row.longitude);
                        if (isNaN(lat) || isNaN(lng)) return;

                        const color = mapColors[row.status] || '#8ea3b8';
                        const label = mapLabels[row.status] || row.status;

                        L.circleMarker([lat, lng], {
                            radius: 7,
                            fillColor: color,
                            color: '#fff',
                            weight: 2.5,
                            fillOpacity: .88,
                        }).addTo(map).bindPopup(`
                    <div style="font-family:'DM Sans',sans-serif;min-width:180px;">
                        <strong style="display:block;font-size:13.5px;margin-bottom:6px;">${row.nama_jalan}</strong>
                        <span style="display:inline-block;padding:3px 10px;border-radius:999px;
                                     font-size:11px;font-weight:700;
                                     background:${color}22;color:${color};">${label}</span>
                    </div>
                `, {
                            maxWidth: 240
                        });
                    });
                })
                .catch(err => console.warn('Map data error:', err));

        });
    </script>

</body>

</html>