<!-- Topbar Styles -->
<style>
/* --- Profile Menu & Topbar Modern System --- */
.profile-pill-btn {
    display: inline-flex;
    align-items: center;
    gap: 9px;
    padding: 3px 12px 3px 4px;
    background: #ffffff;
    border: 1px solid rgba(203, 213, 225, 0.95);
    border-radius: 9999px;
    transition: all 0.22s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
    text-decoration: none !important;
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.05), 0 1px 2px rgba(15, 23, 42, 0.03);
    user-select: none;
}
.profile-pill-btn:hover {
    background: #ffffff;
    border-color: #6366f1;
    box-shadow: 0 4px 14px rgba(99, 102, 241, 0.15), 0 1px 3px rgba(15, 23, 42, 0.06);
    transform: translateY(-1px);
}
.profile-pill-btn:focus,
.nav-item.dropdown.show .profile-pill-btn {
    background: #ffffff;
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.18), 0 4px 12px rgba(99, 102, 241, 0.12);
}

.profile-avatar-box {
    position: relative;
    width: 34px;
    height: 34px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.8rem;
    color: #ffffff;
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    box-shadow: 0 2px 6px rgba(79, 70, 229, 0.35);
    flex-shrink: 0;
    letter-spacing: 0.5px;
}
.profile-avatar-box.member-avatar {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    box-shadow: 0 2px 6px rgba(245, 158, 11, 0.35);
}

.profile-status-dot {
    position: absolute;
    bottom: -1px;
    right: -1px;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: #10b981;
    border: 2px solid #ffffff;
    box-shadow: 0 0 4px rgba(16, 185, 129, 0.7);
}

.profile-info-text {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    line-height: 1.25;
}
.profile-user-name {
    font-size: 0.84rem;
    font-weight: 600;
    color: #0f172a !important;
    white-space: nowrap;
    max-width: 140px;
    overflow: hidden;
    text-overflow: ellipsis;
    letter-spacing: -0.01em;
}
.profile-user-role {
    font-size: 0.70rem;
    font-weight: 500;
    color: #64748b !important;
    white-space: nowrap;
    max-width: 140px;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-top: 1px;
}

.profile-dropdown-caret {
    color: #64748b;
    transition: transform 0.22s ease, color 0.22s ease;
    margin-left: 2px;
}
.nav-item.dropdown.show .profile-dropdown-caret {
    transform: rotate(180deg);
    color: #4f46e5;
}

/* --- Floating Profile Dropdown Menu Card --- */
.profile-dropdown-menu {
    border-radius: 16px !important;
    border: 1px solid rgba(226, 232, 240, 0.85) !important;
    background: #ffffff !important;
    box-shadow: 0 20px 45px -12px rgba(15, 23, 42, 0.18), 0 0 0 1px rgba(15, 23, 42, 0.04) !important;
    padding: 7px !important;
    min-width: 265px !important;
    margin-top: 8px !important;
    z-index: 1060 !important;
    animation: dropdownSlideFade 0.18s cubic-bezier(0.16, 1, 0.3, 1);
}
@keyframes dropdownSlideFade {
    0% { opacity: 0; transform: translateY(8px) scale(0.97); }
    100% { opacity: 1; transform: translateY(0) scale(1); }
}

/* Header User Profile Banner */
.profile-dropdown-header {
    padding: 12px;
    background: linear-gradient(135deg, rgba(79, 70, 229, 0.06) 0%, rgba(124, 58, 237, 0.04) 100%);
    border: 1px solid rgba(99, 102, 241, 0.12);
    border-radius: 12px;
    margin-bottom: 6px;
}
.profile-dropdown-header.member-header {
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.08) 0%, rgba(217, 119, 6, 0.05) 100%);
    border-color: rgba(245, 158, 11, 0.18);
}

/* Dropdown Menu Items */
.profile-menu-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 10px !important;
    border-radius: 9px;
    font-size: 0.84rem;
    font-weight: 500;
    color: #334155 !important;
    transition: all 0.15s cubic-bezier(0.4, 0, 0.2, 1);
    text-decoration: none !important;
}
.profile-menu-item:hover {
    background: #f1f5f9 !important;
    color: #0f172a !important;
    transform: translateX(3px);
}
.profile-menu-item:hover .profile-menu-icon {
    transform: scale(1.08);
}
.profile-menu-item.text-danger {
    color: #dc2626 !important;
}
.profile-menu-item.text-danger:hover {
    background: #fef2f2 !important;
    color: #b91c1c !important;
}

.profile-menu-icon {
    width: 30px;
    height: 30px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    transition: transform 0.15s ease;
}

/* Quick Theme Switch Tile inside Dropdown */
.profile-theme-tile {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 6px 10px;
    background: #f8fafc;
    border: 1px solid #f1f5f9;
    border-radius: 9px;
    margin: 4px 0;
    font-size: 0.8rem;
    color: #64748b;
}
.profile-theme-options {
    display: inline-flex;
    background: rgba(226, 232, 240, 0.8);
    border-radius: 20px;
    padding: 2px;
    gap: 2px;
}
.profile-theme-btn {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 2px 8px;
    border-radius: 16px;
    font-size: 0.72rem;
    font-weight: 600;
    color: #64748b;
    text-decoration: none !important;
    transition: all 0.15s ease;
}
.profile-theme-btn.active,
.profile-theme-btn:hover {
    background: #ffffff;
    color: #0f172a;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

/* Topbar Action Buttons (Theme / Link) */
.topbar-action-btn {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #ffffff;
    border: 1px solid rgba(203, 213, 225, 0.9);
    color: #475569 !important;
    transition: all 0.2s ease;
    text-decoration: none !important;
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.05);
}
.topbar-action-btn:hover {
    background: #ffffff;
    color: #4f46e5 !important;
    border-color: rgba(99, 102, 241, 0.4);
    box-shadow: 0 3px 10px rgba(99, 102, 241, 0.18);
    transform: translateY(-1px);
}

/* --- Dark Theme Support --- */
[data-bs-theme="dark"] .profile-pill-btn {
    background: rgba(30, 41, 59, 0.9);
    border-color: rgba(51, 65, 85, 0.95);
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.35);
}
[data-bs-theme="dark"] .profile-pill-btn:hover,
[data-bs-theme="dark"] .nav-item.dropdown.show .profile-pill-btn {
    background: #1e293b;
    border-color: rgba(129, 140, 248, 0.6);
    box-shadow: 0 0 0 3px rgba(129, 140, 248, 0.2), 0 4px 14px rgba(0, 0, 0, 0.45);
}
[data-bs-theme="dark"] .profile-status-dot {
    border-color: #1e293b;
}
[data-bs-theme="dark"] .profile-user-name {
    color: #f8fafc !important;
}
[data-bs-theme="dark"] .profile-user-role {
    color: #94a3b8 !important;
}
[data-bs-theme="dark"] .profile-dropdown-caret {
    color: #94a3b8;
}
[data-bs-theme="dark"] .profile-dropdown-menu {
    background: #1e293b !important;
    border-color: rgba(51, 65, 85, 0.9) !important;
    box-shadow: 0 20px 45px -10px rgba(0, 0, 0, 0.6), 0 0 0 1px rgba(255, 255, 255, 0.06) !important;
}
[data-bs-theme="dark"] .profile-dropdown-header {
    background: rgba(15, 23, 42, 0.7);
    border-color: rgba(51, 65, 85, 0.8);
}
[data-bs-theme="dark"] .profile-dropdown-header.member-header {
    background: rgba(245, 158, 11, 0.08);
    border-color: rgba(245, 158, 11, 0.2);
}
[data-bs-theme="dark"] .profile-menu-item {
    color: #cbd5e1 !important;
}
[data-bs-theme="dark"] .profile-menu-item:hover {
    background: #334155 !important;
    color: #ffffff !important;
}
[data-bs-theme="dark"] .profile-menu-item.text-danger {
    color: #f87171 !important;
}
[data-bs-theme="dark"] .profile-menu-item.text-danger:hover {
    background: rgba(239, 68, 68, 0.16) !important;
    color: #fca5a5 !important;
}
[data-bs-theme="dark"] .profile-theme-tile {
    background: #0f172a;
    border-color: #334155;
    color: #94a3b8;
}
[data-bs-theme="dark"] .profile-theme-options {
    background: #1e293b;
}
[data-bs-theme="dark"] .profile-theme-btn.active,
[data-bs-theme="dark"] .profile-theme-btn:hover {
    background: #334155;
    color: #f8fafc;
}
[data-bs-theme="dark"] .topbar-action-btn {
    background: rgba(30, 41, 59, 0.9);
    border-color: rgba(51, 65, 85, 0.95);
    color: #cbd5e1 !important;
}
[data-bs-theme="dark"] .topbar-action-btn:hover {
    background: #1e293b;
    color: #fbbf24 !important;
    border-color: rgba(251, 191, 36, 0.45);
}
</style>

<?php if (isset($_SESSION['member_id'])): ?>
<?php
$memberName = $_SESSION['member_name'] ?? 'Member';
$memParts = preg_split('/\s+/', trim($memberName));
$memberInitials = (count($memParts) >= 2)
    ? strtoupper(substr($memParts[0], 0, 1) . substr($memParts[1], 0, 1))
    : strtoupper(substr($memberName, 0, min(2, strlen($memberName))));
if (empty($memberInitials)) $memberInitials = 'ME';
$memberEmail = $_SESSION['member_email'] ?? ($globalSettings['ngo_email'] ?? 'Member Account');
?>
<!-- Member Desktop Topbar -->
<header class="navbar navbar-expand-md navbar-light d-none d-lg-flex d-print-none" style="background: rgba(255,255,255,0.92); backdrop-filter: blur(8px); border-bottom: 1px solid rgba(226, 232, 240, 0.85);">
    <div class="container-xl" style="overflow: visible;">
        <div class="navbar-nav flex-row order-md-last align-items-center">
            <!-- View Website -->
            <a href="<?php echo url('/'); ?>" target="_blank" class="topbar-action-btn me-2" title="View Website" data-bs-toggle="tooltip" data-bs-placement="bottom">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" /><path d="M3.6 9h16.8" /><path d="M3.6 15h16.8" /><path d="M11.5 3a17 17 0 0 0 0 18" /><path d="M12.5 3a17 17 0 0 1 0 18" /></svg>
            </a>
            <!-- Theme Toggles -->
            <div class="d-flex align-items-center me-3">
                <a href="?theme=dark" class="topbar-action-btn hide-theme-dark" title="Enable dark mode" data-bs-toggle="tooltip" data-bs-placement="bottom">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z" /></svg>
                </a>
                <a href="?theme=light" class="topbar-action-btn hide-theme-light" title="Enable light mode" data-bs-toggle="tooltip" data-bs-placement="bottom">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M3 12h1m8 -9v1m8 8h1m-9 8v1m-6.4 -15.4l.7 .7m12.1 -.7l-.7 .7m0 11.4l.7 .7m-12.1 -.7l-.7 .7" /></svg>
                </a>
            </div>

            <!-- Member Profile Menu -->
            <div class="nav-item dropdown">
                <a href="#" class="profile-pill-btn" data-bs-toggle="dropdown" aria-label="Open member menu" aria-expanded="false">
                    <div class="profile-avatar-box member-avatar">
                        <?php echo e($memberInitials); ?>
                        <span class="profile-status-dot"></span>
                    </div>
                    <div class="profile-info-text d-flex">
                        <span class="profile-user-name"><?php echo e($memberName); ?></span>
                        <span class="profile-user-role">Active Member</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-xs profile-dropdown-caret" width="16" height="16" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 9l6 6l6 -6" /></svg>
                </a>

                <div class="dropdown-menu dropdown-menu-end profile-dropdown-menu">
                    <!-- Member Header Banner -->
                    <div class="profile-dropdown-header member-header">
                        <div class="d-flex align-items-center gap-2">
                            <div class="profile-avatar-box member-avatar" style="width:42px;height:42px;font-size:0.95rem;">
                                <?php echo e($memberInitials); ?>
                                <span class="profile-status-dot"></span>
                            </div>
                            <div style="overflow:hidden;line-height:1.25;">
                                <div class="fw-bold text-truncate" style="font-size:0.92rem;color:var(--tblr-body-color, #0f172a);"><?php echo e($memberName); ?></div>
                                <div class="text-muted text-truncate mt-0.5" style="font-size:0.75rem;"><?php echo e($memberEmail); ?></div>
                                <div class="mt-1">
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill" style="font-size:0.67rem;padding:2px 8px;font-weight:600;">Active Member</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Member Action Items -->
                    <a href="<?php echo url('member/profile'); ?>" class="dropdown-item profile-menu-item">
                        <span class="profile-menu-icon" style="background: rgba(245,158,11,0.12); color: #f59e0b;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-xs" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 10m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /><path d="M6.168 18.849a4 4 0 0 1 3.832 -2.849h4a4 4 0 0 1 3.834 2.855" /></svg>
                        </span>
                        <span>My Profile</span>
                        <span class="ms-auto text-muted small" style="font-size:0.7rem;">Edit</span>
                    </a>

                    <a href="<?php echo url('member/dashboard'); ?>" class="dropdown-item profile-menu-item">
                        <span class="profile-menu-icon" style="background: rgba(59,130,246,0.12); color: #3b82f6;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-xs" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4h6v8h-6z" /><path d="M4 16h6v4h-6z" /><path d="M14 12h6v8h-6z" /><path d="M14 4h6v4h-6z" /></svg>
                        </span>
                        <span>Dashboard</span>
                    </a>

                    <a href="<?php echo url('member/id-card'); ?>" class="dropdown-item profile-menu-item">
                        <span class="profile-menu-icon" style="background: rgba(16,185,129,0.12); color: #10b981;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-xs" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 5m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" /><path d="M7 15v-4l-2 2" /><path d="M11 15v-4l-2 2" /><path d="M15 15v-4l-2 2" /><path d="M19 15v-4l-2 2" /></svg>
                        </span>
                        <span>Member ID Card</span>
                    </a>

                    <a href="<?php echo url('/'); ?>" target="_blank" class="dropdown-item profile-menu-item">
                        <span class="profile-menu-icon" style="background: rgba(99,102,241,0.12); color: #6366f1;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-xs" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" /><path d="M3.6 9h16.8" /><path d="M3.6 15h16.8" /><path d="M11.5 3a17 17 0 0 0 0 18" /><path d="M12.5 3a17 17 0 0 1 0 18" /></svg>
                        </span>
                        <span>Live Website</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-xs ms-auto text-muted" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 6h-6a2 2 0 0 0 -2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-6" /><path d="M11 13l9 -9" /><path d="M15 4h5v5" /></svg>
                    </a>

                    <!-- Theme Switcher Tile inside Dropdown -->
                    <div class="profile-theme-tile">
                        <div class="d-flex align-items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z" /></svg>
                            <span>Appearance</span>
                        </div>
                        <div class="profile-theme-options">
                            <a href="?theme=light" class="profile-theme-btn <?php echo (!isset($_SESSION['theme']) || $_SESSION['theme'] !== 'dark') ? 'active' : ''; ?>">Light</a>
                            <a href="?theme=dark" class="profile-theme-btn <?php echo (isset($_SESSION['theme']) && $_SESSION['theme'] === 'dark') ? 'active' : ''; ?>">Dark</a>
                        </div>
                    </div>

                    <div class="dropdown-divider my-1"></div>

                    <!-- Sign Out Trigger -->
                    <a href="#" class="dropdown-item profile-menu-item text-danger" data-bs-toggle="modal" data-bs-target="#modal-logout">
                        <span class="profile-menu-icon" style="background: rgba(239,68,68,0.12); color: #ef4444;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-xs" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" /><path d="M9 12h12l-3 -3" /><path d="M18 15l3 -3" /></svg>
                        </span>
                        <span>Sign Out</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Member Mobile Topbar -->
<header class="navbar navbar-light d-lg-none d-flex d-print-none" style="background:#fff;border-bottom:1px solid #e5e7eb;height:56px;">
    <div class="container-fluid px-3" style="overflow: visible;">
        <div class="d-flex align-items-center justify-content-between w-100">
            <div class="d-flex align-items-center gap-2">
                <a href="javascript:void(0)" class="nav-link px-1" id="mobile-sidebar-toggle">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 6l16 0" /><path d="M4 12l16 0" /><path d="M4 18l16 0" /></svg>
                </a>
                <span class="fw-bold d-inline-block text-truncate" style="font-size: 1.05rem; color: #1a1a2e; max-width: 160px;"><?php echo e($globalSettings['ngo_name'] ?? 'NGO'); ?></span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="?theme=dark" class="topbar-action-btn hide-theme-dark" style="width:32px;height:32px;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z" /></svg>
                </a>
                <a href="?theme=light" class="topbar-action-btn hide-theme-light" style="width:32px;height:32px;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M3 12h1m8 -9v1m8 8h1m-9 8v1m-6.4 -15.4l.7 .7m12.1 -.7l-.7 .7m0 11.4l.7 .7m-12.1 -.7l-.7 .7" /></svg>
                </a>
                <div class="nav-item dropdown">
                    <a href="#" class="profile-pill-btn p-1" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="profile-avatar-box member-avatar" style="width:32px;height:32px;font-size:0.75rem;">
                            <?php echo e($memberInitials); ?>
                            <span class="profile-status-dot"></span>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end profile-dropdown-menu">
                        <div class="profile-dropdown-header member-header">
                            <div class="d-flex align-items-center gap-2">
                                <div class="profile-avatar-box member-avatar" style="width:38px;height:38px;font-size:0.9rem;">
                                    <?php echo e($memberInitials); ?>
                                    <span class="profile-status-dot"></span>
                                </div>
                                <div style="overflow:hidden;line-height:1.25;">
                                    <div class="fw-bold text-truncate" style="font-size:0.9rem;"><?php echo e($memberName); ?></div>
                                    <div class="text-muted text-truncate" style="font-size:0.74rem;"><?php echo e($memberEmail); ?></div>
                                </div>
                            </div>
                        </div>
                        <a href="<?php echo url('member/profile'); ?>" class="dropdown-item profile-menu-item">My Profile</a>
                        <a href="<?php echo url('member/dashboard'); ?>" class="dropdown-item profile-menu-item">Dashboard</a>
                        <a href="<?php echo url('member/id-card'); ?>" class="dropdown-item profile-menu-item">Member ID Card</a>
                        <div class="dropdown-divider my-1"></div>
                        <a href="#" class="dropdown-item profile-menu-item text-danger" data-bs-toggle="modal" data-bs-target="#modal-logout">Sign Out</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<?php return; endif; ?>

<?php
$adminName = $_SESSION['user_name'] ?? 'Administrator';
$adminParts = preg_split('/\s+/', trim($adminName));
$adminInitials = (count($adminParts) >= 2)
    ? strtoupper(substr($adminParts[0], 0, 1) . substr($adminParts[1], 0, 1))
    : strtoupper(substr($adminName, 0, min(2, strlen($adminName))));
if (empty($adminInitials)) $adminInitials = 'AD';

$roleTitle = 'Administrator';
if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1) {
    $roleTitle = 'Super Admin';
} elseif (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 2) {
    $roleTitle = 'Admin';
}
$adminEmail = $_SESSION['user_email'] ?? ($globalSettings['ngo_email'] ?? 'admin@system');
$ngoName = $globalSettings['ngo_name'] ?? 'NGO Help';
?>

<!-- Admin Desktop Topbar -->
<header class="navbar navbar-expand-md navbar-light d-none d-lg-flex d-print-none" style="background: rgba(255,255,255,0.92); backdrop-filter: blur(8px); border-bottom: 1px solid rgba(226, 232, 240, 0.85);">
    <div class="container-xl" style="overflow: visible;">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu" aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Sidebar Toggle (Desktop Burger Menu) -->
        <a href="javascript:void(0)" class="nav-link px-0 me-3 d-none d-lg-block" id="sidebar-toggle" title="Toggle Sidebar">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 6l16 0" /><path d="M4 12l16 0" /><path d="M4 18l16 0" /></svg>
        </a>

        <!-- Right Side Nav Items -->
        <div class="navbar-nav flex-row order-md-last align-items-center">
            <!-- View Live Website -->
            <a href="<?php echo url('/'); ?>" target="_blank" class="topbar-action-btn me-2" title="View Live Website" data-bs-toggle="tooltip" data-bs-placement="bottom">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" /><path d="M3.6 9h16.8" /><path d="M3.6 15h16.8" /><path d="M11.5 3a17 17 0 0 0 0 18" /><path d="M12.5 3a17 17 0 0 1 0 18" /></svg>
            </a>

            <!-- Dark / Light Theme Toggle -->
            <div class="d-flex align-items-center me-3">
                <a href="?theme=dark" class="topbar-action-btn hide-theme-dark" title="Enable dark mode" data-bs-toggle="tooltip" data-bs-placement="bottom">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z" /></svg>
                </a>
                <a href="?theme=light" class="topbar-action-btn hide-theme-light" title="Enable light mode" data-bs-toggle="tooltip" data-bs-placement="bottom">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M3 12h1m8 -9v1m8 8h1m-9 8v1m-6.4 -15.4l.7 .7m12.1 -.7l-.7 .7m0 11.4l.7 .7m-12.1 -.7l-.7 .7" /></svg>
                </a>
            </div>

            <!-- Profile Menu Pill (Right Top Corner Trigger) -->
            <div class="nav-item dropdown">
                <a href="#" class="profile-pill-btn" data-bs-toggle="dropdown" aria-label="Open user menu" aria-expanded="false">
                    <!-- User Initial Avatar with Status Dot -->
                    <div class="profile-avatar-box">
                        <?php echo e($adminInitials); ?>
                        <span class="profile-status-dot"></span>
                    </div>

                    <!-- User Information: Name and Role (ALWAYS VISIBLE) -->
                    <div class="profile-info-text d-flex">
                        <span class="profile-user-name"><?php echo e($adminName); ?></span>
                        <span class="profile-user-role"><?php echo e($roleTitle); ?></span>
                    </div>

                    <!-- Dropdown Chevron -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-xs profile-dropdown-caret" width="16" height="16" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 9l6 6l6 -6" /></svg>
                </a>

                <!-- Rich Profile Dropdown Menu -->
                <div class="dropdown-menu dropdown-menu-end profile-dropdown-menu">
                    <!-- User Info Card Header -->
                    <div class="profile-dropdown-header">
                        <div class="d-flex align-items-center gap-2">
                            <div class="profile-avatar-box" style="width: 42px; height: 42px; font-size: 0.95rem;">
                                <?php echo e($adminInitials); ?>
                                <span class="profile-status-dot"></span>
                            </div>
                            <div style="overflow: hidden; line-height: 1.25;">
                                <div class="fw-bold text-truncate" style="font-size: 0.92rem; color: var(--tblr-body-color, #0f172a);"><?php echo e($adminName); ?></div>
                                <div class="text-muted text-truncate mt-0.5" style="font-size: 0.75rem;"><?php echo e($adminEmail); ?></div>
                                <div class="d-flex align-items-center gap-1 mt-1">
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill" style="font-size: 0.67rem; padding: 2px 8px; font-weight:600;">
                                        <?php echo e($roleTitle); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Menu Action Items -->
                    <a href="<?php echo url('admin/profile'); ?>" class="dropdown-item profile-menu-item">
                        <span class="profile-menu-icon" style="background: rgba(59, 130, 246, 0.12); color: #2563eb;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-xs" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 10m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /><path d="M6.168 18.849a4 4 0 0 1 3.832 -2.849h4a4 4 0 0 1 3.834 2.855" /></svg>
                        </span>
                        <span>My Profile</span>
                        <span class="ms-auto text-muted small" style="font-size:0.7rem;">Account</span>
                    </a>

                    <a href="<?php echo url('admin/settings/organization'); ?>" class="dropdown-item profile-menu-item">
                        <span class="profile-menu-icon" style="background: rgba(245, 158, 11, 0.12); color: #d97706;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-xs" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z" /><path d="M12 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /></svg>
                        </span>
                        <span>System Settings</span>
                        <span class="ms-auto text-muted small" style="font-size:0.7rem;">Config</span>
                    </a>

                    <a href="<?php echo url('admin/security/audit'); ?>" class="dropdown-item profile-menu-item">
                        <span class="profile-menu-icon" style="background: rgba(16, 185, 129, 0.12); color: #059669;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-xs" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3a12 12 0 0 0 8.5 3a12 12 0 0 1 -8.5 15a12 12 0 0 1 -8.5 -15a12 12 0 0 0 8.5 -3" /></svg>
                        </span>
                        <span>Security & Audit</span>
                        <span class="ms-auto text-muted small" style="font-size:0.7rem;">Logs</span>
                    </a>

                    <a href="<?php echo url('/'); ?>" target="_blank" class="dropdown-item profile-menu-item">
                        <span class="profile-menu-icon" style="background: rgba(99, 102, 241, 0.12); color: #4f46e5;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-xs" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" /><path d="M3.6 9h16.8" /><path d="M3.6 15h16.8" /><path d="M11.5 3a17 17 0 0 0 0 18" /><path d="M12.5 3a17 17 0 0 1 0 18" /></svg>
                        </span>
                        <span>Live Website</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-xs ms-auto text-muted" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 6h-6a2 2 0 0 0 -2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-6" /><path d="M11 13l9 -9" /><path d="M15 4h5v5" /></svg>
                    </a>

                    <!-- Theme Switcher Tile inside Dropdown -->
                    <div class="profile-theme-tile">
                        <div class="d-flex align-items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z" /></svg>
                            <span>Appearance</span>
                        </div>
                        <div class="profile-theme-options">
                            <a href="?theme=light" class="profile-theme-btn <?php echo (!isset($_SESSION['theme']) || $_SESSION['theme'] !== 'dark') ? 'active' : ''; ?>">Light</a>
                            <a href="?theme=dark" class="profile-theme-btn <?php echo (isset($_SESSION['theme']) && $_SESSION['theme'] === 'dark') ? 'active' : ''; ?>">Dark</a>
                        </div>
                    </div>

                    <div class="dropdown-divider my-1"></div>

                    <!-- Sign Out Triggering Standard Modal -->
                    <a href="#" class="dropdown-item profile-menu-item text-danger" data-bs-toggle="modal" data-bs-target="#modal-logout">
                        <span class="profile-menu-icon" style="background: rgba(239, 68, 68, 0.12); color: #dc2626;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-xs" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" /><path d="M9 12h12l-3 -3" /><path d="M18 15l3 -3" /></svg>
                        </span>
                        <span>Sign Out</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Admin Mobile Topbar -->
<header class="navbar navbar-light d-lg-none d-flex d-print-none" style="background:#fff;border-bottom:1px solid #e5e7eb;height:56px;">
    <div class="container-fluid px-3" style="overflow: visible;">
        <div class="d-flex align-items-center gap-2">
            <a href="javascript:void(0)" class="nav-link px-1" id="mobile-sidebar-toggle">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 6l16 0" /><path d="M4 12l16 0" /><path d="M4 18l16 0" /></svg>
            </a>
            <span class="fw-bold d-inline-block text-truncate" style="font-size: 1.05rem; color: #1e293b; max-width: 150px;"><?php echo e($globalSettings['ngo_name'] ?? 'NGO'); ?></span>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="?theme=dark" class="topbar-action-btn hide-theme-dark" style="width:32px;height:32px;">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z" /></svg>
            </a>
            <a href="?theme=light" class="topbar-action-btn hide-theme-light" style="width:32px;height:32px;">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M3 12h1m8 -9v1m8 8h1m-9 8v1m-6.4 -15.4l.7 .7m12.1 -.7l-.7 .7m0 11.4l.7 .7m-12.1 -.7l-.7 .7" /></svg>
            </a>
            <div class="nav-item dropdown">
                <a href="#" class="profile-pill-btn p-1" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="profile-avatar-box" style="width:32px;height:32px;font-size:0.75rem;">
                        <?php echo e($adminInitials); ?>
                        <span class="profile-status-dot"></span>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end profile-dropdown-menu">
                    <div class="profile-dropdown-header">
                        <div class="d-flex align-items-center gap-2">
                            <div class="profile-avatar-box" style="width: 38px; height: 38px; font-size: 0.9rem;">
                                <?php echo e($adminInitials); ?>
                                <span class="profile-status-dot"></span>
                            </div>
                            <div style="overflow: hidden; line-height: 1.25;">
                                <div class="fw-bold text-truncate" style="font-size: 0.9rem;"><?php echo e($adminName); ?></div>
                                <div class="text-muted text-truncate" style="font-size: 0.74rem;"><?php echo e($adminEmail); ?></div>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill mt-1" style="font-size: 0.65rem; padding: 2px 7px;">
                                    <?php echo e($roleTitle); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <a href="<?php echo url('admin/profile'); ?>" class="dropdown-item profile-menu-item">My Profile</a>
                    <a href="<?php echo url('admin/settings/organization'); ?>" class="dropdown-item profile-menu-item">Settings</a>
                    <a href="<?php echo url('admin/security/audit'); ?>" class="dropdown-item profile-menu-item">Security & Audit</a>
                    <div class="dropdown-divider my-1"></div>
                    <a href="#" class="dropdown-item profile-menu-item text-danger" data-bs-toggle="modal" data-bs-target="#modal-logout">Sign Out</a>
                </div>
            </div>
        </div>
    </div>
</header>
