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
                            <li class="breadcrumb-item"><a href="<?php echo url('admin/members'); ?>">Members</a></li>
                            <li class="breadcrumb-item active" aria-current="page"><a href="#">Edit Member</a></li>
                        </ol>
                    </div>
                    <h2 class="page-title fw-bold fs-1">
                        Edit Member: <?php echo htmlspecialchars($member->name); ?>
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <div class="card">
                <form action="<?php echo url('admin/members/update/' . ($member->uuid ?? $member->id)); ?>" method="POST" enctype="multipart/form-data" onsubmit="return validateEditPassword()">
                        <?php echo csrf_field('admin/members/update/' . ($member->uuid ?? $member->id)); ?>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" name="name" class="form-control" required value="<?php echo htmlspecialchars($member->name); ?>">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($member->email); ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Phone Number</label>
                                    <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($member->phone); ?>">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Gender</label>
                                    <select name="gender" class="form-select">
                                        <option value="">Select Gender</option>
                                        <option value="Male" <?php echo $member->gender === 'Male' ? 'selected' : ''; ?>>Male</option>
                                        <option value="Female" <?php echo $member->gender === 'Female' ? 'selected' : ''; ?>>Female</option>
                                        <option value="Other" <?php echo $member->gender === 'Other' ? 'selected' : ''; ?>>Other</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Date of Birth</label>
                                    <input type="date" name="dob" class="form-control" value="<?php echo $member->dob; ?>">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Blood Group</label>
                                    <select name="blood_group" class="form-select">
                                        <option value="">Select Blood Group</option>
                                        <?php foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bg): ?>
                                            <option value="<?php echo $bg; ?>" <?php echo $member->blood_group === $bg ? 'selected' : ''; ?>><?php echo $bg; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Occupation</label>
                                    <input type="text" name="occupation" class="form-control" value="<?php echo htmlspecialchars($member->occupation); ?>">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Designation</label>
                                    <select name="designation_id" class="form-select">
                                        <option value="">No Designation</option>
                                        <?php if (!empty($designations)): ?>
                                            <?php foreach ($designations as $designation): ?>
                                                <option value="<?php echo $designation->id; ?>" <?php echo $member->designation_id == $designation->id ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($designation->name); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <label class="form-label">Current Image</label>
                                <div class="mb-2">
                                    <?php if ($member->image): ?>
                                        <img src="<?php echo file_url($member->image); ?>" alt="Member Image" class="img-thumbnail" style="max-height: 150px;">
                                    <?php else: ?>
                                        <span class="text-muted">No image uploaded</span>
                                    <?php endif; ?>
                                </div>
                                <label class="form-label">Change Image <span class="text-muted small fw-normal">(Max 500 KB)</span></label>
                                <input type="file" name="image" class="form-control" accept="image/*" max="512000">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Address Line</label>
                                    <textarea class="form-control" name="address_line" rows="2" placeholder="Street, locality, landmark, etc."><?php echo htmlspecialchars($member->address_line ?? ''); ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">City / Village</label>
                                    <input type="text" name="city" class="form-control" placeholder="Enter city or village" value="<?php echo htmlspecialchars($member->city ?? ''); ?>">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">State</label>
                                    <select name="state" id="edit_state" class="form-select" data-state-district="true" data-district-id="edit_district">
                                        <option value="">Select State</option>
                                        <?php $statesList = require BASE_PATH . 'app/config/states_districts.php'; ?>
                                        <?php foreach ($statesList as $stateName => $districts): ?>
                                            <option value="<?php echo e($stateName); ?>" <?php echo ($member->state ?? '') === $stateName ? 'selected' : ''; ?>><?php echo e($stateName); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">District</label>
                                    <select name="district" id="edit_district" class="form-select"<?php echo empty($member->state) ? ' disabled' : ''; ?>>
                                        <option value=""><?php echo empty($member->state) ? 'First select a state' : 'Select District'; ?></option>
                                        <?php if (!empty($member->state) && isset($statesList[$member->state])): ?>
                                            <?php foreach ($statesList[$member->state] as $district): ?>
                                                <option value="<?php echo e($district); ?>" <?php echo ($member->district ?? '') === $district ? 'selected' : ''; ?>><?php echo e($district); ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">PIN Code</label>
                                    <input type="text" name="pin" class="form-control" placeholder="Enter PIN code" value="<?php echo htmlspecialchars($member->pin ?? ''); ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">New Password</label>
                                    <input type="text" name="password" id="edit_password" class="form-control" placeholder="Leave blank to keep current" value="">
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
                                    <input type="date" name="join_date" class="form-control" value="<?php echo $member->join_date; ?>" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" name="status">
                                        <option value="active" <?php echo $member->status === 'active' ? 'selected' : ''; ?>>Active</option>
                                        <option value="inactive" <?php echo $member->status === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                                        <option value="pending" <?php echo $member->status === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <a href="<?php echo url('admin/members'); ?>" class="btn btn-link link-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Member</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const STATES_DISTRICTS = <?php echo json_encode(require BASE_PATH . 'app/config/states_districts.php'); ?>;

        function generatePasswordField(inputId) {
            var chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            var password = '';
            for (var i = 0; i < 8; i++) {
                password += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            document.getElementById(inputId).value = password;
            var confirmId = inputId.replace('password', 'password_confirm');
            var confirmEl = document.getElementById(confirmId);
            if (confirmEl) confirmEl.value = password;
        }

        function validateEditPassword() {
            var pwd = document.getElementById('edit_password');
            var confirm = document.getElementById('edit_password_confirm');
            if (pwd && confirm && pwd.value && confirm.value && pwd.value !== confirm.value) {
                alert('Password and confirm password do not match.');
                return false;
            }
            return true;
        }
    </script>
    <script src="<?php echo url('assets/js/states-districts.js'); ?>"></script>

    <?php require_once 'app/views/admin/layouts/footer.php'; ?>
</div>
