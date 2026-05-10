<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Masuk — MIS Jalan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" />
    <link rel="stylesheet" href="<?= APP_URL ?>/css/style.css" />
</head>

<body class="login-body">

    <div class="login-wrap">

        <!-- Left decorative panel -->
        <div class="login-left">
            <div class="login-deco">
                <div class="deco-grid">
                    <?php for ($i = 0; $i < 36; $i++): ?>
                        <div class="deco-cell"></div>
                    <?php endfor; ?>
                </div>
                <p class="deco-tagline"><em>Sistem Informasi</em><br>Manajemen Jalan</p>
                <p class="deco-sub">Pemantauan pengajuan perbaikan jalan secara terpusat, transparan, dan efisien.</p>
            </div>
        </div>

        <!-- Right form panel -->
        <div class="login-right">
            <form class="login-form" method="POST" action="">

                <!-- Logo -->
                <div class="login-logo">
                    <svg viewBox="0 0 38 38" fill="none">
                        <rect width="38" height="38" rx="10" fill="#2563eb" />
                        <path d="M8 19h22M8 13h22M8 25h22" stroke="white" stroke-width="2" stroke-linecap="round" />
                        <path d="M12 10v3M19 10v3M26 10v3M12 25v3M19 25v3M26 25v3" stroke="white" stroke-width="1.5" stroke-linecap="round" />
                    </svg>
                    <span>MIS Jalan</span>
                </div>

                <div>
                    <h2 class="login-title">Selamat datang<br><em>kembali.</em></h2>
                    <p class="login-hint">Masuk untuk mengakses dashboard monitoring.</p>
                </div>

                <!-- Error alert -->
                <?php if (!empty($error)): ?>
                    <div class="alert alert-error">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <circle cx="12" cy="12" r="9" />
                            <path d="M12 8v4M12 16h.01" stroke-linecap="round" />
                        </svg>
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <!-- Username -->
                <div class="form-group">
                    <label for="username">Username</label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        placeholder="Masukkan username"
                        autocomplete="username"
                        required
                        value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" />
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="pwInput">Password</label>
                    <div class="input-pw">
                        <input
                            type="password"
                            id="pwInput"
                            name="password"
                            placeholder="••••••••"
                            autocomplete="current-password"
                            required />
                        <button type="button" class="pw-toggle" onclick="togglePw()" title="Tampilkan password">
                            <svg id="eyeIcon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Submit -->
                <button type="submit" class="btn btn-primary btn-full" style="height:44px;font-size:.9rem;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                        <path d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4M10 17l5-5-5-5M15 12H3" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Masuk ke Dashboard
                </button>

                <p class="login-cred">
                    Demo: <code>admin / admin123</code> &nbsp;atau&nbsp; <code>user / user123</code>
                </p>

            </form>
        </div>

    </div>

    <script>
        function togglePw() {
            const input = document.getElementById('pwInput');
            input.type = input.type === 'password' ? 'text' : 'password';
            // swap icon
            const icon = document.getElementById('eyeIcon');
            icon.innerHTML = input.type === 'text' ?
                '<path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23" stroke-linecap="round"/>' :
                '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
        }
    </script>
</body>

</html>