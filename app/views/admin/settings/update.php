<?php require_once 'app/views/admin/layouts/header.php'; ?>
<?php require_once 'app/views/admin/layouts/sidebar.php'; ?>

<div class="page-wrapper">
    <?php require_once 'app/views/admin/layouts/topbar.php'; ?>
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        System Updates
                    </h2>
                    <div class="text-muted mt-1">Check and apply the latest updates from GitHub.</div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="page-body">
        <div class="container-xl">
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible" role="alert">
                    <div class="d-flex">
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M5 12l5 5l10 -10"></path></svg>
                        </div>
                        <div>
                            <?php echo e($_SESSION['success']); unset($_SESSION['success']); ?>
                        </div>
                    </div>
                    <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <div class="d-flex">
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"></path><path d="M12 9v4"></path><path d="M12 16v.01"></path></svg>
                        </div>
                        <div>
                            <?php echo e($_SESSION['error']); unset($_SESSION['error']); ?>
                        </div>
                    </div>
                    <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                </div>
            <?php endif; ?>

            <div class="row row-cards">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Update Status</h3>
                        </div>
                        <div class="card-body">
                            <?php if (empty($repoUrl)): ?>
                                <div class="alert alert-warning">
                                    GitHub Repository URL is not configured. Please set it in <a href="<?php echo url('admin/settings/organization#tab-settings'); ?>">Organization Settings</a>.
                                </div>
                            <?php else: ?>
                                <div class="mb-3">
                                    <strong>Configured Repository:</strong> <a href="<?php echo e($repoUrl); ?>" target="_blank"><?php echo e($repoUrl); ?></a>
                                </div>
                                
                                <div class="row mt-4">
                                    <div class="col-md-6">
                                        <div class="card card-sm shadow-sm border-primary">
                                            <div class="card-body">
                                                <div class="row align-items-center">
                                                    <div class="col-auto">
                                                        <span class="bg-primary text-white avatar">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 12l3 -2" /><path d="M12 7v5" /></svg>
                                                        </span>
                                                    </div>
                                                    <div class="col">
                                                        <div class="font-weight-medium">
                                                            Current Version
                                                        </div>
                                                        <div class="text-muted h1 mb-0">v<?php echo e($currentVersion); ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mt-3 mt-md-0">
                                        <div class="card card-sm shadow-sm <?php echo ($isOutdated ?? false) ? 'border-warning' : 'border-success'; ?>">
                                            <div class="card-body">
                                                <div class="row align-items-center">
                                                    <div class="col-auto">
                                                        <span class="<?php echo ($isOutdated ?? false) ? 'bg-warning' : 'bg-success'; ?> text-white avatar">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 2l3 5h6l-3 5l3 5h-6l-3 5l-3 -5h-6l3 -5l-3 -5h6z" /></svg>
                                                        </span>
                                                    </div>
                                                    <div class="col">
                                                        <div class="font-weight-medium">
                                                            Latest GitHub Release
                                                        </div>
                                                        <div class="text-muted h1 mb-0">
                                                            <?php if ($latestRelease): ?>
                                                                <?php echo e($latestRelease['tag_name']); ?>
                                                            <?php else: ?>
                                                                Unknown
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <?php if ($latestRelease): ?>
                                    <?php 
                                        $latestVer = trim($latestRelease['tag_name'], 'v');
                                        $isOutdated = version_compare($currentVersion, $latestVer, '<');
                                    ?>
                                    
                                    <div class="mt-5">
                                        <?php if ($isOutdated): ?>
                                            <div class="alert alert-warning shadow-sm" role="alert">
                                                <div class="d-flex">
                                                    <div>
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 8v4" /><path d="M12 16h.01" /></svg>
                                                    </div>
                                                    <div>
                                                        <h4 class="alert-title">New version available!</h4>
                                                        <div class="text-muted">Version <?php echo e($latestRelease['tag_name']); ?> is available. It is highly recommended to update your system.</div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <?php if (!empty($latestRelease['body'])): ?>
                                                <div class="card mb-4 shadow-sm border-info">
                                                    <div class="card-header bg-info-lt">
                                                        <h4 class="card-title text-info"><i class="fa fa-list-alt me-2"></i> Release Notes</h4>
                                                    </div>
                                                    <div class="card-body bg-light">
                                                        <pre class="mb-0" style="white-space: pre-wrap; font-family: inherit; font-size: 0.9em;"><?php echo e($latestRelease['body']); ?></pre>
                                                    </div>
                                                </div>
                                            <?php endif; ?>

                                            <form action="<?php echo url('admin/settings/update/run'); ?>" method="POST" id="update-form">
                                                <?php echo csrf_field('admin/settings/update'); ?>
                                                <button type="button" class="btn btn-primary btn-lg w-100 shadow-sm" onclick="confirmUpdate()">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" /><path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" /></svg>
                                                    Update System Now
                                                </button>
                                            </form>
                                            
                                            <div id="update-loading" class="text-center mt-4" style="display: none;">
                                                <div class="spinner-border text-primary" role="status"></div>
                                                <h4 class="mt-2 text-primary">Downloading and applying update... Please do not close this page.</h4>
                                            </div>
                                            
                                            <script>
                                                function confirmUpdate() {
                                                    if (confirm('Are you sure you want to run the update? This will overwrite system files.')) {
                                                        document.getElementById('update-form').style.display = 'none';
                                                        document.getElementById('update-loading').style.display = 'block';
                                                        document.getElementById('update-form').submit();
                                                    }
                                                }
                                            </script>

                                        <?php else: ?>
                                            <div class="alert alert-success shadow-sm" role="alert">
                                                <div class="d-flex">
                                                    <div>
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                                                    </div>
                                                    <div>
                                                        <h4 class="alert-title">You are up to date!</h4>
                                                        <div class="text-muted">You are running the latest version of the application. No further action is required.</div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
