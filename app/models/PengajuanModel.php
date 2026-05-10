<?php
// app/Models/PengajuanModel.php

class PengajuanModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /** Semua pengajuan dengan filter opsional */
    public function all(array $filter = []): array
    {
        $where  = ['1=1'];
        $params = [];

        if (!empty($filter['status'])) {
            $where[]  = 'p.status = ?';
            $params[] = $filter['status'];
        }
        if (!empty($filter['tingkat'])) {
            $where[]  = 'p.tingkat_kerusakan = ?';
            $params[] = $filter['tingkat'];
        }
        if (!empty($filter['search'])) {
            $where[] = '(p.no_pengajuan LIKE ? OR p.nama_pelapor LIKE ? OR p.lokasi LIKE ?)';
            $s = '%' . $filter['search'] . '%';
            array_push($params, $s, $s, $s);
        }
        if (!empty($filter['user_id'])) {
            $where[]  = 'p.user_id = ?';
            $params[] = $filter['user_id'];
        }

        $sql = "SELECT p.*, u.nama AS nama_user, u.username
                FROM pengajuan p
                LEFT JOIN users u ON u.id = p.user_id
                WHERE " . implode(' AND ', $where) . "
                ORDER BY p.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function findById(int $id): array|false
    {
        $stmt = $this->db->prepare(
            "SELECT p.*, u.nama AS nama_user, u.username
             FROM pengajuan p
             LEFT JOIN users u ON u.id = p.user_id
             WHERE p.id = ?"
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create(array $data): int
    {
        $sql  = "INSERT INTO pengajuan
                    (no_pengajuan, nama_pelapor, no_hp, lokasi, kecamatan, kelurahan,
                     jenis_kerusakan, tingkat_kerusakan, num_potholes, deskripsi, foto, user_id)
                 VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['no_pengajuan'],
            $data['nama_pelapor'],
            $data['no_hp']              ?? null,
            $data['lokasi'],
            $data['kecamatan'],
            $data['kelurahan'],
            $data['jenis_kerusakan'],
            $data['tingkat_kerusakan'],
            $data['num_potholes']       ?? 0,
            $data['deskripsi']          ?? null,
            $data['foto']               ?? null,
            $data['user_id']            ?? null,
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function updateStatus(int $id, string $status, string $catatan = ''): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE pengajuan SET status = ?, catatan_admin = ?, updated_at = NOW() WHERE id = ?"
        );
        return $stmt->execute([$status, $catatan, $id]);
    }

    public function delete(int $id): bool
    {
        // Hapus foto jika ada
        $item = $this->findById($id);
        if ($item && $item['foto']) {
            $path = UPLOAD_DIR . $item['foto'];
            if (file_exists($path)) unlink($path);
        }
        $stmt = $this->db->prepare("DELETE FROM pengajuan WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /** Statistik ringkas */
    public function stats(): array
    {
        $row = $this->db->query("
            SELECT
                COUNT(*) AS total,
                SUM(status = 'Pending')          AS pending,
                SUM(status = 'Diproses')         AS diproses,
                SUM(status = 'Selesai')          AS selesai,
                SUM(status = 'Ditolak')          AS ditolak,
                SUM(tingkat_kerusakan = 'Berat') AS berat
            FROM pengajuan
        ")->fetch();
        return $row ?: [];
    }

    /** N pengajuan terbaru */
    public function latest(int $limit = 5): array
    {
        $stmt = $this->db->prepare(
            "SELECT p.*, u.nama AS nama_user
             FROM pengajuan p
             LEFT JOIN users u ON u.id = p.user_id
             ORDER BY p.created_at DESC
             LIMIT ?"
        );
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }
}
