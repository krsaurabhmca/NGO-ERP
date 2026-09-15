        <footer class="footer d-print-none" style="background:#fff;border-top:1px solid #e5e7eb;position:sticky;bottom:0;z-index:20;padding:0.5rem 0;">
            <div class="container-xl">
                <div class="row align-items-center">
                    <div class="col">
                        <span style="font-size:0.78rem;color:#64748b;">
                            <?php if (!empty($globalSettings['ngo_footer_credits'])): ?>
                                <?php echo $globalSettings['ngo_footer_credits']; ?>
                            <?php else: ?>
                                Copyright &copy; <?php echo date('Y'); ?>
                                <a href="." style="color:#003566;text-decoration:none;font-weight:600;"><?php echo htmlspecialchars($globalSettings['ngo_name'] ?? 'NGO HELP'); ?></a>.
                                All rights reserved.
                            <?php endif; ?>
                        </span>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</div>

<!-- Toast Container -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto" id="toast-title">Notification</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" id="toast-message"></div>
    </div>
</div>

<!-- Libs JS -->
<script src="<?php echo url('assets/js/tabler.min.js'); ?>" defer></script>
<script>
    window.showToast = function(message, type = 'info', title = 'Notification') {
        const toastEl = document.getElementById('liveToast');
        const toastTitleEl = document.getElementById('toast-title');
        const toastMessageEl = document.getElementById('toast-message');
        toastTitleEl.innerText = title;
        toastMessageEl.innerText = message;
        toastEl.className = 'toast';
        if (type === 'success') toastEl.classList.add('bg-success', 'text-white');
        if (type === 'error') toastEl.classList.add('bg-danger', 'text-white');
        if (type === 'warning') toastEl.classList.add('bg-warning', 'text-dark');
        const toast = new bootstrap.Toast(toastEl);
        toast.show();
    };

    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.getElementById('sidebar-toggle');
        const body = document.body;
        if (localStorage.getItem('sidebar-collapsed') === 'true') body.classList.add('sidebar-collapsed');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', function() {
                body.classList.toggle('sidebar-collapsed');
                localStorage.setItem('sidebar-collapsed', body.classList.contains('sidebar-collapsed'));
            });
        }
        const mobileToggle = document.getElementById('mobile-sidebar-toggle');
        const sidebar = document.getElementById('member-sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        if (mobileToggle && sidebar && overlay) {
            function openMobile() { sidebar.classList.add('active'); overlay.classList.add('active'); document.body.style.overflow = 'hidden'; }
            function closeMobile() { sidebar.classList.remove('active'); overlay.classList.remove('active'); document.body.style.overflow = ''; }
            mobileToggle.addEventListener('click', openMobile);
            overlay.addEventListener('click', closeMobile);
        }
    });

    <?php if (isset($_SESSION['success'])): ?>
        showToast(<?php echo json_encode($_SESSION['success']); unset($_SESSION['success']); ?>, 'success', 'Success');
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        showToast(<?php echo json_encode($_SESSION['error']); unset($_SESSION['error']); ?>, 'error', 'Error');
    <?php endif; ?>
    <?php if (isset($_SESSION['welcome_toast'])): ?>
        showToast(<?php echo json_encode($_SESSION['welcome_toast']); unset($_SESSION['welcome_toast']); ?>, 'success', 'Welcome');
    <?php endif; ?>
</script>
</body>
</html>