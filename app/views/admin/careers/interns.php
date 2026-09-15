<?php require_once 'app/views/admin/layouts/header.php'; ?>
<?php require_once 'app/views/admin/layouts/sidebar.php'; ?>

<div class="page-wrapper">
    <?php require_once 'app/views/admin/layouts/topbar.php'; ?>

    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <ol class="breadcrumb" aria-label="breadcrumbs">
                        <li class="breadcrumb-item small"><a href="<?php echo url('admin/dashboard'); ?>">Home</a></li>
                        <li class="breadcrumb-item small"><a href="<?php echo url('admin/careers'); ?>">Careers</a></li>
                        <li class="breadcrumb-item active small" aria-current="page">Interns</li>
                    </ol>
                    <h2 class="page-title fw-bold fs-1">
                        Hired Interns
                    </h2>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-add-intern">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                        Add New Intern
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <div class="card border-0 shadow-sm">
                <div class="card-body py-2 border-bottom">
                    <div class="row g-2 align-items-center">
                        <div class="col">
                            <div class="input-icon input-icon-sm">
                                <span class="input-icon-addon"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg></span>
                                <input type="text" id="if-search" class="form-control form-control-sm" placeholder="Search name, email, phone...">
                            </div>
                        </div>
                        <div class="col-auto">
                            <select id="if-status" class="form-select form-select-sm" style="min-width:120px">
                                <option value="">All Status</option>
                                <option value="hired">Hired</option>
                                <option value="terminated">Terminated</option>
                                <option value="resigned">Resigned</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                        <div class="col-auto d-flex align-items-center gap-1">
                            <input type="date" id="if-from" class="form-control form-control-sm" style="width:130px" title="From date">
                            <span class="text-muted small">–</span>
                            <input type="date" id="if-to" class="form-control form-control-sm" style="width:130px" title="To date">
                        </div>
                        <div class="col-auto d-flex align-items-center gap-2">
                            <button type="button" id="if-clear" class="btn btn-sm btn-ghost-secondary d-none" title="Clear filters">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 6l-12 12" /><path d="M6 6l12 12" /></svg>
                                Clear
                            </button>
                            <span id="if-count" class="text-muted small d-none"></span>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table card-table table-vcenter text-nowrap datatable" id="interns-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Contact</th>
                                <th>Status</th>
                                <th>Hired Date</th>
                                <th class="w-1">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($interns)): ?>
                                <?php foreach ($interns as $item): ?>
                                    <tr data-name="<?php echo strtolower(htmlspecialchars($item->name)); ?>" data-email="<?php echo strtolower(htmlspecialchars($item->email)); ?>" data-phone="<?php echo htmlspecialchars($item->phone); ?>" data-status="<?php echo strtolower(htmlspecialchars($item->status)); ?>" data-date="<?php echo date('Y-m-d', strtotime($item->updated_at)); ?>">
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <?php if (!empty($item->profile_image)): ?>
                                                    <span class="avatar avatar-sm" style="background-image: url(<?php echo file_url($item->profile_image); ?>)"></span>
                                                <?php else: ?>
                                                    <span class="avatar avatar-sm"><?php echo strtoupper(substr($item->name, 0, 1)); ?></span>
                                                <?php endif; ?>
                                                <div>
                                                    <div class="fw-semibold"><?php echo htmlspecialchars($item->name); ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-semibold"><?php echo htmlspecialchars($item->career_title ?? 'N/A'); ?></div>
                                            <span class="badge bg-green-lt text-green">Internship</span>
                                        </td>
                                        <td>
                                            <div class="small"><i class="fas fa-envelope text-muted me-1"></i> <?php echo htmlspecialchars($item->email); ?></div>
                                            <div class="small"><i class="fas fa-phone text-muted me-1"></i> <?php echo htmlspecialchars($item->phone); ?></div>
                                        </td>
                                        <td>
                                            <?php 
                                            $badgeColors = ['pending' => 'bg-yellow', 'reviewed' => 'bg-blue', 'shortlisted' => 'bg-purple', 'called_for_interview' => 'bg-cyan', 'rejected' => 'bg-danger', 'hired' => 'bg-success', 'terminated' => 'bg-danger', 'resigned' => 'bg-orange'];
                                            $color = $badgeColors[$item->status] ?? 'bg-secondary';
                                            $statusName = ucwords(str_replace('_', ' ', $item->status));
                                            ?>
                                            <span class="badge <?php echo $color; ?> text-white"><?php echo htmlspecialchars($statusName); ?></span>
                                        </td>
                                        <td><span class="text-muted small"><?php echo date('d M, Y', strtotime($item->updated_at)); ?></span></td>
                                        <td>
                                            <button type="button" class="btn btn-icon btn-info btn-sm" title="View" data-bs-toggle="modal" data-bs-target="#modal-application" data-id="<?php echo ($item->uuid ?? $item->id); ?>">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                                            </button>

                                            <a href="<?php echo url('offer_letter_pdf.php?uuid=' . ($item->uuid ?? $item->id)); ?>" target="_blank" class="btn btn-icon btn-cyan btn-sm" title="Generate Offer Letter">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 15l2 2l4 -4" /></svg>
                                            </a>
                                            <button type="button" class="btn btn-icon btn-success btn-sm btn-send-email" title="Send Email" data-id="<?php echo ($item->uuid ?? $item->id); ?>" data-email="<?php echo htmlspecialchars($item->email); ?>" data-name="<?php echo htmlspecialchars($item->name); ?>" data-status="<?php echo $item->internship_status ?? 'active'; ?>">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z" /><path d="M3 7l9 6l9 -6" /></svg>
                                            </button>
                                            <?php if (($item->internship_status ?? 'active') === 'completed'): ?>
                                                <a href="<?php echo url('certificate_pdf.php?uuid=' . ($item->uuid ?? $item->id)); ?>" target="_blank" class="btn btn-icon btn-warning btn-sm" title="Certificate">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 15m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /><path d="M13 17.5v4.5l2 -1l2 1v-4.5" /><path d="M10 19h-5a2 2 0 0 1 -2 -2v-10c0 -1.1 .9 -2 2 -2h14a2 2 0 0 1 2 2v3" /></svg>
                                                </a>
                                            <?php else: ?>
                                                <button type="button" class="btn btn-icon btn-orange btn-sm btn-complete-internship" title="Complete" data-id="<?php echo ($item->uuid ?? $item->id); ?>">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                                                </button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">No hired interns yet.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- KYC Document Preview Modal -->
<div class="modal modal-blur fade" id="modal-kyc-preview" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 9999;">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-header">
                <h5 class="modal-title">KYC Documents</h5>
            </div>
            <div class="modal-body p-4" id="kyc-preview-content">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Offer Letter Preview Modal -->
<div class="modal modal-blur fade" id="modal-offer-letter" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 9999;">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-header">
                <h5 class="modal-title">Offer Letter</h5>
            </div>
            <div class="modal-body p-4 text-center" id="offer-letter-content">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Application Detail Modal -->
<div class="modal modal-blur fade" id="modal-application" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body p-4">
                <div id="application-content">
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Complete Internship Confirmation Modal -->
<div class="modal modal-blur fade" id="modal-complete-internship" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body text-center py-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-success icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                <h3>Complete Internship?</h3>
                <div class="text-muted">Mark this internship as completed. A certificate can be generated afterward.</div>
            </div>
            <div class="modal-footer">
                <div class="w-100">
                    <div class="row">
                        <div class="col">
                            <button type="button" class="btn w-100" data-bs-dismiss="modal">Cancel</button>
                        </div>
                        <div class="col">
                            <button type="button" class="btn btn-success w-100" id="confirm-complete-btn">Complete</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Send Email Modal -->
<div class="modal modal-blur fade" id="modal-send-email" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body text-center py-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-success icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z" /><path d="M3 7l9 6l9 -6" /></svg>
                <h3 class="mb-1" id="send-email-name"></h3>
                <div class="text-muted mb-3" id="send-email-addr"></div>
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-cyan btn-send-offer-letter">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 15l2 2l4 -4" /></svg>
                        Send Offer Letter
                    </button>
                    <button type="button" class="btn btn-warning btn-send-certificate">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 15m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /><path d="M13 17.5v4.5l2 -1l2 1v-4.5" /><path d="M10 19h-5a2 2 0 0 1 -2 -2v-10c0 -1.1 .9 -2 2 -2h14a2 2 0 0 1 2 2v3" /></svg>
                        Send Certificate
                    </button>
                    <a href="#" class="btn btn-outline-success btn-send-plain-email" target="_blank">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z" /><path d="M3 7l9 6l9 -6" /></svg>
                        Open Email Client
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Intern Modal -->
<div class="modal modal-blur fade" id="modal-add-intern" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-header">
                <h5 class="modal-title">Add New Intern</h5>
            </div>
            <div class="modal-body">
                <form id="form-add-intern" method="POST" enctype="multipart/form-data">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label required">Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required">Position</label>
                            <select name="career_id" class="form-select" id="add-intern-position-select" required>
                                <option value="">Select Position</option>
                                <option value="custom">-- Enter Manually --</option>
                                <?php
                                $careerModel = new \App\Models\Career();
                                $internshipCareers = $careerModel->getActiveByJobType('Internship');
                                foreach ($internshipCareers as $career):
                                ?>
                                    <option value="<?php echo $career->id; ?>"><?php echo htmlspecialchars($career->title); ?> (<?php echo htmlspecialchars($career->location ?? 'N/A'); ?>)</option>
                                <?php endforeach; ?>
                            </select>
                            <input type="text" name="custom_position" id="add-intern-custom-position" class="form-control mt-2" placeholder="Enter position title" style="display: none;">
                        </div>
                    </div>
                    <script>
                    document.getElementById('add-intern-position-select').addEventListener('change', function() {
                        var customInput = document.getElementById('add-intern-custom-position');
                        if (this.value === 'custom') {
                            customInput.style.display = 'block';
                            customInput.required = true;
                        } else {
                            customInput.style.display = 'none';
                            customInput.required = false;
                            customInput.value = '';
                        }
                    });
                    </script>
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Duration <span class="text-muted small">(e.g. 6 months)</span></label>
                            <input type="text" name="duration" class="form-control" placeholder="e.g. 6 months">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Stipend <span class="text-muted small">(e.g. ₹5000/month)</span></label>
                            <input type="text" name="stipend" class="form-control" placeholder="e.g. ₹5000/month">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Profile Image</label>
                        <input type="file" name="profile_image" class="form-control" accept=".jpg,.jpeg,.png,.gif,.webp" max="512000">
                        <div class="form-text text-muted small">Max 500KB. Allowed: JPG, PNG, GIF, WebP</div>
                    </div>
                </form>
                <div id="add-intern-msg" class="mt-2"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary ms-auto" id="btn-add-intern-submit">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                    Add Intern
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modal-application');
    modal.addEventListener('show.bs.modal', function(event) {
        const btn = event.relatedTarget;
        const id = btn.getAttribute('data-id');
        const content = document.getElementById('application-content');
        content.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';
        fetch('<?php echo url('admin/careers/application-json/'); ?>' + id)
            .then(r => r.json())
            .then(res => {
                if (res.status === 'error') {
                    content.innerHTML = '<div class="alert alert-danger">Application not found.</div>';
                    return;
                }
                const d = res.data;
                const badgeColors = { pending: 'bg-yellow', reviewed: 'bg-blue', shortlisted: 'bg-purple', called_for_interview: 'bg-cyan', rejected: 'bg-danger', hired: 'bg-success', terminated: 'bg-danger', resigned: 'bg-orange' };
                const color = badgeColors[d.status] || 'bg-secondary';
                content.innerHTML = `
                    <div class="d-flex align-items-center mb-4">
                        ${d.profile_image
                            ? '<span class="avatar avatar-xl me-3" style="width:60px;height:60px;background-image:url(<?php echo BASE_URL; ?>file.php?f=' + encodeURIComponent(d.profile_image) + ')"></span>'
                            : '<span class="avatar avatar-xl me-3" style="width:60px;height:60px;font-size:1.5rem;">' + d.name.charAt(0).toUpperCase() + '</span>'}
                        <div>
                            <h4 class="mb-1">${d.name}</h4>
                            <div class="text-muted small">
                                <i class="fas fa-envelope me-1"></i> ${d.email}
                                ${d.phone ? '<span class="mx-2">|</span><i class="fas fa-phone me-1"></i> ' + d.phone : ''}
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <div class="bg-light rounded-3 p-3 text-center">
                                <div class="text-muted small mb-1">Position</div>
                                <div class="fw-bold">${d.career_title || 'N/A'}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="bg-light rounded-3 p-3 text-center">
                                <div class="text-muted small mb-1">Status</div>
                                <div><span class="badge bg-primary text-white" style="font-size: 0.9em;">${(d.status || 'N/A').replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())}</span></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="bg-light rounded-3 p-3 text-center">
                                <div class="text-muted small mb-1">Job Type</div>
                                <div class="fw-bold">${d.job_type || 'N/A'}</div>
                            </div>
                        </div>
                    </div>
                    ${d.address ? `
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Address</label>
                        <p class="text-muted mb-1">${d.address}</p>
                        <p class="text-muted mb-0">${[d.city, d.district, d.state].filter(Boolean).join(', ')}${d.pincode ? ' - ' + d.pincode : ''}</p>
                    </div>` : ''}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Cover Letter</label>
                        <div class="bg-light rounded-3 p-3">
                            <p class="mb-0 text-muted" style="white-space:pre-wrap;">${d.cover_letter || 'N/A'}</p>
                        </div>
                    </div>
                    ${d.resume ? `
                    <div class="mb-3">
                        <label class="form-label fw-semibold">KYC Documents</label>
                        <button type="button" class="btn btn-outline-primary btn-view-kyc" data-kyc='${d.resume}'>
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                            View KYC Documents
                        </button>
                    </div>` : ''}
                    ${d.offer_letter ? `
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Offer Letter</label>
                        <a href="${'<?php echo BASE_URL; ?>file.php?f=' + encodeURIComponent(d.offer_letter)}" target="_blank" class="btn btn-outline-cyan">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 15l2 2l4 -4" /></svg>
                            View Offer Letter
                        </a>
                    </div>` : ''}
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Duration</label>
                            <div class="fw-bold">${d.duration || '<span class="text-muted">Not set</span>'}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Stipend</label>
                            <div class="fw-bold">${d.stipend || '<span class="text-muted">Not set</span>'}</div>
                        </div>
                    </div>
                    <hr class="my-4">
                    <h5 class="fw-bold mb-3">Edit Internship Details</h5>
                    <form class="intern-edit-form" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="app_id" value="${d.id}">
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Duration <span class="text-muted small">(e.g. 6 months)</span></label>
                                <input type="text" name="duration" class="form-control" value="${d.duration || ''}" placeholder="e.g. 6 months">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Stipend <span class="text-muted small">(e.g. ₹5000/month)</span></label>
                                <input type="text" name="stipend" class="form-control" value="${d.stipend || ''}" placeholder="e.g. ₹5000/month">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">KYC Documents <span class="text-muted small">(Aadhaar, PAN, etc.)</span></label>
                            <input type="file" name="documents[]" class="form-control" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" multiple>
                            <div class="form-text text-muted small">Max 2MB per file. Allowed: PDF, DOC, DOCX, JPG, PNG</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Offer Letter</label>
                            <input type="file" name="offer_letter" class="form-control" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                            <div class="form-text text-muted small">Max 2MB. Allowed: PDF, DOC, DOCX, JPG, PNG</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Profile Image</label>
                            <input type="file" name="profile_image" class="form-control" accept=".jpg,.jpeg,.png,.gif,.webp">
                            <div class="form-text text-muted small">Max 500KB. Allowed: JPG, PNG, GIF, WebP</div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" /><path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M14 4l0 4l-6 0l0 -4" /></svg>
                            Save Details
                        </button>
                    </form>
                    <div class="intern-edit-msg mt-2"></div>
                    <hr class="my-4">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Current Status</label>
                            <div class="text-center mb-3">
                                <span class="badge ${color} text-white px-3 py-2" style="font-size:1rem;">${d.status.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase())}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Update Status</label>
                            <form class="status-update-form" action="${'<?php echo url('admin/careers/application-update-status/'); ?>' + d.id}" method="POST">
                                <input type="hidden" name="_csrf_token" value="${document.querySelector('meta[name=\'csrf-token\']')?.getAttribute('content') || ''}">
                                <div class="input-group">
                                    <select name="status" class="form-select">
                                        <option value="pending" ${d.status === 'pending' ? 'selected' : ''}>Pending</option>
                                        <option value="reviewed" ${d.status === 'reviewed' ? 'selected' : ''}>Reviewed</option>
                                        <option value="shortlisted" ${d.status === 'shortlisted' ? 'selected' : ''}>Shortlisted</option>
                                        <option value="called_for_interview" ${d.status === 'called_for_interview' ? 'selected' : ''}>Called for Interview</option>
                                        <option value="rejected" ${d.status === 'rejected' ? 'selected' : ''}>Rejected</option>
                                        <option value="hired" ${d.status === 'hired' ? 'selected' : ''}>Hired</option>
                                        <option value="terminated" ${d.status === 'terminated' ? 'selected' : ''}>Terminated</option>
                                        <option value="resigned" ${d.status === 'resigned' ? 'selected' : ''}>Resigned</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="mt-3 text-muted small">
                        <i class="far fa-clock me-1"></i> Submitted: ${new Date(d.created_at).toLocaleString('en-IN', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' })}
                        ${d.updated_at !== d.created_at ? ' | Updated: ' + new Date(d.updated_at).toLocaleString('en-IN', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' }) : ''}
                    </div>
                `;
            })
            .catch(() => {
                content.innerHTML = '<div class="alert alert-danger">Failed to load application details.</div>';
            });
    });

    // Show KYC document preview modal
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-view-kyc');
        if (!btn) return;
        const raw = btn.getAttribute('data-kyc');
        let docs = [];
        try { docs = JSON.parse(raw); } catch(e) { docs = [raw]; }
        const preview = document.getElementById('kyc-preview-content');
        const imgExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        let html = '';
        docs.forEach(function(url, i) {
            const ext = url.split('.').pop().toLowerCase();
            const fullUrl = url.startsWith('http') ? url : '<?php echo BASE_URL; ?>file.php?f=' + encodeURIComponent(url);
            const fileName = url.split('/').pop();
            if (imgExts.includes(ext)) {
                html += '<div class="card mb-3"><div class="card-body text-center p-3"><img src="' + fullUrl + '" class="img-fluid rounded" style="max-height:50vh;" alt="KYC ' + (i+1) + '"><div class="mt-2"><a href="' + fullUrl + '" target="_blank" class="btn btn-primary btn-sm"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" /><path d="M7 11l5 5l5 -5" /><path d="M12 4l0 12" /></svg> Download</a></div></div></div>';
            } else {
                html += '<div class="card mb-3"><div class="card-body text-center p-3"><svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-muted" width="48" height="48" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /></svg><h6 class="mb-1">' + fileName + '</h6><a href="' + fullUrl + '" target="_blank" class="btn btn-primary btn-sm"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" /><path d="M7 11l5 5l5 -5" /><path d="M12 4l0 12" /></svg> Open</a></div></div>';
            }
        });
        preview.innerHTML = html || '<div class="text-center py-4 text-muted">No documents found.</div>';
        const kycModal = new bootstrap.Modal(document.getElementById('modal-kyc-preview'));
        kycModal.show();
    });

    // Show Offer Letter preview modal
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-view-offer');
        if (!btn) return;
        const path = btn.getAttribute('data-offer');
        const content = document.getElementById('offer-letter-content');
        if (!path) {
            content.innerHTML = '<div class="text-center py-5 text-muted"><svg xmlns="http://www.w3.org/2000/svg" class="icon mb-3" width="64" height="64" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v2m0 4v.01" /><path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75" /></svg><h5>No offer letter uploaded yet.</h5><p>Upload from the Edit section.</p></div>';
        } else {
            const fullUrl = path.startsWith('http') ? path : '<?php echo BASE_URL; ?>file.php?f=' + encodeURIComponent(path);
            const ext = path.split('.').pop().toLowerCase();
            const imgExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (imgExts.includes(ext)) {
                content.innerHTML = '<img src="' + fullUrl + '" class="img-fluid rounded" style="max-height:70vh;" alt="Offer Letter"><div class="mt-3"><a href="' + fullUrl + '" target="_blank" class="btn btn-primary"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" /><path d="M7 11l5 5l5 -5" /><path d="M12 4l0 12" /></svg> Download</a></div>';
            } else {
                content.innerHTML = '<div class="text-center py-4"><svg xmlns="http://www.w3.org/2000/svg" class="icon mb-3 text-muted" width="64" height="64" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 15l2 2l4 -4" /></svg><h5>' + path.split('/').pop() + '</h5><a href="' + fullUrl + '" target="_blank" class="btn btn-primary"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" /><path d="M7 11l5 5l5 -5" /><path d="M12 4l0 12" /></svg> Open Document</a></div>';
            }
        }
        const olModal = new bootstrap.Modal(document.getElementById('modal-offer-letter'));
        olModal.show();
    });

    // Handle Complete Internship button
    let completeInternshipId = null;
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-complete-internship');
        if (!btn) return;
        completeInternshipId = btn.getAttribute('data-id');
        const modal = new bootstrap.Modal(document.getElementById('modal-complete-internship'));
        modal.show();
    });

    document.getElementById('confirm-complete-btn').addEventListener('click', function() {
        if (!completeInternshipId) return;
        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Completing...';
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        fetch('<?php echo url('admin/careers/complete-internship/'); ?>' + completeInternshipId, { 
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: '_csrf_token=' + encodeURIComponent(csrfToken)
        })
            .then(r => r.json())
            .then(res => {
                if (res.status === 'success') {
                    location.reload();
                } else {
                    showToast(res.message || 'Failed to complete.', 'error');
                    btn.disabled = false;
                    btn.innerHTML = 'Complete';
                }
            })
            .catch(() => {
                showToast('Something went wrong.', 'error');
                btn.disabled = false;
                btn.innerHTML = 'Complete';
            });
    });

    // Handle edit form submission via AJAX
    document.addEventListener('submit', function(e) {
        if (e.target.classList.contains('intern-edit-form')) {
            e.preventDefault();
            const form = e.target;
            const msgBox = form.parentElement.querySelector('.intern-edit-msg');
            const btn = form.querySelector('button[type="submit"]');
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Saving...';

            const fd = new FormData(form);
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            if (csrfMeta) {
                fd.append('_csrf_token', csrfMeta.getAttribute('content'));
            }
            fetch('<?php echo url('admin/careers/application-update-fields/'); ?>' + form.app_id.value, {
                method: 'POST',
                body: fd
            })
            .then(r => r.json())
            .then(res => {
                if (res.status === 'success') {
                    msgBox.innerHTML = '<div class="alert alert-success py-2 mb-0">' + res.message + '</div>';
                    // Reload page to reflect changes in the table
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    msgBox.innerHTML = '<div class="alert alert-danger py-2 mb-0">' + (res.message || 'Failed to save.') + '</div>';
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                }
            })
            .catch(() => {
                msgBox.innerHTML = '<div class="alert alert-danger py-2 mb-0">Something went wrong.</div>';
                btn.disabled = false;
                btn.innerHTML = originalText;
            });
        }
    });

    // Handle status update form submission via AJAX
    document.addEventListener('submit', function(e) {
        if (e.target.classList.contains('status-update-form')) {
            e.preventDefault();
            const form = e.target;
            const msgBox = form.closest('.row').parentElement.querySelector('.intern-edit-msg');
            const btn = form.querySelector('button[type="submit"]');
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>...';

            const fd = new FormData(form);
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            if (csrfMeta) {
                fd.append('_csrf_token', csrfMeta.getAttribute('content'));
            }
            
            fetch(form.action, {
                method: 'POST',
                body: fd,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(r => r.json())
            .then(res => {
                if (res.status === 'success') {
                    if (msgBox) msgBox.innerHTML = '<div class="alert alert-success py-2 mb-0">' + res.message + '</div>';
                    setTimeout(() => { location.reload(); }, 1000);
                } else {
                    if (msgBox) msgBox.innerHTML = '<div class="alert alert-danger py-2 mb-0">' + (res.message || 'Failed to save.') + '</div>';
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                }
            })
            .catch(() => {
                if (msgBox) msgBox.innerHTML = '<div class="alert alert-danger py-2 mb-0">Something went wrong.</div>';
                btn.disabled = false;
                btn.innerHTML = originalText;
            });
        }
    });

    // Reset Add Intern form on modal close
    var addInternModal = document.getElementById('modal-add-intern');
    addInternModal.addEventListener('hidden.bs.modal', function() {
        document.getElementById('form-add-intern').reset();
        document.getElementById('add-intern-msg').innerHTML = '';
        var btn = document.getElementById('btn-add-intern-submit');
        btn.disabled = false;
        btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg> Add Intern';
    });

    // Add Intern form submission
    document.getElementById('btn-add-intern-submit').addEventListener('click', function() {
        var form = document.getElementById('form-add-intern');
        var msgBox = document.getElementById('add-intern-msg');
        var btn = this;

        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span> Adding...';

        var fd = new FormData(form);
        var csrfMeta = document.querySelector('meta[name="csrf-token"]');
        if (csrfMeta) {
            fd.append('_csrf_token', csrfMeta.getAttribute('content'));
        }
        fetch('<?php echo url('admin/careers/interns/store'); ?>', {
            method: 'POST',
            body: fd
        })
        .then(function(r) { return r.json(); })
        .then(function(res) {
            if (res.status === 'success') {
                msgBox.innerHTML = '<div class="alert alert-success py-2 mb-0">' + res.message + '</div>';
                setTimeout(function() { location.reload(); }, 1000);
            } else {
                msgBox.innerHTML = '<div class="alert alert-danger py-2 mb-0">' + (res.message || 'Failed to add intern.') + '</div>';
                btn.disabled = false;
                btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg> Add Intern';
            }
        })
        .catch(function() {
            msgBox.innerHTML = '<div class="alert alert-danger py-2 mb-0">Something went wrong.</div>';
            btn.disabled = false;
            btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg> Add Intern';
        });
    });

    // Send Email button - open modal with 3 options
    document.querySelectorAll('.btn-send-email').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var id = this.dataset.id;
            var email = this.dataset.email;
            var name = this.dataset.name;
            var status = this.dataset.status;
            document.getElementById('send-email-name').textContent = name;
            document.getElementById('send-email-addr').textContent = email;

            var olBtn = document.querySelector('.btn-send-offer-letter');
            var certBtn = document.querySelector('.btn-send-certificate');
            var plainBtn = document.querySelector('.btn-send-plain-email');

            olBtn.dataset.id = id;
            olBtn.dataset.email = email;

            certBtn.dataset.id = id;
            certBtn.dataset.email = email;
            certBtn.style.display = status === 'completed' ? '' : 'none';

            plainBtn.href = 'mailto:' + email;

            var sendModal = new bootstrap.Modal(document.getElementById('modal-send-email'));
            sendModal.show();
        });
    });

    // Send Offer Letter from modal
    document.querySelector('.btn-send-offer-letter').addEventListener('click', function() {
        var id = this.dataset.id;
        var email = this.dataset.email;
        var btnEl = this;
        btnEl.disabled = true;
        btnEl.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Sending...';
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        fetch('<?php echo url('admin/careers/send-offer-letter/'); ?>' + id, {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'email=' + encodeURIComponent(email) + '&_csrf_token=' + encodeURIComponent(csrfToken)
        })
        .then(function(r) { return r.json(); })
        .then(function(res) {
            showToast(res.message || (res.status === 'success' ? 'Sent successfully.' : 'Failed.'), res.status === 'success' ? 'success' : 'error');
            btnEl.disabled = false;
            btnEl.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 15l2 2l4 -4" /></svg> Send Offer Letter';
            var sendModal = bootstrap.Modal.getInstance(document.getElementById('modal-send-email'));
            if (sendModal) sendModal.hide();
        })
        .catch(function() {
            showToast('Something went wrong.', 'error');
            btnEl.disabled = false;
            btnEl.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 15l2 2l4 -4" /></svg> Send Offer Letter';
        });
    });

    // Send Certificate from modal
    document.querySelector('.btn-send-certificate').addEventListener('click', function() {
        var id = this.dataset.id;
        var email = this.dataset.email;
        var btnEl = this;
        btnEl.disabled = true;
        btnEl.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Sending...';
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        fetch('<?php echo url('admin/careers/send-certificate/'); ?>' + id, {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'email=' + encodeURIComponent(email) + '&_csrf_token=' + encodeURIComponent(csrfToken)
        })
        .then(function(r) { return r.json(); })
        .then(function(res) {
            showToast(res.message || (res.status === 'success' ? 'Sent successfully.' : 'Failed.'), res.status === 'success' ? 'success' : 'error');
            btnEl.disabled = false;
            btnEl.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 15m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /><path d="M13 17.5v4.5l2 -1l2 1v-4.5" /><path d="M10 19h-5a2 2 0 0 1 -2 -2v-10c0 -1.1 .9 -2 2 -2h14a2 2 0 0 1 2 2v3" /></svg> Send Certificate';
            var sendModal = bootstrap.Modal.getInstance(document.getElementById('modal-send-email'));
            if (sendModal) sendModal.hide();
        })
        .catch(function() {
            showToast('Something went wrong.', 'error');
            btnEl.disabled = false;
            btnEl.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 15m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /><path d="M13 17.5v4.5l2 -1l2 1v-4.5" /><path d="M10 19h-5a2 2 0 0 1 -2 -2v-10c0 -1.1 .9 -2 2 -2h14a2 2 0 0 1 2 2v3" /></svg> Send Certificate';
            });
    });
</script>

<style>
.btn-ghost-secondary { color: #667382; background: transparent; border: none; }
.btn-ghost-secondary:hover { color: #e53e3e; background: rgba(229,62,62,.06); }
tr.d-filter-hide { display: none !important; }
#interns-table tbody tr { transition: opacity .15s ease; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var search = document.getElementById('if-search');
    var status = document.getElementById('if-status');
    var dateFrom = document.getElementById('if-from');
    var dateTo = document.getElementById('if-to');
    var clearBtn = document.getElementById('if-clear');
    var countEl = document.getElementById('if-count');
    var rows = document.querySelectorAll('#interns-table tbody tr[data-status]');
    var totalRows = rows.length;
    var timer = null;

    if (totalRows === 0) return;

    function applyFilters() {
        var q = search.value.toLowerCase().trim();
        var s = status.value;
        var df = dateFrom.value;
        var dt = dateTo.value;
        var visible = 0;
        var hasFilter = q || s || df || dt;

        rows.forEach(function(row) {
            var show = true;

            if (q) {
                var name = row.getAttribute('data-name') || '';
                var email = row.getAttribute('data-email') || '';
                var phone = row.getAttribute('data-phone') || '';
                if (name.indexOf(q) === -1 && email.indexOf(q) === -1 && phone.indexOf(q) === -1) show = false;
            }

            if (show && s) {
                if (row.getAttribute('data-status') !== s) show = false;
            }

            if (show && df) {
                if (row.getAttribute('data-date') < df) show = false;
            }

            if (show && dt) {
                if (row.getAttribute('data-date') > dt) show = false;
            }

            if (show) {
                row.classList.remove('d-filter-hide');
                visible++;
            } else {
                row.classList.add('d-filter-hide');
            }
        });

        if (hasFilter) {
            countEl.textContent = visible + ' of ' + totalRows;
            countEl.classList.remove('d-none');
            clearBtn.classList.remove('d-none');
        } else {
            countEl.classList.add('d-none');
            clearBtn.classList.add('d-none');
        }
    }

    search.addEventListener('input', function() {
        clearTimeout(timer);
        timer = setTimeout(applyFilters, 200);
    });

    status.addEventListener('change', applyFilters);
    dateFrom.addEventListener('change', applyFilters);
    dateTo.addEventListener('change', applyFilters);

    clearBtn.addEventListener('click', function() {
        search.value = '';
        status.value = '';
        dateFrom.value = '';
        dateTo.value = '';
        applyFilters();
        search.focus();
    });
});
</script>

<?php require_once 'app/views/admin/layouts/footer.php'; ?>
