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

<!-- Logout Modal -->
<div class="modal modal-blur fade" id="modal-logout" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-status bg-danger"></div>
            <div class="modal-body text-center py-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-danger icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v2m0 4v.01" /><path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75" /></svg>
                <h3>Are you sure?</h3>
                <div class="text-muted">Do you really want to logout from the system?</div>
            </div>
            <div class="modal-footer">
                <div class="w-100">
                    <div class="row">
                        <div class="col">
                            <a href="#" class="btn w-100" data-bs-dismiss="modal">Cancel</a>
                        </div>
                        <div class="col">
                            <form action="<?php echo url('logout'); ?>" method="POST" style="display:inline;">
                                <?php echo csrf_field('admin/logout'); ?>
                                <button type="submit" class="btn btn-danger w-100">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal modal-blur fade" id="modal-delete" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-status bg-danger"></div>
            <div class="modal-body text-center py-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-danger icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                <h3>Confirm Delete</h3>
                <div class="text-muted">Are you sure you want to delete this item? This action cannot be undone.</div>
            </div>
            <div class="modal-footer">
                <div class="w-100">
                    <div class="row">
                        <div class="col">
                            <a href="#" class="btn w-100" data-bs-dismiss="modal">Cancel</a>
                        </div>
                        <div class="col">
                            <a href="#" id="confirm-delete-btn" class="btn btn-danger w-100">Delete</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast Container -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto" id="toast-title">Notification</strong>
            <small id="toast-time">Just now</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" id="toast-message"></div>
    </div>
</div>

<!-- Libs JS -->
<script src="<?php echo url('assets/js/tabler.min.js'); ?>" defer></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Desktop Sidebar Toggle
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const body = document.body;

        if (localStorage.getItem('sidebar-collapsed') === 'true') {
            body.classList.add('sidebar-collapsed');
        }

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function() {
                body.classList.toggle('sidebar-collapsed');
                localStorage.setItem('sidebar-collapsed', body.classList.contains('sidebar-collapsed'));
            });
        }

        // Mobile Sidebar Overlay Toggle
        const mobileToggle = document.getElementById('mobile-sidebar-toggle');
        const memberSidebar = document.getElementById('member-sidebar');
        const adminSidebar = document.getElementById('admin-sidebar');
        const sidebarOverlay = document.getElementById('sidebar-overlay');
        const mobileSidebar = memberSidebar || adminSidebar;

        function openMobileSidebar() {
            if (!mobileSidebar || !sidebarOverlay) return;
            mobileSidebar.classList.add('active');
            sidebarOverlay.classList.add('active');
            body.style.overflow = 'hidden';
        }

        function closeMobileSidebar() {
            if (!mobileSidebar || !sidebarOverlay) return;
            mobileSidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');
            body.style.overflow = '';
        }

        if (mobileToggle) {
            mobileToggle.addEventListener('click', openMobileSidebar);
        }

        // Mobile sidebar close button
        const mobileClose = document.getElementById('mobile-sidebar-close');
        if (mobileClose) {
            mobileClose.addEventListener('click', closeMobileSidebar);
        }

        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', closeMobileSidebar);
        }

        // Close sidebar on link click (mobile)
        if (mobileSidebar) {
            mobileSidebar.querySelectorAll('.nav-link, .dropdown-item').forEach(function(link) {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 992) closeMobileSidebar();
                });
            });
        }

        // Function to show Toast
        window.showToast = function(message, type = 'info', title = 'Notification') {
            const toastEl = document.getElementById('liveToast');
            const toastTitleEl = document.getElementById('toast-title');
            const toastMessageEl = document.getElementById('toast-message');
            
            toastTitleEl.innerText = title;
            toastMessageEl.innerText = message;
            
            // Set color based on type
            toastEl.className = 'toast';
            if (type === 'success') toastEl.classList.add('bg-success', 'text-white');
            if (type === 'error') toastEl.classList.add('bg-danger', 'text-white');
            if (type === 'warning') toastEl.classList.add('bg-warning', 'text-dark');
            
            const toast = new bootstrap.Toast(toastEl);
            toast.show();
        };

        // Handle Delete Modal confirmation - submit POST form
        window.confirmDelete = function(url, action, token) {
            const deleteBtn = document.getElementById('confirm-delete-btn');
            deleteBtn.onclick = function(e) {
                e.preventDefault();
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = url;

                var csrfAction = action || '_default';
                var csrfToken = token || (document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '');

                var actionInput = document.createElement('input');
                actionInput.type = 'hidden';
                actionInput.name = '_csrf_action';
                actionInput.value = csrfAction;
                form.appendChild(actionInput);

                var tokenInput = document.createElement('input');
                tokenInput.type = 'hidden';
                tokenInput.name = '_csrf_token';
                tokenInput.value = csrfToken;
                form.appendChild(tokenInput);

                document.body.appendChild(form);
                form.submit();
            };
            const modal = new bootstrap.Modal(document.getElementById('modal-delete'));
            modal.show();
        };

        // Auto-attach CSRF token to POST forms without explicit CSRF
        var csrfMeta = document.querySelector('meta[name="csrf-token"]');
        if (csrfMeta) {
            var token = csrfMeta.getAttribute('content');
            document.querySelectorAll('form[method="POST"]').forEach(function(f) {
                if (f.querySelector('[name="_csrf_token"]')) return;
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = '_csrf_token';
                input.value = token;
                f.appendChild(input);
            });
        }

        // Show session flash messages as toasts
        <?php if (isset($_SESSION['success'])): ?>
            showToast(<?php echo json_encode($_SESSION['success']); unset($_SESSION['success']); ?>, 'success', 'Success');
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            showToast(<?php echo json_encode($_SESSION['error']); unset($_SESSION['error']); ?>, 'error', 'Error');
        <?php endif; ?>

        <?php if (isset($_SESSION['welcome_toast'])): ?>
            showToast(<?php echo json_encode($_SESSION['welcome_toast']); unset($_SESSION['welcome_toast']); ?>, 'success', 'Welcome');
        <?php endif; ?>
        
        // Fix for Bootstrap modal aria-hidden focus warning
        document.addEventListener('hide.bs.modal', function (event) {
            if (document.activeElement) {
                document.activeElement.blur();
            }
        });

        // Auto-add password view toggle to all password inputs
        document.querySelectorAll('input[type="password"]').forEach(function(input) {
            if (input.parentElement.classList.contains('input-group') || input.parentElement.classList.contains('input-icon-wrap')) return;
            
            const wrapper = document.createElement('div');
            wrapper.className = 'input-group input-group-flat';
            input.parentNode.insertBefore(wrapper, input);
            wrapper.appendChild(input);

            const span = document.createElement('span');
            span.className = 'input-group-text';
            span.style.background = 'transparent';
            
            const link = document.createElement('a');
            link.href = 'javascript:void(0)';
            link.className = 'link-secondary';
            link.title = 'Show password';
            link.tabIndex = -1;
            link.innerHTML = '<i class="fas fa-eye"></i>';
            
            link.addEventListener('click', function(e) {
                e.preventDefault();
                if (input.type === 'password') {
                    input.type = 'text';
                    link.title = 'Hide password';
                    link.innerHTML = '<i class="fas fa-eye-slash"></i>';
                } else {
                    input.type = 'password';
                    link.title = 'Show password';
                    link.innerHTML = '<i class="fas fa-eye"></i>';
                }
            });
            span.appendChild(link);
            wrapper.appendChild(span);
        });
    });
</script>
</body>
</html>