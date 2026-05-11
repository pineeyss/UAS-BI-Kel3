<?php
require_once ROOT . '/core/Controller.php';
require_once ROOT . '/app/models/PengajuanModel.php';

class AnalitikController extends Controller
{

    private PengajuanModel $model;

    public function __construct()
    {
        $this->model = new PengajuanModel();
    }

    public function index(): void
    {
        $this->requireLogin();

        $trend     = $this->model->getTrendBulanan();
        $byJenis   = $this->model->getByJenisKerusakan();
        $byKec     = $this->model->getByKecamatan();
        $byStatus  = $this->model->getByStatus();

        $this->view('analitik/index', [
            'title'    => 'Analitik & Laporan',
            'trend'    => $trend,
            'byJenis'  => $byJenis,
            'byKec'    => $byKec,
            'byStatus' => $byStatus,
        ]);
    }
}
