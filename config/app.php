<?php
// config/app.php

define('APP_NAME',    'MIS Jalan');
define('APP_URL',     'http://localhost/mis-jalan/public');
define('UPLOAD_DIR',  __DIR__ . '/../public/assets/uploads/');
define('UPLOAD_URL',  APP_URL . '/assets/uploads/');
define('SESSION_NAME', 'mis_jalan_session');

// Mulai session sekali
if (session_status() === PHP_SESSION_NONE) {
    session_name(SESSION_NAME);
    session_start();
}

// ---- Auth helpers ----

function isLoggedIn(): bool
{
    return isset($_SESSION['user_id']);
}

function requireLogin(): void
{
    if (!isLoggedIn()) redirect('login.php');
}

function requireAdmin(): void
{
    requireLogin();
    if (($_SESSION['user_role'] ?? '') !== 'admin') redirect('dashboard.php');
}

function currentUser(): array
{
    return [
        'id'       => $_SESSION['user_id']       ?? null,
        'nama'     => $_SESSION['user_nama']      ?? '',
        'username' => $_SESSION['user_username']  ?? '',
        'role'     => $_SESSION['user_role']      ?? 'user',
    ];
}

function isAdmin(): bool
{
    return ($_SESSION['user_role'] ?? '') === 'admin';
}

// ---- HTTP helpers ----

function redirect(string $path): void
{
    header('Location: ' . APP_URL . '/' . ltrim($path, '/'));
    exit;
}

function jsonResponse(mixed $data, int $code = 200): void
{
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

// ---- Utility ----

function generateNoPengajuan(): string
{
    $date  = date('Ymd');
    $db    = Database::getConnection();
    $stmt  = $db->prepare("SELECT COUNT(*) FROM pengajuan WHERE DATE(created_at) = CURDATE()");
    $stmt->execute();
    $count = (int)$stmt->fetchColumn() + 1;
    return sprintf('PJL-%s-%04d', $date, $count);
}

function sanitize(string $value): string
{
    return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
}

function csrf(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCsrf(): void
{
    $token = $_POST['csrf_token'] ?? '';
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        jsonResponse(['error' => 'Token tidak valid.'], 403);
    }
}

// ---- View helpers ----

function tingkatBadge(string $t): string
{
    $map = [
        'Ringan' => 'badge-ringan',
        'Sedang' => 'badge-sedang',
        'Berat'  => 'badge-berat',
    ];
    return '<span class="badge ' . ($map[$t] ?? '') . '">' . htmlspecialchars($t) . '</span>';
}

function statusBadge(string $s): string
{
    $map = [
        'Pending'  => 'badge-pending',
        'Diproses' => 'badge-diproses',
        'Selesai'  => 'badge-selesai',
        'Ditolak'  => 'badge-ditolak',
    ];
    return '<span class="badge ' . ($map[$s] ?? '') . '">' . htmlspecialchars($s) . '</span>';
}
