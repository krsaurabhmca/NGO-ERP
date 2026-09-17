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
$success = $_GET['success'] ?? 0;
$donationUuid = $_GET['donation_uuid'] ?? '';
$paymentStatus = $_GET['status'] ?? '';
?>

<div class="page-wrapper">
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">Pay Membership Fee</h2>
                    <div class="text-muted mt-1">Pay your monthly membership contribution</div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <?php if ($success): ?>
                <div class="card mb-3">
                    <div class="card-body text-center py-5">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-success bg-opacity-10 mb-3"
                            style="width: 72px; height: 72px;">
                            <i class="ti ti-check text-success" style="font-size: 2rem;"></i>
                        </div>
                        <h3 class="fw-bold">Thank You!</h3>
                        <p class="text-muted mb-1">Your membership fee has been submitted successfully.</p>
                        <?php if ($paymentStatus === 'pending'): ?>
                            <p class="text-muted small mb-3">It is currently pending and will be processed soon.</p>
                        <?php endif; ?>
                        <?php if (isset($receiptUrl) && $receiptUrl): ?>
                            <a href="<?php echo e($receiptUrl); ?>" target="_blank" class="btn btn-primary">
                                <i class="ti ti-download me-1"></i> Download Receipt
                            </a>
                        <?php endif; ?>
                        <a href="<?php echo url('member/donate'); ?>" class="btn btn-outline-secondary ms-2">Pay Again</a>
                    </div>
                </div>
            <?php else: ?>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="ti ti-heart me-2"></i>Fee Details</h3>
                    </div>
                    <div class="card-body">
                        <form id="donateForm" method="POST" action="<?php echo url('member/donate/submit'); ?>">
                            <?php echo csrf_field('member/donate'); ?>
                            <input type="hidden" name="payment_method" value="<?php echo $activeGw ?: 'offline'; ?>">
                            <input type="hidden" name="member_id" value="<?php echo $_SESSION['member_id']; ?>">

                            <!-- Designation Fee Info -->
                            <?php if ($designationAmount > 0): ?>
                                <div class="row g-3 align-items-center mb-4">
                                    <div class="col-md-3 text-center text-md-start">
                                        <span class="avatar avatar-xl"
                                            style="background-color: var(--primary); background-image: radial-gradient(circle at 10% 20%, var(--accent) 0%, transparent 50%), radial-gradient(circle at 90% 80%, var(--accent) 0%, transparent 50%), radial-gradient(circle at 50% 50%, var(--primary-dark) 0%, transparent 70%);">
                                            <i class="ti ti-tag text-white" style="font-size: 1.75rem;"></i>
                                        </span>
                                    </div>
                                    <div class="col-md-5">
                                        <h4 class="mb-1"><?php echo e($designationName); ?></h4>
                                        <div class="text-muted small">Your monthly designation fee</div>
                                    </div>
                                    <div class="col-md-4 text-center text-md-end">
                                        <div class="fs-1 fw-bold" style="color: var(--primary);">
                                            <?php echo '₹'; ?>        <?php echo number_format($designationAmount, 2); ?></div>
                                        <div class="text-muted small">per month</div>
                                    </div>
                                </div>
                                <hr class="my-4">
                            <?php endif; ?>

                            <!-- Amount -->
                            <div class="row justify-content-center mb-4">
                                <div class="col-lg-6">
                                    <label class="form-label fw-semibold text-center">Fee Amount <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group input-group-lg mt-1">
                                        <span class="input-group-text fs-3"><?php echo '₹'; ?></span>
                                        <input type="number" name="amount" class="form-control text-center fw-bold fs-3"
                                            required min="1" step="0.01"
                                            value="<?php echo $designationAmount > 0 ? e(number_format($designationAmount, 2, '.', '')) : ''; ?>"
                                            placeholder="Enter amount">
                                    </div>
                                </div>
                            </div>

                            <!-- Member Summary -->
                            <div class="bg-light rounded-3 p-3 mb-4">
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <small class="text-muted d-block">Name</small>
                                        <span class="fw-semibold"><?php echo e($member->name); ?></span>
                                        <input type="hidden" name="donor_name" value="<?php echo e($member->name); ?>">
                                    </div>
                                    <div class="col-md-4">
                                        <small class="text-muted d-block">Email</small>
                                        <span class="fw-semibold"><?php echo e($member->email); ?></span>
                                        <input type="hidden" name="donor_email" value="<?php echo e($member->email); ?>">
                                    </div>
                                    <div class="col-md-4">
                                        <small class="text-muted d-block">Phone</small>
                                        <span class="fw-semibold"><?php echo e($member->phone); ?></span>
                                        <input type="hidden" name="donor_phone" value="<?php echo e($member->phone); ?>">
                                    </div>
                                </div>
                            </div>

                            <!-- Address -->
                            <div class="row g-3 mb-3">
                                <div class="col-12">
                                    <label class="form-label small fw-semibold">Address <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="donor_address" class="form-control" required
                                        placeholder="House / Flat / Street">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-semibold">City <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="donor_city" class="form-control" required placeholder="City">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-semibold">State <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="donor_state" class="form-control" required placeholder="State">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-semibold">Pincode <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="donor_pincode" class="form-control" required
                                        placeholder="e.g. 110001" maxlength="6" pattern="[0-9]{6}">
                                </div>
                            </div>

                            <!-- 80G -->
                            <div class="mb-4">
                                <div class="form-check">
                                    <input type="checkbox" name="claim_80g" id="claim_80g" class="form-check-input"
                                        value="1">
                                    <label class="form-check-label fw-semibold" for="claim_80g"
                                        style="font-size:0.85rem;">Claim 80G Tax Deduction</label>
                                </div>
                                <div id="pan_wrap" style="display:none;" class="mt-2">
                                    <input type="text" name="donor_pan" id="donor_pan" class="form-control"
                                        placeholder="PAN (e.g. ABCDE1234F)" maxlength="10" style="text-transform:uppercase;"
                                        pattern="[A-Z]{5}[0-9]{4}[A-Z]{1}">
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Monthly -->
                            <div class="mb-4 text-center">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="is_recurring" id="pay_once" value="0"
                                        <?php echo $designationAmount > 0 ? '' : 'checked'; ?>>
                                    <label class="form-check-label fw-semibold" for="pay_once">Pay Once</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="is_recurring" id="pay_monthly"
                                        value="1" <?php echo $designationAmount > 0 ? 'checked' : ''; ?>>
                                    <label class="form-check-label fw-semibold" for="pay_monthly"><i
                                            class="ti ti-refresh me-1"></i>Pay Monthly</label>
                                </div>
                                <div class="text-muted small mt-1">Monthly: you will be contacted each month for this
                                    amount.</div>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-accent btn-lg px-sm-5 px-3 w-100 w-sm-auto">
                                    <i class="ti ti-heart me-2"></i> Pay Now
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php require_once 'app/views/admin/layouts/footer.php'; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('claim_80g').addEventListener('change', function () {
            var pi = document.getElementById('donor_pan');
            document.getElementById('pan_wrap').style.display = this.checked ? 'block' : 'none';
            pi.required = this.checked;
            pi.disabled = !this.checked;
            if (!this.checked) pi.value = '';
        });
        document.getElementById('donor_pan').addEventListener('input', function () {
            this.value = this.value.toUpperCase();
        });
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
</style>