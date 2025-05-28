<?php

namespace App\Models;

use Core\Model;
use PDO;

class User extends Model
{
    protected string $table = 'users';

    /**
     * Отримати всіх користувачів.
     *
     * @return array Масив користувачів.
     */
    public function all(): array
    {
        $stmt = $this->pdo->query("SELECT id, username, email, role, avatar, created_at FROM {$this->table}");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Знайти користувача за ID.
     *
     * @param int $id Ідентифікатор користувача.
     * @return array|null Дані користувача або null, якщо не знайдений.
     */
    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Знайти користувача за email.
     *
     * @param string $email Адреса електронної пошти.
     * @return array|null Дані користувача або null, якщо не знайдений.
     */
    public function findByEmail(string $email): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Знайти користувача за ID (дублює find()).
     *
     * @param int $id Ідентифікатор користувача.
     * @return array|null Дані користувача або null, якщо не знайдений.
     */
    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Створити нового користувача.
     *
     * @param array $data Дані користувача для створення.
     * @return bool Повертає true, якщо користувач успішно створений, або false у разі помилки.
     */
    public function create(array $data): bool
    {
        $sql = "INSERT INTO {$this->table}
                (username,email,password,role,avatar)
                VALUES(:username,:email,:password,:role,:avatar)";
        return $this->pdo->prepare($sql)->execute([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => $data['role'] ?? 'user',
            'avatar' => $data['avatar'] ?? null,
        ]);
    }

    /**
     * Оновити дані користувача.
     *
     * @param int $id Ідентифікатор користувача.
     * @param array $data Дані користувача для оновлення.
     * @return bool Повертає true, якщо дані успішно оновлені, або false у разі помилки.
     */
    public function update(int $id, array $data): bool
    {
        $fields = [];
        $params = ['id' => $id];
        if (isset($data['username'])) {
            $fields[] = 'username = :username';
            $params['username'] = $data['username'];
        }
        if (isset($data['email'])) {
            $fields[] = 'email = :email';
            $params['email'] = $data['email'];
        }
        if (isset($data['role'])) {
            $fields[] = 'role = :role';
            $params['role'] = $data['role'];
        }
        if (isset($data['password'])) {
            $fields[] = 'password = :password';
            $params['password'] = $data['password'];
        }
        if (isset($data['avatar'])) {
            $fields[] = 'avatar = :avatar';
            $params['avatar'] = $data['avatar'];
        }
        if (empty($fields)) {
            return false;
        }
        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Видалити користувача за ID.
     *
     * @param int $id Ідентифікатор користувача.
     * @return bool Повертає true, якщо користувач успішно видалений, або false у разі помилки.
     */
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Порахувати кількість користувачів, що відповідають умовам пошуку.
     *
     * @param string $q Пошуковий запит.
     * @return int Кількість користувачів.
     */
    public function countFiltered(string $q = ''): int
    {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE 1";
        $params = [];

        if ($q !== '') {
            $sql .= " AND (username LIKE :q OR email LIKE :q)";
            $params['q'] = "%{$q}%";
        }

        $stmt = $this->pdo->prepare($sql);
        if ($q !== '') {
            $stmt->bindValue('q', $params['q'], PDO::PARAM_STR);
        }
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    /**
     * Отримати перелік користувачів із пагінацією та пошуком.
     *
     * @param int $limit Кількість користувачів на сторінці.
     * @param int $offset Зсув для пагінації.
     * @param string $q Пошуковий запит.
     * @return array Масив користувачів.
     */
    public function getFiltered(int $limit, int $offset, string $q = ''): array
    {
        $sql = "SELECT id, username, email, role, avatar, created_at
                FROM {$this->table}
                WHERE 1";
        $params = [];

        if ($q !== '') {
            $sql .= " AND (username LIKE :q OR email LIKE :q)";
            $params['q'] = "%{$q}%";
        }

        $sql .= " ORDER BY created_at DESC
                  LIMIT :limit OFFSET :offset";

        $stmt = $this->pdo->prepare($sql);
        if ($q !== '') {
            $stmt->bindValue('q', $params['q'], PDO::PARAM_STR);
        }
        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue('offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
