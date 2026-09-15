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
                            <li class="breadcrumb-item"><a href="<?php echo url('admin/members'); ?>">Members</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Membership Fees</li>
                        </ol>
                    </div>
                    <h2 class="page-title fw-bold fs-1">Membership Fees</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <div class="card">
                <div class="card-body py-2 border-bottom">
                    <div class="row g-2 align-items-center">
                        <div class="col">
                            <div class="input-icon input-icon-sm">
                                <span class="input-icon-addon"><svg xmlns="http://www.w3.org/2000/svg" class="icon"
                                        width="16" height="16" viewBox="0 0 24 24" stroke-width="2"
                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                                        <path d="M21 21l-6 -6" />
                                    </svg></span>
                                <input type="text" id="mf-search" class="form-control form-control-sm"
                                    placeholder="Search name, ID...">
                            </div>
                        </div>
                        <div class="col-auto">
                            <select id="mf-designation" class="form-select form-select-sm" style="min-width:140px">
                                <option value="">All Designations</option>
                                <?php foreach ($designations as $d): ?>
                                    <option value="<?php echo strtolower(htmlspecialchars($d->name ?? $d->title)); ?>">
                                        <?php echo htmlspecialchars($d->name ?? $d->title); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-auto">
                            <select class="form-select form-select-sm" id="yearSelect" onchange="applyServerFilters()"
                                style="width: 90px;" title="Year">
                                <?php foreach ($years as $y): ?>
                                    <option value="<?php echo $y; ?>" <?php echo $year == $y ? 'selected' : ''; ?>>
                                        <?php echo $y; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-auto">
                            <select class="form-select form-select-sm" id="monthSelect" onchange="applyServerFilters()"
                                style="width: 110px;" title="Month">
                                <option value="0" <?php echo $month == 0 ? 'selected' : ''; ?>>All Months</option>
                                <?php foreach ($monthNames as $num => $name): ?>
                                    <option value="<?php echo $num; ?>" <?php echo $month == $num ? 'selected' : ''; ?>>
                                        <?php echo $name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-auto d-flex align-items-center gap-2">
                            <button type="button" id="mf-clear" class="btn btn-sm btn-ghost-secondary d-none"
                                title="Clear filters">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M18 6l-12 12" />
                                    <path d="M6 6l12 12" />
                                </svg>
                                Clear
                            </button>
                            <span id="mf-count" class="text-muted small d-none"></span>
                        </div>
                    </div>
                </div>
                <script>
                    function applyServerFilters() {
                        var y = document.getElementById('yearSelect').value;
                        var m = document.getElementById('monthSelect').value;
                        window.location = '?year=' + y + (m > 0 ? '&month=' + m : '');
                    }
                </script>
                <div class="table-responsive">
                    <table class="table card-table table-vcenter text-nowrap" id="fees-table">
                        <thead>
                            <tr>
                                <th>Member</th>
                                <th>Membership ID</th>
                                <th>Designation</th>
                                <th class="text-center">Monthly Fee</th>
                                <th class="text-center">Balance Due</th>
                                <th class="w-1 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($feesData)): ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">No member records found.</td>
                                </tr>
                            <?php else:
                                $i = 1;
                                foreach ($feesData as $f): ?>
                                    <tr data-name="<?php echo strtolower(htmlspecialchars($f['name'])); ?>"
                                        data-mid="<?php echo strtolower(htmlspecialchars($f['membership_id'])); ?>"
                                        data-designation="<?php echo strtolower(htmlspecialchars($f['designation'])); ?>">
                                        <td>
                                            <div class="d-flex py-1 align-items-center">
                                                <span
                                                    class="avatar avatar-sm me-2 d-inline-flex align-items-center justify-content-center"
                                                    style="background: #e9ecef;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#6c757d"
                                                        width="18" height="18">
                                                        <circle cx="12" cy="8" r="4" />
                                                        <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7" />
                                                    </svg>
                                                </span>
                                                <div class="flex-fill">
                                                    <a href="<?php echo url('admin/members/fees/' . $f['id']); ?>"
                                                        class="text-reset text-decoration-none fw-semibold"><?php echo e($f['name']); ?></a>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?php echo e($f['membership_id']); ?></td>
                                        <td><?php echo e($f['designation']); ?></td>
                                        <td class="text-center">
                                            <?php echo $f['monthly_fee'] > 0 ? '₹' . number_format($f['monthly_fee'], 2) : '<span class="text-muted">—</span>'; ?>
                                        </td>
                                        <td
                                            class="text-center fw-semibold <?php echo $f['total_due'] > 0 ? 'text-danger' : 'text-muted'; ?>">
                                            <?php echo $f['total_due'] > 0 ? '₹' . number_format($f['total_due'], 2) : '—'; ?>
                                        </td>
                                        <td class="text-center">
                                            <a href="<?php echo url('admin/members/fees/' . $f['id']); ?>"
                                                class="btn btn-outline-primary btn-sm">View</a>
                                        </td>
                                    </tr>
                                <?php endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
        .btn-ghost-secondary {
            color: #667382;
            background: transparent;
            border: none;
        }

        .btn-ghost-secondary:hover {
            color: #e53e3e;
            background: rgba(229, 62, 62, .06);
        }

        tr.d-filter-hide {
            display: none !important;
        }

        #fees-table tbody tr {
            transition: opacity .15s ease;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var search = document.getElementById('mf-search');
            var designation = document.getElementById('mf-designation');
            var clearBtn = document.getElementById('mf-clear');
            var countEl = document.getElementById('mf-count');
            var rows = document.querySelectorAll('#fees-table tbody tr[data-name]');
            var totalRows = rows.length;
            var timer = null;

            if (totalRows === 0) return;

            function applyFilters() {
                var q = search.value.toLowerCase().trim();
                var d = designation.value;
                var visible = 0;
                var hasFilter = q || d;

                rows.forEach(function (row) {
                    var show = true;

                    if (q) {
                        var name = row.getAttribute('data-name') || '';
                        var mid = row.getAttribute('data-mid') || '';
                        if (name.indexOf(q) === -1 && mid.indexOf(q) === -1) show = false;
                    }

                    if (show && d) {
                        if (row.getAttribute('data-designation') !== d) show = false;
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

            search.addEventListener('input', function () {
                clearTimeout(timer);
                timer = setTimeout(applyFilters, 200);
            });

            designation.addEventListener('change', applyFilters);

            clearBtn.addEventListener('click', function () {
                search.value = '';
                designation.value = '';
                applyFilters();
                search.focus();
            });
        });
    </script>

    <?php require_once 'app/views/admin/layouts/footer.php'; ?>
</div>