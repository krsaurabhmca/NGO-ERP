<?php require_once 'app/views/admin/layouts/header.php'; ?>
<?php require_once 'app/views/admin/layouts/sidebar.php'; ?>

<div class="page-wrapper">
    <?php require_once 'app/views/admin/layouts/topbar.php'; ?>

    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <div class="mb-3">
                        <ol class="breadcrumb" aria-label="breadcrumbs">
                            <li class="breadcrumb-item"><a href="<?php echo url('admin/dashboard'); ?>">Home</a></li>
                            <li class="breadcrumb-item"><a href="#">Settings</a></li>
                            <li class="breadcrumb-item active" aria-current="page"><a href="#">Payment Settings</a></li>
                        </ol>
                    </div>
                    <h2 class="page-title fw-bold fs-1">Payment Settings</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <form action="<?php echo url('admin/settings/payments'); ?>" method="POST">
                                        <input type="hidden" name="_csrf_token" value="<?php echo csrf_token(); ?>">

                <input type="hidden" name="active_tab" value="tab-payments">

                <div class="row row-cards">
                    <!-- Gateway & Mode -->
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="row g-3 align-items-end">
                                    <div class="col-md-4">
                                        <label class="form-label required">Active Gateway</label>
                                        <select name="active_online_gateway" class="form-select">
                                            <?php $activeGw = $settings['active_online_gateway'] ?? ''; ?>
                                            <option value="razorpay" <?php echo $activeGw === 'razorpay' ? 'selected' : ''; ?>>Razorpay</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label required">Mode</label>
                                        <div class="d-flex gap-3 pt-1">
                                            <label class="form-check form-switch form-check-inline mb-0">
                                                <input class="form-check-input" type="radio" name="razorpay_mode" value="test" <?php echo ($settings['razorpay_mode'] ?? 'test') === 'test' ? 'checked' : ''; ?>>
                                                <span class="form-check-label">Test</span>
                                            </label>
                                            <label class="form-check form-switch form-check-inline mb-0">
                                                <input class="form-check-input" type="radio" name="razorpay_mode" value="live" <?php echo ($settings['razorpay_mode'] ?? '') === 'live' ? 'checked' : ''; ?>>
                                                <span class="form-check-label">Live</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="alert <?php echo ($settings['razorpay_mode'] ?? 'test') === 'live' ? 'alert-success' : 'alert-warning'; ?> bg-<?php echo ($settings['razorpay_mode'] ?? 'test') === 'live' ? 'success' : 'warning'; ?>-lt border-0 py-2 mb-0 small">
                                            <i class="fas fa-<?php echo ($settings['razorpay_mode'] ?? 'test') === 'live' ? 'globe' : 'flask'; ?> me-1"></i>
                                            Currently in <strong><?php echo ($settings['razorpay_mode'] ?? 'test') === 'live' ? 'Live' : 'Test'; ?></strong> mode
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Test Keys -->
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <h4 class="card-title d-flex align-items-center gap-2 mb-3">
                                    <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-2 py-1" style="font-size: 0.65rem;">TEST</span>
                                    Test Credentials
                                </h4>
                                <div class="mb-3">
                                    <label class="form-label">Key ID</label>
                                    <input type="text" name="razorpay_test_key_id" class="form-control" value="<?php echo $settings['razorpay_test_key_id'] ?? ''; ?>" placeholder="rzp_test_xxxxxxxxxxxx">
                                </div>
                                <div>
                                    <label class="form-label">Key Secret</label>
                                    <input type="password" name="razorpay_test_key_secret" class="form-control" placeholder="<?php echo !empty($settings['razorpay_test_key_secret']) ? 'Leave empty to keep current secret' : 'Enter test secret key'; ?>">
                                    <?php if (!empty($settings['razorpay_test_key_secret'])): ?>
                                    <small class="text-muted"><i class="fas fa-lock me-1"></i>Secret is stored encrypted</small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Live Keys -->
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <h4 class="card-title d-flex align-items-center gap-2 mb-3">
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1" style="font-size: 0.65rem;">LIVE</span>
                                    Live Credentials
                                </h4>
                                <div class="mb-3">
                                    <label class="form-label">Key ID</label>
                                    <input type="text" name="razorpay_live_key_id" class="form-control" value="<?php echo $settings['razorpay_live_key_id'] ?? ''; ?>" placeholder="rzp_live_xxxxxxxxxxxx">
                                </div>
                                <div>
                                    <label class="form-label">Key Secret</label>
                                    <input type="password" name="razorpay_live_key_secret" class="form-control" placeholder="<?php echo !empty($settings['razorpay_live_key_secret']) ? 'Leave empty to keep current secret' : 'Enter live secret key'; ?>">
                                    <?php if (!empty($settings['razorpay_live_key_secret'])): ?>
                                    <small class="text-muted"><i class="fas fa-lock me-1"></i>Secret is stored encrypted</small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mt-3">
                    <div class="card-footer bg-transparent text-end">
                        <button type="submit" name="submit" class="btn btn-primary px-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" /><path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M14 4l0 4l-6 0l0 -4" /></svg>
                            Save Settings
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php require_once 'app/views/admin/layouts/footer.php'; ?>
</div>
