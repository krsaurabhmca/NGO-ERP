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

                    <h2 class="fw-bold h4 mb-3">1. Introduction</h2>
                    <p class="text-muted lh-lg mb-4">
                        Welcome to <?php echo !empty($globalSettings['ngo_name']) ? $globalSettings['ngo_name'] : 'NGO HELP'; ?>. We are committed to protecting your personal information and your right to privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you visit our website or use our services.
                    </p>

                    <h2 class="fw-bold h4 mb-3">2. Information We Collect</h2>
                    <p class="text-muted lh-lg mb-2">We may collect the following types of information:</p>
                    <ul class="text-muted lh-lg mb-4">
                        <li><strong>Personal Information:</strong> Name, email address, phone number, address, date of birth, and other details you provide through forms.</li>
                        <li><strong>Donation Information:</strong> Payment details, transaction records, and donation history.</li>
                        <li><strong>Usage Data:</strong> Browser type, IP address, pages visited, and time spent on our website.</li>
                    </ul>

                    <h2 class="fw-bold h4 mb-3">3. How We Use Your Information</h2>
                    <p class="text-muted lh-lg mb-2">We use the collected information for the following purposes:</p>
                    <ul class="text-muted lh-lg mb-4">
                        <li>To process donations and issue receipts.</li>
                        <li>To communicate with you regarding our programs, events, and updates.</li>
                        <li>To improve our website and services.</li>
                        <li>To comply with legal obligations.</li>
                        <li>To send newsletters and promotional materials (with your consent).</li>
                    </ul>

                    <h2 class="fw-bold h4 mb-3">4. Data Sharing & Disclosure</h2>
                    <p class="text-muted lh-lg mb-4">
                        We do not sell, trade, or rent your personal information to third parties. We may share your information with trusted service providers who assist us in operating our website and processing payments, all of whom are bound by confidentiality agreements. We may also disclose information when required by law or to protect our rights.
                    </p>

                    <h2 class="fw-bold h4 mb-3">5. Data Security</h2>
                    <p class="text-muted lh-lg mb-4">
                        We implement appropriate technical and organizational security measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction. However, no method of transmission over the Internet is 100% secure, and we cannot guarantee absolute security.
                    </p>

                    <h2 class="fw-bold h4 mb-3">6. Cookies & Tracking</h2>
                    <p class="text-muted lh-lg mb-4">
                        Our website may use cookies and similar tracking technologies to enhance your browsing experience. You can set your browser to refuse cookies, but some features of our website may not function properly as a result.
                    </p>

                    <h2 class="fw-bold h4 mb-3">7. Third-Party Links</h2>
                    <p class="text-muted lh-lg mb-4">
                        Our website may contain links to third-party websites. We are not responsible for the privacy practices or content of these external sites. We encourage you to review their privacy policies before providing any personal information.
                    </p>

                    <h2 class="fw-bold h4 mb-3">8. Your Rights</h2>
                    <p class="text-muted lh-lg mb-2">Depending on your location, you may have the following rights:</p>
                    <ul class="text-muted lh-lg mb-4">
                        <li>Right to access your personal data.</li>
                        <li>Right to rectify inaccurate information.</li>
                        <li>Right to request deletion of your data.</li>
                        <li>Right to withdraw consent at any time.</li>
                        <li>Right to lodge a complaint with a supervisory authority.</li>
                    </ul>

                    <h2 class="fw-bold h4 mb-3">9. Children's Privacy</h2>
                    <p class="text-muted lh-lg mb-4">
                        Our services are not directed to individuals under the age of 13. We do not knowingly collect personal information from children. If we become aware that a child has provided us with personal data, we will take steps to delete such information.
                    </p>

                    <h2 class="fw-bold h4 mb-3">10. Changes to This Policy</h2>
                    <p class="text-muted lh-lg mb-4">
                        We reserve the right to update this Privacy Policy at any time. Any changes will be posted on this page with an updated "Last updated" date. We encourage you to review this policy periodically to stay informed.
                    </p>

                    <h2 class="fw-bold h4 mb-3">11. Contact Us</h2>
                    <p class="text-muted lh-lg mb-4">
                        If you have any questions or concerns about this Privacy Policy, please contact us at:<br>
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
