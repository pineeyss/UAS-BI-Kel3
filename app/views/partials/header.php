<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'SIJALAN') ?> — SIJALAN</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/style.css">
    <?= $extraHead ?? '' ?>
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav class="sidebar" id="sidebar">
            <div class="sidebar-brand">
                <i class="fa-solid fa-road"></i>
                <span>SIJALAN</span>
            </div>
            <div class="sidebar-nav">
                <div class="nav-section">Menu Utama</div>
                <a href="<?= BASE_URL ?>dashboard" class="nav-item <?= (strpos($_GET['url'] ?? '', 'dashboard') !== false || empty($_GET['url'])) ? 'active' : '' ?>">
                    <i class="fa-solid fa-gauge-high"></i> Dashboard
                </a>
                <a href="<?= BASE_URL ?>pengajuan" class="nav-item <?= (strpos($_GET['url'] ?? '', 'pengajuan') !== false) ? 'active' : '' ?>">
                    <i class="fa-solid fa-clipboard-list"></i> Data Pengajuan
                </a>
                <a href="<?= BASE_URL ?>peta" class="nav-item <?= (strpos($_GET['url'] ?? '', 'peta') !== false) ? 'active' : '' ?>">
                    <i class="fa-solid fa-map-location-dot"></i> Peta Sebaran
                </a>
                <div class="nav-section">Laporan</div>
                <a href="<?= BASE_URL ?>analitik" class="nav-item <?= (strpos($_GET['url'] ?? '', 'analitik') !== false) ? 'active' : '' ?>">
                    <i class="fa-solid fa-chart-bar"></i> Analitik
                </a>
            </div>
            <div class="sidebar-footer">
                <div class="user-info">
                    <i class="fa-solid fa-circle-user fa-2x"></i>
                    <div>
                        <div class="user-name"><?= htmlspecialchars($_SESSION['nama'] ?? '') ?></div>
                        <div class="user-role"><?= htmlspecialchars($_SESSION['role'] ?? '') ?></div>
                    </div>
                </div>
                <a href="<?= BASE_URL ?>auth/logout" class="btn-logout">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i> Keluar
                </a>
            </div>
        </nav>

        <!-- Main -->
        <div class="main-content">
            <header class="topbar">
                <button class="btn-toggle-sidebar" id="toggleSidebar">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <h1 class="page-title"><?= htmlspecialchars($title ?? '') ?></h1>
                <div class="topbar-right">
                    <span class="badge-role role-<?= $_SESSION['role'] ?? 'user' ?>">
                        <i class="fa-solid fa-user-shield"></i>
                        <?= ucfirst($_SESSION['role'] ?? '') ?>
                    </span>
                </div>
            </header>

            <?php if (!empty($_SESSION['flash'])): ?>
                <div class="alert alert-<?= $_SESSION['flash']['type'] ?>" id="flashMsg">
                    <?= htmlspecialchars($_SESSION['flash']['msg']) ?>
                    <button onclick="this.parentElement.remove()" class="alert-close">&times;</button>
                </div>
            <?php unset($_SESSION['flash']);
            endif; ?>

            <div class="content-area">