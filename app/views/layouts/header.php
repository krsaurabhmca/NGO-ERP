<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($title ?? 'NGO Management System'); ?></title>
    <!-- Favicon -->
    <?php if(!empty($globalSettings['ngo_favicon'])): ?>
        <link rel="icon" href="<?php echo file_url($globalSettings['ngo_favicon']); ?>" type="image/x-icon"/>
    <?php endif; ?>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="csrf-token" content="<?php echo csrf_token('_default'); ?>">
    <link rel="stylesheet" href="<?php echo url('assets/css/style.css'); ?>">
    <?php if(!empty($globalSettings['theme_primary_color']) || !empty($globalSettings['theme_secondary_color'])): ?>
    <style>
        :root {
            <?php if(!empty($globalSettings['theme_primary_color'])): ?>
            --primary: <?php echo $globalSettings['theme_primary_color']; ?>;
            --primary-dark: <?php echo $globalSettings['theme_primary_color']; ?>;
            <?php endif; ?>
            <?php if(!empty($globalSettings['theme_secondary_color'])): ?>
            --accent: <?php echo $globalSettings['theme_secondary_color']; ?>;
            --accent-dark: <?php echo $globalSettings['theme_secondary_color']; ?>;
            <?php endif; ?>
        }
    </style>
    <?php endif; ?>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const nav = document.querySelector('.navbar-modern');
        window.addEventListener('scroll', function () {
            if (window.scrollY > 20) {
                nav.classList.add('scrolled');
            } else {
                nav.classList.remove('scrolled');
            }
        });
    });
    </script>
</head>
<body>

<!-- Top Bar -->
<div class="topbar d-none d-lg-block">
    <div class="container-fluid px-lg-5">
        <div class="row align-items-center">
            <div class="col d-flex align-items-center">
                <?php
                $topbarItems = [];
                if (!empty($globalSettings['ngo_phone'])) {
                    $topbarItems[] = '<span><i class="fas fa-phone topbar-icon me-1"></i>' . htmlspecialchars($globalSettings['ngo_phone']) . '</span>';
                }
                if (!empty($globalSettings['ngo_email'])) {
                    $topbarItems[] = '<span><i class="fas fa-envelope topbar-icon me-1"></i>' . htmlspecialchars($globalSettings['ngo_email']) . '</span>';
                }
                if (!empty($globalSettings['ngo_address'])) {
                    $topbarItems[] = '<span><i class="fas fa-map-marker-alt topbar-icon me-1"></i>' . htmlspecialchars($globalSettings['ngo_address']) . '</span>';
                }
                if (!empty($topbarItems)) {
                    echo implode('<span class="topbar-sep mx-2">|</span>', $topbarItems);
                } else {
                    echo '<span class="text-white-50 small"><i class="fas fa-hand-holding-heart me-1"></i>Welcome to ' . htmlspecialchars($globalSettings['ngo_name'] ?? 'our NGO') . '</span>';
                }
                ?>
            </div>
            <div class="col-auto topbar-social d-flex align-items-center">
                <?php
                $socialLinks = [
                    'facebook'  => ['icon' => 'fab fa-facebook-f',  'title' => 'Facebook'],
                    'twitter'   => ['icon' => 'fab fa-twitter',     'title' => 'Twitter'],
                    'instagram' => ['icon' => 'fab fa-instagram',   'title' => 'Instagram'],
                    'linkedin'  => ['icon' => 'fab fa-linkedin-in', 'title' => 'LinkedIn'],
                    'youtube'   => ['icon' => 'fab fa-youtube',     'title' => 'YouTube'],
                ];
                $hasAny = false;
                foreach ($socialLinks as $key => $link):
                    $url = $globalSettings['social_' . $key] ?? '';
                    if (!empty($url)):
                        $hasAny = true;
                ?>
                    <a href="<?php echo htmlspecialchars($url); ?>" target="_blank" title="<?php echo $link['title']; ?>"><i class="<?php echo $link['icon']; ?>"></i></a>
                <?php
                    endif;
                endforeach;
                if (!$hasAny): echo '<span class="text-white-50 small">No social links configured</span>';
                endif;
                ?>
            </div>
        </div>
    </div>
</div>

<nav class="navbar navbar-expand-lg sticky-top navbar-modern">
    <div class="container-fluid px-lg-5 position-relative">
        <a class="navbar-brand d-flex align-items-center" href="<?php echo url('/'); ?>">
            <?php if (!empty($globalSettings['ngo_logo'])): ?>
                <img src="<?php echo file_url($globalSettings['ngo_logo']); ?>" alt="Logo" style="max-height: 36px; filter: none !important;">
            <?php else: ?>
                <i class="fas fa-hand-holding-heart" style="color: var(--primary); font-size: 1.4rem;"></i>
            <?php endif; ?>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav navbar-nav-center">
                <li class="nav-item"><a class="nav-link" href="<?php echo url('/'); ?>">Home</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownAbout" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        About <i class="fas fa-chevron-down"></i>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownAbout">
                        <li><a class="dropdown-item" href="<?php echo url('/about'); ?>"><i class="fas fa-info-circle me-2" style="color: var(--primary); width: 16px;"></i>About Us</a></li>
                        <li><a class="dropdown-item" href="<?php echo url('/certificates'); ?>"><i class="fas fa-certificate me-2" style="color: var(--primary); width: 16px;"></i>Certificates</a></li>
                        <li><a class="dropdown-item" href="<?php echo url('/achievements'); ?>"><i class="fas fa-trophy me-2" style="color: var(--primary); width: 16px;"></i>Achievements</a></li>
                        <li><a class="dropdown-item" href="<?php echo url('/beneficiaries'); ?>"><i class="fas fa-hands-helping me-2" style="color: var(--primary); width: 16px;"></i>Beneficiaries</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMedia" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Media <i class="fas fa-chevron-down"></i>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMedia">
                        <li><a class="dropdown-item" href="<?php echo url('/gallery'); ?>"><i class="fas fa-images me-2" style="color: var(--primary); width: 16px;"></i>Gallery</a></li>
                        <li><a class="dropdown-item" href="<?php echo url('/news'); ?>"><i class="fas fa-newspaper me-2" style="color: var(--primary); width: 16px;"></i>News</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMembers" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Get Involved <i class="fas fa-chevron-down"></i>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMembers">
                        <li><a class="dropdown-item" href="<?php echo url('/members'); ?>"><i class="fas fa-users me-2" style="color: var(--primary); width: 16px;"></i>Our Members</a></li>
                        <li><a class="dropdown-item" href="<?php echo url('/members/register'); ?>"><i class="fas fa-user-plus me-2" style="color: var(--primary); width: 16px;"></i>Become a Member</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?php echo url('/careers'); ?>"><i class="fas fa-briefcase me-2" style="color: var(--primary); width: 16px;"></i>Careers</a></li>
                    </ul>
                </li>
                <li class="nav-item"><a class="nav-link" href="<?php echo url('/projects'); ?>">Projects</a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo url('/campaigns'); ?>">Campaigns</a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo url('/contact'); ?>">Contact</a></li>
            </ul>
            <ul class="navbar-nav ms-auto align-items-center gap-3">
                <li class="nav-item">
                    <?php if (isset($_SESSION['member_id'])): ?>
                        <a class="nav-link-admin d-flex align-items-center gap-1" href="<?php echo url('member/dashboard'); ?>">
                            <i class="fas fa-th-large" style="font-size: 0.8rem;"></i> Dashboard
                        </a>
                    <?php else: ?>
                        <a class="nav-link-admin d-flex align-items-center gap-1" href="<?php echo url('/auth'); ?>">
                            <i class="fas fa-lock" style="font-size: 0.8rem;"></i> Login
                        </a>
                    <?php endif; ?>
                </li>
                <li class="nav-item">
                    <a class="btn-accent d-flex align-items-center gap-2" href="<?php echo url('/donate'); ?>">
                        <i class="fas fa-heart" style="font-size: 0.85rem;"></i> Donate Now
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
