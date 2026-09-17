<?php require_once 'app/views/layouts/header.php'; ?>

<section class="position-relative overflow-hidden" style="background-color: var(--primary); background-image: radial-gradient(circle at 10% 20%, var(--accent) 0%, transparent 50%), radial-gradient(circle at 90% 80%, var(--accent) 0%, transparent 50%), radial-gradient(circle at 50% 50%, var(--primary-dark) 0%, transparent 70%);">
    <div class="container-fluid px-lg-5 py-5 text-center position-relative" style="z-index: 1;">
        <h1 class="display-5 fw-bold text-white mb-0"><?php echo $title; ?></h1>
        <p class="text-white-50 mb-0" style="max-width: 600px; margin: 0 auto;">Learn about our mission, vision, and the values that drive us forward.</p>
    </div>
    <div class="position-absolute top-0 end-0 opacity-10 hero-svg-circle">
        <svg width="400" height="400" viewBox="0 0 400 400" fill="none"><circle cx="300" cy="100" r="200" fill="var(--accent)"/><circle cx="100" cy="350" r="150" fill="var(--accent)"/></svg>
    </div>
    <div class="position-absolute bottom-0 start-0 opacity-10 hero-svg-circle">
        <svg width="300" height="300" viewBox="0 0 300 300" fill="none"><circle cx="50" cy="250" r="120" fill="var(--accent)"/></svg>
    </div>
</section>

<section class="py-5" style="background: linear-gradient(180deg, #f8fafc 0%, #fffff0 100%);">
    <div class="container-fluid px-lg-5">
        <div class="row g-5 align-items-center">
            <div class="col-lg-5">
                <div class="position-relative">
                    <?php if (!empty($cms->image)): ?>
                        <img src="<?php echo file_url($cms->image); ?>" class="img-fluid rounded-4 w-100" alt="About Us" style="max-height: 450px; object-fit: cover;">
                    <?php else: ?>
                        <img src="<?php echo url('assets/images/placeholder.jpg'); ?>" class="img-fluid rounded-4 w-100" alt="About Us" style="max-height: 450px; object-fit: cover;">
                    <?php endif; ?>

                </div>
            </div>
            <div class="col-lg-7">
                <span class="badge px-3 py-2 rounded-pill mb-3 fw-semibold" style="background: rgba(13,148,136,0.12); color: var(--accent);">Who We Are</span>
                <h2 class="fw-bold display-6 mb-3" style="color: var(--primary);">Our Story</h2>
                <div class="lh-lg mb-4 fw-medium" style="color: #1a1a2e; font-size: 1.1rem;">
                    <?php echo !empty($cms->content) ? nl2br(htmlspecialchars($cms->content)) : '<p>Founded in 2010, NGO HELP has been at the forefront of community development. We believe that everyone deserves a chance to thrive, regardless of their background or circumstances.</p><p>Our team consists of dedicated professionals who work tirelessly to ensure that our projects are impactful and sustainable.</p>'; ?>
                </div>
                <div class="d-flex align-items-center gap-4 flex-wrap">
                    <a href="<?php echo url('/contact'); ?>" class="btn btn-accent rounded-pill px-4 py-2">
                        <i class="fas fa-paper-plane me-2"></i>Get In Touch
                    </a>
                    <a href="<?php echo url('/projects'); ?>" class="btn rounded-pill px-4 py-2" style="border: 2px solid var(--accent); color: var(--accent); font-weight: 600;">
                        <i class="fas fa-arrow-right me-2"></i>Our Projects
                    </a>
                </div>
            </div>
        </div>

        <div class="row g-4 mt-5">
            <div class="col-6 col-md-3">
                <div class="stat-box h-100 text-center rounded-4 p-4" style="background: #fffff0; box-shadow: 0 4px 20px rgba(0,0,0,0.06);">
                    <div class="fw-bold fs-3 mb-1" style="color: var(--primary);">10+</div>
                    <div class="small fw-semibold" style="color: #64748b;">Years of Service</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-box h-100 text-center rounded-4 p-4" style="background: #fffff0; box-shadow: 0 4px 20px rgba(0,0,0,0.06);">
                    <div class="fw-bold fs-3 mb-1" style="color: var(--accent);">50+</div>
                    <div class="small fw-semibold" style="color: #64748b;">Projects Completed</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-box h-100 text-center rounded-4 p-4" style="background: #fffff0; box-shadow: 0 4px 20px rgba(0,0,0,0.06);">
                    <div class="fw-bold fs-3 mb-1" style="color: var(--accent);">5K+</div>
                    <div class="small fw-semibold" style="color: #64748b;">Lives Impacted</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-box h-100 text-center rounded-4 p-4" style="background: #fffff0; box-shadow: 0 4px 20px rgba(0,0,0,0.06);">
                    <div class="fw-bold fs-3 mb-1" style="color: #7c3aed;">200+</div>
                    <div class="small fw-semibold" style="color: #64748b;">Active Volunteers</div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="position-relative wave-divider">
    <svg viewBox="0 0 1440 60" preserveAspectRatio="none" style="display: block; width: 100%; height: 60px;">
        <path d="M0,30 C360,60 1080,0 1440,30 L1440,60 L0,60 Z" fill="#f8fafc"></path>
    </svg>
    <div style="background: #fffff0; height: 2px;"></div>
    <svg viewBox="0 0 1440 60" preserveAspectRatio="none" style="display: block; width: 100%; height: 60px; transform: rotate(180deg);">
        <path d="M0,30 C360,60 1080,0 1440,30 L1440,60 L0,60 Z" fill="#f8fafc"></path>
    </svg>
</div>

<?php if (!empty($cms->mission) || !empty($cms->vision)): ?>
<section class="py-5" style="background: #FFFFDE;">
    <div class="container-fluid px-lg-5">
        <div class="text-center mb-5">
            <span class="badge px-3 py-2 rounded-pill mb-3 fw-semibold" style="background: rgba(0,53,102,0.1); color: var(--primary);">Our Purpose</span>
            <h2 class="fw-bold display-6 mb-2" style="color: var(--primary);">Mission & Vision</h2>
            <p class="text-secondary fs-5">The guiding principles behind everything we do.</p>
        </div>
        <div class="row g-4 justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card h-100 border-0 shadow-sm p-4 rounded-4 hover-lift position-relative overflow-hidden">
                    <div class="position-absolute top-0 end-0" style="width: 120px; height: 120px; background: rgba(0,53,102,0.05); border-radius: 0 0 0 120px;"></div>
                    <div class="d-flex align-items-center mb-4">
                        <div class="me-3 d-flex align-items-center justify-content-center rounded-3" style="width: 56px; height: 56px; background: rgba(0,53,102,0.1);">
                            <i class="fas fa-bullseye fs-4" style="color: var(--primary);"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-0 h4" style="color: var(--primary);">Our Mission</h3>
                            <small class="text-muted">What we strive for</small>
                        </div>
                    </div>
                    <p class="text-secondary mb-0"><?php echo nl2br(htmlspecialchars($cms->mission)); ?></p>
                </div>
            </div>
            <div class="col-md-6 col-lg-5">
                <div class="card h-100 border-0 shadow-sm p-4 rounded-4 hover-lift position-relative overflow-hidden">
                    <div class="position-absolute top-0 end-0" style="width: 120px; height: 120px; background: rgba(255,191,0,0.08); border-radius: 0 0 0 120px;"></div>
                    <div class="d-flex align-items-center mb-4">
                        <div class="me-3 d-flex align-items-center justify-content-center rounded-3" style="width: 56px; height: 56px; background: rgba(255,191,0,0.15);">
                            <i class="fas fa-eye fs-4" style="color: var(--accent);"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-0 h4" style="color: var(--primary);">Our Vision</h3>
                            <small class="text-muted">What we aspire to be</small>
                        </div>
                    </div>
                    <p class="text-secondary mb-0"><?php echo nl2br(htmlspecialchars($cms->vision)); ?></p>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="py-5" style="background: #fffff0;">
    <div class="container-fluid px-lg-5">
        <div class="text-center mb-5">
            <span class="badge px-3 py-2 rounded-pill mb-3 fw-semibold" style="background: rgba(13,148,136,0.12); color: var(--accent);">Our Values</span>
            <h2 class="fw-bold display-6 mb-2" style="color: var(--primary);">Core Values</h2>
            <p class="text-secondary fs-5">The principles that guide our work and shape our impact.</p>
        </div>
        <div class="row g-4">
            <div class="col-12 col-md-3">
                <div class="card shadow-sm text-center h-100 rounded-4 hover-lift p-4" style="border: 2px solid var(--primary) !important;">
                    <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle" style="width: 72px; height: 72px; background: #fffff0;">
                        <?php if (!empty($cms->value_integrity_image)): ?>
                            <img src="<?php echo file_url($cms->value_integrity_image); ?>" style="height: 36px; object-fit: contain;">
                        <?php else: ?>
                            <i class="<?php echo htmlspecialchars($cms->value_integrity_icon ?? 'fas fa-shield-alt'); ?> fs-1" style="color: var(--primary);"></i>
                        <?php endif; ?>
                    </div>
                    <h5 class="fw-bold mb-1"><?php echo htmlspecialchars($cms->value_integrity_title ?? 'Integrity'); ?></h5>
                    <p class="text-secondary extra-small mb-0"><?php echo htmlspecialchars($cms->value_integrity_desc ?? 'Transparent and accountable.'); ?></p>
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="card shadow-sm text-center h-100 rounded-4 hover-lift p-4" style="border: 2px solid var(--accent) !important;">
                    <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle" style="width: 72px; height: 72px; background: #fffff0;">
                        <?php if (!empty($cms->value_compassion_image)): ?>
                            <img src="<?php echo file_url($cms->value_compassion_image); ?>" style="height: 36px; object-fit: contain;">
                        <?php else: ?>
                            <i class="<?php echo htmlspecialchars($cms->value_compassion_icon ?? 'fas fa-heart'); ?> fs-1" style="color: var(--accent);"></i>
                        <?php endif; ?>
                    </div>
                    <h5 class="fw-bold mb-1"><?php echo htmlspecialchars($cms->value_compassion_title ?? 'Compassion'); ?></h5>
                    <p class="text-secondary extra-small mb-0"><?php echo htmlspecialchars($cms->value_compassion_desc ?? 'Treating everyone with dignity.'); ?></p>
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="card shadow-sm text-center h-100 rounded-4 hover-lift p-4" style="border: 2px solid var(--accent) !important;">
                    <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle" style="width: 72px; height: 72px; background: #fffff0;">
                        <?php if (!empty($cms->value_innovation_image)): ?>
                            <img src="<?php echo file_url($cms->value_innovation_image); ?>" style="height: 36px; object-fit: contain;">
                        <?php else: ?>
                            <i class="<?php echo htmlspecialchars($cms->value_innovation_icon ?? 'fas fa-lightbulb'); ?> fs-1" style="color: var(--accent);"></i>
                        <?php endif; ?>
                    </div>
                    <h5 class="fw-bold mb-1"><?php echo htmlspecialchars($cms->value_innovation_title ?? 'Innovation'); ?></h5>
                    <p class="text-secondary extra-small mb-0"><?php echo htmlspecialchars($cms->value_innovation_desc ?? 'Creative solutions.'); ?></p>
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="card shadow-sm text-center h-100 rounded-4 hover-lift p-4" style="border: 2px solid #7c3aed !important;">
                    <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle" style="width: 72px; height: 72px; background: #fffff0;">
                        <?php if (!empty($cms->value_collaboration_image)): ?>
                            <img src="<?php echo file_url($cms->value_collaboration_image); ?>" style="height: 36px; object-fit: contain;">
                        <?php else: ?>
                            <i class="<?php echo htmlspecialchars($cms->value_collaboration_icon ?? 'fas fa-users'); ?> fs-1" style="color: #7c3aed;"></i>
                        <?php endif; ?>
                    </div>
                    <h5 class="fw-bold mb-1"><?php echo htmlspecialchars($cms->value_collaboration_title ?? 'Collaboration'); ?></h5>
                    <p class="text-secondary extra-small mb-0"><?php echo htmlspecialchars($cms->value_collaboration_desc ?? 'Working together.'); ?></p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 position-relative overflow-hidden text-center" style="background: var(--primary);">
    <div class="container-fluid px-lg-5">
        <h2 class="fw-bold display-6 text-white mb-3">Want to Make a Difference?</h2>
        <p class="text-white opacity-75 fs-5 mb-4 mx-auto" style="max-width: 600px;">Join us in our mission to create lasting change in communities around the world.</p>
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="<?php echo url('/contact'); ?>" class="btn btn-light rounded-pill px-5 py-2 d-inline-flex align-items-center justify-content-center fw-bold" style="color: var(--primary);">
                <i class="fas fa-handshake me-2"></i>Partner With Us
            </a>
            <a href="<?php echo url('/donate'); ?>" class="btn rounded-pill px-5 py-2 d-inline-flex align-items-center justify-content-center fw-bold" style="background: var(--accent); color: var(--primary); border: none;">
                <i class="fas fa-heart me-2"></i>Donate Now
            </a>
        </div>
    </div>
</section>



<?php require_once 'app/views/layouts/footer.php'; ?>
