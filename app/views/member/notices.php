<?php require_once 'app/views/admin/layouts/header.php'; ?>
<?php require_once 'app/views/admin/layouts/sidebar.php'; ?>
<?php require_once 'app/views/admin/layouts/topbar.php'; ?>

<div class="page-wrapper">
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">Notices</h2>
                    <div class="text-muted mt-1">Latest announcements & updates</div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <?php if (!empty($notices)): ?>
                <div class="row g-3">
                    <?php foreach ($notices as $item): ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <?php if ($item->image): ?>
                                    <div style="height: 180px; overflow: hidden; border-radius: var(--tblr-card-border-radius) var(--tblr-card-border-radius) 0 0;">
                                        <img src="<?php echo file_url($item->image); ?>" class="w-100 h-100" alt="<?php echo htmlspecialchars($item->title); ?>" style="object-fit: cover;">
                                    </div>
                                <?php endif; ?>
                                <div class="card-body d-flex flex-column">
                                    <div class="text-muted small mb-2">
                                        <i class="ti ti-calendar me-1"></i> <?php echo date('d M Y', strtotime($item->created_at)); ?>
                                    </div>
                                    <h4 class="card-title mb-2"><?php echo htmlspecialchars($item->title); ?></h4>
                                    <p class="text-muted small mb-3 flex-grow-1">
                                        <?php
                                            $excerpt = strip_tags($item->content);
                                            echo strlen($excerpt) > 200 ? substr($excerpt, 0, 200) . '...' : $excerpt;
                                        ?>
                                    </p>
                                    <button type="button" class="btn btn-outline-primary btn-sm w-100" data-bs-toggle="modal" data-bs-target="#noticeModal<?php echo $item->id; ?>">
                                        <i class="ti ti-eye me-1"></i> Read Full Notice
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Notice Detail Modal -->
                        <div class="modal fade" id="noticeModal<?php echo $item->id; ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                                <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                                    <div class="modal-header border-0 pb-0">
                                        <h5 class="modal-title fw-bold"><?php echo htmlspecialchars($item->title); ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body pt-3">
                                        <div class="text-muted small mb-3">
                                            <i class="ti ti-calendar me-1"></i> <?php echo date('d M Y', strtotime($item->created_at)); ?>
                                        </div>
                                        <?php if ($item->image): ?>
                                            <img src="<?php echo file_url($item->image); ?>" class="w-100 rounded-3 mb-3" alt="<?php echo htmlspecialchars($item->title); ?>" style="max-height: 300px; object-fit: cover;">
                                        <?php endif; ?>
                                        <div class="lh-lg"><?php echo $item->content; ?></div>
                                    </div>
                                    <div class="modal-footer border-0 pt-0">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="ti ti-news" style="font-size: 3rem; color: #94a3b8;"></i>
                        <p class="text-muted mt-2">No notices available at the moment.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once 'app/views/admin/layouts/footer.php'; ?>
