<?php
require_once ROOT . '/core/Model.php';

class PengajuanModel extends Model
{
    protected string $table = 'pengajuan';

    // -------------------------------------------------------
    // Kolom database yang tersedia:
    // id, nama_jalan, foto_path, latitude, longitude,
    // created_at, statuslaporan
    // -------------------------------------------------------

    public function findAllWithFilter(array $filter = [], int $page = 1, int $perPage = 15): array
    {
        $where  = ['1=1'];
        $params = [];

        if (!empty($filter['status'])) {
            $where[]  = 'statuslaporan = ?';
            $params[] = $filter['status'];
        }
        if (!empty($filter['search'])) {
            $where[]  = '(nama_jalan LIKE ?)';
            $s        = '%' . $filter['search'] . '%';
            $params[] = $s;
        }
        if (!empty($filter['tanggal_dari'])) {
            $where[]  = 'DATE(created_at) >= ?';
            $params[] = $filter['tanggal_dari'];
        }
        if (!empty($filter['tanggal_sampai'])) {
            $where[]  = 'DATE(created_at) <= ?';
            $params[] = $filter['tanggal_sampai'];
        }

        $whereStr = implode(' AND ', $where);
        $offset   = ($page - 1) * $perPage;

        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} WHERE $whereStr ORDER BY created_at DESC LIMIT $perPage OFFSET $offset",
            $params
        );
    }

    public function countWithFilter(array $filter = []): int
    {
        $where  = ['1=1'];
        $params = [];

        if (!empty($filter['status'])) {
            $where[]  = 'statuslaporan = ?';
            $params[] = $filter['status'];
        }
        if (!empty($filter['search'])) {
            $where[]  = '(nama_jalan LIKE ?)';
            $s        = '%' . $filter['search'] . '%';
            $params[] = $s;
        }

        $whereStr = implode(' AND ', $where);
        $row      = $this->db->fetchOne(
            "SELECT COUNT(*) as total FROM {$this->table} WHERE $whereStr",
            $params
        );
        return (int) ($row['total'] ?? 0);
    }

    public function create(array $data): void
    {
        $this->db->query(
            "INSERT INTO pengajuan
             (nama_jalan, foto_path, latitude, longitude, created_at, statuslaporan)
             VALUES (?, ?, ?, ?, NOW(), 'diterima')",
            [
                $data['nama_jalan'],
                $data['foto_path'] ?? null,
                $data['latitude']  ?? 0,
                $data['longitude'] ?? 0,
            ]
        );
    }

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

    public function updateStatus(int $id, string $status): void
    {
        $this->db->query(
            "UPDATE pengajuan SET statuslaporan = ? WHERE id = ?",
            [$status, $id]
        );
    }

    public function getKpiStats(): array
    {
        $stats = $this->db->fetchOne(
            "SELECT
                COUNT(*) as total,
                SUM(statuslaporan = 'diterima')  as diterima,
                SUM(statuslaporan = 'diperbaiki') as diperbaiki,
                SUM(statuslaporan = 'selesai')   as selesai,
                SUM(statuslaporan = 'ditolak')   as ditolak,
                SUM(MONTH(created_at) = MONTH(NOW()) AND YEAR(created_at) = YEAR(NOW())) as bulan_ini
             FROM pengajuan"
        );
        return $stats ?: [];
    }

    public function getByStatus(): array
    {
        return $this->db->fetchAll(
            "SELECT statuslaporan as status, COUNT(*) as jumlah
             FROM pengajuan GROUP BY statuslaporan"
        );
    }

    public function getTrendBulanan(): array
    {
        return $this->db->fetchAll(
            "SELECT DATE_FORMAT(created_at,'%Y-%m') as bulan, COUNT(*) as jumlah
             FROM pengajuan
             WHERE created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
             GROUP BY bulan ORDER BY bulan ASC"
        );
    }

    public function getByJenisKerusakan(): array
    {
        // Tidak ada kolom jenis_kerusakan — ekstrak dari nama_jalan
        // Kembalikan distribusi berdasarkan prefix nama jalan sebagai alternatif
        return $this->db->fetchAll(
            "SELECT
                SUBSTRING_INDEX(nama_jalan, ' ', 2) as jenis_kerusakan,
                COUNT(*) as jumlah
             FROM pengajuan
             GROUP BY jenis_kerusakan
             ORDER BY jumlah DESC
             LIMIT 10"
        );
    }

    public function getByKecamatan(): array
    {
        // Tidak ada kolom kecamatan — ekstrak dari nama_jalan (kata ke-3 dan ke-4)
        return $this->db->fetchAll(
            "SELECT
                SUBSTRING_INDEX(SUBSTRING_INDEX(nama_jalan, ' ', 4), ' ', -2) as kecamatan,
                COUNT(*) as jumlah
             FROM pengajuan
             GROUP BY kecamatan
             ORDER BY jumlah DESC
             LIMIT 10"
        );
    }

    public function getForMap(): array
    {
        return $this->db->fetchAll(
            "SELECT id, nama_jalan, foto_path, latitude, longitude, created_at, statuslaporan as status
             FROM pengajuan
             WHERE statuslaporan != 'ditolak'
               AND latitude != 0
               AND longitude != 0"
        );
    }

    public function getKecamatanList(): array
    {
        // Tidak ada kolom kecamatan terpisah — kembalikan array kosong
        // Filter kecamatan dinonaktifkan
        return [];
    }
}
