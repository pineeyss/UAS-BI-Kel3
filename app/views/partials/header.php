<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        <?= htmlspecialchars($title ?? 'SIJALAN') ?>
        — SIJALAN
    </title>

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="stylesheet"
        href="<?= BASE_URL ?>css/style.css">

    <?= $extraHead ?? '' ?>
</head>

<body>

    <div class="wrapper">

        <!-- SIDEBAR -->
        <aside class="sidebar">

            <!-- BRAND -->
            <div class="sidebar-brand">

                <div class="brand-icon">
                    <i class="fa-solid fa-road"></i>
                </div>

                <div class="brand-text">
                    <h2>SIJALAN</h2>
                    <span>Road Monitoring System</span>
                </div>

            </div>

            <!-- NAVIGATION -->
            <div class="sidebar-menu">

                <div class="menu-label">
                    MENU UTAMA
                </div>

                <a href="<?= BASE_URL ?>dashboard"
                    class="nav-link <?= (strpos($_GET['url'] ?? '', 'dashboard') !== false || empty($_GET['url'])) ? 'active' : '' ?>">

                    <i class="fa-solid fa-chart-line"></i>
                    <span>Dashboard</span>

                </a>

                <a href="<?= BASE_URL ?>pengajuan"
                    class="nav-link <?= (strpos($_GET['url'] ?? '', 'pengajuan') !== false) ? 'active' : '' ?>">

                    <i class="fa-solid fa-clipboard-list"></i>
                    <span>Data Pengajuan</span>

                </a>

                <a href="<?= BASE_URL ?>peta"
                    class="nav-link <?= (strpos($_GET['url'] ?? '', 'peta') !== false) ? 'active' : '' ?>">

                    <i class="fa-solid fa-map-location-dot"></i>
                    <span>Peta Sebaran</span>

                </a>

                <?php if (
                    isset($_SESSION['user']) &&
                    $_SESSION['user']['role'] === 'pimpinan'
                ): ?>

                    <div class="menu-label">
                        BUSINESS INTELLIGENCE
                    </div>

                    <a href="<?= BASE_URL ?>analitik"
                        class="nav-link <?= (strpos($_GET['url'] ?? '', 'analitik') !== false) ? 'active' : '' ?>">

                        <i class="fa-solid fa-chart-pie"></i>
                        <span>Dashboard BI</span>

                    </a>

                <?php endif; ?>

                <?php if (
                    isset($_SESSION['user']) &&
                    $_SESSION['user']['role'] === 'masyarakat'
                ): ?>

                    <div class="menu-label">
                        PELAPORAN
                    </div>

                    <a href="<?= BASE_URL ?>pengajuan/create"
                        class="nav-link">

                        <i class="fa-solid fa-plus"></i>
                        <span>Buat Pengajuan</span>

                    </a>

                <?php endif; ?>

            </div>

            <!-- FOOTER -->
            <div class="sidebar-footer">

                <?php if (isset($_SESSION['user'])): ?>

                    <div class="user-box">

                        <div class="user-avatar">
                            <i class="fa-solid fa-user"></i>
                        </div>

                        <div class="user-detail">

                            <div class="user-name">
                                <?= htmlspecialchars($_SESSION['user']['nama']) ?>
                            </div>

                            <div class="user-role">
                                <?= ucfirst($_SESSION['user']['role']) ?>
                            </div>

                        </div>

                    </div>

                    <a href="<?= BASE_URL ?>auth/logout"
                        class="logout-btn">

                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        Keluar

                    </a>

                <?php else: ?>

                    <a href="<?= BASE_URL ?>auth/login"
                        class="login-btn">

                        <i class="fa-solid fa-right-to-bracket"></i>
                        Login

                    </a>

                <?php endif; ?>

            </div>

        </aside>

        <!-- MAIN -->
        <main class="main-content">

        <!-- TOPBAR -->
        <header class="topbar">

            <div class="page-header-modern">

                <div>

                    <h1 class="page-title-modern">

                        <?= htmlspecialchars($title ?? '') ?>

                    </h1>

                    <p class="page-subtitle">

                        Sistem Informasi Monitoring Perbaikan Jalan

                    </p>

                </div>

                <div class="page-role">

                    <i class="fa-solid fa-user-shield"></i>

                    <?= ucfirst($_SESSION['role'] ?? '') ?>

                </div>

            </div>

        </header>

            <!-- FLASH -->
            <?php if (!empty($_SESSION['flash'])): ?>

                <div class="alert alert-<?= $_SESSION['flash']['type'] ?>">

                    <?= htmlspecialchars($_SESSION['flash']['msg']) ?>

                    <button
                        class="alert-close"
                        onclick="this.parentElement.remove()">

                        &times;

                    </button>

                </div>

            <?php
                unset($_SESSION['flash']);
            endif;
            ?>

            <!-- CONTENT -->
            <section class="content-area">