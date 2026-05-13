<?php

$error = $error ?? '';

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Register — RoadReport</title>

    <link rel="preconnect"
        href="https://fonts.googleapis.com">

    <link rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        /* ===== CARD ===== */

        .register-card {
            width: 100%;
            max-width: 460px;
            background: white;
            border-radius: 30px;
            padding: 42px;
            border: 1px solid #e2e8f0;
            box-shadow:
                0 20px 60px rgba(15, 23, 42, .08);
        }

        /* ===== LOGO ===== */

        .logo-area {
            text-align: center;
            margin-bottom: 34px;
        }

        .logo-icon {
            width: 90px;
            height: 90px;
            border-radius: 24px;
            margin: auto;
            margin-bottom: 18px;
            background: #2563eb;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 42px;
        }

        .logo-title {
            font-size: 42px;
            font-weight: 800;
            color: #1e3a8a;
            margin-bottom: 10px;
        }

        .logo-subtitle {
            color: #64748b;
            line-height: 1.7;
        }

        /* ===== ALERT ===== */

        .alert {
            margin-bottom: 20px;
            padding: 14px 16px;
            border-radius: 14px;
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
            font-size: 14px;
        }

        /* ===== FORM ===== */

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 10px;
            font-size: 14px;
            font-weight: 600;
            color: #0f172a;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }

        .form-input {
            width: 100%;
            border: 1px solid #dbeafe;
            background: #f8fafc;
            border-radius: 16px;
            padding: 15px 16px 15px 48px;
            font-size: 15px;
            outline: none;
            transition: .25s;
        }

        .form-input:focus {
            border-color: #2563eb;
            background: white;
            box-shadow:
                0 0 0 4px rgba(37, 99, 235, .08);
        }

        /* ===== BUTTON ===== */

        .btn-register {
            width: 100%;
            border: none;
            background: #2563eb;
            color: white;
            padding: 16px;
            border-radius: 16px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: .25s;
            margin-top: 8px;
        }

        .btn-register:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
        }

        /* ===== FOOTER ===== */

        .login-link {
            margin-top: 24px;
            text-align: center;
            color: #64748b;
            font-size: 14px;
        }

        .login-link a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 700;
        }

        .login-link a:hover {
            text-decoration: underline;
        }
    </style>

</head>

<body>

    <div class="register-card">

        <div class="logo-area">

            <div class="logo-icon">

                <i class="fa-solid fa-road"></i>

            </div>

            <div class="logo-title">

                RoadReport MIS

            </div>

            <div class="logo-subtitle">

                Buat akun untuk mengajukan
                laporan jalan rusak dan
                memantau status pengajuan.

            </div>

        </div>

        <?php if ($error): ?>

            <div class="alert">

                <?= htmlspecialchars($error) ?>

            </div>

        <?php endif; ?>

        <form method="POST"
            action="<?= BASE_URL ?>auth/register">

            <div class="form-group">

                <label class="form-label">

                    Nama Lengkap

                </label>

                <div class="input-wrapper">

                    <i class="fa-solid fa-user"></i>

                    <input type="text"
                        name="nama"
                        class="form-input"
                        placeholder="Masukkan nama lengkap"
                        required>

                </div>

            </div>

            <div class="form-group">

                <label class="form-label">

                    Username

                </label>

                <div class="input-wrapper">

                    <i class="fa-solid fa-at"></i>

                    <input type="text"
                        name="username"
                        class="form-input"
                        placeholder="Masukkan username"
                        required>

                </div>

            </div>

            <div class="form-group">

                <label class="form-label">

                    Password

                </label>

                <div class="input-wrapper">

                    <i class="fa-solid fa-lock"></i>

                    <input type="password"
                        name="password"
                        class="form-input"
                        placeholder="Masukkan password"
                        required>

                </div>

            </div>

            <button type="submit"
                class="btn-register">

                <i class="fa-solid fa-user-plus"></i>

                Daftar Sekarang

            </button>

        </form>

        <div class="login-link">

            Sudah punya akun?

            <a href="<?= BASE_URL ?>auth/login">

                Login disini

            </a>

        </div>

    </div>

</body>

</html>