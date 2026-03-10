<?php
/**
 * EduGenius AI — Central Configuration
 * All sensitive credentials are kept here, server-side only.
 */

// ── MySQL ──────────────────────────────────────────────────────────────────
define('DB_HOST', 'sql104.infinityfree.com');
define('DB_PORT', 3306);
define('DB_USER', 'if0_39905845');
define('DB_PASS', 'Hugo0528');
define('DB_NAME', 'if0_39905845_edugenius1');

// ── Pollinations AI ────────────────────────────────────────────────────────
define('POLLINATIONS_API_KEY', 'sk_dk6t4GEymji9dVR9jPau4bB9RJYJ0JbP');
define('POLLINATIONS_API_URL', 'https://gen.pollinations.ai/v1/chat/completions');

// ── Firebase (Web SDK config – injected into HTML as JS, never hardcoded in pages) ──
define('FIREBASE_API_KEY',          'AIzaSyDuj9IBFWgQVKXuaQYjrFYkmM5JyqouTmk');
define('FIREBASE_AUTH_DOMAIN',      'public-edugenius.firebaseapp.com');
define('FIREBASE_PROJECT_ID',       'public-edugenius');
define('FIREBASE_STORAGE_BUCKET',   'public-edugenius.firebasestorage.app');
define('FIREBASE_MESSAGING_ID',     '1038536066504');
define('FIREBASE_APP_ID',           '1:1038536066504:web:9ba878e0a948c0db83acfd');
define('FIREBASE_MEASUREMENT_ID',   'G-G8GX0YKQB2');

// ── AI Proxy ───────────────────────────────────────────────────────────────
define('MAX_AI_TOKENS', 8192);

/**
 * Returns a <script> block that injects Firebase config as a global JS object.
 * Call echo firebaseConfigScript(); inside a PHP page's <head>.
 */
function firebaseConfigScript(): string {
    return '<script>window.__FIREBASE_CONFIG__=' . json_encode([
        'apiKey'            => FIREBASE_API_KEY,
        'authDomain'        => FIREBASE_AUTH_DOMAIN,
        'projectId'         => FIREBASE_PROJECT_ID,
        'storageBucket'     => FIREBASE_STORAGE_BUCKET,
        'messagingSenderId' => FIREBASE_MESSAGING_ID,
        'appId'             => FIREBASE_APP_ID,
        'measurementId'     => FIREBASE_MEASUREMENT_ID,
    ], JSON_UNESCAPED_SLASHES) . ';</script>';
}

// Auto-create tables on first run (idempotent)
require_once __DIR__ . '/api/db_init.php';
