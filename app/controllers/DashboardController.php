<?php

class DashboardController
{
    private PengajuanModel $model;

    public function __construct()
    {
        requireLogin();
        $this->model = new PengajuanModel();
    }

    public function index(): void
    {
        $stats  = $this->model->stats();
        $latest = $this->model->latest(8);

        $pageTitle    = 'Dashboard';
        $pageSubtitle = 'Ringkasan monitoring pengajuan perbaikan jalan';
        $activeMenu   = 'dashboard';

        include __DIR__ . '/../partials/header.php';
        include __DIR__ . '/../Views/dashboard/index.php';
        include __DIR__ . '/../partials/footer.php';
    }
}
