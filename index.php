<?php

if (!file_exists(__DIR__ . '/.env') && file_exists(__DIR__ . '/install.php')) {
    header('Location: install.php');
    exit;
}

$isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || ($_SERVER['SERVER_PORT'] ?? 0) == 443
    || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');

session_set_cookie_params([
    'lifetime' => 86400,
    'path' => '/',
    'secure' => $isSecure,
    'httponly' => true,
    'samesite' => 'Strict'
]);
session_start();
if (!isset($_SESSION['_initiated'])) {
    session_regenerate_id(true);
    $_SESSION['_initiated'] = true;
}
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Log errors silently — no details shown to visitors in production.
set_error_handler(function ($severity, $msg, $file, $line) {
    if (!(error_reporting() & $severity)) return false;
    error_log("[NGO Error] $msg in $file:$line (severity=$severity)");
});
register_shutdown_function(function () {
    $e = error_get_last();
    if ($e && in_array($e['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        error_log("[NGO Fatal] {$e['message']} in {$e['file']}:{$e['line']}");
        if (!headers_sent()) {
            http_response_code(500);
            header('Content-Type: text/html; charset=utf-8');
        }
        echo '<!DOCTYPE html><html><head><title>Error</title><style>body{font-family:sans-serif;padding:2rem;background:#f8f9fa;color:#333;text-align:center;}h1{color:#dc3545;}</style></head><body><h1>Something went wrong</h1><p>Please try again later.</p></body></html>';
    }
});

$basePath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
if ($basePath === '/') $basePath = '';
if (substr($basePath, -7) === '/public') {
    $basePath = substr($basePath, 0, -7);
}

$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$uriPath = urldecode($uri);

if ($basePath !== '' && strpos($uriPath, $basePath) === 0) {
    $cleanUriPath = substr($uriPath, strlen($basePath));
} else {
    $cleanUriPath = $uriPath;
}
$cleanUriPath = '/' . ltrim($cleanUriPath, '/');

$publicFile = __DIR__ . '/public' . $cleanUriPath;
$realPublic = realpath(__DIR__ . '/public');
$realFile = realpath($publicFile);

if ($cleanUriPath !== '/' && $realFile !== false && $realPublic !== false && !is_dir($realFile)) {
    $publicPrefix = str_replace('\\', '/', $realPublic) . '/';
    $normalizedRealFile = str_replace('\\', '/', $realFile);
    if (strpos($normalizedRealFile, $publicPrefix) !== 0) {
        $realFile = false;
    }
}

if ($realFile !== false && !is_dir($realFile)) {
    $mimeTypes = [
        'css' => 'text/css',
        'js'  => 'application/javascript',
        'png' => 'image/png',
        'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg',
        'gif' => 'image/gif', 'svg' => 'image/svg+xml',
        'webp' => 'image/webp', 'ico' => 'image/x-icon',
        'pdf' => 'application/pdf',
        'ttf' => 'font/ttf', 'woff' => 'font/woff', 'woff2' => 'font/woff2',
    ];
    $ext = strtolower(pathinfo($realFile, PATHINFO_EXTENSION));
    if (isset($mimeTypes[$ext])) {
        header('Content-Type: ' . $mimeTypes[$ext]);
    }
    header('Content-Length: ' . filesize($realFile));
    readfile($realFile);
    exit;
}

$_GET['url'] = ltrim(str_replace(['/public/index.php', '/index.php'], '', $cleanUriPath), '/');
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

require_once __DIR__ . '/config/config.php';

security_headers();

spl_autoload_register(function ($class) {
    // Directories are lowercase (app/core/), class files are TitleCase (Router.php).
    // Split the namespace to handle each part separately.
    $parts = explode('\\', $class);
    $className = array_pop($parts);
    $nsPath = strtolower(implode('/', $parts));
    $file = __DIR__ . '/' . ($nsPath ? $nsPath . '/' : '') . $className . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

$router = new App\Core\Router();
require_once __DIR__ . '/routes/web.php';
$router->resolve();
