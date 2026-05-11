<?php
require_once ROOT . '/core/Controller.php';

class DashboardController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DASHBOARD MASYARAKAT
    |--------------------------------------------------------------------------
    */
    public function index(): void
    {
        /*
        |--------------------------------------------------------------------------
        | WAJIB LOGIN
        |--------------------------------------------------------------------------
        */
        if (!isset($_SESSION['user'])) {

            header(
                'Location: ' .
                BASE_URL .
                'auth/login'
            );

            exit;
        }

        /*
        |--------------------------------------------------------------------------
        | REDIRECT ROLE
        |--------------------------------------------------------------------------
        */
        $role =
            $_SESSION['user']['role'];

        if ($role === 'admin') {

            header(
                'Location: ' .
                BASE_URL .
                'dashboard/admin'
            );

            exit;
        }

        if ($role === 'dinas') {

            header(
                'Location: ' .
                BASE_URL .
                'dashboard/dinas'
            );

            exit;
        }

        if ($role === 'pimpinan') {

            header(
                'Location: ' .
                BASE_URL .
                'dashboard/pimpinan'
            );

            exit;
        }

        /*
        |--------------------------------------------------------------------------
        | VIEW MASYARAKAT
        |--------------------------------------------------------------------------
        */
        $data = [
            'title' => 'Dashboard Masyarakat'
        ];

        $this->view(
            'dashboard/index',
            $data
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD ADMIN
    |--------------------------------------------------------------------------
    */
    public function admin(): void
    {
        if (
            !isset($_SESSION['user']) ||
            $_SESSION['user']['role'] !== 'admin'
        ) {

            header(
                'Location: ' .
                BASE_URL .
                'auth/login'
            );

            exit;
        }

        $data = [
            'title' => 'Dashboard Admin'
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
            !isset($_SESSION['user']) ||
            $_SESSION['user']['role'] !== 'dinas'
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
            !isset($_SESSION['user']) ||
            $_SESSION['user']['role'] !== 'pimpinan'
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