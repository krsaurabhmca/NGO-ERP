<?php require_once 'app/views/admin/layouts/header.php'; ?>
<?php require_once 'app/views/admin/layouts/header.php'; ?>
<?php require_once 'app/views/admin/layouts/sidebar.php'; ?>
<?php require_once 'app/views/admin/layouts/topbar.php'; ?>
<?php
// Determine active payment gateway
$activeGw = $globalSettings['active_online_gateway'] ?? '';
if ($activeGw === 'razorpay') {
    $rzpMode = $globalSettings['razorpay_mode'] ?? 'test';
    $rzpKeyId = $rzpMode === 'live'
        ? ($globalSettings['razorpay_live_key_id'] ?? $globalSettings['razorpay_key_id'] ?? '')
        : ($globalSettings['razorpay_test_key_id'] ?? $globalSettings['razorpay_key_id'] ?? '');
    $rzpKeySecret = $rzpMode === 'live'
        ? ($globalSettings['razorpay_live_key_secret'] ?? $globalSettings['razorpay_key_secret'] ?? '')
        : ($globalSettings['razorpay_test_key_secret'] ?? $globalSettings['razorpay_key_secret'] ?? '');
    if (empty($rzpKeyId) || empty($rzpKeySecret))
        $activeGw = '';
}
?>

<div class="page-wrapper">
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">Fees Ledger</h2>
                    <div class="text-muted mt-1">Monthly membership fee record</div>
                </div>
                <div class="col-12 col-sm-auto ms-auto d-flex gap-2 flex-wrap">
                    <div class="btn-list d-flex gap-2">
                        <select class="form-select form-select-sm" id="mMonth" onchange="applyMemFilters()"
                            style="width: auto;">
                            <option value="0" <?php echo $selectedMonth == 0 ? 'selected' : ''; ?>>All</option>
                            <?php foreach ($monthNames as $num => $name): ?>
                                <option value="<?php echo $num; ?>" <?php echo $selectedMonth == $num ? 'selected' : ''; ?>>
                                    <?php echo substr($name, 0, 3); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <select class="form-select form-select-sm" id="mYear" onchange="applyMemFilters()"
                            style="width: auto;">
                            <?php foreach ($years as $y): ?>
                                <option value="<?php echo $y; ?>" <?php echo $selectedYear == $y ? 'selected' : ''; ?>>
                                    <?php echo $y; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php if ($designationAmount > 0): ?>
                        <button type="button" class="btn btn-accent"
                            onclick="openPayModal(<?php echo $totalDue; ?>, 'All Due Months')">
                            <i class="ti ti-plus me-1"></i> Pay All
                            (<?php echo '₹'; ?><?php echo number_format($totalDue, 2); ?>)
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <?php if ($designationAmount > 0): ?>
                <!-- Designation Summary Card -->
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body text-center py-3">
                                <div class="text-muted small text-uppercase fw-semibold tracking-wide">Designation</div>
                                <div class="fs-3 fw-bold mt-1"><?php echo e($designationName); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body text-center py-3">
                                <div class="text-muted small text-uppercase fw-semibold tracking-wide">Monthly Fee</div>
                                <div class="fs-3 fw-bold mt-1" style="color: var(--primary);">
                                    <?php echo '₹'; ?>    <?php echo number_format($designationAmount, 2); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body text-center py-3">
                                <div class="text-muted small text-uppercase fw-semibold tracking-wide">Member Since</div>
                                <div class="fs-3 fw-bold mt-1"><?php echo date('M Y', strtotime($member->join_date)); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-warning border-0 rounded-3 mb-3">
                    <i class="ti ti-alert-circle me-2"></i> No designation fee amount set. Please contact the administrator.
                </div>
            <?php endif; ?>

            <?php if (empty($feesByYear)): ?>
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="ti ti-calendar-off" style="font-size: 3rem; color: #94a3b8;"></i>
                        <p class="text-muted mt-2">No fee records available.</p>
                    </div>
                </div>
            <?php else:
                foreach ($feesByYear as $yearData): ?>
                    <!-- Year Card -->
                    <div class="card mb-3">
                        <div class="card-header d-flex flex-wrap align-items-center gap-2">
                            <h3 class="card-title mb-0"><i class="ti ti-calendar me-2"></i><?php echo $yearData['year']; ?></h3>
                            <div class="ms-sm-auto d-flex align-items-center gap-2">
                                <?php
                                $progressPercent = $yearData['total_months'] > 0 ? round(($yearData['paid_count'] / $yearData['total_months']) * 100) : 0;
                                ?>
                                <div class="d-flex align-items-center gap-2">
                                    <span
                                        class="text-muted small text-nowrap"><?php echo $yearData['paid_count']; ?>/<?php echo $yearData['total_months']; ?>
                                        months</span>
                                    <div class="progress" style="width: 60px; height: 6px;">
                                        <div class="progress-bar bg-success" style="width: <?php echo $progressPercent; ?>%;">
                                        </div>
                                    </div>
                                </div>
                                <small class="text-muted d-none d-md-inline">
                                    Paid: <strong
                                        class="text-success"><?php echo '₹'; ?><?php echo number_format($yearData['paid_amount'], 2); ?></strong>
                                    /
                                    Total:
                                    <strong><?php echo '₹'; ?><?php echo number_format($yearData['total_amount'], 2); ?></strong>
                                </small>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table">
                                <thead>
                                    <tr>
                                        <th class="w-1">#</th>
                                        <th>Month</th>
                                        <th class="text-center">Amount</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center w-1">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1;
                                    foreach ($yearData['months'] as $month): ?>
                                        <tr>
                                            <td class="text-muted"><?php echo $i++; ?></td>
                                            <td class="fw-semibold"><?php echo e($month['month_name']); ?></td>
                                            <td class="text-center fw-bold">
                                                <?php echo '₹'; ?>            <?php echo number_format($month['amount'], 2); ?></td>
                                            <td class="text-center">
                                                <?php if ($month['is_paid']): ?>
                                                    <span class="badge bg-success text-white"><i
                                                            class="ti ti-check me-1"></i>Paid</span>
                                                <?php elseif ($month['status'] === 'Upcoming'): ?>
                                                    <span class="badge bg-info text-white"><i
                                                            class="ti ti-calendar me-1"></i>Upcoming</span>
                                                <?php elseif ($month['is_current']): ?>
                                                    <span class="badge bg-info text-white"><i class="ti ti-bell me-1"></i>Due</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger text-white"><i
                                                            class="ti ti-alert-triangle me-1"></i>Due</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <?php if (!$month['is_paid']): ?>
                                                    <button type="button" class="btn btn-accent py-1"
                                                        onclick="openPayModal(<?php echo $month['amount']; ?>, '<?php echo e($month['month_name']); ?> <?php echo $month['year']; ?>')">Pay</button>
                                                <?php elseif ($month['receipt_url']): ?>
                                                    <a href="<?php echo e($month['receipt_url']); ?>" target="_blank"
                                                        class="btn btn-sm btn-outline-primary"><i
                                                            class="ti ti-download me-1"></i>Receipt</a>
                                                <?php else: ?>
                                                    <span class="text-muted small">—</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endforeach; endif; ?>

            <div class="text-center mt-3">
                <a href="<?php echo url('member/donations'); ?>" class="btn btn-outline-secondary">
                    <i class="ti ti-history me-1"></i> View Full Payment History
                </a>
            </div>
        </div>
    </div>
</div>

<?php require_once 'app/views/admin/layouts/footer.php'; ?>

<!-- Pay Modal -->
<div class="modal fade" id="payModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <form id="payForm" method="POST" action="<?php echo url('member/donate/submit'); ?>">
                <?php echo csrf_field('member/donate'); ?>
                <input type="hidden" name="payment_method" value="<?php echo $activeGw ?: 'offline'; ?>">
                <input type="hidden" name="member_id" value="<?php echo $_SESSION['member_id']; ?>">
                <input type="hidden" name="donor_name" value="<?php echo e($member->name); ?>">
                <input type="hidden" name="donor_email" value="<?php echo e($member->email); ?>">
                <input type="hidden" name="donor_phone" value="<?php echo e($member->phone); ?>">
                <input type="hidden" name="is_recurring" value="0">
                <input type="hidden" name="amount" id="modalAmount" value="<?php echo $totalDue; ?>">

                <div class="modal-body text-center px-4 py-5">
                    <div id="modalMsg"></div>

                    <!-- Default Pay View -->
                    <div id="payView">
                        <div class="mb-4">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle"
                                style="width: 72px; height: 72px; background: #fef3e6;">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#e8943e"
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path
                                        d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                                </svg>
                            </div>
                        </div>
                        <h5 class="fw-bold mb-1" style="color: #1a1a2e;">Confirm Your Payment</h5>
                        <p class="text-muted small mb-3" id="modalMonthLabel" style="font-size: 13px;">January 2026</p>

                        <div class="bg-light rounded-3 py-3 px-3 mx-2 mb-3" style="background: #f8f9fc !important;">
                            <div class="text-muted small mb-1">Amount to Pay</div>
                            <div style="font-size: 28px; font-weight: 800; color: #1a1a2e;"><?php echo '₹'; ?><span
                                    id="modalAmountLabel"><?php echo number_format($totalDue, 2); ?></span></div>
                        </div>

                        <div class="d-flex align-items-center justify-content-center gap-2 mb-4">
                            <div
                                style="width: 32px; height: 32px; background: #eef2ff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 600; color: var(--primary);">
                                <?php echo strtoupper(substr($member->name, 0, 1)); ?></div>
                            <div class="text-start">
                                <div class="fw-semibold" style="font-size: 14px; color: #1a1a2e;">
                                    <?php echo e($member->name); ?></div>
                                <div style="font-size: 12px; color: #888;"><?php echo e($member->phone); ?></div>
                            </div>
                        </div>

                        <button type="submit" class="btn w-100 py-2 fw-bold" id="modalPayBtn"
                            style="background: #1a1a2e; color: #fff; border-radius: 12px; font-size: 15px;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2"
                                style="vertical-align: middle;">
                                <path
                                    d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                            </svg>
                            Pay Now
                        </button>
                        <button type="button" class="btn btn-link w-100 mt-1"
                            style="color: #999; text-decoration: none; font-size: 13px;"
                            data-bs-dismiss="modal">Cancel</button>
                    </div>

                    <!-- Success View (hidden by default) -->
                    <div id="successView" style="display: none;">
                        <div class="mb-3">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle"
                                style="width: 80px; height: 80px; background: #e8f8ed;">
                                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#22c55e"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12" />
                                </svg>
                            </div>
                        </div>
                        <h5 class="fw-bold mb-1" style="color: #1a1a2e; font-size: 20px;">Payment Successful!</h5>
                        <p class="text-muted mb-3" style="font-size: 13px;">Thank you for your generous support.</p>

                        <div class="bg-light rounded-3 py-3 px-3 mx-2 mb-3" style="background: #f8f9fc !important;">
                            <div class="text-muted small mb-1">Amount Paid</div>
                            <div style="font-size: 24px; font-weight: 800; color: #22c55e;"><?php echo '₹'; ?><span
                                    id="successAmount"><?php echo number_format($totalDue, 2); ?></span></div>
                        </div>

                        <div id="receiptLinkArea" style="display: none;">
                            <a id="receiptLink" href="#" target="_blank" class="btn w-100 py-2 fw-bold mb-2"
                                style="background: #1a1a2e; color: #fff; border-radius: 12px; font-size: 14px;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2"
                                    style="vertical-align: middle;">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                    <polyline points="7 10 12 15 17 10" />
                                    <line x1="12" y1="15" x2="12" y2="3" />
                                </svg>
                                Download Receipt
                            </a>
                        </div>
                        <button type="button" class="btn w-100 py-2 fw-bold" id="successCloseBtn"
                            style="background: #f0f0f0; color: #555; border-radius: 12px; font-size: 14px; border: none;"
                            data-bs-dismiss="modal">Close</button>
                    </div>

                    <!-- Failed View (hidden by default) -->
                    <div id="failedView" style="display: none;">
                        <div class="mb-3">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle"
                                style="width: 80px; height: 80px; background: #fef0f0;">
                                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#ef4444"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10" />
                                    <line x1="15" y1="9" x2="9" y2="15" />
                                    <line x1="9" y1="9" x2="15" y2="15" />
                                </svg>
                            </div>
                        </div>
                        <h5 class="fw-bold mb-1" style="color: #1a1a2e; font-size: 20px;">Payment Failed</h5>
                        <p class="text-muted mb-3" id="failedMsg" style="font-size: 13px;">Something went wrong. Please
                            try again.</p>

                        <button type="button" class="btn w-100 py-2 fw-bold mb-2" id="retryBtn"
                            style="background: #1a1a2e; color: #fff; border-radius: 12px; font-size: 14px;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2"
                                style="vertical-align: middle;">
                                <polyline points="1 4 1 10 7 10" />
                                <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10" />
                            </svg>
                            Try Again
                        </button>
                        <button type="button" class="btn w-100 py-2 fw-bold"
                            style="background: #f0f0f0; color: #555; border-radius: 12px; font-size: 14px; border: none;"
                            data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    function applyMemFilters() {
        var y = document.getElementById('mYear').value;
        var m = document.getElementById('mMonth').value;
        window.location = '?year=' + y + (m > 0 ? '&month=' + m : '');
    }

    function showPayView() {
        document.getElementById('payView').style.display = '';
        document.getElementById('successView').style.display = 'none';
        document.getElementById('failedView').style.display = 'none';
    }

    function showSuccessView(amount, receiptUrl) {
        document.getElementById('payView').style.display = 'none';
        document.getElementById('failedView').style.display = 'none';
        document.getElementById('successView').style.display = '';
        document.getElementById('successAmount').textContent = Number(amount).toFixed(2);

        var receiptArea = document.getElementById('receiptLinkArea');
        var receiptLink = document.getElementById('receiptLink');
        if (receiptUrl) {
            receiptLink.href = receiptUrl;
            receiptArea.style.display = '';
        } else {
            receiptArea.style.display = 'none';
        }
    }

    function showFailedView(message) {
        document.getElementById('payView').style.display = 'none';
        document.getElementById('successView').style.display = 'none';
        document.getElementById('failedView').style.display = '';
        document.getElementById('failedMsg').textContent = message || 'Something went wrong. Please try again.';
    }

    function openPayModal(amount, monthLabel) {
        showPayView();
        document.getElementById('modalAmount').value = amount;
        document.getElementById('modalAmountLabel').textContent = Number(amount).toFixed(2);
        document.getElementById('modalMonthLabel').textContent = monthLabel;
        document.getElementById('modalMsg').innerHTML = '';
        document.getElementById('modalPayBtn').disabled = false;
        document.getElementById('modalPayBtn').innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2" style="vertical-align: middle;"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg> Pay Now';
        new bootstrap.Modal(document.getElementById('payModal')).show();
    }

    // Retry button resets to pay view
    document.addEventListener('click', function (e) {
        if (e.target.id === 'retryBtn' || e.target.closest('#retryBtn')) {
            showPayView();
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        var payForm = document.getElementById('payForm');
        var payBtn = document.getElementById('modalPayBtn');
        var paySuccess = false;

        var payModalEl = document.getElementById('payModal');
        payModalEl.addEventListener('hidden.bs.modal', function () {
            if (paySuccess) location.reload();
        });

        <?php if ($activeGw === 'razorpay'): ?>
            payForm.addEventListener('submit', function (e) {
                e.preventDefault();
                payBtn.disabled = true;
                payBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Processing...';
                document.getElementById('modalMsg').innerHTML = '';

                var formData = new FormData(payForm);

                fetch('<?php echo url('donate/razorpay-order'); ?>', {
                    method: 'POST',
                    body: formData
                })
                    .then(function (r) { return r.json(); })
                    .then(function (res) {
                        if (res.status !== 'success') {
                            showFailedView(res.message || 'Failed to create order.');
                            payBtn.disabled = false;
                            payBtn.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2" style="vertical-align: middle;"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg> Pay Now';
                            return;
                        }

                        var donationUuid = res.donation_uuid || '';
                        var options = {
                            key: res.key_id,
                            amount: res.amount,
                            currency: 'INR',
                            name: '<?php echo e($globalSettings['ngo_name'] ?? 'NGO HELP'); ?>',
                            description: 'Membership Fee',
                            order_id: res.order_id,
                            prefill: {
                                name: '<?php echo e($member->name); ?>',
                                email: '<?php echo e($member->email); ?>',
                                contact: '<?php echo e($member->phone); ?>'
                            },
                            handler: function (paymentRes) {
                                var fd = new FormData(payForm);
                                fd.append('razorpay_order_id', paymentRes.razorpay_order_id);
                                fd.append('razorpay_payment_id', paymentRes.razorpay_payment_id);
                                fd.append('razorpay_signature', paymentRes.razorpay_signature);
                                payBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Verifying...';

                                fetch('<?php echo url('donate/razorpay-verify'); ?>', {
                                    method: 'POST',
                                    body: fd
                                })
                                    .then(function (r) { return r.json(); })
                                    .then(function (vres) {
                                        if (vres.status === 'success') {
                                            paySuccess = true;
                                            showSuccessView(formData.get('amount') || <?php echo $totalDue; ?>, vres.receipt_url || null);
                                        } else {
                                            showFailedView(vres.message || 'Verification failed. Please contact support.');
                                            payBtn.disabled = false;
                                            payBtn.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2" style="vertical-align: middle;"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg> Pay Now';
                                        }
                                    })
                                    .catch(function () {
                                        showFailedView('Verification failed. Please contact support.');
                                        payBtn.disabled = false;
                                        payBtn.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2" style="vertical-align: middle;"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg> Pay Now';
                                    });
                            },
                            modal: {
                                ondismiss: function () {
                                    payBtn.disabled = false;
                                    payBtn.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2" style="vertical-align: middle;"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg> Pay Now';
                                    var fd = new FormData();
                                    fd.append('donation_uuid', donationUuid);
                                    fetch('<?php echo url('donate/razorpay-fail'); ?>', {
                                        method: 'POST',
                                        headers: { 'X-Requested-With': 'XMLHttpRequest' },
                                        body: fd
                                    }).catch(function () { });
                                }
                            }
                        };
                        var rzp = new Razorpay(options);
                        rzp.open();
                    })
                    .catch(function () {
                        showFailedView('Payment service unavailable. Please try again.');
                        payBtn.disabled = false;
                        payBtn.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2" style="vertical-align: middle;"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg> Pay Now';
                    });
            });
        <?php endif; ?>
    });
</script>
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

    .tracking-wide {
        letter-spacing: 0.05em;
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

        .table-responsive {
            overflow-x: hidden !important;
        }

        .card-table {
            width: 100%;
        }

        .page-header .row {
            margin-left: 0;
            margin-right: 0;
        }

        .page-header .btn-list {
            flex-wrap: wrap;
            gap: 0.25rem !important;
        }

        .page-header .btn-list .form-select {
            font-size: 0.75rem;
            padding: 0.2rem 1.5rem 0.2rem 0.4rem;
        }

        .page-header .btn-accent {
            font-size: 0.75rem;
            padding: 0.25rem 0.6rem;
            white-space: nowrap;
        }

        .page-header .btn-accent .ti {
            display: none;
        }

        .card-table thead th:first-child,
        .card-table tbody td:first-child {
            display: none;
        }

        .card-table td,
        .card-table th {
            padding: 0.25rem 0.2rem !important;
            font-size: 0.7rem;
        }

        .card-table td:nth-child(3) {
            white-space: nowrap;
            font-size: 0.7rem;
        }

        .card-table .badge {
            font-size: 0.55rem;
            padding: 0.1em 0.3em;
        }

        .card-table .badge i {
            display: none;
        }

        .card-table td:last-child {
            width: 1px;
        }

        .card-table .btn-accent {
            font-size: 0.65rem;
            padding: 0.15rem 0.5rem;
            white-space: nowrap;
        }

        .card-table .btn-outline-primary {
            font-size: 0.6rem;
            padding: 0.1rem 0.3rem;
            white-space: nowrap;
        }

        .card-table .btn-outline-primary .me-1 {
            margin-right: 0 !important;
        }

        .card-header {
            padding: 0.4rem 0.5rem !important;
        }

        .card-header h3 {
            font-size: 0.85rem !important;
        }

        .card-header .text-nowrap {
            white-space: normal !important;
            font-size: 0.65rem;
        }

        .card-header .progress {
            flex-shrink: 0;
            width: 40px !important;
            height: 4px !important;
        }

        .row.g-3.mb-4 {
            margin-left: 0;
            margin-right: 0;
            gap: 0.25rem !important;
        }

        .row.g-3.mb-4>[class*="col-"] {
            padding-left: 0.25rem;
            padding-right: 0.25rem;
        }

        .row.g-3.mb-4 .card-body {
            padding: 0.4rem !important;
        }

        .row.g-3.mb-4 .fs-3 {
            font-size: 0.9rem !important;
        }

        .row.g-3.mb-4 .text-muted.small {
            font-size: 0.6rem !important;
        }
    }
</style>