<?php
require_once ROOT . '/core/Model.php';

class PengajuanModel extends Model
{
    protected string $table = 'pengajuan';

    /* ══════════════════════════════════════════════════════
       BACA — Filter + Paginasi
    ══════════════════════════════════════════════════════ */
    public function findAllWithFilter(array $filter = [], int $page = 1, int $perPage = 15): array
    {
        [$where, $params] = $this->buildWhere($filter);
        $offset = ($page - 1) * $perPage;
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table}
             WHERE $where ORDER BY created_at DESC
             LIMIT $perPage OFFSET $offset",
            $params
        );
    }

    public function countWithFilter(array $filter = []): int
    {
        [$where, $params] = $this->buildWhere($filter);
        $row = $this->db->fetchOne(
            "SELECT COUNT(*) as total FROM {$this->table} WHERE $where",
            $params
        );
        return (int)($row['total'] ?? 0);
    }

    private function buildWhere(array $filter): array
    {
        $where  = ['1=1'];
        $params = [];

        if (!empty($filter['status'])) {
            $where[]  = 'statuslaporan = ?';
            $params[] = $filter['status'];
        }
        if (!empty($filter['tingkat'])) {
            $where[]  = 'tingkat_kerusakan = ?';
            $params[] = $filter['tingkat'];
        }
        if (!empty($filter['search'])) {
            $where[]  = 'nama_jalan LIKE ?';
            $params[] = '%' . $filter['search'] . '%';
        }
        if (!empty($filter['tanggal_dari'])) {
            $where[]  = 'DATE(created_at) >= ?';
            $params[] = $filter['tanggal_dari'];
        }
        if (!empty($filter['tanggal_sampai'])) {
            $where[]  = 'DATE(created_at) <= ?';
            $params[] = $filter['tanggal_sampai'];
        }
        return [implode(' AND ', $where), $params];
    }

    /* ══════════════════════════════════════════════════════
       BACA — Single / List helpers
    ══════════════════════════════════════════════════════ */

    /**
     * Ambil satu baris berdasarkan primary key.
     * Dipanggil dari PengajuanController::edit(), detail(), status().
     */
    public function findById(int $id): array|false
    {
        return $this->db->fetchOne(
            "SELECT * FROM {$this->table} WHERE id = ?",
            [$id]
        );
    }

    /**
     * Hapus laporan berdasarkan ID.
     * Dipanggil dari PengajuanController::delete().
     */
    public function delete(int $id): void
    {
        $this->db->query(
            "DELETE FROM {$this->table} WHERE id = ?",
            [$id]
        );
    }

    public function findRecent(int $limit = 10): array
    {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} ORDER BY created_at DESC LIMIT ?",
            [$limit]
        );
    }

    public function findByStatus(string $status, int $limit = 10): array
    {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table}
             WHERE statuslaporan = ? ORDER BY created_at DESC LIMIT ?",
            [$status, $limit]
        );
    }

    public function findByUserId(int $userId, int $limit = 10): array
    {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table}
             WHERE user_id = ? ORDER BY created_at DESC LIMIT ?",
            [$userId, $limit]
        );
    }

    /* ══════════════════════════════════════════════════════
       TULIS — Create
    ══════════════════════════════════════════════════════ */
    public function create(array $data): void
    {
        $this->db->query(
            "INSERT INTO pengajuan
             (nama_jalan, foto_path, latitude, longitude,
              deskripsi, user_id, created_at, statuslaporan)
             VALUES (?, ?, ?, ?, ?, ?, NOW(), 'diterima')",
            [
                $data['nama_jalan'],
                $data['foto_path']  ?? null,
                $data['latitude']   ?? 0,
                $data['longitude']  ?? 0,
                $data['deskripsi']  ?? null,
                $data['user_id']    ?? null,
            ]
        );
    }

    /* ══════════════════════════════════════════════════════
       TULIS — Update
    ══════════════════════════════════════════════════════ */
    public function update(int $id, array $data): void
    {
        $sets   = [];
        $params = [];
        foreach ($data as $k => $v) {
            $sets[]   = "$k = ?";
            $params[] = $v;
        }
        $params[] = $id;
        $this->db->query(
            "UPDATE pengajuan SET " . implode(', ', $sets) . " WHERE id = ?",
            $params
        );
    }

    /**
     * Admin: verifikasi laporan — set status, tingkat, catatan, admin_id, verified_at
     */
    public function verifikasiAdmin(int $id, string $status, string $tingkat, string $catatan, int $adminId): void
    {
        $this->db->query(
            "UPDATE pengajuan
             SET statuslaporan    = ?,
                 tingkat_kerusakan = ?,
                 catatan_admin    = ?,
                 admin_id         = ?,
                 verified_at      = NOW()
             WHERE id = ?",
            [$status, $tingkat, $catatan, $adminId, $id]
        );
    }

    /**
     * Dinas: update status pengerjaan + catatan + foto perbaikan
     */
    public function updateStatusDinas(int $id, string $status, ?string $catatan, ?string $fotoPerbaikan, int $dinasId): void
    {
        $this->db->query(
            "UPDATE pengajuan
             SET statuslaporan   = ?,
                 catatan_dinas   = ?,
                 foto_perbaikan  = COALESCE(?, foto_perbaikan),
                 updated_by      = ?,
                 updated_at      = NOW()
             WHERE id = ?",
            [$status, $catatan, $fotoPerbaikan, $dinasId, $id]
        );
    }

    /**
     * Backward-compat: dipakai dari PengajuanController->status()
     */
    public function updateStatus(int $id, string $status, ?string $catatan = null): void
    {
        $adminId = $_SESSION['user']['id'] ?? null;
        if ($adminId) {
            $this->db->query(
                "UPDATE pengajuan
                 SET statuslaporan = ?,
                     catatan_admin = COALESCE(?, catatan_admin),
                     admin_id      = ?,
                     verified_at   = NOW()
                 WHERE id = ?",
                [$status, $catatan, $adminId, $id]
            );
        } else {
            $this->db->query(
                "UPDATE pengajuan SET statuslaporan = ? WHERE id = ?",
                [$status, $id]
            );
        }
    }

    /* ══════════════════════════════════════════════════════
       ANALITIK / KPI
    ══════════════════════════════════════════════════════ */
    public function getKpiStats(): array
    {
        $row = $this->db->fetchOne(
            "SELECT
                COUNT(*)                                                          AS total,
                SUM(statuslaporan = 'diterima')                                  AS diterima,
                SUM(statuslaporan = 'diperbaiki')                                AS diperbaiki,
                SUM(statuslaporan = 'selesai')                                   AS selesai,
                SUM(statuslaporan = 'ditolak')                                   AS ditolak,
                SUM(MONTH(created_at)=MONTH(NOW()) AND YEAR(created_at)=YEAR(NOW())) AS bulan_ini
             FROM pengajuan"
        );
        return $row ?: [];
    }

    public function getByStatus(): array
    {
        return $this->db->fetchAll(
            "SELECT statuslaporan AS status, COUNT(*) AS jumlah
             FROM pengajuan GROUP BY statuslaporan"
        );
    }

    public function getTrendBulanan(): array
    {
        return $this->db->fetchAll(
            "SELECT DATE_FORMAT(created_at,'%Y-%m') AS bulan, COUNT(*) AS jumlah
             FROM pengajuan
             WHERE created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
             GROUP BY bulan ORDER BY bulan ASC"
        );
    }

    public function getByTingkat(): array
    {
        return $this->db->fetchAll(
            "SELECT tingkat_kerusakan AS tingkat, COUNT(*) AS jumlah
             FROM pengajuan
             WHERE tingkat_kerusakan IS NOT NULL
             GROUP BY tingkat_kerusakan ORDER BY FIELD(tingkat_kerusakan,'ringan','sedang','berat')"
        );
    }

    public function getTopJalan(int $limit = 10): array
    {
        return $this->db->fetchAll(
            "SELECT nama_jalan, COUNT(*) AS jumlah
             FROM pengajuan
             GROUP BY nama_jalan
             ORDER BY jumlah DESC
             LIMIT ?",
            [$limit]
        );
    }

    /** Rasio selesai vs total (%) */
    public function getResponseRate(): float
    {
        $row = $this->db->fetchOne(
            "SELECT
                COUNT(*) AS total,
                SUM(statuslaporan = 'selesai') AS selesai
             FROM pengajuan"
        );
        if (!$row || (int)$row['total'] === 0) return 0.0;
        return round((int)$row['selesai'] / (int)$row['total'] * 100, 1);
    }

    public function getByJenisKerusakan(): array
    {
        return $this->getByTingkat();
    }

    public function getByKecamatan(): array
    {
        return $this->getTopJalan(10);
    }

    /* ══════════════════════════════════════════════════════
       PETA
    ══════════════════════════════════════════════════════ */
    public function getForMap(): array
    {
        return $this->db->fetchAll(
            "SELECT id, nama_jalan, foto_path, latitude, longitude,
                    created_at, statuslaporan AS status, tingkat_kerusakan
             FROM pengajuan
             WHERE statuslaporan != 'ditolak'
               AND latitude  != 0
               AND longitude != 0"
        );
    }

    public function getKecamatanList(): array
    {
        return [];
    }
}
