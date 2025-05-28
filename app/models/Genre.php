<?php

namespace App\Models;

use Core\Model;
use PDO;

class Genre extends Model
{
    protected string $table = 'genres';

    /**
     * Отримати всі жанри
     *
     * @return array Список всіх жанрів
     */
    public function all(): array
    {
        return $this->pdo
            ->query("SELECT * FROM {$this->table}")
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Знайти жанр за ID
     *
     * @param int $id ID жанру
     * @return array|null Дані жанру або null, якщо не знайдено
     */
    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Створити новий жанр
     *
     * @param array $data Дані жанру (наприклад, ['name' => 'Pop'])
     * @return bool Чи вдалося створити запис
     */
    public function create(array $data): bool
    {
        $stmt = $this->pdo->prepare("INSERT INTO {$this->table} (name) VALUES (:name)");
        return $stmt->execute(['name' => $data['name']]);
    }

    /**
     * Оновити назву жанру за ID
     *
     * @param int   $id   ID жанру
     * @param array $data Нові дані (тільки 'name')
     * @return bool Чи вдалося оновити запис
     */
    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare("UPDATE {$this->table} SET name = :name WHERE id = :id");
        return $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
        ]);
    }

    /**
     * Видалити жанр за ID
     *
     * @param int $id ID жанру
     * @return bool Чи вдалося видалити
     */
    public function delete(int $id): bool
    {
        return parent::delete($id);
    }

    /**
     * Швидкий пошук жанрів за назвою (наприклад, для автозаповнення)
     *
     * @param string $q Рядок пошуку
     * @param int    $limit Максимальна кількість результатів
     * @return array Список жанрів, що відповідають запиту
     */
    public function search(string $q, int $limit = 50): array
    {
        $sql = "SELECT id, name
                  FROM {$this->table}
                 WHERE name LIKE :q
              ORDER BY name
                 LIMIT :limit";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue('q', '%' . $q . '%', PDO::PARAM_STR);
        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchByName(string $q): array
    {
        $stmt = $this->pdo->prepare("
            SELECT id, name
            FROM {$this->table}
            WHERE name LIKE :q
            ORDER BY name
            LIMIT 100
        ");
        $stmt->execute(['q' => "%{$q}%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Отримати треки, що належать до конкретного жанру
     *
     * @param int $id ID жанру
     * @return array Список треків
     */
    public function getTracksByGenreId(int $id): array
    {
        $sql = "
          SELECT t.*
            FROM tracks t
            JOIN track_genre tg ON tg.track_id = t.id
           WHERE tg.genre_id = :gid
           ORDER BY t.created_at DESC
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['gid' => $id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
