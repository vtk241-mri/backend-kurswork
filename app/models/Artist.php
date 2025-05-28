<?php

namespace App\Models;

use Core\Model;
use PDO;

class Artist extends Model
{
    protected string $table = 'artists';

    /**
     * Отримати всіх артистів
     *
     * @return array<int, array>
     */
    public function all(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM {$this->table}");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Знайти артиста за його ID
     *
     * @param int $id
     * @return array|null
     */
    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Створити нового артиста
     *
     * @param array $data
     * @return bool
     */
    public function create(array $data): bool
    {
        $sql = "INSERT INTO {$this->table} (name, bio, image)
                VALUES (:name, :bio, :image)";

        return $this->pdo->prepare($sql)->execute([
            'name' => $data['name'],
            'bio' => $data['bio'],
            'image' => $data['image'],
        ]);
    }

    /**
     * Оновити інформацію про артиста
     *
     * @param int   $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        $fields = [];
        $params = ['id' => $id];

        foreach (['name', 'bio', 'image'] as $f) {
            if (isset($data[$f])) {
                $fields[] = "$f = :$f";
                $params[$f] = $data[$f];
            }
        }

        if (empty($fields)) {
            return false;
        }

        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = :id";
        return $this->pdo->prepare($sql)->execute($params);
    }

    /**
     * Пошук артистів по частковій назві (тільки name, id, image)
     *
     * @param string $q
     * @return array<int, array>
     */
    public function searchByName(string $q): array
    {
        $stmt = $this->pdo->prepare("
            SELECT id, name, image
            FROM {$this->table}
            WHERE name LIKE :q
            ORDER BY name
            LIMIT 100
        ");
        $stmt->execute(['q' => "%{$q}%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Видалити артиста за ID
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return parent::delete($id);
    }

    /**
     * Отримати треки артиста за його ID
     *
     * @param int $id
     * @return array<int, array>
     */
    public function getTracksByArtistId(int $id): array
    {
        $sql = "
          SELECT t.*
            FROM tracks t
            JOIN track_artist ta ON ta.track_id = t.id
           WHERE ta.artist_id = :aid
           ORDER BY t.created_at DESC
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['aid' => $id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Порахувати загальну кількість артистів
     *
     * @return int
     */
    public function countAll(): int
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM {$this->table}");
        return (int) $stmt->fetchColumn();
    }

    /**
     * Отримати список артистів з пагінацією
     *
     * @param int $limit
     * @param int $offset
     * @return array<int, array>
     */
    public function getPaginated(int $limit, int $offset): array
    {
        $sql = "SELECT * FROM {$this->table}
                ORDER BY name ASC
                LIMIT :limit OFFSET :offset";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue('offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Порахувати кількість артистів з урахуванням пошукового запиту
     *
     * @param string $q
     * @return int
     */
    public function countFiltered(string $q = ''): int
    {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE 1";
        if ($q !== '') {
            $sql .= " AND name LIKE :q";
        }
        $stmt = $this->pdo->prepare($sql);
        if ($q !== '') {
            $stmt->bindValue('q', "%{$q}%", PDO::PARAM_STR);
        }
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    /**
     * Отримати відфільтрований список артистів з пагінацією
     *
     * @param int    $limit
     * @param int    $offset
     * @param string $q
     * @return array<int, array>
     */
    public function getFiltered(int $limit, int $offset, string $q = ''): array
    {
        $sql = "SELECT id, name, bio, image, created_at
                FROM {$this->table}
                WHERE 1";

        if ($q !== '') {
            $sql .= " AND name LIKE :q";
        }

        $sql .= " ORDER BY name ASC
                  LIMIT :limit OFFSET :offset";

        $stmt = $this->pdo->prepare($sql);
        if ($q !== '') {
            $stmt->bindValue('q', "%{$q}%", PDO::PARAM_STR);
        }
        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue('offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
