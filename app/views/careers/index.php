<?php require_once 'app/views/layouts/header.php'; ?>

<section class="position-relative overflow-hidden" style="background-color: var(--primary); background-image: radial-gradient(circle at 10% 20%, var(--accent) 0%, transparent 50%), radial-gradient(circle at 90% 80%, var(--accent) 0%, transparent 50%), radial-gradient(circle at 50% 50%, var(--primary-dark) 0%, transparent 70%);">
    <div class="container-fluid px-lg-5 py-5 text-center position-relative" style="z-index: 1;">
        <h1 class="display-5 fw-bold text-white mb-0"><?php echo $title; ?></h1>
        <p class="text-white-50 mb-0">Join our team and help us create meaningful impact.</p>
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
            <div class="col-lg-10">
                <?php if (!empty($careers)): ?>
                    <div class="row g-4">
                        <?php foreach ($careers as $item): ?>
                            <div class="col-12">
                                <div class="card border-0 shadow-sm hover-lift rounded-4 overflow-hidden">
                                    <div class="row g-0 align-items-stretch">
                                        <div class="col-md-auto d-flex align-items-center justify-content-center p-4" style="background-color: var(--slate-100); border-left: 4px solid var(--primary); min-width: 120px;">
                                            <div class="text-center">
                                                <div class="icon-circle mx-auto mb-2" style="width: 52px; height: 52px; background: <?php echo $item->job_type === 'Internship' ? 'rgba(13,148,136,0.12)' : 'rgba(0,53,102,0.1)'; ?>;">
                                                    <i class="fas <?php echo $item->job_type === 'Internship' ? 'fa-graduation-cap' : 'fa-briefcase'; ?>" style="color: <?php echo $item->job_type === 'Internship' ? 'var(--accent)' : 'var(--primary)'; ?>; font-size: 1.2rem;"></i>
                                                </div>
                                                <span class="badge <?php echo $item->job_type === 'Internship' ? 'bg-success-lt text-success' : 'bg-primary-lt text-primary'; ?> px-3 py-1 rounded-pill fw-semibold" style="font-size: 0.7rem;">
                                                    <?php echo htmlspecialchars($item->job_type); ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col p-4">
                                            <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                                <span class="text-muted" style="font-size: 0.78rem;">
                                                    <i class="fas fa-map-marker-alt me-1" style="color: var(--accent);"></i> <?php echo htmlspecialchars($item->location); ?>
                                                </span>
                                                <?php if($item->deadline): ?>
                                                    <span class="text-muted" style="font-size: 0.78rem;">
                                                        <span class="mx-2">·</span>
                                                        <i class="far fa-clock me-1"></i> Apply by <?php echo date('d M, Y', strtotime($item->deadline)); ?>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                            <h3 class="fw-bold h5 mb-2">
                                                <a href="<?php echo url('careers/' . $item->slug); ?>" class="text-decoration-none text-dark">
                                                    <?php echo htmlspecialchars($item->title); ?>
                                                </a>
                                            </h3>
                                            <p class="text-muted small mb-0">
                                                <?php echo htmlspecialchars(substr($item->description ?? '', 0, 120)); ?>...
                                            </p>
                                        </div>
                                        <div class="col-md-auto d-flex flex-column justify-content-center gap-2 p-4 border-start">
                                            <a href="<?php echo url('careers/' . $item->slug); ?>" class="btn btn-outline-secondary rounded-pill px-4" style="font-size: 0.85rem;">
                                                <i class="fas fa-eye me-1"></i> Details
                                            </a>
                                            <a href="<?php echo url('careers/apply/' . $item->slug); ?>" class="btn btn-accent rounded-pill px-4" style="font-size: 0.85rem;">
                                                <i class="fas fa-paper-plane me-1"></i> Apply
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5 bg-white rounded-4 shadow-sm">
                        <div class="icon-circle bg-primary-lt mx-auto mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-briefcase fa-2x text-primary opacity-50"></i>
                        </div>
                        <h4 class="fw-bold text-dark">No Open Positions</h4>
                        <p class="text-muted mb-0">We currently don't have any open positions. Please check back later.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php require_once 'app/views/layouts/footer.php'; ?>
