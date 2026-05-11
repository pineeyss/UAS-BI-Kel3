<?php
abstract class Controller
{
    protected function view(string $view, array $data = []): void
    {
        extract($data);
        $viewFile = __DIR__ . '/../app/views/' . $view . '.php';
        if (!file_exists($viewFile)) {
            die("View tidak ditemukan: $view");
        }
        require $viewFile;
    }

    protected function redirect(string $url): void
    {
        header("Location: " . BASE_URL . $url);
        exit;
    }

    protected function isLoggedIn(): bool
    {
        return isset($_SESSION['user_id']);
    }

    protected function requireLogin(): void
    {
        if (!$this->isLoggedIn()) {
            $this->redirect('auth/login');
        }
    }

    protected function requireAdmin(): void
    {
        $this->requireLogin();
        if ($_SESSION['role'] !== 'admin') {
            $this->redirect('dashboard');
        }
    }

    protected function json(mixed $data, int $code = 200): void
    {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    protected function post(string $key, mixed $default = null): mixed
    {
        return isset($_POST[$key]) ? trim($_POST[$key]) : $default;
    }

    protected function get(string $key, mixed $default = null): mixed
    {
        return isset($_GET[$key]) ? trim($_GET[$key]) : $default;
    }
}
