<?php require_once 'app/views/admin/layouts/header.php'; ?>
<?php require_once 'app/views/admin/layouts/header.php'; ?>
<?php require_once 'app/views/admin/layouts/sidebar.php'; ?>

<div class="page-wrapper">
    <?php require_once 'app/views/admin/layouts/topbar.php'; ?>

    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <ol class="breadcrumb" aria-label="breadcrumbs">
                        <li class="breadcrumb-item"><a href="<?php echo url('admin/dashboard'); ?>">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo url('admin/members/fees'); ?>">Membership
                                Fees</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo e($member->name); ?></li>
                    </ol>
                    <h2 class="page-title fw-bold fs-1"><?php echo e($member->name); ?></h2>
                    <div class="text-muted mt-1"><?php echo e($member->membership_id); ?> &middot;
                        <?php echo e($designationName); ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <div class="card mb-3">
                <div class="card-body py-2 border-bottom">
                    <div class="row g-2 align-items-center">
                        <div class="col-auto">
                            <select class="form-select form-select-sm" id="mfYear" onchange="applyMfFilters()"
                                style="width: 90px;" title="Year">
                                <?php foreach ($years as $y): ?>
                                    <option value="<?php echo $y; ?>" <?php echo $year == $y ? 'selected' : ''; ?>>
                                        <?php echo $y; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-auto">
                            <select class="form-select form-select-sm" id="mfMonth" onchange="applyMfFilters()"
                                style="width: 110px;" title="Month">
                                <option value="0" <?php echo $month == 0 ? 'selected' : ''; ?>>All Months</option>
                                <?php foreach ($monthNames as $num => $name): ?>
                                    <option value="<?php echo $num; ?>" <?php echo $month == $num ? 'selected' : ''; ?>>
                                        <?php echo $name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-auto ms-auto">
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                data-bs-target="#regPaymentModal"
                                onclick="document.getElementById('regPayMsg').innerHTML = '';">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M12 5l0 14" />
                                    <path d="M5 12l14 0" />
                                </svg>
                                Register Payment
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                function applyMfFilters() {
                    var y = document.getElementById('mfYear').value;
                    var m = document.getElementById('mfMonth').value;
                    window.location = '?year=' + y + (m > 0 ? '&month=' + m : '');
                }
            </script>

            <!-- Stats -->
            <div class="row row-deck row-cards mb-3">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="text-muted small text-uppercase fw-semibold">Monthly Fee</div>
                            <div class="h2 mt-1 mb-0" style="color: var(--primary);">
                                <?php echo $monthlyFee > 0 ? '₹' . number_format($monthlyFee, 2) : '<span class="text-muted fs-4">N/A</span>'; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="text-muted small text-uppercase fw-semibold">Paid Months</div>
                            <div class="h2 mt-1 mb-0 text-success">
                                <?php echo count($paidMonths); ?>/<?php echo count($months); ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="text-muted small text-uppercase fw-semibold">Total Paid</div>
                            <div class="h2 mt-1 mb-0 text-success">
                                <?php echo '₹'; ?><?php echo number_format($totalPaid, 2); ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="text-muted small text-uppercase fw-semibold">Balance Due</div>
                            <div class="h2 mt-1 mb-0 <?php echo $totalDue > 0 ? 'text-danger' : 'text-success'; ?>">
                                <?php echo $totalDue > 0 ? '₹' . number_format($totalDue, 2) : '₹' . '0.00'; ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monthly Breakdwn -->
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title"><i class="ti ti-calendar me-2"></i>Monthly Breakdown — <?php echo $year; ?>
                    </h3>
                </div>
                <div class="table-responsive">
                    <table class="table card-table table-vcenter text-nowrap">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Month</th>
                                <th class="text-center">Amount</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1;
                            foreach ($months as $m): ?>
                                <tr>
                                    <td class="text-muted"><?php echo $i++; ?></td>
                                    <td class="fw-semibold"><?php echo e($m['name']); ?></td>
                                    <td class="text-center fw-semibold">
                                        <?php echo '₹'; ?>    <?php echo number_format($m['amount'], 2); ?></td>
                                    <td class="text-center">
                                        <?php if ($m['is_paid']): ?>
                                            <span class="badge bg-success text-white"><i
                                                    class="ti ti-check me-1"></i>Paid</span>
                                        <?php elseif ($m['status'] === 'Upcoming'): ?>
                                            <span class="badge bg-info text-white"><i
                                                    class="ti ti-calendar me-1"></i>Upcoming</span>
                                        <?php elseif ($m['is_current']): ?>
                                            <span class="badge bg-info text-white"><i class="ti ti-bell me-1"></i>Due</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger text-white"><i
                                                    class="ti ti-alert-triangle me-1"></i>Due</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Payment History -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="ti ti-history me-2"></i>Payment History</h3>
                </div>
                <div class="table-responsive">
                    <table class="table card-table table-vcenter text-nowrap">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Month</th>
                                <th class="text-center">Amount</th>
                                <th class="text-center">Method</th>
                                <th class="text-center">Receipt</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($donations)): ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">No payments recorded.</td>
                                </tr>
                            <?php else:
                                $j = 1;
                                foreach ($donations as $d): ?>
                                    <tr>
                                        <td class="text-muted"><?php echo $j++; ?></td>
                                        <td><?php echo date('d M Y', strtotime($d->created_at)); ?></td>
                                        <td class="fw-semibold"><?php
                                        $pfDate = $d->payment_for_date ?? '';
                                        echo $pfDate && $pfDate !== '0000-00-00' ? date('F Y', strtotime($pfDate)) : date('F Y', strtotime($d->created_at));
                                        ?></td>
                                        <td class="text-center fw-semibold">
                                            <?php echo '₹'; ?>        <?php echo number_format($d->amount, 2); ?></td>
                                        <td class="text-center"><?php echo ucfirst($d->payment_method); ?></td>
                                        <td class="text-center">
                                            <?php if ($d->status === 'completed' && !empty($d->receipt_url)): ?>
                                                <a href="<?php echo $d->receipt_url; ?>" target="_blank"
                                                    class="btn btn-outline-primary btn-sm" title="Download Receipt">
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

    <?php require_once 'app/views/admin/layouts/footer.php'; ?>
</div>

<!-- Register Payment Modal -->
<div class="modal fade" id="regPaymentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="regPaymentForm" method="POST" action="<?php echo url('admin/finance/donations/store'); ?>">
                <input type="hidden" name="member_id" value="<?php echo ($member->uuid ?? $member->id); ?>">
                <input type="hidden" name="donor_name" value="<?php echo e($member->name); ?>">
                <input type="hidden" name="donor_email" value="<?php echo e($member->email); ?>">
                <input type="hidden" name="donor_phone" value="<?php echo e($member->phone); ?>">

                <div class="modal-header">
                    <h5 class="modal-title">Register Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="regPayMsg"></div>
                    <div class="mb-3">
                        <label class="form-label">Member</label>
                        <input type="text" class="form-control"
                            value="<?php echo e($member->name); ?> (<?php echo e($member->membership_id); ?>)" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Amount</label>
                        <input type="number" name="amount" class="form-control" step="0.01" min="1" required
                            value="<?php echo $monthlyFee > 0 ? number_format($monthlyFee, 2, '.', '') : ''; ?>"
                            readonly style="background: #f5f5f5; cursor: not-allowed;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Payment For</label>
                        <div
                            style="max-height: 160px; overflow-y: auto; border: 1px solid #e6e7e9; border-radius: 4px; padding: 8px 12px;">
                            <?php if (empty($pendingMonths)): ?>
                                <div class="text-muted small py-1">No pending months</div>
                            <?php else:
                                $prevYear = null;
                                foreach ($pendingMonths as $pm):
                                    $pmVal = $pm['month'] . '-' . $pm['year'];
                                    ?>
                                    <?php if ($pm['year'] != $prevYear):
                                        $prevYear = $pm['year']; ?>
                                        <div class="fw-semibold text-muted small mb-1 mt-1"><?php echo $pm['year']; ?></div>
                                    <?php endif; ?>
                                    <label class="form-check d-flex align-items-center gap-2 mb-1" style="cursor: pointer;">
                                        <input type="checkbox" name="pay_months[]" value="<?php echo $pmVal; ?>"
                                            class="form-check-input pay-month-cb" onchange="updateTotalAmount()">
                                        <span class="form-check-label"
                                            style="font-size: 14px;"><?php echo date('F', mktime(0, 0, 0, $pm['month'], 1)); ?></span>
                                    </label>
                                <?php endforeach; endif; ?>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-2 pt-1 border-top">
                            <span class="text-muted small fw-semibold">Total Amount:</span>
                            <span class="fw-bold" id="totalAmountDisplay"
                                style="font-size: 16px;"><?php echo '₹'; ?>0.00</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Payment Method</label>
                        <select name="payment_method" class="form-select">
                            <option value="cash">Cash</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="card">Card</option>
                            <option value="upi">UPI</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Transaction ID</label>
                        <input type="text" name="transaction_id" class="form-control" placeholder="Optional">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="completed">Completed</option>
                            <option value="pending">Pending</option>
                            <option value="failed">Failed</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="regPayBtn">Register Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    var monthlyFee = <?php echo $monthlyFee > 0 ? number_format($monthlyFee, 2, '.', '') : 0; ?>;

    function updateTotalAmount() {
        var checked = document.querySelectorAll('input[name="pay_months[]"]:checked').length;
        var total = checked * monthlyFee;
        document.querySelector('input[name="amount"]').value = total.toFixed(2);
        document.getElementById('totalAmountDisplay').textContent = '<?php echo '₹'; ?>' + total.toFixed(2);
    }


    document.addEventListener('DOMContentLoaded', function () {
        var regForm = document.getElementById('regPaymentForm');
        var regBtn = document.getElementById('regPayBtn');

        regForm.addEventListener('submit', function (e) {
            e.preventDefault();
            var checked = document.querySelectorAll('input[name="pay_months[]"]:checked');
            if (checked.length === 0) {
                document.getElementById('regPayMsg').innerHTML = '<div class="alert alert-warning py-2">Please select at least one month.</div>';
                return;
            }

            regBtn.disabled = true;
            regBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Processing...';
            document.getElementById('regPayMsg').innerHTML = '';

            var baseFd = new FormData(regForm);
            baseFd.delete('pay_months[]');

            var completed = 0;
            var total = checked.length;

            function submitNext(index) {
                if (index >= total) {
                    document.getElementById('regPayMsg').innerHTML = '<div class="alert alert-success py-2">All ' + total + ' payment(s) registered successfully.</div>';
                    setTimeout(function () { location.reload(); }, 1200);
                    return;
                }

                var monthVal = checked[index].value;
                var parts = monthVal.split('-');
                var m = parts[0].padStart(2, '0');
                var y = parts[1];
                var formattedDate = y + '-' + m + '-01';

                var fd = new FormData();
                for (var pair of baseFd.entries()) {
                    fd.append(pair[0], pair[1]);
                }
                fd.set('amount', monthlyFee.toFixed(2));
                fd.append('payment_for_date', formattedDate);

                fetch('<?php echo url('admin/finance/donations/store'); ?>', {
                    method: 'POST',
                    body: fd
                })
                    .then(function (r) { return r.json(); })
                    .then(function (res) {
                        if (res.status === 'success') {
                            completed++;
                            regBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> ' + completed + '/' + total;
                            submitNext(index + 1);
                        } else {
                            document.getElementById('regPayMsg').innerHTML = '<div class="alert alert-danger py-2">Error on month ' + (index + 1) + ': ' + (res.message || 'Failed.') + '</div>';
                            regBtn.disabled = false;
                            regBtn.innerHTML = 'Register Payment';
                        }
                    })
                    .catch(function () {
                        document.getElementById('regPayMsg').innerHTML = '<div class="alert alert-danger py-2">Network error on month ' + (index + 1) + '.</div>';
                        regBtn.disabled = false;
                        regBtn.innerHTML = 'Register Payment';
                    });
            }

            submitNext(0);
        });
    });
</script>