<?php

/** @var string $action */
/** @var array  $errors */
/** @var array  $old    */
/** @var array  $item   */
$action = $action ?? 'create';
$errors = $errors ?? [];
$old    = $old    ?? [];
$item   = $item   ?? [];

// $extraHead HARUS didefinisikan sebelum require header.php agar CSS termuat
$extraHead = '
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css">
<style>
#pickMap { height: 320px; border-radius: var(--radius-sm); border: 1px solid var(--border); cursor: crosshair; }
.coords-display { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 10px; }
</style>';

require ROOT . '/app/views/partials/header.php';

$actionUrl = $action === 'create'
    ? BASE_URL . 'pengajuan/create'
    : BASE_URL . 'pengajuan/edit/' . ($item['id'] ?? '');
?>

<!-- Breadcrumb -->
<div style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--text-muted);margin-bottom:20px;">
    <a href="<?= BASE_URL ?>pengajuan" style="color:var(--brand);">Laporan</a>
    <i class="fa-solid fa-chevron-right" style="font-size:10px;"></i>
    <span><?= $action === 'create' ? 'Buat Laporan Baru' : 'Edit Laporan #' . ($item['id'] ?? '') ?></span>
</div>

<div class="card" style="max-width:860px;">
    <div class="card-header">
        <h3>
            <i class="fa-solid fa-<?= $action === 'create' ? 'plus-circle' : 'pen' ?>"></i>
            <?= htmlspecialchars($title ?? '') ?>
        </h3>
        <a href="<?= BASE_URL ?>pengajuan" class="btn btn-outline btn-sm">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
    </div>
    <div class="card-body">
        <form method="POST" action="<?= $actionUrl ?>" enctype="multipart/form-data">

            <!-- Nama Jalan -->
            <div class="form-group" style="margin-bottom:18px;">
                <label>Nama / Ruas Jalan <span class="required">*</span></label>
                <input type="text" name="nama_jalan"
                    class="form-control <?= !empty($errors['nama_jalan']) ? 'is-invalid' : '' ?>"
                    value="<?= htmlspecialchars($old['nama_jalan'] ?? $item['nama_jalan'] ?? '') ?>"
                    placeholder="Contoh: Jl. Ahmad Yani KM 5 Segmen 2">
                <?php if (!empty($errors['nama_jalan'])): ?>
                    <div class="invalid-feedback"><?= $errors['nama_jalan'] ?></div>
                <?php endif; ?>
            </div>

            <!-- Deskripsi -->
            <div class="form-group" style="margin-bottom:18px;">
                <label>Deskripsi Kerusakan</label>
                <textarea name="deskripsi" class="form-control" rows="3"
                    placeholder="Jelaskan kondisi kerusakan jalan, misal: lubang besar di tengah jalan, aspal terkelupas..."><?= htmlspecialchars($old['deskripsi'] ?? $item['deskripsi'] ?? '') ?></textarea>
            </div>

            <!-- Foto -->
            <div class="form-group" style="margin-bottom:18px;">
                <label>Foto Kerusakan <span style="color:var(--text-light);font-weight:400;">(opsional, maks 2MB)</span></label>
                <input type="file" name="foto_path"
                    class="form-control <?= !empty($errors['foto_path']) ? 'is-invalid' : '' ?>"
                    accept="image/jpeg,image/png,image/webp">
                <?php if (!empty($item['foto_path'])): ?>
                    <div class="foto-preview">
                        <img src="<?= BASE_URL ?><?= htmlspecialchars($item['foto_path']) ?>" alt="Foto saat ini">
                        <small>Foto saat ini — kosongkan jika tidak ingin menggantinya.</small>
                    </div>
                <?php endif; ?>
                <?php if (!empty($errors['foto_path'])): ?>
                    <div class="invalid-feedback"><?= $errors['foto_path'] ?></div>
                <?php endif; ?>
            </div>

            <!-- Koordinat via klik peta -->
            <div class="form-group" style="margin-bottom:8px;">
                <label><i class="fa-solid fa-map-pin" style="color:var(--brand);"></i> Lokasi (klik peta untuk menentukan koordinat) <span class="required">*</span></label>
                <div id="pickMap"></div>
                <div class="coords-display">
                    <div>
                        <label style="font-size:12px;color:var(--text-muted);">Latitude</label>
                        <input type="text" name="latitude" id="lat_input"
                            class="form-control <?= !empty($errors['latitude']) ? 'is-invalid' : '' ?>"
                            value="<?= htmlspecialchars($old['latitude'] ?? $item['latitude'] ?? '') ?>"
                            placeholder="-6.7345..." readonly>
                        <?php if (!empty($errors['latitude'])): ?>
                            <div class="invalid-feedback"><?= $errors['latitude'] ?></div>
                        <?php endif; ?>
                    </div>
                    <div>
                        <label style="font-size:12px;color:var(--text-muted);">Longitude</label>
                        <input type="text" name="longitude" id="lng_input"
                            class="form-control <?= !empty($errors['longitude']) ? 'is-invalid' : '' ?>"
                            value="<?= htmlspecialchars($old['longitude'] ?? $item['longitude'] ?? '') ?>"
                            placeholder="108.5678..." readonly>
                        <?php if (!empty($errors['longitude'])): ?>
                            <div class="invalid-feedback"><?= $errors['longitude'] ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                <small style="font-size:12px;color:var(--text-light);margin-top:6px;display:block;">
                    <i class="fa-solid fa-circle-info"></i> Klik pada peta untuk mengisi koordinat secara otomatis, atau isi manual jika tahu koordinat GPS.
                </small>
            </div>

            <!-- Action buttons -->
            <div class="form-actions" style="padding-top:16px;border-top:1px solid var(--border-soft);margin-top:20px;">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <?= $action === 'create' ? 'Kirim Laporan' : 'Simpan Perubahan' ?>
                </button>
                <a href="<?= BASE_URL ?>pengajuan" class="btn btn-outline">
                    <i class="fa-solid fa-xmark"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>

<?php
$initLat = (float)($old['latitude']  ?? $item['latitude']  ?? -6.73);
$initLng = (float)($old['longitude'] ?? $item['longitude'] ?? 108.57);
$hasCoord = ($initLat !== -6.73 || $initLng !== 108.57);

$extraScript = '
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
<script>
const defaultLat = ' . $initLat . ';
const defaultLng = ' . $initLng . ';
const hasCoord   = ' . ($hasCoord ? 'true' : 'false') . ';

const map = L.map("pickMap").setView([defaultLat, defaultLng], hasCoord ? 15 : 11);
L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    attribution: "© OpenStreetMap"
}).addTo(map);

let marker = null;

function placeMarker(lat, lng) {
    if (marker) marker.remove();
    marker = L.marker([lat, lng], { draggable: true }).addTo(map)
        .bindPopup("Geser untuk sesuaikan posisi")
        .openPopup();
    marker.on("dragend", e => {
        const pos = e.target.getLatLng();
        document.getElementById("lat_input").value = pos.lat.toFixed(8);
        document.getElementById("lng_input").value = pos.lng.toFixed(8);
    });
    document.getElementById("lat_input").value = lat.toFixed(8);
    document.getElementById("lng_input").value = lng.toFixed(8);
}

if (hasCoord) placeMarker(defaultLat, defaultLng);

map.on("click", e => {
    placeMarker(e.latlng.lat, e.latlng.lng);
    map.setView([e.latlng.lat, e.latlng.lng], map.getZoom());
});

// Tombol lokasi saat ini (GPS)
const locBtn = L.control({ position: "topright" });
locBtn.onAdd = () => {
    const btn = L.DomUtil.create("button", "");
    btn.style = "background:white;border:1px solid #ccc;padding:8px 12px;border-radius:6px;cursor:pointer;font-size:13px;box-shadow:0 2px 6px rgba(0,0,0,.15);";
    btn.innerHTML = \'<i class="fa-solid fa-location-crosshairs"></i> Lokasi Saya\';
    btn.onclick = (e) => {
        e.preventDefault();
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(pos => {
                const lat = pos.coords.latitude;
                const lng = pos.coords.longitude;
                placeMarker(lat, lng);
                map.setView([lat, lng], 16);
            }, () => alert("Tidak dapat mengakses lokasi Anda."));
        }
    };
    return btn;
};
locBtn.addTo(map);
</script>';
?>
<?php require ROOT . '/app/views/partials/footer.php'; ?>