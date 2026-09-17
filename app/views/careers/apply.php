<?php require_once 'app/views/layouts/header.php'; ?>

<section class="position-relative overflow-hidden" style="background-color: var(--primary); background-image: radial-gradient(circle at 10% 20%, var(--accent) 0%, transparent 50%), radial-gradient(circle at 90% 80%, var(--accent) 0%, transparent 50%), radial-gradient(circle at 50% 50%, var(--primary-dark) 0%, transparent 70%);">
    <div class="container-fluid px-lg-5 py-5 text-center position-relative" style="z-index: 1;">
        <h1 class="display-5 fw-bold text-white mb-0">Apply for <?php echo htmlspecialchars($career->title); ?></h1>
    </div>
    <div class="position-absolute top-0 end-0 opacity-10 hero-svg-circle">
        <svg width="400" height="400" viewBox="0 0 400 400" fill="none"><circle cx="300" cy="100" r="200" fill="var(--accent)"/><circle cx="100" cy="350" r="150" fill="var(--accent)"/></svg>
    </div>
    <div class="position-absolute bottom-0 start-0 opacity-10 hero-svg-circle">
        <svg width="300" height="300" viewBox="0 0 300 300" fill="none"><circle cx="50" cy="250" r="120" fill="var(--accent)"/></svg>
    </div>
</section>

<section class="py-5" style="background: #fffff0;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4"><i class="fas fa-check-circle me-2"></i> <?php echo e($_SESSION['success']); unset($_SESSION['success']); ?></div>
                <?php endif; ?>
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4"><i class="fas fa-exclamation-circle me-2"></i> <?php echo e($_SESSION['error']); unset($_SESSION['error']); ?></div>
                <?php endif; ?>

                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="p-4" style="background-color: var(--primary); background-image: radial-gradient(circle at 10% 20%, var(--accent) 0%, transparent 50%), radial-gradient(circle at 90% 80%, var(--accent) 0%, transparent 50%), radial-gradient(circle at 50% 50%, var(--primary-dark) 0%, transparent 70%);">
                        <div class="d-flex align-items-center text-white gap-3">
                            <div class="icon-circle" style="width: 48px; height: 48px; min-width: 48px; background: rgba(255,255,255,0.1);">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-0"><?php echo htmlspecialchars($career->title); ?></h5>
                                <small class="text-white-50">
                                    <i class="fas fa-map-marker-alt me-1"></i> <?php echo htmlspecialchars($career->location); ?>
                                    <span class="mx-2">·</span>
                                    <?php echo htmlspecialchars($career->job_type); ?>
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4 p-lg-5">
                        <form action="<?php echo url('careers/apply/' . $career->slug); ?>" method="POST" enctype="multipart/form-data">
                            <div class="row g-3">
                                <div class="col-lg-6">
                                    <label class="form-label small fw-semibold">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" required placeholder="Your full name">
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label small fw-semibold">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control" required placeholder="you@example.com">
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label small fw-semibold">Phone <span class="text-danger">*</span></label>
                                    <input type="tel" name="phone" class="form-control" required placeholder="+91 98765 43210">
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label small fw-semibold">Resume/CV <span class="text-danger">*</span></label>
                                    <input type="file" name="resume" class="form-control" accept=".pdf,.doc,.docx" required>
                                    <small class="form-text text-muted">Max 2MB (PDF, DOC, DOCX)</small>
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-semibold">Address <span class="text-danger">*</span></label>
                                    <textarea name="address" class="form-control" rows="1" required placeholder="Street, locality, house number"></textarea>
                                </div>
                                <div class="col-lg-3">
                                    <label class="form-label small fw-semibold">City <span class="text-danger">*</span></label>
                                    <input type="text" name="city" class="form-control" required placeholder="City">
                                </div>
                                <div class="col-lg-3">
                                    <label class="form-label small fw-semibold">District <span class="text-danger">*</span></label>
                                    <input type="text" name="district" class="form-control" required placeholder="District">
                                </div>
                                <div class="col-lg-3">
                                    <label class="form-label small fw-semibold">Pin Code <span class="text-danger">*</span></label>
                                    <input type="text" name="pincode" class="form-control" required placeholder="Pin code">
                                </div>
                                <div class="col-lg-3">
                                    <label class="form-label small fw-semibold">State <span class="text-danger">*</span></label>
                                    <input type="text" name="state" class="form-control" required placeholder="State">
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-semibold">Cover Letter</label>
                                    <textarea name="cover_letter" class="form-control" rows="4" placeholder="Tell us why you'd be a great fit..."></textarea>
                                </div>
                                <div class="col-12 text-center mt-2">
                                    <button type="submit" class="btn btn-accent btn-lg rounded-pill px-5 fw-bold shadow-sm">
                                        <i class="fas fa-paper-plane me-2"></i> Submit Application
                                    </button>
                                    <p class="text-muted small mt-2 mb-0">We will review your application and get back to you.</p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'app/views/layouts/footer.php'; ?>
