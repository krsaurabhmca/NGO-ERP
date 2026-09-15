<?php require_once 'app/views/layouts/header.php'; ?>

<section class="position-relative overflow-hidden" style="background: linear-gradient(135deg, #003566 0%, #00224d 100%);">
    <div class="container-fluid px-lg-5 py-5 text-center position-relative" style="z-index: 1;">
        <h1 class="display-5 fw-bold text-white mb-0"><?php echo htmlspecialchars($career->title); ?></h1>
    </div>
    <div class="position-absolute top-0 end-0 opacity-10 hero-svg-circle">
        <svg width="400" height="400" viewBox="0 0 400 400" fill="none"><circle cx="300" cy="100" r="200" fill="#FFBF00"/><circle cx="100" cy="350" r="150" fill="#0d9488"/></svg>
    </div>
    <div class="position-absolute bottom-0 start-0 opacity-10 hero-svg-circle">
        <svg width="300" height="300" viewBox="0 0 300 300" fill="none"><circle cx="50" cy="250" r="120" fill="#FFBF00"/></svg>
    </div>
</section>

<section class="py-5" style="background: #fffff0;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5">
                    <div class="row g-0 bg-white rounded-top-4">
                        <div class="col-sm-4 d-flex align-items-center justify-content-center p-4" style="background: linear-gradient(135deg, #00356608, #0d948808);">
                            <div class="text-center">
                                <div class="icon-circle mx-auto mb-2" style="width: 64px; height: 64px; background: rgba(0,53,102,0.1);">
                                    <i class="fas <?php echo $career->job_type === 'Internship' ? 'fa-graduation-cap' : 'fa-briefcase'; ?> fa-2x" style="color: #003566;"></i>
                                </div>
                                <span class="badge bg-primary-lt text-primary px-3 py-1 rounded-pill fw-semibold"><?php echo htmlspecialchars($career->job_type); ?></span>
                            </div>
                        </div>
                        <div class="col-sm-8 p-4 p-lg-5">
                            <div class="row g-3 text-muted" style="font-size: 0.9rem;">
                                <div class="col-6">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="text-center" style="min-width: 28px; color: #0d9488;"><i class="fas fa-map-marker-alt"></i></div>
                                        <div><small class="text-muted d-block">Location</small><span class="fw-semibold text-dark"><?php echo htmlspecialchars($career->location); ?></span></div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="text-center" style="min-width: 28px; color: #0d9488;"><i class="far fa-calendar-alt"></i></div>
                                        <div><small class="text-muted d-block">Deadline</small><span class="fw-semibold text-dark"><?php echo $career->deadline ? date('d M, Y', strtotime($career->deadline)) : 'No deadline'; ?></span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4 p-lg-5">
                        <h4 class="fw-bold mb-3" style="color: #003566;">Job Description & Requirements</h4>
                        <div class="lh-lg text-muted fs-5">
                            <?php echo nl2br(htmlspecialchars($career->description)); ?>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 p-4 p-lg-5" style="background: linear-gradient(135deg, #003566, #00224d);">
                    <div class="text-center text-white">
                        <h4 class="fw-bold mb-2">Ready to Apply?</h4>
                        <p class="text-white-50 mb-4">Send in your application and we will get back to you.</p>
                        <div class="d-flex justify-content-center gap-3 flex-wrap">
                            <a href="<?php echo url('careers/apply/' . $career->slug); ?>" class="btn btn-accent btn-lg rounded-pill px-5 fw-bold">
                                <i class="fas fa-paper-plane me-2"></i> Apply Now
                            </a>
                            <a href="<?php echo url('/careers'); ?>" class="btn btn-outline-light rounded-pill px-4">
                                <i class="fas fa-arrow-left me-1"></i> All Jobs
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'app/views/layouts/footer.php'; ?>
