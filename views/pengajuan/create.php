<?php
// views/pengajuan/create.php
?>

<div class="page-body">
    <div class="card" style="max-width:800px">

        <div class="card-head">
            <div class="card-head-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <circle cx="12" cy="12" r="9" />
                    <path d="M12 8v8M8 12h8" stroke-linecap="round" />
                </svg>
                Form Pengajuan Perbaikan Jalan
            </div>
        </div>

        <div class="card-body">
            <form id="formPengajuan" enctype="multipart/form-data" onsubmit="submitPengajuan(event)">
                <input type="hidden" name="csrf_token" value="<?= csrf() ?>" />

                <!-- Section: Identitas Pelapor -->
                <div style="margin-bottom:28px">
                    <p style="font-size:.75rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--ink-muted);margin-bottom:14px;border-bottom:1px solid var(--border);padding-bottom:8px;">
                        Identitas Pelapor
                    </p>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="nama_pelapor">Nama Lengkap <span style="color:var(--red)">*</span></label>
                            <input type="text" id="nama_pelapor" name="nama_pelapor" placeholder="Nama pelapor" required />
                        </div>
                        <div class="form-group">
                            <label for="no_hp">Nomor HP</label>
                            <input type="tel" id="no_hp" name="no_hp" placeholder="08xxxxxxxxxx" />
                        </div>
                    </div>
                </div>

                <!-- Section: Lokasi Kerusakan -->
                <div style="margin-bottom:28px">
                    <p style="font-size:.75rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--ink-muted);margin-bottom:14px;border-bottom:1px solid var(--border);padding-bottom:8px;">
                        Lokasi Kerusakan
                    </p>
                    <div class="form-group" style="margin-bottom:14px">
                        <label for="lokasi">Alamat Lengkap <span style="color:var(--red)">*</span></label>
                        <textarea id="lokasi" name="lokasi" rows="2" placeholder="Jl. Contoh No. 1, RT 01/RW 02…" required></textarea>
                    </div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="kecamatan">Kecamatan <span style="color:var(--red)">*</span></label>
                            <input type="text" id="kecamatan" name="kecamatan" placeholder="Nama kecamatan" required />
                        </div>
                        <div class="form-group">
                            <label for="kelurahan">Kelurahan / Desa <span style="color:var(--red)">*</span></label>
                            <input type="text" id="kelurahan" name="kelurahan" placeholder="Nama kelurahan" required />
                        </div>
                    </div>
                </div>

                <!-- Section: Detail Kerusakan -->
                <div style="margin-bottom:28px">
                    <p style="font-size:.75rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--ink-muted);margin-bottom:14px;border-bottom:1px solid var(--border);padding-bottom:8px;">
                        Detail Kerusakan
                    </p>
                    <div class="form-grid" style="margin-bottom:14px">
                        <div class="form-group">
                            <label for="jenis_kerusakan">Jenis Kerusakan <span style="color:var(--red)">*</span></label>
                            <select id="jenis_kerusakan" name="jenis_kerusakan" required>
                                <option value="">— Pilih jenis —</option>
                                <option value="Jalan Berlubang">Jalan Berlubang</option>
                                <option value="Aspal Retak">Aspal Retak</option>
                                <option value="Jalan Amblas">Jalan Amblas</option>
                                <option value="Jalan Banjir">Jalan Banjir</option>
                                <option value="Marka Jalan Pudar">Marka Jalan Pudar</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="tingkat_kerusakan">Tingkat Kerusakan <span style="color:var(--red)">*</span></label>
                            <select id="tingkat_kerusakan" name="tingkat_kerusakan" required>
                                <option value="">— Pilih tingkat —</option>
                                <option value="Ringan">Ringan — Tidak menghalangi lalu lintas</option>
                                <option value="Sedang">Sedang — Mengganggu kenyamanan</option>
                                <option value="Berat">Berat — Membahayakan pengguna jalan</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="num_potholes">Estimasi Jumlah Lubang</label>
                            <input type="number" id="num_potholes" name="num_potholes" placeholder="0" min="0" max="9999" />
                            <span class="form-hint">Isi 0 jika bukan jalan berlubang</span>
                        </div>
                    </div>
                    <div class="form-group" style="margin-top:14px">
                        <label for="deskripsi">Deskripsi Tambahan</label>
                        <textarea id="deskripsi" name="deskripsi" rows="3" placeholder="Jelaskan kondisi kerusakan secara singkat…"></textarea>
                    </div>
                </div>

                <!-- Section: Foto -->
                <div style="margin-bottom:32px">
                    <p style="font-size:.75rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--ink-muted);margin-bottom:14px;border-bottom:1px solid var(--border);padding-bottom:8px;">
                        Foto Dokumentasi
                    </p>
                    <label class="file-upload" for="foto" id="fileLabel">
                        <input type="file" id="foto" name="foto" accept=".jpg,.jpeg,.png,.webp" onchange="previewFoto(this)" />
                        <div class="file-upload-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4" stroke-linecap="round" stroke-linejoin="round" />
                                <polyline points="17,8 12,3 7,8" stroke-linecap="round" stroke-linejoin="round" />
                                <line x1="12" y1="3" x2="12" y2="15" stroke-linecap="round" />
                            </svg>
                        </div>
                        <div class="file-upload-text">
                            <strong>Klik untuk upload</strong> atau drag & drop<br>
                            <span style="font-size:.72rem">JPG, PNG, WebP &bull; Maks 5MB</span>
                        </div>
                    </label>
                </div>

                <!-- Submit -->
                <div style="display:flex;justify-content:flex-end;gap:10px">
                    <a href="<?= APP_URL ?>/pengajuan.php" class="btn btn-ghost">Batal</a>
                    <button type="submit" class="btn btn-primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                            <path d="M5 12h14M12 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        Kirim Pengajuan
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>