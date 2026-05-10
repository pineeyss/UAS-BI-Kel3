<?php

class UsersController
{
    private UserModel      $userModel;
    private PengajuanModel $pengModel;

    public function __construct()
    {
        requireAdmin();
        $this->userModel = new UserModel();
        $this->pengModel = new PengajuanModel();
    }

    public function index(): void
    {
        $users = $this->userModel->all();
        $stats = $this->pengModel->stats();

        // Hitung pengajuan per user
        $allPeng      = $this->pengModel->all();
        $countPerUser = [];
        foreach ($allPeng as $p) {
            $uid = $p['user_id'] ?? 0;
            $countPerUser[$uid] = ($countPerUser[$uid] ?? 0) + 1;
        }

        $pageTitle    = 'Kelola Users';
        $pageSubtitle = 'Manajemen akun pengguna sistem';
        $activeMenu   = 'users';

        include __DIR__ . '/../partials/header.php';
        include __DIR__ . '/../Views/users/index.php';
        include __DIR__ . '/../partials/footer.php';
    }
}
