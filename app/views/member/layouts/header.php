<?php
$theme = $_SESSION['theme'] ?? 'light';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title><?php echo e($title ?? 'Member Panel'); ?> - <?php echo e($globalSettings['ngo_name'] ?? 'Member Panel'); ?></title>
    <?php if(!empty($globalSettings['ngo_favicon'])): ?>
        <link rel="icon" href="<?php echo file_url($globalSettings['ngo_favicon']); ?>" type="image/x-icon"/>
    <?php endif; ?>
    <meta name="csrf-token" content="<?php echo csrf_token('_default'); ?>">
    <link href="<?php echo url('assets/css/tabler.min.css'); ?>" rel="stylesheet"/>
    <style>
        @import url('https://rsms.me/inter/inter.css');
        :root { --tblr-font-sans-serif: 'Inter var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; }
        body { font-feature-settings: "cv03", "cv04", "cv11"; }
        .container-xl, .container-lg, .container-md, .container-sm, .container {
            padding-left: 0.75rem !important;
            padding-right: 0.75rem !important;
            max-width: 98% !important;
        }
        .navbar-vertical .nav-link { font-size: 0.95rem; padding: 0.75rem 1rem; }
        .navbar-vertical .nav-link-title { font-weight: 500; letter-spacing: 0.01em; }
        .navbar-vertical .nav-link-icon .icon { width: 1.25rem; height: 1.25rem; }
        .navbar-vertical .navbar-brand-image { height: 2.5rem; }
        .page-wrapper { min-height: 100vh; }
        @media (max-width: 991.98px) {
            .sidebar-overlay { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.4); z-index: 28; }
            .sidebar-overlay.active { display: block; }
            #member-sidebar { position: fixed !important; top: 0; left: -280px; height: 100vh; z-index: 29; transition: left 0.3s ease; width: 260px !important; }
            #member-sidebar.active { left: 0; }
            #member-sidebar .container-fluid { height: 100%; overflow-y: auto; }
        }
        body.sidebar-collapsed .navbar-vertical { overflow-x: hidden; }
        body.sidebar-collapsed .navbar-vertical .nav-link-title,
        body.sidebar-collapsed .navbar-vertical .navbar-brand span,
        body.sidebar-collapsed .navbar-vertical .dropdown-toggle:after { display: none !important; }
        body.sidebar-collapsed .navbar-vertical .navbar-brand { justify-content: center; padding: 0.75rem 0; }
        body.sidebar-collapsed .navbar-vertical .nav-link { justify-content: center; padding: 0.75rem 0; }
        body.sidebar-collapsed .navbar-vertical .nav-link-icon { margin-right: 0 !important; }
        @media (min-width: 992px) {
            body.sidebar-collapsed .navbar-vertical { width: 5rem !important; }
            body.sidebar-collapsed .page-wrapper { margin-left: 5rem !important; }
        }
    </style>
</head>
<body data-bs-theme="<?php echo $theme; ?>">
<div class="page">