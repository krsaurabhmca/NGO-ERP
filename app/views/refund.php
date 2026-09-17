<?php require_once 'app/views/layouts/header.php'; ?>

<section class="position-relative overflow-hidden" style="background-color: var(--primary); background-image: radial-gradient(circle at 10% 20%, var(--accent) 0%, transparent 50%), radial-gradient(circle at 90% 80%, var(--accent) 0%, transparent 50%), radial-gradient(circle at 50% 50%, var(--primary-dark) 0%, transparent 70%);">
    <div class="container-fluid px-lg-5 py-5 text-center position-relative" style="z-index: 1;">
        <h1 class="display-5 fw-bold text-white mb-0"><?php echo $title; ?></h1>
    </div>
    <div class="position-absolute top-0 end-0 opacity-10 hero-svg-circle">
        <svg width="400" height="400" viewBox="0 0 400 400" fill="none"><circle cx="300" cy="100" r="200" fill="var(--accent)"/><circle cx="100" cy="350" r="150" fill="var(--accent)"/></svg>
    </div>
    <div class="position-absolute bottom-0 start-0 opacity-10 hero-svg-circle">
        <svg width="300" height="300" viewBox="0 0 300 300" fill="none"><circle cx="50" cy="250" r="120" fill="var(--accent)"/></svg>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="card border-0 shadow-sm rounded-4 p-4 p-lg-5">
                    <?php if (!empty($content)): ?>
                        <div class="text-muted lh-lg"><?php echo nl2br(htmlspecialchars($content)); ?></div>
                    <?php else: ?>
                    <div class="mb-4">
                        <p class="text-muted small">Last updated: <?php echo date('F d, Y'); ?></p>
                    </div>

                    <h2 class="fw-bold h4 mb-3">1. Overview</h2>
                    <p class="text-muted lh-lg mb-4">
                        At <?php echo !empty($globalSettings['ngo_name']) ? htmlspecialchars($globalSettings['ngo_name']) : 'NGO HELP'; ?>, we value the trust and generosity of our donors. This Refund Policy outlines the circumstances under which donation refunds may be considered and the process for requesting a refund.
                    </p>

                    <h2 class="fw-bold h4 mb-3">2. General Policy</h2>
                    <p class="text-muted lh-lg mb-4">
                        As a general principle, all donations made to <?php echo !empty($globalSettings['ngo_name']) ? htmlspecialchars($globalSettings['ngo_name']) : 'NGO HELP'; ?> are non-refundable. Donations are considered final once processed, as they are immediately allocated to our ongoing projects and programs serving underprivileged communities.
                    </p>

                    <h2 class="fw-bold h4 mb-3">3. Eligibility for Refund</h2>
                    <p class="text-muted lh-lg mb-2">Refunds may be considered under the following exceptional circumstances:</p>
                    <ul class="text-muted lh-lg mb-4">
                        <li><strong>Duplicate Transaction:</strong> If the same donation was processed more than once due to a technical error.</li>
                        <li><strong>Unauthorized Transaction:</strong> If a donation was made fraudulently using your payment method without your authorization.</li>
                        <li><strong>Incorrect Amount:</strong> If an amount significantly higher than intended was donated due to a data entry error.</li>
                        <li><strong>Technical Failure:</strong> If a technical error resulted in payment being deducted but the donation was not recorded in our system.</li>
                    </ul>

                    <h2 class="fw-bold h4 mb-3">4. Refund Request Process</h2>
                    <p class="text-muted lh-lg mb-2">To request a refund, please follow the steps below:</p>
                    <ol class="text-muted lh-lg mb-4">
                        <li>Send an email to <strong><?php echo !empty($globalSettings['ngo_email']) ? $globalSettings['ngo_email'] : 'info@ngohelp.org'; ?></strong> with the subject line "Refund Request – Donation".</li>
                        <li>Include the following information:
                            <ul class="mt-1 mb-1">
                                <li>Your full name</li>
                                <li>Donation date and amount</li>
                                <li>Transaction/Reference ID</li>
                                <li>Reason for refund request</li>
                                <li>Contact phone number</li>
                            </ul>
                        </li>
                        <li>Our finance team will review your request within <strong>5 business days</strong>.</li>
                    </ol>

                    <h2 class="fw-bold h4 mb-3">5. Refund Timeline</h2>
                    <p class="text-muted lh-lg mb-2">Refund timelines depend on the payment method used:</p>
                    <ul class="text-muted lh-lg mb-4">
                        <li><strong>Razorpay:</strong> 5–7 business days to reflect in your bank account.</li>
                        <li><strong>UPI:</strong> 3–5 business days.</li>
                        <li><strong>Bank Transfer:</strong> 7–10 business days.</li>
                        <li><strong>Cash/Offline:</strong> Processed through direct bank transfer within 10 business days.</li>
                    </ul>

                    <h2 class="fw-bold h4 mb-3">6. Deductions</h2>
                    <p class="text-muted lh-lg mb-4">
                        In cases where a refund is approved for donations made through online payment gateways (Razorpay), the payment gateway processing fees (typically 2-3% of the transaction amount) may not be recoverable. The refund will be processed for the net amount received by the Organization.
                    </p>

                    <h2 class="fw-bold h4 mb-3">7. Non-Refundable Scenarios</h2>
                    <p class="text-muted lh-lg mb-2">Refunds will NOT be issued in the following situations:</p>
                    <ul class="text-muted lh-lg mb-4">
                        <li>Donor changes their mind after making a voluntary donation.</li>
                        <li>Request is submitted more than <strong>30 days</strong> after the donation date.</li>
                        <li>Insufficient documentation to verify the claim.</li>
                        <li>Donations made to specific campaigns where funds have already been disbursed.</li>
                        <li>Tax exemption certificate has already been issued for the donation.</li>
                    </ul>

                    <h2 class="fw-bold h4 mb-3">8. Cancellation of Recurring Donations</h2>
                    <p class="text-muted lh-lg mb-4">
                        If you have set up a recurring donation and wish to cancel future payments, you may do so at any time by contacting us. Cancellation of recurring donations will stop future payments but will not refund already processed transactions.
                    </p>

                    <h2 class="fw-bold h4 mb-3">9. In-Kind Donations</h2>
                    <p class="text-muted lh-lg mb-4">
                        For in-kind donations (goods, materials, clothing, food), returns or exchanges are not available. All in-kind donations are distributed directly to beneficiaries and cannot be retrieved.
                    </p>

                    <h2 class="fw-bold h4 mb-3">10. Changes to This Policy</h2>
                    <p class="text-muted lh-lg mb-4">
                        We reserve the right to modify this Refund Policy at any time. Changes will be posted on this page with an updated "Last updated" date. We encourage you to review this policy periodically.
                    </p>

                    <h2 class="fw-bold h4 mb-3">11. Contact Us</h2>
                    <p class="text-muted lh-lg mb-4">
                        For any questions regarding donations or refunds, please reach out to us:<br>
                        <?php if (!empty($globalSettings['ngo_email'])): ?><strong>Email:</strong> <?php echo htmlspecialchars($globalSettings['ngo_email']); ?><br><?php endif; ?>
                        <?php if (!empty($globalSettings['ngo_phone'])): ?><strong>Phone:</strong> <?php echo htmlspecialchars($globalSettings['ngo_phone']); ?><br><?php endif; ?>
                        <?php if (!empty($globalSettings['ngo_address'])): ?><strong>Address:</strong> <?php echo htmlspecialchars($globalSettings['ngo_address']); ?><?php endif; ?>
                    </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'app/views/layouts/footer.php'; ?>
