<?php
require_once ROOT . '/core/Model.php';

class UserModel extends Model
{
    protected string $table = 'users';

    // Kolom tersedia: id, nama, username, password, role, created_at, updated_at

    public function findByUsername(string $username): array|false
    {
        return $this->db->fetchOne(
            "SELECT * FROM users WHERE username = ?",
            [$username]
        );
    }

    public function create(string $nama, string $username, string $password, string $role = 'user'): void
    {
        $this->db->query(
            "INSERT INTO users (nama, username, password, role, created_at, updated_at)
             VALUES (?, ?, ?, ?, NOW(), NOW())",
            [$nama, $username, password_hash($password, PASSWORD_BCRYPT), $role]
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
            "UPDATE users SET " . implode(', ', $sets) . ", updated_at = NOW() WHERE id = ?",
            $params
        );
    }
}
