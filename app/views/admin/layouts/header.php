<?php
// Handle theme switching
if (isset($_GET['theme'])) {
    $_SESSION['theme'] = $_GET['theme'];
    // Get current URL without the theme parameter to redirect back
    $url = strtok($_SERVER["REQUEST_URI"], '?');
    header("Location: " . $url);
    exit;
}

$theme = $_SESSION['theme'] ?? 'light';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title><?php echo e($title ?? 'Dashboard'); ?> - <?php echo e($globalSettings['ngo_name'] ?? 'Admin Panel'); ?></title>
    <!-- Favicon -->
    <?php if(!empty($globalSettings['ngo_favicon'])): ?>
        <link rel="icon" href="<?php echo file_url($globalSettings['ngo_favicon']); ?>" type="image/x-icon"/>
    <?php endif; ?>
    <meta name="csrf-token" content="<?php echo csrf_token('_default'); ?>">
    <meta name="csrf-token-generate-password" content="<?php echo csrf_token('admin/members/generate-password'); ?>">
    <!-- CSS files -->
    <link href="<?php echo url('assets/css/tabler.min.css'); ?>" rel="stylesheet"/>
    <?php
    $primaryColor = $globalSettings['theme_primary_color'] ?? '#0054a6';
    $secondaryColor = $globalSettings['theme_secondary_color'] ?? '#206bc4';
    
    if (!function_exists('hexToRgbStr')) {
        function hexToRgbStr($hex) {
            $hex = str_replace('#', '', $hex);
            if(strlen($hex) == 3) {
                $r = hexdec(substr($hex,0,1).substr($hex,0,1));
                $g = hexdec(substr($hex,1,1).substr($hex,1,1));
                $b = hexdec(substr($hex,2,1).substr($hex,2,1));
            } elseif(strlen($hex) == 6) {
                $r = hexdec(substr($hex,0,2));
                $g = hexdec(substr($hex,2,2));
                $b = hexdec(substr($hex,4,2));
            } else {
                return '0, 84, 166';
            }
            return "$r, $g, $b";
        }
    }
    $primaryRgb = hexToRgbStr($primaryColor);
    ?>
    <style>
        @import url('https://rsms.me/inter/inter.css');
        :root {
            --tblr-font-sans-serif: 'Inter var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
            --tblr-primary: <?php echo $primaryColor; ?>;
            --tblr-primary-rgb: <?php echo $primaryRgb; ?>;
        }
        body {
            font-feature-settings: "cv03", "cv04", "cv11";
            overflow-x: hidden;
            width: 100%;
        }
        /* Force no horizontal scroll on mobile */
        @media (max-width: 575.98px) {
            .page-wrapper, .page-body, .container-xl { overflow-x: hidden !important; }
            .row { margin-left: 0; margin-right: 0; }
            .col, [class*="col-"] { padding-left: 0.375rem; padding-right: 0.375rem; }
        }
        /* Fix breadcrumb scrollbar */
        .breadcrumb {
            flex-wrap: wrap !important;
            overflow-x: hidden !important;
            margin-bottom: 0 !important;
        }
        /* Reduce side padding */
        .container-xl, .container-lg, .container-md, .container-sm, .container {
            padding-left: 0.75rem !important;
            padding-right: 0.75rem !important;
            max-width: 100% !important;
        }
        /* Compact sidebar content */
        .navbar-vertical .nav-link {
            font-size: 0.835rem;
            padding: 0.32rem 0.65rem;
        }
        .navbar-vertical .nav-link-title {
            font-weight: 500;
            letter-spacing: 0.01em;
        }
        .sidebar-dropdown .dropdown-item {
            font-size: 0.785rem;
        }
        .navbar-vertical .navbar-brand-image {
            height: 1.85rem;
        }

        .page-wrapper {
            min-height: 100vh;
        }
        /* Prevent horizontal scroll on mobile */
        .page-wrapper, .page-body, .container-xl {
            overflow-x: hidden;
            max-width: 100vw;
        }
        /* Fix topbar dropdown overlapping */
        header.navbar {
            overflow: visible;
            position: relative;
            z-index: 1050;
        }
        header.navbar .navbar-nav .dropdown-menu {
            z-index: 1055;
            pointer-events: auto;
        }
        /* Responsive width utilities */
        .w-sm-auto { width: 100%; }
        @media (min-width: 576px) { .w-sm-auto { width: auto; } }
        @media (max-width: 991.98px) {
            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0; left: 0; right: 0; bottom: 0;
                background: rgba(0,0,0,0.4);
                z-index: 28;
            }
            .sidebar-overlay.active { display: block; }
            #admin-sidebar {
                position: fixed !important;
                top: 0; left: -280px;
                height: 100vh;
                z-index: 29;
                transition: left 0.3s ease;
                width: 260px !important;
            }
            #admin-sidebar.active { left: 0; }
            #admin-sidebar .container-fluid {
                height: 100%;
                overflow-y: auto;
            }
        }
        /* Collapsed Sidebar Styles */
        body.sidebar-collapsed .navbar-vertical {
            overflow-x: hidden;
        }
        body.sidebar-collapsed .navbar-vertical .nav-link-title,
        body.sidebar-collapsed .navbar-vertical .navbar-brand span,
        body.sidebar-collapsed .navbar-vertical .dropdown-toggle:after {
            display: none !important;
        }
        body.sidebar-collapsed .navbar-vertical .navbar-brand {
            justify-content: center;
            padding: 0.75rem 0;
        }
        body.sidebar-collapsed .navbar-vertical .nav-link {
            justify-content: center;
            padding: 0.75rem 0;
        }
        body.sidebar-collapsed .navbar-vertical .nav-link-icon {
            margin-right: 0 !important;
        }

        /* Desktop specific layout overrides */
        @media (min-width: 992px) {
            body.sidebar-collapsed .navbar-vertical {
                width: 5rem !important;
            }
            body.sidebar-collapsed .page-wrapper {
                margin-left: 5rem !important;
            }
        }

        /* Mobile: compact all admin pages */
        @media (max-width: 575.98px) {
            .page-header { padding-top: 0.5rem !important; padding-bottom: 0.5rem !important; }
            .page-header h2.page-title { font-size: 1.1rem !important; }
            .page-header .breadcrumb { font-size: 0.75rem; margin-bottom: 0.25rem; }
            .page-header .btn { font-size: 0.75rem; padding: 0.25rem 0.5rem; }
            .card-body { padding: 0.6rem !important; }
            .card-header { padding: 0.4rem 0.6rem !important; }
            .card-header h3.card-title { font-size: 0.85rem !important; }
            .card-footer { padding: 0.4rem 0.6rem !important; }
            .table td, .table th { padding: 0.25rem 0.3rem !important; font-size: 0.7rem; }
            .table .badge { font-size: 0.55rem; padding: 0.1em 0.3em; }
            .table .badge i { display: none; }
            .btn { font-size: 0.75rem; padding: 0.25rem 0.5rem; }
            .btn-sm { font-size: 0.65rem; padding: 0.15rem 0.35rem; }
            .form-control, .form-select { font-size: 0.8rem; padding: 0.25rem 0.4rem; }
            .form-label { font-size: 0.75rem; margin-bottom: 0.2rem; }
            .modal-dialog { margin: 0.5rem; }
            .modal-header { padding: 0.5rem 0.75rem; }
            .modal-body { padding: 0.75rem; }
            .modal-footer { padding: 0.5rem 0.75rem; }
            .modal .btn { font-size: 0.75rem; padding: 0.3rem 0.6rem; }
            .alert { padding: 0.4rem 0.6rem; font-size: 0.75rem; margin-bottom: 0.5rem; }
            .input-group { flex-wrap: wrap; }
            .input-group .btn { font-size: 0.7rem; padding: 0.25rem 0.4rem; }
            .card-table td:last-child .btn { white-space: nowrap; }
            .row.g-3 > [class*="col-"] { padding-left: 0.25rem; padding-right: 0.25rem; }
            .row.g-3 { margin-left: -0.25rem; margin-right: -0.25rem; }
            .fs-3 { font-size: 1rem !important; }
            .text-muted.small { font-size: 0.65rem !important; }
            .d-flex.align-items-center.gap-3 { gap: 0.5rem !important; }
            .rounded-3.p-2 svg.icon { width: 20px; height: 20px; }
            .input-icon .form-control { padding-left: 1.8rem !important; font-size: 0.8rem; }
            .input-icon .input-icon-addon { width: 1.8rem; font-size: 0.8rem; }
            .d-flex.gap-2.flex-wrap { gap: 0.25rem !important; }
            .btn-group.btn-group-sm .btn { font-size: 0.6rem; padding: 0.15rem 0.3rem; }
            .datagrid { gap: 0.25rem !important; }
            .datagrid-item { padding: 0.25rem !important; }
        }
    </style>
</head>
<body data-bs-theme="<?php echo $theme; ?>">
<div class="page">
