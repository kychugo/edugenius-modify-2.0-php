<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/auth_helper.php';
require_once __DIR__ . '/article_fetcher.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Firebase-Token');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$uid = requireAuth();

$body = json_decode(file_get_contents('php://input'), true);
if (!is_array($body)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON body']);
    exit;
}

$url = trim((string)($body['url'] ?? ''));
if ($url === '') {
    http_response_code(400);
    echo json_encode(['error' => 'URL is required']);
    exit;
}

$article = fetchArticleDetails($url);
if ($article === null) {
    http_response_code(422);
    echo json_encode(['error' => 'Unable to fetch article content']);
    exit;
}

echo json_encode(['article' => $article]);
