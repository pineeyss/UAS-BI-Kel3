<?php
session_start();

define('ROOT', dirname(__DIR__));

require_once ROOT . '/config/database.php';
require_once ROOT . '/core/Database.php';
require_once ROOT . '/core/Model.php';
require_once ROOT . '/core/Controller.php';
require_once ROOT . '/core/Router.php';

$router = new Router();
$router->dispatch();
