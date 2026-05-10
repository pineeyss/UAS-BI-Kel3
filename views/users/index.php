<?php
// views/users/index.php
$users = $users ?? [];
$stats = $stats ?? ['total' => 0, 'pending' => 0];
$countPerUser = $countPerUser ?? [];
?>

<div class="page-body">

    <!-- Summary bar -->
    <div class="stats-grid" style="grid-template-columns:repeat(3,1fr)">
        <div class="stat-card">
            <div class="stat-icon icon-blue">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" stroke-linecap="round" />
                    <circle cx="9" cy="7" r="4" />
                    <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" stroke-linecap="round" />
                </svg>
            </div>
            <div class="stat-body">
                <span class="stat-label">Total Users</span>
                <span class="stat-value"><?= count($users) ?></span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon icon-green">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <polyline points="20,6 9,17 4,12" stroke-linecap="round" />
                </svg>
            </div>
            <div class="stat-body">
                <span class="stat-label">Total Pengajuan</span>
                <span class="stat-value"><?= number_format($stats['total'] ?? 0) ?></span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon icon-amber">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <circle cx="12" cy="12" r="9" />
                    <path d="M12 8v4l3 3" stroke-linecap="round" />
                </svg>
            </div>
            <div class="stat-body">
                <span class="stat-label">Pending</span>
                <span class="stat-value"><?= number_format($stats['pending'] ?? 0) ?></span>
            </div>
        </div>
    </div>

    <!-- Users grid -->
    <div class="card">
        <div class="card-head">
            <div class="card-head-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" stroke-linecap="round" />
                    <circle cx="9" cy="7" r="4" />
                    <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" stroke-linecap="round" />
                </svg>
                Daftar Pengguna
            </div>
        </div>

        <div class="users-grid">
            <?php foreach ($users as $u): ?>
                <?php
                $jml    = $countPerUser[$u['id']] ?? 0;
                $initials = strtoupper(substr($u['nama'], 0, 1));
                $joined = date('d M Y', strtotime($u['created_at']));
                ?>
                <div class="user-card">
                    <div class="user-card-head">
                        <div class="uc-avatar"><?= $initials ?></div>
                        <div>
                            <div class="uc-name"><?= htmlspecialchars($u['nama']) ?></div>
                            <div class="uc-username">@<?= htmlspecialchars($u['username']) ?></div>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:8px">
                        <span class="badge-role <?= $u['role'] === 'admin' ? 'role-admin' : 'role-user' ?>" style="font-size:.68rem">
                            <?= ucfirst($u['role']) ?>
                        </span>
                    </div>
                    <div style="display:flex;flex-direction:column;gap:5px">
                        <div class="uc-stat">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" width="14" height="14">
                                <path d="M9 12h6M9 16h4M17 3H7a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 002-2V7l-4-4z" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M17 3v4h4" stroke-linecap="round" />
                            </svg>
                            <strong><?= $jml ?></strong> pengajuan
                        </div>
                        <div class="uc-stat">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" width="14" height="14">
                                <rect x="3" y="4" width="18" height="18" rx="2" />
                                <path d="M16 2v4M8 2v4M3 10h18" stroke-linecap="round" />
                            </svg>
                            Bergabung <?= $joined ?>
                        </div>
                    </div>
                    <a href="<?= APP_URL ?>/pengajuan.php?user_id=<?= $u['id'] ?>" class="btn btn-ghost btn-sm" style="width:100%;justify-content:center">
                        Lihat Pengajuan →
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

</div>