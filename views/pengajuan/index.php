<?php
// views/pengajuan/index.php
// Required: $data, $filter
?>

<div class="page-body">
    <div class="card">

        <!-- Filter bar -->
        <form class="filter-bar" method="GET" action="">
            <!-- Search -->
            <div class="search-box">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <circle cx="11" cy="11" r="7" />
                    <path d="M21 21l-4.35-4.35" stroke-linecap="round" />
                </svg>
                <input
                    type="text"
                    name="search"
                    placeholder="Cari no. pengajuan, pelapor, lokasi…"
                    value="<?= htmlspecialchars($filter['search']) ?>" />
            </div>

            <!-- Status filter -->
            <select name="status">
                <option value="">Semua Status</option>
                <?php foreach (['Pending', 'Diproses', 'Selesai', 'Ditolak'] as $s): ?>
                    <option value="<?= $s ?>" <?= $filter['status'] === $s ? 'selected' : '' ?>><?= $s ?></option>
                <?php endforeach; ?>
            </select>

            <!-- Tingkat filter -->
            <select name="tingkat">
                <option value="">Semua Tingkat</option>
                <?php foreach (['Ringan', 'Sedang', 'Berat'] as $t): ?>
                    <option value="<?= $t ?>" <?= $filter['tingkat'] === $t ? 'selected' : '' ?>><?= $t ?></option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="btn btn-primary btn-sm">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
                    <path d="M3 4h18M6 8h12M10 12h4" stroke-linecap="round" />
                </svg>
                Filter
            </button>

            <?php if ($filter['search'] || $filter['status'] || $filter['tingkat']): ?>
                <a href="<?= APP_URL ?>/pengajuan.php" class="btn btn-ghost btn-sm">Reset</a>
            <?php endif; ?>

            <?php if (isAdmin()): ?>
                <a href="<?= APP_URL ?>/tambah.php" class="btn btn-primary btn-sm" style="margin-left:auto">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
                        <path d="M12 5v14M5 12h14" stroke-linecap="round" />
                    </svg>
                    Tambah
                </a>
            <?php endif; ?>
        </form>

        <!-- Table -->
        <div class="tbl-wrap">
            <table>
                <thead>
                    <tr>
                        <th>No. Pengajuan</th>
                        <th>Pelapor</th>
                        <th>Kecamatan</th>
                        <th>Jenis</th>
                        <th>Tingkat</th>
                        <th>Status</th>
                        <?php if (isAdmin()): ?><th>Oleh</th><?php endif; ?>
                        <th>Tanggal</th>
                        <th style="width:64px"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($data)): ?>
                        <tr>
                            <td colspan="<?= isAdmin() ? 9 : 8 ?>">
                                <div class="state-empty">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                                        <circle cx="11" cy="11" r="7" />
                                        <path d="M21 21l-4.35-4.35" stroke-linecap="round" />
                                    </svg>
                                    <h4>Tidak ada data</h4>
                                    <p>Coba ubah filter pencarian Anda.</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($data as $row): ?>
                            <tr>
                                <td class="text-mono text-blue"><?= htmlspecialchars($row['no_pengajuan']) ?></td>
                                <td style="font-weight:500"><?= htmlspecialchars($row['nama_pelapor']) ?></td>
                                <td class="text-sm text-muted"><?= htmlspecialchars($row['kecamatan']) ?></td>
                                <td class="text-sm"><?= htmlspecialchars($row['jenis_kerusakan']) ?></td>
                                <td><?= tingkatBadge($row['tingkat_kerusakan']) ?></td>
                                <td><?= statusBadge($row['status']) ?></td>
                                <?php if (isAdmin()): ?>
                                    <td class="text-sm text-muted"><?= htmlspecialchars($row['nama_user'] ?? '—') ?></td>
                                <?php endif; ?>
                                <td class="text-sm text-muted"><?= date('d/m/Y', strtotime($row['created_at'])) ?></td>
                                <td>
                                    <div class="actions">
                                        <button class="action-btn" onclick="openDetail(<?= $row['id'] ?>)" title="Lihat detail">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                                <circle cx="12" cy="12" r="3" />
                                            </svg>
                                        </button>
                                        <?php if (isAdmin()): ?>
                                            <button class="action-btn danger" onclick="deleteItem(<?= $row['id'] ?>)" title="Hapus">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                    <polyline points="3,6 5,6 21,6" />
                                                    <path d="M19,6l-1,14H6L5,6" stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M9,6V4h6v2" stroke-linecap="round" />
                                                </svg>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Info footer -->
        <?php if (!empty($data)): ?>
            <div style="padding:14px 22px;border-top:1px solid var(--border);font-size:.78rem;color:var(--ink-muted);">
                Menampilkan <strong><?= count($data) ?></strong> data
                <?php if ($filter['status'] || $filter['tingkat'] || $filter['search']): ?>
                    (difilter)
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </div>
</div>