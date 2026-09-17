<?php

// Load .env values early (needed for BASE_URL)
(function () {
    $envFile = __DIR__ . '/../.env';
    if (!file_exists($envFile))
        return;
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0)
            continue;
        if (strpos($line, '=') !== false) {
            [$k, $v] = explode('=', $line, 2);
            $v = trim($v);
            if (strlen($v) >= 2) {
                $f = $v[0];
                $l = $v[strlen($v) - 1];
                if (($f === '"' && $l === '"') || ($f === "'" && $l === "'"))
                    $v = substr($v, 1, -1);
            }
            if (!isset($_ENV[trim($k)]))
                $_ENV[trim($k)] = $v;
        }
    }
})();

// BASE_URL: Dynamic detection matching current browser address bar (protocol, domain/host & path), fallback to APP_URL for CLI
$isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || ($_SERVER['SERVER_PORT'] ?? 0) == 443
    || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');
$protocol = $isSecure ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'] ?? '';

if (!empty($host)) {
    $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
    $requestUri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    $path = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');
    if (strpos($scriptName, '/public/index.php') !== false && strpos($requestUri, $path) !== 0) {
        $path = rtrim(preg_replace('#/public$#', '', $path), '/');
    }
    if (substr($path, -7) === '/public') {
        $path = substr($path, 0, -7);
    }
    $base_url = $protocol . $host . ($path !== '' ? $path : '') . '/';
} elseif (!empty($_ENV['APP_URL'])) {
    $base_url = rtrim($_ENV['APP_URL'], '/') . '/';
} else {
    $base_url = 'http://localhost/';
}

define('BASE_URL', $base_url);
define('BASE_PATH', __DIR__ . '/../');
define('PUBLIC_PATH', BASE_PATH . 'public/');
define('STORAGE_PATH', BASE_PATH . 'storage/');
define('UPLOAD_PATH', STORAGE_PATH . 'uploads/');
define('APP_NAME', 'NGO Management System');
define('APP_VERSION', '1.0.5');

// Localization
date_default_timezone_set('Asia/Kolkata');
define('CURRENCY_SYMBOL', '&#8377;');
define('CURRENCY_CODE', 'INR');
define('MAX_AMOUNT', 999999999.99);

// Helper function for URLs
function url($path = '')
{
    if ($path === '')
        return BASE_URL;
    $path = ltrim($path, '/');
    // Preserve and strip query string and fragment
    $suffix = '';
    if (($pos = strcspn($path, '?#')) < strlen($path)) {
        $suffix = substr($path, $pos);
        $path = substr($path, 0, $pos);
    }
    // Encode each path segment
    $segments = explode('/', $path);
    $encoded = implode('/', array_map('rawurlencode', $segments));
    return BASE_URL . $encoded . $suffix;
}

// File upload MIME validation
function validate_upload($file, $allowedExtensions, $allowedMimes, $maxSize)
{
    if ($file['error'] !== UPLOAD_ERR_OK)
        return 'Upload error';
    if ($file['size'] > $maxSize)
        return 'File too large';

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedExtensions))
        return 'Invalid file type';

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    if (!in_array($mime, $allowedMimes))
        return 'Invalid file content';

    return true;
}

// Input sanitization helper
function input($key, $default = '')
{
    $value = $_POST[$key] ?? $_GET[$key] ?? $default;
    if (is_string($value)) {
        $value = trim($value);
        $value = stripslashes($value);
    }
    return $value;
}

// XSS protection helper
function e($str)
{
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

// CSRF Protection — per-action tokens, invalidated after use
function csrf_token($action = '_default')
{
    if (!isset($_SESSION['_csrf_tokens'][$action])) {
        $_SESSION['_csrf_tokens'][$action] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf_tokens'][$action];
}

function csrf_field($action = '_default')
{
    $token = csrf_token($action);
    return '<input type="hidden" name="_csrf_action" value="' . htmlspecialchars($action, ENT_QUOTES) . '">'
        . '<input type="hidden" name="_csrf_token" value="' . $token . '">';
}

function verify_csrf($action = '_default', $token = null)
{
    if ($token === null) {
        $token = $_POST['_csrf_token'] ?? '';
    }
    $stored = $_SESSION['_csrf_tokens'][$action] ?? null;
    if (!$stored || !hash_equals($stored, $token)) {
        return false;
    }
    // Consume token — prevent reuse
    unset($_SESSION['_csrf_tokens'][$action]);
    return true;
}

// Activity logging helper
function log_activity($action, $module, $record_id = null, $old_data = null, $new_data = null)
{
    return \App\Models\AuditLog::log($action, $module, $record_id, $old_data, $new_data);
}

// File URL helper (served through file.php for access control)
function file_url($path)
{
    if (empty($path))
        return '';
    return BASE_URL . 'file.php?f=' . ltrim($path, '/');
}

// Strip dangerous HTML tags that could enable XSS (preserves safe formatting)
function strip_dangerous_html($html)
{
    if (empty($html))
        return $html;
    $allowedTags = '<p><br><b><strong><i><em><u><a><ul><ol><li><h1><h2><h3><h4><h5><h6><blockquote><pre><code><span><div><img><table><thead><tbody><tr><th><td><hr>';
    return strip_tags($html, $allowedTags);
}

// Safe file deletion helper — prevents path traversal
function safe_unlink($path)
{
    if (empty($path))
        return false;
    $real = realpath($path);
    $allowed = realpath(UPLOAD_PATH);
    if ($real === false || $allowed === false || strpos($real, $allowed) !== 0) {
        error_log("safe_unlink: blocked deletion of '$path' (outside upload path)");
        return false;
    }
    if (file_exists($real)) {
        return unlink($real);
    }
    return false;
}

// Security headers
function security_headers()
{
    if (headers_sent())
        return;

    // Prevent caching to ensure flash messages (like toasts) always render on redirects
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    header('Cache-Control: post-check=0, pre-check=0', false);
    header('Pragma: no-cache');
    header('Expires: 0');

    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: DENY');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
    $csp = "default-src 'self';"
        . " script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://www.google.com https://www.gstatic.com https://checkout.razorpay.com https://cdn.razorpay.com;"
        . " style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://fonts.googleapis.com https://rsms.me;"
        . " img-src 'self' data: blob: https:;"
        . " font-src 'self' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://fonts.gstatic.com https://rsms.me;"
        . " connect-src 'self' https:;"
        . " frame-src https://www.google.com https://maps.google.com https://www.youtube.com https://www.youtube-nocookie.com https://youtu.be https://checkout.razorpay.com https://api.razorpay.com blob:;"
        . " object-src 'none';"
        . " base-uri 'self'";
    header("Content-Security-Policy: " . $csp);
}

// JSON response helper
function json_response($data)
{
    if (!headers_sent()) {
        header('Content-Type: application/json; charset=utf-8');
    }
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_write_close();
    }
    $json = json_encode($data);
    if ($json === false) {
        echo json_encode(['status' => 'error', 'message' => 'JSON Encoding Error: ' . json_last_error_msg()]);
    } else {
        echo $json;
    }
    exit;
}

// Signed URL Helper Functions
require_once BASE_PATH . 'app/helpers/SignedUrlHelper.php';
require_once BASE_PATH . 'app/helpers/CryptoHelper.php';

/**
 * Generate a signed URL with expiration
 * 
 * @param string $path The URL path (e.g., '/donate/receipt/uuid-here')
 * @param int $expiresInMinutes Time until URL expires (default: 60 minutes)
 * @param array $params Additional query parameters to include
 * @return string The signed URL
 */
function generate_signed_url($path, $expiresInMinutes = 60, $params = [])
{
    return \App\Helpers\SignedUrlHelper::generate($path, $expiresInMinutes, $params);
}

/**
 * Verify a signed URL
 * 
 * @param string $signature The signature from query parameter
 * @param int $expires The expiration timestamp from query parameter
 * @param string|null $path Optional path to verify (defaults to current REQUEST_URI)
 * @return array ['valid' => bool, 'error' => string|null]
 */
function verify_signed_url($url = null)
{
    return \App\Helpers\SignedUrlHelper::verify($url);
}

/**
 * Verify signed URL and exit with error if invalid
 * Convenience function for route protection
 * 
 * @param string|null $redirectUrl URL to redirect to on error (default: home)
 */
function require_valid_signature($redirectUrl = null)
{
    $result = verify_signed_url();

    if (!$result['valid']) {
        $_SESSION['error'] = $result['error'] ?? 'Invalid or expired link.';
        header('Location: ' . ($redirectUrl ?? url('/')));
        exit;
    }
}

// CAPTCHA helper functions for brute-force protection
function captcha_generate()
{
    $ops = ['+', '-'];
    $a = random_int(1, 20);
    $b = random_int(1, 20);
    $op = $ops[array_rand($ops)];

    if ($op === '-') {
        if ($a < $b) {
            list($a, $b) = [$b, $a];
        }
        $answer = $a - $b;
    } else {
        $answer = $a + $b;
    }

    $_SESSION['_captcha_answer'] = $answer;
    $_SESSION['_captcha_question'] = "$a $op $b";
    return $_SESSION['_captcha_question'];
}

function captcha_question()
{
    if (empty($_SESSION['_captcha_question'])) {
        captcha_generate();
    }
    return $_SESSION['_captcha_question'];
}

function verify_captcha($answer)
{
    if (!isset($_SESSION['_captcha_answer'])) {
        return false;
    }
    $correct = (int) $_SESSION['_captcha_answer'] === (int) $answer;
    unset($_SESSION['_captcha_answer'], $_SESSION['_captcha_question']);
    return $correct;
}
