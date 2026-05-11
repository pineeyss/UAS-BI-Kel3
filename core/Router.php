<?php
class Router
{
    public function dispatch(): void
    {
        $url = $this->parseUrl();

        $controllerName = isset($url[0]) && $url[0] !== ''
            ? ucfirst(strtolower($url[0])) . 'Controller'
            : 'DashboardController';

        $method = isset($url[1]) && $url[1] !== ''
            ? strtolower($url[1])
            : 'index';

        $params = array_slice($url, 2);

        $file = __DIR__ . '/../app/controllers/' . $controllerName . '.php';

        if (!file_exists($file)) {
            $this->notFound();
            return;
        }

        require_once $file;

        if (!class_exists($controllerName)) {
            $this->notFound();
            return;
        }

        $controller = new $controllerName();

        if (!method_exists($controller, $method)) {
            $this->notFound();
            return;
        }

        call_user_func_array([$controller, $method], $params);
    }

    private function parseUrl(): array
    {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return ['dashboard'];
    }

    private function notFound(): void
    {
        http_response_code(404);
        echo "<h1>404 - Halaman tidak ditemukan</h1>";
        exit;
    }
}
