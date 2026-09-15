<?php require_once 'app/views/layouts/header.php'; ?>

<section class="position-relative overflow-hidden" style="background: linear-gradient(135deg, #003566 0%, #00224d 100%);">
    <div class="container-fluid px-lg-5 py-5 text-center position-relative" style="z-index: 1;">
        <h1 class="display-5 fw-bold text-white mb-0"><?php echo $title; ?></h1>
    </div>
    <div class="position-absolute top-0 end-0 opacity-10 hero-svg-circle">
        <svg width="400" height="400" viewBox="0 0 400 400" fill="none"><circle cx="300" cy="100" r="200" fill="#FFBF00"/><circle cx="100" cy="350" r="150" fill="#0d9488"/></svg>
    </div>
    <div class="position-absolute bottom-0 start-0 opacity-10 hero-svg-circle">
        <svg width="300" height="300" viewBox="0 0 300 300" fill="none"><circle cx="50" cy="250" r="120" fill="#FFBF00"/></svg>
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

                    <h2 class="fw-bold h4 mb-3">1. Acceptance of Terms</h2>
                    <p class="text-muted lh-lg mb-4">
                        By accessing or using the <?php echo !empty($globalSettings['ngo_name']) ? htmlspecialchars($globalSettings['ngo_name']) : 'NGO HELP'; ?> website and services, you agree to be bound by these Terms & Conditions. If you do not agree with any part of these terms, you must not use our website or services.
                    </p>

                    <h2 class="fw-bold h4 mb-3">2. Definitions</h2>
                    <p class="text-muted lh-lg mb-2">
                        Throughout these Terms, the following definitions apply:
                    </p>
                    <ul class="text-muted lh-lg mb-4">
                        <li><strong>"Organization"</strong> refers to <?php echo !empty($globalSettings['ngo_name']) ? htmlspecialchars($globalSettings['ngo_name']) : 'NGO HELP'; ?>, its affiliates, volunteers, and staff.</li>
                        <li><strong>"User"</strong> refers to any individual accessing or using our website and services.</li>
                        <li><strong>"Services"</strong> includes donations, volunteering, membership, event registration, and all other offerings.</li>
                        <li><strong>"Content"</strong> includes text, images, videos, documents, and all materials on the website.</li>
                    </ul>

                    <h2 class="fw-bold h4 mb-3">3. Donations & Payments</h2>
                    <p class="text-muted lh-lg mb-2">By making a donation, you agree that:</p>
                    <ul class="text-muted lh-lg mb-4">
                        <li>All donations are voluntary and non-refundable except as stated in our Refund Policy.</li>
                        <li>You are authorized to use the payment method provided.</li>
                        <li>All transaction information provided is accurate and complete.</li>
                        <li>Donations will be used at the Organization's discretion to further its charitable mission.</li>
                        <li>Tax receipts will be issued for eligible donations as per applicable laws.</li>
                    </ul>

                    <h2 class="fw-bold h4 mb-3">4. User Accounts</h2>
                    <p class="text-muted lh-lg mb-2">When registering for an account or membership, you agree to:</p>
                    <ul class="text-muted lh-lg mb-4">
                        <li>Provide accurate, current, and complete information.</li>
                        <li>Maintain and update your information as necessary.</li>
                        <li>Keep your login credentials confidential.</li>
                        <li>Accept responsibility for all activities under your account.</li>
                        <li>Notify us immediately of any unauthorized use of your account.</li>
                    </ul>

                    <h2 class="fw-bold h4 mb-3">5. Intellectual Property</h2>
                    <p class="text-muted lh-lg mb-4">
                        All content on this website, including but not limited to text, graphics, logos, images, software, and design elements, is the property of <?php echo !empty($globalSettings['ngo_name']) ? htmlspecialchars($globalSettings['ngo_name']) : 'NGO HELP'; ?> and is protected under applicable copyright, trademark, and intellectual property laws. You may not reproduce, distribute, modify, or create derivative works without prior written permission.
                    </p>

                    <h2 class="fw-bold h4 mb-3">6. User Conduct</h2>
                    <p class="text-muted lh-lg mb-2">Users of this website agree not to:</p>
                    <ul class="text-muted lh-lg mb-4">
                        <li>Use the website for any unlawful purpose or in violation of any applicable law.</li>
                        <li>Submit false, misleading, or fraudulent information.</li>
                        <li>Harass, abuse, or harm other users or staff.</li>
                        <li>Upload or transmit viruses, malware, or harmful code.</li>
                        <li>Attempt to gain unauthorized access to our systems or other users' accounts.</li>
                        <li>Scrape, crawl, or use automated means to collect data without permission.</li>
                    </ul>

                    <h2 class="fw-bold h4 mb-3">7. Volunteer & Membership Terms</h2>
                    <p class="text-muted lh-lg mb-4">
                        Volunteers and members must adhere to the Organization's code of conduct, policies, and guidelines. The Organization reserves the right to terminate volunteer or membership status at any time due to violation of these Terms, misconduct, or any act deemed detrimental to the Organization's mission or reputation.
                    </p>

                    <h2 class="fw-bold h4 mb-3">8. Limitation of Liability</h2>
                    <p class="text-muted lh-lg mb-4">
                        To the fullest extent permitted by law, <?php echo !empty($globalSettings['ngo_name']) ? htmlspecialchars($globalSettings['ngo_name']) : 'NGO HELP'; ?> shall not be liable for any direct, indirect, incidental, consequential, or punitive damages arising from your use of our website or services. The Organization makes no warranties or representations regarding the accuracy or completeness of information on this website.
                    </p>

                    <h2 class="fw-bold h4 mb-3">9. Third-Party Services</h2>
                    <p class="text-muted lh-lg mb-4">
                        Our website may integrate with third-party services such as payment gateways, social media platforms, and mapping services. We are not responsible for the practices, content, or availability of these third-party services. Your use of such services is subject to their respective terms and conditions.
                    </p>

                    <h2 class="fw-bold h4 mb-3">10. Termination</h2>
                    <p class="text-muted lh-lg mb-4">
                        We reserve the right to suspend or terminate your access to our services at any time, without prior notice, for conduct that we believe violates these Terms or is harmful to other users, the Organization, or third parties.
                    </p>

                    <h2 class="fw-bold h4 mb-3">11. Changes to Terms</h2>
                    <p class="text-muted lh-lg mb-4">
                        We reserve the right to update or modify these Terms & Conditions at any time. Changes will be posted on this page with an updated "Last updated" date. Continued use of our website after any changes constitutes your acceptance of the revised Terms.
                    </p>

                    <h2 class="fw-bold h4 mb-3">12. Governing Law</h2>
                    <p class="text-muted lh-lg mb-4">
                        These Terms are governed by and construed in accordance with the laws of India. Any disputes arising out of or relating to these Terms shall be subject to the exclusive jurisdiction of the courts located in Mumbai, India.
                    </p>

                    <h2 class="fw-bold h4 mb-3">13. Contact Information</h2>
                    <p class="text-muted lh-lg mb-4">
                        If you have any questions regarding these Terms & Conditions, please contact us:<br>
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
