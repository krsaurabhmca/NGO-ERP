<?php require_once 'app/views/layouts/header.php'; ?>

<?php
$progress = $campaign->goal_amount > 0 ? min(100, round(($campaign->raised_amount / $campaign->goal_amount) * 100)) : 0;
$daysLeft = '';
$urgent = false;
if ($campaign->end_date) {
    $end = new DateTime($campaign->end_date);
    $now = new DateTime();
    $diff = $now->diff($end);
    if ($end > $now) {
        $daysLeft = $diff->days . ' day' . ($diff->days > 1 ? 's' : '') . ' left';
        $urgent = $diff->days <= 7;
    } else {
        $daysLeft = 'Ended';
    }
}
?>

<section class="position-relative overflow-hidden"
    style="background-color: var(--primary); background-image: radial-gradient(circle at 10% 20%, var(--accent) 0%, transparent 50%), radial-gradient(circle at 90% 80%, var(--accent) 0%, transparent 50%), radial-gradient(circle at 50% 50%, var(--primary-dark) 0%, transparent 70%);">
    <div class="container-fluid px-lg-5 py-5 text-center position-relative" style="z-index: 1;">
        <h1 class="display-5 fw-bold text-white mb-0"><?php echo htmlspecialchars($campaign->title); ?></h1>
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
        <?php if (!empty($_GET['donation_success'])): ?>
            <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4 text-center py-4">
                <h5 class="fw-bold mb-1"><i class="fas fa-check-circle me-2"></i>Thank You!</h5>
                <p class="mb-0 text-muted">Your donation has been submitted successfully. It is pending and will be
                    processed soon.</p>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4">
                <?php echo e($_SESSION['error']);
                unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                    <?php if ($campaign->image): ?>
                        <img src="<?php echo file_url($campaign->image); ?>"
                            alt="<?php echo htmlspecialchars($campaign->title); ?>" class="card-img-top"
                            style="max-height: 400px; object-fit: cover;">
                    <?php endif; ?>
                    <div class="card-body p-4 p-lg-5">
                        <div class="d-flex flex-wrap align-items-center gap-3 mb-4 pb-3 border-bottom text-muted"
                            style="font-size: 0.85rem;">
                            <span><i class="far fa-calendar-alt me-1" style="color: var(--accent);"></i>
                                <?php echo $campaign->start_date ? date('d M Y', strtotime($campaign->start_date)) : 'N/A'; ?>
                                -
                                <?php echo $campaign->end_date ? date('d M Y', strtotime($campaign->end_date)) : 'Ongoing'; ?></span>
                            <?php if ($daysLeft): ?>
                                <span class="<?php echo $urgent ? 'text-danger fw-bold' : ''; ?>"><i
                                        class="far fa-clock me-1"></i> <?php echo $daysLeft; ?></span>
                            <?php endif; ?>
                            <span class="badge px-3 py-2 rounded-pill fw-semibold"
                                style="background: var(--primary)15; color: var(--primary); font-size: 0.72rem;">Active</span>
                        </div>
                        <h2 class="fw-bold h4 mb-3" style="color: var(--primary);">About This Campaign</h2>
                        <div class="lh-lg text-muted" style="text-align: justify; white-space: pre-wrap;">
                            <?php echo htmlspecialchars($campaign->description ?? 'No description available.'); ?>
                        </div>
                    </div>
                </div>
                <a href="<?php echo url('/campaigns'); ?>" class="btn btn-outline-secondary rounded-pill px-4">
                    <i class="fas fa-arrow-left me-2"></i> Back to All Campaigns
                </a>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden" style="position: sticky; top: 100px;">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Fundraising Progress</h5>
                        <div class="mb-3">
                            <div class="progress" style="height: 10px; border-radius: 1rem;">
                                <div class="progress-bar"
                                    style="width: <?php echo $progress; ?>%; background-color: var(--primary); border-radius: 1rem;">
                                </div>
                            </div>
                            <div class="d-flex justify-content-between small mt-2">
                                <span class="fw-bold fs-3"
                                    style="color: var(--primary);"><?php echo '₹'; ?><?php echo number_format($campaign->raised_amount); ?></span>
                                <span class="text-muted small mt-2">of
                                    <?php echo '₹'; ?><?php echo number_format($campaign->goal_amount); ?></span>
                            </div>
                        </div>
                        <div class="row g-2 mb-4 p-3 bg-light rounded-3 text-center">
                            <div class="col-4">
                                <div class="fw-bold fs-5" style="color: var(--primary);"><?php echo $progress; ?>%</div>
                                <div class="text-muted" style="font-size: 0.7rem;">Funded</div>
                            </div>
                            <div class="col-4">
                                <div class="fw-bold fs-5" style="color: var(--accent);">
                                    <?php echo $campaign->donor_count ?? 0; ?></div>
                                <div class="text-muted" style="font-size: 0.7rem;">Donors</div>
                            </div>
                            <div class="col-4">
                                <div class="fw-bold fs-5" style="color: var(--primary);">
                                    <?php echo $daysLeft ? explode(' ', $daysLeft)[0] : '∞'; ?></div>
                                <div class="text-muted" style="font-size: 0.7rem;">Days Left</div>
                            </div>
                        </div>

                        <div id="donate">
                            <h6 class="fw-bold mb-3">Quick Donate</h6>
                            <div id="campaign-msg"></div>
                            <form id="campaign-donate-form" action="<?php echo url('donate'); ?>" method="POST">
                                <input type="hidden" name="campaign_id" value="<?php echo $campaign->id; ?>">
                                <input type="hidden" name="campaign_slug" value="<?php echo $campaign->slug; ?>">
                                <?php
                                $cmpActiveGw = $globalSettings['active_online_gateway'] ?? '';
                                $cmpRzpMode = $globalSettings['razorpay_mode'] ?? 'test';
                                $cmpRzpKeyId = $cmpRzpMode === 'live'
                                    ? ($globalSettings['razorpay_live_key_id'] ?? $globalSettings['razorpay_key_id'] ?? '')
                                    : ($globalSettings['razorpay_test_key_id'] ?? $globalSettings['razorpay_key_id'] ?? '');
                                $cmpRzpKeySecret = $cmpRzpMode === 'live'
                                    ? ($globalSettings['razorpay_live_key_secret'] ?? $globalSettings['razorpay_key_secret'] ?? '')
                                    : ($globalSettings['razorpay_test_key_secret'] ?? $globalSettings['razorpay_key_secret'] ?? '');
                                if ($cmpActiveGw !== 'razorpay' || empty($cmpRzpKeyId) || empty($cmpRzpKeySecret))
                                    $cmpActiveGw = '';
                                ?>
                                <input type="hidden" name="payment_method"
                                    value="<?php echo $cmpActiveGw ?: 'offline'; ?>">
                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <button type="button"
                                            class="btn btn-outline-secondary rounded-pill w-100 preset-amount"
                                            data-amount="500" style="font-size: 0.85rem;"><?php echo '₹'; ?>500</button>
                                    </div>
                                    <div class="col-6">
                                        <button type="button"
                                            class="btn btn-outline-secondary rounded-pill w-100 preset-amount"
                                            data-amount="1000"
                                            style="font-size: 0.85rem;"><?php echo '₹'; ?>1,000</button>
                                    </div>
                                    <div class="col-6">
                                        <button type="button"
                                            class="btn btn-outline-secondary rounded-pill w-100 preset-amount"
                                            data-amount="2000"
                                            style="font-size: 0.85rem;"><?php echo '₹'; ?>2,000</button>
                                    </div>
                                    <div class="col-6">
                                        <button type="button"
                                            class="btn btn-outline-secondary rounded-pill w-100 preset-amount"
                                            data-amount="5000"
                                            style="font-size: 0.85rem;"><?php echo '₹'; ?>5,000</button>
                                    </div>
                                </div>
                                <div class="input-group mb-2">
                                    <span class="input-group-text"><?php echo '₹'; ?></span>
                                    <input type="number" name="amount" id="donation_amount" class="form-control"
                                        required min="1" step="1" placeholder="Custom amount">
                                </div>
                                <div class="mb-2">
                                    <input type="text" name="donor_name" class="form-control" required
                                        placeholder="Your full name *">
                                </div>
                                <div class="mb-2">
                                    <input type="email" name="donor_email" class="form-control" required
                                        placeholder="Email (for receipt) *">
                                </div>
                                <div class="mb-2">
                                    <input type="tel" name="donor_phone" class="form-control" required
                                        placeholder="Phone *">
                                </div>
                                <div class="mb-2">
                                    <input type="text" name="donor_address" class="form-control" required
                                        placeholder="Address *">
                                </div>
                                <div class="row g-2 mb-2">
                                    <div class="col-6">
                                        <input type="text" name="donor_city" class="form-control" required
                                            placeholder="City *">
                                    </div>
                                    <div class="col-6">
                                        <input type="text" name="donor_state" class="form-control" required
                                            placeholder="State *">
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <input type="text" name="donor_pincode" class="form-control" required
                                        placeholder="Pincode *" maxlength="6">
                                </div>
                                <div class="form-check mb-2">
                                    <input type="checkbox" name="claim_80g" id="campaign_claim_80g"
                                        class="form-check-input" value="1">
                                    <label class="form-check-label" for="campaign_claim_80g"
                                        style="font-size:0.8rem;">Claim 80G Tax Deduction</label>
                                </div>
                                <div class="mb-3" id="campaign_pan_wrap" style="display:none;">
                                    <input type="text" name="donor_pan" id="campaign_donor_pan" class="form-control"
                                        placeholder="PAN (for 80G certificate)" maxlength="10"
                                        style="text-transform:uppercase;">
                                </div>
                                <button type="submit" id="campaign-donate-btn"
                                    class="btn btn-accent rounded-pill w-100 fw-bold">
                                    <i class="fas fa-heart me-2"></i> Donate <?php echo '₹'; ?><span
                                        id="donate_btn_amount"><?php echo number_format($campaign->goal_amount > 0 ? min(500, $campaign->goal_amount - $campaign->raised_amount) : 500); ?></span>
                                </button>
                            </form>
                        </div>

                        <div class="mt-4 pt-3 border-top text-center">
                            <small class="text-muted">Share this campaign</small>
                            <div class="d-flex justify-content-center gap-2 mt-2">
                                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(url('campaigns/' . $campaign->slug)); ?>"
                                    target="_blank" class="btn btn-sm btn-outline-primary rounded-circle"
                                    style="width: 34px; height: 34px;"><i class="fab fa-facebook-f"></i></a>
                                <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode('Support ' . $campaign->title . ' - ' . url('campaigns/' . $campaign->slug)); ?>"
                                    target="_blank" class="btn btn-sm btn-outline-info rounded-circle"
                                    style="width: 34px; height: 34px;"><i class="fab fa-twitter"></i></a>
                                <a href="https://wa.me/?text=<?php echo urlencode('Support ' . $campaign->title . ' - ' . url('campaigns/' . $campaign->slug)); ?>"
                                    target="_blank" class="btn btn-sm btn-outline-success rounded-circle"
                                    style="width: 34px; height: 34px;"><i class="fab fa-whatsapp"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const presets = document.querySelectorAll('.preset-amount');
        const amountInput = document.getElementById('donation_amount');
        const btnAmount = document.getElementById('donate_btn_amount');

        presets.forEach(btn => {
            btn.addEventListener('click', function () {
                presets.forEach(b => b.classList.remove('btn-primary', 'active'));
                presets.forEach(b => b.classList.add('btn-outline-secondary'));
                this.classList.remove('btn-outline-secondary');
                this.classList.add('btn-primary');
                const val = this.getAttribute('data-amount');
                amountInput.value = val;
                btnAmount.textContent = Number(val).toLocaleString();
            });
        });

        if (amountInput) {
            amountInput.addEventListener('input', function () {
                presets.forEach(b => {
                    b.classList.remove('btn-primary');
                    b.classList.add('btn-outline-secondary');
                });
                btnAmount.textContent = this.value ? Number(this.value).toLocaleString() : '0';
            });
        }

        document.getElementById('campaign_claim_80g').addEventListener('change', function () {
            var wrap = document.getElementById('campaign_pan_wrap');
            var panInput = document.getElementById('campaign_donor_pan');
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
        document.getElementById('campaign_donor_pan').addEventListener('input', function () {
            this.value = this.value.toUpperCase();
        });
    });
</script>

<!-- Success Modal -->
<div class="modal fade" id="campaignSuccessModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm mx-2 mx-sm-auto">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-body text-center py-5 px-4">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-success bg-opacity-10 mb-3"
                    style="width: 64px; height: 64px;">
                    <i class="fas fa-check-circle text-success" style="font-size: 1.75rem;"></i>
                </div>
                <h5 class="fw-bold mb-1">Thank You!</h5>
                <p class="text-muted mb-4" style="font-size: 0.9rem;" id="campaignSuccessMsg">Your donation has been
                    completed successfully.</p>
                <div class="d-grid gap-2">
                    <a href="#" id="campaignReceiptLink" class="btn btn-accent rounded-pill" target="_blank">
                        <i class="fas fa-download me-2"></i> Download Receipt
                    </a>
                    <button type="button" class="btn btn-outline-secondary rounded-pill"
                        data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function showCampaignSuccessModal(msg, receiptUrl) {
        document.getElementById('campaignSuccessMsg').textContent = msg;
        var link = document.getElementById('campaignReceiptLink');
        if (receiptUrl) {
            link.href = receiptUrl;
            link.style.display = 'inline-flex';
        } else {
            link.style.display = 'none';
        }
        var modal = new bootstrap.Modal(document.getElementById('campaignSuccessModal'));
        modal.show();
    }
</script>

<?php if ($cmpActiveGw === 'razorpay'): ?>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        document.getElementById('campaign-donate-form').addEventListener('submit', function (e) {
            var method = this.querySelector('[name="payment_method"]').value;
            if (method === 'offline') {
                return;
            }

            e.preventDefault();
            var msgBox = document.getElementById('campaign-msg');
            var btn = document.getElementById('campaign-donate-btn');
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
                        description: 'Campaign Donation',
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
                                        showCampaignSuccessModal(vres.message || 'Donation successful!', vres.receipt_url || null);
                                    } else {
                                        msgBox.innerHTML = '<div class="alert alert-danger py-2 mb-0">' + (vres.message || 'Verification failed.') + '</div>';
                                    }
                                    btn.disabled = false;
                                    btn.innerHTML = '<i class="fas fa-heart me-2"></i> Donate Now';
                                })
                                .catch(function (err) {
                                    msgBox.innerHTML = '<div class="alert alert-danger py-2 mb-0">Verification request failed. Check console for details.</div>';
                                    console.error('Verify error:', err);
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
<?php endif; ?>

<?php require_once 'app/views/layouts/footer.php'; ?>