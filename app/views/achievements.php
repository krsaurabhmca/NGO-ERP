<?php require_once 'app/views/layouts/header.php'; ?>

<section class="position-relative overflow-hidden" style="background: linear-gradient(135deg, #003566 0%, #00224d 100%);">
    <div class="container-fluid px-lg-5 py-5 text-center position-relative" style="z-index: 1;">
        <h1 class="display-5 fw-bold text-white mb-0"><?php echo $title; ?></h1>
        <p class="text-white-50 mb-0">Milestones and accomplishments that drive us forward.</p>
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
        <?php if (!empty($media)): ?>
            <div class="row g-4">
                <?php foreach ($media as $item): ?>
                    <?php if ($item->status === 'active'): ?>
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="card border-0 shadow-sm h-100 overflow-hidden hover-lift rounded-4" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#achieveModal" data-src="<?php echo file_url($item->file_path); ?>" data-title="<?php echo htmlspecialchars($item->title); ?>">
                            <div class="d-flex align-items-center justify-content-center bg-white" style="height: 260px; overflow: hidden;">
                                <img src="<?php echo file_url($item->file_path); ?>" alt="<?php echo htmlspecialchars($item->title); ?>" class="img-fluid p-4" style="max-height: 100%; max-width: 100%; object-fit: contain;">
                            </div>
                            <?php if (!empty($item->title)): ?>
                            <div class="card-body p-3 text-center border-top" style="background: linear-gradient(135deg, #00356608, #0d948808);">
                                <p class="card-text fw-bold mb-0" style="font-size: 0.82rem; color: #003566;">
                                    <i class="fas fa-trophy me-1" style="color: #FFBF00;"></i>
                                    <?php echo htmlspecialchars($item->title); ?>
                                </p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <!-- Lightbox Modal -->
            <div class="modal fade" id="achieveModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-fullscreen-md-down modal-lg modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg" style="background: #0f172a;">
                        <div class="modal-header border-0 pb-0">
                            <h5 class="modal-title text-white fw-bold" id="achieveModalTitle"></h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body d-flex align-items-center justify-content-center p-4" style="min-height: 50vh;">
                            <img id="achieveModalImg" src="" class="img-fluid rounded-3" alt="" style="max-height: 75vh; object-fit: contain;">
                        </div>
                        <div class="modal-footer border-0 pt-0 justify-content-center">
                            <button type="button" class="btn btn-sm btn-outline-light rounded-pill px-4" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-trophy fa-3x text-muted mb-3 opacity-25"></i>
                <p class="text-muted fs-5">No achievements available yet.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var modal = document.getElementById('achieveModal');
    if (modal) {
        modal.addEventListener('show.bs.modal', function(e) {
            var btn = e.relatedTarget;
            document.getElementById('achieveModalImg').src = btn.getAttribute('data-src');
            document.getElementById('achieveModalTitle').textContent = btn.getAttribute('data-title') || 'Achievement';
        });
        modal.addEventListener('hidden.bs.modal', function() {
            document.getElementById('achieveModalImg').src = '';
        });
    }
});
</script>

<?php require_once 'app/views/layouts/footer.php'; ?>
