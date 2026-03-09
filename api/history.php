<?php
/**
 * EduGenius AI — History API (MySQL backend)
 * Replaces Firestore history calls.
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/auth_helper.php';
require_once __DIR__ . '/db_init.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

$uid    = requireAuth();
$method = $_SERVER['REQUEST_METHOD'];

try {
    $pdo = getDbConnection();

    // ── GET ──────────────────────────────────────────────────────────────
    if ($method === 'GET') {
        $id = $_GET['id'] ?? '';

        // Single record
        if ($id !== '') {
            $stmt = $pdo->prepare(
                'SELECT * FROM `history` WHERE `id` = ? AND `user_id` = ? LIMIT 1'
            );
            $stmt->execute([$id, $uid]);
            $row = $stmt->fetch();
            if (!$row) {
                http_response_code(404);
                echo json_encode(['error' => 'Not found']);
                exit;
            }
            $row['messages'] = json_decode($row['messages'], true) ?: [];
            echo json_encode($row);
            exit;
        }

        // List records
        $limit  = min((int)($_GET['limit'] ?? 20), 100);
        $after  = $_GET['after'] ?? ''; // updated_at cursor (ISO datetime)
        $tool   = $_GET['tool']  ?? '';

        $where  = ['`user_id` = ?'];
        $params = [$uid];

        if ($after !== '') {
            $where[]  = '`updated_at` < ?';
            $params[] = $after;
        }
        if ($tool !== '') {
            $where[]  = '`tool` = ?';
            $params[] = $tool;
        }

        $whereSQL = implode(' AND ', $where);
        $params[] = $limit;

        $stmt = $pdo->prepare(
            "SELECT `id`, `tool`, `subject`, `page`, `summary`, `messages`, `created_at`, `updated_at`
               FROM `history`
              WHERE {$whereSQL}
           ORDER BY `updated_at` DESC
              LIMIT ?"
        );
        $stmt->execute($params);
        $rows = $stmt->fetchAll();

        foreach ($rows as &$r) {
            $r['messages'] = json_decode($r['messages'], true) ?: [];
        }
        unset($r);

        echo json_encode(['docs' => $rows, 'count' => count($rows)]);
        exit;
    }

    // ── POST (create) ─────────────────────────────────────────────────────
    if ($method === 'POST') {
        $body = json_decode(file_get_contents('php://input'), true) ?: [];
        // Cryptographically-random UUID v4
        $bytes = random_bytes(16);
        $bytes[6] = chr((ord($bytes[6]) & 0x0f) | 0x40); // version 4
        $bytes[8] = chr((ord($bytes[8]) & 0x3f) | 0x80); // variant bits
        $id = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($bytes), 4));

        $tool     = substr((string)($body['tool']    ?? ''), 0, 100);
        $subject  = substr((string)($body['subject'] ?? ''), 0, 100);
        $page     = substr((string)($body['page']    ?? 'index'), 0, 50);
        $summary  = substr((string)($body['summary'] ?? ''), 0, 500);
        $messages = json_encode($body['messages'] ?? []);

        $stmt = $pdo->prepare(
            'INSERT INTO `history` (`id`, `user_id`, `tool`, `subject`, `page`, `summary`, `messages`)
             VALUES (?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([$id, $uid, $tool, $subject, $page, $summary, $messages]);

        echo json_encode(['id' => $id]);
        exit;
    }

    // ── PUT (append messages or update summary) ───────────────────────────
    if ($method === 'PUT') {
        $id   = $_GET['id'] ?? '';
        $body = json_decode(file_get_contents('php://input'), true) ?: [];

        // Fetch existing record
        $stmt = $pdo->prepare(
            'SELECT `messages`, `summary` FROM `history` WHERE `id` = ? AND `user_id` = ? LIMIT 1'
        );
        $stmt->execute([$id, $uid]);
        $row = $stmt->fetch();
        if (!$row) {
            http_response_code(404);
            echo json_encode(['error' => 'Not found']);
            exit;
        }

        // If only updating summary (no messages in body)
        if (isset($body['summary']) && !isset($body['messages'])) {
            $newSummary = substr((string)$body['summary'], 0, 500);
            $stmt = $pdo->prepare(
                'UPDATE `history` SET `summary` = ?, `updated_at` = NOW()
                  WHERE `id` = ? AND `user_id` = ?'
            );
            $stmt->execute([$newSummary, $id, $uid]);
            echo json_encode(['ok' => true]);
            exit;
        }

        $existing = json_decode($row['messages'], true) ?: [];
        $new      = array_merge($existing, $body['messages'] ?? []);
        // Cap at 200 messages per session
        if (count($new) > 200) $new = array_slice($new, -200);

        $stmt = $pdo->prepare(
            'UPDATE `history` SET `messages` = ?, `updated_at` = NOW()
              WHERE `id` = ? AND `user_id` = ?'
        );
        $stmt->execute([json_encode($new), $id, $uid]);

        echo json_encode(['ok' => true]);
        exit;
    }

    // ── DELETE ────────────────────────────────────────────────────────────
    if ($method === 'DELETE') {
        $id   = $_GET['id'] ?? '';
        $stmt = $pdo->prepare(
            'DELETE FROM `history` WHERE `id` = ? AND `user_id` = ?'
        );
        $stmt->execute([$id, $uid]);
        echo json_encode(['ok' => true]);
        exit;
    }

    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);

} catch (PDOException $e) {
    error_log('EduGenius history API error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
}
