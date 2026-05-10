<?php

class PengajuanController
{
    private PengajuanModel $model;

    public function __construct()
    {
        requireLogin();
        $this->model = new PengajuanModel();
    }

    /** GET /pengajuan.php */
    public function index(): void
    {
        $filter = [
            'status'  => $_GET['status']  ?? '',
            'tingkat' => $_GET['tingkat'] ?? '',
            'search'  => $_GET['search']  ?? '',
        ];
        if (!isAdmin()) {
            $filter['user_id'] = currentUser()['id'];
        }
        $data = $this->model->all($filter);

        $pageTitle  = 'Daftar Pengajuan';
        $activeMenu = 'pengajuan';

        include __DIR__ . '/../partials/header.php';
        include __DIR__ . '/../Views/pengajuan/index.php';
        include __DIR__ . '/../partials/footer.php';
    }

    /** GET /tambah.php */
    public function create(): void
    {
        $pageTitle    = 'Tambah Pengajuan';
        $pageSubtitle = 'Isi formulir dengan lengkap dan benar';
        $activeMenu   = 'tambah';

        include __DIR__ . '/../partials/header.php';
        include __DIR__ . '/../Views/pengajuan/create.php';
        include __DIR__ . '/../partials/footer.php';
    }

    /** POST /tambah.php */
    public function store(): void
    {
        verifyCsrf();

        $required = ['nama_pelapor', 'lokasi', 'kecamatan', 'kelurahan', 'jenis_kerusakan', 'tingkat_kerusakan'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                jsonResponse(['error' => "Field $field wajib diisi."], 422);
            }
        }

        // Upload foto
        $fotoPath = null;
        if (!empty($_FILES['foto']['name'])) {
            $ext   = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
            $allow = ['jpg', 'jpeg', 'png', 'webp'];
            if (!in_array($ext, $allow)) {
                jsonResponse(['error' => 'Format foto tidak didukung. Gunakan JPG, PNG, atau WebP.'], 422);
            }
            if ($_FILES['foto']['size'] > 5_000_000) {
                jsonResponse(['error' => 'Ukuran foto maksimal 5MB.'], 422);
            }
            if (!is_dir(UPLOAD_DIR)) mkdir(UPLOAD_DIR, 0755, true);
            $filename = uniqid('foto_', true) . '.' . $ext;
            if (!move_uploaded_file($_FILES['foto']['tmp_name'], UPLOAD_DIR . $filename)) {
                jsonResponse(['error' => 'Gagal menyimpan foto.'], 500);
            }
            $fotoPath = $filename;
        }

        $user = currentUser();
        $id   = $this->model->create([
            'no_pengajuan'      => generateNoPengajuan(),
            'nama_pelapor'      => sanitize($_POST['nama_pelapor']),
            'no_hp'             => sanitize($_POST['no_hp'] ?? ''),
            'lokasi'            => sanitize($_POST['lokasi']),
            'kecamatan'         => sanitize($_POST['kecamatan']),
            'kelurahan'         => sanitize($_POST['kelurahan']),
            'jenis_kerusakan'   => $_POST['jenis_kerusakan'],
            'tingkat_kerusakan' => $_POST['tingkat_kerusakan'],
            'num_potholes'      => max(0, (int)($_POST['num_potholes'] ?? 0)),
            'deskripsi'         => sanitize($_POST['deskripsi'] ?? ''),
            'foto'              => $fotoPath,
            'user_id'           => $user['id'],
        ]);

        $item = $this->model->findById($id);
        jsonResponse(['success' => true, 'no_pengajuan' => $item['no_pengajuan'], 'id' => $id]);
    }

    /** GET /api/pengajuan.php?id=X */
    public function show(int $id): void
    {
        $item = $this->model->findById($id);
        if (!$item) jsonResponse(['error' => 'Data tidak ditemukan.'], 404);
        if (!isAdmin() && $item['user_id'] != currentUser()['id']) {
            jsonResponse(['error' => 'Akses ditolak.'], 403);
        }
        jsonResponse($item);
    }

    /** POST /api/status.php */
    public function updateStatus(): void
    {
        requireAdmin();
        verifyCsrf();

        $id      = (int)($_POST['id'] ?? 0);
        $status  = $_POST['status'] ?? '';
        $catatan = sanitize($_POST['catatan'] ?? '');
        $valid   = ['Pending', 'Diproses', 'Selesai', 'Ditolak'];

        if (!$id || !in_array($status, $valid)) {
            jsonResponse(['error' => 'Data tidak valid.'], 422);
        }
        $this->model->updateStatus($id, $status, $catatan);
        jsonResponse(['success' => true]);
    }

    /** POST /api/delete.php */
    public function delete(): void
    {
        requireAdmin();
        verifyCsrf();

        $id = (int)($_POST['id'] ?? 0);
        if (!$id) jsonResponse(['error' => 'ID tidak valid.'], 422);
        $this->model->delete($id);
        jsonResponse(['success' => true]);
    }
}
