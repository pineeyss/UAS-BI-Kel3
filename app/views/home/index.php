<?php

/** @var array $kpi */
/** @var array $byStatus */
/** @var array $trend */

$total      = $kpi['total'] ?? 0;
$diterima   = $kpi['diterima'] ?? 0;
$diperbaiki = $kpi['diperbaiki'] ?? 0;
$selesai    = $kpi['selesai'] ?? 0;

$progress = $total > 0
    ? round(($selesai / $total) * 100)
    : 0;

$statusLabels = [];
$statusData = [];

foreach ($byStatus as $s){

    $statusLabels[] = ucfirst($s['status']);
    $statusData[]   = (int)$s['jumlah'];

}

$trendLabels = array_column($trend,'bulan');
$trendData   = array_map('intval',array_column($trend,'jumlah'));

?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>SIJALAN MIS</title>

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css">

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>

<link rel="preconnect" href="https://fonts.googleapis.com">

<link rel="preconnect"
href="https://fonts.gstatic.com"
crossorigin>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
rel="stylesheet">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:'Inter',sans-serif;
    background:#f5f7fb;
    color:#0f172a;
}

/* ================= NAVBAR ================= */

.navbar{
    position:sticky;
    top:0;
    z-index:1000;
    background:rgba(255,255,255,.9);
    backdrop-filter:blur(10px);
    border-bottom:1px solid #e2e8f0;
    padding:18px 5%;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.logo{
    display:flex;
    align-items:center;
    gap:12px;
    font-size:28px;
    font-weight:800;
    color:#2563eb;
}

.logo i{
    font-size:30px;
}

.nav-right{
    display:flex;
    align-items:center;
    gap:14px;
}

.btn{
    border:none;
    outline:none;
    cursor:pointer;
    text-decoration:none;
    font-weight:600;
    padding:12px 22px;
    border-radius:12px;
    transition:.25s;
    display:inline-flex;
    align-items:center;
    gap:8px;
}

.btn-primary{
    background:#2563eb;
    color:white;
    box-shadow:0 6px 20px rgba(37,99,235,.25);
}

.btn-primary:hover{
    transform:translateY(-2px);
    background:#1d4ed8;
}

/* ================= CONTAINER ================= */

.container{
    width:min(1400px,92%);
    margin:auto;
    padding:30px 0 50px;
}

/* ================= HERO ================= */

.hero{
    position:relative;
    overflow:hidden;
    background:
    linear-gradient(135deg,#1d4ed8,#2563eb,#3b82f6);
    border-radius:28px;
    padding:90px 50px;
    margin-bottom:30px;
    color:white;
    box-shadow:
    0 20px 50px rgba(37,99,235,.22);
}

.hero::before{
    content:'';
    position:absolute;
    width:500px;
    height:500px;
    border-radius:50%;
    background:rgba(255,255,255,.08);
    top:-200px;
    right:-120px;
}

.hero::after{
    content:'';
    position:absolute;
    width:300px;
    height:300px;
    border-radius:50%;
    background:rgba(255,255,255,.06);
    bottom:-140px;
    left:-100px;
}

.hero-content{
    position:relative;
    z-index:2;
}

.hero-badge{
    display:inline-block;
    padding:8px 18px;
    border-radius:999px;
    background:rgba(255,255,255,.14);
    margin-bottom:20px;
    font-size:14px;
    font-weight:600;
    backdrop-filter:blur(6px);
}

.hero h1{
    font-size:72px;
    line-height:1;
    margin-bottom:18px;
    font-weight:900;
    letter-spacing:-2px;
}

.hero p{
    font-size:21px;
    max-width:760px;
    line-height:1.7;
    opacity:.92;
    margin-bottom:36px;
}

/* ================= KPI ================= */

.hero-stats{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:20px;
}

.hero-stat{
    background:rgba(255,255,255,.12);
    backdrop-filter:blur(10px);
    border:1px solid rgba(255,255,255,.15);
    border-radius:22px;
    padding:28px;
    transition:.3s;
}

.hero-stat:hover{
    transform:translateY(-5px);
}

.hero-stat h2{
    font-size:42px;
    margin-bottom:8px;
    font-weight:800;
}

.hero-stat span{
    font-size:15px;
    opacity:.9;
}

/* ================= GRID ================= */

.dashboard-grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:24px;
}

.full-width{
    grid-column:1/-1;
}

/* ================= CARD ================= */

.card{
    background:white;
    border-radius:24px;
    overflow:hidden;
    border:1px solid #e2e8f0;
    box-shadow:
    0 8px 30px rgba(15,23,42,.04);
    transition:.3s;
}

.card:hover{
    transform:translateY(-4px);
    box-shadow:
    0 14px 40px rgba(15,23,42,.08);
}

.card-header{
    padding:22px 26px;
    border-bottom:1px solid #f1f5f9;
    font-size:20px;
    font-weight:700;
    display:flex;
    align-items:center;
    gap:12px;
}

.card-header i{
    color:#2563eb;
}

.card-body{
    padding:26px;
}

/* ================= MAP ================= */

#map{
    height:550px;
    border-radius:18px;
    overflow:hidden;
}

/* ================= FOOTER ================= */

.footer{
    margin-top:50px;
    background:#0f172a;
    color:white;
    text-align:center;
    padding:40px 20px;
    font-size:15px;
}

/* ================= RESPONSIVE ================= */

@media(max-width:1100px){

    .hero-stats{
        grid-template-columns:1fr 1fr;
    }

}

@media(max-width:900px){

    .dashboard-grid{
        grid-template-columns:1fr;
    }

    .hero{
        padding:70px 30px;
    }

    .hero h1{
        font-size:52px;
    }

    .hero p{
        font-size:17px;
    }

}

@media(max-width:600px){

    .hero-stats{
        grid-template-columns:1fr;
    }

    .navbar{
        padding:16px 20px;
    }

    .logo{
        font-size:22px;
    }

    .hero h1{
        font-size:42px;
    }

}

</style>

</head>

<body>

<div class="navbar">

    <div class="logo">

        <i class="fa-solid fa-road"></i>

        SIJALAN

    </div>

    <div class="nav-right">

        <a href="<?= BASE_URL ?>auth/login"
           class="btn btn-primary">

           <i class="fa-solid fa-right-to-bracket"></i>

           Login

        </a>

    </div>

</div>

<div class="container">

    <div class="hero">

        <div class="hero-content">

            <div class="hero-badge">
                BUSINESS INTELLIGENCE DASHBOARD
            </div>

            <h1>SIJALAN</h1>

            <p>
                Management Information System Monitoring Perbaikan Jalan
                berbasis Business Intelligence untuk membantu visualisasi,
                monitoring, dan pengambilan keputusan secara real-time.
            </p>

            <div class="hero-stats">

                <div class="hero-stat">

                    <h2><?= number_format($total) ?></h2>

                    <span>Total Laporan</span>

                </div>

                <div class="hero-stat">

                    <h2><?= number_format($diterima) ?></h2>

                    <span>Laporan Diterima</span>

                </div>

                <div class="hero-stat">

                    <h2><?= number_format($diperbaiki) ?></h2>

                    <span>Sedang Diperbaiki</span>

                </div>

                <div class="hero-stat">

                    <h2><?= $progress ?>%</h2>

                    <span>Tingkat Penyelesaian</span>

                </div>

            </div>

        </div>

    </div>

    <div class="dashboard-grid">

        <div class="card">

            <div class="card-header">

                <i class="fa-solid fa-chart-pie"></i>

                Distribusi Status Laporan

            </div>

            <div class="card-body">

                <canvas id="statusChart"></canvas>

            </div>

        </div>

        <div class="card">

            <div class="card-header">

                <i class="fa-solid fa-chart-line"></i>

                Tren Laporan Bulanan

            </div>

            <div class="card-body">

                <canvas id="trendChart"></canvas>

            </div>

        </div>

        <div class="card full-width">

            <div class="card-header">

                <i class="fa-solid fa-map-location-dot"></i>

                Peta Sebaran Kerusakan Jalan

            </div>

            <div class="card-body">

                <div id="map"></div>

            </div>

        </div>

    </div>

</div>

<div class="footer">

    © <?= date('Y') ?> SIJALAN MIS —
    Monitoring Perbaikan Jalan Berbasis Business Intelligence

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

<script>

new Chart(document.getElementById("statusChart"),{

    type:"doughnut",

    data:{

        labels:<?= json_encode($statusLabels) ?>,

        datasets:[{

            data:<?= json_encode($statusData) ?>,

            backgroundColor:[
                "#22c55e",
                "#f59e0b",
                "#3b82f6",
                "#ef4444"
            ]

        }]

    }

});

new Chart(document.getElementById("trendChart"),{

    type:"line",

    data:{

        labels:<?= json_encode($trendLabels) ?>,

        datasets:[{

            label:"Jumlah Laporan",

            data:<?= json_encode($trendData) ?>,

            borderColor:"#2563eb",

            backgroundColor:"rgba(37,99,235,.1)",

            fill:true,

            tension:0.4

        }]

    }

});

const map = L.map("map").setView([-6.73,108.57],11);

L.tileLayer(
"https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",
{
    attribution:"© OpenStreetMap"
}
).addTo(map);

const colors = {

    diterima:"#3b82f6",
    diperbaiki:"#f59e0b",
    selesai:"#22c55e",
    ditolak:"#ef4444"

};

fetch("<?= BASE_URL ?>peta/data")

.then(r => r.json())

.then(rows => {

    rows.forEach(row => {

        const lat = parseFloat(row.latitude);
        const lng = parseFloat(row.longitude);

        if(isNaN(lat) || isNaN(lng)) return;

        const color = colors[row.status] || "#64748b";

        L.circleMarker([lat,lng],{

            radius:8,
            fillColor:color,
            color:"#fff",
            weight:2,
            fillOpacity:1

        })

        .addTo(map)

        .bindPopup(

            `<b>${row.nama_jalan}</b><br>
             Status: ${row.status}`

        );

    });

});

</script>

</body>
</html>
