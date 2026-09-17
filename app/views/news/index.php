<?php require_once 'app/views/layouts/header.php'; ?>

<section class="position-relative overflow-hidden" style="background-color: var(--primary); background-image: radial-gradient(circle at 10% 20%, var(--accent) 0%, transparent 50%), radial-gradient(circle at 90% 80%, var(--accent) 0%, transparent 50%), radial-gradient(circle at 50% 50%, var(--primary-dark) 0%, transparent 70%);">
    <div class="container-fluid px-lg-5 py-5 text-center position-relative" style="z-index: 1;">
        <h1 class="display-5 fw-bold text-white mb-0"><?php echo $title; ?></h1>
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
        <?php if (!empty($news)): ?>
            <div class="row g-4">
                <?php foreach ($news as $item): ?>
                    <div class="col-md-6 col-lg-4">
                        <a href="<?php echo url('news/' . $item->slug); ?>" class="text-decoration-none">
                            <div class="card h-100 border-0 shadow-sm overflow-hidden hover-lift">
                                <?php if ($item->image): ?>
                                    <div class="position-relative overflow-hidden" style="height: 220px;">
                                        <img src="<?php echo file_url($item->image); ?>" class="w-100 h-100" alt="<?php echo htmlspecialchars($item->title); ?>" style="object-fit: cover; transition: transform 0.4s ease;">
                                    </div>
                                <?php else: ?>
                                    <div class="d-flex align-items-center justify-content-center" style="height: 220px; background-color: var(--slate-100); border: 2px solid var(--primary);">
                                        <i class="fas fa-newspaper fa-3x opacity-25" style="color: var(--primary);"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="card-body p-4 d-flex flex-column">
                                    <div class="d-flex align-items-center text-muted small mb-2 gap-3">
                                        <span><i class="far fa-calendar-alt me-1" style="color: var(--accent);"></i> <?php echo date('d M Y', strtotime($item->created_at)); ?></span>
                                        <span><i class="far fa-building me-1" style="color: var(--accent);"></i> <?php echo !empty($globalSettings['ngo_name']) ? htmlspecialchars($globalSettings['ngo_name']) : 'NGO HELP'; ?></span>
                                    </div>
                                    <h3 class="fw-bold h5 mb-2 text-dark">
                                        <?php echo htmlspecialchars($item->title); ?>
                                    </h3>
                                    <p class="text-muted small mb-0 flex-grow-1">
                                        <?php
                                            $excerpt = strip_tags($item->content);
                                            echo strlen($excerpt) > 120 ? substr($excerpt, 0, 120) . '...' : $excerpt;
                                        ?>
                                    </p>
                                    <div class="mt-3 pt-3 border-top d-flex align-items-center" style="color: var(--accent); font-weight: 600; font-size: 0.85rem;">
                                        Read More <i class="fas fa-arrow-right ms-2"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-newspaper fa-3x text-muted mb-3 opacity-25"></i>
                <p class="text-muted fs-5">No news articles available at the moment.</p>
            </div>
        <?php endif; ?>
    </div>
</section>



<?php require_once 'app/views/layouts/footer.php'; ?>
