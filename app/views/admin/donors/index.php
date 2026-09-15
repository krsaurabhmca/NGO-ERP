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
                            <li class="breadcrumb-item active" aria-current="page"><a href="#">Donors</a></li>
                        </ol>
                    </div>
                    <h2 class="page-title fw-bold fs-1">
                        Donors Management
                    </h2>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="#" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal"
                            data-bs-target="#modal-add-donor">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 5l0 14" />
                                <path d="M5 12l14 0" />
                            </svg>
                            Add New Donor
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
                                <th>ID</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Contact Info</th>
                                <th>Total Donated</th>
                                <th>Status</th>
                                <th class="w-1">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($donors)): ?>
                                <?php foreach ($donors as $donor): ?>
                                    <tr>
                                        <td><span class="text-muted"><?php echo $donor->donor_id; ?></span></td>
                                        <td><?php echo htmlspecialchars($donor->name); ?></td>
                                        <td>
                                            <?php if ($donor->donor_type === 'individual'): ?>
                                                <span class="badge badge-outline text-blue">Individual</span>
                                            <?php elseif ($donor->donor_type === 'corporate'): ?>
                                                <span class="badge badge-outline text-purple">Corporate</span>
                                            <?php else: ?>
                                                <span class="badge badge-outline text-orange">Foundation</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="small"><?php echo htmlspecialchars($donor->email); ?></div>
                                            <div class="small text-muted"><?php echo htmlspecialchars($donor->phone); ?></div>
                                        </td>
                                        <td>
                                            <span
                                                class="fw-bold"><?php echo '₹' . number_format($donor->total_donations, 2); ?></span>
                                        </td>
                                        <td>
                                            <?php if ($donor->status === 'active'): ?>
                                                <span class="badge bg-success me-1"></span> Active
                                            <?php else: ?>
                                                <span class="badge bg-secondary me-1"></span> Inactive
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="#" class="btn btn-icon btn-primary btn-sm" title="Edit"
                                                data-bs-toggle="modal" data-bs-target="#modal-edit-donor"
                                                onclick="populateEditDonor(this)"
                                                data-id="<?php echo $donor->uuid ?? $donor->id; ?>"
                                                data-name="<?php echo htmlspecialchars($donor->name); ?>"
                                                data-email="<?php echo htmlspecialchars($donor->email); ?>"
                                                data-phone="<?php echo htmlspecialchars($donor->phone); ?>"
                                                data-address="<?php echo htmlspecialchars($donor->address); ?>"
                                                data-type="<?php echo $donor->donor_type; ?>"
                                                data-status="<?php echo $donor->status; ?>">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                                    stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" />
                                                    <path d="M13.5 6.5l4 4" />
                                                </svg>
                                            </a>
                                            <a href="javascript:void(0)"
                                                onclick="confirmDelete('<?php echo url('admin/donors/delete/' . ($donor->uuid ?? $donor->id)); ?>', 'admin/donors/delete/<?php echo ($donor->uuid ?? $donor->id); ?>', '<?php echo csrf_token('admin/donors/delete/' . ($donor->uuid ?? $donor->id)); ?>')"
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
                                    <td colspan="7" class="text-center py-4 text-muted">No donors found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Donor Modal -->
    <div class="modal modal-blur fade" id="modal-add-donor" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="<?php echo url('admin/donors/store'); ?>" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Donor</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="mb-3">
                                    <label class="form-label">Donor/Company Name</label>
                                    <input type="text" name="name" class="form-control" required
                                        placeholder="Enter name">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label class="form-label">Donor Type</label>
                                    <select class="form-select" name="donor_type">
                                        <option value="individual" selected>Individual</option>
                                        <option value="corporate">Corporate</option>
                                        <option value="foundation">Foundation</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" name="email" class="form-control"
                                        placeholder="example@email.com">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Phone Number</label>
                                    <input type="text" name="phone" class="form-control" placeholder="Phone number">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Address</label>
                                    <textarea class="form-control" name="address" rows="3"
                                        placeholder="Full address"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" name="status">
                                        <option value="active" selected>Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary ms-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 5l0 14" />
                                <path d="M5 12l14 0" />
                            </svg>
                            Add Donor
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Donor Modal -->
    <div class="modal modal-blur fade" id="modal-edit-donor" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="editDonorForm" action="" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Donor</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="mb-3">
                                    <label class="form-label">Donor/Company Name</label>
                                    <input type="text" id="edit_name" name="name" class="form-control" required
                                        placeholder="Enter name">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label class="form-label">Donor Type</label>
                                    <select class="form-select" id="edit_donor_type" name="donor_type">
                                        <option value="individual">Individual</option>
                                        <option value="corporate">Corporate</option>
                                        <option value="foundation">Foundation</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" id="edit_email" name="email" class="form-control"
                                        placeholder="example@email.com">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Phone Number</label>
                                    <input type="text" id="edit_phone" name="phone" class="form-control"
                                        placeholder="Phone number">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Address</label>
                                    <textarea class="form-control" id="edit_address" name="address" rows="3"
                                        placeholder="Full address"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" id="edit_status" name="status">
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary ms-auto">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function populateEditDonor(btn) {
            const id = btn.getAttribute('data-id');
            const name = btn.getAttribute('data-name');
            const email = btn.getAttribute('data-email');
            const phone = btn.getAttribute('data-phone');
            const address = btn.getAttribute('data-address');
            const type = btn.getAttribute('data-type');
            const status = btn.getAttribute('data-status');

            document.getElementById('editDonorForm').action = '<?php echo url('admin/donors/update/'); ?>' + id;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_phone').value = phone;
            document.getElementById('edit_address').value = address;
            document.getElementById('edit_donor_type').value = type;
            document.getElementById('edit_status').value = status;
        }
    </script>

    <?php require_once 'app/views/admin/layouts/footer.php'; ?>
</div>