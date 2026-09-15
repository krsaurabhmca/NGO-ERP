<?php require_once 'app/views/admin/layouts/header.php'; ?>
<?php require_once 'app/views/admin/layouts/sidebar.php'; ?>

<div class="page-wrapper">
    <?php require_once 'app/views/admin/layouts/topbar.php'; ?>

    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title fw-bold fs-1">Crowdfunding Campaigns</h2>
                </div>
                <div class="col-auto ms-auto">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-add-campaign">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                        Create Campaign
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
                                <th>Goal</th>
                                <th>Raised</th>
                                <th>Donors</th>
                                <th>Progress</th>
                                <th>Dates</th>
                                <th>Status</th>
                                <th class="w-1">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($campaigns)): ?>
                                <?php foreach ($campaigns as $item): ?>
                                    <?php $progress = $item->goal_amount > 0 ? min(100, round(($item->raised_amount / $item->goal_amount) * 100)) : 0; ?>
                                    <tr>
                                        <td>
                                            <?php if ($item->image): ?>
                                                <span class="avatar avatar-sm" style="background-image: url('<?php echo file_url($item->image); ?>')"></span>
                                            <?php else: ?>
                                                <span class="avatar avatar-sm"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 17a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" /><path d="M21 17a3 3 0 1 0 -6 0" /><path d="M9 17l6 0" /><path d="M9 17v-9" /><path d="M15 17v-9" /><path d="M12 3l6 4" /><path d="M12 3l-6 4" /></svg></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="font-weight-medium text-truncate" style="max-width: 250px;"><?php echo htmlspecialchars($item->title); ?></div>
                                            <div class="text-muted small text-truncate" style="max-width: 250px;"><?php echo htmlspecialchars(substr($item->description, 0, 50)) . (strlen($item->description) > 50 ? '...' : ''); ?></div>
                                        </td>
                                        <td>₹<?php echo number_format($item->goal_amount); ?></td>
                                        <td>₹<?php echo number_format($item->raised_amount); ?></td>
                                        <td><span class="badge bg-info-lt text-info"><?php echo $item->donor_count ?? 0; ?></span></td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="progress" style="width: 80px; height: 6px;">
                                                    <div class="progress-bar bg-teal" style="width: <?php echo $progress; ?>%"></div>
                                                </div>
                                                <span class="text-muted small"><?php echo $progress; ?>%</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="small"><?php echo $item->start_date ? date('d M Y', strtotime($item->start_date)) : '-'; ?></div>
                                            <div class="text-muted small">to <?php echo $item->end_date ? date('d M Y', strtotime($item->end_date)) : '-'; ?></div>
                                        </td>
                                        <td>
                                            <span class="badge <?php echo $item->status === 'active' ? 'bg-success' : 'bg-warning text-dark'; ?>">
                                                <?php echo ucfirst($item->status); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="javascript:void(0)" onclick="viewCampaign('<?php echo ($item->uuid ?? $item->id); ?>')" class="btn btn-icon btn-info btn-sm" title="View">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                                            </a>
                                            <a href="javascript:void(0)" onclick="editCampaign('<?php echo ($item->uuid ?? $item->id); ?>')" class="btn btn-icon btn-primary btn-sm" title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" /><path d="M13.5 6.5l4 4" /></svg>
                                            </a>
                                            <a href="javascript:void(0)" onclick="confirmDelete('<?php echo url('admin/campaigns/delete/' . ($item->uuid ?? $item->id)); ?>', 'admin/campaigns/delete/<?php echo ($item->uuid ?? $item->id); ?>', '<?php echo csrf_token('admin/campaigns/delete/' . ($item->uuid ?? $item->id)); ?>')" class="btn btn-icon btn-danger btn-sm" title="Delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="9" class="text-center py-4 text-muted">No campaigns found. Create your first campaign.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- View Campaign Modal -->
    <div class="modal modal-blur fade" id="modal-view-campaign" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">View Campaign</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h2 id="view_title" class="fw-bold mb-3"></h2>
                    <div class="d-flex align-items-center mb-4 text-muted small gap-3">
                        <span><i class="far fa-calendar-alt me-1 text-primary"></i> <span id="view_dates"></span></span>
                        <span id="view_status" class="badge"></span>
                    </div>
                    <div id="view_image_container" class="mb-4 text-center" style="display: none;">
                        <img id="view_image" src="" class="img-fluid rounded shadow-sm" style="max-height: 400px; object-fit: cover;">
                    </div>
                    <div class="row mb-4 g-2">
                        <div class="col-4">
                            <div class="p-3 bg-light rounded-3 text-center">
                                <div class="text-muted small fw-semibold">Goal</div>
                                <div id="view_goal" class="fs-2 fw-bold text-dark"></div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-3 bg-light rounded-3 text-center">
                                <div class="text-muted small fw-semibold">Raised</div>
                                <div id="view_raised" class="fs-2 fw-bold text-primary"></div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-3 bg-light rounded-3 text-center">
                                <div class="text-muted small fw-semibold">Donors</div>
                                <div id="view_donors" class="fs-2 fw-bold text-info"></div>
                            </div>
                        </div>
                    </div>
                    <div id="view_description" class="fs-4 lh-lg" style="white-space: pre-wrap;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary ms-auto" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Campaign Modal -->
    <div class="modal modal-blur fade" id="modal-add-campaign" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="<?php echo url('admin/campaigns/store'); ?>" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title">Create Campaign</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" required placeholder="Enter campaign title">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="6" placeholder="Describe the campaign..."></textarea>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label class="form-label">Goal Amount (₹)</label>
                                    <input type="number" name="goal_amount" class="form-control" step="0.01" min="0" placeholder="100000">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label class="form-label">Start Date</label>
                                    <input type="date" name="start_date" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label class="form-label">End Date</label>
                                    <input type="date" name="end_date" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Image</label>
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
                        <button type="submit" class="btn btn-primary ms-auto">Create Campaign</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Campaign Modal -->
    <div class="modal modal-blur fade" id="modal-edit-campaign" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="edit-campaign-form" action="" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Campaign</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="edit_title" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" id="edit_description" class="form-control" rows="6"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label class="form-label">Goal Amount (₹)</label>
                                    <input type="number" name="goal_amount" id="edit_goal_amount" class="form-control" step="0.01" min="0">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label class="form-label">Raised Amount (₹)</label>
                                    <input type="number" name="raised_amount" id="edit_raised_amount" class="form-control" step="0.01" min="0">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" id="edit_status" class="form-select">
                                        <option value="active">Active (Published)</option>
                                        <option value="inactive">Inactive (Draft)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Start Date</label>
                                    <input type="date" name="start_date" id="edit_start_date" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">End Date</label>
                                    <input type="date" name="end_date" id="edit_end_date" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Change Image</label>
                            <input type="file" name="image" class="form-control" accept="image/*" max="512000">
                            <small class="text-muted">Max 500KB</small>
                            <div id="edit_image_preview" class="mt-2"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary ms-auto">Update Campaign</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function viewCampaign(id) {
            fetch('<?php echo url('admin/campaigns/edit/'); ?>' + id)
                .then(response => response.json())
                .then(result => {
                    if (result.status === 'success') {
                        const c = result.data;
                        document.getElementById('view_title').innerText = c.title;
                        document.getElementById('view_description').innerText = c.description || 'No description provided.';
                        document.getElementById('view_goal').innerText = '₹' + Number(c.goal_amount).toLocaleString();
                        document.getElementById('view_raised').innerText = '₹' + Number(c.raised_amount).toLocaleString();
                        document.getElementById('view_donors').innerText = c.donor_count ?? 0;
                        document.getElementById('view_dates').innerText = (c.start_date || '-') + ' to ' + (c.end_date || '-');

                        const statusBadge = document.getElementById('view_status');
                        statusBadge.className = 'badge ' + (c.status === 'active' ? 'bg-success' : 'bg-secondary');
                        statusBadge.innerText = c.status.charAt(0).toUpperCase() + c.status.slice(1);

                        const imgContainer = document.getElementById('view_image_container');
                        const img = document.getElementById('view_image');
                        if (c.image) {
                            img.src = '<?php echo BASE_URL; ?>file.php?f=' + encodeURIComponent(c.image);
                            imgContainer.style.display = 'block';
                        } else {
                            imgContainer.style.display = 'none';
                        }

                        new bootstrap.Modal(document.getElementById('modal-view-campaign')).show();
                    }
                });
        }

        function editCampaign(id) {
            fetch('<?php echo url('admin/campaigns/edit/'); ?>' + id)
                .then(response => response.json())
                .then(result => {
                    if (result.status === 'success') {
                        const c = result.data;
                        document.getElementById('edit-campaign-form').action = '<?php echo url('admin/campaigns/update/'); ?>' + id;
                        document.getElementById('edit_title').value = c.title;
                        document.getElementById('edit_description').value = c.description || '';
                        document.getElementById('edit_goal_amount').value = c.goal_amount;
                        document.getElementById('edit_raised_amount').value = c.raised_amount;
                        document.getElementById('edit_start_date').value = c.start_date || '';
                        document.getElementById('edit_end_date').value = c.end_date || '';
                        document.getElementById('edit_status').value = c.status;

                        const preview = document.getElementById('edit_image_preview');
                        if (c.image) {
                            preview.innerHTML = '<img src="<?php echo BASE_URL; ?>file.php?f=' + encodeURIComponent(c.image) + '" class="img-thumbnail" style="max-height: 100px;">';
                        } else {
                            preview.innerHTML = '';
                        }

                        new bootstrap.Modal(document.getElementById('modal-edit-campaign')).show();
                    }
                });
        }
    </script>

    <style>
    @media (max-width: 575.98px) {
        #viewCampaignModal .row.mb-4.g-2 > .col-4 { width: 50% !important; flex: 0 0 50% !important; max-width: 50% !important; }
        #viewCampaignModal .row.mb-4.g-2 > .col-4 .fs-2 { font-size: 0.9rem !important; }
        #viewCampaignModal .row.mb-4.g-2 > .col-4 .p-3 { padding: 0.4rem !important; }
    }
    </style>
    <?php require_once 'app/views/admin/layouts/footer.php'; ?>
</div>
