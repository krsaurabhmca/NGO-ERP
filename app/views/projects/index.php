<?php require_once 'app/views/layouts/header.php'; ?>

<section class="position-relative overflow-hidden" style="background-color: var(--primary); background-image: radial-gradient(circle at 10% 20%, var(--accent) 0%, transparent 50%), radial-gradient(circle at 90% 80%, var(--accent) 0%, transparent 50%), radial-gradient(circle at 50% 50%, var(--primary-dark) 0%, transparent 70%);">
    <div class="container-fluid px-lg-5 py-5 text-center position-relative" style="z-index: 1;">
        <h1 class="display-5 fw-bold text-white mb-0"><?php echo $title; ?></h1>
        <p class="text-white-50 mb-0">Explore our initiatives making real impact on the ground.</p>
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
        <div class="d-flex justify-content-center mb-4">
            <div class="btn-group shadow-sm rounded-pill overflow-hidden" role="group">
                <a href="<?php echo url('projects?status=active'); ?>" class="btn <?php echo ($currentStatus ?? 'active') === 'active' ? 'btn-primary' : 'btn-outline-secondary'; ?> px-4" style="border-radius: 50rem 0 0 50rem;">
                    <i class="fas fa-spinner me-1"></i> Active
                </a>
                <a href="<?php echo url('projects?status=completed'); ?>" class="btn <?php echo ($currentStatus ?? '') === 'completed' ? 'btn-success' : 'btn-outline-secondary'; ?> px-4" style="border-radius: 0;">
                    <i class="fas fa-check me-1"></i> Completed
                </a>
                <a href="<?php echo url('projects?status=all'); ?>" class="btn <?php echo ($currentStatus ?? '') === 'all' ? 'btn-dark' : 'btn-outline-secondary'; ?> px-4" style="border-radius: 0 50rem 50rem 0;">
                    <i class="fas fa-th-large me-1"></i> All
                </a>
            </div>
        </div>

        <?php if (!empty($projects)): ?>
            <div class="row g-4">
                <?php foreach ($projects as $item): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm overflow-hidden hover-lift rounded-4">
                            <div class="position-relative overflow-hidden" style="height: 220px;">
                                <?php if ($item->image): ?>
                                    <img src="<?php echo file_url($item->image); ?>" class="w-100 h-100" alt="<?php echo htmlspecialchars($item->title); ?>" style="object-fit: cover; transition: transform 0.4s ease;">
                                <?php else: ?>
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center" style="background-color: var(--slate-100); border: 2px solid var(--primary);">
                                        <i class="fas fa-project-diagram fa-3x opacity-25" style="color: var(--primary);"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="position-absolute top-0 start-0 m-3">
                                    <span class="badge px-3 py-2 rounded-pill shadow-sm fw-semibold" style="<?php echo $item->status === 'completed' ? 'background: #2ecc71;' : ($item->status === 'ongoing' ? 'background: var(--primary);' : 'background: var(--accent); color: var(--primary);'); ?> font-size: 0.72rem;">
                                        <?php echo $item->status === 'ongoing' ? '<i class="fas fa-spinner me-1"></i>' : ($item->status === 'completed' ? '<i class="fas fa-check me-1"></i>' : '<i class="far fa-clock me-1"></i>'); ?><?php echo ucfirst($item->status); ?>
                                    </span>
                                </div>
                            </div>
                            <div class="card-body p-4 d-flex flex-column">
                                <h3 class="fw-bold h5 mb-2">
                                    <a href="<?php echo url('projects/' . $item->slug); ?>" class="text-decoration-none text-dark">
                                        <?php echo htmlspecialchars($item->title); ?>
                                    </a>
                                </h3>
                                <p class="text-muted small mb-3 flex-grow-1">
                                    <?php 
                                        $excerpt = strip_tags($item->description);
                                        echo strlen($excerpt) > 120 ? substr($excerpt, 0, 120) . '...' : $excerpt;
                                    ?>
                                </p>
                                <div class="d-flex align-items-center text-muted gap-3 mb-3" style="font-size: 0.78rem;">
                                    <span><i class="far fa-calendar-alt me-1" style="color: var(--accent);"></i> <?php echo date('M Y', strtotime($item->start_date)); ?></span>
                                    <?php if($item->end_date): ?>
                                        <span class="text-muted">→</span>
                                        <span><i class="fas fa-flag-checkered me-1" style="color: var(--accent);"></i> <?php echo date('M Y', strtotime($item->end_date)); ?></span>
                                    <?php endif; ?>
                                </div>
                                <a href="<?php echo url('projects/' . $item->slug); ?>" class="btn btn-accent w-100 rounded-pill fw-semibold">
                                    View Details <i class="fas fa-arrow-right ms-2 small"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-project-diagram fa-3x text-muted mb-3 opacity-25"></i>
                <p class="text-muted fs-5">No active projects available at the moment.</p>
            </div>
        <?php endif; ?>
    </div>
</section>



<?php require_once 'app/views/layouts/footer.php'; ?>
