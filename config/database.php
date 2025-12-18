<?php

use Utils\Logger;

require_once __DIR__ . '/env.php';
require_once __DIR__ . '/../src/Utils/Logger.php';

load_env(__DIR__ . '/../.env');

/**
 * @throws Throwable
 */
function get_pdo(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        $host   = $_ENV['DB_HOST']     ?? '127.0.0.1';
        $port   = $_ENV['DB_PORT']     ?? '';
        $dbname = $_ENV['DB_NAME']     ?? '';
        $user   = $_ENV['DB_USER']     ?? '';
        $pass   = $_ENV['DB_PASSWORD'] ?? '';

        try {
            $pdo = new PDO(
                "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4",
                $user,
                $pass,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        } catch (Throwable $e) {
            Logger::error('DB connection failed', [
                'error' => $_ENV['APP_ENV'] === 'dev' ? $e->getMessage() : 'Database error'
            ]);
            throw $e;
        }
    }
    return $pdo;
}