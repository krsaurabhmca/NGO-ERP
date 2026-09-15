<?php require_once 'app/views/admin/layouts/header.php'; ?>
<?php require_once 'app/views/admin/layouts/sidebar.php'; ?>

<div class="page-wrapper">
    <?php require_once 'app/views/admin/layouts/topbar.php'; ?>

    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title fw-bold fs-1">
                        Career & Jobs Management
                    </h2>
                </div>
                <div class="col-auto ms-auto">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-add-career">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                        Post New Job
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <div class="card border-0 shadow-sm">
                <div class="table-responsive">
                    <table class="table card-table table-vcenter text-nowrap datatable">
                        <thead>
                            <tr>
                                <th>Job Title</th>
                                <th>Location & Type</th>
                                <th>Deadline</th>
                                <th>Status</th>
                                <th class="w-1">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($careers)): ?>
                                <?php foreach ($careers as $item): ?>
                                    <tr>
                                        <td>
                                            <div class="font-weight-medium text-truncate" style="max-width: 250px;"><?php echo htmlspecialchars($item->title); ?></div>
                                            <div class="text-muted small"><?php echo url('careers/' . $item->slug); ?></div>
                                        </td>
                                        <td>
                                            <div class="text-dark small"><i class="fas fa-map-marker-alt text-muted me-1"></i> <?php echo htmlspecialchars($item->location); ?></div>
                                            <div class="text-muted small"><i class="fas fa-briefcase me-1"></i> <?php echo htmlspecialchars($item->job_type); ?></div>
                                        </td>
                                        <td><?php echo $item->deadline ? date('d M, Y', strtotime($item->deadline)) : '<span class="text-muted">No Deadline</span>'; ?></td>
                                        <td>
                                            <span class="badge <?php echo $item->status === 'open' ? 'bg-success' : 'bg-danger'; ?>-lt">
                                                <?php echo ucfirst($item->status); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="javascript:void(0)" onclick="viewCareer(<?php echo $item->id; ?>)" class="btn btn-icon btn-info btn-sm" title="View Job">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                                            </a>
                                            <a href="javascript:void(0)" onclick="editCareer(<?php echo $item->id; ?>)" class="btn btn-icon btn-primary btn-sm" title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" /><path d="M13.5 6.5l4 4" /></svg>
                                            </a>
                                            <a href="javascript:void(0)" onclick="confirmDelete('<?php echo url('admin/careers/delete/' . ($item->uuid ?? $item->id)); ?>', 'admin/careers/delete/<?php echo ($item->uuid ?? $item->id); ?>', '<?php echo csrf_token('admin/careers/delete/' . ($item->uuid ?? $item->id)); ?>')" class="btn btn-icon btn-danger btn-sm" title="Delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">No job postings found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Career Modal -->
    <div class="modal modal-blur fade" id="modal-add-career" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="<?php echo url('admin/careers/store'); ?>" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Post New Job</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Job Title</label>
                            <input type="text" name="title" class="form-control" required placeholder="e.g., Project Coordinator">
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Location</label>
                                    <input type="text" name="location" class="form-control" placeholder="e.g., New York, NY (or Remote)">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Job Type</label>
                                    <select name="job_type" class="form-select">
                                        <option value="Full-time">Full-time</option>
                                        <option value="Part-time">Part-time</option>
                                        <option value="Contract">Contract</option>
                                        <option value="Volunteer">Volunteer</option>
                                        <option value="Internship">Internship</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Job Description & Requirements</label>
                            <textarea name="description" class="form-control" rows="8" required placeholder="Describe the role, responsibilities, and requirements..."></textarea>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Application Deadline <span class="text-muted fw-normal">(Optional)</span></label>
                                    <input type="date" name="deadline" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="open">Open (Accepting Applications)</option>
                                        <option value="closed">Closed</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary ms-auto">Post Job</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Career Modal -->
    <div class="modal modal-blur fade" id="modal-view-career" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="view-career-title">Job Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <div class="bg-light rounded-3 p-3 text-center">
                                <div class="text-muted small mb-1">Location</div>
                                <div class="fw-bold" id="view-career-location">-</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="bg-light rounded-3 p-3 text-center">
                                <div class="text-muted small mb-1">Job Type</div>
                                <div class="fw-bold" id="view-career-type">-</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="bg-light rounded-3 p-3 text-center">
                                <div class="text-muted small mb-1">Deadline</div>
                                <div class="fw-bold" id="view-career-deadline">-</div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description & Requirements</label>
                        <div class="bg-light rounded-3 p-3" id="view-career-description" style="white-space: pre-wrap;"></div>
                    </div>
                    <div>
                        <span class="badge" id="view-career-status"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Career Modal -->
    <div class="modal modal-blur fade" id="modal-edit-career" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="edit-career-form" action="" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Job Posting</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Job Title</label>
                            <input type="text" name="title" id="edit_title" class="form-control" required>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Location</label>
                                    <input type="text" name="location" id="edit_location" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Job Type</label>
                                    <select name="job_type" id="edit_job_type" class="form-select">
                                        <option value="Full-time">Full-time</option>
                                        <option value="Part-time">Part-time</option>
                                        <option value="Contract">Contract</option>
                                        <option value="Volunteer">Volunteer</option>
                                        <option value="Internship">Internship</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Job Description & Requirements</label>
                            <textarea name="description" id="edit_description" class="form-control" rows="8" required></textarea>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Application Deadline <span class="text-muted fw-normal">(Optional)</span></label>
                                    <input type="date" name="deadline" id="edit_deadline" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" id="edit_status" class="form-select">
                                        <option value="open">Open (Accepting Applications)</option>
                                        <option value="closed">Closed</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary ms-auto">Update Job</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function viewCareer(id) {
            fetch('<?php echo url('admin/careers/edit/'); ?>' + id)
                .then(res => res.json())
                .then(result => {
                    if (result.status === 'success') {
                        const c = result.data;
                        document.getElementById('view-career-title').textContent = c.title;
                        document.getElementById('view-career-location').textContent = c.location || '-';
                        document.getElementById('view-career-type').textContent = c.job_type || '-';
                        document.getElementById('view-career-deadline').textContent = c.deadline ? new Date(c.deadline).toLocaleDateString('en-IN', { day: 'numeric', month: 'short', year: 'numeric' }) : 'No Deadline';
                        document.getElementById('view-career-description').textContent = c.description || 'No description provided.';
                        const badge = document.getElementById('view-career-status');
                        badge.className = 'badge ' + (c.status === 'open' ? 'bg-success' : 'bg-danger') + '-lt';
                        badge.textContent = c.status === 'open' ? 'Open' : 'Closed';
                        new bootstrap.Modal(document.getElementById('modal-view-career')).show();
                    }
                });
        }

        function editCareer(id) {
            fetch('<?php echo url('admin/careers/edit/'); ?>' + id)
                .then(response => response.json())
                .then(result => {
                    if (result.status === 'success') {
                        const career = result.data;
                        document.getElementById('edit-career-form').action = '<?php echo url('admin/careers/update/'); ?>' + id;
                        document.getElementById('edit_title').value = career.title;
                        document.getElementById('edit_location').value = career.location;
                        document.getElementById('edit_job_type').value = career.job_type;
                        document.getElementById('edit_description').value = career.description;
                        document.getElementById('edit_status').value = career.status;
                        
                        if (career.deadline) {
                            document.getElementById('edit_deadline').value = career.deadline.split(' ')[0];
                        } else {
                            document.getElementById('edit_deadline').value = '';
                        }

                        const modal = new bootstrap.Modal(document.getElementById('modal-edit-career'));
                        modal.show();
                    }
                });
        }
    </script>

    <?php require_once 'app/views/admin/layouts/footer.php'; ?>
</div>
