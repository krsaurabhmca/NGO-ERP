<?php

$envFile = __DIR__ . '/../.env';
if (!file_exists($envFile)) {
    $envFile = __DIR__ . '/../../.env';
}
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $value = trim($value);
            // Strip surrounding quotes: "value" or 'value'
            if (strlen($value) >= 2) {
                $first = $value[0]; $last = $value[strlen($value) - 1];
                if (($first === '"' && $last === '"') || ($first === "'" && $last === "'")) {
                    $value = substr($value, 1, -1);
                }
            }
            $_ENV[trim($key)] = $value;
        }
    }
}

define('DB_HOST', $_ENV['DB_HOST'] ?? 'localhost');
define('DB_USER', $_ENV['DB_USER'] ?? 'root');
define('DB_PASS', $_ENV['DB_PASS'] ?? 'ServBay.dev');
define('DB_NAME', $_ENV['DB_NAME'] ?? 'ngo');
define('APP_KEY', $_ENV['APP_KEY'] ?? '');
