<?php

namespace App\Models;

use Core\Model;
use PDO;

class Comment extends Model
{
    protected string $table = 'comments';

    /**
     * Створити новий коментар
     *
     * @param array $data Дані коментаря: track_id, user_id, content
     * @return bool Чи успішно створено
     */
    public function create(array $data): bool
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO {$this->table} (track_id, user_id, content)
             VALUES (:track_id, :user_id, :content)"
        );
        return $stmt->execute([
            'track_id' => $data['track_id'],
            'user_id' => $data['user_id'],
            'content' => $data['content'],
        ]);
    }

    /**
     * Отримати всі коментарі до треку з іменами користувачів
     *
     * @param int $trackId ID треку
     * @return array Список коментарів
     */
    public function getByTrackId(int $trackId): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT c.id, c.content, c.created_at, u.username AS user_name
             FROM {$this->table} c
             JOIN users u ON c.user_id = u.id
             WHERE c.track_id = ?
             ORDER BY c.created_at DESC"
        );
        $stmt->execute([$trackId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Видалити коментар за ID
     *
     * @param int $id ID коментаря
     * @return bool Чи успішно видалено
     */
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Отримати останні N коментарів з іменами користувачів і назвами треків
     *
     * @param int $limit Кількість коментарів (за замовчуванням 5)
     * @return array Список коментарів
     */
    public function recentWithMeta(int $limit = 5): array
    {
        $sql = "SELECT
                c.id,
                c.content,
                c.created_at,
                u.username    AS user_name,
                t.id          AS track_id,
                t.title       AS track_title
            FROM {$this->table} c
            LEFT JOIN users  u ON c.user_id  = u.id
            LEFT JOIN tracks t ON c.track_id = t.id
            ORDER BY c.created_at DESC
            LIMIT :limit";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Підрахунок кількості коментарів з урахуванням пошукового запиту
     *
     * @param string $q Пошуковий рядок
     * @return int Кількість знайдених записів
     */
    public function countFiltered(string $q = ''): int
    {
        $sql = "
            SELECT COUNT(*) 
            FROM {$this->table} c
            JOIN users  u ON c.user_id  = u.id
            JOIN tracks t ON c.track_id = t.id
            WHERE 1
        ";
        if ($q !== '') {
            $sql .= " AND (
                c.content  LIKE :q
                OR u.username LIKE :q
                OR t.title    LIKE :q
            )";
        }

        $stmt = $this->pdo->prepare($sql);
        if ($q !== '') {
            $stmt->bindValue('q', "%{$q}%", PDO::PARAM_STR);
        }
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    /**
     * Отримати коментарі з пошуком та пагінацією
     *
     * @param int $limit Кількість записів на сторінку
     * @param int $offset Зміщення (offset)
     * @param string $q Пошуковий рядок
     * @return array Список фільтрованих коментарів
     */
    public function getFiltered(int $limit, int $offset, string $q = ''): array
    {
        $sql = "
            SELECT
              c.id,
              c.content,
              c.created_at,
              u.username AS user_name,
              t.title    AS track_title
            FROM {$this->table} c
            JOIN users  u ON c.user_id  = u.id
            JOIN tracks t ON c.track_id = t.id
            WHERE 1
        ";

        if ($q !== '') {
            $sql .= " AND (
                c.content  LIKE :q
                OR u.username LIKE :q
                OR t.title    LIKE :q
            )";
        }

        $sql .= "
            ORDER BY c.created_at DESC
            LIMIT :limit OFFSET :offset
        ";

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
