<?php
namespace Core;

use PDO;
use PDOException;

class Database
{
    private static ?Database $instance = null;
    private PDO $connection;

    private function __construct()
    {
        $c = require __DIR__ . '/../config/database.php';
        $dsn = "{$c['driver']}:host={$c['host']};dbname={$c['dbname']};charset={$c['charset']}";
        $this->connection = new PDO($dsn, $c['username'], $c['password']);
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }
}
