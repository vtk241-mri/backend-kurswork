<?php
namespace Core;

use PDO;

class Model
{
    protected PDO $pdo;
    protected string $table;

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function all(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM {$this->table}");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Повертає загальну кількість рядків у таблиці
     */
    public function count(string $column = 'id'): int
    {
        $stmt = $this->pdo->query("SELECT COUNT({$column}) FROM {$this->table}");
        return (int) $stmt->fetchColumn();
    }

    /**
     * Повертає останні $limit записів, за датою $orderBy
     */
    public function recent(int $limit = 5, string $orderBy = 'created_at'): array
    {
        $sql = "SELECT * FROM {$this->table}
                ORDER BY {$orderBy} DESC
                LIMIT :limit";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
