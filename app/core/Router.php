<?php

namespace App\Core;

class Router
{
    protected $routes = [];

    public function add($method, $path, $callback)
    {
        $path = trim($path, '/');
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'callback' => $callback
        ];
    }

    public function resolve()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = $_GET['url'] ?? '';
        $path = trim($path, '/');

        array_walk_recursive($_POST, function (&$v) { if (is_string($v)) $v = trim(stripslashes($v)); });
        array_walk_recursive($_GET, function (&$v) { if (is_string($v)) $v = trim(stripslashes($v)); });

        if ($method === 'POST') {
            // Parse JSON body for AJAX requests
            $body = [];
            $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
            if (strpos($contentType, 'application/json') !== false) {
                $body = json_decode(file_get_contents('php://input'), true) ?? [];
            }

            // Skip CSRF for Razorpay AJAX endpoints (they have HMAC + session security)
            $currentPath = trim($_GET['url'] ?? '', '/');
            $csrfExempt = ['donate/razorpay-order', 'donate/razorpay-verify', 'donate/razorpay-fail'];
            if (!in_array($currentPath, $csrfExempt)) {
                $action = $_POST['_csrf_action'] ?? $body['_csrf_action'] ?? '_default';
                $token = $_POST['_csrf_token'] ?? $body['_csrf_token'] ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? '');

                // Validate and consume the per-action token
                $stored = $_SESSION['_csrf_tokens'][$action] ?? null;
                if (!$stored || !hash_equals($stored, $token)) {
                    $isAjax = (strpos($contentType, 'application/json') !== false)
                        || ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHttpRequest';
                    if ($isAjax) {
                        header('Content-Type: application/json');
                        echo json_encode(['success' => false, 'error' => 'Invalid or expired form token.']);
                        exit;
                    }
                    $_SESSION['error'] = 'Invalid or expired form token. Please try again.';
                    $referer = $_SERVER['HTTP_REFERER'] ?? '/';
                    header('Location: ' . $referer);
                    exit;
                }

                // Consume token — one-time use, prevents replay attacks
                unset($_SESSION['_csrf_tokens'][$action]);
            }
        }

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $this->matchPath($route['path'], $path)) {
                return $this->callCallback($route['callback'], $this->getParams($route['path'], $path));
            }
        }

        http_response_code(404);
        $errorFile = __DIR__ . '/../views/errors/404.php';
        if (file_exists($errorFile)) {
            require $errorFile;
        } else {
            echo "<!DOCTYPE html><html><head><title>404 - Page Not Found</title><style>body{font-family:sans-serif;text-align:center;padding:4rem;color:#666;}h1{font-size:4rem;color:#dc3545;}</style></head><body><h1>404</h1><p>Page Not Found</p></body></html>";
        }
        exit;
    }

    protected function matchPath($routePath, $requestPath)
    {
        $routeParts = explode('/', $routePath);
        $requestParts = explode('/', $requestPath);

        if (count($routeParts) !== count($requestParts)) {
            return false;
        }

        foreach ($routeParts as $key => $part) {
            if (strpos($part, '{') === 0 && strpos($part, '}') === (strlen($part) - 1)) {
                continue;
            }
            if ($part !== $requestParts[$key]) {
                return false;
            }
        }

        return true;
    }

    protected function getParams($routePath, $requestPath)
    {
        $params = [];
        $routeParts = explode('/', $routePath);
        $requestParts = explode('/', $requestPath);

        foreach ($routeParts as $key => $part) {
            if (strpos($part, '{') === 0 && strpos($part, '}') === (strlen($part) - 1)) {
                $params[] = $requestParts[$key];
            }
        }

        return $params;
    }

    protected function callCallback($callback, $params)
    {
        if (is_array($callback)) {
            $controller = new $callback[0]();
            return call_user_func_array([$controller, $callback[1]], $params);
        }

        return call_user_func_array($callback, $params);
    }
}
