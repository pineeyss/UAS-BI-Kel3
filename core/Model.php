<?php
abstract class Model
{
    protected Database $db;
    protected string $table = '';

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function findAll(string $orderBy = 'id DESC'): array
    {
        return $this->db->fetchAll("SELECT * FROM {$this->table} ORDER BY $orderBy");
    }

    public function findById(int $id): array|false
    {
        return $this->db->fetchOne("SELECT * FROM {$this->table} WHERE id = ?", [$id]);
    }

    public function delete(int $id): void
    {
        $this->db->query("DELETE FROM {$this->table} WHERE id = ?", [$id]);
    }

    public function count(): int
    {
        $row = $this->db->fetchOne("SELECT COUNT(*) as total FROM {$this->table}");
        return (int) $row['total'];
    }
}
