<?php
/**
 * EduGenius AI — Database Initialisation
 * Creates all required MySQL tables (idempotent – safe to run on every request).
 */

if (!defined('DB_HOST')) {
    require_once __DIR__ . '/../config.php';
}

function getDbConnection(): PDO {
    static $pdo = null;
    if ($pdo !== null) return $pdo;

    $dsn = sprintf(
        'mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
        DB_HOST, DB_PORT, DB_NAME
    );
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    return $pdo;
}

function initDatabase(): void {
    $pdo = getDbConnection();

    // ── History table ──────────────────────────────────────────────────────
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `history` (
            `id`         VARCHAR(36)  NOT NULL,
            `user_id`    VARCHAR(128) NOT NULL,
            `tool`       VARCHAR(100) NOT NULL DEFAULT '',
            `subject`    VARCHAR(100) NOT NULL DEFAULT '',
            `page`       VARCHAR(50)  NOT NULL DEFAULT 'index',
            `summary`    TEXT         NOT NULL,
            `messages`   LONGTEXT     NOT NULL,
            `created_at` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP
                                      ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            INDEX `idx_user_id`      (`user_id`),
            INDEX `idx_user_updated` (`user_id`, `updated_at`),
            INDEX `idx_user_tool`    (`user_id`, `tool`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
}

try {
    initDatabase();
} catch (PDOException $e) {
    // Log silently – don't expose DB errors to the browser
    error_log('EduGenius DB init error: ' . $e->getMessage());
}
