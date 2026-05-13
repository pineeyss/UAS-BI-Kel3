<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun — RoadReport</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="stylesheet" href="<?= BASE_URL ?>css/register.css">
</head>

<body class="register-body">

    <div class="register-wrapper">

        <div class="register-card">
            
            <span class="badge-top">Join Our Community</span>

            <div class="register-logo">
                <i class="fa-solid fa-user-plus"></i>
            </div>

            <h1>Daftar <i>Akun</i></h1>
            <p class="subtitle">Silakan lengkapi data diri Anda untuk mulai melaporkan kondisi jalan.</p>

            <?php if (!empty($error)): ?>
                <div class="alert-error">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= BASE_URL ?>auth/register">

                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" placeholder="Masukkan nama lengkap" required>
                </div>

                <div class="form-group">
                    <label>Nomor Telepon</label>
                    <input type="text" name="no_telp" placeholder="Contoh: 0812xxxx" required>
                </div>

                <div class="form-group">
                    <label>Alamat</label>
                    <textarea name="alamat" rows="3" placeholder="Masukkan alamat lengkap" required></textarea>
                </div>

                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" placeholder="Buat username unik" required>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Buat password minimal 6 karakter" required>
                </div>

                <button type="submit" class="btn-register">
                    <i class="fa-solid fa-paper-plane"></i> Daftar Sekarang
                </button>

            </form>

            <p class="footer-text">
                Sudah punya akun? <a href="<?= BASE_URL ?>auth/login">Login di sini</a>
            </p>

            <a href="<?= BASE_URL ?>home" class="back-home">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Halaman Utama
            </a>

        </div>
    </div>

</body>
</html>