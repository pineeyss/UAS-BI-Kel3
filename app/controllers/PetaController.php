<?php
require_once ROOT . '/core/Controller.php';
require_once ROOT . '/app/models/PengajuanModel.php';

class PetaController extends Controller
{

    private PengajuanModel $model;

    public function __construct()
    {
        $this->model = new PengajuanModel();
    }

    public function index(): void
    {
        $this->requireLogin();
        $this->view('peta/index', ['title' => 'Peta Sebaran Kerusakan']);
    }

    // API endpoint — return JSON for Leaflet
    public function data(): void
    {
        $this->requireLogin();
        $rows = $this->model->getForMap();
        $this->json($rows);
    }
}
