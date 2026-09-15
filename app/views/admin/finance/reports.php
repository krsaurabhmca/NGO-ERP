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
                        <li class="breadcrumb-item active small" aria-current="page">Financial Reports</li>
                    </ol>
                    <h2 class="page-title fw-bold fs-1">Financial Reports</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <form class="card card-sm mb-3" method="GET">
                <div class="card-body">
                    <div class="row g-3 align-items-end">
                        <div class="col-12">
                            <div class="btn-group btn-group-sm" id="quick-dates">
                                <button type="button" class="btn btn-outline-secondary" data-months="0">This
                                    Month</button>
                                <button type="button" class="btn btn-outline-secondary" data-months="-1">Last
                                    Month</button>
                                <button type="button" class="btn btn-outline-secondary" data-months="-3">Last 3
                                    Months</button>
                                <button type="button" class="btn btn-outline-secondary" data-months="-12">This
                                    Year</button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">From</label>
                            <input type="date" name="from" id="report-from" class="form-control"
                                value="<?php echo $from; ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">To</label>
                            <input type="date" name="to" id="report-to" class="form-control" value="<?php echo $to; ?>">
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary w-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                                    <path d="M21 21l-6 -6" />
                                </svg>
                                Generate Report
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <div class="rounded-3 bg-success-lt p-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-success" width="32"
                                            height="32" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2" />
                                            <path d="M12 3v3m0 12v3" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="text-muted small">Donations</div>
                                    <div class="fw-bold fs-3 text-success">
                                        <?php echo '₹'; ?><?php echo number_format($donationTotal, 2); ?></div>
                                    <div class="text-muted small"><?php echo $donationCount; ?> transactions</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <div class="rounded-3 bg-danger-lt p-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-danger" width="32"
                                            height="32" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M17 8v-3a1 1 0 0 0 -1 -1h-10a2 2 0 0 0 0 4h12a1 1 0 0 1 1 1v3m0 4v3a1 1 0 0 1 -1 1h-12a2 2 0 0 1 -2 -2v-12" />
                                            <path d="M9 16h1" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="text-muted small">Expenses</div>
                                    <div class="fw-bold fs-3 text-danger">
                                        <?php echo '₹'; ?><?php echo number_format($expenseTotal, 2); ?></div>
                                    <div class="text-muted small">Period total</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <div
                                        class="rounded-3 bg-<?php echo $balance >= 0 ? 'primary' : 'warning'; ?>-lt p-2">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="icon text-<?php echo $balance >= 0 ? 'primary' : 'warning'; ?>"
                                            width="32" height="32" viewBox="0 0 24 24" stroke-width="1.5"
                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
                                            <path d="M12 7v5l3 3" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="text-muted small">Net Balance</div>
                                    <div
                                        class="fw-bold fs-3 <?php echo $balance >= 0 ? 'text-primary' : 'text-warning'; ?>">
                                        <?php echo '₹'; ?><?php echo number_format($balance, 2); ?></div>
                                    <div class="text-muted small"><?php echo $from; ?> to <?php echo $to; ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Donations vs Expenses (Monthly)</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="combinedChart" style="max-height: 280px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Monthly Breakdown</h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-sm table-vcenter card-table">
                                    <thead>
                                        <tr>
                                            <th>Month</th>
                                            <th class="text-end">Donations</th>
                                            <th class="text-end">Expenses</th>
                                            <th class="text-end">Net</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']; ?>
                                        <?php for ($m = 1; $m <= 12; $m++): ?>
                                            <?php $dv = 0;
                                            foreach ($monthlyDonations as $md) {
                                                if ((int) $md->month === $m) {
                                                    $dv = (float) $md->total;
                                                    break;
                                                }
                                            } ?>
                                            <?php $ev = $monthlyExpenses[$m] ?? 0; ?>
                                            <?php $nv = $dv - $ev; ?>
                                            <tr>
                                                <td><?php echo $months[$m - 1]; ?></td>
                                                <td class="text-end text-success">
                                                    <?php echo '₹'; ?>    <?php echo number_format($dv, 2); ?></td>
                                                <td class="text-end text-danger">
                                                    <?php echo '₹'; ?>    <?php echo number_format($ev, 2); ?></td>
                                                <td
                                                    class="text-end fw-bold <?php echo $nv >= 0 ? 'text-primary' : 'text-warning'; ?>">
                                                    <?php echo '₹'; ?>    <?php echo number_format($nv, 2); ?></td>
                                            </tr>
                                        <?php endfor; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Income vs Expense Distribution</h3>
                        </div>
                        <div class="card-body">
                            <div style="max-height: 250px;">
                                <canvas id="donutChart"></canvas>
                            </div>
                            <div class="row text-center mt-3 g-2">
                                <div class="col-6">
                                    <div class="p-2 rounded bg-success-lt">
                                        <div class="small text-muted">Income</div>
                                        <div class="fw-bold text-success">
                                            <?php echo '₹'; ?><?php echo number_format($donationTotal, 2); ?></div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-2 rounded bg-danger-lt">
                                        <div class="small text-muted">Expenses</div>
                                        <div class="fw-bold text-danger">
                                            <?php echo '₹'; ?><?php echo number_format($expenseTotal, 2); ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

    var donData = [];
    <?php for ($m = 1; $m <= 12; $m++): ?>
        <?php $dv = 0;
        foreach ($monthlyDonations as $md) {
            if ((int) $md->month === $m) {
                $dv = (float) $md->total;
                break;
            }
        } ?>
        donData.push(<?php echo $dv; ?>);
    <?php endfor; ?>

    var expData = [<?php for ($m = 1; $m <= 12; $m++): ?><?php echo $monthlyExpenses[$m] ?? 0; ?><?php echo $m < 12 ? ',' : ''; ?><?php endfor; ?>];

    new Chart(document.getElementById('combinedChart'), {
        type: 'bar',
        data: {
            labels: months,
            datasets: [{
                label: 'Donations',
                data: donData,
                backgroundColor: 'rgba(47, 179, 68, 0.7)',
                borderColor: '#2fb344',
                borderWidth: 1
            }, {
                label: 'Expenses',
                data: expData,
                backgroundColor: 'rgba(214, 57, 57, 0.7)',
                borderColor: '#d63939',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: true, position: 'top' } },
            scales: { y: { beginAtZero: true } }
        }
    });

    new Chart(document.getElementById('donutChart'), {
        type: 'doughnut',
        data: {
            labels: ['Income (Donations)', 'Expenses'],
            datasets: [{
                data: [<?php echo $donationTotal; ?>, <?php echo $expenseTotal; ?>],
                backgroundColor: ['rgba(47, 179, 68, 0.8)', 'rgba(214, 57, 57, 0.8)'],
                borderColor: ['#2fb344', '#d63939'],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: true, position: 'bottom' } }
        }
    });

    document.querySelectorAll('#quick-dates button').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var months = parseInt(this.getAttribute('data-months'));
            var d = new Date();
            var to = new Date(d.getFullYear(), d.getMonth() + 1, 0);
            var from = new Date(d.getFullYear(), d.getMonth() + months + 1, 1);
            document.getElementById('report-from').value = from.toISOString().slice(0, 10);
            document.getElementById('report-to').value = to.toISOString().slice(0, 10);
        });
    });
</script>

<?php require_once 'app/views/admin/layouts/footer.php'; ?>