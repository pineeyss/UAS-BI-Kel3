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

<<<<<<< HEAD
    /*
    |--------------------------------------------------------------------------
    | DASHBOARD MASYARAKAT
    |--------------------------------------------------------------------------
    */
    public function index(): void
    {
        $data = [
            'title' => 'Dashboard Masyarakat'
        ];

        $this->view('dashboard/index', $data);
=======
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
>>>>>>> 89759f71efaab53d24ced6b3403987a7c73d8fb2
    }

    /* ── ADMIN ────────────────────────────────────────── */
    public function admin(): void
    {
<<<<<<< HEAD
        if (
            !isset($_SESSION['role']) ||
            $_SESSION['role'] !== 'admin'
        ) {
=======
        $this->guardRole('admin');
>>>>>>> 89759f71efaab53d24ced6b3403987a7c73d8fb2

        $kpi      = $this->model->getKpiStats();
        $byStatus = $this->model->getByStatus();
        $terbaru  = $this->model->findRecent(8);

<<<<<<< HEAD
            exit;
        }

        /*
        |--------------------------------------------------------------------------
        | DATA DASHBOARD
        |--------------------------------------------------------------------------
        */

        $kpi        = $this->model->getKpiStats();
        $byStatus   = $this->model->getByStatus();
        $terbaru    = $this->model->findAllWithFilter([], 1, 5);

        $data = [

            'title'     => 'Dashboard Admin',

            'kpi'       => $kpi,

            'byStatus'  => $byStatus,

            'terbaru'   => $terbaru
        ];

        $this->view(
            'dashboard/admin',
            $data
        );
=======
        $this->view('dashboard/admin', [
            'title'    => 'Dashboard Admin',
            'kpi'      => $kpi,
            'byStatus' => $byStatus,
            'terbaru'  => $terbaru,
        ]);
>>>>>>> 89759f71efaab53d24ced6b3403987a7c73d8fb2
    }

    /* ── DINAS ────────────────────────────────────────── */
    public function dinas(): void
    {
<<<<<<< HEAD
        if (
            !isset($_SESSION['role']) ||
            $_SESSION['role'] !== 'dinas'
        ) {
=======
        $this->guardRole('dinas');
>>>>>>> 89759f71efaab53d24ced6b3403987a7c73d8fb2

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
<<<<<<< HEAD
        if (
            !isset($_SESSION['role']) ||
            $_SESSION['role'] !== 'pimpinan'
        ) {
=======
        $this->guardRole('pimpinan');
>>>>>>> 89759f71efaab53d24ced6b3403987a7c73d8fb2

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
