<?php

// Start session
session_start();

// Check if application is installed
if (!file_exists(__DIR__ . '/../.env') && file_exists(__DIR__ . '/../install.php')) {
    header('Location: ../install.php');
    exit;
}

// Error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

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

// Load configuration
require_once '../config/config.php';

security_headers();

// Simple Autoloader
spl_autoload_register(function ($class) {
    $parts = explode('\\', $class);
    $className = array_pop($parts);
    $nsPath = strtolower(implode('/', $parts));
    $file = '../' . ($nsPath ? $nsPath . '/' : '') . $className . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Normalize route parameter if not set via rewrite query string
if (!isset($_GET['url']) || $_GET['url'] === '') {
    $basePath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
    if ($basePath === '/') $basePath = '';
    if (substr($basePath, -7) === '/public') {
        $basePath = substr($basePath, 0, -7);
    }
    $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    $uriPath = urldecode($uri);
    if ($basePath !== '' && strpos($uriPath, $basePath) === 0) {
        $urlPathOnly = substr($uriPath, strlen($basePath));
    } else {
        $urlPathOnly = $uriPath;
    }
    $_GET['url'] = ltrim(str_replace(['/public/index.php', '/index.php'], '', $urlPathOnly), '/');
}

// Load routes
$router = new App\Core\Router();
require_once '../routes/web.php';

// Resolve route
$router->resolve();
