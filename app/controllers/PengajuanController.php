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

    public function index(): void
    {
        $this->requireLogin();

        $filter = [
            'status'    => $this->get('status'),
            'kecamatan' => $this->get('kecamatan'),
            'search'    => $this->get('search'),
            'tanggal_dari'    => $this->get('tanggal_dari'),
            'tanggal_sampai'  => $this->get('tanggal_sampai'),
        ];

        $page    = max(1, (int)$this->get('page', 1));
        $perPage = 15;
        $total   = $this->model->countWithFilter($filter);
        $data    = $this->model->findAllWithFilter($filter, $page, $perPage);
        $kecList = $this->model->getKecamatanList();

        $this->view('pengajuan/index', [
            'title'      => 'Data Pengajuan',
            'pengajuan'  => $data,
            'filter'     => $filter,
            'kecList'    => $kecList,
            'page'       => $page,
            'perPage'    => $perPage,
            'total'      => $total,
            'totalPages' => ceil($total / $perPage),
        ]);
    }

    public function create(): void
    {
        $this->requireLogin();

        $errors = [];
        $old    = [];

        if ($this->isPost()) {
            $old = $_POST;

            $required = ['nama_pelapor', 'no_hp', 'lokasi', 'kecamatan', 'kelurahan', 'jenis_kerusakan', 'tingkat_kerusakan'];
            foreach ($required as $field) {
                if (empty($this->post($field))) {
                    $errors[$field] = 'Wajib diisi.';
                }
            }

            $foto = null;
            if (!empty($_FILES['foto']['name'])) {
                $uploadResult = $this->uploadFoto($_FILES['foto']);
                if ($uploadResult['error']) {
                    $errors['foto'] = $uploadResult['error'];
                } else {
                    $foto = $uploadResult['filename'];
                }
            }

            if (empty($errors)) {
                $this->model->create([
                    'nama_pelapor'     => $this->post('nama_pelapor'),
                    'no_hp'            => $this->post('no_hp'),
                    'lokasi'           => $this->post('lokasi'),
                    'kecamatan'        => $this->post('kecamatan'),
                    'kelurahan'        => $this->post('kelurahan'),
                    'jenis_kerusakan'  => $this->post('jenis_kerusakan'),
                    'tingkat_kerusakan' => $this->post('tingkat_kerusakan'),
                    'num_potholes'     => (int)$this->post('num_potholes', 0),
                    'deskripsi'        => $this->post('deskripsi'),
                    'foto'             => $foto,
                    'user_id'          => $_SESSION['user_id'],
                ]);

                $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Pengajuan berhasil ditambahkan.'];
                $this->redirect('pengajuan');
            }
        }

        $this->view('pengajuan/form', [
            'title'  => 'Tambah Pengajuan',
            'action' => 'create',
            'errors' => $errors,
            'old'    => $old,
        ]);
    }

    public function edit(int $id): void
    {
        $this->requireAdmin();

        $item   = $this->model->findById($id);
        $errors = [];
        $old    = $item ?: [];

        if (!$item) {
            $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Data tidak ditemukan.'];
            $this->redirect('pengajuan');
        }

        if ($this->isPost()) {
            $old = $_POST;

            $required = ['nama_pelapor', 'no_hp', 'lokasi', 'kecamatan', 'kelurahan', 'jenis_kerusakan', 'tingkat_kerusakan'];
            foreach ($required as $field) {
                if (empty($this->post($field))) {
                    $errors[$field] = 'Wajib diisi.';
                }
            }

            $foto = $item['foto'];
            if (!empty($_FILES['foto']['name'])) {
                $uploadResult = $this->uploadFoto($_FILES['foto']);
                if ($uploadResult['error']) {
                    $errors['foto'] = $uploadResult['error'];
                } else {
                    $foto = $uploadResult['filename'];
                }
            }

            if (empty($errors)) {
                $this->model->update($id, [
                    'nama_pelapor'      => $this->post('nama_pelapor'),
                    'no_hp'             => $this->post('no_hp'),
                    'lokasi'            => $this->post('lokasi'),
                    'kecamatan'         => $this->post('kecamatan'),
                    'kelurahan'         => $this->post('kelurahan'),
                    'jenis_kerusakan'   => $this->post('jenis_kerusakan'),
                    'tingkat_kerusakan' => $this->post('tingkat_kerusakan'),
                    'num_potholes'      => (int)$this->post('num_potholes', 0),
                    'deskripsi'         => $this->post('deskripsi'),
                    'foto'              => $foto,
                ]);

                $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Data berhasil diperbarui.'];
                $this->redirect('pengajuan');
            }
        }

        $this->view('pengajuan/form', [
            'title'  => 'Edit Pengajuan',
            'action' => 'edit',
            'item'   => $item,
            'errors' => $errors,
            'old'    => $old,
        ]);
    }

    public function detail(int $id): void
    {
        $this->requireLogin();

        $item = $this->model->findById($id);
        if (!$item) {
            $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Data tidak ditemukan.'];
            $this->redirect('pengajuan');
        }

        $this->view('pengajuan/detail', [
            'title' => 'Detail Pengajuan',
            'item'  => $item,
        ]);
    }

    public function status(int $id): void
    {
        $this->requireAdmin();

        if ($this->isPost()) {
            $status  = $this->post('status');
            $catatan = $this->post('catatan_admin');
            $allowed = ['Pending', 'Diproses', 'Selesai', 'Ditolak'];
            if (in_array($status, $allowed)) {
                $this->model->updateStatus($id, $status, $catatan);
                $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Status berhasil diperbarui.'];
            }
        }

        $this->redirect('pengajuan/detail/' . $id);
    }

    public function delete(int $id): void
    {
        $this->requireAdmin();
        $this->model->delete($id);
        $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Data berhasil dihapus.'];
        $this->redirect('pengajuan');
    }

    private function uploadFoto(array $file): array
    {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        $maxSize      = 2 * 1024 * 1024; // 2MB

        if (!in_array($file['type'], $allowedTypes)) {
            return ['error' => 'Format file harus JPG atau PNG.', 'filename' => null];
        }
        if ($file['size'] > $maxSize) {
            return ['error' => 'Ukuran file maksimal 2MB.', 'filename' => null];
        }

        $ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'foto_' . uniqid() . '.' . $ext;
        $dest     = ROOT . '/public/uploads/' . $filename;

        if (!move_uploaded_file($file['tmp_name'], $dest)) {
            return ['error' => 'Gagal mengupload file.', 'filename' => null];
        }

        return ['error' => null, 'filename' => $filename];
    }
}
