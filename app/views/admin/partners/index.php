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
                            <li class="breadcrumb-item active" aria-current="page"><a href="#">Partners</a></li>
                        </ol>
                    </div>
                    <h2 class="page-title fw-bold fs-1">
                        Partners Management
                    </h2>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="#" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#modal-partner">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                            Add Partner
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible" role="alert">
                    <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                    <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                </div>
            <?php endif; ?>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="table-responsive">
                    <table class="table card-table table-vcenter text-nowrap datatable">
                        <thead>
                            <tr>
                                <th>Partner</th>
                                <th>Website</th>
                                <th>Created</th>
                                <th class="w-1">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($partners)): ?>
                                <?php foreach ($partners as $partner): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex py-1 align-items-center">
                                            <?php if (!empty($partner->logo)): ?>
                                                <span class="avatar avatar-sm me-3" style="background-image: url('<?php echo file_url($partner->logo); ?>'); background-size: contain; background-repeat: no-repeat; background-position: center;"></span>
                                            <?php else: ?>
                                                <span class="avatar avatar-sm me-3 d-inline-flex align-items-center justify-content-center" style="background: #e9ecef;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#6c757d" width="18" height="18"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
                                                </span>
                                            <?php endif; ?>
                                            <div class="flex-fill">
                                                <div class="font-weight-medium"><?php echo htmlspecialchars($partner->name); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if (!empty($partner->website)): ?>
                                            <a href="<?php echo htmlspecialchars($partner->website); ?>" target="_blank" class="text-muted small">
                                                <?php echo htmlspecialchars($partner->website); ?>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-muted small"><?php echo date('d M, Y', strtotime($partner->created_at)); ?></td>
                                    <td>
                                        <button class="btn btn-icon btn-primary btn-sm edit-partner" data-id="<?php echo ($partner->uuid ?? $partner->id); ?>" title="Edit" data-bs-toggle="tooltip">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" /><path d="M13.5 6.5l4 4" /></svg>
                                        </button>
                                        <button class="btn btn-icon btn-danger btn-sm delete-partner-btn" data-action="admin/partners/delete/<?php echo ($partner->uuid ?? $partner->id); ?>" data-url="<?php echo url('admin/partners/delete/' . ($partner->uuid ?? $partner->id)); ?>" data-token="<?php echo csrf_token('admin/partners/delete/' . ($partner->uuid ?? $partner->id)); ?>" title="Delete" data-bs-toggle="tooltip">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">No partners added yet. Click "Add Partner" to get started.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Partner Modal -->
<div class="modal modal-blur fade" id="modal-partner" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-partner-title">Add Partner</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="partner-form" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" id="partner-id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Partner Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="partner-name" class="form-control" required placeholder="Enter partner name">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Website URL</label>
                                <input type="url" name="website" id="partner-website" class="form-control" placeholder="https://partner-website.com">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label class="form-label">Partner Logo <span class="text-muted small fw-normal">(Max 500KB)</span></label>
                                <input type="file" name="logo" id="partner-logo" class="form-control" accept="image/*">
                                <div id="logo-preview" class="mt-2 text-center d-none">
                                    <img src="" alt="Logo Preview" style="max-height: 60px; max-width: 100%; object-fit: contain;">
                                </div>
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
                        <span id="partner-btn-text">Add Partner</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var modal = new bootstrap.Modal(document.getElementById('modal-partner'));
    var form = document.getElementById('partner-form');
    var btnText = document.getElementById('partner-btn-text');
    var modalTitle = document.getElementById('modal-partner-title');
    var logoPreview = document.getElementById('logo-preview');
    var logoInput = document.getElementById('partner-logo');

    // Reset form on modal open for "Add"
    document.querySelector('[data-bs-target="#modal-partner"]').addEventListener('click', function() {
        form.reset();
        document.getElementById('partner-id').value = '';
        document.getElementById('partner-name').value = '';
        document.getElementById('partner-website').value = '';
        logoPreview.classList.add('d-none');
        btnText.textContent = 'Add Partner';
        modalTitle.textContent = 'Add Partner';
    });

    // Logo preview
    logoInput.addEventListener('change', function() {
        var file = this.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function(e) {
                logoPreview.querySelector('img').src = e.target.result;
                logoPreview.classList.remove('d-none');
            };
            reader.readAsDataURL(file);
        }
    });

    function bindEditListeners() {
        document.querySelectorAll('.edit-partner').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var id = this.getAttribute('data-id');
                fetch('<?php echo url('admin/partners/edit'); ?>/' + id)
                    .then(function(r) { return r.json(); })
                    .then(function(res) {
                        if (res.status === 'success') {
                            var p = res.data;
                            document.getElementById('partner-id').value = p.id;
                            document.getElementById('partner-name').value = p.name;
                            document.getElementById('partner-website').value = p.website || '';
                            if (p.logo) {
                                logoPreview.querySelector('img').src = '<?php echo BASE_URL; ?>file.php?f=' + encodeURIComponent(p.logo);
                                logoPreview.classList.remove('d-none');
                            } else {
                                logoPreview.classList.add('d-none');
                            }
                            btnText.textContent = 'Update Partner';
                            modalTitle.textContent = 'Edit Partner';
                            modal.show();
                        } else {
                            showToast(res.message || 'Failed to load partner data', 'error');
                        }
                    })
                    .catch(function(err) {
                        console.error('Error:', err);
                        showToast('An error occurred while fetching partner data', 'error');
                    });
            });
        });
    }

    function bindDeleteListeners() {
        document.querySelectorAll('.delete-partner-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var url = this.getAttribute('data-url');
                var token = this.getAttribute('data-token');
                var action = this.getAttribute('data-action');
                var deleteBtn = document.getElementById('confirm-delete-btn');
                var row = this.closest('tr');
                
                deleteBtn.onclick = function(e) {
                    e.preventDefault();
                    var fd = new FormData();
                    fd.append('_csrf_action', action);
                    fd.append('_csrf_token', token);
                    
                    fetch(url, { method: 'POST', body: fd })
                        .then(function(r) { return r.json(); })
                        .then(function(res) {
                            var modalEl = document.getElementById('modal-delete');
                            var deleteModal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                            deleteModal.hide();
                            
                            if (res.status === 'success') {
                                if (row) row.remove();
                                showToast(res.message, 'success');
                            } else {
                                showToast(res.message || 'Error occurred.', 'error');
                            }
                        })
                        .catch(function(err) {
                            console.error('Error:', err);
                            showToast('An error occurred while deleting partner', 'error');
                        });
                };
                
                var modalEl = document.getElementById('modal-delete');
                var deleteModal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                deleteModal.show();
            });
        });
    }

    // Initial binding
    bindEditListeners();
    bindDeleteListeners();

    // Form submit
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        var id = document.getElementById('partner-id').value;
        var url = id ? '<?php echo url('admin/partners/update'); ?>/' + id : '<?php echo url('admin/partners/store'); ?>';
        var fd = new FormData(form);

        var submitBtn = form.querySelector('button[type="submit"]');
        var originalBtnText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Saving...';
        submitBtn.disabled = true;

        fetch(url, { method: 'POST', body: fd })
            .then(function(r) { return r.json(); })
            .then(function(res) {
                submitBtn.innerHTML = originalBtnText;
                submitBtn.disabled = false;
                
                if (res.status === 'success') {
                    modal.hide();
                    showToast(res.message, 'success');
                    
                    // Fetch fresh table HTML in background and replace to avoid page reload delays
                    fetch(window.location.href)
                        .then(function(r) { return r.text(); })
                        .then(function(html) {
                            var doc = new DOMParser().parseFromString(html, 'text/html');
                            var newTbody = doc.querySelector('.table-responsive tbody');
                            if (newTbody) {
                                document.querySelector('.table-responsive tbody').innerHTML = newTbody.innerHTML;
                                bindEditListeners();
                                bindDeleteListeners();
                            }
                        });
                } else {
                    showToast(res.message || 'Something went wrong.', 'error');
                }
            })
            .catch(function(err) {
                submitBtn.innerHTML = originalBtnText;
                submitBtn.disabled = false;
                console.error('Error:', err);
                showToast('An error occurred while saving partner', 'error');
            });
    });
});
</script>

<?php require_once 'app/views/admin/layouts/footer.php'; ?>
