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
                            <li class="breadcrumb-item active" aria-current="page"><a href="#">Applications</a></li>
                        </ol>
                    </div>
                    <h2 class="page-title fw-bold fs-1">
                        Job Applications
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <div class="card border-0 shadow-sm">
                <div class="card-body py-2 border-bottom">
                    <div class="row g-2 align-items-center">
                        <div class="col">
                            <div class="input-icon input-icon-sm">
                                <span class="input-icon-addon"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg></span>
                                <input type="text" id="af-search" class="form-control form-control-sm" placeholder="Search applicant, email, phone...">
                            </div>
                        </div>
                        <div class="col-auto">
                            <select id="af-status" class="form-select form-select-sm" style="min-width:120px">
                                <option value="">All Status</option>
                                <?php
                                $statuses = ['pending', 'reviewed', 'shortlisted', 'called_for_interview', 'rejected', 'hired'];
                                foreach ($statuses as $s):
                                ?>
                                    <option value="<?php echo $s; ?>"><?php echo ucwords(str_replace('_', ' ', $s)); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-auto">
                            <select id="af-jobtype" class="form-select form-select-sm" style="min-width:120px">
                                <option value="">All Job Types</option>
                                <?php foreach ($jobTypes as $jt): ?>
                                    <option value="<?php echo htmlspecialchars($jt); ?>"><?php echo htmlspecialchars($jt); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-auto d-flex align-items-center gap-1">
                            <input type="date" id="af-from" class="form-control form-control-sm" style="width:130px" title="From date">
                            <span class="text-muted small">–</span>
                            <input type="date" id="af-to" class="form-control form-control-sm" style="width:130px" title="To date">
                        </div>
                        <div class="col-auto d-flex align-items-center gap-2">
                            <button type="button" id="af-clear" class="btn btn-sm btn-ghost-secondary d-none" title="Clear filters">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 6l-12 12" /><path d="M6 6l12 12" /></svg>
                                Clear
                            </button>
                            <span id="af-count" class="text-muted small d-none"></span>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table card-table table-vcenter text-nowrap datatable" id="applications-table">
                        <thead>
                            <tr>
                                <th class="w-1">S.No</th>
                                <th>Applicant Name</th>
                                <th>Position</th>
                                <th>Email / Phone</th>
                                <th>Submitted</th>
                                <th>Status</th>
                                <th class="w-1">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($applications)): ?>
                                <?php $i = 1; foreach ($applications as $app): ?>
                                    <tr data-name="<?php echo strtolower(htmlspecialchars($app->name)); ?>" data-email="<?php echo strtolower(htmlspecialchars($app->email)); ?>" data-phone="<?php echo htmlspecialchars($app->phone ?? ''); ?>" data-status="<?php echo strtolower(htmlspecialchars($app->status)); ?>" data-jobtype="<?php echo htmlspecialchars($app->job_type ?? ''); ?>" data-date="<?php echo date('Y-m-d', strtotime($app->created_at)); ?>">
                                        <td><span class="text-muted"><?php echo $i++; ?></span></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="avatar avatar-sm me-2"><?php echo strtoupper(substr($app->name, 0, 1)); ?></span>
                                                <div><?php echo htmlspecialchars($app->name); ?></div>
                                            </div>
                                        </td>
                                        <td><?php echo htmlspecialchars($app->career_title ?? 'N/A'); ?></td>
                                        <td>
                                            <div class="small"><?php echo htmlspecialchars($app->email); ?></div>
                                            <div class="small text-muted"><?php echo htmlspecialchars($app->phone ?? ''); ?></div>
                                        </td>
                                        <td class="text-muted small"><?php echo date('d M Y', strtotime($app->created_at)); ?></td>
                                        <td>
                                            <?php
                                                $badge = match($app->status) {
                                                    'pending' => 'bg-yellow-lt',
                                                    'reviewed' => 'bg-blue-lt',
                                                    'shortlisted' => 'bg-purple-lt',
                                                    'called_for_interview' => 'bg-cyan-lt',
                                                    'rejected' => 'bg-danger-lt',
                                                    'hired' => 'bg-green-lt',
                                                    default => 'bg-secondary-lt'
                                                };
                                            ?>
                                            <span class="badge <?php echo $badge; ?>"><?php echo ucwords(str_replace('_', ' ', $app->status)); ?></span>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <a href="mailto:<?php echo htmlspecialchars($app->email); ?>" class="btn btn-icon btn-success btn-sm" title="Send Email">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 5m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" /><path d="M3 7l9 6l9 -6" /></svg>
                                                </a>
                                                <?php if (!empty($app->phone)): ?>
                                                    <a href="tel:<?php echo htmlspecialchars($app->phone); ?>" class="btn btn-icon btn-info btn-sm" title="Call">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2" /></svg>
                                                    </a>
                                                <?php endif; ?>
                                                <a href="<?php echo url('admin/careers/application/' . ($app->uuid ?? $app->id)); ?>" class="btn btn-icon btn-primary btn-sm" title="View Details">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">No applications received yet.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.btn-ghost-secondary { color: #667382; background: transparent; border: none; }
.btn-ghost-secondary:hover { color: #e53e3e; background: rgba(229,62,62,.06); }
tr.d-filter-hide { display: none !important; }
#applications-table tbody tr { transition: opacity .15s ease; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var search = document.getElementById('af-search');
    var status = document.getElementById('af-status');
    var jobtype = document.getElementById('af-jobtype');
    var dateFrom = document.getElementById('af-from');
    var dateTo = document.getElementById('af-to');
    var clearBtn = document.getElementById('af-clear');
    var countEl = document.getElementById('af-count');
    var rows = document.querySelectorAll('#applications-table tbody tr[data-status]');
    var totalRows = rows.length;
    var timer = null;

    if (totalRows === 0) return;

    function applyFilters() {
        var q = search.value.toLowerCase().trim();
        var s = status.value;
        var jt = jobtype.value;
        var df = dateFrom.value;
        var dt = dateTo.value;
        var visible = 0;
        var hasFilter = q || s || jt || df || dt;

        rows.forEach(function(row) {
            var show = true;

            if (q) {
                var name = row.getAttribute('data-name') || '';
                var email = row.getAttribute('data-email') || '';
                var phone = row.getAttribute('data-phone') || '';
                if (name.indexOf(q) === -1 && email.indexOf(q) === -1 && phone.indexOf(q) === -1) show = false;
            }

            if (show && s) {
                if (row.getAttribute('data-status') !== s) show = false;
            }

            if (show && jt) {
                if (row.getAttribute('data-jobtype') !== jt) show = false;
            }

            if (show && df) {
                if (row.getAttribute('data-date') < df) show = false;
            }

            if (show && dt) {
                if (row.getAttribute('data-date') > dt) show = false;
            }

            if (show) {
                row.classList.remove('d-filter-hide');
                visible++;
            } else {
                row.classList.add('d-filter-hide');
            }
        });

        if (hasFilter) {
            countEl.textContent = visible + ' of ' + totalRows;
            countEl.classList.remove('d-none');
            clearBtn.classList.remove('d-none');
        } else {
            countEl.classList.add('d-none');
            clearBtn.classList.add('d-none');
        }
    }

    search.addEventListener('input', function() {
        clearTimeout(timer);
        timer = setTimeout(applyFilters, 200);
    });

    status.addEventListener('change', applyFilters);
    jobtype.addEventListener('change', applyFilters);
    dateFrom.addEventListener('change', applyFilters);
    dateTo.addEventListener('change', applyFilters);

    clearBtn.addEventListener('click', function() {
        search.value = '';
        status.value = '';
        jobtype.value = '';
        dateFrom.value = '';
        dateTo.value = '';
        applyFilters();
        search.focus();
    });
});
</script>

    <?php require_once 'app/views/admin/layouts/footer.php'; ?>
</div>
