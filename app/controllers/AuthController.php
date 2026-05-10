<?php
// app/Controllers/AuthController.php

class AuthController
{
    private UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /** GET /login.php */
    public function showLogin(): void
    {
        if (isLoggedIn()) redirect('dashboard.php');
        $error = null;
        include __DIR__ . '/../Views/auth/login.php';
    }

    /** POST /login.php */
    public function handleLogin(): void
    {
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $error    = null;

        if ($username && $password) {
            $user = $this->userModel->findByUsername($username);
            if ($user && $this->userModel->verifyPassword($password, $user['password'])) {
                session_regenerate_id(true);
                $_SESSION['user_id']       = $user['id'];
                $_SESSION['user_nama']     = $user['nama'];
                $_SESSION['user_username'] = $user['username'];
                $_SESSION['user_role']     = $user['role'];
                redirect('dashboard.php');
            } else {
                $error = 'Username atau password salah.';
            }
        } else {
            $error = 'Harap isi semua kolom.';
        }

        include __DIR__ . '/../Views/auth/login.php';
    }

    /** GET /logout.php */
    public function logout(): void
    {
        session_destroy();
        redirect('login.php');
    }
}
