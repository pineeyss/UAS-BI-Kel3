<?php
$extraHead = '
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css">
<style>
#map { height: 520px; border-radius: 8px; }
.leaflet-popup-content b { color: #1d4ed8; }
</style>';
require ROOT . '/app/views/partials/header.php';
?>

<div class="card">
    <div class="card-header">
        <h3><i class="fa-solid fa-map-location-dot"></i> Peta Sebaran Laporan Jalan</h3>
        <div class="map-legend-inline">
            <span class="legend-dot" style="background:#3b82f6"></span> Diterima &nbsp;
            <span class="legend-dot" style="background:#f59e0b"></span> Diperbaiki &nbsp;
            <span class="legend-dot" style="background:#22c55e"></span> Selesai
        </div>
    </div>
    <div class="card-body p-0">
        <div id="map"></div>
    </div>
</div>

<?php
$extraScript = '
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
<script>
// Koordinat default: Cirebon (sesuai data di database)
const map = L.map("map").setView([-6.73, 108.57], 11);

L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    attribution: "&copy; OpenStreetMap contributors"
}).addTo(map);

const colors = {
    diterima:   "#3b82f6",
    diperbaiki: "#f59e0b",
    selesai:    "#22c55e",
    ditolak:    "#6b7280"
};

function makeIcon(color) {
    return L.divIcon({
        className: "",
        html: `<div style="width:14px;height:14px;border-radius:50%;background:${color};border:2.5px solid #fff;box-shadow:0 1px 4px rgba(0,0,0,.4)"></div>`,
        iconSize:    [14, 14],
        iconAnchor:  [7, 7],
        popupAnchor: [0, -10]
    });
}

fetch("' . BASE_URL . 'peta/data")
    .then(r => r.json())
    .then(rows => {
        rows.forEach(row => {
            const lat   = parseFloat(row.latitude);
            const lng   = parseFloat(row.longitude);
            if (isNaN(lat) || isNaN(lng)) return;

            const status = row.status || "diterima";
            const color  = colors[status] || "#6b7280";

            L.marker([lat, lng], { icon: makeIcon(color) })
                .addTo(map)
                .bindPopup(`
                    <b>#${row.id}</b><br>
                    ${row.nama_jalan}<br>
                    Status: <b style="color:${color}">${status.charAt(0).toUpperCase() + status.slice(1)}</b>
                `);
        });
    })
    .catch(err => console.error("Gagal load data peta:", err));
</script>';
?>

<?php require ROOT . '/app/views/partials/footer.php'; ?>