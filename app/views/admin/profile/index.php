<?php require_once 'app/views/admin/layouts/header.php'; ?>
<?php require_once 'app/views/admin/layouts/sidebar.php'; ?>

<div class="page-wrapper">
    <?php require_once 'app/views/admin/layouts/topbar.php'; ?>

    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">My Profile</h2>
                    <div class="text-muted mt-1">Manage your account settings and preferences</div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <?php if (!empty($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible" role="alert">
                <div class="d-flex">
                    <div><i class="fas fa-check-circle me-2 mt-1"></i></div>
                    <div><?php echo e($_SESSION['success']); unset($_SESSION['success']); ?></div>
                </div>
                <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
            </div>
            <?php endif; ?>
            <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible" role="alert">
                <div class="d-flex">
                    <div><i class="fas fa-exclamation-triangle me-2 mt-1"></i></div>
                    <div><?php echo e($_SESSION['error']); unset($_SESSION['error']); ?></div>
                </div>
                <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
            </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <span class="avatar avatar-xl mb-4 rounded-circle shadow-sm d-inline-flex align-items-center justify-content-center" style="width: 120px; height: 120px; border: 4px solid #f0f2f5; background: #e9ecef;">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#003566" width="60" height="60"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
</span>
                            <h3 class="mb-1"><?php echo htmlspecialchars($user->name); ?></h3>
                            <div class="text-muted mb-3"><i class="fas fa-envelope me-1"></i> <?php echo htmlspecialchars($user->email); ?></div>
                            <span class="badge bg-primary-lt px-3 py-2 text-uppercase" style="letter-spacing: 1px;"><i class="fas fa-shield-alt me-1"></i> <?php echo htmlspecialchars($user->role_name ?? 'Admin'); ?></span>
                        </div>
                        <div class="card-footer bg-transparent">
                            <div class="d-flex justify-content-between align-items-center text-muted small">
                                <span><i class="fas fa-circle <?php echo $user->status === 'active' ? 'text-success' : 'text-danger'; ?> me-1" style="font-size: 8px; vertical-align: middle;"></i> <?php echo ucfirst($user->status); ?></span>
                                <span><i class="fas fa-clock me-1"></i> <?php echo $user->last_login ? date('d M, h:i A', strtotime($user->last_login)) : 'Never'; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Account Details</h3>
                        </div>
                        <form action="<?php echo url('admin/profile'); ?>" method="POST">
                            <div class="card-body">
                                <div class="row mb-4">
                                    <div class="col-md-6 mb-3 mb-md-0">
                                        <label class="form-label required">Full Name</label>
                                        <div class="input-icon">
                                            <span class="input-icon-addon"><i class="fas fa-user"></i></span>
                                            <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($user->name); ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label required">Email Address</label>
                                        <div class="input-icon">
                                            <span class="input-icon-addon"><i class="fas fa-envelope"></i></span>
                                            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user->email); ?>" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="hr-text">Security Settings</div>
                                
                                <p class="text-muted small mb-3"><i class="fas fa-info-circle me-1 text-primary"></i> Leave the password fields blank if you do not wish to change your current password.</p>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3 mb-md-0">
                                        <label class="form-label">New Password</label>
                                        <div class="input-icon">
                                            <span class="input-icon-addon"><i class="fas fa-lock text-muted"></i></span>
                                            <input type="password" name="password" class="form-control" placeholder="Min 6 characters" minlength="6">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Confirm New Password</label>
                                        <div class="input-icon">
                                            <span class="input-icon-addon"><i class="fas fa-check-circle text-muted"></i></span>
                                            <input type="password" name="confirm_password" class="form-control" placeholder="Re-enter new password">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent text-end">
                                <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-2"></i> Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<?php require_once 'app/views/admin/layouts/footer.php'; ?>
