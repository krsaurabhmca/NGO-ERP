<?php require_once 'app/views/admin/layouts/header.php'; ?>
<?php require_once 'app/views/admin/layouts/sidebar.php'; ?>
<?php require_once 'app/views/admin/layouts/topbar.php'; ?>

<div class="page-wrapper">
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">My Fee Payments</h2>
                </div>
                <div class="col-auto ms-auto">
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <!-- Stats -->
            <div class="row row-deck row-cards mb-3">
                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <div>
                                <div class="fs-2 fw-bold"><?php echo e($stats ? ($stats->total_donations ?? 0) : 0); ?>
                                </div>
                                <div class="text-muted">Total Payments</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <div>
                                <div class="fs-2 fw-bold">
                                    <?php echo '₹'; ?><?php echo number_format($stats ? ($stats->total_amount ?? 0) : 0, 2); ?>
                                </div>
                                <div class="text-muted">Total Amount</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <div>
                                <div class="fs-2 fw-bold"><?php echo e($stats ? ($stats->recurring_count ?? 0) : 0); ?>
                                </div>
                                <div class="text-muted">Recurring</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Payment History</h3>
                </div>
                <div class="table-responsive">
                    <table class="table card-table table-vcenter text-nowrap">
                        <thead>
                            <tr>
                                <th class="w-1">#</th>
                                <th>Date</th>
                                <th>Month</th>
                                <th>Amount</th>
                                <th>Payment</th>
                                <th>Status</th>
                                <th class="w-1">Receipt</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($donations)): ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-5">
                                        <i class="ti ti-heart-off"
                                            style="font-size: 2rem; display: block; margin-bottom: 0.5rem;"></i>
                                        No donations yet.
                                    </td>
                                </tr>
                            <?php else:
                                foreach ($donations as $i => $d): ?>
                                    <tr>
                                        <td><?php echo $i + 1; ?></td>
                                        <td><?php echo date('d M Y', strtotime($d->created_at)); ?></td>
                                        <td><?php
                                        $pfDate = $d->payment_for_date ?? '';
                                        echo $pfDate && $pfDate !== '0000-00-00' ? date('F Y', strtotime($pfDate)) : date('F Y', strtotime($d->created_at));
                                        ?></td>
                                        <td class="fw-bold"><?php echo '₹'; ?><?php echo number_format($d->amount, 2); ?></td>
                                        <td><?php echo ucfirst($d->payment_method); ?></td>
                                        <td>
                                            <span
                                                class="badge bg-<?php echo $d->status === 'completed' ? 'success' : ($d->status === 'pending' ? 'warning' : 'danger'); ?> text-white">
                                                <?php echo ucfirst($d->status); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($d->status === 'completed'): ?>
                                                <a href="<?php echo isset($d->receipt_url) ? e($d->receipt_url) : url('donate/receipt/' . $d->uuid); ?>"
                                                    target="_blank" class="btn btn-outline-primary btn-sm" title="Download Receipt">
                                                    <i class="ti ti-download me-1"></i>Receipt
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted small">—</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'app/views/admin/layouts/footer.php'; ?>
<style>
    .btn-accent {
        background: #FFBF00;
        border: none;
        color: #003566;
        font-weight: 700;
        border-radius: 50rem;
    }

    .btn-accent:hover {
        background: #e6ac00;
        color: #003566;
    }

    /* Mobile: compact + no side scroll */
    @media (max-width: 575.98px) {

        html,
        body,
        .page-wrapper,
        .page-body,
        .container-xl {
            overflow-x: hidden !important;
            max-width: 100vw !important;
        }

        .row.row-deck.row-cards {
            margin-left: 0;
            margin-right: 0;
        }

        .row.row-deck.row-cards>[class*="col-"] {
            padding-left: 0.25rem;
            padding-right: 0.25rem;
        }

        .row.row-deck.row-cards .card-body {
            padding: 0.5rem !important;
        }

        .row.row-deck.row-cards .fs-2 {
            font-size: 1rem !important;
        }

        .row.row-deck.row-cards .text-muted {
            font-size: 0.65rem !important;
        }

        .table-responsive {
            overflow-x: hidden !important;
        }

        .card-table {
            width: 100%;
        }

        .card-table.text-nowrap {
            white-space: normal !important;
        }

        .card-table td,
        .card-table th {
            padding: 0.25rem 0.15rem !important;
            font-size: 0.7rem;
        }

        .card-table thead th:nth-child(1),
        .card-table tbody td:nth-child(1),
        .card-table thead th:nth-child(5),
        .card-table tbody td:nth-child(5) {
            display: none;
        }

        .card-table td:nth-child(2) {
            white-space: nowrap;
            font-size: 0.65rem;
        }

        .card-table td:nth-child(3) {
            font-size: 0.7rem;
        }

        .card-table td:nth-child(4) {
            white-space: nowrap;
            font-size: 0.7rem;
        }

        .card-table .badge {
            font-size: 0.55rem;
            padding: 0.1em 0.3em;
        }

        .card-table td:last-child {
            width: 1px;
        }

        .card-table .btn-outline-primary {
            font-size: 0.6rem;
            padding: 0.1rem 0.4rem;
            white-space: nowrap;
        }

        .card-table .btn-outline-primary .ti {
            font-size: 0.65rem;
            margin-right: 0.15rem !important;
        }
    }
</style>