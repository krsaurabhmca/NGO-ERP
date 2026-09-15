<?php require_once 'app/views/admin/layouts/header.php'; ?>
<?php require_once 'app/views/admin/layouts/sidebar.php'; ?>

<div class="page-wrapper">
    <?php require_once 'app/views/admin/layouts/topbar.php'; ?>

    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title fw-bold fs-1">
                        News Management
                    </h2>
                </div>
                <div class="col-auto ms-auto">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-add-news">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                        Create News Article
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
                                <th>Title</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th class="w-1">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($news)): ?>
                                <?php foreach ($news as $item): ?>
                                    <tr>
                                        <td>
                                            <?php if ($item->image): ?>
                                                <span class="avatar avatar-sm" style="background-image: url('<?php echo file_url($item->image); ?>')"></span>
                                            <?php else: ?>
                                                <span class="avatar avatar-sm"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" /><path d="M13.5 6.5l4 4" /></svg></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="font-weight-medium text-truncate" style="max-width: 300px;"><?php echo htmlspecialchars($item->title); ?></div>
                                            <div class="text-muted small"><?php echo url('news/' . $item->slug); ?></div>
                                        </td>
                                        <td><?php echo date('d M, Y', strtotime($item->created_at)); ?></td>
                                        <td>
                                            <span class="badge <?php echo $item->status === 'active' ? 'bg-success' : 'bg-warning text-dark'; ?>">
                                                <?php echo ucfirst($item->status); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="javascript:void(0)" onclick="viewNewsDetail('<?php echo ($item->uuid ?? $item->id); ?>')" class="btn btn-icon btn-info btn-sm" title="View Article">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                                            </a>
                                            <a href="javascript:void(0)" onclick="editNews('<?php echo ($item->uuid ?? $item->id); ?>')" class="btn btn-icon btn-primary btn-sm" title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" /><path d="M13.5 6.5l4 4" /></svg>
                                            </a>
                                            <a href="javascript:void(0)" onclick="confirmDelete('<?php echo url('admin/news/delete/' . ($item->uuid ?? $item->id)); ?>', 'admin/news/delete/<?php echo ($item->uuid ?? $item->id); ?>', '<?php echo csrf_token('admin/news/delete/' . ($item->uuid ?? $item->id)); ?>')" class="btn btn-icon btn-danger btn-sm" title="Delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">No news articles found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- View News Modal -->
    <div class="modal modal-blur fade" id="modal-view-news" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">View News Article</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h2 id="view_news_title" class="fw-bold mb-3"></h2>
                    <div class="d-flex align-items-center mb-4 text-muted small">
                        <div class="me-3">
                            <i class="far fa-calendar-alt me-1 text-primary"></i> <span id="view_news_date"></span>
                        </div>
                        <div>
                            <span id="view_news_status" class="badge"></span>
                        </div>
                    </div>
                    <div id="view_news_image_container" class="mb-4 text-center" style="display: none;">
                        <img id="view_news_image" src="" class="img-fluid rounded shadow-sm" style="max-height: 400px; object-fit: cover;">
                    </div>
                    <div id="view_news_content" class="fs-4 lh-lg" style="white-space: pre-wrap;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary ms-auto" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add News Modal -->
    <div class="modal modal-blur fade" id="modal-add-news" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="<?php echo url('admin/news/store'); ?>" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title">Create News Article</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" class="form-control" required placeholder="Enter news title">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Content</label>
                            <textarea name="content" class="form-control" rows="10" required placeholder="Write your news content here..."></textarea>
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
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="active">Active (Published)</option>
                                        <option value="inactive">Inactive (Draft)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary ms-auto">Create Article</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit News Modal -->
    <div class="modal modal-blur fade" id="modal-edit-news" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="edit-news-form" action="" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit News Article</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" id="edit_title" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Content</label>
                            <textarea name="content" id="edit_content" class="form-control" rows="10" required></textarea>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Change Image</label>
                                    <input type="file" name="image" class="form-control" accept="image/*" max="512000">
                                    <small class="text-muted">Max 500KB</small>
                                    <div id="edit_image_preview" class="mt-2"></div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" id="edit_status" class="form-select">
                                        <option value="active">Active (Published)</option>
                                        <option value="inactive">Inactive (Draft)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary ms-auto">Update Article</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function viewNewsDetail(id) {
            fetch('<?php echo url('admin/news/edit/'); ?>' + id)
                .then(response => response.json())
                .then(result => {
                    if (result.status === 'success') {
                        const news = result.data;
                        document.getElementById('view_news_title').innerText = news.title;
                        document.getElementById('view_news_content').innerText = news.content;
                        document.getElementById('view_news_date').innerText = new Date(news.created_at).toLocaleString();
                        
                        const statusBadge = document.getElementById('view_news_status');
                        statusBadge.className = 'badge ' + (news.status === 'active' ? 'bg-success' : 'bg-secondary');
                        statusBadge.innerText = news.status.charAt(0).toUpperCase() + news.status.slice(1);

                        const imageContainer = document.getElementById('view_news_image_container');
                        const image = document.getElementById('view_news_image');
                        if (news.image) {
                            image.src = '<?php echo BASE_URL; ?>file.php?f=' + encodeURIComponent(news.image);
                            imageContainer.style.display = 'block';
                        } else {
                            imageContainer.style.display = 'none';
                        }

                        const modal = new bootstrap.Modal(document.getElementById('modal-view-news'));
                        modal.show();
                    } else {
                        showToast('Failed to load article details.', 'error');
                    }
                });
        }

        function editNews(id) {
            fetch('<?php echo url('admin/news/edit/'); ?>' + id)
                .then(response => response.json())
                .then(result => {
                    if (result.status === 'success') {
                        const news = result.data;
                        document.getElementById('edit-news-form').action = '<?php echo url('admin/news/update/'); ?>' + id;
                        document.getElementById('edit_title').value = news.title;
                        document.getElementById('edit_content').value = news.content;
                        document.getElementById('edit_status').value = news.status;

                        const preview = document.getElementById('edit_image_preview');
                        if (news.image) {
                            preview.innerHTML = `<img src="<?php echo BASE_URL; ?>file.php?f=${encodeURIComponent(news.image)}" class="img-thumbnail" style="max-height: 100px;">`;
                        } else {
                            preview.innerHTML = '';
                        }

                        const modal = new bootstrap.Modal(document.getElementById('modal-edit-news'));
                        modal.show();
                    }
                });
        }
    </script>

    <?php require_once 'app/views/admin/layouts/footer.php'; ?>
</div>
