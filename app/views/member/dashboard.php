<?php require_once 'app/views/admin/layouts/header.php'; ?>
<?php require_once 'app/views/admin/layouts/sidebar.php'; ?>
<?php require_once 'app/views/admin/layouts/topbar.php'; ?>

<div class="page-wrapper">
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">Dashboard</h2>
                    <div class="text-muted mt-1">Welcome back, <?php echo htmlspecialchars($member->name); ?>!</div>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <span
                        class="badge bg-<?php echo $member->status === 'active' ? 'success text-white' : ($member->status === 'pending' ? 'warning text-dark' : 'secondary text-white'); ?> fs-6">
                        <?php echo ucfirst($member->status); ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <div class="row row-deck row-cards mb-3">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center gap-3">
                                <span class="avatar avatar-lg" style="background: #eef2ff; color: var(--primary);">
                                    <i class="ti ti-id-badge-2" style="font-size: 1.5rem;"></i>
                                </span>
                                <div>
                                    <div class="fs-2 fw-bold"><?php echo htmlspecialchars($member->membership_id); ?>
                                    </div>
                                    <div class="text-muted">Membership ID</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center gap-3">
                                <span class="avatar avatar-lg" style="background: #f0fdf4; color: #16a34a;">
                                    <i class="ti ti-tag" style="font-size: 1.5rem;"></i>
                                </span>
                                <div>
                                    <div class="fs-2 fw-bold"><?php echo htmlspecialchars($designationName); ?></div>
                                    <div class="text-muted">Designation</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center gap-3">
                                <span class="avatar avatar-lg" style="background: #fef2f2; color: #dc2626;">
                                    <i class="ti ti-calendar" style="font-size: 1.5rem;"></i>
                                </span>
                                <div>
                                    <div class="fs-2 fw-bold">
                                        <?php echo date('d M Y', strtotime($member->join_date)); ?></div>
                                    <div class="text-muted">Member Since</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Donation Stats -->
            <div class="row row-deck row-cards mb-3">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center gap-3">
                                <span class="avatar avatar-lg" style="background: #fef2f2; color: #dc2626;">
                                    <i class="ti ti-heart" style="font-size: 1.5rem;"></i>
                                </span>
                                <div>
                                    <div class="fs-2 fw-bold">
                                        <?php echo '₹'; ?><?php echo number_format($donationStats ? ($donationStats->total_amount ?? 0) : 0, 2); ?>
                                    </div>
                                    <div class="text-muted">Total Paid</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center gap-3">
                                <span class="avatar avatar-lg" style="background: #eef2ff; color: var(--primary);">
                                    <i class="ti ti-receipt" style="font-size: 1.5rem;"></i>
                                </span>
                                <div>
                                    <div class="fs-2 fw-bold">
                                        <?php echo e($donationStats ? ($donationStats->total_donations ?? 0) : 0); ?>
                                    </div>
                                    <div class="text-muted">Payments Made</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center gap-3">
                                <span class="avatar avatar-lg" style="background: #f0fdf4; color: #16a34a;">
                                    <i class="ti ti-refresh" style="font-size: 1.5rem;"></i>
                                </span>
                                <div>
                                    <div class="fs-2 fw-bold">
                                        <?php echo e($donationStats ? ($donationStats->recurring_count ?? 0) : 0); ?>
                                    </div>
                                    <div class="text-muted">Recurring</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Donations -->
            <div class="row row-deck row-cards mb-3">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="ti ti-history me-2"></i>Recent Payments</h3>
                            <div class="card-actions">
                                <a href="<?php echo url('member/donations'); ?>"
                                    class="btn btn-sm btn-outline-primary">View All</a>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($recentDonations)): ?>
                                        <tr>
                                            <td colspan="3" class="text-center text-muted py-3">No donations yet.</td>
                                        </tr>
                                    <?php else:
                                        foreach ($recentDonations as $d): ?>
                                            <tr>
                                                <td><?php echo date('d M Y', strtotime($d->created_at)); ?></td>
                                                <td class="fw-bold">
                                                    <?php echo '₹'; ?>        <?php echo number_format($d->amount, 2); ?></td>
                                                <td><span
                                                        class="badge bg-<?php echo $d->status === 'completed' ? 'success' : ($d->status === 'pending' ? 'warning' : 'danger'); ?> text-white"><?php echo ucfirst($d->status); ?></span>
                                                </td>
                                            </tr>
                                        <?php endforeach; endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center py-4">
                            <div class="mb-3">
                                <span class="avatar avatar-xl"
                                    style="background-color: var(--primary); background-image: radial-gradient(circle at 10% 20%, var(--accent) 0%, transparent 50%), radial-gradient(circle at 90% 80%, var(--accent) 0%, transparent 50%), radial-gradient(circle at 50% 50%, var(--primary-dark) 0%, transparent 70%);">
                                    <i class="ti ti-hand-heart text-white" style="font-size: 2rem;"></i>
                                </span>
                            </div>
                            <h4>Support Our Mission</h4>
                            <p class="text-muted small">Your contribution helps us make a difference.</p>
                            <a href="<?php echo url('member/fees'); ?>" class="btn btn-accent w-100">
                                <i class="ti ti-heart me-1"></i> Pay Membership Fee
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="ti ti-address-card me-2"></i>Membership Details</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <tr>
                                        <td class="text-muted pe-3" style="width: 30%; min-width: 100px;">Full Name</td>
                                        <td class="fw-semibold"><?php echo htmlspecialchars($member->name); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Email</td>
                                        <td class="fw-semibold"><?php echo htmlspecialchars($member->email); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Phone</td>
                                        <td class="fw-semibold"><?php echo htmlspecialchars($member->phone); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Gender</td>
                                        <td class="fw-semibold">
                                            <?php echo htmlspecialchars($member->gender ?? 'N/A'); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Date of Birth</td>
                                        <td class="fw-semibold">
                                            <?php echo $member->dob ? date('d M Y', strtotime($member->dob)) : 'N/A'; ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <tr>
                                        <td class="text-muted pe-3" style="width: 30%; min-width: 100px;">Blood Group
                                        </td>
                                        <td class="fw-semibold">
                                            <?php echo htmlspecialchars($member->blood_group ?? 'N/A'); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Occupation</td>
                                        <td class="fw-semibold">
                                            <?php echo htmlspecialchars($member->occupation ?? 'N/A'); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Designation</td>
                                        <td class="fw-semibold"><?php echo htmlspecialchars($designationName); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Join Date</td>
                                        <td class="fw-semibold">
                                            <?php echo date('d M Y', strtotime($member->join_date)); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Status</td>
                                        <td><span
                                                class="badge bg-<?php echo $member->status === 'active' ? 'success text-white' : ($member->status === 'pending' ? 'warning text-dark' : 'secondary text-white'); ?>"><?php echo ucfirst($member->status); ?></span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2">
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="text-muted pe-3" style="width: 30%; min-width: 100px;">Address</td>
                                    <td class="fw-semibold">
                                        <?php echo htmlspecialchars($member->address_line ?? 'N/A'); ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">City / District</td>
                                    <td class="fw-semibold">
                                        <?php echo htmlspecialchars(($member->city ?? '') . ($member->district ? ', ' . $member->district : '')); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">State / PIN</td>
                                    <td class="fw-semibold">
                                        <?php echo htmlspecialchars(($member->state ?? '') . ($member->pin ? ' - ' . $member->pin : '')); ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require_once 'app/views/admin/layouts/footer.php'; ?>
</div>
<style>
    .btn-accent {
        background: var(--accent);
        border: none;
        color: var(--primary);
        font-weight: 700;
        border-radius: 50rem;
    }

    .btn-accent:hover {
        background: var(--accent-dark);
        color: var(--primary);
    }
</style>