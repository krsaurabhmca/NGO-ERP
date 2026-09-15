<?php require_once 'app/views/admin/layouts/header.php'; ?>
<?php require_once 'app/views/admin/layouts/sidebar.php'; ?>

<div class="page-wrapper">
    <?php require_once 'app/views/admin/layouts/topbar.php'; ?>

    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title fw-bold fs-1">
                        Projects Management
                    </h2>
                </div>
                <div class="col-auto ms-auto">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-add-project">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                        Create Project
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <div class="card">
                <div class="table-responsive">
                    <table class="table card-table table-vcenter text-nowrap datatable">
                        <thead>
                            <tr>
                                <th class="w-1">Image</th>
                                <th>Project Details</th>
                                <th>Duration</th>
                                <th>Status</th>
                                <th class="w-1">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($projects)): ?>
                                <?php foreach ($projects as $item): ?>
                                    <tr>
                                        <td>
                                            <?php if ($item->image): ?>
                                                <span class="avatar avatar-sm" style="background-image: url('<?php echo file_url($item->image); ?>')"></span>
                                            <?php else: ?>
                                                <span class="avatar avatar-sm"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" /><path d="M13.5 6.5l4 4" /></svg></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="font-weight-medium text-truncate" style="max-width: 250px;"><?php echo htmlspecialchars($item->title); ?></div>
                                            <div class="text-muted small text-truncate" style="max-width: 250px;"><?php echo htmlspecialchars(substr($item->description, 0, 50)) . '...'; ?></div>
                                        </td>
                                        <td>
                                            <div class="small">
                                                <strong>Start:</strong> <?php echo $item->start_date ? date('d M, Y', strtotime($item->start_date)) : 'N/A'; ?><br>
                                                <strong>End:</strong> <?php echo $item->end_date ? date('d M, Y', strtotime($item->end_date)) : 'Ongoing'; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <?php 
                                                $badgeClass = 'bg-primary-lt'; // default ongoing
                                                if ($item->status === 'completed') $badgeClass = 'bg-success-lt';
                                                if ($item->status === 'planned') $badgeClass = 'bg-warning-lt';
                                            ?>
                                            <span class="badge <?php echo $badgeClass; ?> px-3">
                                                <?php echo ucfirst($item->status); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="javascript:void(0)" onclick="viewProject('<?php echo ($item->uuid ?? $item->id); ?>')" class="btn btn-icon btn-info btn-sm" title="View Project">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                                            </a>
                                            <a href="javascript:void(0)" onclick="editProject('<?php echo ($item->uuid ?? $item->id); ?>')" class="btn btn-icon btn-primary btn-sm" title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" /><path d="M13.5 6.5l4 4" /></svg>
                                            </a>
                                            <a href="javascript:void(0)" onclick="confirmDelete('<?php echo url('admin/projects/delete/' . ($item->uuid ?? $item->id)); ?>', 'admin/projects/delete/<?php echo ($item->uuid ?? $item->id); ?>', '<?php echo csrf_token('admin/projects/delete/' . ($item->uuid ?? $item->id)); ?>')" class="btn btn-icon btn-danger btn-sm" title="Delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">No projects found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Project Modal -->
    <div class="modal modal-blur fade" id="modal-add-project" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="<?php echo url('admin/projects/store'); ?>" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title">Create Project</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Project Title</label>
                            <input type="text" name="title" class="form-control" required placeholder="Enter project title">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="6" required placeholder="Describe the project..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Video Link (YouTube/Vimeo)</label>
                            <input type="url" name="video_url" class="form-control" placeholder="https://www.youtube.com/watch?v=...">
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Featured Image</label>
                                    <input type="file" name="image" class="form-control" accept="image/*" max="512000">
                                    <small class="text-muted">Max 500KB</small>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Project Gallery</label>
                                    <input type="file" name="gallery[]" class="form-control" accept="image/*" multiple max="512000">
                                    <small class="text-muted">Max 500KB per image. You can select multiple images.</small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="ongoing">Ongoing</option>
                                        <option value="completed">Completed</option>
                                        <option value="planned">Planned</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label class="form-label">Start Date</label>
                                    <input type="date" name="start_date" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label class="form-label">End Date (Optional)</label>
                                    <input type="date" name="end_date" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary ms-auto">Create Project</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Project Modal -->
    <div class="modal modal-blur fade" id="modal-view-project" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Project Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-12 text-center mb-3" id="view_image_container">
                            <img id="view_image" src="" class="img-fluid rounded shadow-sm" style="max-height: 300px; object-fit: cover; display: none;">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Title</label>
                        <p id="view_title" class="form-control-plaintext"></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Description</label>
                        <div id="view_description" class="p-3 bg-light rounded" style="white-space: pre-wrap;"></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-4">
                            <label class="form-label fw-bold">Status</label>
                            <p id="view_status" class="form-control-plaintext"></p>
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label fw-bold">Start Date</label>
                            <p id="view_start_date" class="form-control-plaintext"></p>
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label fw-bold">End Date</label>
                            <p id="view_end_date" class="form-control-plaintext"></p>
                        </div>
                    </div>
                    <div class="mb-3" id="view_video_container" style="display: none;">
                        <label class="form-label fw-bold">Video</label>
                        <div class="ratio ratio-16x9">
                            <iframe id="view_video" src="" allowfullscreen></iframe>
                        </div>
                    </div>
                    <div class="mb-3" id="view_gallery_container" style="display: none;">
                        <label class="form-label fw-bold">Gallery</label>
                        <div id="view_gallery" class="row g-2"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Project Modal -->
    <div class="modal modal-blur fade" id="modal-edit-project" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="edit-project-form" action="" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Project</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Project Title</label>
                            <input type="text" name="title" id="edit_title" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" id="edit_description" class="form-control" rows="6" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Video Link (YouTube/Vimeo)</label>
                            <input type="url" name="video_url" id="edit_video_url" class="form-control">
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Change Featured Image</label>
                                    <input type="file" name="image" class="form-control" accept="image/*" max="512000">
                                    <small class="text-muted">Max 500KB</small>
                                    <div id="edit_image_preview" class="mt-2"></div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Add to Gallery</label>
                                    <input type="file" name="gallery[]" class="form-control" accept="image/*" multiple max="512000">
                                    <small class="text-muted">Max 500KB per image. Select more images to add to the gallery.</small>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Current Gallery</label>
                            <div id="edit_gallery_preview" class="row g-2 border p-2 rounded bg-light" style="min-height: 50px;">
                                <!-- Gallery images will be loaded here -->
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" id="edit_status" class="form-select">
                                        <option value="ongoing">Ongoing</option>
                                        <option value="completed">Completed</option>
                                        <option value="planned">Planned</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label class="form-label">Start Date</label>
                                    <input type="date" name="start_date" id="edit_start_date" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label class="form-label">End Date</label>
                                    <input type="date" name="end_date" id="edit_end_date" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary ms-auto">Update Project</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function getEmbedUrl(url) {
            var match;
            match = url.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]{11})/);
            if (match) {
                return 'https://www.youtube-nocookie.com/embed/' + match[1];
            }
            return url;
        }

        function viewProject(id) {
            fetch('<?php echo url('admin/projects/edit/'); ?>' + id)
                .then(response => response.json())
                .then(result => {
                    if (result.status === 'success') {
                        const p = result.data;
                        const gallery = result.gallery;

                        document.getElementById('view_title').textContent = p.title;
                        document.getElementById('view_description').textContent = p.description;

                        const img = document.getElementById('view_image');
                        if (p.image) {
                            img.src = '<?php echo BASE_URL; ?>file.php?f=' + encodeURIComponent(p.image);
                            img.style.display = 'block';
                        } else {
                            img.style.display = 'none';
                        }

                        const statusEl = document.getElementById('view_status');
                        const badgeClass = p.status === 'completed' ? 'bg-success-lt' : p.status === 'planned' ? 'bg-warning-lt' : 'bg-primary-lt';
                        statusEl.innerHTML = '<span class="badge ' + badgeClass + ' px-3">' + p.status.charAt(0).toUpperCase() + p.status.slice(1) + '</span>';

                        document.getElementById('view_start_date').textContent = p.start_date ? new Date(p.start_date).toLocaleDateString('en-IN', { day: 'numeric', month: 'short', year: 'numeric' }) : 'N/A';
                        document.getElementById('view_end_date').textContent = p.end_date ? new Date(p.end_date).toLocaleDateString('en-IN', { day: 'numeric', month: 'short', year: 'numeric' }) : 'Ongoing';

                        const videoContainer = document.getElementById('view_video_container');
                        const videoEl = document.getElementById('view_video');
                        if (p.video_url) {
                            videoContainer.style.display = 'block';
                            videoEl.src = getEmbedUrl(p.video_url);
                        } else {
                            videoContainer.style.display = 'none';
                            videoEl.src = '';
                        }

                        const galleryContainer = document.getElementById('view_gallery_container');
                        const galleryEl = document.getElementById('view_gallery');
                        galleryEl.innerHTML = '';
                        if (gallery && gallery.length > 0) {
                            galleryContainer.style.display = 'block';
                            gallery.forEach(img => {
                                galleryEl.innerHTML += '<div class="col-4 col-sm-3 col-md-2"><img src="<?php echo BASE_URL; ?>file.php?f=' + encodeURIComponent(img.image_path) + '" class="img-fluid rounded border shadow-sm" style="aspect-ratio: 1/1; object-fit: cover;"></div>';
                            });
                        } else {
                            galleryContainer.style.display = 'none';
                        }

                        const modal = new bootstrap.Modal(document.getElementById('modal-view-project'));
                        modal.show();
                    }
                });
        }

        function editProject(id) {
            fetch('<?php echo url('admin/projects/edit/'); ?>' + id)
                .then(response => response.json())
                .then(result => {
                    if (result.status === 'success') {
                        const project = result.data;
                        const gallery = result.gallery;
                        
                        document.getElementById('edit-project-form').action = '<?php echo url('admin/projects/update/'); ?>' + id;
                        document.getElementById('edit_title').value = project.title;
                        document.getElementById('edit_description').value = project.description;
                        document.getElementById('edit_video_url').value = project.video_url || '';
                        document.getElementById('edit_status').value = project.status;
                        
                        if (project.start_date) {
                            document.getElementById('edit_start_date').value = project.start_date.split(' ')[0];
                        } else {
                            document.getElementById('edit_start_date').value = '';
                        }

                        if (project.end_date) {
                            document.getElementById('edit_end_date').value = project.end_date.split(' ')[0];
                        } else {
                            document.getElementById('edit_end_date').value = '';
                        }

                        const preview = document.getElementById('edit_image_preview');
                        if (project.image) {
                            preview.innerHTML = `<img src="<?php echo BASE_URL; ?>file.php?f=${encodeURIComponent(project.image)}" class="img-thumbnail" style="max-height: 100px;">`;
                        } else {
                            preview.innerHTML = '';
                        }

                        const galleryPreview = document.getElementById('edit_gallery_preview');
                        galleryPreview.innerHTML = '';
                        if (gallery.length > 0) {
                            gallery.forEach(img => {
                                galleryPreview.innerHTML += `
                                    <div class="col-4 col-sm-3 col-md-2 position-relative gallery-item" id="gallery-img-${img.id}">
                                        <img src="<?php echo BASE_URL; ?>file.php?f=${encodeURIComponent(img.image_path)}" class="img-fluid rounded border shadow-sm" style="aspect-ratio: 1/1; object-fit: cover;">
                                        <button type="button" onclick="deleteGalleryImage(${img.id})" class="btn btn-danger btn-icon btn-sm position-absolute top-0 end-0 m-1 shadow" title="Remove">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                        </button>
                                    </div>
                                `;
                            });
                        } else {
                            galleryPreview.innerHTML = '<div class="col-12 text-center py-3 text-muted">No images in gallery.</div>';
                        }

                        const modal = new bootstrap.Modal(document.getElementById('modal-edit-project'));
                        modal.show();
                    }
                });
        }

        function deleteGalleryImage(id) {
            if (confirm('Are you sure you want to remove this image from the gallery?')) {
                fetch('<?php echo url('admin/projects/delete-gallery/'); ?>' + id)
                    .then(response => response.json())
                    .then(result => {
                        if (result.status === 'success') {
                            const item = document.getElementById('gallery-img-' + id);
                            item.remove();
                            showToast('Gallery image removed successfully.', 'success', 'Success');
                        } else {
                            showToast('Failed to remove image.', 'error', 'Error');
                        }
                    });
            }
        }
    </script>

    <?php require_once 'app/views/admin/layouts/footer.php'; ?>
</div>
