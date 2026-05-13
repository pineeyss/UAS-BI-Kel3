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

    /*
    |--------------------------------------------------------------------------
    | HELPER ROLE
    |--------------------------------------------------------------------------
    */
    private function guardRole(string $role): void
    {
        if (
            !isset($_SESSION['user']) ||
            $_SESSION['user']['role'] !== $role
        ) {

            $this->redirect('auth/login');
            exit;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD MASYARAKAT
    |--------------------------------------------------------------------------
    */
    public function index(): void
    {
        if (!isset($_SESSION['user'])) {

            $this->redirect('auth/login');
            return;
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

        $kpi = $this->model->getKpiStats();

        $this->view('dashboard/index', [

            'title' => 'Dashboard Masyarakat',

            'kpi' => $kpi
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD ADMIN
    |--------------------------------------------------------------------------
    */
    public function admin(): void
    {
        $this->guardRole('admin');

        $kpi        = $this->model->getKpiStats();
        $byStatus   = $this->model->getByStatus();
        $terbaru    = $this->model->findAllWithFilter([], 1, 5);

        $this->view('dashboard/admin', [

            'title'     => 'Dashboard Admin',

            'kpi'       => $kpi,

            'byStatus'  => $byStatus,

            'terbaru'   => $terbaru
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD DINAS
    |--------------------------------------------------------------------------
    */
    public function dinas(): void
    {
        $this->guardRole('dinas');

        $kpi = $this->model->getKpiStats();

        $antrian = $this->model->findByStatus(
            'diterima',
            5
        );

        $onProgress = $this->model->findByStatus(
            'diperbaiki',
            5
        );

        $recentDone = $this->model->findByStatus(
            'selesai',
            5
        );

        $byTingkat = $this->model->getByTingkat();

        $this->view('dashboard/dinas', [

            'title' => 'Dashboard Dinas',

            'kpi' => $kpi,

            'antrian' => $antrian,

            'onProgress' => $onProgress,

            'recentDone' => $recentDone,

            'byTingkat' => $byTingkat
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD PIMPINAN
    |--------------------------------------------------------------------------
    */
    public function pimpinan(): void
    {
        $this->guardRole('pimpinan');

        $kpi = $this->model->getKpiStats();

        $byStatus = $this->model->getByStatus();

        $byTingkat = $this->model->getByTingkat();

        $trend = $this->model->getTrendBulanan();

        $topJalan = $this->model->getTopJalan();

        $responsRate = $this->model->getResponseRate();

        $this->view('dashboard/pimpinan', [

            'title' => 'Dashboard Pimpinan',

            'kpi' => $kpi,

            'byStatus' => $byStatus,

            'trend' => $trend,

            'byTingkat' => $byTingkat,

            'topJalan' => $topJalan,

            'responsRate' => $responsRate
        ]);
    }
}