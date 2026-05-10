<?php
// views/dashboard/index.php
// Required: $stats, $latest  (from DashboardController)
?>

<div class="page-body">

    <!-- ===== Stats Grid ===== -->
    <div class="stats-grid">

        <div class="stat-card">
            <div class="stat-icon icon-blue">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M9 12h6M9 16h4M17 3H7a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 002-2V7l-4-4z" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M17 3v4h4" stroke-linecap="round" />
                </svg>
            </div>
            <div class="stat-body">
                <span class="stat-label">Total Pengajuan</span>
                <span class="stat-value"><?= number_format($stats['total'] ?? 0) ?></span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon icon-amber">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <circle cx="12" cy="12" r="9" />
                    <path d="M12 8v4l3 3" stroke-linecap="round" />
                </svg>
            </div>
            <div class="stat-body">
                <span class="stat-label">Pending</span>
                <span class="stat-value"><?= number_format($stats['pending'] ?? 0) ?></span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon icon-sky">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <polyline points="22,12 18,12 15,21 9,3 6,12 2,12" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>
            <div class="stat-body">
                <span class="stat-label">Diproses</span>
                <span class="stat-value"><?= number_format($stats['diproses'] ?? 0) ?></span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon icon-green">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <polyline points="20,6 9,17 4,12" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>
            <div class="stat-body">
                <span class="stat-label">Selesai</span>
                <span class="stat-value"><?= number_format($stats['selesai'] ?? 0) ?></span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon icon-red">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                    <line x1="12" y1="9" x2="12" y2="13" stroke-linecap="round" />
                    <line x1="12" y1="17" x2="12.01" y2="17" stroke-linecap="round" />
                </svg>
            </div>
            <div class="stat-body">
                <span class="stat-label">Prioritas Berat</span>
                <span class="stat-value"><?= number_format($stats['berat'] ?? 0) ?></span>
            </div>
        </div>

    </div>
    <!-- /stats-grid -->

    <!-- ===== Latest Table ===== -->
    <div class="card">
        <div class="card-head">
            <div class="card-head-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M12 20h9M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4L16.5 3.5z" stroke-linecap="round" />
                </svg>
                Pengajuan Terbaru
            </div>
            <a href="<?= APP_URL ?>/pengajuan.php" class="btn btn-ghost btn-sm">
                Lihat semua
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
                    <path d="M5 12h14M12 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </a>
        </div>

        <div class="tbl-wrap">
            <table>
                <thead>
                    <tr>
                        <th>No. Pengajuan</th>
                        <th>Pelapor</th>
                        <th>Lokasi</th>
                        <th>Jenis</th>
                        <th>Tingkat</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($latest)): ?>
                        <tr>
                            <td colspan="8">
                                <div class="state-empty">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                                        <path d="M9 12h6M9 16h4M17 3H7a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 002-2V7l-4-4z" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <h4>Belum ada pengajuan</h4>
                                    <p>Data pengajuan akan muncul di sini.</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($latest as $row): ?>
                            <tr>
                                <td class="text-mono text-blue"><?= htmlspecialchars($row['no_pengajuan']) ?></td>
                                <td style="font-weight:500"><?= htmlspecialchars($row['nama_pelapor']) ?></td>
                                <td class="text-muted text-sm" style="max-width:200px">
                                    <?= htmlspecialchars(mb_substr($row['lokasi'], 0, 45)) ?><?= mb_strlen($row['lokasi']) > 45 ? '…' : '' ?>
                                </td>
                                <td class="text-sm"><?= htmlspecialchars($row['jenis_kerusakan']) ?></td>
                                <td><?= tingkatBadge($row['tingkat_kerusakan']) ?></td>
                                <td><?= statusBadge($row['status']) ?></td>
                                <td class="text-muted text-sm"><?= date('d/m/Y', strtotime($row['created_at'])) ?></td>
                                <td>
                                    <div class="actions">
                                        <button class="action-btn" onclick="openDetail(<?= $row['id'] ?>)" title="Detail">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                                <circle cx="12" cy="12" r="3" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>