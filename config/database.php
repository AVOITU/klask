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
        $dbname = $_ENV['DB_NAME']     ?? '';
        $user   = $_ENV['DB_USER']     ?? '';
        $pass   = $_ENV['DB_PASSWORD'] ?? '';

        try {
            $pdo = new PDO(
                "mysql:host=$host;dbname=$dbname;charset=utf8",
                $user,
                $pass,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        } catch (Throwable $e) {
            Logger::error('DB connection failed', [
                'dsn' => $_ENV['DB_DSN'] ?? null,
                'user' => $_ENV['DB_USER'] ?? null,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
    return $pdo;
}