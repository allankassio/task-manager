<?php
namespace Allan\TaskManager;

use PDO;

class Database {
    public static function getConnection(): PDO {
        $host = $_ENV['DB_HOST'] ?? 'db';
        $db   = $_ENV['DB_NAME'] ?? 'taskmanager';
        $user = $_ENV['DB_USER'] ?? 'user';
        $pass = $_ENV['DB_PASSWORD'] ?? 'secret';

        return new PDO(
            "mysql:host=$host;dbname=$db;charset=utf8mb4",
            $user,
            $pass,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );
    }
}
