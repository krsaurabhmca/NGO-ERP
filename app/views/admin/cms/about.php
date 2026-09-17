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
                            <li class="breadcrumb-item active" aria-current="page"><a href="#">Manage About Page</a></li>
                        </ol>
                    </div>
                    <h2 class="page-title fw-bold fs-1">
                        Manage About Page
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="row g-0">
                    <!-- Sidebar Navigation -->
                    <div class="col-12 col-md-3 border-end">
                        <div class="card-body">
                            <h4 class="subheader mb-4">About Settings</h4>
                            <div class="list-group list-group-transparent mb-3">
                                <a href="#tab-main" class="list-group-item list-group-item-action d-flex align-items-center active" data-bs-toggle="tab">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" /><path d="M4 13h16" /><path d="M13 12l0 -8" /><path d="M13 16l0 4" /><path d="M13 13l4 -4" /><path d="M13 13l-4 -4" /></svg>
                                    Main Content
                                </a>
                                <a href="#tab-banner" class="list-group-item list-group-item-action d-flex align-items-center" data-bs-toggle="tab">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 8h.01" /><path d="M3 6a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v12a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3v-12z" /><path d="M3 16l5 -5c.928 -.893 2.072 -.893 3 0l5 5" /><path d="M14 14l1 -1c.928 -.893 2.072 -.893 3 0l3 3" /></svg>
                                    Banner Image
                                </a>
                                <a href="#tab-values" class="list-group-item list-group-item-action d-flex align-items-center" data-bs-toggle="tab">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z" /></svg>
                                    Core Values
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Content Section -->
                    <div class="col-12 col-md-9 d-flex flex-column">
                        <div class="card-body">
                            <div class="tab-content">
                                <!-- Page 1: Main Content -->
                                <div class="tab-pane active show" id="tab-main">
                                    <form action="<?php echo url('admin/cms/update-page'); ?>" method="POST">
                                        <input type="hidden" name="page_key" value="about">
                                        <input type="hidden" name="active_tab" value="tab-main">
                                        
                                        <h3 class="card-title mb-4">Page Content & Mission</h3>
                                        <div class="mb-3">
                                            <label class="form-label">Page Title</label>
                                            <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($page->title ?? ''); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Main Page Content (HTML supported)</label>
                                            <textarea name="content" class="form-control" rows="8"><?php echo htmlspecialchars($page->content ?? ''); ?></textarea>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Our Mission</label>
                                                <textarea name="mission" class="form-control" rows="4" placeholder="Enter mission..."><?php echo htmlspecialchars($page->mission ?? ''); ?></textarea>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Our Vision</label>
                                                <textarea name="vision" class="form-control" rows="4" placeholder="Enter vision..."><?php echo htmlspecialchars($page->vision ?? ''); ?></textarea>
                                            </div>
                                        </div>
                                        <div class="mt-4 pt-3 border-top d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" /><path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M14 4l0 4l-6 0l0 -4" /></svg>
                                                Save Main Content
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Page 2: Banner Image -->
                                <div class="tab-pane" id="tab-banner">
                                    <form action="<?php echo url('admin/cms/update-page'); ?>" method="POST" enctype="multipart/form-data">
                                        <input type="hidden" name="page_key" value="about">
                                        <input type="hidden" name="active_tab" value="tab-banner">
                                        
                                        <h3 class="card-title mb-4">About Section Banner</h3>
                                        <div class="row">
                                            <div class="col-lg-8">
                                                <div class="mb-3">
                                                    <div class="form-label">Current Banner Preview</div>
                                                    <div class="border-2 border-dashed rounded bg-light d-flex align-items-center justify-content-center overflow-hidden position-relative" style="width: 100%; height: 300px; border-color: var(--tblr-border-color) !important;">
                                                        <img id="about-image-preview" src="<?php echo !empty($page->image) ? file_url($page->image) : ''; ?>" alt="Banner Preview" style="width: 100%; height: 100%; object-fit: cover; <?php echo empty($page->image) ? 'display:none;' : ''; ?>">
                                                        <div id="about-image-placeholder" class="text-center p-3" style="<?php echo !empty($page->image) ? 'display:none;' : ''; ?>">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg text-muted mb-3" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 8h.01" /><path d="M3 6a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v12a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3v-12z" /><path d="M3 16l5 -5c.928 -.893 2.072 -.893 3 0l5 5" /><path d="M14 14l1 -1c.928 -.893 2.072 -.893 3 0l3 3" /></svg>
                                                            <p class="text-muted small fw-bold m-0">No banner selected</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="about_image_input" class="btn btn-outline-primary py-2 px-4">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" /><path d="M7 9l5 -5l5 5" /><path d="M12 4l0 12" /></svg>
                                                        Upload New Banner
                                                    </label>
                                                    <input type="file" name="image" id="about_image_input" class="d-none" accept="image/*">
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="bg-blue-lt p-3 rounded-2">
                                                    <h4 class="mb-2 small fw-bold text-uppercase text-blue">Requirements:</h4>
                                                    <ul class="list-unstyled mb-0 small text-muted">
                                                        <li class="mb-1 d-flex align-items-center"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-2 text-blue" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg> Recommended Ratio: 16:9</li>
                                                        <li class="mb-1 d-flex align-items-center"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-2 text-blue" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg> Max File Size: 500 KB</li>
                                                        <li class="d-flex align-items-center"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-2 text-blue" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg> Formats: JPG, PNG, WebP</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-4 pt-3 border-top d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" /><path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M14 4l0 4l-6 0l0 -4" /></svg>
                                                Save Banner Image
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Page 3: Core Values -->
                                <div class="tab-pane" id="tab-values">
                                    <form action="<?php echo url('admin/cms/update-page'); ?>" method="POST" enctype="multipart/form-data">
                                        <input type="hidden" name="page_key" value="about">
                                        <input type="hidden" name="active_tab" value="tab-values">
                                        
                                        <h3 class="card-title mb-4">Our Core Values</h3>
                                        <div class="row">
                                            <!-- Value 1 -->
                                            <div class="col-md-6 mb-4">
                                                <div class="p-3 bg-light rounded-2 border">
                                                    <div class="d-flex align-items-center mb-3">
                                                        <div class="me-3 border rounded bg-white d-flex align-items-center justify-content-center overflow-hidden shadow-sm" style="width: 60px; height: 60px;">
                                                            <?php if (!empty($page->value_integrity_image)): ?>
                                                                <img src="<?php echo file_url($page->value_integrity_image); ?>" style="width: 100%; height: 100%; object-fit: contain;">
                                                            <?php else: ?>
                                                                <i class="<?php echo $page->value_integrity_icon ?? 'fas fa-shield-alt'; ?> text-primary fa-lg"></i>
                                                            <?php endif; ?>
                                                        </div>
                                                        <h4 class="mb-0 text-primary fw-bold">Value 1</h4>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold">Title</label>
                                                        <input type="text" name="integrity_title" class="form-control" value="<?php echo htmlspecialchars($page->value_integrity_title ?? 'Integrity'); ?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold">Logo Image</label>
                                                        <input type="file" name="value_integrity_image" class="form-control form-control-sm" accept="image/*" max="512000">
                                                        <small class="text-muted d-block">Max 500KB</small>
                                                        <input type="hidden" name="integrity_icon" value="<?php echo htmlspecialchars($page->value_integrity_icon ?? 'fas fa-shield-alt'); ?>">
                                                    </div>
                                                    <div>
                                                        <label class="form-label small fw-bold">Description</label>
                                                        <textarea name="integrity_desc" class="form-control" rows="3"><?php echo htmlspecialchars($page->value_integrity_desc ?? ''); ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Value 2 -->
                                            <div class="col-md-6 mb-4">
                                                <div class="p-3 bg-light rounded-2 border">
                                                    <div class="d-flex align-items-center mb-3">
                                                        <div class="me-3 border rounded bg-white d-flex align-items-center justify-content-center overflow-hidden shadow-sm" style="width: 60px; height: 60px;">
                                                            <?php if (!empty($page->value_compassion_image)): ?>
                                                                <img src="<?php echo file_url($page->value_compassion_image); ?>" style="width: 100%; height: 100%; object-fit: contain;">
                                                            <?php else: ?>
                                                                <i class="<?php echo $page->value_compassion_icon ?? 'fas fa-heart'; ?> text-danger fa-lg"></i>
                                                            <?php endif; ?>
                                                        </div>
                                                        <h4 class="mb-0 text-danger fw-bold">Value 2</h4>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold">Title</label>
                                                        <input type="text" name="compassion_title" class="form-control" value="<?php echo htmlspecialchars($page->value_compassion_title ?? 'Compassion'); ?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold">Logo Image</label>
                                                        <input type="file" name="value_compassion_image" class="form-control form-control-sm" accept="image/*" max="512000">
                                                        <small class="text-muted d-block">Max 500KB</small>
                                                        <input type="hidden" name="compassion_icon" value="<?php echo htmlspecialchars($page->value_compassion_icon ?? 'fas fa-heart'); ?>">
                                                    </div>
                                                    <div>
                                                        <label class="form-label small fw-bold">Description</label>
                                                        <textarea name="compassion_desc" class="form-control" rows="3"><?php echo htmlspecialchars($page->value_compassion_desc ?? ''); ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Value 3 -->
                                            <div class="col-md-6 mb-4">
                                                <div class="p-3 bg-light rounded-2 border">
                                                    <div class="d-flex align-items-center mb-3">
                                                        <div class="me-3 border rounded bg-white d-flex align-items-center justify-content-center overflow-hidden shadow-sm" style="width: 60px; height: 60px;">
                                                            <?php if (!empty($page->value_innovation_image)): ?>
                                                                <img src="<?php echo file_url($page->value_innovation_image); ?>" style="width: 100%; height: 100%; object-fit: contain;">
                                                            <?php else: ?>
                                                                <i class="<?php echo $page->value_innovation_icon ?? 'fas fa-lightbulb'; ?> text-warning fa-lg"></i>
                                                            <?php endif; ?>
                                                        </div>
                                                        <h4 class="mb-0 text-warning fw-bold">Value 3</h4>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold">Title</label>
                                                        <input type="text" name="innovation_title" class="form-control" value="<?php echo htmlspecialchars($page->value_innovation_title ?? 'Innovation'); ?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold">Logo Image</label>
                                                        <input type="file" name="value_innovation_image" class="form-control form-control-sm" accept="image/*" max="512000">
                                                        <small class="text-muted d-block">Max 500KB</small>
                                                        <input type="hidden" name="innovation_icon" value="<?php echo htmlspecialchars($page->value_innovation_icon ?? 'fas fa-lightbulb'); ?>">
                                                    </div>
                                                    <div>
                                                        <label class="form-label small fw-bold">Description</label>
                                                        <textarea name="innovation_desc" class="form-control" rows="3"><?php echo htmlspecialchars($page->value_innovation_desc ?? ''); ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Value 4 -->
                                            <div class="col-md-6 mb-4">
                                                <div class="p-3 bg-light rounded-2 border">
                                                    <div class="d-flex align-items-center mb-3">
                                                        <div class="me-3 border rounded bg-white d-flex align-items-center justify-content-center overflow-hidden shadow-sm" style="width: 60px; height: 60px;">
                                                            <?php if (!empty($page->value_collaboration_image)): ?>
                                                                <img src="<?php echo file_url($page->value_collaboration_image); ?>" style="width: 100%; height: 100%; object-fit: contain;">
                                                            <?php else: ?>
                                                                <i class="<?php echo $page->value_collaboration_icon ?? 'fas fa-users'; ?> text-success fa-lg"></i>
                                                            <?php endif; ?>
                                                        </div>
                                                        <h4 class="mb-0 text-success fw-bold">Value 4</h4>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold">Title</label>
                                                        <input type="text" name="collaboration_title" class="form-control" value="<?php echo htmlspecialchars($page->value_collaboration_title ?? 'Collaboration'); ?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold">Logo Image</label>
                                                        <input type="file" name="value_collaboration_image" class="form-control form-control-sm" accept="image/*" max="512000">
                                                        <small class="text-muted d-block">Max 500KB</small>
                                                        <input type="hidden" name="collaboration_icon" value="<?php echo htmlspecialchars($page->value_collaboration_icon ?? 'fas fa-users'); ?>">
                                                    </div>
                                                    <div>
                                                        <label class="form-label small fw-bold">Description</label>
                                                        <textarea name="collaboration_desc" class="form-control" rows="3"><?php echo htmlspecialchars($page->value_collaboration_desc ?? ''); ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-4 pt-3 border-top d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" /><path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M14 4l0 4l-6 0l0 -4" /></svg>
                                                Save Core Values
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require_once 'app/views/admin/layouts/footer.php'; ?>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Tab Persistence
        const hash = window.location.hash;
        if (hash) {
            const triggerEl = document.querySelector(`.list-group-item[href="${hash}"]`);
            if (triggerEl) {
                bootstrap.Tab.getInstance(triggerEl)?.show() || new bootstrap.Tab(triggerEl).show();
            }
        }

        // Update hash on tab change
        const tabTriggers = document.querySelectorAll('.list-group-item[data-bs-toggle="tab"]');
        tabTriggers.forEach(trigger => {
            trigger.addEventListener('shown.bs.tab', (e) => {
                window.location.hash = e.target.getAttribute('href');
            });
        });

        const imageInput = document.getElementById('about_image_input');
        const imagePreview = document.getElementById('about-image-preview');
        const imagePlaceholder = document.getElementById('about-image-placeholder');
        const maxKB = 500;

        if (imageInput) {
            imageInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    // Removed frontend size restriction to allow UploadHelper to compress large images
                    
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                        imagePreview.style.display = 'block';
                        imagePlaceholder.style.display = 'none';
                    }
                    reader.readAsDataURL(file);
                }
            });
        }
    });
</script>
                </div>
            </div>
        </div>
    </div>

    <?php require_once 'app/views/admin/layouts/footer.php'; ?>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const imageInput = document.getElementById('about_image_input');
        const imagePreview = document.getElementById('about-image-preview');
        const imagePlaceholder = document.getElementById('about-image-placeholder');
        const maxKB = 500;

        if (imageInput) {
            imageInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    // Removed frontend size restriction to allow UploadHelper to compress large images
                    
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                        imagePreview.style.display = 'block';
                        imagePlaceholder.style.display = 'none';
                    }
                    reader.readAsDataURL(file);
                }
            });
        }
    });
</script>
