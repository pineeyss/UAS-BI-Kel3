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
    | DASHBOARD MASYARAKAT
    |--------------------------------------------------------------------------
    */
    public function index(): void
    {
        $data = [
            'title' => 'Dashboard Masyarakat'
        ];

        $this->view('dashboard/index', $data);
    }

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD ADMIN
    |--------------------------------------------------------------------------
    */
    public function admin(): void
    {
        if (
            !isset($_SESSION['role']) ||
            $_SESSION['role'] !== 'admin'
        ) {

            header(
                'Location: ' .
                BASE_URL .
                'auth/login'
            );

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
    }

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD DINAS
    |--------------------------------------------------------------------------
    */
    public function dinas(): void
    {
        if (
            !isset($_SESSION['role']) ||
            $_SESSION['role'] !== 'dinas'
        ) {

            header(
                'Location: ' .
                BASE_URL .
                'auth/login'
            );

            exit;
        }

        $data = [
            'title' => 'Dashboard Dinas'
        ];

        $this->view(
            'dashboard/dinas',
            $data
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD PIMPINAN
    |--------------------------------------------------------------------------
    */
    public function pimpinan(): void
    {
        if (
            !isset($_SESSION['role']) ||
            $_SESSION['role'] !== 'pimpinan'
        ) {

            header(
                'Location: ' .
                BASE_URL .
                'auth/login'
            );

            exit;
        }

        $data = [
            'title' => 'Dashboard Pimpinan'
        ];

        $this->view(
            'dashboard/pimpinan',
            $data
        );
    }
}