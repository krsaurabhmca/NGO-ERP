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
                            <li class="breadcrumb-item active" aria-current="page"><a href="#">Designations</a></li>
                        </ol>
                    </div>
                    <h2 class="page-title fw-bold fs-1">
                        Designations Management
                    </h2>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="#" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal"
                            data-bs-target="#modal-add-designation">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 5l0 14" />
                                <path d="M5 12l14 0" />
                            </svg>
                            Add New Designation
                        </a>
                    </div>
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
                                <th>Name</th>
                                <th>Monthly Amount</th>
                                <th>Created At</th>
                                <th class="w-1">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($designations)): ?>
                                <?php foreach ($designations as $designation): ?>
                                    <tr>
                                        <td>
                                            <div class="font-weight-medium"><?php echo htmlspecialchars($designation->name); ?>
                                            </div>
                                        </td>
                                        <td><?php echo '₹' . number_format($designation->monthly_amount, 2); ?></td>
                                        <td><?php echo date('d M, Y', strtotime($designation->created_at)); ?></td>
                                        <td>
                                            <a href="javascript:void(0)"
                                                class="btn btn-icon <?php echo $designation->show_in_form ? 'btn-success' : 'btn-danger'; ?> btn-sm"
                                                title="<?php echo $designation->show_in_form ? 'Visible in Form' : 'Hidden from Form'; ?>"
                                                data-bs-toggle="tooltip"
                                                onclick="confirmToggleStatus('<?php echo url('admin/designations/toggle-status/' . ($designation->uuid ?? $designation->id)); ?>', <?php echo $designation->show_in_form; ?>)">
                                                <?php if ($designation->show_in_form): ?>
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                                        stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                        <path
                                                            d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                                    </svg>
                                                <?php else: ?>
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                                        stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M10.585 10.587a2 2 0 0 0 2.829 2.828" />
                                                        <path
                                                            d="M16.681 16.673a8.717 8.717 0 0 1 -4.681 1.327c-3.6 0 -6.6 -2 -9 -6c1.272 -2.12 2.712 -3.678 4.32 -4.674m2.86 -1.146a9.055 9.055 0 0 1 1.82 -.18c3.6 0 6.6 2 9 6c-.792 1.32 -1.733 2.44 -2.817 3.361" />
                                                        <path d="M3 3l18 18" />
                                                    </svg>
                                                <?php endif; ?>
                                            </a>
                                            <a href="javascript:void(0)" class="btn btn-icon btn-primary btn-sm" title="Edit"
                                                data-bs-toggle="modal" data-bs-target="#modal-edit-designation"
                                                onclick="editDesignation(<?php echo htmlspecialchars(json_encode($designation)); ?>)">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                                    stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" />
                                                    <path d="M13.5 6.5l4 4" />
                                                </svg>
                                            </a>
                                            <a href="javascript:void(0)"
                                                onclick="confirmDelete('<?php echo url('admin/designations/delete/' . ($designation->uuid ?? $designation->id)); ?>', 'admin/designations/delete/<?php echo ($designation->uuid ?? $designation->id); ?>', '<?php echo csrf_token('admin/designations/delete/' . ($designation->uuid ?? $designation->id)); ?>')"
                                                class="btn btn-icon btn-danger btn-sm" title="Delete" data-bs-toggle="tooltip">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                                    stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M4 7l16 0" />
                                                    <path d="M10 11l0 6" />
                                                    <path d="M14 11l0 6" />
                                                    <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                    <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">No designations found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Designation Modal -->
    <div class="modal modal-blur fade" id="modal-add-designation" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="<?php echo url('admin/designations/store'); ?>" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Designation</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Designation Name</label>
                            <input type="text" name="name" class="form-control" required
                                placeholder="e.g. President, Secretary">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Monthly Amount (<?php echo '₹'; ?>)</label>
                            <input type="number" name="monthly_amount" class="form-control" step="0.01" min="0"
                                value="0.00" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="show_in_form" value="1" checked>
                                <span class="form-check-label">Show in Registration Form</span>
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">Cancel</a>
                        <button type="submit" class="btn btn-primary ms-auto">Add Designation</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Designation Modal -->
    <div class="modal modal-blur fade" id="modal-edit-designation" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="editDesignationForm" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Designation</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Designation Name</label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Monthly Amount (<?php echo '₹'; ?>)</label>
                            <input type="number" name="monthly_amount" id="edit_monthly_amount" class="form-control"
                                step="0.01" min="0" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="show_in_form"
                                    id="edit_show_in_form" value="1">
                                <span class="form-check-label">Show in Registration Form</span>
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">Cancel</a>
                        <button type="submit" class="btn btn-primary ms-auto">Update Designation</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function editDesignation(data) {
            document.getElementById('editDesignationForm').action = "<?php echo url('admin/designations/update/'); ?>" + data.id;
            document.getElementById('edit_name').value = data.name;
            document.getElementById('edit_monthly_amount').value = data.monthly_amount;
            document.getElementById('edit_show_in_form').checked = data.show_in_form == 1;
        }

        function confirmToggleStatus(url, currentStatus) {
            const modal = new bootstrap.Modal(document.getElementById('modal-toggle-status'));
            const statusText = currentStatus ? 'hide this designation from' : 'show this designation in';
            document.getElementById('toggle-status-text').textContent = statusText;

            // Convert to POST form submission
            const confirmBtn = document.getElementById('btn-confirm-toggle');
            confirmBtn.onclick = function (e) {
                e.preventDefault();
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = url;

                var csrfMeta = document.querySelector('meta[name="csrf-token"]');
                var csrfToken = csrfMeta?.getAttribute('content') || '';

                var actionInput = document.createElement('input');
                actionInput.type = 'hidden';
                actionInput.name = '_csrf_action';
                actionInput.value = '_default';
                form.appendChild(actionInput);

                var tokenInput = document.createElement('input');
                tokenInput.type = 'hidden';
                tokenInput.name = '_csrf_token';
                tokenInput.value = csrfToken;
                form.appendChild(tokenInput);

                document.body.appendChild(form);
                form.submit();
            };

            modal.show();
        }
    </script>

    <!-- Toggle Status Confirmation Modal -->
    <div class="modal modal-blur fade" id="modal-toggle-status" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-status bg-warning"></div>
                <div class="modal-body text-center py-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-warning icon-lg" width="24"
                        height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 9v2m0 4v.01" />
                        <path
                            d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75" />
                    </svg>
                    <h3>Change Visibility?</h3>
                    <div class="text-muted">Are you sure you want to <span id="toggle-status-text"></span> the
                        registration form?</div>
                </div>
                <div class="modal-footer">
                    <div class="w-100">
                        <div class="row">
                            <div class="col"><a href="#" class="btn w-100" data-bs-dismiss="modal">Cancel</a></div>
                            <div class="col"><a href="#" class="btn btn-warning w-100" id="btn-confirm-toggle">Yes,
                                    Change It</a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require_once 'app/views/admin/layouts/footer.php'; ?>
</div>