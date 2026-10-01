<?php require_once 'app/views/layouts/header.php'; ?>

<section class="position-relative overflow-hidden" style="background-color: var(--primary); background-image: radial-gradient(circle at 10% 20%, var(--accent) 0%, transparent 50%), radial-gradient(circle at 90% 80%, var(--accent) 0%, transparent 50%), radial-gradient(circle at 50% 50%, var(--primary-dark) 0%, transparent 70%);">
    <div class="container-fluid px-lg-5 py-5 text-center position-relative" style="z-index: 1;">
        <h1 class="display-5 fw-bold text-white mb-0"><?php echo $title; ?></h1>
        <p class="text-white-50 mb-0">Important legal and compliance documents of our organization.</p>
    </div>
    <div class="position-absolute top-0 end-0 opacity-10 hero-svg-circle">
        <svg width="400" height="400" viewBox="0 0 400 400" fill="none"><circle cx="300" cy="100" r="200" fill="var(--accent)"/><circle cx="100" cy="350" r="150" fill="var(--accent)"/></svg>
    </div>
    <div class="position-absolute bottom-0 start-0 opacity-10 hero-svg-circle">
        <svg width="300" height="300" viewBox="0 0 300 300" fill="none"><circle cx="50" cy="250" r="120" fill="var(--accent)"/></svg>
    </div>
</section>

<section class="py-5" style="background: #f8f9fa;">
    <div class="container">
        <?php if (!empty($media)): ?>
            <div class="row g-4 justify-content-center">
                <?php foreach ($media as $item): ?>
                    <?php if (($item->status ?? 'active') === 'active'): ?>
                    <div class="col-sm-6 col-md-4 col-lg-3">
                        <div class="card h-100 shadow-sm border-0 rounded-4 hover-lift" style="transition: transform 0.2s ease, box-shadow 0.2s ease;">
                            <div class="card-body text-center p-4 d-flex flex-column align-items-center justify-content-center">
                                <?php if (in_array(strtolower(pathinfo($item->file_path, PATHINFO_EXTENSION)), ['pdf', 'doc', 'docx'])): ?>
                                    <div class="mb-3 d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 80px; height: 80px; background-color: rgba(0, 53, 102, 0.1);">
                                        <i class="fas fa-file-pdf fa-3x" style="color: var(--primary);"></i>
                                    </div>
                                <?php else: ?>
                                    <div class="mb-3" style="height: 80px;">
                                        <img src="<?php echo file_url($item->file_path); ?>" class="img-fluid rounded" style="max-height: 100%; object-fit: contain;">
                                    </div>
                                <?php endif; ?>
                                
                                <h5 class="card-title fw-bold mb-2" style="font-size: 1.1rem; color: #333;"><?php echo htmlspecialchars($item->title); ?></h5>
                                <?php if(!empty($item->description)): ?>
                                    <p class="text-muted small mb-3"><?php echo htmlspecialchars($item->description); ?></p>
                                <?php endif; ?>
                                
                                <div class="mt-auto pt-3">
                                    <a href="<?php echo file_url($item->file_path); ?>" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill px-4 fw-medium">
                                        <i class="fas fa-download me-1"></i> Download
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-folder-open fa-4x text-muted mb-3 opacity-25"></i>
                <p class="text-muted fs-5">No documents available yet.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<style>
.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
}
</style>

<?php require_once 'app/views/layouts/footer.php'; ?>
