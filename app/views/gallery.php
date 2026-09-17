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
    <div class="container-fluid px-lg-5">
        <?php if (!empty($gallery)): ?>
            <div class="row g-3">
                <?php foreach ($gallery as $idx => $item): ?>
                    <?php if ($item->status === 'active'): ?>
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="card border-0 shadow-sm overflow-hidden h-100 hover-lift gallery-item" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#galleryModal" data-src="<?php echo file_url($item->file_path); ?>" data-title="<?php echo htmlspecialchars($item->title); ?>" data-desc="<?php echo htmlspecialchars($item->description ?? ''); ?>">
                            <div class="position-relative overflow-hidden" style="aspect-ratio: 1 / 1;">
                                <img src="<?php echo file_url($item->file_path); ?>" class="w-100 h-100" alt="<?php echo htmlspecialchars($item->title); ?>" style="object-fit: cover; transition: transform 0.4s ease;">
                                <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" style="background: rgba(0,53,102,0); transition: background 0.3s ease;">
                                    <div class="icon-circle bg-white shadow-sm" style="width: 48px; height: 48px; opacity: 0; transform: scale(0.5); transition: all 0.3s ease;">
                                        <i class="fas fa-search-plus text-primary"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <!-- Lightbox Modal -->
            <div class="modal fade" id="galleryModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-fullscreen-md-down modal-xl modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg" style="background: #0f172a;">
                        <div class="modal-header border-0 pb-0" style="background: transparent;">
                            <div class="text-white">
                                <h5 class="modal-title fw-bold" id="galleryModalTitle"></h5>
                                <small class="text-white-50" id="galleryModalDesc"></small>
                            </div>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body d-flex align-items-center justify-content-center p-3" style="min-height: 60vh;">
                            <img id="galleryModalImg" src="" class="img-fluid rounded-3" alt="" style="max-height: 75vh; object-fit: contain;">
                        </div>
                        <div class="modal-footer border-0 pt-0 justify-content-center">
                            <button type="button" class="btn btn-sm btn-outline-light rounded-pill px-4" data-bs-dismiss="modal">
                                <i class="fas fa-times me-1"></i> Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <i class="fas fa-images fa-3x text-muted mb-3 opacity-25"></i>
                <p class="text-muted fs-5">No images in our gallery yet.</p>
            </div>
        <?php endif; ?>
    </div>
</section>



<script>
document.addEventListener('DOMContentLoaded', function() {
    var modal = document.getElementById('galleryModal');
    if (modal) {
        modal.addEventListener('show.bs.modal', function(e) {
            var btn = e.relatedTarget;
            var src = btn.getAttribute('data-src');
            var title = btn.getAttribute('data-title');
            var desc = btn.getAttribute('data-desc');
            document.getElementById('galleryModalImg').src = src;
            document.getElementById('galleryModalTitle').textContent = title || 'Gallery Image';
            document.getElementById('galleryModalDesc').textContent = desc || '';
        });
        modal.addEventListener('hidden.bs.modal', function() {
            document.getElementById('galleryModalImg').src = '';
        });
    }
});
</script>

<?php require_once 'app/views/layouts/footer.php'; ?>
