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
                        <li class="breadcrumb-item small"><a href="<?php echo url('admin/finance'); ?>">Finance</a></li>
                        <li class="breadcrumb-item active small" aria-current="page">Expenses</li>
                    </ol>
                    <h2 class="page-title fw-bold fs-1">Expenses</h2>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <button type="button" class="btn btn-outline-primary me-2" data-bs-toggle="modal"
                        data-bs-target="#modal-add-category">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
                            stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 5l0 14" />
                            <path d="M5 12l14 0" />
                        </svg>
                        Add Category
                    </button>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#modal-add-expense">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
                            stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 5l0 14" />
                            <path d="M5 12l14 0" />
                        </svg>
                        Add Expense
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <div class="card mb-3">
                <div class="card-body text-center py-3">
                    <div class="text-muted small">Total Expenses</div>
                    <div class="fw-bold fs-2 text-danger">₹<?php echo number_format($totalExpenses, 2); ?></div>
                </div>
            </div>

            <div class="card">
                <div class="table-responsive">
                    <table class="table card-table table-vcenter text-nowrap datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Description</th>
                                <th>Category</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Payment Method</th>
                                <th>Notes</th>
                                <th class="w-1">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($expenses)): ?>
                                <?php $i = 1;
                                foreach ($expenses as $e): ?>
                                    <tr>
                                        <td><?php echo $i++; ?></td>
                                        <td class="fw-semibold"><?php echo htmlspecialchars($e->description); ?></td>
                                        <td><span class="badge bg-blue-lt"><?php echo htmlspecialchars($e->category); ?></span>
                                        </td>
                                        <td class="fw-bold text-danger">
                                            <?php echo '₹'; ?>        <?php echo number_format($e->amount, 2); ?></td>
                                        <td class="text-muted small"><?php echo date('d M Y', strtotime($e->expense_date)); ?>
                                        </td>
                                        <td><span
                                                class="badge bg-secondary-lt"><?php echo ucfirst($e->payment_method); ?></span>
                                        </td>
                                        <td class="small text-muted"><?php echo htmlspecialchars($e->notes ?: '-'); ?></td>
                                        <td>
                                            <a href="javascript:void(0)"
                                                onclick="viewExpense('<?php echo ($e->uuid ?? $e->id); ?>')"
                                                class="btn btn-icon btn-cyan btn-sm" title="View">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                                    stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                    <path
                                                        d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                                </svg>
                                            </a>
                                            <a href="javascript:void(0)"
                                                onclick="editExpense('<?php echo ($e->uuid ?? $e->id); ?>')"
                                                class="btn btn-icon btn-yellow btn-sm" title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                                    stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" />
                                                    <path d="M13.5 6.5l4 4" />
                                                </svg>
                                            </a>
                                            <a href="javascript:void(0)"
                                                onclick="confirmDelete('<?php echo url('admin/finance/delete-expense/' . ($e->uuid ?? $e->id)); ?>', 'admin/finance/delete-expense/<?php echo ($e->uuid ?? $e->id); ?>', '<?php echo csrf_token('admin/finance/delete-expense/' . ($e->uuid ?? $e->id)); ?>')"
                                                class="btn btn-icon btn-danger btn-sm" title="Delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                                    stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M4 7l16 0" />
                                                    <path d="M10 11l0 6" />
                                                    <path d="M14 11l0 6" />
                                                    <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                    <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">No expenses recorded.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Category Modal -->
<div class="modal modal-blur fade" id="modal-add-category" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="<?php echo url('admin/finance/expenses/store-category'); ?>" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Manage Expense Categories</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-4">
                        <label class="form-label">Add New Category</label>
                        <div class="input-group">
                            <input type="text" name="name" class="form-control" required
                                placeholder="e.g. Food & Supplies">
                            <button type="submit" class="btn btn-primary">Add</button>
                        </div>
                    </div>
                    <div class="border-top pt-3">
                        <label class="form-label mb-2">Existing Categories</label>
                        <?php if (!empty($categories)): ?>
                            <div class="d-flex flex-wrap gap-1">
                                <?php foreach ($categories as $cat): ?>
                                    <span
                                        class="badge bg-secondary-lt text-dark px-2 py-2 d-inline-flex align-items-center gap-2">
                                        <?php echo htmlspecialchars($cat->name); ?>
                                        <a href="javascript:void(0)"
                                            onclick="confirmDelete('<?php echo url('admin/finance/expenses/delete-category/' . ($cat->uuid ?? $cat->id)); ?>', 'admin/finance/expenses/delete-category/<?php echo ($cat->uuid ?? $cat->id); ?>', '<?php echo csrf_token('admin/finance/expenses/delete-category/' . ($cat->uuid ?? $cat->id)); ?>')"
                                            class="text-danger text-decoration-none" style="line-height: 1;">&times;</a>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-muted small mb-0">No categories added yet.</p>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Expense Modal -->
<div class="modal modal-blur fade" id="modal-add-expense" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-header">
                <h5 class="modal-title">Add Expense</h5>
            </div>
            <div class="modal-body">
                <form id="form-add-expense" method="POST" enctype="multipart/form-data">
                    <div class="row g-3 mb-3">
                        <div class="col-md-8">
                            <label class="form-label required">Description</label>
                            <input type="text" name="description" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label required">Amount (₹)</label>
                            <input type="number" name="amount" class="form-control" required min="1" step="0.01">
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Category</label>
                            <select name="category" class="form-select">
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo htmlspecialchars($cat->name); ?>">
                                        <?php echo htmlspecialchars($cat->name); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label required">Date</label>
                            <input type="date" name="expense_date" class="form-control"
                                value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Payment Method</label>
                            <select name="payment_method" class="form-select">
                                <option value="cash">Cash</option>
                                <option value="bank">Bank Transfer</option>
                                <option value="cheque">Cheque</option>
                                <option value="card">Card</option>
                                <option value="online">Online</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Receipt / Document</label>
                        <input type="file" name="receipt" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                        <div class="form-text text-muted small">Max 2MB. Allowed: PDF, JPG, PNG</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="2"></textarea>
                    </div>
                </form>
                <div id="add-expense-msg" class="mt-2"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary ms-auto" id="btn-add-expense-submit">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 5l0 14" />
                        <path d="M5 12l14 0" />
                    </svg>
                    Add Expense
                </button>
            </div>
        </div>
    </div>
</div>

<!-- View Expense Modal -->
<div class="modal modal-blur fade" id="modal-view-expense" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-header">
                <h5 class="modal-title">Expense Details</h5>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-sm mb-0">
                    <tr>
                        <th class="w-25">Description</th>
                        <td id="view-description"></td>
                    </tr>
                    <tr>
                        <th>Category</th>
                        <td id="view-category"></td>
                    </tr>
                    <tr>
                        <th>Amount</th>
                        <td id="view-amount" class="fw-bold text-danger"></td>
                    </tr>
                    <tr>
                        <th>Date</th>
                        <td id="view-date"></td>
                    </tr>
                    <tr>
                        <th>Payment Method</th>
                        <td id="view-payment-method"></td>
                    </tr>
                    <tr>
                        <th>Notes</th>
                        <td id="view-notes"></td>
                    </tr>
                    <tr>
                        <th>Receipt</th>
                        <td id="view-receipt"></td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Expense Modal -->
<div class="modal modal-blur fade" id="modal-edit-expense" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-header">
                <h5 class="modal-title">Edit Expense</h5>
            </div>
            <div class="modal-body">
                <form id="form-edit-expense" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="edit-id">
                    <div class="row g-3 mb-3">
                        <div class="col-md-8">
                            <label class="form-label required">Description</label>
                            <input type="text" name="description" id="edit-description" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label required">Amount (₹)</label>
                            <input type="number" name="amount" id="edit-amount" class="form-control" required min="1"
                                step="0.01">
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Category</label>
                            <select name="category" id="edit-category" class="form-select">
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo htmlspecialchars($cat->name); ?>">
                                        <?php echo htmlspecialchars($cat->name); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label required">Date</label>
                            <input type="date" name="expense_date" id="edit-expense-date" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Payment Method</label>
                            <select name="payment_method" id="edit-payment-method" class="form-select">
                                <option value="cash">Cash</option>
                                <option value="bank">Bank Transfer</option>
                                <option value="cheque">Cheque</option>
                                <option value="card">Card</option>
                                <option value="online">Online</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Change Receipt / Document</label>
                        <input type="file" name="receipt" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                        <div class="form-text text-muted small">Max 2MB. Leave empty to keep existing.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" id="edit-notes" class="form-control" rows="2"></textarea>
                    </div>
                </form>
                <div id="edit-expense-msg" class="mt-2"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary ms-auto" id="btn-edit-expense-submit">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                        <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                        <path d="M14 4l0 4l-6 0l0 -4" />
                    </svg>
                    Save Changes
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function viewExpense(id) {
        fetch('<?php echo url('admin/finance/expenses/view'); ?>' + '/' + id)
            .then(function (r) { return r.json(); })
            .then(function (res) {
                if (res.status === 'success') {
                    var d = res.data;
                    document.getElementById('view-description').textContent = d.description;
                    document.getElementById('view-category').innerHTML = '<span class="badge bg-blue-lt">' + (d.category || '-') + '</span>';
                    document.getElementById('view-amount').textContent = '₹' + parseFloat(d.amount).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    document.getElementById('view-date').textContent = d.expense_date;
                    document.getElementById('view-payment-method').innerHTML = '<span class="badge bg-secondary-lt">' + (d.payment_method ? d.payment_method.charAt(0).toUpperCase() + d.payment_method.slice(1) : '-') + '</span>';
                    document.getElementById('view-notes').textContent = d.notes || '-';
                    var receiptCell = document.getElementById('view-receipt');
                    if (d.receipt) {
                        receiptCell.innerHTML = '<a href="<?php echo BASE_URL; ?>file.php?f=' + encodeURIComponent(d.receipt) + '" target="_blank" class="btn btn-sm btn-outline-primary"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M12 11v5" /><path d="M9 14l3 3l3 -3" /></svg> View Receipt</a>';
                    } else {
                        receiptCell.textContent = '-';
                    }
                    var modal = new bootstrap.Modal(document.getElementById('modal-view-expense'));
                    modal.show();
                } else {
                    alert('Failed to load expense details.');
                }
            })
            .catch(function () { alert('Something went wrong.'); });
    }

    function editExpense(id) {
        fetch('<?php echo url('admin/finance/expenses/view'); ?>' + '/' + id)
            .then(function (r) { return r.json(); })
            .then(function (res) {
                if (res.status === 'success') {
                    var d = res.data;
                    document.getElementById('edit-id').value = d.id;
                    document.getElementById('edit-description').value = d.description;
                    document.getElementById('edit-amount').value = d.amount;
                    document.getElementById('edit-category').value = d.category;
                    document.getElementById('edit-expense-date').value = d.expense_date;
                    document.getElementById('edit-payment-method').value = d.payment_method;
                    document.getElementById('edit-notes').value = d.notes || '';
                    var modal = new bootstrap.Modal(document.getElementById('modal-edit-expense'));
                    modal.show();
                } else {
                    alert('Failed to load expense details.');
                }
            })
            .catch(function () { alert('Something went wrong.'); });
    }

    document.addEventListener('DOMContentLoaded', function () {
        if (window.location.hash === '#modal-add-category') {
            var modal = new bootstrap.Modal(document.getElementById('modal-add-category'));
            modal.show();
        }
    });

    document.getElementById('btn-add-expense-submit').addEventListener('click', function () {
        var form = document.getElementById('form-add-expense');
        var msgBox = document.getElementById('add-expense-msg');
        var btn = this;

        if (!form.checkValidity()) { form.reportValidity(); return; }

        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span> Adding...';

        var fd = new FormData(form);
        fetch('<?php echo url('admin/finance/expenses/store'); ?>', {
            method: 'POST', body: fd
        })
            .then(function (r) { return r.json(); })
            .then(function (res) {
                if (res.status === 'success') {
                    msgBox.innerHTML = '<div class="alert alert-success py-2 mb-0">' + res.message + '</div>';
                    setTimeout(function () { window.location.href = '<?php echo url('admin/finance/expenses'); ?>'; }, 1000);
                } else {
                    msgBox.innerHTML = '<div class="alert alert-danger py-2 mb-0">' + (res.message || 'Failed.') + '</div>';
                    btn.disabled = false;
                    btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg> Add Expense';
                }
            })
            .catch(function () {
                msgBox.innerHTML = '<div class="alert alert-danger py-2 mb-0">Something went wrong.</div>';
                btn.disabled = false;
                btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg> Add Expense';
            });
    });

    var addExpModal = document.getElementById('modal-add-expense');
    addExpModal.addEventListener('hidden.bs.modal', function () {
        document.getElementById('form-add-expense').reset();
        document.getElementById('add-expense-msg').innerHTML = '';
        var btn = document.getElementById('btn-add-expense-submit');
        btn.disabled = false;
        btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg> Add Expense';
    });

    document.getElementById('btn-edit-expense-submit').addEventListener('click', function () {
        var form = document.getElementById('form-edit-expense');
        var msgBox = document.getElementById('edit-expense-msg');
        var btn = this;

        if (!form.checkValidity()) { form.reportValidity(); return; }

        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span> Saving...';

        var fd = new FormData(form);
        fetch('<?php echo url('admin/finance/expenses/update'); ?>', {
            method: 'POST', body: fd
        })
            .then(function (r) { return r.json(); })
            .then(function (res) {
                if (res.status === 'success') {
                    msgBox.innerHTML = '<div class="alert alert-success py-2 mb-0">' + res.message + '</div>';
                    setTimeout(function () { window.location.href = '<?php echo url('admin/finance/expenses'); ?>'; }, 1000);
                } else {
                    msgBox.innerHTML = '<div class="alert alert-danger py-2 mb-0">' + (res.message || 'Failed.') + '</div>';
                    btn.disabled = false;
                    btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" /><path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M14 4l0 4l-6 0l0 -4" /></svg> Save Changes';
                }
            })
            .catch(function () {
                msgBox.innerHTML = '<div class="alert alert-danger py-2 mb-0">Something went wrong.</div>';
                btn.disabled = false;
                btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" /><path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M14 4l0 4l-6 0l0 -4" /></svg> Save Changes';
            });
    });

    var editExpModal = document.getElementById('modal-edit-expense');
    editExpModal.addEventListener('hidden.bs.modal', function () {
        document.getElementById('form-edit-expense').reset();
        document.getElementById('edit-expense-msg').innerHTML = '';
        var btn = document.getElementById('btn-edit-expense-submit');
        btn.disabled = false;
        btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" /><path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M14 4l0 4l-6 0l0 -4" /></svg> Save Changes';
    });
</script>

<?php require_once 'app/views/admin/layouts/footer.php'; ?>