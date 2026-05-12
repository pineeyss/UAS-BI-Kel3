<?php
require_once ROOT . '/core/Controller.php';
require_once ROOT . '/app/models/PengajuanModel.php';

class PengajuanController extends Controller
{
    private PengajuanModel $model;

    public function __construct()
    {
        $this->model = new PengajuanModel();
    }

    /* ── Index: daftar laporan ─────────────────────────── */
    public function index(): void
    {
        $this->requireLogin();

        $filter = [
            'status'          => $this->get('status'),
            'tingkat'         => $this->get('tingkat'),
            'search'          => $this->get('search'),
            'tanggal_dari'    => $this->get('tanggal_dari'),
            'tanggal_sampai'  => $this->get('tanggal_sampai'),
        ];

        $page    = max(1, (int)$this->get('page', 1));
        $perPage = 15;
        $total   = $this->model->countWithFilter($filter);
        $data    = $this->model->findAllWithFilter($filter, $page, $perPage);

        $this->view('pengajuan/index', [
            'title'      => 'Data Laporan Jalan',
            'pengajuan'  => $data,
            'filter'     => $filter,
            'page'       => $page,
            'perPage'    => $perPage,
            'total'      => $total,
            'totalPages' => (int)ceil($total / $perPage),
        ]);
    }

    /* ── Create: form + proses tambah laporan ─────────── */
    public function create(): void
    {
        $this->requireLogin();

        $errors = [];
        $old    = [];

        if ($this->isPost()) {
            $old = $_POST;

            $namaJalan = $this->post('nama_jalan');
            $latitude  = $this->post('latitude');
            $longitude = $this->post('longitude');
            $deskripsi = $this->post('deskripsi');

            if (empty($namaJalan)) $errors['nama_jalan'] = 'Wajib diisi.';
            if (empty($latitude))  $errors['latitude']   = 'Wajib diisi.';
            if (empty($longitude)) $errors['longitude']  = 'Wajib diisi.';

            if (!empty($latitude) && !is_numeric($latitude))  $errors['latitude']  = 'Harus berupa angka desimal.';
            if (!empty($longitude) && !is_numeric($longitude)) $errors['longitude'] = 'Harus berupa angka desimal.';

            $fotoPath = null;
            if (!empty($_FILES['foto_path']['name'])) {
                $upload = $this->uploadFoto($_FILES['foto_path']);
                if ($upload['error']) {
                    $errors['foto_path'] = $upload['error'];
                } else {
                    $fotoPath = 'uploads/' . $upload['filename'];
                }
            }

            if (empty($errors)) {
                $this->model->create([
                    'nama_jalan' => $namaJalan,
                    'latitude'   => (float)$latitude,
                    'longitude'  => (float)$longitude,
                    'deskripsi'  => $deskripsi,
                    'foto_path'  => $fotoPath,
                    'user_id'    => $_SESSION['user']['id'] ?? null,
                ]);

                $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Laporan berhasil dikirim. Menunggu verifikasi admin.'];
                $this->redirect('pengajuan');
            }
        }

        $this->view('pengajuan/form', [
            'title'  => 'Buat Laporan Baru',
            'action' => 'create',
            'errors' => $errors,
            'old'    => $old,
            'item'   => [],
        ]);
    }

    /* ── Edit: hanya admin ────────────────────────────── */
    public function edit(int $id): void
    {
        $this->requireAdmin();

        $item = $this->model->findById($id);
        if (!$item) {
            $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Data tidak ditemukan.'];
            $this->redirect('pengajuan');
        }

        $errors = [];
        $old    = $item;

        if ($this->isPost()) {
            $old = $_POST;

            $namaJalan = $this->post('nama_jalan');
            $latitude  = $this->post('latitude');
            $longitude = $this->post('longitude');

            if (empty($namaJalan)) $errors['nama_jalan'] = 'Wajib diisi.';
            if (empty($latitude))  $errors['latitude']   = 'Wajib diisi.';
            if (empty($longitude)) $errors['longitude']  = 'Wajib diisi.';

            $fotoPath = $item['foto_path'];
            if (!empty($_FILES['foto_path']['name'])) {
                $upload = $this->uploadFoto($_FILES['foto_path']);
                if ($upload['error']) {
                    $errors['foto_path'] = $upload['error'];
                } else {
                    $fotoPath = 'uploads/' . $upload['filename'];
                }
            }

            if (empty($errors)) {
                $this->model->update($id, [
                    'nama_jalan' => $namaJalan,
                    'latitude'   => (float)$latitude,
                    'longitude'  => (float)$longitude,
                    'deskripsi'  => $this->post('deskripsi'),
                    'foto_path'  => $fotoPath,
                ]);

                $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Data laporan berhasil diperbarui.'];
                $this->redirect('pengajuan/detail/' . $id);
            }
        }

        $this->view('pengajuan/form', [
            'title'  => 'Edit Laporan #' . $id,
            'action' => 'edit',
            'item'   => $item,
            'errors' => $errors,
            'old'    => $old,
        ]);
    }

    /* ── Detail ───────────────────────────────────────── */
    public function detail(int $id): void
    {
        $this->requireLogin();

        $item = $this->model->findById($id);
        if (!$item) {
            $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Data tidak ditemukan.'];
            $this->redirect('pengajuan');
        }

        $this->view('pengajuan/detail', [
            'title' => 'Detail Laporan #' . $id,
            'item'  => $item,
        ]);
    }

    /* ── Status: admin verifikasi ─────────────────────── */
    public function status(int $id): void
    {
        $this->requireAdmin();

        if ($this->isPost()) {
            $status  = $this->post('status');
            $tingkat = $this->post('tingkat_kerusakan');
            $catatan = $this->post('catatan_admin');

            $allowed = ['diterima', 'diperbaiki', 'selesai', 'ditolak'];
            if (in_array($status, $allowed)) {
                $this->model->verifikasiAdmin(
                    $id,
                    $status,
                    $tingkat ?: 'sedang',
                    $catatan ?: '',
                    $_SESSION['user']['id']
                );
                $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Status laporan berhasil diperbarui.'];
            }
        }

        $this->redirect('pengajuan/detail/' . $id);
    }

    /* ── Status Dinas: update pengerjaan ──────────────── */
    public function statusDinas(int $id): void
    {
        $this->requireLogin();
        // Fix: konsisten gunakan $_SESSION['user']['role'], bukan $_SESSION['role']
        if (($_SESSION['user']['role'] ?? '') !== 'dinas') {
            $this->redirect('dashboard');
        }

        if ($this->isPost()) {
            $status  = $this->post('status');
            $catatan = $this->post('catatan_dinas');
            $allowed = ['diperbaiki', 'selesai'];

            $fotoPerbaikan = null;
            if (!empty($_FILES['foto_perbaikan']['name'])) {
                $upload = $this->uploadFoto($_FILES['foto_perbaikan']);
                if (!$upload['error']) {
                    $fotoPerbaikan = 'uploads/' . $upload['filename'];
                }
            }

            if (in_array($status, $allowed)) {
                $this->model->updateStatusDinas(
                    $id,
                    $status,
                    $catatan,
                    $fotoPerbaikan,
                    $_SESSION['user']['id']
                );
                $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Status pengerjaan diperbarui.'];
            }
        }

        $this->redirect('pengajuan/detail/' . $id);
    }

    /* ── Delete: hanya admin ──────────────────────────── */
    public function delete(int $id): void
    {
        $this->requireAdmin();
        $this->model->delete($id);
        $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Laporan berhasil dihapus.'];
        $this->redirect('pengajuan');
    }

    /* ── Upload helper ────────────────────────────────── */
    private function uploadFoto(array $file): array
    {
        $allowedMime = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
        $maxSize     = 2 * 1024 * 1024;

        if (!in_array($file['type'], $allowedMime)) {
            return ['error' => 'Format file harus JPG, PNG, atau WEBP.', 'filename' => null];
        }
        if ($file['size'] > $maxSize) {
            return ['error' => 'Ukuran file maksimal 2MB.', 'filename' => null];
        }

        $ext      = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $filename = 'foto_' . uniqid() . '.' . $ext;
        $dest     = ROOT . '/public/uploads/' . $filename;

        if (!is_dir(ROOT . '/public/uploads/')) {
            mkdir(ROOT . '/public/uploads/', 0755, true);
        }

        if (!move_uploaded_file($file['tmp_name'], $dest)) {
            return ['error' => 'Gagal mengupload file.', 'filename' => null];
        }

        return ['error' => null, 'filename' => $filename];
    }
}
