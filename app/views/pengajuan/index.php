<?php

/** @var array  $pengajuan  */
/** @var array  $filter     */
/** @var int    $page       */
/** @var int    $perPage    */
/** @var int    $total      */
/** @var int    $totalPages */
$pengajuan  = $pengajuan  ?? [];
$filter     = $filter     ?? [];
$page       = $page       ?? 1;
$perPage    = $perPage    ?? 15;
$total      = $total      ?? 0;
$totalPages = $totalPages ?? 1;

$role = $_SESSION['role'] ?? '';
require ROOT . '/app/views/partials/header.php';
?>

<div class="card">
    <div class="card-header">
        <h3><i class="fa-solid fa-clipboard-list"></i> Daftar Laporan Jalan</h3>
        <div style="display:flex;gap:8px;align-items:center;">
            <span style="font-size:13px;color:var(--text-muted);"><?= number_format($total) ?> data</span>
            <?php if (in_array($role, ['masyarakat'])): ?>
                <a href="<?= BASE_URL ?>pengajuan/create" class="btn btn-primary btn-sm">
                    <i class="fa-solid fa-plus"></i> Buat Laporan
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="card-body">

        <!-- Filter Bar -->
        <form method="GET" action="<?= BASE_URL ?>pengajuan">
            <div class="filter-bar">
                <input type="text" name="search" class="form-control"
                    placeholder="Cari nama jalan..."
                    value="<?= htmlspecialchars($filter['search'] ?? '') ?>"
                    style="min-width:220px;">

                <select name="status" class="form-control">
                    <option value="">Semua Status</option>
                    <?php foreach (['diterima' => 'Diterima', 'diperbaiki' => 'Diperbaiki', 'selesai' => 'Selesai', 'ditolak' => 'Ditolak'] as $val => $lbl): ?>
                        <option value="<?= $val ?>" <?= ($filter['status'] ?? '') === $val ? 'selected' : '' ?>><?= $lbl ?></option>
                    <?php endforeach; ?>
                </select>

                <select name="tingkat" class="form-control">
                    <option value="">Semua Tingkat</option>
                    <?php foreach (['ringan' => 'Ringan', 'sedang' => 'Sedang', 'berat' => 'Berat'] as $val => $lbl): ?>
                        <option value="<?= $val ?>" <?= ($filter['tingkat'] ?? '') === $val ? 'selected' : '' ?>><?= $lbl ?></option>
                    <?php endforeach; ?>
                </select>

                <input type="date" name="tanggal_dari" class="form-control"
                    value="<?= htmlspecialchars($filter['tanggal_dari'] ?? '') ?>"
                    title="Dari tanggal">

                <input type="date" name="tanggal_sampai" class="form-control"
                    value="<?= htmlspecialchars($filter['tanggal_sampai'] ?? '') ?>"
                    title="Sampai tanggal">

                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-magnifying-glass"></i> Cari
                </button>
                <a href="<?= BASE_URL ?>pengajuan" class="btn btn-outline">
                    <i class="fa-solid fa-rotate-left"></i> Reset
                </a>
            </div>
        </form>

        <!-- Active filter chips -->
        <?php
        $activeFilters = array_filter($filter);
        if (!empty($activeFilters)):
        ?>
            <div style="display:flex;flex-wrap:wrap;gap:6px;margin-bottom:14px;">
                <?php foreach ($activeFilters as $key => $val): ?>
                    <span style="background:var(--brand-light);color:var(--brand);font-size:12px;font-weight:600;padding:3px 10px;border-radius:999px;">
                        <?= ucfirst(str_replace('_', ' ', $key)) ?>: <?= htmlspecialchars($val) ?>
                    </span>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="40">#</th>
                        <th width="60">ID</th>
                        <th>Nama Jalan</th>
                        <th width="100">Tingkat</th>
                        <th width="110">Status</th>
                        <th width="100">Tanggal</th>
                        <?php if (in_array($role, ['admin', 'dinas'])): ?>
                            <th width="110">Diverifikasi</th>
                        <?php endif; ?>
                        <th width="100">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($pengajuan)): ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted" style="padding:40px;">
                                <i class="fa-solid fa-folder-open" style="font-size:32px;color:var(--border);display:block;margin-bottom:10px;"></i>
                                Tidak ada data yang sesuai filter.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $no = ($page - 1) * $perPage + 1; ?>
                        <?php foreach ($pengajuan as $row): ?>
                            <tr>
                                <td style="color:var(--text-muted);font-size:12px;"><?= $no++ ?></td>
                                <td>
                                    <a href="<?= BASE_URL ?>pengajuan/detail/<?= $row['id'] ?>"
                                        style="font-family:'Space Grotesk',sans-serif;font-weight:700;">
                                        #<?= $row['id'] ?>
                                    </a>
                                </td>
                                <td>
                                    <div style="max-width:240px;">
                                        <div style="font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                            <?= htmlspecialchars($row['nama_jalan']) ?>
                                        </div>
                                        <?php if (!empty($row['deskripsi'])): ?>
                                            <div style="font-size:11.5px;color:var(--text-muted);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin-top:2px;">
                                                <?= htmlspecialchars(substr($row['deskripsi'], 0, 60)) ?>...
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <?php $t = $row['tingkat_kerusakan'] ?? null; ?>
                                    <?php if ($t): ?>
                                        <span class="badge" style="background:<?= match ($t) {
                                                                                    'berat'  => '#fef2f2',
                                                                                    'sedang' => '#fff7ed',
                                                                                    default  => '#f0fdf4'
                                                                                } ?>;color:<?= match ($t) {
                                                                                                'berat'  => '#991b1b',
                                                                                                'sedang' => '#c2410c',
                                                                                                default  => '#166534'
                                                                                            } ?>;"><?= ucfirst($t) ?></span>
                                    <?php else: ?>
                                        <span style="color:var(--text-light);font-size:12px;">—</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge status-<?= strtolower($row['statuslaporan']) ?>">
                                        <?= ucfirst($row['statuslaporan']) ?>
                                    </span>
                                </td>
                                <td style="font-size:12.5px;color:var(--text-muted);white-space:nowrap;">
                                    <?php
                                        $tanggal = $row['created_at'] ?? null;

                                        if ($tanggal) {
                                            $tanggalFormat =
                                                substr($tanggal, 6, 2) . '/' .
                                                substr($tanggal, 4, 2) . '/' .
                                                substr($tanggal, 0, 4);

                                            echo $tanggalFormat;
                                        } else {
                                            echo '-';
                                        }
                                        ?>
                                </td>
                                <?php if (in_array($role, ['admin', 'dinas'])): ?>
                                    <td style="font-size:12px;color:var(--text-muted);">
                                        <?= $row['verified_at'] ? date('d/m/Y', strtotime($row['verified_at'])) : '<span style="color:var(--text-light);">Belum</span>' ?>
                                    </td>
                                <?php endif; ?>
                                <td>
                                    <div class="action-col">
                                        <a href="<?= BASE_URL ?>pengajuan/detail/<?= $row['id'] ?>"
                                            class="btn btn-sm btn-info" title="Detail">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <?php if ($role === 'admin'): ?>
                                            <a href="<?= BASE_URL ?>pengajuan/edit/<?= $row['id'] ?>"
                                                class="btn btn-sm btn-warning" title="Edit">
                                                <i class="fa-solid fa-pen"></i>
                                            </a>
                                            <a href="<?= BASE_URL ?>pengajuan/delete/<?= $row['id'] ?>"
                                                class="btn btn-sm btn-danger" title="Hapus"
                                                onclick="return confirm('Yakin hapus laporan #<?= $row['id'] ?>?')">
                                                <i class="fa-solid fa-trash"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?<?= http_build_query(array_merge($filter, ['page' => $page - 1])) ?>"
                        class="btn btn-sm btn-outline">
                        <i class="fa-solid fa-chevron-left"></i> Prev
                    </a>
                <?php endif; ?>

                <?php
                /* Show page numbers with ellipsis */
                $start = max(1, $page - 2);
                $end   = min($totalPages, $page + 2);
                if ($start > 1): ?>
                    <a href="?<?= http_build_query(array_merge($filter, ['page' => 1])) ?>" class="btn btn-sm btn-outline">1</a>
                    <?php if ($start > 2): ?><span style="color:var(--text-light);padding:0 4px;">…</span><?php endif; ?>
                <?php endif; ?>

                <?php for ($p = $start; $p <= $end; $p++): ?>
                    <a href="?<?= http_build_query(array_merge($filter, ['page' => $p])) ?>"
                        class="btn btn-sm <?= $p === $page ? 'btn-primary' : 'btn-outline' ?>">
                        <?= $p ?>
                    </a>
                <?php endfor; ?>

                <?php if ($end < $totalPages): ?>
                    <?php if ($end < $totalPages - 1): ?><span style="color:var(--text-light);padding:0 4px;">…</span><?php endif; ?>
                    <a href="?<?= http_build_query(array_merge($filter, ['page' => $totalPages])) ?>" class="btn btn-sm btn-outline"><?= $totalPages ?></a>
                <?php endif; ?>

                <?php if ($page < $totalPages): ?>
                    <a href="?<?= http_build_query(array_merge($filter, ['page' => $page + 1])) ?>"
                        class="btn btn-sm btn-outline">
                        Next <i class="fa-solid fa-chevron-right"></i>
                    </a>
                <?php endif; ?>

                <span class="page-info">Hal <?= $page ?> / <?= $totalPages ?></span>
            </div>
        <?php endif; ?>

    </div>
</div>

<?php require ROOT . '/app/views/partials/footer.php'; ?>