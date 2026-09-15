<?php require_once 'app/views/admin/layouts/header.php'; ?>
<?php require_once 'app/views/admin/layouts/sidebar.php'; ?>

<div class="page-wrapper">
    <?php require_once 'app/views/admin/layouts/topbar.php'; ?>

    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <ol class="breadcrumb" aria-label="breadcrumbs">
                        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                    </ol>
                    <h2 class="page-title fw-bold fs-1">Dashboard</h2>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <a href="<?php echo url('admin/finance/donations'); ?>" class="btn btn-primary">
                        <span class="fw-bold me-1">&#8377;</span>
                        View Donations
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <div class="row row-deck row-cards g-3 mb-3">
                <div class="col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <div class="rounded-3 bg-success-lt p-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-success" width="32" height="32" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2" /><path d="M12 3v3m0 12v3" /></svg>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="text-muted small">Total Donations</div>
                                    <div class="fw-bold fs-3">₹<?php echo number_format($stats['donations'], 2); ?></div>
                                    <div class="text-muted small">Balance: ₹<?php echo number_format($stats['balance'], 2); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <div class="rounded-3 bg-blue-lt p-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-blue" width="32" height="32" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 3.13a4 4 0 0 1 0 7.75" /><path d="M21 21v-2a4 4 0 0 0 -3 -3.85" /></svg>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="text-muted small">Members</div>
                                    <div class="fw-bold fs-3"><?php echo $stats['members']; ?></div>
                                    <div class="text-muted small">Total registered</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <div class="rounded-3 bg-purple-lt p-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-purple" width="32" height="32" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 21l1.65 -3.8a9 9 0 1 1 3.4 2.9l-5.05 .9" /><path d="M9 10a.5 .5 0 0 0 1 0v-1a.5 .5 0 0 0 -1 0v1a5 5 0 0 0 5 5h1a.5 .5 0 0 0 0 -1h-1a.5 .5 0 0 0 0 1" /></svg>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="text-muted small">Donors</div>
                                    <div class="fw-bold fs-3"><?php echo $stats['donors']; ?></div>
                                    <div class="text-muted small">Active supporters</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <div class="rounded-3 bg-orange-lt p-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-orange" width="32" height="32" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 3.1a9 9 0 0 1 9 9.1" /><path d="M7 9a5 5 0 0 1 5 -5" /><path d="M12 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M12 12l0 8" /></svg>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="text-muted small">Projects</div>
                                    <div class="fw-bold fs-3"><?php echo $stats['ongoing_projects']; ?> / <?php echo $stats['projects']; ?></div>
                                    <div class="text-muted small">Ongoing / Total</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <div class="rounded-3 bg-cyan-lt p-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-cyan" width="32" height="32" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 5m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v2l0 0l-5 3l-5 -3l0 -2z" fill="currentColor" /><path d="M5 11v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" /><path d="M11 16h2" /></svg>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="text-muted small">Campaigns</div>
                                    <div class="fw-bold fs-3"><?php echo $stats['campaigns']; ?></div>
                                    <div class="text-muted small">Active campaigns</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <div class="rounded-3 bg-pink-lt p-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-pink" width="32" height="32" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M9 12l2 2l4 -4" /></svg>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="text-muted small">Beneficiaries</div>
                                    <div class="fw-bold fs-3"><?php echo $stats['beneficiaries']; ?></div>
                                    <div class="text-muted small">Total helped</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <div class="rounded-3 bg-warning-lt p-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-warning" width="32" height="32" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 8l0 4" /><path d="M12 16l.01 0" /></svg>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="text-muted small">Pending</div>
                                    <div class="fw-bold fs-3">₹<?php echo number_format($stats['pending_donations'], 2); ?></div>
                                    <div class="text-muted small">Pending donations</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <div class="rounded-3 bg-secondary-lt p-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-secondary" width="32" height="32" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z" /><path d="M3 7l9 6l9 -6" /></svg>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="text-muted small">Inquiries</div>
                                    <div class="fw-bold fs-3"><?php echo $stats['pending_contacts']; ?></div>
                                    <div class="text-muted small">Unread messages</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Recent Donations</h3>
                            <div class="card-actions">
                                <a href="<?php echo url('admin/finance/donations'); ?>">View all</a>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm table-vcenter card-table">
                                <thead>
                                    <tr>
                                        <th>Donor</th>
                                        <th>Amount</th>
                                        <th>Method</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($recentDonations)): ?>
                                        <?php foreach ($recentDonations as $d): ?>
                                            <tr>
                                                <td class="fw-semibold"><?php echo htmlspecialchars($d->donor_name); ?></td>
                                                <td class="fw-bold text-success">₹<?php echo number_format($d->amount, 2); ?></td>
                                                <td><?php echo ucfirst($d->payment_method ?: 'Offline'); ?></td>
                                                <td>
                                                    <span class="badge text-white <?php echo $d->status === 'completed' ? 'bg-success' : ($d->status === 'pending' ? 'bg-warning' : 'bg-danger'); ?>"><?php echo ucfirst($d->status); ?></span>
                                                </td>
                                                <td class="text-muted small"><?php echo date('d M Y', strtotime($d->created_at)); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="5" class="text-center py-3 text-muted">No donations yet.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Recent Projects</h3>
                            <div class="card-actions">
                                <a href="<?php echo url('admin/projects'); ?>">View all</a>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm table-vcenter card-table">
                                <thead>
                                    <tr>
                                        <th>Project</th>
                                        <th>Start Date</th>
                                        <th>Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($recentProjects)): ?>
                                        <?php foreach ($recentProjects as $p): ?>
                                            <tr>
                                                <td class="fw-semibold"><?php echo htmlspecialchars($p->title); ?></td>
                                                <td class="text-muted small"><?php echo $p->start_date ? date('d M Y', strtotime($p->start_date)) : 'N/A'; ?></td>
                                                <td>
                                                    <span class="badge text-white <?php echo $p->status === 'ongoing' ? 'bg-primary' : ($p->status === 'completed' ? 'bg-success' : 'bg-warning'); ?>"><?php echo ucfirst($p->status); ?></span>
                                                </td>
                                                <td class="text-end">
                                                    <a href="<?php echo url('admin/projects'); ?>" class="btn btn-sm btn-light">Manage</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="4" class="text-center py-3 text-muted">No projects yet.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require_once 'app/views/admin/layouts/footer.php'; ?>
</div>