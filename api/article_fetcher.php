<?php

const MAX_ARTICLE_DOWNLOAD_BYTES = 2 * 1024 * 1024;
const MAX_ARTICLE_CONTENT_LENGTH = 12000;

function extractFirstHttpUrl(string $text): ?string
{
    if (preg_match('~https?://[^\s<>"\'{}|\\\\^`]+~i', $text, $matches)) {
        return $matches[0];
    }

    return null;
}

function isPublicHttpUrl(string $url): bool
{
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        return false;
    }

    $parts = parse_url($url);
    if (!is_array($parts)) {
        return false;
    }

    $scheme = strtolower((string)($parts['scheme'] ?? ''));
    $host = (string)($parts['host'] ?? '');
    if (!in_array($scheme, ['http', 'https'], true) || $host === '') {
        return false;
    }

    if (isset($parts['user']) || isset($parts['pass'])) {
        return false;
    }

    $ips = [];
    if (filter_var($host, FILTER_VALIDATE_IP)) {
        $ips[] = $host;
    } elseif (function_exists('dns_get_record')) {
        $records = @dns_get_record($host, DNS_A + DNS_AAAA);
        foreach ($records ?: [] as $record) {
            if (!empty($record['ip'])) {
                $ips[] = $record['ip'];
            }
            if (!empty($record['ipv6'])) {
                $ips[] = $record['ipv6'];
            }
        }
    } else {
        $resolved = @gethostbynamel($host);
        if (is_array($resolved)) {
            $ips = array_merge($ips, $resolved);
        }
    }

    if ($ips === []) {
        return false;
    }

    foreach (array_unique($ips) as $ip) {
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
            return false;
        }
    }

    return true;
}

function isSafeDisplayUrl(string $url): bool
{
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        return false;
    }

    $parts = parse_url($url);
    if (!is_array($parts)) {
        return false;
    }

    $scheme = strtolower((string)($parts['scheme'] ?? ''));
    $host = (string)($parts['host'] ?? '');

    if (!in_array($scheme, ['http', 'https'], true) || $host === '') {
        return false;
    }

    return !isset($parts['user']) && !isset($parts['pass']);
}

function absolutizeUrl(string $baseUrl, string $relativeUrl): ?string
{
    $relativeUrl = trim($relativeUrl);
    if ($relativeUrl === '') {
        return null;
    }

    if (preg_match('~^https?://~i', $relativeUrl)) {
        return isSafeDisplayUrl($relativeUrl) ? $relativeUrl : null;
    }

    $base = parse_url($baseUrl);
    if (!is_array($base) || empty($base['scheme']) || empty($base['host'])) {
        return null;
    }

    $scheme = $base['scheme'];
    $host = $base['host'];
    $port = isset($base['port']) ? ':' . $base['port'] : '';

    if (strpos($relativeUrl, '//') === 0) {
        $resolved = $scheme . ':' . $relativeUrl;
        return isSafeDisplayUrl($resolved) ? $resolved : null;
    }

    if ($relativeUrl[0] === '/') {
        $resolved = "{$scheme}://{$host}{$port}{$relativeUrl}";
        return isSafeDisplayUrl($resolved) ? $resolved : null;
    }

    $basePath = $base['path'] ?? '/';
    $directory = preg_replace('~/[^/]*$~', '/', $basePath) ?: '/';
    $resolved = "{$scheme}://{$host}{$port}{$directory}{$relativeUrl}";

    $normalized = preg_replace('~/\./~', '/', $resolved);
    while (strpos((string)$normalized, '/../') !== false) {
        $normalized = preg_replace('~/(?!\.\.)[^/]+/\.\./~', '/', $normalized, 1);
    }

    return isSafeDisplayUrl((string)$normalized) ? (string)$normalized : null;
}

function fetchArticleDetails(string $url): ?array
{
    if (!isPublicHttpUrl($url)) {
        return null;
    }

    // Cap upstream HTML downloads to keep article fetches lightweight and avoid memory abuse.
    $maxBytes = MAX_ARTICLE_DOWNLOAD_BYTES;
    $body = '';

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_MAXREDIRS      => 5,
        CURLOPT_RETURNTRANSFER => false,
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_TIMEOUT        => 20,
        CURLOPT_USERAGENT      => 'EduGeniusBot/1.0',
        CURLOPT_HTTPHEADER     => [
            'Accept: text/html,application/xhtml+xml',
            'Accept-Language: en-US,en;q=0.9,zh-HK;q=0.8,zh;q=0.7',
        ],
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_SSL_VERIFYHOST => 2,
        CURLOPT_PROTOCOLS      => CURLPROTO_HTTP | CURLPROTO_HTTPS,
        CURLOPT_REDIR_PROTOCOLS => CURLPROTO_HTTP | CURLPROTO_HTTPS,
        CURLOPT_WRITEFUNCTION  => static function ($curl, $chunk) use (&$body, $maxBytes) {
            $body .= $chunk;
            return strlen($body) > $maxBytes ? 0 : strlen($chunk);
        },
    ]);

    $ok = curl_exec($ch);
    $error = curl_errno($ch);
    $httpCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $contentType = (string)curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    $effectiveUrl = (string)curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
    curl_close($ch);

    if ($ok === false || $error !== 0 || $httpCode >= 400 || $body === '') {
        return null;
    }

    if (!preg_match('~^(text/html|application/xhtml\+xml)~i', $contentType)) {
        return null;
    }

    if ($effectiveUrl === '' || !isPublicHttpUrl($effectiveUrl)) {
        return null;
    }

    $article = extractArticleDataFromHtml($body, $effectiveUrl);
    if ($article === null) {
        return null;
    }

    $article['source_url'] = $effectiveUrl;
    return $article;
}

function extractArticleDataFromHtml(string $html, string $baseUrl): ?array
{
    if (trim($html) === '') {
        return null;
    }

    $previousLibxml = libxml_use_internal_errors(true);
    $dom = new DOMDocument();
    $loaded = @$dom->loadHTML($html, LIBXML_NOERROR | LIBXML_NOWARNING | LIBXML_NONET);
    libxml_clear_errors();
    libxml_use_internal_errors($previousLibxml);

    if (!$loaded) {
        return null;
    }

    $xpath = new DOMXPath($dom);
    $title = firstNonEmpty([
        metaContent($xpath, 'property', 'og:title'),
        metaContent($xpath, 'name', 'twitter:title'),
        trim((string)$xpath->evaluate('string(//title[1])')),
        trim((string)$xpath->evaluate('string(//h1[1])')),
    ]);

    $imageUrl = firstNonEmpty([
        absolutizeUrl($baseUrl, metaContent($xpath, 'property', 'og:image') ?? ''),
        absolutizeUrl($baseUrl, metaContent($xpath, 'name', 'twitter:image') ?? ''),
        firstCandidateImageUrl($xpath, $baseUrl),
    ]);

    $content = extractArticleText($xpath);
    if ($content === '') {
        return null;
    }

    return [
        'title' => $title ?: 'Source Article',
        'image_url' => $imageUrl ?: '',
        'content' => $content,
    ];
}

function extractArticleText(DOMXPath $xpath): string
{
    $candidateQueries = [
        '//article',
        '//*[@itemprop="articleBody"]',
        '//*[contains(translate(@class,"ABCDEFGHIJKLMNOPQRSTUVWXYZ","abcdefghijklmnopqrstuvwxyz"),"article-body")]',
        '//*[contains(translate(@class,"ABCDEFGHIJKLMNOPQRSTUVWXYZ","abcdefghijklmnopqrstuvwxyz"),"article-content")]',
        '//*[contains(translate(@class,"ABCDEFGHIJKLMNOPQRSTUVWXYZ","abcdefghijklmnopqrstuvwxyz"),"story-body")]',
        '//*[contains(translate(@class,"ABCDEFGHIJKLMNOPQRSTUVWXYZ","abcdefghijklmnopqrstuvwxyz"),"post-content")]',
        '//*[contains(translate(@id,"ABCDEFGHIJKLMNOPQRSTUVWXYZ","abcdefghijklmnopqrstuvwxyz"),"article-body")]',
        '//main',
    ];

    $bestText = '';
    foreach ($candidateQueries as $query) {
        foreach ($xpath->query($query) ?: [] as $node) {
            $text = collectParagraphText($xpath, $node);
            if (mb_strlen($text) > mb_strlen($bestText)) {
                $bestText = $text;
            }
        }
        if (mb_strlen($bestText) >= 400) {
            break;
        }
    }

    if (mb_strlen($bestText) < 200) {
        $paragraphs = [];
        foreach ($xpath->query('//p') ?: [] as $paragraph) {
            $text = normalizeText($paragraph->textContent ?? '');
            if (mb_strlen($text) >= 60) {
                $paragraphs[] = $text;
            }
        }
        $bestText = implode("\n\n", array_slice($paragraphs, 0, 25));
    }

    // Limit the extracted article body so the chat UI stays usable and AI prompts remain within token budget.
    return mb_substr(trim($bestText), 0, MAX_ARTICLE_CONTENT_LENGTH);
}

function collectParagraphText(DOMXPath $xpath, DOMNode $node): string
{
    $paragraphs = [];
    foreach ($xpath->query('.//p', $node) ?: [] as $paragraph) {
        $text = normalizeText($paragraph->textContent ?? '');
        if (mb_strlen($text) >= 40) {
            $paragraphs[] = $text;
        }
    }

    if ($paragraphs === []) {
        return normalizeText($node->textContent ?? '');
    }

    return implode("\n\n", $paragraphs);
}

function firstCandidateImageUrl(DOMXPath $xpath, string $baseUrl): ?string
{
    $queries = [
        '//article//img[@src]',
        '//*[@itemprop="articleBody"]//img[@src]',
        '//main//img[@src]',
        '//img[@src]',
    ];

    foreach ($queries as $query) {
        foreach ($xpath->query($query) ?: [] as $img) {
            $src = trim((string)$img->getAttribute('src'));
            $resolved = absolutizeUrl($baseUrl, $src);
            if ($resolved !== null) {
                return $resolved;
            }
        }
    }

    return null;
}

function metaContent(DOMXPath $xpath, string $attribute, string $value): ?string
{
    if (!in_array($attribute, ['property', 'name'], true)) {
        return null;
    }

    $query = sprintf('string(//meta[@%s=%s][1]/@content)', $attribute, xpathLiteral($value));
    $content = trim((string)$xpath->evaluate($query));
    return $content !== '' ? $content : null;
}

function normalizeText(string $text): string
{
    $decoded = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $decoded = preg_replace("/\r\n?/", "\n", $decoded);
    $decoded = preg_replace("/[ \t]+/u", ' ', (string)$decoded);
    $decoded = preg_replace("/\n{3,}/u", "\n\n", (string)$decoded);
    return trim((string)$decoded);
}

function firstNonEmpty(array $values): ?string
{
    foreach ($values as $value) {
        if (is_string($value) && trim($value) !== '') {
            return trim($value);
        }
    }

    return null;
}

function xpathLiteral(string $value): string
{
    if (strpos($value, "'") === false) {
        return "'" . $value . "'";
    }

    if (strpos($value, '"') === false) {
        return '"' . $value . '"';
    }

    $parts = explode("'", $value);
    return "concat('" . implode("', \"'\", '", $parts) . "')";
}
