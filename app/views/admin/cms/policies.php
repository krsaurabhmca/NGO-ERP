<?php require_once 'app/views/admin/layouts/header.php'; ?>
<?php require_once 'app/views/admin/layouts/sidebar.php'; ?>

<div class="page-wrapper">
    <?php require_once 'app/views/admin/layouts/topbar.php'; ?>

    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <div class="mb-1">
                        <ol class="breadcrumb" aria-label="breadcrumbs">
                            <li class="breadcrumb-item small"><a href="<?php echo url('admin/dashboard'); ?>">Home</a></li>
                            <li class="breadcrumb-item small"><a href="#">CMS</a></li>
                            <li class="breadcrumb-item active small" aria-current="page">Policies</li>
                        </ol>
                    </div>
                    <h2 class="page-title fw-bold fs-1">
                        Manage Policies
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">

            <div class="card border-0 shadow-sm overflow-hidden">
                <form action="<?php echo url('admin/cms/update-policies'); ?>" method="POST" id="policies-form">
                <input type="hidden" name="active_tab" id="active_tab_input" value="tab-privacy">
                <div class="row g-0">
                    <!-- Sidebar Navigation -->
                    <div class="col-12 col-md-3 border-end">
                        <div class="card-body">
                            <h4 class="subheader mb-4">Policy Pages</h4>
                            <div class="list-group list-group-transparent mb-3">
                                <a href="#tab-privacy" class="list-group-item list-group-item-action d-flex align-items-center active" data-bs-toggle="tab">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 10a4 4 0 1 0 0 -8a4 4 0 0 0 0 8" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
                                    Privacy Policy
                                </a>
                                <a href="#tab-terms" class="list-group-item list-group-item-action d-flex align-items-center" data-bs-toggle="tab">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 9h1" /><path d="M9 13h6" /><path d="M9 17h6" /></svg>
                                    Terms & Conditions
                                </a>
                                <a href="#tab-refund" class="list-group-item list-group-item-action d-flex align-items-center" data-bs-toggle="tab">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z" /></svg>
                                    Refund Policy
                                </a>
                            </div>

                        </div>
                    </div>

                    <!-- Content Section -->
                    <div class="col-12 col-md-9 d-flex flex-column">
                        <div class="card-body">
                            <div class="tab-content">
                                <!-- Privacy Policy -->
                                <div class="tab-pane active show" id="tab-privacy">
                                        <h3 class="card-title mb-4">Privacy Policy</h3>
                                        <p class="text-muted small mb-4">This content will be displayed on the <a href="<?php echo url('/privacy-policy'); ?>" target="_blank">Privacy Policy</a> public page.</p>
                                        <div class="mb-3">
                                            <textarea name="policy_privacy" class="form-control" rows="20" placeholder="Enter privacy policy content..."><?php echo htmlspecialchars($settings['policy_privacy'] ?? ''); ?></textarea>
                                        </div>
                                        <div class="mt-4 pt-3 border-top d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" /><path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M14 4l0 4l-6 0l0 -4" /></svg>
                                                Save All Policies
                                            </button>
                                        </div>
                                </div>

                                <!-- Terms & Conditions -->
                                <div class="tab-pane" id="tab-terms">
                                        <h3 class="card-title mb-4">Terms & Conditions</h3>
                                        <p class="text-muted small mb-4">This content will be displayed on the <a href="<?php echo url('/terms-and-conditions'); ?>" target="_blank">Terms & Conditions</a> public page.</p>
                                        <div class="mb-3">
                                            <textarea name="policy_terms" class="form-control" rows="20" placeholder="Enter terms & conditions content..."><?php echo htmlspecialchars($settings['policy_terms'] ?? ''); ?></textarea>
                                        </div>
                                        <div class="mt-4 pt-3 border-top d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" /><path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M14 4l0 4l-6 0l0 -4" /></svg>
                                                Save All Policies
                                            </button>
                                        </div>
                                </div>

                                <!-- Refund Policy -->
                                <div class="tab-pane" id="tab-refund">
                                        <h3 class="card-title mb-4">Refund Policy</h3>
                                        <p class="text-muted small mb-4">This content will be displayed on the <a href="<?php echo url('/refund-policy'); ?>" target="_blank">Refund Policy</a> public page.</p>
                                        <div class="mb-3">
                                            <textarea name="policy_refund" class="form-control" rows="20" placeholder="Enter refund policy content..."><?php echo htmlspecialchars($settings['policy_refund'] ?? ''); ?></textarea>
                                        </div>
                                        <div class="mt-4 pt-3 border-top d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" /><path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M14 4l0 4l-6 0l0 -4" /></svg>
                                                Save All Policies
                                            </button>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>

    <?php require_once 'app/views/admin/layouts/footer.php'; ?>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const hash = window.location.hash;
        if (hash) {
            const triggerEl = document.querySelector(`.list-group-item[href="${hash}"]`);
            if (triggerEl) {
                bootstrap.Tab.getInstance(triggerEl)?.show() || new bootstrap.Tab(triggerEl).show();
            }
        }

        const tabTriggers = document.querySelectorAll('.list-group-item[data-bs-toggle="tab"]');
        tabTriggers.forEach(trigger => {
            trigger.addEventListener('shown.bs.tab', (e) => {
                const targetHash = e.target.getAttribute('href');
                window.location.hash = targetHash;
                const activeTabInput = document.getElementById('active_tab_input');
                if(activeTabInput) {
                    activeTabInput.value = targetHash.substring(1);
                }
            });
        });
    });
</script>
