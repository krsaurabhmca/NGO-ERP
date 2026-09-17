<?php require_once 'app/views/layouts/header.php'; ?>

<section class="position-relative overflow-hidden"
    style="background-color: var(--primary); background-image: radial-gradient(circle at 10% 20%, var(--accent) 0%, transparent 50%), radial-gradient(circle at 90% 80%, var(--accent) 0%, transparent 50%), radial-gradient(circle at 50% 50%, var(--primary-dark) 0%, transparent 70%);">
    <div class="container-fluid px-lg-5 py-5 text-center position-relative" style="z-index: 1;">
        <h1 class="display-5 fw-bold text-white mb-0">Make a Donation</h1>
        <p class="text-white-50 mb-0">Your contribution helps us make a difference.</p>
    </div>
    <div class="position-absolute top-0 end-0 opacity-10 hero-svg-circle">
        <svg width="400" height="400" viewBox="0 0 400 400" fill="none">
            <circle cx="300" cy="100" r="200" fill="var(--accent)" />
            <circle cx="100" cy="350" r="150" fill="var(--accent)" />
        </svg>
    </div>
    <div class="position-absolute bottom-0 start-0 opacity-10 hero-svg-circle">
        <svg width="300" height="300" viewBox="0 0 300 300" fill="none">
            <circle cx="50" cy="250" r="120" fill="var(--accent)" />
        </svg>
    </div>
</section>

<section class="py-5" style="background: #fffff0;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4"><i
                            class="fas fa-exclamation-circle me-2"></i>
                        <?php echo e($_SESSION['error']);
                        unset($_SESSION['error']); ?></div>
                <?php endif; ?>

                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="p-4 text-white text-center"
                        style="background-color: var(--primary); background-image: radial-gradient(circle at 10% 20%, var(--accent) 0%, transparent 50%), radial-gradient(circle at 90% 80%, var(--accent) 0%, transparent 50%), radial-gradient(circle at 50% 50%, var(--primary-dark) 0%, transparent 70%);">
                        <div class="icon-circle bg-white bg-opacity-10 mx-auto mb-3" style="width: 64px; height: 64px;">
                            <i class="fas fa-hand-holding-heart" style="color: var(--accent); font-size: 1.5rem;"></i>
                        </div>
                        <h4 class="fw-bold mb-1">Support Our Mission</h4>
                        <p class="text-white-50 mb-0 small">
                            <?php echo !empty($globalSettings['ngo_name']) ? $globalSettings['ngo_name'] : 'NGO HELP'; ?>
                        </p>
                    </div>
                    <div class="card-body p-4 p-lg-5">
                        <form id="donate-form" action="<?php echo url('donate'); ?>" method="POST">
                            <?php
                            $activeGw = $globalSettings['active_online_gateway'] ?? '';
                            $rzpMode = $globalSettings['razorpay_mode'] ?? 'test';
                            $rzpKeyId = $rzpMode === 'live'
                                ? ($globalSettings['razorpay_live_key_id'] ?? $globalSettings['razorpay_key_id'] ?? '')
                                : ($globalSettings['razorpay_test_key_id'] ?? $globalSettings['razorpay_key_id'] ?? '');
                            $rzpKeySecret = $rzpMode === 'live'
                                ? ($globalSettings['razorpay_live_key_secret'] ?? $globalSettings['razorpay_key_secret'] ?? '')
                                : ($globalSettings['razorpay_test_key_secret'] ?? $globalSettings['razorpay_key_secret'] ?? '');
                            if ($activeGw !== 'razorpay' || empty($rzpKeyId) || empty($rzpKeySecret)) {
                                $activeGw = '';
                            }
                            ?>
                            <input type="hidden" name="payment_method" value="<?php echo $activeGw ?: 'offline'; ?>">
                            <div class="text-center mb-5">
                                <label class="form-label fw-semibold mb-3">Donation Amount <span
                                        class="text-danger">*</span></label>
                                <div class="quick-amounts mb-3 d-flex flex-wrap justify-content-center gap-2">
                                    <button type="button" class="btn btn-outline-secondary rounded-pill quick-amt"
                                        data-value="100">₹100</button>
                                    <button type="button" class="btn btn-outline-secondary rounded-pill quick-amt"
                                        data-value="250">₹250</button>
                                    <button type="button" class="btn btn-outline-secondary rounded-pill quick-amt"
                                        data-value="500">₹500</button>
                                    <button type="button" class="btn btn-outline-secondary rounded-pill quick-amt"
                                        data-value="1000">₹1,000</button>
                                    <button type="button" class="btn btn-outline-secondary rounded-pill quick-amt"
                                        data-value="2000">₹2,000</button>
                                    <button type="button" class="btn btn-outline-secondary rounded-pill quick-amt"
                                        data-value="5000">₹5,000</button>
                                </div>
                                <div class="col-lg-7 mx-auto">
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text"><?php echo '₹'; ?></span>
                                        <input type="number" name="amount" class="form-control text-center" required
                                            min="1" step="0.01" placeholder="Enter amount">
                                    </div>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-lg-6">
                                    <label class="form-label small fw-semibold">Full Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="donor_name" class="form-control" required
                                        placeholder="Your name" minlength="3" pattern="[A-Za-z\s\.]{3,}"
                                        title="Enter your full name (minimum 3 characters)">
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label small fw-semibold">Email <span
                                            class="text-danger">*</span></label>
                                    <input type="email" name="donor_email" class="form-control" required
                                        placeholder="you@example.com">
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label small fw-semibold">Phone <span
                                            class="text-danger">*</span></label>
                                    <input type="tel" name="donor_phone" class="form-control" required
                                        placeholder="+91 98765 43210" pattern="[0-9]{10}" maxlength="10"
                                        title="Enter a valid 10-digit phone number">
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label small fw-semibold">Pincode <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="donor_pincode" class="form-control" required
                                        placeholder="e.g. 110001" maxlength="6" pattern="[0-9]{6}"
                                        title="Enter a valid 6-digit pincode">
                                </div>
                                <div class="col-12">
                                    <div class="form-check">
                                        <input type="checkbox" name="claim_80g" id="claim_80g" class="form-check-input"
                                            value="1">
                                        <label class="form-check-label fw-semibold" for="claim_80g"
                                            style="font-size:0.85rem;">Claim 80G Tax Deduction (PAN required for
                                            certificate)</label>
                                    </div>
                                    <div id="pan_wrap" style="display:none;" class="mt-2">
                                        <input type="text" name="donor_pan" id="donor_pan" class="form-control"
                                            placeholder="Enter PAN (e.g. ABCDE1234F)" maxlength="10"
                                            style="text-transform:uppercase;" pattern="[A-Z]{5}[0-9]{4}[A-Z]{1}"
                                            title="Enter a valid PAN (5 letters + 4 digits + 1 letter)">
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <label class="form-label small fw-semibold">Address <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="donor_address" class="form-control" required
                                        placeholder="House / Flat / Street">
                                </div>
                                <div class="col-lg-4">
                                    <label class="form-label small fw-semibold">City <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="donor_city" class="form-control" required
                                        placeholder="City">
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label small fw-semibold">State <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="donor_state" class="form-control" required
                                        placeholder="State">
                                </div>
                                <div class="col-12">
                                    <small class="text-muted"><i class="fas fa-info-circle me-1"></i> Your information
                                        is secure and used only for donation receipt purposes.</small>
                                </div>
                                <div class="col-12 text-center mt-3">
                                    <button type="submit"
                                        class="btn btn-accent btn-lg rounded-pill px-5 fw-bold shadow-sm"
                                        id="donate-btn">
                                        <i class="fas fa-heart me-2"></i> Donate Now
                                    </button>
                                </div>
                            </div>
                        </form>
                        <div id="donate-msg" class="mt-3 text-center"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm mx-2 mx-sm-auto">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-body text-center py-5 px-4">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-success bg-opacity-10 mb-3"
                    style="width: 64px; height: 64px;">
                    <i class="fas fa-check-circle text-success" style="font-size: 1.75rem;"></i>
                </div>
                <h5 class="fw-bold mb-1">Thank You!</h5>
                <p class="text-muted mb-4" style="font-size: 0.9rem;" id="successModalMsg">Your donation has been
                    completed successfully.</p>
                <div class="d-grid gap-2">
                    <a href="#" id="receiptLink" class="btn btn-accent rounded-pill" target="_blank">
                        <i class="fas fa-download me-2"></i> Download Receipt
                    </a>
                    <a href="#" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">Close</a>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    document.querySelectorAll('.quick-amt').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.quick-amt').forEach(function (b) { b.classList.remove('active'); });
            this.classList.add('active');
            var input = document.querySelector('[name="amount"]');
            input.value = this.getAttribute('data-value');
            input.focus();
        });
    });
    document.querySelector('[name="amount"]').addEventListener('input', function () {
        document.querySelectorAll('.quick-amt').forEach(function (b) { b.classList.remove('active'); });
    });

    document.getElementById('claim_80g').addEventListener('change', function () {
        var wrap = document.getElementById('pan_wrap');
        var panInput = document.getElementById('donor_pan');
        if (this.checked) {
            wrap.style.display = 'block';
            panInput.required = true;
            panInput.disabled = false;
        } else {
            wrap.style.display = 'none';
            panInput.required = false;
            panInput.disabled = true;
            panInput.value = '';
        }
    });
    panInput = document.getElementById('donor_pan');
    panInput.addEventListener('input', function () {
        this.value = this.value.toUpperCase();
    });

    document.getElementById('donate-form').addEventListener('submit', function (e) {
        var panWrap = document.getElementById('pan_wrap');
        var panInput = document.getElementById('donor_pan');
        if (panWrap.style.display !== 'none' && panInput.value.trim() === '') {
            e.preventDefault();
            panInput.focus();
            panInput.style.borderColor = '#dc3545';
            return;
        }
        var firstInvalid = this.querySelector(':invalid');
        if (firstInvalid) {
            e.preventDefault();
            firstInvalid.focus();
            firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });

    function showSuccessModal(msg, receiptUrl) {
        document.getElementById('successModalMsg').textContent = msg;
        var link = document.getElementById('receiptLink');
        if (receiptUrl) {
            link.href = receiptUrl;
            link.style.display = 'inline-flex';
        } else {
            link.style.display = 'none';
        }
        var modal = new bootstrap.Modal(document.getElementById('successModal'));
        modal.show();
    }

    <?php if (isset($_GET['success']) && $_GET['success'] == 1):
        if (($_GET['status'] ?? '') === 'completed'):
            if (isset($_GET['receipt_url'])) {
                $receiptUrl = json_encode($_GET['receipt_url']); ?>
                document.addEventListener('DOMContentLoaded', function () {
                    var link = document.getElementById('receiptLink');
                    link.href = <?php echo $receiptUrl; ?>;
                    link.style.display = 'inline-flex';
                    document.getElementById('successModalMsg').textContent = 'Your donation has been completed successfully! Thank you for your generous support!';
                    var modal = new bootstrap.Modal(document.getElementById('successModal'));
                    modal.show();
                });
            <?php } elseif (isset($_GET['donation_uuid'])) {
                $donationUuid = json_encode($_GET['donation_uuid']); ?>
                document.addEventListener('DOMContentLoaded', function () {
                    showSuccessModal('Your donation has been completed successfully! Thank you for your generous support!', <?php echo $donationUuid; ?>);
                });
            <?php } else { ?>
                document.addEventListener('DOMContentLoaded', function () {
                    showSuccessModal('Your donation has been submitted successfully! We will contact you shortly.', null);
                });
            <?php }endif; endif; ?>
</script>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    document.getElementById('donate-form').addEventListener('submit', function (e) {
        var method = this.querySelector('[name="payment_method"]').value;
        if (method === 'offline') {
            return;
        }

        e.preventDefault();
        var msgBox = document.getElementById('donate-msg');
        var btn = document.getElementById('donate-btn');
        var form = this;

        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        var formData = new FormData(form);
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Processing...';
        msgBox.innerHTML = '';

        fetch('<?php echo url('donate/razorpay-order'); ?>', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: formData
        })
            .then(function (r) { return r.json(); })
            .then(function (res) {
                if (res.status !== 'success') {
                    msgBox.innerHTML = '<div class="alert alert-danger py-2 mb-0">' + (res.message || 'Failed to create order.') + '</div>';
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-heart me-2"></i> Donate Now';
                    return;
                }

                var donationUuid = res.donation_uuid || '';
                var options = {
                    key: res.key_id,
                    amount: res.amount,
                    currency: 'INR',
                    name: '<?php echo !empty($globalSettings['ngo_name']) ? $globalSettings['ngo_name'] : 'NGO HELP'; ?>',
                    description: 'Donation',
                    order_id: res.order_id,
                    prefill: {
                        name: form.querySelector('[name="donor_name"]').value,
                        email: form.querySelector('[name="donor_email"]').value || '',
                        contact: form.querySelector('[name="donor_phone"]').value || ''
                    },
                    handler: function (paymentRes) {
                        var fd = new FormData(form);
                        fd.append('razorpay_order_id', paymentRes.razorpay_order_id);
                        fd.append('razorpay_payment_id', paymentRes.razorpay_payment_id);
                        fd.append('razorpay_signature', paymentRes.razorpay_signature);

                        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Verifying...';

                        fetch('<?php echo url('donate/razorpay-verify'); ?>', {
                            method: 'POST',
                            headers: { 'X-Requested-With': 'XMLHttpRequest' },
                            body: fd
                        })
                            .then(function (r) { return r.json(); })
                            .then(function (vres) {
                                if (vres.status === 'success') {
                                    form.reset();
                                    showSuccessModal(vres.message, vres.receipt_url);
                                } else {
                                    msgBox.innerHTML = '<div class="alert alert-danger py-2 mb-0">' + (vres.message || 'Verification failed.') + '</div>';
                                }
                                btn.disabled = false;
                                btn.innerHTML = '<i class="fas fa-heart me-2"></i> Donate Now';
                            })
                            .catch(function () {
                                msgBox.innerHTML = '<div class="alert alert-danger py-2 mb-0">Verification failed. Please contact support.</div>';
                                btn.disabled = false;
                                btn.innerHTML = '<i class="fas fa-heart me-2"></i> Donate Now';
                            });
                    },
                    modal: {
                        ondismiss: function () {
                            btn.disabled = false;
                            btn.innerHTML = '<i class="fas fa-heart me-2"></i> Donate Now';
                            if (msgBox) msgBox.innerHTML = '<div class="alert alert-warning py-2 mb-0">Payment cancelled.</div>';
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
                msgBox.innerHTML = '<div class="alert alert-danger py-2 mb-0">Something went wrong. Please try again.</div>';
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-heart me-2"></i> Donate Now';
            });
    });
</script>

<?php require_once 'app/views/layouts/footer.php'; ?>