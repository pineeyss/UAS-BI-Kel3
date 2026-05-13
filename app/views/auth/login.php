<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — RoadReport Premium</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="stylesheet" href="<?= BASE_URL ?>css/login.css">
</head>

<body class="login-body">

    <div class="login-wrapper">

        <div class="login-card">
            
            <span class="badge-top">Business Intelligence System</span>

            <div class="login-logo">
                <i class="fa-solid fa-road"></i>
            </div>

            <h1>RoadReport <i>MIS</i></h1>
            <p class="subtitle">Sistem Monitoring Pengajuan Perbaikan Jalan</p>

            <?php if (!empty($error)): ?>
                <div class="alert-error">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= BASE_URL ?>auth/login">

                <div class="form-group">
                    <label>Username</label>
                    <input 
                        type="text" 
                        name="username" 
                        placeholder="Masukkan username" 
                        required>
                </div>

                <div class="form-group">
                    <label>Password</label>
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
                            <i class="fa-solid fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-login">
                    <i class="fa-solid fa-right-to-bracket"></i> Masuk
                </button>

            </form>

            <p class="footer-text">
                Belum punya akun? <a href="<?= BASE_URL ?>auth/register">Daftar sekarang</a>
            </p>

            <a href="<?= BASE_URL ?>home" class="back-home">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard Public
            </a>

        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('passwordInput');
            const icon = document.getElementById('eyeIcon');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
    </script>

</body>

</html>