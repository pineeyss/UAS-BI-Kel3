<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — SIJALAN</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/style.css">
</head>

<body class="login-body">
    <div class="login-container">
        <div class="login-card">
            <div class="login-brand">
                <i class="fa-solid fa-road fa-3x"></i>
                <h1>SIJALAN</h1>
                <p>Sistem Monitoring Pengajuan Perbaikan Jalan</p>
                <span class="mis-badge">Management Information System (MIS)</span>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= BASE_URL ?>auth/login">
                <div class="form-group">
                    <label><i class="fa-solid fa-user"></i> Username</label>
                    <input type="text" name="username" class="form-control"
                        value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                        placeholder="Masukkan username" required autofocus>
                </div>
                <div class="form-group">
                    <label><i class="fa-solid fa-lock"></i> Password</label>
                    <div class="input-group">
                        <input type="password" name="password" id="passInput"
                            class="form-control" placeholder="Masukkan password" required>
                        <button type="button" class="btn-eye" onclick="togglePass()">
                            <i class="fa-solid fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fa-solid fa-right-to-bracket"></i> Masuk
                </button>
            </form>

            <div class="login-hint">
                <small>Demo: <strong>admin</strong> / <strong>admin123</strong></small>
            </div>
        </div>
    </div>
    <script>
        function togglePass() {
            const inp = document.getElementById('passInput');
            const ico = document.getElementById('eyeIcon');
            if (inp.type === 'password') {
                inp.type = 'text';
                ico.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                inp.type = 'password';
                ico.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
    </script>
</body>

</html>