<?php
// partials/header.php
// Required vars: $pageTitle, $pageSubtitle (optional), $activeMenu
$user = currentUser();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= htmlspecialchars($pageTitle ?? 'MIS Jalan') ?> — MIS Jalan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" />
    <link rel="stylesheet" href="<?= APP_URL ?>/css/style.css" />
</head>

<body>

    <aside class="sidebar" id="sidebar">

        <!-- Brand -->
        <div class="sidebar-brand">
            <div class="brand-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M3 7h18M3 12h18M3 17h18" stroke-linecap="round" />
                    <path d="M7 4v3M12 4v3M17 4v3M7 17v3M12 17v3M17 17v3" stroke-linecap="round" />
                </svg>
            </div>
            <div class="brand-text">
                <span class="brand-name">MIS Jalan</span>
                <span class="brand-sub">Monitoring System</span>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="sidebar-nav">
            <span class="nav-label">Menu Utama</span>

            <a href="<?= APP_URL ?>/dashboard.php"
                class="nav-item <?= ($activeMenu ?? '') === 'dashboard' ? 'active' : '' ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <rect x="3" y="3" width="7" height="7" rx="1.5" />
                    <rect x="14" y="3" width="7" height="7" rx="1.5" />
                    <rect x="14" y="14" width="7" height="7" rx="1.5" />
                    <rect x="3" y="14" width="7" height="7" rx="1.5" />
                </svg>
                <span>Dashboard</span>
            </a>

            <a href="<?= APP_URL ?>/pengajuan.php"
                class="nav-item <?= ($activeMenu ?? '') === 'pengajuan' ? 'active' : '' ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M9 12h6M9 16h4M17 3H7a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 002-2V7l-4-4z" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M17 3v4h4" stroke-linecap="round" />
                </svg>
                <span>Daftar Pengajuan</span>
            </a>

            <a href="<?= APP_URL ?>/tambah.php"
                class="nav-item <?= ($activeMenu ?? '') === 'tambah' ? 'active' : '' ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <circle cx="12" cy="12" r="9" />
                    <path d="M12 8v8M8 12h8" stroke-linecap="round" />
                </svg>
                <span>Tambah Pengajuan</span>
            </a>

            <?php if (isAdmin()): ?>
                <span class="nav-label">Admin</span>
                <a href="<?= APP_URL ?>/users.php"
                    class="nav-item <?= ($activeMenu ?? '') === 'users' ? 'active' : '' ?>">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" stroke-linecap="round" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" stroke-linecap="round" />
                    </svg>
                    <span>Kelola Users</span>
                </a>
            <?php endif; ?>
        </nav>

        <!-- User footer -->
        <div class="sidebar-footer">
            <div class="user-pill">
                <div class="user-avatar"><?= strtoupper(substr($user['nama'], 0, 1)) ?></div>
                <div class="user-info">
                    <span class="user-name"><?= htmlspecialchars($user['nama']) ?></span>
                    <span class="user-role"><?= ucfirst($user['role']) ?></span>
                </div>
            </div>
            <a href="<?= APP_URL ?>/logout.php" class="btn-logout" title="Keluar">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M17 16l4-4m0 0l-4-4m4 4H7M13 4H6a2 2 0 00-2 2v12a2 2 0 002 2h7" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </a>
        </div>

    </aside>

    <main class="main-content">

        <div class="topbar">
            <div class="topbar-left">
                <h1 class="page-title"><?= htmlspecialchars($pageTitle ?? '') ?></h1>
                <?php if (!empty($pageSubtitle)): ?>
                    <p class="page-subtitle"><?= htmlspecialchars($pageSubtitle) ?></p>
                <?php endif; ?>
            </div>
            <div class="topbar-right">
                <span class="badge-role <?= isAdmin() ? 'role-admin' : 'role-user' ?>">
                    <?= ucfirst($user['role']) ?>
                </span>
            </div>
        </div>