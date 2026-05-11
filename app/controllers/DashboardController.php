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

    public function index(): void
    {
        $this->requireLogin();

        $kpi        = $this->model->getKpiStats();
        $byStatus   = $this->model->getByStatus();
        $terbaru    = $this->model->findAllWithFilter([], 1, 5);

        $this->view('dashboard/index', [
            'title'    => 'Dashboard',
            'kpi'      => $kpi,
            'byStatus' => $byStatus,
            'terbaru'  => $terbaru,
        ]);
    }
}
