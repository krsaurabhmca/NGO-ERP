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
                            <li class="breadcrumb-item active" aria-current="page"><a href="#">Beneficiaries</a></li>
                        </ol>
                    </div>
                    <h2 class="page-title fw-bold fs-1">
                        Beneficiaries Management
                    </h2>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list" style="border: 1px solid var(--tblr-border-color, #e6e7e9); border-radius: 6px; padding: 4px;">
                        <a href="#" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#modal-add-assistance-type">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                            Add Assistance Type
                        </a>
                        <a href="#" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#modal-add-beneficiary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
                            Add New Beneficiary
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
                                <th>Photo</th>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Demographics</th>
                                <th>Assistance Type</th>
                                <th>Files</th>
                                <th>Status</th>
                                <th class="w-1">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($beneficiaries)): ?>
                                <?php foreach ($beneficiaries as $beneficiary): ?>
                                    <tr>
                                        <td>
                                            <?php if (!empty($beneficiary->photo)): ?>
                                                <?php $photoUrl = file_url($beneficiary->photo); ?>
                                                <span class="avatar avatar-sm" style="background-image: url(&quot;<?php echo $photoUrl; ?>&quot;)"></span>
                                            <?php else: ?>
                                                <span class="avatar avatar-sm"><?php echo substr($beneficiary->name, 0, 1); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td><span class="text-muted"><?php echo $beneficiary->beneficiary_id; ?></span></td>
                                        <td>
                                            <div class="fw-bold"><?php echo htmlspecialchars($beneficiary->name); ?></div>
                                            <div class="small text-muted"><?php echo htmlspecialchars($beneficiary->contact_info); ?></div>
                                        </td>
                                        <td>
                                            <span class="text-muted">
                                                <?php 
                                                    if (!empty($beneficiary->date_of_birth)) {
                                                        $dob = new DateTime($beneficiary->date_of_birth);
                                                        $now = new DateTime();
                                                        echo $now->diff($dob)->y . ' yrs, ';
                                                    }
                                                ?>
                                                <?php echo ucfirst($beneficiary->gender); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-purple-lt"><?php echo htmlspecialchars($beneficiary->assistance_type); ?></span>
                                        </td>
                                        <td>
                                            <?php if (!empty($beneficiary->documents)): ?>
                                                <a href="<?php echo file_url($beneficiary->documents); ?>" target="_blank" class="btn btn-sm btn-ghost-secondary">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /></svg>
                                                    View Doc
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted small">No Doc</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($beneficiary->status === 'active'): ?>
                                                <span class="badge bg-success me-1"></span> Active
                                            <?php else: ?>
                                                <span class="badge bg-secondary me-1"></span> Inactive
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="javascript:void(0)" onclick="editBeneficiary('<?php echo ($beneficiary->uuid ?? $beneficiary->id); ?>')" class="btn btn-icon btn-primary btn-sm" title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" /><path d="M13.5 6.5l4 4" /></svg>
                                            </a>
                                            <a href="javascript:void(0)" onclick="confirmDelete('<?php echo url('admin/beneficiaries/delete/' . ($beneficiary->uuid ?? $beneficiary->id)); ?>', 'admin/beneficiaries/delete/<?php echo ($beneficiary->uuid ?? $beneficiary->id); ?>', '<?php echo csrf_token('admin/beneficiaries/delete/' . ($beneficiary->uuid ?? $beneficiary->id)); ?>')" class="btn btn-icon btn-danger btn-sm" title="Delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">No beneficiaries found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Beneficiary Modal -->
    <div class="modal modal-blur fade" id="modal-add-beneficiary" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="<?php echo url('admin/beneficiaries/store'); ?>" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Beneficiary</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <div class="form-label">Photo</div>
                                <div class="d-flex flex-column align-items-center gap-2">
                                    <div class="border rounded d-flex align-items-center justify-content-center bg-light" style="width: 100px; height: 130px; overflow: hidden;">
                                        <img id="bene-photo-preview" src="" style="width: 100%; height: 100%; object-fit: cover; display: none;">
                                        <svg id="bene-photo-placeholder" xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user text-muted" width="40" height="40" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
                                    </div>
                                    <input type="file" name="photo" id="bene_photo_input" class="form-control form-control-sm" accept="image/*" max="512000">
                                    <small class="text-muted">Max: 500KB &middot; Ratio: 2:3</small>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="mb-3">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" name="name" class="form-control" required placeholder="Enter name">
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Date of Birth</label>
                                            <input type="date" name="date_of_birth" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Gender</label>
                                            <select class="form-select" name="gender">
                                                <option value="not_specified" selected>Not Specified</option>
                                                <option value="male">Male</option>
                                                <option value="female">Female</option>
                                                <option value="other">Other</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Contact Info (Phone/Email)</label>
                                    <input type="text" name="contact_info" class="form-control" placeholder="Contact details">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Assistance Type</label>
                                    <select name="assistance_type" class="form-select" required>
                                        <option value="">Select type...</option>
                                        <?php foreach ($assistanceTypes as $at): ?>
                                            <option value="<?php echo htmlspecialchars($at->name); ?>"><?php echo htmlspecialchars($at->name); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="mb-3">
                                    <label class="form-label">Address / Location</label>
                                    <input type="text" name="address" class="form-control" placeholder="Full address or location">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label class="form-label">Supporting Documents</label>
                                    <input type="file" name="documents" class="form-control" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                    <small class="text-muted">PDF or Image (Max: 2MB)</small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Enrolled Date</label>
                                    <input type="date" name="enrolled_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                                </div>
                            </div>
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
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                            Add Beneficiary
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Assistance Type Modal -->
    <div class="modal modal-blur fade" id="modal-add-assistance-type" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="<?php echo url('admin/beneficiaries/store-assistance-type'); ?>" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Manage Assistance Types</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-4">
                            <label class="form-label">Add New Type</label>
                            <div class="input-group">
                                <input type="text" name="name" class="form-control" required placeholder="e.g. Education, Medical">
                                <button type="submit" class="btn btn-primary">Add</button>
                            </div>
                        </div>
                        <div class="border-top pt-3">
                            <label class="form-label mb-2">Existing Types</label>
                            <?php if (!empty($assistanceTypes)): ?>
                                <div class="d-flex flex-wrap gap-1">
                                    <?php foreach ($assistanceTypes as $at): ?>
                                        <span class="badge bg-secondary-lt text-dark px-2 py-2 d-inline-flex align-items-center gap-2">
                                            <?php echo htmlspecialchars($at->name); ?>
                                            <a href="javascript:void(0)" onclick="confirmDelete('<?php echo url('admin/beneficiaries/delete-assistance-type/' . ($at->uuid ?? $at->id)); ?>', 'admin/beneficiaries/delete-assistance-type/<?php echo ($at->uuid ?? $at->id); ?>', '<?php echo csrf_token('admin/beneficiaries/delete-assistance-type/' . ($at->uuid ?? $at->id)); ?>')" class="text-danger text-decoration-none" style="line-height: 1;">&times;</a>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-muted small mb-0">No types added yet.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Beneficiary Modal -->
    <div class="modal modal-blur fade" id="modal-edit-beneficiary" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="edit-beneficiary-form" action="" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Beneficiary</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <div class="form-label">Photo</div>
                                <div class="d-flex flex-column align-items-center gap-2">
                                    <div class="border rounded d-flex align-items-center justify-content-center bg-light" style="width: 100px; height: 130px; overflow: hidden;">
                                        <img id="edit-photo-preview" src="" style="width: 100%; height: 100%; object-fit: cover; display: none;">
                                        <svg id="edit-photo-placeholder" xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user text-muted" width="40" height="40" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
                                    </div>
                                    <input type="file" name="photo" id="edit_photo_input" class="form-control form-control-sm" accept="image/*" max="512000">
                                    <small class="text-muted">Max: 500KB &middot; Ratio: 2:3</small>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="mb-3">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" name="name" id="edit_name" class="form-control" required>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Date of Birth</label>
                                            <input type="date" name="date_of_birth" id="edit_dob" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Gender</label>
                                            <select class="form-select" name="gender" id="edit_gender">
                                                <option value="not_specified">Not Specified</option>
                                                <option value="male">Male</option>
                                                <option value="female">Female</option>
                                                <option value="other">Other</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Contact Info</label>
                                    <input type="text" name="contact_info" id="edit_contact" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Assistance Type</label>
                                    <select name="assistance_type" id="edit_assistance" class="form-select">
                                        <option value="">Select type...</option>
                                        <?php foreach ($assistanceTypes as $at): ?>
                                            <option value="<?php echo htmlspecialchars($at->name); ?>"><?php echo htmlspecialchars($at->name); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="mb-3">
                                    <label class="form-label">Address</label>
                                    <input type="text" name="address" id="edit_address" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label class="form-label">Replace Documents</label>
                                    <input type="file" name="documents" class="form-control" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Enrolled Date</label>
                                    <input type="date" name="enrolled_date" id="edit_enrolled" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" name="status" id="edit_status">
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary ms-auto">Update Beneficiary</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php require_once 'app/views/admin/layouts/footer.php'; ?>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const photoInput = document.getElementById('bene_photo_input');
        const photoPreview = document.getElementById('bene-photo-preview');
        const photoPlaceholder = document.getElementById('bene-photo-placeholder');

        if (photoInput) {
            photoInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        photoPreview.src = e.target.result;
                        photoPreview.style.display = 'block';
                        photoPlaceholder.style.display = 'none';
                    }
                    reader.readAsDataURL(file);
                }
            });
        }

        const editPhotoInput = document.getElementById('edit_photo_input');
        const editPhotoPreview = document.getElementById('edit-photo-preview');
        const editPhotoPlaceholder = document.getElementById('edit-photo-placeholder');

        if (editPhotoInput) {
            editPhotoInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        editPhotoPreview.src = e.target.result;
                        editPhotoPreview.style.display = 'block';
                        editPhotoPlaceholder.style.display = 'none';
                    }
                    reader.readAsDataURL(file);
                }
            });
        }
    });

    document.addEventListener("DOMContentLoaded", function() {
        if (window.location.hash === '#modal-add-assistance-type') {
            const modal = new bootstrap.Modal(document.getElementById('modal-add-assistance-type'));
            modal.show();
        }
    });

    function editBeneficiary(id) {
        fetch('<?php echo url('admin/beneficiaries/edit/'); ?>' + id)
            .then(response => response.json())
            .then(result => {
                if (result.status === 'success') {
                    const b = result.data;
                    document.getElementById('edit-beneficiary-form').action = '<?php echo url('admin/beneficiaries/update/'); ?>' + id;
                    document.getElementById('edit_name').value = b.name;
                    document.getElementById('edit_dob').value = b.date_of_birth || '';
                    document.getElementById('edit_gender').value = b.gender;
                    document.getElementById('edit_contact').value = b.contact_info || '';
                    document.getElementById('edit_assistance').value = b.assistance_type || '';
                    document.getElementById('edit_address').value = b.address || '';
                    document.getElementById('edit_enrolled').value = b.enrolled_date || '';
                    document.getElementById('edit_status').value = b.status;

                    const preview = document.getElementById('edit-photo-preview');
                    const placeholder = document.getElementById('edit-photo-placeholder');
                    if (b.photo) {
                        preview.src = '<?php echo BASE_URL; ?>file.php?f=' + encodeURIComponent(b.photo);
                        preview.style.display = 'block';
                        placeholder.style.display = 'none';
                    } else {
                        preview.style.display = 'none';
                        placeholder.style.display = 'block';
                    }

                    new bootstrap.Modal(document.getElementById('modal-edit-beneficiary')).show();
                }
            });
    }
</script>
