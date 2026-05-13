<?php

/**
 * partials/header.php
 * Digunakan oleh semua view authenticated.
 * Controller bisa set $extraHead (CSS/meta tambahan) sebelum require file ini.
 */

$title     = $title     ?? APP_NAME;
$extraHead = $extraHead ?? '';
$role      = $_SESSION['role']  ?? 'guest';
$nama      = $_SESSION['nama']  ?? 'Pengguna';

/* ── Nav items per role ─────────────────────────────────── */
$navItems = [];
switch ($role) {
    case 'admin':
        $navItems = [
            ['url' => 'dashboard/admin',  'icon' => 'fa-gauge',            'label' => 'Dashboard'],
            ['url' => 'pengajuan',         'icon' => 'fa-clipboard-list',   'label' => 'Laporan'],
            ['url' => 'peta',              'icon' => 'fa-map-location-dot', 'label' => 'Peta'],
            ['url' => 'analitik',          'icon' => 'fa-chart-line',       'label' => 'Analitik'],
        ];
        break;
    case 'dinas':
        $navItems = [
            ['url' => 'dashboard/dinas',  'icon' => 'fa-gauge',            'label' => 'Dashboard'],
            ['url' => 'pengajuan',         'icon' => 'fa-clipboard-list',   'label' => 'Laporan'],
            ['url' => 'peta',              'icon' => 'fa-map-location-dot', 'label' => 'Peta'],
        ];
        break;
    case 'pimpinan':
        $navItems = [
            ['url' => 'dashboard/pimpinan', 'icon' => 'fa-gauge',          'label' => 'Dashboard'],
            ['url' => 'analitik',           'icon' => 'fa-chart-line',      'label' => 'Analitik'],
            ['url' => 'peta',               'icon' => 'fa-map-location-dot', 'label' => 'Peta'],
        ];
        break;
    default: /* masyarakat */
        $navItems = [
            ['url' => 'dashboard',         'icon' => 'fa-gauge',            'label' => 'Dashboard'],
            ['url' => 'pengajuan',         'icon' => 'fa-clipboard-list',   'label' => 'Laporan Saya'],
            ['url' => 'pengajuan/create',  'icon' => 'fa-plus',             'label' => 'Buat Laporan'],
            ['url' => 'peta',              'icon' => 'fa-map-location-dot', 'label' => 'Peta'],
        ];
}

/* ── Current URL helper ─────────────────────────────────── */
$currentUrl = $_GET['url'] ?? '';
// Fallback untuk PHP < 8.0 yang tidak punya str_starts_with()
if (!function_exists('str_starts_with')) {
    function str_starts_with(string $haystack, string $needle): bool
    {
        return strncmp($haystack, $needle, strlen($needle)) === 0;
    }
}
function isActive(string $url, string $current): string
{
    return str_starts_with($current, $url) ? 'active' : '';
}

/* ── Role badge ─────────────────────────────────────────── */
$roleMeta = [
    'admin'      => ['label' => 'Administrator', 'color' => '#dc2626', 'bg' => '#fef2f2'],
    'dinas'      => ['label' => 'Dinas Teknis',  'color' => '#d97706', 'bg' => '#fffbeb'],
    'pimpinan'   => ['label' => 'Pimpinan',      'color' => '#7c3aed', 'bg' => '#f5f3ff'],
    'masyarakat' => ['label' => 'Masyarakat',    'color' => '#059669', 'bg' => '#ecfdf5'],
];
$rm = $roleMeta[$role] ?? $roleMeta['masyarakat'];
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?> — RoadReport</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700;800&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        /* ══════════════════════════════════════════
       RESET & TOKENS
    ══════════════════════════════════════════ */
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --brand: #1d4ed8;
            --brand-dark: #1e3a8a;
            --brand-light: #eff6ff;
            --accent: #0ea5e9;
            --success: #16a34a;
            --warning: #d97706;
            --danger: #dc2626;
            --info: #0284c7;
            --surface: #ffffff;
            --surface-alt: #f8fafc;
            --border: #e2e8f0;
            --border-soft: #f1f5f9;
            --text: #0f172a;
            --text-muted: #64748b;
            --text-light: #94a3b8;
            --sidebar-w: 260px;
            --header-h: 64px;
            --radius: 14px;
            --radius-sm: 8px;
            --radius-lg: 20px;
            --shadow-sm: 0 1px 3px rgba(15, 23, 42, .06), 0 1px 2px rgba(15, 23, 42, .04);
            --shadow: 0 4px 16px rgba(15, 23, 42, .07), 0 1px 4px rgba(15, 23, 42, .04);
            --shadow-md: 0 10px 30px rgba(15, 23, 42, .10), 0 2px 8px rgba(15, 23, 42, .05);
            --trans: .2s ease;
        }

        html {
            font-size: 15px;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--surface-alt);
            color: var(--text);
            min-height: 100vh;
            display: flex;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        img {
            max-width: 100%;
        }

        /* ══════════════════════════════════════════
       SIDEBAR
    ══════════════════════════════════════════ */
        .sidebar {
            width: var(--sidebar-w);
            min-height: 100vh;
            background: var(--brand-dark);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 200;
            transition: transform var(--trans);
        }

        .sidebar-brand {
            padding: 22px 24px 18px;
            border-bottom: 1px solid rgba(255, 255, 255, .08);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-icon {
            width: 40px;
            height: 40px;
            background: var(--brand);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: white;
            flex-shrink: 0;
        }

        .brand-text {
            line-height: 1;
        }

        .brand-name {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 20px;
            font-weight: 800;
            color: white;
            letter-spacing: -.3px;
        }

        .brand-sub {
            font-size: 10.5px;
            color: rgba(255, 255, 255, .45);
            margin-top: 2px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        /* User info block */
        .sidebar-user {
            padding: 16px 20px;
            margin: 12px 12px 4px;
            background: rgba(255, 255, 255, .07);
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            gap: 11px;
        }

        .sidebar-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--brand);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            font-size: 14px;
            color: white;
            flex-shrink: 0;
        }

        .sidebar-user-name {
            font-weight: 600;
            font-size: 13.5px;
            color: white;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar-user-role {
            font-size: 11px;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 999px;
            display: inline-block;
            margin-top: 3px;
            background: <?= $rm['bg'] ?>;
            color: <?= $rm['color'] ?>;
        }

        /* Nav */
        .sidebar-nav {
            flex: 1;
            padding: 10px 12px;
            overflow-y: auto;
        }

        .nav-section-label {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: rgba(255, 255, 255, .30);
            padding: 14px 12px 6px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 11px 14px;
            border-radius: 10px;
            color: rgba(255, 255, 255, .7);
            font-size: 14px;
            font-weight: 500;
            transition: all var(--trans);
            margin-bottom: 2px;
            cursor: pointer;
        }

        .nav-item i {
            width: 18px;
            text-align: center;
            font-size: 14px;
            flex-shrink: 0;
            transition: color var(--trans);
        }

        .nav-item:hover {
            background: rgba(255, 255, 255, .09);
            color: white;
        }

        .nav-item.active {
            background: var(--brand);
            color: white;
            font-weight: 600;
            box-shadow: 0 4px 14px rgba(29, 78, 216, .35);
        }

        .nav-item.active i {
            color: white;
        }

        .sidebar-footer {
            padding: 14px 12px 20px;
            border-top: 1px solid rgba(255, 255, 255, .07);
        }

        .nav-logout {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 11px 14px;
            border-radius: 10px;
            color: rgba(255, 255, 255, .55);
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all var(--trans);
        }

        .nav-logout:hover {
            background: rgba(220, 38, 38, .15);
            color: #fca5a5;
        }

        .nav-logout i {
            width: 18px;
            text-align: center;
        }

        /* ══════════════════════════════════════════
       MAIN AREA
    ══════════════════════════════════════════ */
        .main-wrapper {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Top Bar */
        .topbar {
            height: var(--header-h);
            background: var(--surface);
            border-bottom: 1px solid var(--border-soft);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: var(--shadow-sm);
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .topbar-title {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 17px;
            font-weight: 700;
            color: var(--text);
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .topbar-badge {
            font-size: 12px;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 999px;
            background: <?= $rm['bg'] ?>;
            color: <?= $rm['color'] ?>;
        }

        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 20px;
            color: var(--text-muted);
            cursor: pointer;
            padding: 4px;
        }

        /* Page Content */
        .page-content {
            padding: 28px;
            flex: 1;
        }

        /* Flash messages */
        .flash-msg {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 18px;
            border-radius: var(--radius-sm);
            margin-bottom: 22px;
            font-weight: 500;
            font-size: 14px;
            animation: slideIn .3s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .flash-success {
            background: #f0fdf4;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .flash-danger {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .flash-warning {
            background: #fffbeb;
            color: #92400e;
            border: 1px solid #fde68a;
        }

        .flash-info {
            background: #eff6ff;
            color: #1e40af;
            border: 1px solid #bfdbfe;
        }

        /* ══════════════════════════════════════════
       COMPONENTS — KPI Cards
    ══════════════════════════════════════════ */
        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
            margin-bottom: 24px;
        }

        .kpi-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 20px 22px;
            display: flex;
            align-items: flex-start;
            gap: 16px;
            box-shadow: var(--shadow-sm);
            transition: all var(--trans);
        }

        .kpi-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
        }

        .kpi-icon {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 19px;
            flex-shrink: 0;
        }

        .bg-blue {
            background: #eff6ff;
            color: var(--brand);
        }

        .bg-yellow {
            background: #fffbeb;
            color: var(--warning);
        }

        .bg-orange {
            background: #fff7ed;
            color: #ea580c;
        }

        .bg-green {
            background: #f0fdf4;
            color: var(--success);
        }

        .bg-red {
            background: #fef2f2;
            color: var(--danger);
        }

        .bg-purple {
            background: #f5f3ff;
            color: #7c3aed;
        }

        .kpi-body {
            flex: 1;
            min-width: 0;
        }

        .kpi-value {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 28px;
            font-weight: 800;
            line-height: 1;
            color: var(--text);
        }

        .kpi-label {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-muted);
            margin-top: 5px;
        }

        .kpi-sub {
            font-size: 11.5px;
            color: var(--text-light);
            margin-top: 3px;
        }

        .text-success {
            color: var(--success) !important;
        }

        .text-warning {
            color: var(--warning) !important;
        }

        .text-danger {
            color: var(--danger) !important;
        }

        .text-info {
            color: var(--info) !important;
        }

        /* ══════════════════════════════════════════
       COMPONENTS — Card
    ══════════════════════════════════════════ */
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
            margin-bottom: 22px;
        }

        .card-header {
            padding: 18px 22px;
            border-bottom: 1px solid var(--border-soft);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .card-header h3 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 15px;
            font-weight: 700;
            color: var(--text);
            display: flex;
            align-items: center;
            gap: 9px;
        }

        .card-header h3 i {
            color: var(--brand);
            font-size: 15px;
        }

        .card-body {
            padding: 22px;
        }

        .card-body.p-0 {
            padding: 0;
        }

        /* ══════════════════════════════════════════
       COMPONENTS — Buttons
    ══════════════════════════════════════════ */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 18px;
            border-radius: var(--radius-sm);
            font-size: 13.5px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all var(--trans);
            text-decoration: none;
            white-space: nowrap;
            font-family: 'DM Sans', sans-serif;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .btn-primary {
            background: var(--brand);
            color: white;
            box-shadow: 0 4px 12px rgba(29, 78, 216, .25);
        }

        .btn-primary:hover {
            background: var(--brand-dark);
        }

        .btn-success {
            background: var(--success);
            color: white;
        }

        .btn-warning {
            background: var(--warning);
            color: white;
        }

        .btn-danger {
            background: var(--danger);
            color: white;
        }

        .btn-info {
            background: var(--info);
            color: white;
        }

        .btn-outline {
            background: transparent;
            color: var(--text-muted);
            border: 1px solid var(--border);
        }

        .btn-outline:hover {
            background: var(--surface-alt);
            color: var(--text);
        }

        .btn-sm {
            padding: 6px 13px;
            font-size: 12.5px;
        }

        .btn-block {
            width: 100%;
            justify-content: center;
            margin-bottom: 8px;
        }

        /* ══════════════════════════════════════════
       COMPONENTS — Badges
    ══════════════════════════════════════════ */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 10px;
            border-radius: 999px;
            font-size: 11.5px;
            font-weight: 700;
            white-space: nowrap;
        }

        .badge-lg {
            padding: 5px 14px;
            font-size: 13px;
        }

        .status-diterima {
            background: #eff6ff;
            color: #1e40af;
        }

        .status-diperbaiki {
            background: #fff7ed;
            color: #c2410c;
        }

        .status-selesai {
            background: #f0fdf4;
            color: #166534;
        }

        .status-ditolak {
            background: #fef2f2;
            color: #991b1b;
        }

        /* ══════════════════════════════════════════
       COMPONENTS — Table
    ══════════════════════════════════════════ */
        .table-responsive {
            overflow-x: auto;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13.5px;
        }

        .table th {
            background: var(--surface-alt);
            color: var(--text-muted);
            font-size: 11.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .5px;
            padding: 11px 16px;
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }

        .table td {
            padding: 13px 16px;
            border-bottom: 1px solid var(--border-soft);
            color: var(--text);
            vertical-align: middle;
        }

        .table-hover tbody tr:hover td {
            background: var(--brand-light);
        }

        .table a {
            color: var(--brand);
            font-weight: 600;
        }

        .table a:hover {
            text-decoration: underline;
        }

        .action-col {
            white-space: nowrap;
            display: flex;
            gap: 6px;
            align-items: center;
        }

        .text-center {
            text-align: center;
        }

        .text-muted {
            color: var(--text-muted);
        }

        .table-info {
            font-size: 13px;
            color: var(--text-muted);
            padding: 10px 0 12px;
        }

        /* ══════════════════════════════════════════
       COMPONENTS — Forms
    ══════════════════════════════════════════ */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        .form-full {
            grid-column: 1 / -1;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .form-group label {
            font-size: 13px;
            font-weight: 600;
            color: var(--text);
        }

        .form-control,
        select.form-control {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            font-size: 14px;
            font-family: 'DM Sans', sans-serif;
            background: var(--surface);
            color: var(--text);
            transition: border-color var(--trans), box-shadow var(--trans);
            outline: none;
        }

        .form-control:focus {
            border-color: var(--brand);
            box-shadow: 0 0 0 3px rgba(29, 78, 216, .1);
        }

        .form-control.is-invalid {
            border-color: var(--danger);
        }

        .invalid-feedback {
            font-size: 12px;
            color: var(--danger);
        }

        .required {
            color: var(--danger);
        }

        .form-actions {
            display: flex;
            gap: 10px;
            padding-top: 6px;
        }

        /* foto preview */
        .foto-section {
            margin-top: 20px;
        }

        .foto-section h4 {
            font-size: 13.5px;
            font-weight: 700;
            color: var(--text-muted);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .foto-full {
            width: 100%;
            max-height: 360px;
            object-fit: cover;
            border-radius: var(--radius-sm);
            border: 1px solid var(--border);
        }

        .foto-preview img {
            max-width: 200px;
            border-radius: var(--radius-sm);
            border: 1px solid var(--border);
            margin-top: 8px;
        }

        .foto-preview small {
            display: block;
            font-size: 12px;
            color: var(--text-light);
            margin-top: 4px;
        }

        /* ══════════════════════════════════════════
       COMPONENTS — Filter Bar
    ══════════════════════════════════════════ */
        .filter-bar {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 16px;
            align-items: flex-end;
        }

        .filter-bar .form-control {
            width: auto;
            min-width: 160px;
        }

        /* ══════════════════════════════════════════
       COMPONENTS — Pagination
    ══════════════════════════════════════════ */
        .pagination {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
            padding-top: 16px;
        }

        .page-info {
            font-size: 13px;
            color: var(--text-muted);
            font-weight: 500;
        }

        /* ══════════════════════════════════════════
       COMPONENTS — Layout Grids
    ══════════════════════════════════════════ */
        .row-2col {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 22px;
            margin-bottom: 22px;
        }

        .analitik-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 22px;
        }

        .analitik-full {
            grid-column: 1 / -1;
        }

        /* ══════════════════════════════════════════
       COMPONENTS — Detail grid
    ══════════════════════════════════════════ */
        .detail-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .detail-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .detail-label {
            font-size: 11.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .4px;
            color: var(--text-light);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .detail-value {
            font-size: 14.5px;
            font-weight: 500;
            color: var(--text);
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .legend-dot {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            vertical-align: middle;
        }

        .map-legend-inline {
            font-size: 13px;
            color: var(--text-muted);
        }

        /* ══════════════════════════════════════════
       RESPONSIVE
    ══════════════════════════════════════════ */
        @media (max-width: 1100px) {
            .kpi-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 900px) {

            .row-2col,
            .analitik-grid {
                grid-template-columns: 1fr;
            }

            .analitik-full {
                grid-column: 1;
            }

            .detail-grid {
                grid-template-columns: 1fr;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .main-wrapper {
                margin-left: 0;
            }

            .mobile-toggle {
                display: block;
            }

            .page-content {
                padding: 18px;
            }

            .kpi-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 480px) {
            .kpi-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <?= $extraHead ?>
</head>

<body>

    <!-- ═══ SIDEBAR ═══════════════════════════════════════════ -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="brand-icon"><i class="fa-solid fa-road"></i></div>
            <div class="brand-text">
                <div class="brand-name">RoadReport</div>
                <div class="brand-sub">MIS Jalan</div>
            </div>
        </div>

        <div class="sidebar-user">
            <div class="sidebar-avatar"><?= strtoupper(substr($nama, 0, 1)) ?></div>
            <div>
                <div class="sidebar-user-name"><?= htmlspecialchars($nama) ?></div>
                <div class="sidebar-user-role"><?= $rm['label'] ?></div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section-label">Menu</div>
            <?php foreach ($navItems as $item): ?>
                <a href="<?= BASE_URL . $item['url'] ?>"
                    class="nav-item <?= isActive($item['url'], $currentUrl) ?>">
                    <i class="fa-solid <?= $item['icon'] ?>"></i>
                    <?= $item['label'] ?>
                </a>
            <?php endforeach; ?>
        </nav>

        <div class="sidebar-footer">
            <a href="<?= BASE_URL ?>auth/logout" class="nav-logout">
                <i class="fa-solid fa-right-from-bracket"></i>
                Logout
            </a>
        </div>
    </aside>

    <!-- ═══ MAIN ═══════════════════════════════════════════════ -->
    <div class="main-wrapper">
        <header class="topbar">
            <div class="topbar-left">
                <button class="mobile-toggle" onclick="document.getElementById('sidebar').classList.toggle('open')">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <span class="topbar-title"><?= htmlspecialchars($title) ?></span>
            </div>
            <div class="topbar-right">
                <span class="topbar-badge"><?= $rm['label'] ?></span>
                <a href="<?= BASE_URL ?>home" class="btn btn-outline btn-sm" title="Halaman Publik">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                </a>
            </div>
        </header>
        <main class="page-content">
            
            <?php
            /* Flash message */
            if (!empty($_SESSION['flash'])):
                $flash = $_SESSION['flash'];
                unset($_SESSION['flash']);
                $fClass = match ($flash['type']) {
                    'success' => 'flash-success',
                    'danger'  => 'flash-danger',
                    'warning' => 'flash-warning',
                    default   => 'flash-info',
                };
                $fIcon = match ($flash['type']) {
                    'success' => 'fa-circle-check',
                    'danger'  => 'fa-circle-xmark',
                    'warning' => 'fa-triangle-exclamation',
                    default   => 'fa-circle-info',
                };
            ?>
                <div class="flash-msg <?= $fClass ?>">
                    <i class="fa-solid <?= $fIcon ?>"></i>
                    <?= htmlspecialchars($flash['msg']) ?>
                </div>
            <?php endif; ?>