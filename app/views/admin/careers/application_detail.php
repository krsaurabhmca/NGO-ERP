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
                            <li class="breadcrumb-item"><a href="<?php echo url('admin/careers'); ?>">Careers</a></li>
                            <li class="breadcrumb-item"><a href="<?php echo url('admin/careers/applications'); ?>">Applications</a></li>
                            <li class="breadcrumb-item active" aria-current="page"><a href="#">#<?php echo $app->id; ?></a></li>
                        </ol>
                    </div>
                    <h2 class="page-title fw-bold fs-1">
                        Application Details
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h3 class="card-title">Applicant Information</h3>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-4">
                                <span class="avatar avatar-xl me-3" style="width: 60px; height: 60px; font-size: 1.5rem;"><?php echo strtoupper(substr($app->name, 0, 1)); ?></span>
                                <div>
                                    <h4 class="mb-1"><?php echo htmlspecialchars($app->name); ?></h4>
                                    <div class="text-muted small">
                                        <i class="fas fa-envelope me-1"></i> <?php echo htmlspecialchars($app->email); ?>
                                        <?php if (!empty($app->phone)): ?>
                                            <span class="mx-2">|</span>
                                            <i class="fas fa-phone me-1"></i> <?php echo htmlspecialchars($app->phone); ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-md-4">
                                    <div class="bg-light rounded-3 p-3 text-center">
                                        <div class="text-muted small mb-1">Position</div>
                                        <div class="fw-bold"><?php echo htmlspecialchars($app->career_title ?? 'N/A'); ?></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="bg-light rounded-3 p-3 text-center">
                                        <div class="text-muted small mb-1">Location</div>
                                        <div class="fw-bold"><?php echo htmlspecialchars($app->location ?? 'N/A'); ?></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="bg-light rounded-3 p-3 text-center">
                                        <div class="text-muted small mb-1">Job Type</div>
                                        <div class="fw-bold"><?php echo htmlspecialchars($app->job_type ?? 'N/A'); ?></div>
                                    </div>
                                </div>
                            </div>

                            <?php if (!empty($app->address)): ?>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Address</label>
                                    <p class="text-muted mb-1"><?php echo htmlspecialchars($app->address); ?></p>
                                    <p class="text-muted mb-0">
                                        <?php echo htmlspecialchars($app->city ?? ''); ?>
                                        <?php if (!empty($app->city)): ?>, <?php endif; ?>
                                        <?php echo htmlspecialchars($app->district ?? ''); ?>
                                        <?php if (!empty($app->district) && !empty($app->pincode)): ?> - <?php endif; ?>
                                        <?php echo htmlspecialchars($app->pincode ?? ''); ?>
                                        <?php if (!empty($app->state)): ?>, <?php echo htmlspecialchars($app->state); ?><?php endif; ?>
                                    </p>
                                </div>
                            <?php endif; ?>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Cover Letter</label>
                                <div class="bg-light rounded-3 p-3">
                                    <p class="mb-0 text-muted" style="white-space: pre-wrap;"><?php echo htmlspecialchars($app->cover_letter); ?></p>
                                </div>
                            </div>

                            <?php if (!empty($app->resume)): ?>
                                <div>
                                    <label class="form-label fw-semibold">Resume / CV</label>
                                    <a href="<?php echo file_url($app->resume); ?>" target="_blank" class="btn btn-outline-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" /><path d="M7 11l5 5l5 -5" /><path d="M12 4l0 12" /></svg>
                                        Download Resume
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h3 class="card-title">Update Status</h3>
                        </div>
                        <div class="card-body">
                            <?php
                                $badge = match($app->status) {
                                    'pending' => 'bg-yellow',
                                    'reviewed' => 'bg-blue',
                                    'shortlisted' => 'bg-purple',
                                    'called_for_interview' => 'bg-cyan',
                                    'rejected' => 'bg-danger',
                                    'hired' => 'bg-success',
                                    default => 'bg-secondary'
                                };
                            ?>
                            <div class="text-center mb-4">
                                <span class="badge <?php echo $badge; ?> px-3 py-2" style="font-size: 1rem;"><?php echo ucwords(str_replace('_', ' ', $app->status)); ?></span>
                            </div>

                            <form action="<?php echo url('admin/careers/application-update-status/' . ($app->uuid ?? $app->id)); ?>" method="POST">
                                <div class="mb-3">
                                    <label class="form-label">Change Status</label>
                                    <select name="status" class="form-select">
                                        <option value="pending" <?php echo $app->status === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="reviewed" <?php echo $app->status === 'reviewed' ? 'selected' : ''; ?>>Reviewed</option>
                                        <option value="shortlisted" <?php echo $app->status === 'shortlisted' ? 'selected' : ''; ?>>Shortlisted</option>
                                        <option value="called_for_interview" <?php echo $app->status === 'called_for_interview' ? 'selected' : ''; ?>>Called for Interview</option>
                                        <option value="rejected" <?php echo $app->status === 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                                        <option value="hired" <?php echo $app->status === 'hired' ? 'selected' : ''; ?>>Hired</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" /><path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M14 4l0 4l-6 0l0 -4" /></svg>
                                    Update Status
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Timeline</h3>
                        </div>
                        <div class="card-body">
                            <div class="list-group list-group-flush">
                                <div class="list-group-item d-flex align-items-center">
                                    <span class="status-dot status-dot-animated bg-yellow me-3"></span>
                                    <div class="flex-fill">
                                        <div class="fw-bold">Submitted</div>
                                        <div class="text-muted small"><?php echo date('d M Y, h:i A', strtotime($app->created_at)); ?></div>
                                    </div>
                                </div>
                                <?php if ($app->updated_at !== $app->created_at): ?>
                                    <div class="list-group-item d-flex align-items-center">
                                        <span class="status-dot bg-blue me-3"></span>
                                        <div class="flex-fill">
                                            <div class="fw-bold">Last Updated</div>
                                            <div class="text-muted small"><?php echo date('d M Y, h:i A', strtotime($app->updated_at)); ?></div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-start mt-3">
                <a href="<?php echo url('admin/careers/applications'); ?>" class="btn btn-outline-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /><path d="M5 12l6 6" /><path d="M5 12l6 -6" /></svg>
                    Back to Applications
                </a>
            </div>
        </div>
    </div>

    <?php require_once 'app/views/admin/layouts/footer.php'; ?>
</div>
