<?php require_once 'app/views/admin/layouts/header.php'; ?>
<?php require_once 'app/views/admin/layouts/sidebar.php'; ?>

<?php
$openIssues = [];
$catNames = ['security' => 'Security', 'url_manipulation' => 'URL', 'payment' => 'Payment', 'error_pages' => 'Errors', 'broken_links' => 'Links', 'broken_ui' => 'UI', 'pdf' => 'PDF', 'other' => 'Other'];
foreach ($issues as $category => $categoryIssues) {
    foreach ($categoryIssues as $issue) {
        $issue['category'] = $category;
        $openIssues[] = $issue;
    }
}
$openCount = count($openIssues);
$total = array_sum(array_map('count', $all_issues));
$fixedCount = $total - $openCount;
?>

<div class="page-wrapper">
    <?php require_once 'app/views/admin/layouts/topbar.php'; ?>

    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <ol class="breadcrumb" aria-label="breadcrumbs">
                        <li class="breadcrumb-item"><a href="<?php echo url('admin/dashboard'); ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active">System Analysis</li>
                    </ol>
                    <h2 class="page-title">System Analysis</h2>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <span class="badge bg-success fs-5 px-3 py-2"><?php echo $fixedCount; ?>/<?php echo $total; ?> Fixed</span>
                </div>
            </div>
            <div class="text-muted small mt-1">Last scanned: <?php echo $audit_date; ?></div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <?php if ($openCount === 0): ?>
            <div class="text-center py-5">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-3 text-success" width="64" height="64" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10"/></svg>
                <h3 class="text-success">All Clear</h3>
                <p class="text-muted">All <?php echo $total; ?> issues have been resolved.</p>
            </div>
            <?php else: ?>
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th class="w-1">Severity</th>
                                    <th>Issue</th>
                                    <th class="w-1">File</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($openIssues as $issue):
                                    $sev = strtoupper($issue['severity'] ?? 'LOW');
                                    $sevClass = $sev === 'CRITICAL' ? 'bg-danger' : ($sev === 'HIGH' ? 'bg-warning text-dark' : ($sev === 'MEDIUM' ? 'bg-info' : 'bg-secondary'));
                                ?>
                                <tr>
                                    <td><span class="badge <?php echo $sevClass; ?>"><?php echo $sev; ?></span></td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($issue['title'] ?? ''); ?></strong>
                                        <div class="text-muted small"><?php echo htmlspecialchars($issue['description'] ?? ''); ?></div>
                                    </td>
                                    <td class="text-nowrap"><code class="small"><?php echo htmlspecialchars($issue['file'] ?? ''); ?></code></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <?php require_once 'app/views/admin/layouts/footer.php'; ?>
</div>
