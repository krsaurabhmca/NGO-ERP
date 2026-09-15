<?php require_once 'app/views/admin/layouts/header.php'; ?>
<?php require_once 'app/views/admin/layouts/sidebar.php'; ?>
<?php require_once 'app/views/admin/layouts/topbar.php'; ?>

<div class="page-wrapper">
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">My Profile</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="ti ti-user-edit me-2"></i>Profile Information</h3>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <?php if ($member->image): ?>
                            <span class="avatar avatar-xl rounded" style="background-image: url(<?php echo file_url($member->image); ?>)"></span>
                        <?php else: ?>
                            <span class="avatar avatar-xl rounded" style="background: #eef2ff; color: #003566; font-size: 2rem; font-weight: 700;">
                                <?php echo strtoupper(substr($member->name, 0, 1)); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <tr><td class="text-muted pe-3" style="width: 30%; min-width: 100px;">Membership ID</td><td class="fw-semibold"><?php echo htmlspecialchars($member->membership_id); ?></td></tr>
                                    <tr><td class="text-muted">Full Name</td><td class="fw-semibold"><?php echo htmlspecialchars($member->name); ?></td></tr>
                                    <tr><td class="text-muted">Email</td><td class="fw-semibold"><?php echo htmlspecialchars($member->email); ?></td></tr>
                                    <tr><td class="text-muted">Phone</td><td class="fw-semibold"><?php echo htmlspecialchars($member->phone); ?></td></tr>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <tr><td class="text-muted pe-3" style="width: 30%; min-width: 100px;">Gender</td><td class="fw-semibold"><?php echo htmlspecialchars($member->gender ?? 'N/A'); ?></td></tr>
                                    <tr><td class="text-muted">Date of Birth</td><td class="fw-semibold"><?php echo $member->dob ? date('d M Y', strtotime($member->dob)) : 'N/A'; ?></td></tr>
                                    <tr><td class="text-muted">Blood Group</td><td class="fw-semibold"><?php echo htmlspecialchars($member->blood_group ?? 'N/A'); ?></td></tr>
                                    <tr><td class="text-muted">Occupation</td><td class="fw-semibold"><?php echo htmlspecialchars($member->occupation ?? 'N/A'); ?></td></tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <a href="<?php echo url('member/offer-letter'); ?>" target="_blank" class="btn btn-warning">
                        <i class="ti ti-file-description me-1"></i> Offer Letter
                    </a>
                </div>
            </div>
        </div>
    </div>

    <?php require_once 'app/views/admin/layouts/footer.php'; ?>
</div>