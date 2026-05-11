<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        Login — SIJALAN
    </title>

    <!-- ICON -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- CSS -->
    <link rel="stylesheet"
        href="<?= BASE_URL ?>css/login.css">

</head>

<body class="login-body">

    <div class="login-wrapper">

        <!-- CARD -->
        <div class="login-card">

            <!-- LOGO -->
            <div class="login-logo">

                <i class="fa-solid fa-road"></i>

            </div>

            <!-- TITLE -->
            <h1>
                SIJALAN
            </h1>

            <p class="subtitle">

                Sistem Monitoring
                Pengajuan Perbaikan Jalan

            </p>

            <!-- ERROR -->
            <?php if (!empty($error)): ?>

                <div class="alert-error">

                    <i class="fa-solid fa-circle-exclamation"></i>

                    <?= htmlspecialchars($error) ?>

                </div>

            <?php endif; ?>

            <!-- FORM -->
            <form method="POST"
                action="<?= BASE_URL ?>auth/login">

                <!-- USERNAME -->
                <div class="form-group">

                    <label>

                        Username

                    </label>

                    <input
                        type="text"
                        name="username"
                        placeholder="Masukkan username"
                        required>

                </div>

                <!-- PASSWORD -->
                <div class="form-group">

                    <label>

                        Password

                    </label>

                    <div class="password-box">

                        <input
                            type="password"
                            name="password"
                            id="passwordInput"
                            placeholder="Masukkan password"
                            required>

                        <button
                            type="button"
                            class="eye-btn"
                            onclick="togglePassword()">

                            <i class="fa-solid fa-eye"
                                id="eyeIcon"></i>

                        </button>

                    </div>

                </div>

                <!-- LOGIN BUTTON -->
                <button
                    type="submit"
                    class="btn-login">

                    <i class="fa-solid fa-right-to-bracket"></i>

                    Masuk

                </button>

            </form>

            <!-- DIVIDER -->
            <div class="divider">

                <span>
                    atau
                </span>

            </div>

            <!-- REGISTER -->
            <a href="<?= BASE_URL ?>auth/register"
                class="btn-secondary">

                <i class="fa-solid fa-user-plus"></i>

                Buat Akun Baru

            </a>

            <!-- HOME -->
            <a href="<?= BASE_URL ?>home"
                class="btn-outline">

                <i class="fa-solid fa-arrow-left"></i>

                Dashboard Public

            </a>

            <!-- DEMO -->
            <div class="login-demo">

                <div class="demo-title">

                    Akun Demo

                </div>

                <div class="demo-grid">

                    <div class="demo-item">

                        <strong>Admin</strong>

                        <span>
                            admin / admin123
                        </span>

                    </div>

                    <div class="demo-item">

                        <strong>Dinas</strong>

                        <span>
                            dinas / admin123
                        </span>

                    </div>

                    <div class="demo-item">

                        <strong>Pimpinan</strong>

                        <span>
                            pimpinan / admin123
                        </span>

                    </div>

                    <div class="demo-item">

                        <strong>Masyarakat</strong>

                        <span>
                            user / admin123
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- SCRIPT -->
    <script>

        function togglePassword() {

            const input =
                document.getElementById(
                    'passwordInput'
                );

            const icon =
                document.getElementById(
                    'eyeIcon'
                );

            if (
                input.type === 'password'
            ) {

                input.type = 'text';

                icon.classList.replace(
                    'fa-eye',
                    'fa-eye-slash'
                );

            } else {

                input.type = 'password';

                icon.classList.replace(
                    'fa-eye-slash',
                    'fa-eye'
                );
            }
        }

    </script>

</body>

</html>