<?php require_once 'app/views/admin/layouts/header.php'; ?>
<?php require_once 'app/views/admin/layouts/sidebar.php'; ?>

<div class="page-wrapper">
    <?php require_once 'app/views/admin/layouts/topbar.php'; ?>

    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <div class="mb-3">
                        <ol class="breadcrumb" aria-label="breadcrumbs">
                            <li class="breadcrumb-item"><a href="<?php echo url('admin/dashboard'); ?>">Home</a></li>
                            <li class="breadcrumb-item"><a href="#">CMS</a></li>
                            <li class="breadcrumb-item active" aria-current="page"><a href="#"><?php echo $category === 'slider' ? 'Home Slider' : $title; ?></a></li>
                        </ol>
                    </div>
                    <h2 class="page-title fw-bold fs-1">
                        <?php echo $category === 'slider' ? 'Home Slider Manager' : $title; ?>
                    </h2>
                </div>
                <div class="col-auto ms-auto">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-add-media">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                        <?php 
                            if ($category === 'slider') echo 'Add New Display Image';
                            else if ($category === 'gallery') echo 'Add New Image';
                            else if ($category === 'certificate') echo 'Add New Certificate';
                            else if ($category === 'achievement') echo 'Add New Achievement';
                            else echo 'Add New Item'; 
                        ?>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <?php if ($category === 'slider'): ?>
                <div class="alert alert-info border-0 shadow-sm mb-3 py-2">
                    <div class="d-flex">
                        <div class="me-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" /><path d="M12 9h.01" /><path d="M11 12h1v4h1" /></svg>
                        </div>
                        <div>
                            <div class="text-muted small">Only the first <strong>5 active images</strong> will be displayed on the homepage slider.</div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="row row-cards g-2">
                <?php if (!empty($items)): ?>
                    <?php foreach ($items as $item): ?>
                        <div class="<?php 
                            if ($category === 'slider') echo 'col-6 col-md-4 col-lg-3';
                            else if (in_array($category, ['certificate', 'achievement'])) echo 'col-6 col-md-3 col-lg-2';
                            else echo 'col-sm-6 col-lg-3'; 
                        ?>">
                            <div class="card card-sm border-0 shadow-sm h-100">
                                <a href="javascript:void(0)" onclick="viewMedia('<?php echo file_url($item->file_path); ?>', '<?php echo addslashes(htmlspecialchars($item->title)); ?>', '<?php echo addslashes(htmlspecialchars($item->description)); ?>')" class="d-block">
                                    <?php if ($category === 'slider'): ?>
                                        <img src="<?php echo file_url($item->file_path); ?>" class="card-img-top" style="aspect-ratio: 16 / 9; object-fit: cover; width: 100%;">
                                    <?php elseif (in_array($category, ['certificate', 'achievement'])): ?>
                                        <div class="bg-light d-flex align-items-center justify-content-center p-1" style="aspect-ratio: 3 / 4; width: 100%;">
                                            <img src="<?php echo file_url($item->file_path); ?>" style="max-height: 100%; max-width: 100%; object-fit: contain;">
                                        </div>
                                    <?php else: ?>
                                        <img src="<?php echo file_url($item->file_path); ?>" class="card-img-top" style="aspect-ratio: 1 / 1; object-fit: cover; width: 100%;">
                                    <?php endif; ?>
                                </a>
                                <div class="card-body p-2">
                                    <div class="d-flex align-items-start">
                                        <div class="overflow-hidden flex-grow-1">
                                            <div class="fw-bold small text-truncate" title="<?php echo htmlspecialchars($item->title); ?>"><?php echo htmlspecialchars($item->title); ?></div>
                                            <?php if ($category === 'slider'): ?>
                                                <div class="text-muted extra-small" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;"><?php echo htmlspecialchars($item->description); ?></div>
                                            <?php elseif (!in_array($category, ['certificate', 'achievement', 'gallery'])): ?>
                                                <div class="text-muted extra-small text-truncate"><?php echo htmlspecialchars($item->description); ?></div>
                                            <?php endif; ?>
                                            
                                            <div class="mt-2 d-flex align-items-center">
                                                <?php if ($category === 'slider'): ?>
                                                    <label class="form-check form-switch mb-0">
                                                        <input class="form-check-input" type="checkbox" <?php echo ($item->status ?? 'active') === 'active' ? 'checked' : ''; ?> 
                                                               onchange="window.location.href='<?php echo url('admin/cms/toggle-media-status/' . ($item->uuid ?? $item->id)); ?>'">
                                                        <span class="form-check-label extra-small">Visible on Home</span>
                                                    </label>
                                                <?php else: ?>
                                                    <span class="badge <?php echo ($item->status ?? 'active') === 'active' ? 'bg-success' : 'bg-secondary'; ?>-lt extra-small">
                                                        <?php echo ucfirst($item->status ?? 'active'); ?>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="ms-auto ps-2">
                                            <a href="javascript:void(0)" onclick="confirmDelete('<?php echo url('admin/cms/delete-media/' . ($item->uuid ?? $item->id)); ?>', 'admin/cms/delete-media/<?php echo ($item->uuid ?? $item->id); ?>', '<?php echo csrf_token('admin/cms/delete-media/' . ($item->uuid ?? $item->id)); ?>')" class="text-danger" title="Delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-xs" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-5 text-muted">
                        No items found in this section.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Add Media Modal -->
    <div class="modal modal-blur fade" id="modal-add-media" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="<?php echo url('admin/cms/store-media'); ?>" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="category" value="<?php echo $category; ?>">
                    <div class="modal-header">
                        <h5 class="modal-title">Add to <?php echo ucfirst($category); ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <?php if ($category !== 'gallery'): ?>
                        <div class="mb-3">
                            <label class="form-label">Description <span class="text-muted fw-normal small">(Optional)</span></label>
                            <textarea name="description" class="form-control" rows="2"></textarea>
                        </div>
                        <?php endif; ?>
                        <div class="mb-3">
                            <label class="form-label">Upload File</label>
                            <input type="file" name="file" id="media_file_input" class="form-control" accept="image/*" required max="512000">
                            <small class="form-hint">
                                <?php if ($category === 'slider'): ?>
                                    Recommended Aspect Ratio: <span class="fw-bold text-info">16:9</span>. 
                                <?php endif; ?>
                                Max file size: <span class="fw-bold text-primary">500KB</span>. 
                                Format: JPG, PNG, WebP.
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary ms-auto">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Media Modal -->
    <div class="modal modal-blur fade" id="modal-view-media" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title" id="view-media-title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0 text-center bg-black mt-3">
                    <img src="" id="view-media-img" style="max-width: 100%; max-height: 70vh; object-fit: contain;">
                </div>
                <div class="p-3 bg-white border-top">
                    <div id="view-media-desc" class="text-muted small"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <?php require_once 'app/views/admin/layouts/footer.php'; ?>
</div>

<script>
    function viewMedia(url, title, desc) {
        document.getElementById('view-media-img').src = url;
        document.getElementById('view-media-title').innerText = title;
        document.getElementById('view-media-desc').innerText = desc || 'No description provided.';
        
        const modalElement = document.getElementById('modal-view-media');
        const modal = new bootstrap.Modal(modalElement);
        modal.show();
    }

    document.addEventListener("DOMContentLoaded", function() {
        const fileInput = document.getElementById('media_file_input');
        const maxKB = 500;

        if (fileInput) {
            fileInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    if (file.size > maxKB * 1024) {
                        showToast(`File is too large. Maximum size allowed is ${maxKB}KB.`, 'error', 'Upload Error');
                        this.value = ''; // Clear input
                        return;
                    }
                }
            });
        }
    });
</script>
