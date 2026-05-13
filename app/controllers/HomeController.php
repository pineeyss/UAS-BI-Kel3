<?php

require_once ROOT . '/core/Controller.php';
require_once ROOT . '/app/models/PengajuanModel.php';

class HomeController extends Controller
{
    private PengajuanModel $model;

    public function __construct()
    {
        $this->model = new PengajuanModel();
    }

    public function index(): void
    {
        $kpi = $this->model->getKpiStats();

        $byStatus = $this->model->getByStatus();

        $trend = $this->model->getTrendBulanan();

        $this->view('home/index', [
            'title' => 'RoadReport MIS',
            'kpi' => $kpi,
            'byStatus' => $byStatus,
            'trend' => $trend
        ]);
    }
}
