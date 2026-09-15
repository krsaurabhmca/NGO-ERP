<?php require_once 'app/views/layouts/header.php'; ?>

<section class="position-relative overflow-hidden" style="background: linear-gradient(135deg, #003566 0%, #00224d 100%);">
    <div class="container-fluid px-lg-5 py-5 text-center position-relative" style="z-index: 1;">
        <h1 class="display-5 fw-bold text-white mb-0"><?php echo $title; ?></h1>
        <p class="text-white-50 mb-0">The people whose lives we have touched through our initiatives.</p>
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
        <?php if (!empty($beneficiaries)): ?>
            <div class="row g-4">
                <?php foreach ($beneficiaries as $b): ?>
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="card text-center p-4 h-100">
                            <div class="mb-3 mx-auto">
                                <?php if (!empty($b->photo)): ?>
                                    <img src="<?php echo file_url($b->photo); ?>" class="rounded-circle" alt="<?php echo htmlspecialchars($b->name); ?>" style="width: 90px; height: 90px; object-fit: cover; border: 3px solid #00356615;">
                                <?php else: ?>
                                    <img src="data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23003566'%3e%3ccircle cx='12' cy='8' r='4'/%3e%3cpath d='M4 20c0-4 3.6-7 8-7s8 3 8 7'/%3e%3c/svg%3e" class="rounded-circle" alt="Avatar" style="width: 90px; height: 90px; background: %23f1f5f9; padding: 15px; border: 3px solid #00356615;">
                                <?php endif; ?>
                            </div>
                            <h5 class="fw-bold mb-1 text-truncate"><?php echo htmlspecialchars($b->name); ?></h5>
                            <div class="d-flex flex-wrap justify-content-center gap-2 mt-2 mb-2">
                                <?php if (!empty($b->gender) && $b->gender !== 'not_specified'): ?>
                                    <span class="badge bg-info-lt text-info fw-normal" style="font-size: 0.7rem;">
                                        <i class="fas fa-<?php echo $b->gender === 'male' ? 'mars' : 'venus'; ?> me-1"></i><?php echo ucfirst($b->gender); ?>
                                    </span>
                                <?php endif; ?>
                                <?php if (!empty($b->assistance_type)): ?>
                                    <span class="badge bg-success-lt text-success fw-normal" style="font-size: 0.7rem;">
                                        <i class="fas fa-hands-helping me-1"></i><?php echo htmlspecialchars($b->assistance_type); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <?php if (!empty($b->date_of_birth)): ?>
                                <p class="text-muted mb-0" style="font-size: 0.72rem;">
                                    <i class="far fa-calendar-alt me-1"></i> <?php echo date('d M Y', strtotime($b->date_of_birth)); ?>
                                </p>
                            <?php endif; ?>
                            <div class="mt-auto pt-3">
                                <span class="badge px-3 py-2 rounded-pill fw-semibold" style="background: rgba(13,148,136,0.1); color: #0d9488; font-size: 0.72rem;">
                                    <i class="fas fa-heart me-1"></i> Beneficiary
                                </span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-hand-holding-heart fa-3x text-muted mb-3 opacity-25"></i>
                <p class="text-muted fs-5">No beneficiaries found at the moment.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once 'app/views/layouts/footer.php'; ?>
