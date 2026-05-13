<?php
$extraHead = '
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/MarkerCluster/1.5.3/MarkerCluster.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/MarkerCluster/1.5.3/MarkerCluster.Default.css">
<style>
#map { height: 560px; border-radius: 0 0 var(--radius) var(--radius); }
.map-stat-bar {
    display: flex;
    gap: 6px;
    padding: 14px 20px;
    border-bottom: 1px solid var(--border-soft);
    background: var(--surface-alt);
    flex-wrap: wrap;
    align-items: center;
}
.map-stat {
    display: flex;
    align-items: center;
    gap: 7px;
    padding: 5px 14px;
    border-radius: 999px;
    font-size: 12.5px;
    font-weight: 600;
    cursor: pointer;
    border: 2px solid transparent;
    transition: all .2s;
    user-select: none;
}
.map-stat.active { border-color: currentColor; }
.dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }
.map-controls {
    padding: 12px 20px;
    border-bottom: 1px solid var(--border-soft);
    display: flex;
    gap: 10px;
    align-items: center;
    flex-wrap: wrap;
}
#mapLoading {
    position: absolute;
    inset: 0;
    background: rgba(255,255,255,.8);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    border-radius: var(--radius);
    font-size: 14px;
    font-weight: 600;
    color: var(--text-muted);
    gap: 10px;
}
</style>';

require ROOT . '/app/views/partials/header.php';
?>

<div class="card" style="overflow:visible;position:relative;">
    <div class="card-header">
        <h3><i class="fa-solid fa-map-location-dot"></i> Peta Sebaran Laporan Kerusakan Jalan</h3>
        <div style="display:flex;align-items:center;gap:8px;">
            <span id="totalMarker" style="font-size:13px;color:var(--text-muted);"></span>
            <button id="btnCluster" class="btn btn-outline btn-sm" onclick="toggleCluster()">
                <i class="fa-solid fa-layer-group"></i> Cluster: ON
            </button>
        </div>
    </div>

    <!-- Filter status strip -->
    <div class="map-stat-bar">
        <span style="font-size:12px;font-weight:700;color:var(--text-muted);margin-right:4px;">Filter:</span>
        <div class="map-stat active" data-status="all" style="background:#f1f5f9;color:#475569;" onclick="filterMarkers('all', this)">
            <div class="dot" style="background:#64748b;"></div> Semua
        </div>
        <div class="map-stat" data-status="diterima" style="background:#eff6ff;color:#1e40af;" onclick="filterMarkers('diterima', this)">
            <div class="dot" style="background:#3b82f6;"></div> Diterima
        </div>
        <div class="map-stat" data-status="diperbaiki" style="background:#fff7ed;color:#c2410c;" onclick="filterMarkers('diperbaiki', this)">
            <div class="dot" style="background:#f59e0b;"></div> Diperbaiki
        </div>
        <div class="map-stat" data-status="selesai" style="background:#f0fdf4;color:#166534;" onclick="filterMarkers('selesai', this)">
            <div class="dot" style="background:#22c55e;"></div> Selesai
        </div>
    </div>

    <!-- Controls -->
    <div class="map-controls">
        <input type="text" id="searchJalan" class="form-control"
            placeholder="Cari nama jalan di peta..." style="max-width:280px;"
            oninput="searchOnMap(this.value)">
        <button class="btn btn-outline btn-sm" onclick="map.setView(defaultCenter, defaultZoom)">
            <i class="fa-solid fa-maximize"></i> Reset View
        </button>
        <button class="btn btn-outline btn-sm" onclick="locateMe()">
            <i class="fa-solid fa-location-crosshairs"></i> Lokasi Saya
        </button>
        <span id="searchResult" style="font-size:12.5px;color:var(--text-muted);"></span>
    </div>

    <!-- Map -->
    <div style="position:relative;">
        <div id="mapLoading">
            <i class="fa-solid fa-spinner fa-spin" style="color:var(--brand);"></i>
            Memuat data peta...
        </div>
        <div id="map"></div>
    </div>
</div>

<!-- Stats ringkasan bawah -->
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-top:0;">
    <div class="card" style="margin-bottom:0;">
        <div class="card-body" style="display:flex;align-items:center;gap:14px;">
            <div style="width:42px;height:42px;border-radius:10px;background:#eff6ff;color:#1d4ed8;display:flex;align-items:center;justify-content:center;font-size:18px;">
                <i class="fa-solid fa-circle-info"></i>
            </div>
            <div>
                <div id="statTotal" style="font-family:'Space Grotesk',sans-serif;font-size:22px;font-weight:800;">—</div>
                <div style="font-size:12px;color:var(--text-muted);font-weight:600;">Total Titik di Peta</div>
            </div>
        </div>
    </div>
    <div class="card" style="margin-bottom:0;">
        <div class="card-body" style="display:flex;align-items:center;gap:14px;">
            <div style="width:42px;height:42px;border-radius:10px;background:#fef2f2;color:#dc2626;display:flex;align-items:center;justify-content:center;font-size:18px;">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div>
                <div id="statBerat" style="font-family:'Space Grotesk',sans-serif;font-size:22px;font-weight:800;">—</div>
                <div style="font-size:12px;color:var(--text-muted);font-weight:600;">Tingkat Berat</div>
            </div>
        </div>
    </div>
    <div class="card" style="margin-bottom:0;">
        <div class="card-body" style="display:flex;align-items:center;gap:14px;">
            <div style="width:42px;height:42px;border-radius:10px;background:#f0fdf4;color:#16a34a;display:flex;align-items:center;justify-content:center;font-size:18px;">
                <i class="fa-solid fa-road-circle-check"></i>
            </div>
            <div>
                <div id="statSelesai" style="font-family:'Space Grotesk',sans-serif;font-size:22px;font-weight:800;">—</div>
                <div style="font-size:12px;color:var(--text-muted);font-weight:600;">Sudah Diselesaikan</div>
            </div>
        </div>
    </div>
</div>

<?php
$extraScript = '
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.5.3/leaflet.markercluster.js"></script>
<script>
const defaultCenter = [-6.73, 108.57];
const defaultZoom   = 11;

const map = L.map("map").setView(defaultCenter, defaultZoom);
L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    attribution: "© OpenStreetMap contributors",
    maxZoom: 19
}).addTo(map);

const colors = {
    diterima:   "#3b82f6",
    diperbaiki: "#f59e0b",
    selesai:    "#22c55e",
    ditolak:    "#6b7280"
};

function makeIcon(color, size = 13) {
    return L.divIcon({
        className: "",
        html: `<div style="width:${size}px;height:${size}px;border-radius:50%;background:${color};border:2.5px solid white;box-shadow:0 2px 6px rgba(0,0,0,.35);transition:.2s;"></div>`,
        iconSize:    [size, size],
        iconAnchor:  [size/2, size/2],
        popupAnchor: [0, -size/2]
    });
}

let allData       = [];
let currentFilter = "all";
let clusterMode   = true;
let clusterGroup  = L.markerClusterGroup({ chunkedLoading: true, maxClusterRadius: 50 });
let plainGroup    = L.layerGroup();

map.addLayer(clusterGroup);

fetch("' . BASE_URL . 'peta/data")
    .then(r => r.json())
    .then(rows => {
        document.getElementById("mapLoading").style.display = "none";
        allData = rows;
        renderMarkers(rows);
        updateStats(rows);
        document.getElementById("totalMarker").textContent = rows.length + " titik dimuat";
    })
    .catch(() => {
        document.getElementById("mapLoading").innerHTML = \'<i class="fa-solid fa-circle-xmark" style="color:#dc2626;"></i> Gagal memuat data.\';
    });

function buildMarker(row) {
    const lat   = parseFloat(row.latitude);
    const lng   = parseFloat(row.longitude);
    if (isNaN(lat) || isNaN(lng)) return null;

    const color  = colors[row.status] || "#64748b";
    const tingkat = row.tingkat_kerusakan
        ? `<span style="font-size:11px;background:${row.tingkat_kerusakan==="berat"?"#fef2f2":row.tingkat_kerusakan==="sedang"?"#fff7ed":"#f0fdf4"};color:${row.tingkat_kerusakan==="berat"?"#991b1b":row.tingkat_kerusakan==="sedang"?"#c2410c":"#166534"};padding:2px 7px;border-radius:999px;font-weight:700;">${row.tingkat_kerusakan.charAt(0).toUpperCase()+row.tingkat_kerusakan.slice(1)}</span>`
        : "";

    const m = L.marker([lat, lng], { icon: makeIcon(color) });
    m._rowData = row;
    m.bindPopup(`
        <div style="font-family:DM Sans,sans-serif;min-width:200px;">
            <div style="font-weight:700;font-size:13.5px;margin-bottom:6px;color:#0f172a;">${row.nama_jalan}</div>
            <div style="font-size:12px;color:#64748b;margin-bottom:4px;">
                <b>Status:</b> <span style="color:${color};font-weight:700;">${row.status.charAt(0).toUpperCase()+row.status.slice(1)}</span>
                &nbsp; ${tingkat}
            </div>
            <div style="font-size:11.5px;color:#94a3b8;margin-bottom:8px;">${row.created_at ? row.created_at.substring(0,10) : ""}</div>
            <a href="' . BASE_URL . 'pengajuan/detail/${row.id}"
               style="display:inline-block;background:#1d4ed8;color:white;font-size:12px;font-weight:600;padding:5px 12px;border-radius:6px;text-decoration:none;">
               Lihat Detail →
            </a>
        </div>
    `, { maxWidth: 260 });
    return m;
}

function renderMarkers(rows) {
    clusterGroup.clearLayers();
    plainGroup.clearLayers();

    rows.forEach(row => {
        const m = buildMarker(row);
        if (!m) return;
        if (clusterMode) clusterGroup.addLayer(m);
        else             plainGroup.addLayer(m);
    });

    if (!clusterMode && !map.hasLayer(plainGroup)) map.addLayer(plainGroup);
    if (clusterMode  && !map.hasLayer(clusterGroup)) map.addLayer(clusterGroup);
}

function filterMarkers(status, el) {
    currentFilter = status;
    document.querySelectorAll(".map-stat").forEach(e => e.classList.remove("active"));
    el.classList.add("active");

    const filtered = status === "all"
        ? allData
        : allData.filter(r => r.status === status);

    renderMarkers(filtered);
    document.getElementById("totalMarker").textContent = filtered.length + " titik ditampilkan";
}

function toggleCluster() {
    clusterMode = !clusterMode;
    document.getElementById("btnCluster").innerHTML =
        `<i class="fa-solid fa-layer-group"></i> Cluster: ${clusterMode ? "ON" : "OFF"}`;

    if (clusterMode) {
        map.removeLayer(plainGroup);
        map.addLayer(clusterGroup);
    } else {
        map.removeLayer(clusterGroup);
        map.addLayer(plainGroup);
    }

    const filtered = currentFilter === "all"
        ? allData
        : allData.filter(r => r.status === currentFilter);
    renderMarkers(filtered);
}

function searchOnMap(q) {
    const resultEl = document.getElementById("searchResult");
    if (!q.trim()) { resultEl.textContent = ""; return; }

    const found = allData.filter(r =>
        r.nama_jalan.toLowerCase().includes(q.toLowerCase())
    );
    resultEl.textContent = found.length + " hasil ditemukan";

    if (found.length > 0) {
        const first = found[0];
        map.setView([parseFloat(first.latitude), parseFloat(first.longitude)], 16);
    }
}

function locateMe() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(pos => {
            map.setView([pos.coords.latitude, pos.coords.longitude], 15);
            L.circle([pos.coords.latitude, pos.coords.longitude], {
                radius: 100, color: "#1d4ed8", fillOpacity: .15
            }).addTo(map);
        }, () => alert("Tidak dapat mengakses lokasi Anda."));
    }
}

function updateStats(rows) {
    document.getElementById("statTotal").textContent   = rows.length;
    document.getElementById("statBerat").textContent   = rows.filter(r => r.tingkat_kerusakan === "berat").length;
    document.getElementById("statSelesai").textContent = rows.filter(r => r.status === "selesai").length;
}
</script>';
?>
<?php require ROOT . '/app/views/partials/footer.php'; ?>