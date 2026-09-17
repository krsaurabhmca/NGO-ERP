<?php
$current_url = $_SERVER['REQUEST_URI'] ?? '';
$base_url = rtrim(str_replace('index.php', '', $_SERVER['SCRIPT_NAME']), '/');
$current_path = str_replace($base_url, '', $current_url);
$current_path = '/' . ltrim(strtok($current_path, '?'), '/');

function isActive($path, $current_path, $exact = true) {
    if ($exact) {
        return $current_path === $path ? 'active' : '';
    }
    return strpos($current_path, $path) === 0 ? 'active' : '';
}

function isDropdownActive($paths, $current_path) {
    foreach ($paths as $path) {
        if (strpos($current_path, $path) === 0) {
            return 'active';
        }
    }
    return '';
}

function isDropdownShow($paths, $current_path) {
    foreach ($paths as $path) {
        if (strpos($current_path, $path) === 0) {
            return 'show';
        }
    }
    return '';
}
?>

<style>
/* --- Sidebar & Scrollbar Modern UI --- */
#admin-sidebar, #member-sidebar {
    border-right: 1px solid rgba(255, 255, 255, 0.08) !important;
}

/* Transparent Scrollbar */
#admin-sidebar, 
#member-sidebar,
#admin-sidebar .navbar-collapse,
#member-sidebar .navbar-collapse,
#admin-sidebar .container-fluid,
#member-sidebar .container-fluid {
    scrollbar-width: thin;
    scrollbar-color: transparent transparent;
}
#admin-sidebar::-webkit-scrollbar,
#member-sidebar::-webkit-scrollbar,
#admin-sidebar *::-webkit-scrollbar,
#member-sidebar *::-webkit-scrollbar {
    width: 4px;
    height: 4px;
    background: transparent;
}
#admin-sidebar::-webkit-scrollbar-track,
#member-sidebar::-webkit-scrollbar-track,
#admin-sidebar *::-webkit-scrollbar-track,
#member-sidebar *::-webkit-scrollbar-track {
    background: transparent;
}
#admin-sidebar::-webkit-scrollbar-thumb,
#member-sidebar::-webkit-scrollbar-thumb,
#admin-sidebar *::-webkit-scrollbar-thumb,
#member-sidebar *::-webkit-scrollbar-thumb {
    background: transparent;
    border-radius: 10px;
    transition: background 0.25s ease;
}
#admin-sidebar:hover::-webkit-scrollbar-thumb,
#member-sidebar:hover::-webkit-scrollbar-thumb,
#admin-sidebar:hover *::-webkit-scrollbar-thumb,
#member-sidebar:hover *::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.2);
}

/* Compact White Background Logo Container */
.navbar-vertical .navbar-brand {
    padding: 0.35rem 0.5rem 0.2rem !important;
    margin-bottom: 0 !important;
    min-height: auto !important;
}
.sidebar-logo-card {
    background: #ffffff !important;
    padding: 4px 10px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.18), 0 0 0 1px rgba(255, 255, 255, 0.12);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    max-width: 86%;
    margin: 0.25rem auto 0.15rem auto;
}
.sidebar-logo-card:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
}
.sidebar-logo-img {
    max-height: 1.85rem;
    max-width: 135px;
    width: auto;
    object-fit: contain;
    filter: none !important;
}

/* Compact Fancy Colorful Icon Badges */
.nav-icon-badge {
    width: 25px;
    height: 25px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    margin-right: 0.5rem;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    flex-shrink: 0;
}
.nav-icon-badge svg {
    width: 14px;
    height: 14px;
    transition: transform 0.2s ease;
}
.nav-link:hover .nav-icon-badge,
.nav-item.active > .nav-link .nav-icon-badge {
    transform: scale(1.08);
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.25);
}

/* Palette Presets */
.icon-blue     { background: rgba(59, 130, 246, 0.2) !important; color: #60a5fa !important; }
.icon-emerald  { background: rgba(16, 185, 129, 0.2) !important; color: #34d399 !important; }
.icon-amber    { background: rgba(245, 158, 11, 0.2) !important; color: #fbbf24 !important; }
.icon-rose     { background: rgba(244, 63, 94, 0.2) !important; color: #fb7185 !important; }
.icon-purple   { background: rgba(139, 92, 246, 0.2) !important; color: #a78bfa !important; }
.icon-cyan     { background: rgba(6, 182, 212, 0.2) !important; color: #22d3ee !important; }
.icon-indigo   { background: rgba(99, 102, 241, 0.2) !important; color: #818cf8 !important; }
.icon-teal     { background: rgba(20, 184, 166, 0.2) !important; color: #2dd4bf !important; }
.icon-fuchsia  { background: rgba(217, 70, 239, 0.2) !important; color: #f472b6 !important; }
.icon-green    { background: rgba(34, 197, 94, 0.2) !important; color: #4ade80 !important; }
.icon-yellow   { background: rgba(234, 179, 8, 0.2) !important; color: #fde047 !important; }
.icon-orange   { background: rgba(249, 115, 22, 0.2) !important; color: #fb923c !important; }
.icon-sky      { background: rgba(14, 165, 233, 0.2) !important; color: #38bdf8 !important; }
.icon-slate    { background: rgba(148, 163, 184, 0.2) !important; color: #cbd5e1 !important; }
.icon-red      { background: rgba(239, 68, 68, 0.2) !important; color: #f87171 !important; }

/* Compact Nav Items */
.navbar-vertical .navbar-nav .nav-link {
    padding: 0.32rem 0.65rem !important;
    border-radius: 7px !important;
    margin: 1px 0.35rem !important;
    font-size: 0.835rem !important;
    line-height: 1.35 !important;
    min-height: auto !important;
    transition: all 0.15s ease !important;
}
.navbar-vertical .navbar-nav .nav-link .nav-link-title {
    font-size: 0.835rem !important;
    font-weight: 500;
}
.navbar-vertical .navbar-nav .nav-item.active > .nav-link {
    background: rgba(255, 255, 255, 0.08) !important;
    color: #ffffff !important;
    font-weight: 600 !important;
    box-shadow: inset 3px 0 0 #3b82f6;
}
.navbar-vertical .navbar-nav .nav-link:hover {
    background: rgba(255, 255, 255, 0.05) !important;
}

/* Compact Dropdown Menus */
.navbar-vertical .dropdown-menu {
    background: #141f2d !important;
    border: 1px solid rgba(255, 255, 255, 0.07) !important;
    border-radius: 7px !important;
    padding: 0.2rem !important;
    margin: 1px 0.35rem !important;
}
.navbar-vertical .dropdown-item {
    border-radius: 5px !important;
    padding: 0.26rem 0.65rem !important;
    font-size: 0.785rem !important;
    line-height: 1.3 !important;
}
.navbar-vertical .dropdown-item:hover, 
.navbar-vertical .dropdown-item.active {
    background: rgba(255, 255, 255, 0.08) !important;
}

@media (max-width: 991.98px) {
    html, body { overflow-x: hidden !important; width: 100% !important; max-width: 100% !important; }
    .page-wrapper, .page-body, .container-xl { overflow-x: hidden !important; max-width: 100vw !important; }
    .row { margin-left: 0 !important; margin-right: 0 !important; }
    [class*="col-"] { padding-left: 0.5rem !important; padding-right: 0.5rem !important; }
    .table-responsive { overflow-x: hidden !important; }
    .card-body { padding: 0.75rem !important; }
    .card-header { padding: 0.5rem 0.75rem !important; }
    .card-header .card-title { font-size: 0.9rem !important; }
    .avatar-xl { width: 3rem !important; height: 3rem !important; font-size: 1.25rem !important; }
    .table td, .table th { padding: 0.25rem 0.35rem !important; font-size: 0.78rem !important; }
    .table td:first-child { min-width: auto !important; width: auto !important; white-space: nowrap; }
    .card-footer { padding: 0.5rem 0.75rem !important; }
    .mb-4 { margin-bottom: 0.75rem !important; }
    #member-sidebar, #admin-sidebar { position: fixed !important; top: 0 !important; left: -280px !important; right: auto !important; bottom: 0 !important; height: 100vh !important; width: 260px !important; z-index: 1050 !important; transition: left 0.3s ease; }
    #member-sidebar.active, #admin-sidebar.active { left: 0 !important; }
    #member-sidebar .container-fluid, #admin-sidebar .container-fluid { display: flex !important; height: 100% !important; overflow-y: auto !important; }
    #member-sidebar .navbar-brand, #admin-sidebar .navbar-brand { display: flex !important; padding: 1rem 1rem 0.5rem !important; border-bottom: 1px solid rgba(255,255,255,0.1); margin-bottom: 0.5rem; }
    #member-sidebar .navbar-nav, #admin-sidebar .navbar-nav { padding-top: 0 !important; text-align: left !important; }
    #member-sidebar .nav-link, #admin-sidebar .nav-link { justify-content: flex-start !important; padding: 0.75rem 1rem !important; }
    #sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1049; }
    #sidebar-overlay.active { display: block; }
}
</style>

<?php
// If member is logged in, show member sidebar
if (isset($_SESSION['member_id'])): ?>
<!-- Member Sidebar -->
<div class="sidebar-overlay" id="sidebar-overlay"></div>
<aside class="navbar navbar-vertical navbar-expand-lg" data-bs-theme="dark" id="member-sidebar">
    <div class="container-fluid">
        <h1 class="navbar-brand d-flex align-items-center justify-content-center mb-0 py-1">
            <a href="<?php echo url('member/dashboard'); ?>" class="sidebar-logo-card text-decoration-none">
                <?php if (!empty($globalSettings['ngo_logo'])): ?>
                    <img src="<?php echo file_url($globalSettings['ngo_logo']); ?>" alt="Logo" class="sidebar-logo-img">
                <?php else: ?>
                    <span class="fw-bold text-dark fs-4 px-1"><?php echo htmlspecialchars($globalSettings['ngo_name'] ?? 'NGO'); ?></span>
                <?php endif; ?>
            </a>
        </h1>
        <div class="navbar-collapse" id="sidebar-menu">
            <ul class="navbar-nav pt-1">
                <li class="nav-item <?php echo isActive('/member/dashboard', $current_path) ? 'active' : ''; ?>">
                    <a class="nav-link" href="<?php echo url('member/dashboard'); ?>">
                        <span class="nav-icon-badge icon-blue">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l-2 0l9 -9l9 9l-2 0" /><path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" /><path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" /></svg>
                        </span>
                        <span class="nav-link-title">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item <?php echo isActive('/member/profile', $current_path) ? 'active' : ''; ?>">
                    <a class="nav-link" href="<?php echo url('member/profile'); ?>">
                        <span class="nav-icon-badge icon-purple">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 10m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /><path d="M6.168 18.849a4 4 0 0 1 3.832 -2.849h4a4 4 0 0 1 3.834 2.855" /></svg>
                        </span>
                        <span class="nav-link-title">My Profile</span>
                    </a>
                </li>
                <li class="nav-item <?php echo isActive('/member/id-card', $current_path) ? 'active' : ''; ?>">
                    <a class="nav-link" href="<?php echo url('member/id-card'); ?>">
                        <span class="nav-icon-badge icon-emerald">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 5m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" /><path d="M7 15v-4l-2 2" /><path d="M11 15v-4l-2 2" /><path d="M15 15v-4l-2 2" /><path d="M19 15v-4l-2 2" /></svg>
                        </span>
                        <span class="nav-link-title">ID Card</span>
                    </a>
                </li>
                <li class="nav-item <?php echo isActive('/member/fees', $current_path) || isActive('/member/donate', $current_path) ? 'active' : ''; ?>">
                    <a class="nav-link" href="<?php echo url('member/fees'); ?>">
                        <span class="nav-icon-badge icon-amber">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" /><path d="M16 3l0 4" /><path d="M8 3l0 4" /><path d="M4 11l16 0" /><path d="M8 15h.01" /><path d="M12 15h.01" /><path d="M16 15h.01" /><path d="M8 19h.01" /><path d="M12 19h.01" /><path d="M16 19h.01" /></svg>
                        </span>
                        <span class="nav-link-title">My Fees</span>
                    </a>
                </li>
                <li class="nav-item <?php echo isActive('/member/donations', $current_path); ?>">
                    <a class="nav-link" href="<?php echo url('member/donations'); ?>">
                        <span class="nav-icon-badge icon-cyan">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M21 15v4a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-4" /><polyline points="7 10 12 15 17 10" /><line x1="12" y1="15" x2="12" y2="3" /></svg>
                        </span>
                        <span class="nav-link-title">Payment History</span>
                    </a>
                </li>
                <li class="nav-item <?php echo isActive('/member/notices', $current_path); ?>">
                    <a class="nav-link" href="<?php echo url('member/notices'); ?>">
                        <span class="nav-icon-badge icon-rose">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 5a2 2 0 1 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6" /><path d="M9 17v1a3 3 0 0 0 6 0v-1" /></svg>
                        </span>
                        <span class="nav-link-title">Notices</span>
                    </a>
                </li>
                <li class="nav-item mt-3">
                    <a class="nav-link text-danger" href="#" data-bs-toggle="modal" data-bs-target="#modal-logout">
                        <span class="nav-icon-badge icon-red">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" /><path d="M9 12h12l-3 -3" /><path d="M18 15l3 -3" /></svg>
                        </span>
                        <span class="nav-link-title">Logout</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</aside>
<?php return; endif;

// Admin Sidebar logic
$roleId = $_SESSION['role_id'] ?? 0;
$userId = $_SESSION['user_id'] ?? 0;

if ($roleId == 1) {
    $hasPerm = function($name) { return true; };
} else {
    $grantedPerms = [];
    if (class_exists('\\App\\Models\\Permission')) {
        $permModel = new \App\Models\Permission();
        foreach ($permModel->getUserPermissions($userId) as $p) {
            $grantedPerms[$p->name] = true;
        }
        foreach ($permModel->getRolePermissions($roleId) as $p) {
            $grantedPerms[$p->name] = true;
        }
    }
    $hasPerm = function($name) use ($grantedPerms) { return isset($grantedPerms[$name]); };
}
?>

<!-- Admin Sidebar -->
<div class="sidebar-overlay" id="sidebar-overlay"></div>
<aside class="navbar navbar-vertical navbar-expand-lg" data-bs-theme="dark" id="admin-sidebar">
    <div class="container-fluid">
        <h1 class="navbar-brand d-flex align-items-center justify-content-center mb-0 py-1">
            <a href="<?php echo url('admin/dashboard'); ?>" class="sidebar-logo-card text-decoration-none">
                <?php if (!empty($globalSettings['ngo_logo'])): ?>
                    <img src="<?php echo file_url($globalSettings['ngo_logo']); ?>" alt="Logo" class="sidebar-logo-img">
                <?php else: ?>
                    <span class="fw-bold text-dark fs-4 px-1"><?php echo htmlspecialchars($globalSettings['ngo_name'] ?? 'NGO'); ?></span>
                <?php endif; ?>
            </a>
        </h1>
        <div class="navbar-collapse" id="sidebar-menu">
            <ul class="navbar-nav pt-1">
                <li class="nav-item <?php echo isActive('/admin/dashboard', $current_path); ?>">
                    <a class="nav-link" href="<?php echo url('admin/dashboard'); ?>">
                        <span class="nav-icon-badge icon-blue">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l-2 0l9 -9l9 9l-2 0" /><path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" /><path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" /></svg>
                        </span>
                        <span class="nav-link-title">Home</span>
                    </a>
                </li>

                <?php if ($hasPerm('Slider Manager') || $hasPerm('About Page') || $hasPerm('Gallery') || $hasPerm('Certificates') || $hasPerm('Achievements')): ?>
                <li class="nav-item dropdown <?php echo isActive('/admin/cms', $current_path, false); ?>">
                    <a class="nav-link dropdown-toggle" href="#navbar-cms" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="<?php echo isActive('/admin/cms', $current_path, false) ? 'true' : 'false'; ?>" >
                        <span class="nav-icon-badge icon-emerald">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" /><path d="M4 13h16" /><path d="M13 12l0 -8" /><path d="M13 16l0 4" /><path d="M13 13l4 -4" /><path d="M13 13l-4 -4" /></svg>
                        </span>
                        <span class="nav-link-title">Content</span>
                    </a>
                    <div class="dropdown-menu <?php echo isActive('/admin/cms', $current_path, false) ? 'show' : ''; ?>">
                        <?php if ($hasPerm('Slider Manager')): ?><a class="dropdown-item <?php echo isActive('/admin/cms/slider', $current_path); ?>" href="<?php echo url('admin/cms/slider'); ?>"><span class="badge badge-dot bg-success me-2"></span>Slider</a><?php endif; ?>
                        <?php if ($hasPerm('About Page')): ?><a class="dropdown-item <?php echo isActive('/admin/cms/about', $current_path); ?>" href="<?php echo url('admin/cms/about'); ?>"><span class="badge badge-dot bg-success me-2"></span>About</a><?php endif; ?>
                        <?php if ($hasPerm('Gallery')): ?><a class="dropdown-item <?php echo isActive('/admin/cms/gallery', $current_path); ?>" href="<?php echo url('admin/cms/gallery'); ?>"><span class="badge badge-dot bg-success me-2"></span>Gallery</a><?php endif; ?>
                        <?php if ($hasPerm('Certificates')): ?><a class="dropdown-item <?php echo isActive('/admin/cms/certificates', $current_path); ?>" href="<?php echo url('admin/cms/certificates'); ?>"><span class="badge badge-dot bg-success me-2"></span>Certificates</a><?php endif; ?>
                        <?php if ($hasPerm('Achievements')): ?><a class="dropdown-item <?php echo isActive('/admin/cms/achievements', $current_path); ?>" href="<?php echo url('admin/cms/achievements'); ?>"><span class="badge badge-dot bg-success me-2"></span>Achievements</a><?php endif; ?>
                        <?php if ($hasPerm('Policies')): ?><a class="dropdown-item <?php echo isActive('/admin/cms/policies', $current_path); ?>" href="<?php echo url('admin/cms/policies'); ?>"><span class="badge badge-dot bg-success me-2"></span>Policies</a><?php endif; ?>
                    </div>
                </li>
                <?php endif; ?>

                <?php if ($hasPerm('News Manager')): ?>
                <li class="nav-item <?php echo isActive('/admin/news', $current_path, false); ?>">
                    <a class="nav-link" href="<?php echo url('admin/news'); ?>">
                        <span class="nav-icon-badge icon-amber">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M16 6h3a1 1 0 0 1 1 1v11a2 2 0 0 1 -4 0v-13a1 1 0 0 0 -1 -1h-10a1 1 0 0 0 -1 1v12a3 3 0 0 0 3 3h11" /><path d="M8 8l4 0" /><path d="M8 12l4 0" /><path d="M8 16l4 0" /></svg>
                        </span>
                        <span class="nav-link-title">News</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if ($hasPerm('Notices Manager')): ?>
                <li class="nav-item <?php echo isActive('/admin/notices', $current_path, false); ?>">
                    <a class="nav-link" href="<?php echo url('admin/notices'); ?>">
                        <span class="nav-icon-badge icon-rose">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 5a2 2 0 1 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6" /><path d="M9 17v1a3 3 0 0 0 6 0v-1" /></svg>
                        </span>
                        <span class="nav-link-title">Notices</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if ($hasPerm('Projects')): ?>
                <li class="nav-item <?php echo isActive('/admin/projects', $current_path, false); ?>">
                    <a class="nav-link" href="<?php echo url('admin/projects'); ?>">
                        <span class="nav-icon-badge icon-purple">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 21h18" /><path d="M9 8h10" /><path d="M9 12h10" /><path d="M9 16h10" /><path d="M4 8h.01" /><path d="M4 12h.01" /><path d="M4 16h.01" /></svg>
                        </span>
                        <span class="nav-link-title">Projects</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if ($hasPerm('Crowdfunding')): ?>
                <li class="nav-item <?php echo isActive('/admin/campaigns', $current_path, false); ?>">
                    <a class="nav-link" href="<?php echo url('admin/campaigns'); ?>">
                        <span class="nav-icon-badge icon-cyan">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 17a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" /><path d="M21 17a3 3 0 1 0 -6 0" /><path d="M9 17l6 0" /><path d="M9 17v-9" /><path d="M15 17v-9" /><path d="M12 3l6 4" /><path d="M12 3l-6 4" /></svg>
                        </span>
                        <span class="nav-link-title">Campaigns</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if ($hasPerm('Job Postings') || $hasPerm('Interns') || $hasPerm('Employees') || $hasPerm('Applications')): ?>
                <li class="nav-item dropdown <?php echo isDropdownActive(['/admin/careers'], $current_path); ?>">
                    <a class="nav-link dropdown-toggle" href="#navbar-careers" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="<?php echo isDropdownActive(['/admin/careers'], $current_path) ? 'true' : 'false'; ?>" >
                        <span class="nav-icon-badge icon-indigo">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" /><path d="M8 11l0 5" /><path d="M8 8l0 .01" /><path d="M12 16l0 -5" /><path d="M16 16v-3a2 2 0 0 0 -4 0" /></svg>
                        </span>
                        <span class="nav-link-title">Careers</span>
                    </a>
                    <div class="dropdown-menu <?php echo isDropdownShow(['/admin/careers'], $current_path); ?>">
                        <?php if ($hasPerm('Job Postings')): ?><a class="dropdown-item <?php echo isActive('/admin/careers', $current_path, false) && !str_contains($current_path, '/admin/careers/') ? 'active' : ''; ?>" href="<?php echo url('admin/careers'); ?>"><span class="badge badge-dot bg-indigo me-2"></span>Jobs</a><?php endif; ?>
                        <?php if ($hasPerm('Interns')): ?><a class="dropdown-item <?php echo isActive('/admin/careers/interns', $current_path, false) ? 'active' : ''; ?>" href="<?php echo url('admin/careers/interns'); ?>"><span class="badge badge-dot bg-indigo me-2"></span>Interns</a><?php endif; ?>
                        <?php if ($hasPerm('Employees')): ?><a class="dropdown-item <?php echo isActive('/admin/careers/employees', $current_path, false) ? 'active' : ''; ?>" href="<?php echo url('admin/careers/employees'); ?>"><span class="badge badge-dot bg-indigo me-2"></span>Employees</a><?php endif; ?>
                        <?php if ($hasPerm('Applications')): ?><a class="dropdown-item <?php echo isActive('/admin/careers/applications', $current_path, false) || isActive('/admin/careers/application/', $current_path, false) ? 'active' : ''; ?>" href="<?php echo url('admin/careers/applications'); ?>"><span class="badge badge-dot bg-indigo me-2"></span>Applications <?php if (!empty($appBadgeCount ?? 0)): ?><span class="badge bg-primary ms-auto"><?php echo $appBadgeCount; ?></span><?php endif; ?></a><?php endif; ?>
                    </div>
                </li>
                <?php endif; ?>

                <?php if ($hasPerm('Contact Inquiries')): ?>
                <li class="nav-item <?php echo isActive('/admin/contacts', $current_path, false); ?>">
                    <a class="nav-link" href="<?php echo url('admin/contacts'); ?>">
                        <span class="nav-icon-badge icon-teal">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 5m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" /><path d="M3 7l9 6l9 -6" /></svg>
                        </span>
                        <span class="nav-link-title">Messages</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if ($hasPerm('All Members') || $hasPerm('Member Requests') || $hasPerm('Membership Fees') || $hasPerm('Designations')): ?>
                <li class="nav-item dropdown <?php echo isDropdownActive(['/admin/members', '/admin/designations', '/admin/members/fees'], $current_path); ?>">
                    <a class="nav-link dropdown-toggle" href="#navbar-members" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="<?php echo isDropdownActive(['/admin/members', '/admin/designations', '/admin/members/fees'], $current_path) ? 'true' : 'false'; ?>" >
                        <span class="nav-icon-badge icon-fuchsia">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 3.13a4 4 0 0 1 0 7.75" /><path d="M21 21v-2a4 4 0 0 0 -3 -3.85" /></svg>
                        </span>
                        <span class="nav-link-title">Members</span>
                    </a>
                    <div class="dropdown-menu <?php echo isDropdownShow(['/admin/members', '/admin/designations', '/admin/members/fees'], $current_path); ?>">
                        <?php if ($hasPerm('All Members')): ?><a class="dropdown-item <?php echo (strpos($current_path, '/admin/members') === 0 && ($_GET['status'] ?? '') !== 'pending' && strpos($current_path, '/admin/members/fees') === false) ? 'active' : ''; ?>" href="<?php echo url('admin/members'); ?>"><span class="badge badge-dot bg-pink me-2"></span>All Members</a><?php endif; ?>
                        <?php if ($hasPerm('Member Requests')): ?><a class="dropdown-item <?php echo ($_GET['status'] ?? '') === 'pending' ? 'active' : ''; ?>" href="<?php echo url('admin/members?status=pending'); ?>"><span class="badge badge-dot bg-pink me-2"></span>Member Requests</a><?php endif; ?>
                        <?php if ($hasPerm('Membership Fees')): ?><a class="dropdown-item <?php echo isActive('/admin/members/fees', $current_path) ? 'active' : ''; ?>" href="<?php echo url('admin/members/fees'); ?>"><span class="badge badge-dot bg-pink me-2"></span>Membership Fees</a><?php endif; ?>
                        <?php if ($hasPerm('Designations')): ?><a class="dropdown-item <?php echo isActive('/admin/designations', $current_path, false); ?>" href="<?php echo url('admin/designations'); ?>"><span class="badge badge-dot bg-pink me-2"></span>Designations</a><?php endif; ?>
                    </div>
                </li>
                <?php endif; ?>

                <?php if ($hasPerm('Donors') || $hasPerm('Beneficiaries')): ?>
                <li class="nav-item dropdown <?php echo isDropdownActive(['/admin/donors', '/admin/beneficiaries'], $current_path); ?>">
                    <a class="nav-link dropdown-toggle" href="#navbar-stakeholders" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="<?php echo isDropdownActive(['/admin/donors', '/admin/beneficiaries'], $current_path) ? 'true' : 'false'; ?>" >
                        <span class="nav-icon-badge icon-green">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5" /><path d="M12 12l8 -4.5" /><path d="M12 12l0 9" /><path d="M12 12l-8 -4.5" /><path d="M16 5.25l-8 4.5" /></svg>
                        </span>
                        <span class="nav-link-title">People</span>
                    </a>
                    <div class="dropdown-menu <?php echo isDropdownShow(['/admin/donors', '/admin/beneficiaries'], $current_path); ?>">
                        <?php if ($hasPerm('Donors')): ?><a class="dropdown-item <?php echo isActive('/admin/donors', $current_path, false); ?>" href="<?php echo url('admin/donors'); ?>"><span class="badge badge-dot bg-green me-2"></span>Donors</a><?php endif; ?>
                        <?php if ($hasPerm('Beneficiaries')): ?><a class="dropdown-item <?php echo isActive('/admin/beneficiaries', $current_path, false); ?>" href="<?php echo url('admin/beneficiaries'); ?>"><span class="badge badge-dot bg-green me-2"></span>Beneficiaries</a><?php endif; ?>
                    </div>
                </li>
                <?php endif; ?>

                <?php if ($hasPerm('All Donations') || $hasPerm('Expenses') || $hasPerm('Financial Reports')): ?>
                <li class="nav-item dropdown <?php echo isDropdownActive(['/admin/finance'], $current_path); ?>">
                    <a class="nav-link dropdown-toggle" href="#navbar-finance" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="<?php echo isDropdownActive(['/admin/finance'], $current_path) ? 'true' : 'false'; ?>" >
                        <span class="nav-icon-badge icon-yellow">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z" /></svg>
                        </span>
                        <span class="nav-link-title">Finance</span>
                    </a>
                    <div class="dropdown-menu <?php echo isDropdownShow(['/admin/finance'], $current_path); ?>">
                        <?php if ($hasPerm('All Donations')): ?><a class="dropdown-item <?php echo isActive('/admin/finance/donations', $current_path); ?>" href="<?php echo url('admin/finance/donations'); ?>"><span class="badge badge-dot bg-warning me-2"></span>Donations</a><?php endif; ?>
                        <?php if ($hasPerm('Expenses')): ?><a class="dropdown-item <?php echo isActive('/admin/finance/expenses', $current_path); ?>" href="<?php echo url('admin/finance/expenses'); ?>"><span class="badge badge-dot bg-warning me-2"></span>Expenses</a><?php endif; ?>
                        <?php if ($hasPerm('Financial Reports')): ?><a class="dropdown-item <?php echo isActive('/admin/finance/reports', $current_path); ?>" href="<?php echo url('admin/finance/reports'); ?>"><span class="badge badge-dot bg-warning me-2"></span>Reports</a><?php endif; ?>
                    </div>
                </li>
                <?php endif; ?>

                <?php if ($roleId == 1 || $hasPerm('Partners')): ?>
                <li class="nav-item <?php echo isActive('/admin/partners', $current_path, false); ?>">
                    <a class="nav-link" href="<?php echo url('admin/partners'); ?>">
                        <span class="nav-icon-badge icon-orange">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 21h18" /><path d="M3 7v1a4 4 0 0 0 8 0v-1" /><path d="M13 7v1a4 4 0 0 0 8 0v-1" /><path d="M3 12h18" /></svg>
                        </span>
                        <span class="nav-link-title">Partners</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if ($hasPerm('User Management')): ?>
                <li class="nav-item <?php echo isActive('/admin/users', $current_path, false); ?>">
                    <a class="nav-link" href="<?php echo url('admin/users'); ?>">
                        <span class="nav-icon-badge icon-sky">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 3.13a4 4 0 0 1 0 7.75" /><path d="M21 21v-2a4 4 0 0 0 -3 -3.85" /></svg>
                        </span>
                        <span class="nav-link-title">Users</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if ($hasPerm('Organization') || $hasPerm('SMTP Settings') || $hasPerm('Payment Gateways') || $hasPerm('Templates')): ?>
                <li class="nav-item dropdown <?php echo isActive('/admin/settings', $current_path, false); ?>">
                    <a class="nav-link dropdown-toggle" href="#navbar-settings" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="<?php echo isActive('/admin/settings', $current_path, false) ? 'true' : 'false'; ?>" >
                        <span class="nav-icon-badge icon-slate">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z" /><path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" /></svg>
                        </span>
                        <span class="nav-link-title">Settings</span>
                    </a>
                    <div class="dropdown-menu <?php echo isActive('/admin/settings', $current_path, false) ? 'show' : ''; ?>">
                        <?php if ($hasPerm('Organization')): ?><a class="dropdown-item <?php echo isActive('/admin/settings/organization', $current_path); ?>" href="<?php echo url('admin/settings/organization'); ?>"><span class="badge badge-dot bg-secondary me-2"></span>Organization</a><?php endif; ?>
                        <?php if ($roleId == 1): ?><a class="dropdown-item <?php echo isActive('/admin/settings/update', $current_path); ?>" href="<?php echo url('admin/settings/update'); ?>"><span class="badge badge-dot bg-secondary me-2"></span>Updates</a><?php endif; ?>
                        <?php if ($hasPerm('SMTP Settings')): ?><a class="dropdown-item <?php echo isActive('/admin/settings/smtp', $current_path); ?>" href="<?php echo url('admin/settings/smtp'); ?>"><span class="badge badge-dot bg-secondary me-2"></span>SMTP Settings</a><?php endif; ?>
                        <?php if ($hasPerm('Payment Gateways')): ?><a class="dropdown-item <?php echo isActive('/admin/settings/payments', $current_path); ?>" href="<?php echo url('admin/settings/payments'); ?>"><span class="badge badge-dot bg-secondary me-2"></span>Payment Gateways</a><?php endif; ?>
                        <?php if ($hasPerm('Templates')): ?><a class="dropdown-item <?php echo isActive('/admin/settings/templates', $current_path); ?>" href="<?php echo url('admin/settings/templates'); ?>"><span class="badge badge-dot bg-secondary me-2"></span>Templates</a><?php endif; ?>
                    </div>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</aside>
