pengajuan_index = r"""<?php
                        /** @var array  $pengajuan */
                        /** @var array  $filter */
                        /** @var array  $kecList */
                        /** @var int    $page */
                        /** @var int    $perPage */
                        /** @var int    $total */
                        /** @var int    $totalPages */
                        $pengajuan  = $pengajuan  ?? [];
                        $filter     = $filter     ?? [];
                        $kecList    = $kecList    ?? [];
                        $page       = $page       ?? 1;
                        $perPage    = $perPage    ?? 15;
                        $total      = $total      ?? 0;
                        $totalPages = $totalPages ?? 1;

                        require ROOT . '/app/views/partials/header.php';
                        ?>

<div class="card">
    <div class="card-header">
        <h3><i class="fa-solid fa-clipboard-list"></i> Daftar Laporan Jalan</h3>
        <a href="<?= BASE_URL ?>pengajuan/create" class="btn btn-primary btn-sm">
            <i class="fa-solid fa-plus"></i> Tambah
        </a>
    </div>
    <div class="card-body">
        <form method="GET" action="<?= BASE_URL ?>pengajuan" class="filter-bar">
            <input type="text" name="search" class="form-control"
                placeholder="Cari nama jalan..."
                value="<?= htmlspecialchars($filter['search'] ?? '') ?>">
            <select name="status" class="form-control">
                <option value="">Semua Status</option>
                <?php foreach (['diterima', 'diperbaiki', 'selesai', 'ditolak'] as $s): ?>
                    <option value="<?= $s ?>" <?= ($filter['status'] ?? '') === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                <?php endforeach; ?>
            </select>
            <input type="date" name="tanggal_dari" class="form-control" value="<?= htmlspecialchars($filter['tanggal_dari'] ?? '') ?>">
            <input type="date" name="tanggal_sampai" class="form-control" value="<?= htmlspecialchars($filter['tanggal_sampai'] ?? '') ?>">
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i> Cari</button>
            <a href="<?= BASE_URL ?>pengajuan" class="btn btn-outline"><i class="fa-solid fa-rotate-left"></i> Reset</a>
        </form>

        <div class="table-info">Menampilkan <?= count($pengajuan) ?> dari <?= number_format($total) ?> data</div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>ID</th>
                        <th>Nama Jalan</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($pengajuan)): ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted">Tidak ada data.</td>
                        </tr>
                    <?php else: ?>
                        <?php $no = ($page - 1) * $perPage + 1;
                        foreach ($pengajuan as $row): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><a href="<?= BASE_URL ?>pengajuan/detail/<?= $row['id'] ?>">#<?= $row['id'] ?></a></td>
                                <td><?= htmlspecialchars($row['nama_jalan']) ?></td>
                                <td><?= $row['latitude'] ?></td>
                                <td><?= $row['longitude'] ?></td>
                                <td><span class="badge status-<?= strtolower($row['statuslaporan']) ?>"><?= ucfirst($row['statuslaporan']) ?></span></td>
                                <td><?= date('d/m/Y', strtotime($row['created_at'])) ?></td>
                                <td class="action-col">
                                    <a href="<?= BASE_URL ?>pengajuan/detail/<?= $row['id'] ?>" class="btn btn-sm btn-info" title="Detail"><i class="fa-solid fa-eye"></i></a>
                                    <?php if ($_SESSION['role'] === 'admin'): ?>
                                        <a href="<?= BASE_URL ?>pengajuan/edit/<?= $row['id'] ?>" class="btn btn-sm btn-warning" title="Edit"><i class="fa-solid fa-pen"></i></a>
                                        <a href="<?= BASE_URL ?>pengajuan/delete/<?= $row['id'] ?>" class="btn btn-sm btn-danger" title="Hapus" onclick="return confirm('Yakin hapus data ini?')"><i class="fa-solid fa-trash"></i></a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?<?= http_build_query(array_merge($filter, ['page' => $page - 1])) ?>" class="btn btn-sm btn-outline"><i class="fa-solid fa-chevron-left"></i> Prev</a>
                <?php endif; ?>
                <span class="page-info">Hal <?= $page ?> / <?= $totalPages ?></span>
                <?php if ($page < $totalPages): ?>
                    <a href="?<?= http_build_query(array_merge($filter, ['page' => $page + 1])) ?>" class="btn btn-sm btn-outline">Next <i class="fa-solid fa-chevron-right"></i></a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require ROOT . '/app/views/partials/footer.php'; ?>
"""