<?php
require_once ROOT . '/core/Controller.php';
require_once ROOT . '/app/models/UserModel.php';

class AuthController extends Controller
{

    private UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function login(): void
    {
        if ($this->isLoggedIn()) {
            $this->redirect('dashboard');
        }

        $error = '';

        if ($this->isPost()) {
            $username = $this->post('username');
            $password = $this->post('password');

            if (empty($username) || empty($password)) {
                $error = 'Username dan password wajib diisi.';
            } else {
                $user = $this->userModel->findByUsername($username);
                if ($user && password_verify($password, $user['password'])) {
                    $_SESSION['user_id']  = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['nama']     = $user['nama'];
                    $_SESSION['role']     = $user['role'];
                    $this->redirect('dashboard');
                } else {
                    $error = 'Username atau password salah.';
                }
            }
        }

        $this->view('auth/login', ['error' => $error]);
    }

    public function logout(): void
    {
        session_destroy();
        $this->redirect('auth/login');
    }
}
