<?php

session_start();

require_once __DIR__ . '/config/config.php';

$file = $_GET['f'] ?? '';
if (empty($file)) {
    http_response_code(400);
    exit;
}

$file = rawurldecode($file);
$file = str_replace('\\', '/', $file);
if (strpos($file, '..') !== false) {
    http_response_code(404);
    exit;
}

// Strip legacy uploads/ prefix
$file = preg_replace('#^uploads/+#', '', $file);

$fullPath = UPLOAD_PATH . $file;
$realPath = realpath($fullPath);
$realStorage = realpath(UPLOAD_PATH);

// Fallback for legacy files stored in public/uploads/
if ($realPath === false || !file_exists($realPath)) {
    $legacyPath = BASE_PATH . 'public/uploads/' . ltrim($file, '/');
    if (file_exists($legacyPath)) {
        $realPath = realpath($legacyPath);
        $realStorage = realpath(BASE_PATH . 'public/uploads');
    }
}

if ($realPath === false || $realStorage === false || strpos($realPath, $realStorage) !== 0) {
    http_response_code(404);
    exit;
}

if (!file_exists($realPath) || is_dir($realPath)) {
    http_response_code(404);
    exit;
}

// Access control: private files require authentication
$privatePrefixes = ['receipts/', 'careers/resumes/', 'kyc/', 'offer_letters/'];
$requiresAuth = false;
foreach ($privatePrefixes as $prefix) {
    if (strpos($file, $prefix) === 0) {
        $requiresAuth = true;
        break;
    }
}

if ($requiresAuth) {
    $authenticated = !empty($_SESSION['user_id']) || !empty($_SESSION['member_id']);
    if (!$authenticated) {
        http_response_code(404);
        exit;
    }
    // Ownership check: admin (role_id=1) can access all; members can only access their own files
    $isAdmin = ($_SESSION['role_id'] ?? 0) == 1;
    if (!$isAdmin) {
        $memberId = $_SESSION['member_id'] ?? 0;
        // Try to verify ownership via filename pattern (member ID embedded in filename)
        $allowed = false;
        if (preg_match('/members\/(\d+)_/', $file, $m) || preg_match('/profiles\/(\d+)_/', $file, $m)) {
            $allowed = ((int)$m[1] === (int)$memberId);
        }
        if (!$allowed) {
            http_response_code(404);
            exit;
        }
    }
}

$mimeTypes = [
    'css' => 'text/css',
    'js'  => 'application/javascript',
    'png' => 'image/png',
    'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg',
    'gif' => 'image/gif', 'svg' => 'image/svg+xml',
    'webp' => 'image/webp', 'ico' => 'image/x-icon',
    'pdf' => 'application/pdf',
    'doc' => 'application/msword',
    'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
];
$ext = strtolower(pathinfo($realPath, PATHINFO_EXTENSION));
if (isset($mimeTypes[$ext])) {
    header('Content-Type: ' . $mimeTypes[$ext]);
}

header('Content-Length: ' . filesize($realPath));
header('X-Content-Type-Options: nosniff');
readfile($realPath);
exit;
