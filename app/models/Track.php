<?php

namespace App\Models;

use Core\Model;
use PDO;

class Track extends Model
{
    protected string $table = 'tracks';

    /**
     * Отримати всі треки
     *
     * @return array<int,array>
     */
    public function all(): array
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Знайти трек за ID
     *
     * @param int $id
     * @return array<string,mixed>|null
     */
    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Створити новий трек та зв’язки з артистами і жанрами
     *
     * @param array<string,mixed> $data
     * @return bool
     */
    public function create(array $data): bool
    {
        $sql = "INSERT INTO {$this->table}
                 (title, description, file_path, cover_image, uploaded_by)
                 VALUES (:title, :description, :file_path, :cover_image, :uploaded_by)";
        $stmt = $this->pdo->prepare($sql);
        $ok = $stmt->execute([
            'title' => $data['title'],
            'description' => $data['description'] ?: null,
            'file_path' => $data['file_path'],
            'cover_image' => $data['cover_image'] ?? null,
            'uploaded_by' => $data['uploaded_by'],
        ]);

        if (!$ok) {
            return false;
        }

        $trackId = (int) $this->pdo->lastInsertId();
        $this->syncArtists($trackId, $data['artist_ids'] ?? []);
        $this->syncGenres($trackId, $data['genre_ids'] ?? []);
        return true;
    }

    /**
     * Оновити трек і його зв’язки
     *
     * @param int $id
     * @param array<string,mixed> $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        $fields = [];
        $params = ['id' => $id];

        foreach (['title', 'description', 'file_path', 'cover_image'] as $f) {
            if (isset($data[$f])) {
                $fields[] = "$f = :$f";
                $params[$f] = $data[$f];
            }
        }

        if (!empty($fields)) {
            $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = :id";
            $this->pdo->prepare($sql)->execute($params);
        }

        $this->syncArtists($id, $data['artist_ids'] ?? []);
        $this->syncGenres($id, $data['genre_ids'] ?? []);
        return true;
    }

    /**
     * Видалити трек
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Прив’язати артистів до треку
     *
     * @param int $trackId
     * @param array<int> $artistIds
     * @return void
     */
    protected function syncArtists(int $trackId, array $artistIds): void
    {
        $this->pdo->prepare("DELETE FROM track_artist WHERE track_id = ?")->execute([$trackId]);

        $stmt = $this->pdo->prepare("INSERT INTO track_artist (track_id, artist_id) VALUES (?, ?)");
        foreach (array_unique($artistIds) as $aid) {
            $stmt->execute([$trackId, (int) $aid]);
        }
    }

    /**
     * Прив’язати жанри до треку
     *
     * @param int $trackId
     * @param array<int> $genreIds
     * @return void
     */
    protected function syncGenres(int $trackId, array $genreIds): void
    {
        $this->pdo->prepare("DELETE FROM track_genre WHERE track_id = ?")->execute([$trackId]);

        $stmt = $this->pdo->prepare("INSERT INTO track_genre (track_id, genre_id) VALUES (?, ?)");
        foreach (array_unique($genreIds) as $gid) {
            $stmt->execute([$trackId, (int) $gid]);
        }
    }

    /**
     * Отримати артистів треку
     *
     * @param int $trackId
     * @return array<int,array>
     */
    public function getArtists(int $trackId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT a.id, a.name
            FROM track_artist ta
            JOIN artists a ON a.id = ta.artist_id
            WHERE ta.track_id = ?
        ");
        $stmt->execute([$trackId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Отримати жанри треку
     *
     * @param int $trackId
     * @return array<int,array>
     */
    public function getGenres(int $trackId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT g.id, g.name
            FROM track_genre tg
            JOIN genres g ON g.id = tg.genre_id
            WHERE tg.track_id = ?
        ");
        $stmt->execute([$trackId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Пошук треків за назвою або іменем артиста
     *
     * @param string $query
     * @return array<int,array>
     */
    public function search(string $query): array
    {
        $sql = "
            SELECT DISTINCT t.*
            FROM {$this->table} t
            LEFT JOIN track_artist ta ON ta.track_id = t.id
            LEFT JOIN artists a ON a.id = ta.artist_id
            WHERE t.title LIKE :q OR a.name LIKE :q
            ORDER BY t.created_at DESC
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['q' => '%' . $query . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Порахувати кількість треків за фільтрами
     *
     * @param string $q
     * @param int $genreId
     * @return int
     */
    public function countFiltered(string $q = '', int $genreId = 0): int
    {
        $sql = "
    SELECT COUNT(DISTINCT t.id)
    FROM {$this->table} t
    LEFT JOIN track_artist ta ON ta.track_id = t.id
    LEFT JOIN artists a ON a.id = ta.artist_id
    LEFT JOIN track_genre tg ON tg.track_id = t.id
    WHERE t.status = 'approved'
";

        $params = [];
        if ($q !== '') {
            $sql .= " AND (t.title LIKE :q OR a.name LIKE :q)";
            $params['q'] = "%{$q}%";
        }
        if ($genreId > 0) {
            $sql .= " AND tg.genre_id = :gid";
            $params['gid'] = $genreId;
        }

        $stmt = $this->pdo->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v, PDO::PARAM_STR);
        }
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    /**
     * Отримати треки з фільтрацією та пагінацією
     *
     * @param int $limit
     * @param int $offset
     * @param string $q
     * @param int $genreId
     * @return array<int,array>
     */
    public function getFiltered(int $limit, int $offset, string $q = '', int $genreId = 0): array
    {
        $sql = "
    SELECT
      t.id, t.title, t.cover_image, t.created_at,
      GROUP_CONCAT(DISTINCT a.name SEPARATOR ', ') AS artist_list,
      GROUP_CONCAT(DISTINCT g.name SEPARATOR ', ') AS genre_list
    FROM {$this->table} t
    LEFT JOIN track_artist ta ON ta.track_id = t.id
    LEFT JOIN artists a ON a.id = ta.artist_id
    LEFT JOIN track_genre tg ON tg.track_id = t.id
    LEFT JOIN genres g ON g.id = tg.genre_id
    WHERE t.status = 'approved'
";

        $params = [];
        if ($q !== '') {
            $sql .= " AND (t.title LIKE :q OR a.name LIKE :q)";
            $params['q'] = "%{$q}%";
        }
        if ($genreId > 0) {
            $sql .= " AND tg.genre_id = :gid";
            $params['gid'] = $genreId;
        }

        $sql .= "
            GROUP BY t.id
            ORDER BY t.created_at DESC
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $this->pdo->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v, PDO::PARAM_STR);
        }
        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue('offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Повернути тільки затверджені треки
     *
     * @return array<int,array>
     */
    public function allWithArtistGenre(): array
    {
        $tracks = $this->pdo
            ->prepare("SELECT * FROM {$this->table} WHERE status = 'approved' ORDER BY created_at DESC");
        $tracks->execute();
        $list = $tracks->fetchAll(PDO::FETCH_ASSOC);

        // Додаємо artist_name і genre_name, як у вас раніше
        foreach ($list as &$t) {
            $t['artist_name'] = implode(', ', array_column($this->getArtists($t['id']), 'name'));
            $t['genre_name'] = implode(', ', array_column($this->getGenres($t['id']), 'name'));
        }
        return $list;
    }

    /**
     * Створити новий трек у статусі pending
     */
    public function createPending(array $data): bool
    {
        $sql = "INSERT INTO {$this->table}
           (title, description, file_path, cover_image, uploaded_by, status)
           VALUES (:title,:description,:file_path,:cover_image,:uploaded_by,'pending')";
        $stmt = $this->pdo->prepare($sql);
        $ok = $stmt->execute([
            'title' => $data['title'],
            'description' => $data['description'],
            'file_path' => $data['file_path'],
            'cover_image' => $data['cover_image'],
            'uploaded_by' => $data['uploaded_by'],
        ]);
        if (!$ok)
            return false;

        $trackId = (int) $this->pdo->lastInsertId();
        $this->syncArtists($trackId, $data['artist_ids'] ?? []);
        $this->syncGenres($trackId, $data['genre_ids'] ?? []);
        return true;
    }

    /**
     * Повернути треки у статусі pending
     *
     * @return array<int,array>
     */
    public function getPending(): array
    {
        return $this->pdo
            ->query("SELECT t.*, u.username AS uploader
                      FROM {$this->table} t
                      JOIN users u ON t.uploaded_by = u.id
                     WHERE t.status = 'pending'
                     ORDER BY t.created_at DESC")
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Схвалити трек
     */
    public function approve(int $id): bool
    {
        $stmt = $this->pdo->prepare(
            "UPDATE {$this->table} SET status = 'approved' WHERE id = ?"
        );
        return $stmt->execute([$id]);
    }

    /**
     * Відхилити трек
     */
    public function reject(int $id): bool
    {
        $stmt = $this->pdo->prepare(
            "UPDATE {$this->table} SET status = 'rejected' WHERE id = ?"
        );
        return $stmt->execute([$id]);
    }
}
