<?php require_once 'app/views/admin/layouts/header.php'; ?>
<?php require_once 'app/views/admin/layouts/sidebar.php'; ?>

<div class="page-wrapper">
    <?php require_once 'app/views/admin/layouts/topbar.php'; ?>

    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <ol class="breadcrumb" aria-label="breadcrumbs">
                        <li class="breadcrumb-item small"><a href="<?php echo url('admin/dashboard'); ?>">Home</a></li>
                        <li class="breadcrumb-item active small" aria-current="page">Finance Dashboard</li>
                    </ol>
                    <h2 class="page-title fw-bold fs-1">Finance Dashboard</h2>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <a href="<?php echo url('admin/finance/donations'); ?>" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
                            stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2" />
                            <path d="M12 3v3m0 12v3" />
                        </svg>
                        View All Donations
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <div class="row row-deck row-cards">
                <div class="col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="subheader">Total Donations</div>
                            </div>
                            <div class="h1 mb-2"><?php echo '₹'; ?><?php echo number_format($totalDonations, 2); ?>
                            </div>
                            <div class="text-muted small"><?php echo $totalDonors; ?> completed donations</div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="subheader">Total Expenses</div>
                            </div>
                            <div class="h1 mb-2"><?php echo '₹'; ?><?php echo number_format($totalExpenses, 2); ?></div>
                            <div class="text-muted small">All time expenses</div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="subheader">Balance</div>
                            </div>
                            <div class="h1 mb-2 <?php echo $balance >= 0 ? 'text-success' : 'text-danger'; ?>">
                                <?php echo '₹'; ?><?php echo number_format($balance, 2); ?></div>
                            <div class="text-muted small">Donations - Expenses</div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="subheader">Pending Donations</div>
                            </div>
                            <div class="h1 mb-2 text-warning"><?php echo $pendingCount; ?></div>
                            <div class="text-muted small">Awaiting confirmation</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">Monthly Donations (<?php echo date('Y'); ?>)</h3>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <canvas id="monthlyChart" style="max-height: 300px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctx = document.getElementById('monthlyChart').getContext('2d');
    var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    var data = [];
    <?php for ($m = 1; $m <= 12; $m++): ?>
        <?php $val = 0;
        foreach ($monthlyDonations as $md) {
            if ((int) $md->month === $m) {
                $val = (float) $md->total;
                break;
            }
        } ?>
        data.push(<?php echo $val; ?>);
    <?php endfor; ?>
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: months,
            datasets: [{
                label: 'Donations (₹)',
                data: data,
                backgroundColor: 'rgba(32, 107, 196, 0.7)',
                borderColor: 'rgba(32, 107, 196, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });
</script>

<?php require_once 'app/views/admin/layouts/footer.php'; ?>