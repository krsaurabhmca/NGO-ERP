<?php require_once 'app/views/layouts/header.php'; ?>

<section class="position-relative overflow-hidden" style="background-color: var(--primary); background-image: radial-gradient(circle at 10% 20%, var(--accent) 0%, transparent 50%), radial-gradient(circle at 90% 80%, var(--accent) 0%, transparent 50%), radial-gradient(circle at 50% 50%, var(--primary-dark) 0%, transparent 70%);">
    <div class="container-fluid px-3 px-lg-5 py-4 py-lg-5 text-center position-relative" style="z-index: 1;">
        <h1 class="display-6 display-lg-5 fw-bold text-white mb-0"><?php echo $title; ?></h1>
        <p class="text-white-50 mb-0 small">We'd love to hear from you. Get in touch with us.</p>
    </div>
    <div class="position-absolute top-0 end-0 opacity-10 hero-svg-circle d-none d-lg-block">
        <svg width="400" height="400" viewBox="0 0 400 400" fill="none"><circle cx="300" cy="100" r="200" fill="var(--accent)"/><circle cx="100" cy="350" r="150" fill="var(--accent)"/></svg>
    </div>
    <div class="position-absolute bottom-0 start-0 opacity-10 hero-svg-circle d-none d-lg-block">
        <svg width="300" height="300" viewBox="0 0 300 300" fill="none"><circle cx="50" cy="250" r="120" fill="var(--accent)"/></svg>
    </div>
</section>

<section class="py-4 py-lg-5" style="background: #fffff0;">
    <div class="container">
        <?php if (($globalSettings['contact_page_status'] ?? 'active') === 'inactive'): ?>
            <div class="row justify-content-center py-5">
                <div class="col-md-8 col-lg-6 text-center">
                    <div class="p-4 p-lg-5 bg-white rounded-4 shadow-sm">
                        <div class="icon-circle mx-auto mb-3" style="width: 70px; height: 70px; background: rgba(220,53,69,0.1);">
                            <i class="fas fa-comment-slash text-danger fa-2x"></i>
                        </div>
                        <h2 class="fw-bold mb-3">Contact Form Unavailable</h2>
                        <p class="text-muted mb-0 small">Our contact form is temporarily disabled. Please reach out via phone or visit our office.</p>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success border-0 shadow-sm rounded-3 mb-3"><i class="fas fa-check-circle me-2"></i> <?php echo e($_SESSION['success']); unset($_SESSION['success']); ?></div>
            <?php endif; ?>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-3"><i class="fas fa-exclamation-circle me-2"></i> <?php echo e($_SESSION['error']); unset($_SESSION['error']); ?></div>
            <?php endif; ?>

            <div class="row g-3 g-lg-4">
                <div class="col-lg-5">
                    <div class="card border-0 shadow-sm rounded-4 p-3 p-lg-4 h-100">
                        <h3 class="fw-bold mb-3" style="color: var(--primary); font-size: 1.2rem;">Get in Touch</h3>
                        <p class="text-muted small mb-3">Have questions or want to support our mission? Reach out through any channel below.</p>

                        <?php if (!empty($globalSettings['ngo_address'])): ?>
                        <div class="d-flex gap-3 mb-3">
                            <div class="icon-circle flex-shrink-0" style="width: 42px; height: 42px; min-width: 42px; background: rgba(0,53,102,0.1);">
                                <i class="fas fa-map-marker-alt" style="color: var(--primary); font-size: 1rem;"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1" style="font-size: 0.8rem;">Our Location</h6>
                                <p class="text-muted mb-0" style="font-size: 0.8rem;"><?php echo htmlspecialchars($globalSettings['ngo_address']); ?></p>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($globalSettings['ngo_phone'])): ?>
                        <div class="d-flex gap-3 mb-3">
                            <div class="icon-circle flex-shrink-0" style="width: 42px; height: 42px; min-width: 42px; background: rgba(13,148,136,0.1);">
                                <i class="fas fa-phone" style="color: var(--accent); font-size: 1rem;"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1" style="font-size: 0.8rem;">Phone Number</h6>
                                <p class="text-muted mb-0" style="font-size: 0.8rem;"><?php echo htmlspecialchars($globalSettings['ngo_phone']); ?></p>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($globalSettings['ngo_email'])): ?>
                        <div class="d-flex gap-3 mb-3">
                            <div class="icon-circle flex-shrink-0" style="width: 42px; height: 42px; min-width: 42px; background: rgba(255,191,0,0.15);">
                                <i class="fas fa-envelope" style="color: var(--accent); font-size: 1rem;"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1" style="font-size: 0.8rem;">Email Address</h6>
                                <p class="text-muted mb-0" style="font-size: 0.8rem;"><?php echo htmlspecialchars($globalSettings['ngo_email']); ?></p>
                            </div>
                        </div>
                        <?php endif; ?>

                        <div class="pt-3 border-top mt-auto">
                            <h6 class="fw-bold mb-2" style="font-size: 0.8rem;">Follow Us</h6>
                            <div class="d-flex gap-2 flex-wrap">
                                <?php if (!empty($globalSettings['social_facebook'])): ?>
                                    <a href="<?php echo htmlspecialchars($globalSettings['social_facebook']); ?>" target="_blank" style="width: 36px; height: 36px; display:inline-flex; align-items:center; justify-content:center; border-radius:50%; background:#1877f2; color:#fff; text-decoration:none; transition:opacity 0.2s;" onmouseover="this.style.opacity=0.85" onmouseout="this.style.opacity=1"><i class="fab fa-facebook-f" style="font-size:0.85rem;"></i></a>
                                <?php endif; ?>
                                <?php if (!empty($globalSettings['social_twitter'])): ?>
                                    <a href="<?php echo htmlspecialchars($globalSettings['social_twitter']); ?>" target="_blank" style="width: 36px; height: 36px; display:inline-flex; align-items:center; justify-content:center; border-radius:50%; background:#000; color:#fff; text-decoration:none; transition:opacity 0.2s;" onmouseover="this.style.opacity=0.85" onmouseout="this.style.opacity=1"><i class="fab fa-twitter" style="font-size:0.85rem;"></i></a>
                                <?php endif; ?>
                                <?php if (!empty($globalSettings['social_instagram'])): ?>
                                    <a href="<?php echo htmlspecialchars($globalSettings['social_instagram']); ?>" target="_blank" style="width: 36px; height: 36px; display:inline-flex; align-items:center; justify-content:center; border-radius:50%; background-color: var(--accent); color:#fff; text-decoration:none; transition:opacity 0.2s;" onmouseover="this.style.opacity=0.85" onmouseout="this.style.opacity=1"><i class="fab fa-instagram" style="font-size:0.85rem;"></i></a>
                                <?php endif; ?>
                                <?php if (!empty($globalSettings['social_linkedin'])): ?>
                                    <a href="<?php echo htmlspecialchars($globalSettings['social_linkedin']); ?>" target="_blank" style="width: 36px; height: 36px; display:inline-flex; align-items:center; justify-content:center; border-radius:50%; background:#0a66c2; color:#fff; text-decoration:none; transition:opacity 0.2s;" onmouseover="this.style.opacity=0.85" onmouseout="this.style.opacity=1"><i class="fab fa-linkedin-in" style="font-size:0.85rem;"></i></a>
                                <?php endif; ?>
                                <?php if (!empty($globalSettings['social_youtube'])): ?>
                                    <a href="<?php echo htmlspecialchars($globalSettings['social_youtube']); ?>" target="_blank" style="width: 36px; height: 36px; display:inline-flex; align-items:center; justify-content:center; border-radius:50%; background:#ff0000; color:#fff; text-decoration:none; transition:opacity 0.2s;" onmouseover="this.style.opacity=0.85" onmouseout="this.style.opacity=1"><i class="fab fa-youtube" style="font-size:0.85rem;"></i></a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                        <div class="p-3 p-lg-4 text-white" style="background-color: var(--primary); background-image: radial-gradient(circle at 10% 20%, var(--accent) 0%, transparent 50%), radial-gradient(circle at 90% 80%, var(--accent) 0%, transparent 50%), radial-gradient(circle at 50% 50%, var(--primary-dark) 0%, transparent 70%);">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fas fa-paper-plane" style="color: var(--accent);"></i>
                                <span class="fw-semibold small">Send us a message</span>
                            </div>
                        </div>
                        <div class="card-body p-3 p-lg-4">
                            <form action="<?php echo url('/contact/submit'); ?>" method="POST">
                                <div class="row g-2 g-lg-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold" style="font-size: 0.8rem;">Your Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control form-control-sm" placeholder="John Doe" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold" style="font-size: 0.8rem;">Email Address <span class="text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control form-control-sm" placeholder="john@example.com" required>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-semibold" style="font-size: 0.8rem;">Subject <span class="text-danger">*</span></label>
                                        <input type="text" name="subject" class="form-control form-control-sm" placeholder="How can we help?" required>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-semibold" style="font-size: 0.8rem;">Your Message <span class="text-danger">*</span></label>
                                        <textarea name="message" class="form-control form-control-sm" rows="4" placeholder="Write your message here..." required></textarea>
                                    </div>
                                    <div class="col-12 mt-3">
                                        <button type="submit" class="btn btn-accent rounded-pill px-4 px-lg-5 py-2 fw-bold" style="font-size: 0.85rem;">
                                            <i class="fas fa-paper-plane me-2"></i> Send Message
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <?php if (($globalSettings['show_google_map'] ?? 'yes') === 'yes'): ?>
                <div class="row mt-4 mt-lg-5">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm overflow-hidden rounded-4">
                            <div class="card-body p-0">
                                <?php if (!empty($globalSettings['ngo_map_embed'])): ?>
                                    <?php echo $globalSettings['ngo_map_embed']; ?>
                                <?php else: ?>
                                    <div class="d-flex flex-column align-items-center justify-content-center text-muted p-5" style="min-height: 300px; background: #f8f9fa;">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#adb5bd" stroke-width="1.5" width="64" height="64">
                                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                            <circle cx="12" cy="10" r="3"/>
                                        </svg>
                                        <p class="mt-3 mb-0">Map not configured. Add your map embed in Settings.</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>

<?php require_once 'app/views/layouts/footer.php'; ?>
