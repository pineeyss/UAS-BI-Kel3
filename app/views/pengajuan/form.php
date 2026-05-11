<?php
                        /** @var string $action */
                        /** @var array  $errors */
                        /** @var array  $old */
                        /** @var array  $item */
                        $action = $action ?? 'create';
                        $errors = $errors ?? [];
                        $old    = $old    ?? [];
                        $item   = $item   ?? [];

                        require ROOT . '/app/views/partials/header.php';

                        $actionUrl = $action === 'create'
                            ? BASE_URL . 'pengajuan/create'
                            : BASE_URL . 'pengajuan/edit/' . ($item['id'] ?? '');
                        ?>

<div class="card">
    <div class="card-header">
        <h3>
            <i class="fa-solid fa-<?= $action === 'create' ? 'plus' : 'pen' ?>"></i>
            <?= htmlspecialchars($title ?? '') ?>
        </h3>
        <a href="<?= BASE_URL ?>pengajuan" class="btn btn-outline btn-sm">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
    </div>
    <div class="card-body">
        <form method="POST" action="<?= $actionUrl ?>" enctype="multipart/form-data" class="form-grid">

            <div class="form-group form-full">
                <label>Nama Jalan <span class="required">*</span></label>
                <input type="text" name="nama_jalan"
                    class="form-control <?= !empty($errors['nama_jalan']) ? 'is-invalid' : '' ?>"
                    value="<?= htmlspecialchars($old['nama_jalan'] ?? '') ?>"
                    placeholder="Contoh: Jl. Ahmad Yani KM 5 Segmen 2">
                <?php if (!empty($errors['nama_jalan'])): ?>
                    <div class="invalid-feedback"><?= $errors['nama_jalan'] ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label>Latitude <span class="required">*</span></label>
                <input type="text" name="latitude"
                    class="form-control <?= !empty($errors['latitude']) ? 'is-invalid' : '' ?>"
                    value="<?= htmlspecialchars($old['latitude'] ?? '') ?>"
                    placeholder="Contoh: -6.81428790">
                <?php if (!empty($errors['latitude'])): ?>
                    <div class="invalid-feedback"><?= $errors['latitude'] ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label>Longitude <span class="required">*</span></label>
                <input type="text" name="longitude"
                    class="form-control <?= !empty($errors['longitude']) ? 'is-invalid' : '' ?>"
                    value="<?= htmlspecialchars($old['longitude'] ?? '') ?>"
                    placeholder="Contoh: 108.44558280">
                <?php if (!empty($errors['longitude'])): ?>
                    <div class="invalid-feedback"><?= $errors['longitude'] ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group form-full">
                <label>Foto Dokumentasi</label>
                <input type="file" name="foto_path"
                    class="form-control <?= !empty($errors['foto_path']) ? 'is-invalid' : '' ?>"
                    accept="image/jpeg,image/png">
                <?php if (!empty($item['foto_path'])): ?>
                    <div class="foto-preview">
                        <img src="<?= BASE_URL ?><?= htmlspecialchars($item['foto_path']) ?>" alt="Foto saat ini">
                        <small>Foto saat ini (kosongkan jika tidak ingin menggantinya)</small>
                    </div>
                <?php endif; ?>
                <?php if (!empty($errors['foto_path'])): ?>
                    <div class="invalid-feedback"><?= $errors['foto_path'] ?></div>
                <?php endif; ?>
            </div>

            <div class="form-actions form-full">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan
                </button>
                <a href="<?= BASE_URL ?>pengajuan" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>

<?php require ROOT . '/app/views/partials/footer.php'; ?>
"""