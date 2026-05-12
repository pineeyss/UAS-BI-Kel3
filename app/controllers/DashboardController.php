<?php
require_once ROOT . '/core/Controller.php';
require_once ROOT . '/app/models/PengajuanModel.php';

class DashboardController extends Controller
{
    private PengajuanModel $model;

    public function __construct()
    {
        $this->model = new PengajuanModel();
    }

    /* ── Helper: cek login & role ─────────────────────── */
    private function guardRole(string $role): void
    {
        if (!isset($_SESSION['user'])) {
            $this->redirect('auth/login');
            exit;
        }
        if ($_SESSION['user']['role'] !== $role) {
            $this->redirect('auth/login');
            exit;
        }
    }

    /* ── MASYARAKAT ───────────────────────────────────── */
    public function index(): void
    {
        if (!isset($_SESSION['user'])) {
            $this->redirect('auth/login');
            return; // pastikan tidak lanjut jika redirect tidak memanggil exit
        }

        $role = $_SESSION['user']['role'];
        if ($role === 'admin') {
            $this->redirect('dashboard/admin');
            return;
        }
        if ($role === 'dinas') {
            $this->redirect('dashboard/dinas');
            return;
        }
        if ($role === 'pimpinan') {
            $this->redirect('dashboard/pimpinan');
            return;
        }

        $userId = $_SESSION['user']['id'];
        $kpi    = $this->model->getKpiStats();
        $myData = $this->model->findByUserId($userId, 5);

        $this->view('dashboard/index', [
            'title'  => 'Dashboard Saya',
            'kpi'    => $kpi,
            'myData' => $myData,
        ]);
    }

    /* ── ADMIN ────────────────────────────────────────── */
    public function admin(): void
    {
        $this->guardRole('admin');

        $kpi      = $this->model->getKpiStats();
        $byStatus = $this->model->getByStatus();
        $terbaru  = $this->model->findRecent(8);

        $this->view('dashboard/admin', [
            'title'    => 'Dashboard Admin',
            'kpi'      => $kpi,
            'byStatus' => $byStatus,
            'terbaru'  => $terbaru,
        ]);
    }

    /* ── DINAS ────────────────────────────────────────── */
    public function dinas(): void
    {
        $this->guardRole('dinas');

        $kpi         = $this->model->getKpiStats();
        $antrian     = $this->model->findByStatus('diterima',   6);
        $onProgress  = $this->model->findByStatus('diperbaiki', 6);
        $recentDone  = $this->model->findByStatus('selesai',    6);
        $byTingkat   = $this->model->getByTingkat();

        $this->view('dashboard/dinas', [
            'title'       => 'Dashboard Dinas Teknis',
            'kpi'         => $kpi,
            'antrian'     => $antrian,
            'onProgress'  => $onProgress,
            'recentDone'  => $recentDone,
            'byTingkat'   => $byTingkat,
        ]);
    }

    /* ── PIMPINAN ─────────────────────────────────────── */
    public function pimpinan(): void
    {
        $this->guardRole('pimpinan');

        $kpi         = $this->model->getKpiStats();
        $byStatus    = $this->model->getByStatus();
        $trend       = $this->model->getTrendBulanan();
        $byTingkat   = $this->model->getByTingkat();
        $topJalan    = $this->model->getTopJalan(10);
        $responsRate = $this->model->getResponseRate();

        $this->view('dashboard/pimpinan', [
            'title'       => 'Dashboard Pimpinan',
            'kpi'         => $kpi,
            'byStatus'    => $byStatus,
            'trend'       => $trend,
            'byTingkat'   => $byTingkat,
            'topJalan'    => $topJalan,
            'responsRate' => $responsRate,
        ]);
    }
}
