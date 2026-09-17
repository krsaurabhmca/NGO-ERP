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
                            <li class="breadcrumb-item active" aria-current="page"><a href="#">Members</a></li>
                        </ol>
                    </div>
                    <h2 class="page-title fw-bold fs-1">
                        Members Management
                    </h2>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="#" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#modal-add-member">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                            Add New Member
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <div class="card">
                <div class="card-body py-2 border-bottom">
                    <div class="row g-2 align-items-center">
                        <div class="col">
                            <div class="input-icon input-icon-sm">
                                <span class="input-icon-addon"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg></span>
                                <input type="text" id="mf-search" class="form-control form-control-sm" placeholder="Search name, email, phone...">
                            </div>
                        </div>
                        <div class="col-auto">
                            <select id="mf-status" class="form-select form-select-sm" style="min-width:120px">
                                <option value="">All Status</option>
                                <option value="active" <?php echo ($status ?? '') === 'active' ? 'selected' : ''; ?>>Active</option>
                                <option value="pending" <?php echo ($status ?? '') === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="inactive" <?php echo ($status ?? '') === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                            </select>
                        </div>
                        <div class="col-auto">
                            <select id="mf-designation" class="form-select form-select-sm" style="min-width:140px">
                                <option value="">All Designations</option>
                                <?php foreach ($designations as $d): ?>
                                    <option value="<?php echo strtolower(htmlspecialchars($d->name ?? $d->title)); ?>"><?php echo htmlspecialchars($d->name ?? $d->title); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-auto d-flex align-items-center gap-1">
                            <input type="date" id="mf-from" class="form-control form-control-sm" style="width:130px" title="From date">
                            <span class="text-muted small">–</span>
                            <input type="date" id="mf-to" class="form-control form-control-sm" style="width:130px" title="To date">
                        </div>
                        <div class="col-auto d-flex align-items-center gap-2">
                            <button type="button" id="mf-clear" class="btn btn-sm btn-ghost-secondary d-none" title="Clear filters">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 6l-12 12" /><path d="M6 6l12 12" /></svg>
                                Clear
                            </button>
                            <span id="mf-count" class="text-muted small d-none"></span>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table card-table table-vcenter text-nowrap datatable" id="members-table">
                        <thead>
                            <tr>
                                <th>Member</th>
                                <th>ID</th>
                                <th>Contact Info</th>
                                <th>Join Date</th>
                                <th>Status</th>
                                <th class="w-1">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($members)): ?>
                                <?php foreach ($members as $member): ?>
                                    <tr data-name="<?php echo strtolower(htmlspecialchars($member->name)); ?>" data-email="<?php echo strtolower(htmlspecialchars($member->email)); ?>" data-phone="<?php echo htmlspecialchars($member->phone); ?>" data-mid="<?php echo strtolower(htmlspecialchars($member->membership_id)); ?>" data-status="<?php echo $member->status; ?>" data-designation="<?php echo strtolower(htmlspecialchars($member->designation_name ?? '')); ?>" data-date="<?php echo date('Y-m-d', strtotime($member->join_date)); ?>">
                                        <td>
                                            <div class="d-flex py-1 align-items-center">
                                                <?php if ($member->image): ?>
                                                    <span class="avatar avatar-sm me-2" style="background-image: url('<?php echo file_url($member->image); ?>')"></span>
                                                <?php else: ?>
                                                    <span class="avatar avatar-sm me-2 d-inline-flex align-items-center justify-content-center" style="background: #e9ecef;">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#6c757d" width="18" height="18"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
                                                    </span>
                                                <?php endif; ?>
                                                <div class="flex-fill">
                                                    <div class="font-weight-medium"><?php echo htmlspecialchars($member->name); ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="text-muted"><?php echo $member->membership_id; ?></span></td>
                                        <td>
                                            <div class="small"><?php echo htmlspecialchars($member->email); ?></div>
                                            <div class="small text-muted"><?php echo htmlspecialchars($member->phone); ?></div>
                                        </td>
                                        <td><?php echo date('d M, Y', strtotime($member->join_date)); ?></td>
                                        <td>
                                            <?php if ($member->status === 'active'): ?>
                                                <span class="badge bg-success me-1"></span> Active
                                            <?php elseif ($member->status === 'pending'): ?>
                                                <span class="badge bg-warning me-1"></span> Pending
                                            <?php else: ?>
                                                <span class="badge bg-secondary me-1"></span> Inactive
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($member->status === 'pending'): ?>
                                                <form method="POST" action="<?php echo url('admin/members/approve/' . ($member->uuid ?? $member->id)); ?>" style="display:inline;">
                                                    <?php echo csrf_field('admin/members/approve/' . ($member->uuid ?? $member->id)); ?>
                                                    <button type="submit" class="btn btn-icon btn-success btn-sm" title="Approve" data-bs-toggle="tooltip">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                            <?php if ($member->status !== 'pending'): ?>
                                                <a href="<?php echo url('admin/members/id-card/' . ($member->uuid ?? $member->id)); ?>" target="_blank" class="btn btn-icon btn-info btn-sm" title="ID Card" data-bs-toggle="tooltip">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 5m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v8a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z" /><path d="M3 10l18 0" /><path d="M7 15l.01 0" /><path d="M11 15l2 0" /></svg>
                                                </a>
                                                <a href="<?php echo url('admin/members/offer-letter/' . ($member->uuid ?? $member->id)); ?>" target="_blank" class="btn btn-icon btn-warning btn-sm" title="Offer Letter" data-bs-toggle="tooltip">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 12l2 2l4 -4" /></svg>
                                                </a>
                                            <?php endif; ?>
                                            <a href="javascript:void(0)" onclick="viewMember('<?php echo ($member->uuid ?? $member->id); ?>')" class="btn btn-icon btn-secondary btn-sm" title="View" data-bs-toggle="tooltip">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                                            </a>
                                            <?php if (($status_filter ?? '') !== 'pending'): ?>
                                                <a href="javascript:void(0)" onclick="editMember('<?php echo ($member->uuid ?? $member->id); ?>')" class="btn btn-icon btn-primary btn-sm" title="Edit" data-bs-toggle="tooltip">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" /><path d="M13.5 6.5l4 4" /></svg>
                                                </a>
                                            <?php endif; ?>
                                            <a href="javascript:void(0)" onclick="confirmDelete('<?php echo url('admin/members/delete/' . ($member->uuid ?? $member->id)); ?>', 'admin/members/delete/<?php echo ($member->uuid ?? $member->id); ?>', '<?php echo csrf_token('admin/members/delete/' . ($member->uuid ?? $member->id)); ?>')" class="btn btn-icon btn-danger btn-sm" title="Delete" data-bs-toggle="tooltip">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">No members found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Member Modal -->
    <div class="modal modal-blur fade" id="modal-add-member" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="<?php echo url('admin/members/store'); ?>" method="POST" enctype="multipart/form-data">
                            <?php echo csrf_field('admin/members/store'); ?>
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Member</h5>
                        <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" name="name" class="form-control" required placeholder="Enter member's full name">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" name="email" class="form-control" placeholder="example@email.com">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Phone Number</label>
                                    <input type="text" name="phone" class="form-control" placeholder="Phone number">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Gender</label>
                                    <select name="gender" class="form-select">
                                        <option value="">Select Gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Date of Birth</label>
                                    <input type="date" name="dob" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Blood Group</label>
                                    <select name="blood_group" class="form-select">
                                        <option value="">Select Blood Group</option>
                                        <option value="A+">A+</option>
                                        <option value="A-">A-</option>
                                        <option value="B+">B+</option>
                                        <option value="B-">B-</option>
                                        <option value="AB+">AB+</option>
                                        <option value="AB-">AB-</option>
                                        <option value="O+">O+</option>
                                        <option value="O-">O-</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Occupation</label>
                                    <input type="text" name="occupation" class="form-control" placeholder="Enter occupation">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Designation</label>
                                    <select name="designation_id" class="form-select">
                                        <option value="">No Designation</option>
                                        <?php if (!empty($designations)): ?>
                                            <?php foreach ($designations as $designation): ?>
                                                <option value="<?php echo $designation->id; ?>"><?php echo htmlspecialchars($designation->name); ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Upload Image <span class="text-muted small fw-normal">(Max 500 KB)</span></label>
                                    <input type="file" name="image" class="form-control" accept="image/*" max="512000">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Address Line</label>
                                    <textarea class="form-control" name="address_line" rows="2" placeholder="Street, locality, landmark, etc."></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">City / Village</label>
                                    <input type="text" name="city" class="form-control" placeholder="Enter city or village">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">State</label>
                                    <select name="state" class="form-select" data-state-district="true" data-district-id="add_district">
                                        <option value="">Select State</option>
                                        <?php $statesList = require BASE_PATH . 'app/config/states_districts.php'; ?>
                                        <?php foreach ($statesList as $stateName => $districts): ?>
                                            <option value="<?php echo e($stateName); ?>"><?php echo e($stateName); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">District</label>
                                    <select name="district" id="add_district" class="form-select" disabled>
                                        <option value="">First select a state</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">PIN Code</label>
                                    <input type="text" name="pin" class="form-control" placeholder="Enter PIN code">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Join Date</label>
                                    <input type="date" name="join_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" name="status">
                                        <option value="active" selected>Active</option>
                                        <option value="inactive">Inactive</option>
                                        <option value="pending">Pending</option>
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
                            Add Member
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Member Modal -->
    <div class="modal modal-blur fade" id="modal-edit-member" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="edit-member-form" action="" method="POST" enctype="multipart/form-data" onsubmit="return validateEditPassword()">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Member</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" name="name" id="edit_name" class="form-control" required placeholder="Enter member's full name">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" name="email" id="edit_email" class="form-control" placeholder="example@email.com">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Phone Number</label>
                                    <input type="text" name="phone" id="edit_phone" class="form-control" placeholder="Phone number">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Gender</label>
                                    <select name="gender" id="edit_gender" class="form-select">
                                        <option value="">Select Gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Date of Birth</label>
                                    <input type="date" name="dob" id="edit_dob" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Blood Group</label>
                                    <select name="blood_group" id="edit_blood_group" class="form-select">
                                        <option value="">Select Blood Group</option>
                                        <option value="A+">A+</option>
                                        <option value="A-">A-</option>
                                        <option value="B+">B+</option>
                                        <option value="B-">B-</option>
                                        <option value="AB+">AB+</option>
                                        <option value="AB-">AB-</option>
                                        <option value="O+">O+</option>
                                        <option value="O-">O-</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Occupation</label>
                                    <input type="text" name="occupation" id="edit_occupation" class="form-control" placeholder="Enter occupation">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Designation</label>
                                    <select name="designation_id" id="edit_designation_id" class="form-select">
                                        <option value="">No Designation</option>
                                        <?php if (!empty($designations)): ?>
                                            <?php foreach ($designations as $designation): ?>
                                                <option value="<?php echo $designation->id; ?>"><?php echo htmlspecialchars($designation->name); ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <label class="form-label">Current Image</label>
                                <div class="mb-2" id="current_image_container">
                                    <!-- Current image will be displayed here -->
                                </div>
                                <label class="form-label">Change Image <span class="text-muted small fw-normal">(Max 500 KB)</span></label>
                                <input type="file" name="image" class="form-control" accept="image/*" max="512000">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Address Line</label>
                                    <textarea class="form-control" name="address_line" id="edit_address_line" rows="2" placeholder="Street, locality, landmark, etc."></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">City / Village</label>
                                    <input type="text" name="city" id="edit_city" class="form-control" placeholder="Enter city or village">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">State</label>
                                    <select name="state" id="edit_state" class="form-select" data-state-district="true" data-district-id="edit_district">
                                        <option value="">Select State</option>
                                        <?php $statesList = require BASE_PATH . 'app/config/states_districts.php'; ?>
                                        <?php foreach ($statesList as $stateName => $districts): ?>
                                            <option value="<?php echo e($stateName); ?>"><?php echo e($stateName); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">District</label>
                                    <select name="district" id="edit_district" class="form-select" disabled>
                                        <option value="">First select a state</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">PIN Code</label>
                                    <input type="text" name="pin" id="edit_pin" class="form-control" placeholder="Enter PIN code">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">New Password</label>
                                    <input type="text" name="password" id="edit_password" class="form-control" placeholder="Leave blank to keep current">
                                    <small class="text-muted">Leave empty to keep existing password</small>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Confirm Password</label>
                                    <input type="text" name="password_confirm" id="edit_password_confirm" class="form-control" placeholder="Re-enter new password">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Join Date</label>
                                    <input type="date" name="join_date" id="edit_join_date" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" name="status" id="edit_status">
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                        <option value="pending">Pending</option>
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
                            Update Member
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Member Modal -->
    <div class="modal modal-blur fade" id="modal-view-member" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">View Member Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="card shadow-none border-0 mb-0">
                        <div class="card-body p-4">
                            <div class="row align-items-center mb-4 pb-4 border-bottom">
                                <div class="col-auto">
                                    <span class="avatar avatar-xl rounded" id="view_image" style="width: 100px; height: 100px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);"></span>
                                </div>
                                <div class="col">
                                    <h2 class="mb-1 fw-bold text-dark" id="view_name"></h2>
                                    <div class="text-muted fw-medium mb-2" id="view_designation" style="font-size: 1.1rem;"></div>
                                    <div class="d-flex align-items-center flex-wrap gap-2">
                                        <div id="view_status"></div>
                                        <div id="view_phone_container">
                                            <a href="#" id="view_phone_link" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-medium">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2" /></svg>
                                                <span id="view_phone_text"></span>
                                            </a>
                                        </div>
                                        <div id="view_email_container">
                                            <a href="#" id="view_email_link" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-medium">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z" /><path d="M3 7l9 6l9 -6" /></svg>
                                                <span id="view_email_text"></span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row row-cards">
                                <div class="col-md-6">
                                    <div class="datagrid">
                                        <div class="datagrid-item">
                                            <div class="datagrid-title">Membership ID</div>
                                            <div class="datagrid-content fw-bold text-primary" id="view_membership_id"></div>
                                        </div>
                                        <div class="datagrid-item">
                                            <div class="datagrid-title">Date of Birth</div>
                                            <div class="datagrid-content" id="view_dob"></div>
                                        </div>
                                        <div class="datagrid-item">
                                            <div class="datagrid-title">Gender</div>
                                            <div class="datagrid-content" id="view_gender"></div>
                                        </div>
                                        <div class="datagrid-item">
                                            <div class="datagrid-title">Blood Group</div>
                                            <div class="datagrid-content text-danger fw-bold" id="view_blood_group"></div>
                                        </div>
                                        <div class="datagrid-item">
                                            <div class="datagrid-title">Password</div>
                                            <div class="datagrid-content" id="view_password">
                                                <span class="text-muted small">Not set</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="datagrid">
                                        <div class="datagrid-item">
                                            <div class="datagrid-title">Join Date</div>
                                            <div class="datagrid-content" id="view_join_date"></div>
                                        </div>
                                        <div class="datagrid-item">
                                            <div class="datagrid-title">Occupation</div>
                                            <div class="datagrid-content" id="view_occupation"></div>
                                        </div>
                                        <div class="datagrid-item" style="grid-column: span 2;">
                                            <div class="datagrid-title">Address</div>
                                            <div class="datagrid-content" id="view_address"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-sm px-4" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Password Generated Modal (shown after password generation) -->
    <div class="modal fade" id="modal-password-generated" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header" style="background-color: var(--primary); background-image: radial-gradient(circle at 10% 20%, var(--accent) 0%, transparent 50%), radial-gradient(circle at 90% 80%, var(--accent) 0%, transparent 50%), radial-gradient(circle at 50% 50%, var(--primary-dark) 0%, transparent 70%);">
                    <h5 class="modal-title text-white fw-bold">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3a12 12 0 0 0 8.5 3a12 12 0 0 1 -8.5 15a12 12 0 0 1 -8.5 -15a12 12 0 0 0 8.5 -3"/><path d="M12 3v18"/></svg>
                        Password Generated
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="d-flex align-items-center mb-3" id="pwd-gen-member-info">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-success bg-opacity-10 me-3" style="width: 48px; height: 48px;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="text-success" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0" id="pwd-gen-name"></h6>
                            <small class="text-muted" id="pwd-gen-membership-id"></small>
                        </div>
                    </div>
                    <div class="alert alert-info border-0 rounded-3 mb-3">
                        <div class="fw-semibold mb-2">Generated Password</div>
                        <div class="input-group">
                            <input type="text" class="form-control form-control-lg text-center fw-bold text-primary bg-white" id="pwd-gen-password" readonly style="font-family: monospace; letter-spacing: 3px; font-size: 1.3rem;">
                            <button class="btn btn-outline-primary" type="button" onclick="copyPasswordToClipboard()" title="Copy password">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 8m0 2a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-8a2 2 0 0 1 -2 -2z"/><path d="M16 8v-2a2 2 0 0 0 -2 -2h-8a2 2 0 0 0 -2 2v8a2 2 0 0 0 2 2h2"/></svg>
                            </button>
                        </div>
                        <div class="mt-2 small text-muted">Share this password with the member securely.</div>
                    </div>
                </div>
                <div class="modal-footer d-flex gap-2">
                    <button type="button" class="btn btn-outline-secondary flex-fill" onclick="copyPasswordToClipboard()">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 8m0 2a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-8a2 2 0 0 1 -2 -2z"/><path d="M16 8v-2a2 2 0 0 0 -2 -2h-8a2 2 0 0 0 -2 2v8a2 2 0 0 0 2 2h2"/></svg>
                        Copy
                    </button>
                    <button type="button" class="btn btn-primary flex-fill" data-bs-dismiss="modal">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                        Got it
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Credentials Modal (shown after member approval) -->
    <?php if (isset($_SESSION['member_credentials'])): 
        $creds = $_SESSION['member_credentials'];
        unset($_SESSION['member_credentials']);
    ?>
    <div class="modal fade" id="modal-credentials" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header" style="background-color: var(--primary); background-image: radial-gradient(circle at 10% 20%, var(--accent) 0%, transparent 50%), radial-gradient(circle at 90% 80%, var(--accent) 0%, transparent 50%), radial-gradient(circle at 50% 50%, var(--primary-dark) 0%, transparent 70%);">
                    <h5 class="modal-title text-white fw-bold">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                        Member Approved
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-success bg-opacity-10 me-3" style="width: 48px; height: 48px;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="text-success" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0"><?php echo htmlspecialchars($creds['name']); ?></h6>
                            <small class="text-muted"><?php echo htmlspecialchars($creds['membership_id']); ?></small>
                        </div>
                    </div>
                    <div class="alert alert-info border-0 rounded-3 mb-0">
                        <div class="fw-semibold mb-2">Login Credentials</div>
                        <div class="mb-1"><strong>Email:</strong> <?php echo htmlspecialchars($creds['email']); ?></div>
                        <div class="mb-1"><strong>Password:</strong> <span class="fw-bold text-primary"><?php echo htmlspecialchars($creds['password']); ?></span></div>
                        <div class="mt-2 small text-muted">Share these credentials with the member. They can use them to log in to their account.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary w-100" data-bs-dismiss="modal">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                        Got it
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var modal = new bootstrap.Modal(document.getElementById('modal-credentials'));
            modal.show();
        });

        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                showToast('Password copied to clipboard!', 'success');
            }).catch(function() {
                // Fallback
                var textarea = document.createElement('textarea');
                textarea.value = text;
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);
                showToast('Password copied to clipboard!', 'success');
            });
        }

        function generateMemberPassword(id) {
            const csrfToken = document.querySelector('meta[name="csrf-token-generate-password"]')?.getAttribute('content');
            fetch('<?php echo url('admin/members/generate-password/'); ?>' + id, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    _csrf_token: csrfToken,
                    _csrf_action: 'admin/members/generate-password'
                })
            })
                .then(response => response.json())
                .then(result => {
                    if (result.status === 'success') {
                        document.getElementById('pwd-gen-password').value = result.password;
                        const modal = new bootstrap.Modal(document.getElementById('modal-password-generated'));
                        modal.show();
                        viewMember(id);
                    } else {
                        showToast(result.message || 'Failed to generate password.', 'error');
                    }
                })
                .catch(function() {
                    showToast('An error occurred.', 'error');
                });
        }

        function copyPasswordToClipboard() {
            const pwdInput = document.getElementById('pwd-gen-password');
            pwdInput.select();
            navigator.clipboard.writeText(pwdInput.value).then(function() {
                showToast('Password copied to clipboard!', 'success');
            }).catch(function() {
                document.execCommand('copy');
                showToast('Password copied to clipboard!', 'success');
            });
        }

        function generatePasswordField(inputId) {
            var chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            var password = '';
            for (var i = 0; i < 8; i++) {
                password += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            document.getElementById(inputId).value = password;
            // Also auto-fill confirm if it exists
            var confirmId = inputId.replace('password', 'password_confirm');
            var confirmEl = document.getElementById(confirmId);
            if (confirmEl) confirmEl.value = password;
        }

        function validateEditPassword() {
            var pwd = document.getElementById('edit_password');
            var confirm = document.getElementById('edit_password_confirm');
            if (pwd && confirm && pwd.value && confirm.value && pwd.value !== confirm.value) {
                showToast('Password and confirm password do not match.', 'error', 'Error');
                return false;
            }
            return true;
        }
    </script>
    <?php endif; ?>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Check for edit parameter in URL
            const urlParams = new URLSearchParams(window.location.search);
            const editId = urlParams.get('edit');
            if (editId) {
                editMember(editId);
                // Clean up the URL
                window.history.replaceState({}, document.title, window.location.pathname);
            }
        });

        function viewMember(id) {
            fetch('<?php echo url('admin/members/get/'); ?>' + id)
                .then(response => response.json())
                .then(result => {
                    if (result.status === 'success') {
                        const member = result.data;
                        
                        document.getElementById('view_name').textContent = member.name;
                        document.getElementById('view_designation').textContent = member.designation_name;
                        document.getElementById('view_membership_id').textContent = member.membership_id;
                        
                        // Handle Phone
                        if (member.phone) {
                            document.getElementById('view_phone_container').style.display = 'flex';
                            document.getElementById('view_phone_text').textContent = 'Call ' + member.phone;
                            document.getElementById('view_phone_link').href = 'tel:' + member.phone;
                        } else {
                            document.getElementById('view_phone_container').style.display = 'none';
                        }
                        
                        // Handle Email
                        if (member.email) {
                            document.getElementById('view_email_container').style.display = 'flex';
                            document.getElementById('view_email_text').textContent = 'Mail ' + member.email;
                            document.getElementById('view_email_link').href = 'mailto:' + member.email;
                        } else {
                            document.getElementById('view_email_container').style.display = 'none';
                        }

                        document.getElementById('view_gender').textContent = member.gender || 'N/A';
                        document.getElementById('view_dob').textContent = member.dob ? new Date(member.dob).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) : 'N/A';
                        document.getElementById('view_blood_group').textContent = member.blood_group || 'N/A';
                        
                        // Handle Password
                        const viewPassword = document.getElementById('view_password');
                        if (member.password_plain) {
                            viewPassword.innerHTML = '<span class="text-muted" style="font-family: monospace; letter-spacing: 2px;">' + member.password_plain + '</span>'
                                + '<button type="button" class="btn btn-icon btn-sm btn-outline-secondary ms-2" onclick="copyToClipboard(\'' + member.password_plain + '\')" title="Copy password" data-bs-toggle="tooltip">'
                                + '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 8m0 2a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-8a2 2 0 0 1 -2 -2z"/><path d="M16 8v-2a2 2 0 0 0 -2 -2h-8a2 2 0 0 0 -2 2v8a2 2 0 0 0 2 2h2"/></svg>'
                                + '</button>';
                        } else {
                            const generateUrl = '<?php echo url('admin/members/generate-password/'); ?>' + member.id;
                            viewPassword.innerHTML = '<span class="text-muted small">Not set</span>'
                                + '<button type="button" class="btn btn-icon btn-sm btn-outline-success ms-2" onclick="generateMemberPassword(' + member.id + ')" title="Generate password" data-bs-toggle="tooltip">'
                                + '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3a12 12 0 0 0 8.5 3a12 12 0 0 1 -8.5 15a12 12 0 0 1 -8.5 -15a12 12 0 0 0 8.5 -3"/><path d="M12 3v18"/><path d="M12 11l4 -4"/><path d="M12 13l-4 -4"/></svg>'
                                + '</button>';
                        }
                        document.getElementById('view_occupation').textContent = member.occupation || 'N/A';
                        document.getElementById('view_join_date').textContent = new Date(member.join_date).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
                        // Build formatted address from structured fields with fallback to old address
                        let formattedAddress = '';
                        if (member.address_line || member.city || member.district || member.state || member.pin) {
                            const parts = [];
                            if (member.address_line) parts.push(member.address_line);
                            if (member.city) parts.push(member.city);
                            if (member.district) parts.push(member.district);
                            if (member.state) parts.push(member.state);
                            if (member.pin) parts.push(member.pin);
                            formattedAddress = parts.join(', ');
                        } else if (member.address) {
                            formattedAddress = member.address;
                        } else {
                            formattedAddress = 'N/A';
                        }
                        document.getElementById('view_address').textContent = formattedAddress;

                        // Status badge
                        const statusContainer = document.getElementById('view_status');
                        if (member.status === 'active') {
                            statusContainer.innerHTML = '<span class="badge bg-success text-white">Active</span>';
                        } else if (member.status === 'pending') {
                            statusContainer.innerHTML = '<span class="badge bg-warning">Pending</span>';
                        } else {
                            statusContainer.innerHTML = '<span class="badge bg-secondary">Inactive</span>';
                        }

                        // Avatar
                        if (member.image) {
                            document.getElementById('view_image').style.backgroundImage = "url('<?php echo BASE_URL; ?>file.php?f=" + encodeURIComponent(member.image) + "')";
                        } else {
                            document.getElementById('view_image').innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#6c757d" width="64" height="64"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>';
                            document.getElementById('view_image').style.backgroundImage = 'none';
                            document.getElementById('view_image').style.display = 'flex';
                            document.getElementById('view_image').style.alignItems = 'center';
                            document.getElementById('view_image').style.justifyContent = 'center';
                            document.getElementById('view_image').style.background = '#e9ecef';
                        }

                        const modal = new bootstrap.Modal(document.getElementById('modal-view-member'));
                        modal.show();
                    } else {
                        showToast(result.message || 'Failed to fetch member data', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('An error occurred while fetching member data', 'error');
                });
        }

        function editMember(id) {
            fetch('<?php echo url('admin/members/get/'); ?>' + id)
                .then(response => response.json())
                .then(result => {
                    if (result.status === 'success') {
                        const member = result.data;
                        document.getElementById('edit-member-form').action = '<?php echo url('admin/members/update/'); ?>' + id;
                        document.getElementById('edit_name').value = member.name;
                        document.getElementById('edit_email').value = member.email;
                        document.getElementById('edit_phone').value = member.phone;
                        document.getElementById('edit_gender').value = member.gender || '';
                        document.getElementById('edit_dob').value = member.dob;
                        document.getElementById('edit_blood_group').value = member.blood_group || '';
                        document.getElementById('edit_occupation').value = member.occupation;
                        document.getElementById('edit_designation_id').value = member.designation_id || '';
                        document.getElementById('edit_address_line').value = member.address_line || '';
                        document.getElementById('edit_city').value = member.city || '';
                        // Set state first to populate districts, then set district
                        const editState = document.getElementById('edit_state');
                        editState.value = member.state || '';
                        editState.dispatchEvent(new Event('change'));
                        document.getElementById('edit_district').value = member.district || '';
                        document.getElementById('edit_pin').value = member.pin || '';
                        document.getElementById('edit_join_date').value = member.join_date;
                        document.getElementById('edit_status').value = member.status;

                        // Handle image
                        const imageContainer = document.getElementById('current_image_container');
                        if (member.image) {
                            imageContainer.innerHTML = `<img src="<?php echo BASE_URL; ?>file.php?f=${encodeURIComponent(member.image)}" class="img-thumbnail" style="max-height: 100px;">`;
                        } else {
                            imageContainer.innerHTML = '<span class="text-muted">No image uploaded</span>';
                        }

                        // Show modal
                        const modal = new bootstrap.Modal(document.getElementById('modal-edit-member'));
                        modal.show();
                    } else {
                        showToast(result.message || 'Failed to fetch member data', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('An error occurred while fetching member data', 'error');
                });
        }
    </script>

    <script>
        const STATES_DISTRICTS = <?php echo json_encode(require BASE_PATH . 'app/config/states_districts.php'); ?>;
    </script>
    <script src="<?php echo url('assets/js/states-districts.js'); ?>"></script>

    <style>
    @media (max-width: 575.98px) {
        .card-table td:last-child .d-flex { gap: 0.15rem !important; }
        .card-table td:last-child .btn-icon { width: 1.5rem; height: 1.5rem; padding: 0 !important; }
        .card-table td:last-child .btn-icon svg { width: 0.85rem; height: 0.85rem; }
    }
    .btn-ghost-secondary { color: #667382; background: transparent; border: none; }
    .btn-ghost-secondary:hover { color: #e53e3e; background: rgba(229,62,62,.06); }
    tr.d-filter-hide { display: none !important; }
    #members-table tbody tr { transition: opacity .15s ease; }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var search = document.getElementById('mf-search');
        var status = document.getElementById('mf-status');
        var designation = document.getElementById('mf-designation');
        var dateFrom = document.getElementById('mf-from');
        var dateTo = document.getElementById('mf-to');
        var clearBtn = document.getElementById('mf-clear');
        var countEl = document.getElementById('mf-count');
        var rows = document.querySelectorAll('#members-table tbody tr[data-status]');
        var totalRows = rows.length;
        var timer = null;

        function applyFilters() {
            var q = search.value.toLowerCase().trim();
            var s = status.value;
            var d = designation.value;
            var df = dateFrom.value;
            var dt = dateTo.value;
            var visible = 0;
            var hasFilter = q || s || d || df || dt;

            rows.forEach(function(row) {
                var show = true;

                if (q) {
                    var name = row.getAttribute('data-name') || '';
                    var email = row.getAttribute('data-email') || '';
                    var phone = row.getAttribute('data-phone') || '';
                    var mid = row.getAttribute('data-mid') || '';
                    if (name.indexOf(q) === -1 && email.indexOf(q) === -1 && phone.indexOf(q) === -1 && mid.indexOf(q) === -1) show = false;
                }

                if (show && s) {
                    if (row.getAttribute('data-status') !== s) show = false;
                }

                if (show && d) {
                    if (row.getAttribute('data-designation') !== d) show = false;
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
        designation.addEventListener('change', applyFilters);
        dateFrom.addEventListener('change', applyFilters);
        dateTo.addEventListener('change', applyFilters);

        clearBtn.addEventListener('click', function() {
            search.value = '';
            status.value = '';
            designation.value = '';
            dateFrom.value = '';
            dateTo.value = '';
            applyFilters();
            search.focus();
        });

        // Initialize filters on page load to respect pre-filled values
        applyFilters();
    });
    </script>
    <?php require_once 'app/views/admin/layouts/footer.php'; ?>
</div>
