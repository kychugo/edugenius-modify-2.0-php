<?php
/**
 * EduGenius AI — AI Proxy
 * Proxies requests to Pollinations AI, adding the API key and system prompt server-side.
 * Supports SSE streaming pass-through.
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/auth_helper.php';
require_once __DIR__ . '/prompts.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$uid = requireAuth();

$body = json_decode(file_get_contents('php://input'), true);
if (!$body) {
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Invalid JSON body']);
    exit;
}

$subject     = (string)($body['subject']     ?? '');
$mode        = (string)($body['mode']        ?? '');
$model       = (string)($body['model']       ?? 'gemini-search');
$stream      = (bool)($body['stream']        ?? true);
$messages    = $body['messages']             ?? [];
$maxTokens   = min((int)($body['max_tokens'] ?? 4096), MAX_AI_TOKENS);
$temperature = (float)($body['temperature'] ?? 0);

// Sanitize model name (alphanumeric + hyphens only)
$model = preg_replace('/[^a-zA-Z0-9\-]/', '', $model);

// Build system prompt from subject + mode
$systemPrompt = getSystemPrompt($subject, $mode);

// Prepend system message if we have a prompt
$finalMessages = [];
if ($systemPrompt !== '') {
    $finalMessages[] = ['role' => 'system', 'content' => $systemPrompt];
}
// Append user messages (filtering out any system message the client might have sent)
foreach ($messages as $msg) {
    if (is_array($msg) && isset($msg['role']) && $msg['role'] !== 'system') {
        // Validate content type
        if (is_string($msg['content']) || is_array($msg['content'])) {
            $finalMessages[] = $msg;
        }
    }
}

if (empty($finalMessages)) {
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'No messages provided']);
    exit;
}

$payload = json_encode([
    'messages'    => $finalMessages,
    'model'       => $model,
    'stream'      => $stream,
    'temperature' => $temperature,
    'max_tokens'  => $maxTokens,
]);

$ch = curl_init(POLLINATIONS_API_URL);
curl_setopt_array($ch, [
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => $payload,
    CURLOPT_HTTPHEADER     => [
        'Content-Type: application/json',
        'Authorization: Bearer ' . POLLINATIONS_API_KEY,
        'Accept: text/event-stream',
    ],
    CURLOPT_RETURNTRANSFER => false,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_TIMEOUT        => 120,
    CURLOPT_SSL_VERIFYPEER => true,
]);

if ($stream) {
    // Stream SSE events through to the client
    header('Content-Type: text/event-stream');
    header('Cache-Control: no-cache');
    header('X-Accel-Buffering: no'); // Disable nginx buffering

    if (ob_get_level()) ob_end_clean();

    curl_setopt($ch, CURLOPT_WRITEFUNCTION, function ($curl, $data) {
        echo $data;
        if (ob_get_level()) ob_flush();
        flush();
        return strlen($data);
    });

    $result   = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($result === false || ($httpCode >= 400)) {
        echo 'data: ' . json_encode(['error' => 'Upstream AI error', 'code' => $httpCode]) . "\n\n";
        flush();
    }
} else {
    // Non-streaming: collect and return
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    header('Content-Type: application/json');
    if ($response === false || $httpCode >= 400) {
        http_response_code($httpCode ?: 502);
        echo json_encode(['error' => 'Upstream AI error']);
    } else {
        http_response_code($httpCode);
        echo $response;
    }
}
