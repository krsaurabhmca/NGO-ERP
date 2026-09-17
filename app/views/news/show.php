<?php require_once 'app/views/layouts/header.php'; ?>

<section class="position-relative overflow-hidden" style="background-color: var(--primary); background-image: radial-gradient(circle at 10% 20%, var(--accent) 0%, transparent 50%), radial-gradient(circle at 90% 80%, var(--accent) 0%, transparent 50%), radial-gradient(circle at 50% 50%, var(--primary-dark) 0%, transparent 70%);">
    <div class="container-fluid px-lg-5 py-5 text-center position-relative" style="z-index: 1;">
        <h1 class="display-5 fw-bold text-white mb-0"><?php echo htmlspecialchars($news->title); ?></h1>
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
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <?php if ($news->image): ?>
                        <img src="<?php echo file_url($news->image); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($news->title); ?>" style="max-height: 420px; object-fit: cover;">
                    <?php endif; ?>
                    <div class="card-body p-4 p-lg-5">
                        <div class="d-flex flex-wrap align-items-center text-muted small mb-4 gap-3 pb-3 border-bottom">
                            <span><i class="far fa-calendar-alt me-1" style="color: var(--accent);"></i> <?php echo date('d F, Y', strtotime($news->created_at)); ?></span>
                            <span><i class="far fa-building me-1" style="color: var(--accent);"></i> Published by <?php echo !empty($globalSettings['ngo_name']) ? htmlspecialchars($globalSettings['ngo_name']) : 'NGO HELP'; ?></span>
                        </div>

                        <div class="fs-5 lh-lg text-dark" style="text-align: justify;">
                            <?php echo nl2br(htmlspecialchars($news->content)); ?>
                        </div>

                        <hr class="my-5">

                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                            <a href="<?php echo url('/news'); ?>" class="btn btn-outline-secondary rounded-pill px-4">
                                <i class="fas fa-arrow-left me-2"></i> Back to All News
                            </a>
                            <div class="d-flex align-items-center gap-2">
                                <span class="text-muted small fw-semibold me-1">Share:</span>
                                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(url('news/' . $news->slug)); ?>" target="_blank" class="btn btn-sm btn-outline-primary rounded-circle" style="width: 34px; height: 34px;"><i class="fab fa-facebook-f"></i></a>
                                <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode($news->title . ' - ' . url('news/' . $news->slug)); ?>" target="_blank" class="btn btn-sm btn-outline-info rounded-circle" style="width: 34px; height: 34px;"><i class="fab fa-twitter"></i></a>
                                <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode(url('news/' . $news->slug)); ?>" target="_blank" class="btn btn-sm btn-outline-secondary rounded-circle" style="width: 34px; height: 34px;"><i class="fab fa-linkedin-in"></i></a>
                                <a href="https://wa.me/?text=<?php echo urlencode($news->title . ' - ' . url('news/' . $news->slug)); ?>" target="_blank" class="btn btn-sm btn-outline-success rounded-circle" style="width: 34px; height: 34px;"><i class="fab fa-whatsapp"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'app/views/layouts/footer.php'; ?>
