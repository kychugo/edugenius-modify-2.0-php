<?php
/**
 * EduGenius AI — Firebase ID-Token Verification
 *
 * Verifies a Firebase JWT and returns the user UID, or false on failure.
 */

if (!defined('FIREBASE_PROJECT_ID')) {
    require_once __DIR__ . '/../config.php';
}

/**
 * Fetch (and cache in-process) Firebase public keys.
 * Keys are cached in /tmp with an expiry derived from the Cache-Control header.
 */
function getFirebasePublicKeys(): array {
    $cacheFile   = sys_get_temp_dir() . '/firebase_pubkeys.json';
    $cacheExpiry = sys_get_temp_dir() . '/firebase_pubkeys_expiry.txt';

    if (
        file_exists($cacheFile) &&
        file_exists($cacheExpiry) &&
        (int)file_get_contents($cacheExpiry) > time()
    ) {
        return json_decode(file_get_contents($cacheFile), true) ?: [];
    }

    $url     = 'https://www.googleapis.com/robot/v1/metadata/x509/securetoken@system.gserviceaccount.com';
    $context = stream_context_create(['http' => ['timeout' => 5]]);
    $body    = @file_get_contents($url, false, $context);
    if ($body === false) return [];

    // Extract max-age from Cache-Control header
    $maxAge = 3600;
    foreach ($http_response_header as $h) {
        if (stripos($h, 'Cache-Control:') !== false &&
            preg_match('/max-age=(\d+)/i', $h, $m)) {
            $maxAge = (int)$m[1];
            break;
        }
    }

    $keys = json_decode($body, true) ?: [];
    file_put_contents($cacheFile,   $body);
    file_put_contents($cacheExpiry, time() + $maxAge);
    // Restrict cache files to the current user only
    @chmod($cacheFile,   0600);
    @chmod($cacheExpiry, 0600);
    return $keys;
}

/**
 * Decode a base64url string.
 */
function base64UrlDecode(string $input): string {
    $remainder = strlen($input) % 4;
    if ($remainder) $input .= str_repeat('=', 4 - $remainder);
    return base64_decode(strtr($input, '-_', '+/'));
}

/**
 * Verify a Firebase ID token.
 *
 * @param string $idToken  The raw JWT from the client.
 * @return string|false    The user UID on success, false on failure.
 */
function verifyFirebaseToken(string $idToken) {
    $parts = explode('.', $idToken);
    if (count($parts) !== 3) return false;

    [$headerB64, $payloadB64, $sigB64] = $parts;

    $header  = json_decode(base64UrlDecode($headerB64),  true);
    $payload = json_decode(base64UrlDecode($payloadB64), true);

    if (!$header || !$payload) return false;

    // Basic claims validation
    $now       = time();
    $projectId = FIREBASE_PROJECT_ID;
    if (
        ($payload['aud']  ?? '') !== $projectId ||
        ($payload['iss']  ?? '') !== "https://securetoken.google.com/{$projectId}" ||
        ($payload['exp']  ?? 0)  <= $now ||
        ($payload['iat']  ?? 0)  >  $now + 300 ||
        empty($payload['sub'])
    ) {
        return false;
    }

    // Signature verification
    $keys  = getFirebasePublicKeys();
    $kid   = $header['kid'] ?? '';
    if (!isset($keys[$kid])) return false;

    $pubKey = openssl_get_publickey($keys[$kid]);
    if (!$pubKey) return false;

    $data = "{$headerB64}.{$payloadB64}";
    $sig  = base64UrlDecode($sigB64);
    $ok   = openssl_verify($data, $sig, $pubKey, OPENSSL_ALGO_SHA256);

    return $ok === 1 ? (string)$payload['sub'] : false;
}

/**
 * Extract and verify the Firebase token from the Authorization header.
 * Sends a 401 response and exits if invalid.
 *
 * @return string Verified user UID.
 */
function requireAuth(): string {
    // Try all sources where the Authorization header may appear, depending on
    // the PHP SAPI and server configuration (mod_php, CGI, FastCGI, etc.).
    $authHeader = $_SERVER['HTTP_AUTHORIZATION']
               ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION']
               ?? $_SERVER['AUTHORIZATION']
               ?? '';

    // Last-resort fallback: apache_request_headers() works when mod_php is
    // loaded even if the header was not copied into $_SERVER.
    if ($authHeader === '' && function_exists('apache_request_headers')) {
        $headers = apache_request_headers();
        $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';
    }

    if (!str_starts_with($authHeader, 'Bearer ')) {
        http_response_code(401);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Missing or invalid Authorization header']);
        exit;
    }
    $token = substr($authHeader, 7);
    $uid   = verifyFirebaseToken($token);
    if ($uid === false) {
        http_response_code(401);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid or expired Firebase token']);
        exit;
    }
    return $uid;
}
