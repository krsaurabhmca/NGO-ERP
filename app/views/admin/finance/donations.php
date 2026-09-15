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
                        <li class="breadcrumb-item active small" aria-current="page">All Donations</li>
                    </ol>
                    <h2 class="page-title fw-bold fs-1">All Donations</h2>
                </div>
                <div class="col-auto ms-auto d-print-none d-flex gap-2">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-add-donation">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                        Add Donation
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <div class="row g-3 mb-3">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body text-center py-3">
                            <div class="text-muted small">Completed</div>
                            <div class="fw-bold fs-3 text-success">₹<?php echo number_format($totalCompleted, 2); ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body text-center py-3">
                            <div class="text-muted small">Pending</div>
                            <div class="fw-bold fs-3 text-warning">₹<?php echo number_format($totalPending, 2); ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body text-center py-3">
                            <div class="text-muted small">Failed</div>
                            <div class="fw-bold fs-3 text-danger">₹<?php echo number_format($totalFailed, 2); ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body text-center py-3">
                            <div class="text-muted small">Total Donors</div>
                            <div class="fw-bold fs-3"><?php echo $countCompleted; ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body py-2 border-bottom">
                    <div class="row g-2 align-items-center">
                        <div class="col">
                            <div class="input-icon input-icon-sm">
                                <span class="input-icon-addon"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg></span>
                                <input type="text" id="df-search" class="form-control form-control-sm" placeholder="Search donor...">
                            </div>
                        </div>
                        <div class="col-auto">
                            <select id="df-status" class="form-select form-select-sm" style="min-width:120px">
                                <option value="">All Status</option>
                                <option value="completed">Completed</option>
                                <option value="pending">Pending</option>
                                <option value="failed">Failed</option>
                            </select>
                        </div>
                        <div class="col-auto">
                            <select id="df-method" class="form-select form-select-sm" style="min-width:130px">
                                <option value="">All Methods</option>
                                <option value="razorpay">Online</option>
                                <option value="offline">Cash</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="upi">UPI</option>
                                <option value="card">Card</option>
                            </select>
                        </div>
                        <div class="col-auto d-flex align-items-center gap-1">
                            <input type="date" id="df-from" class="form-control form-control-sm" style="width:130px" title="From date">
                            <span class="text-muted small">–</span>
                            <input type="date" id="df-to" class="form-control form-control-sm" style="width:130px" title="To date">
                        </div>
                        <div class="col-auto d-flex align-items-center gap-2">
                            <button type="button" id="df-clear" class="btn btn-sm btn-ghost-secondary d-none" title="Clear filters">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 6l-12 12" /><path d="M6 6l12 12" /></svg>
                                Clear
                            </button>
                            <span id="df-count" class="text-muted small d-none"></span>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table card-table table-vcenter text-nowrap datatable" id="donations-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Donor</th>
                                <th>Contact</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th class="w-1">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($donations)): ?>
                                <?php $i = 1; foreach ($donations as $d): ?>
                                    <tr data-name="<?php echo strtolower(htmlspecialchars($d->donor_name)); ?>" data-email="<?php echo strtolower(htmlspecialchars($d->donor_email)); ?>" data-phone="<?php echo htmlspecialchars($d->donor_phone); ?>" data-status="<?php echo $d->status; ?>" data-method="<?php echo $d->payment_method ?: 'offline'; ?>" data-date="<?php echo date('Y-m-d', strtotime($d->created_at)); ?>">
                                        <td><?php echo $i++; ?></td>
                                        <td>
                                            <div class="fw-semibold"><?php echo htmlspecialchars($d->donor_name); ?></div>
                                        </td>
                                        <td>
                                            <div class="small"><?php echo htmlspecialchars($d->donor_email); ?></div>
                                            <div class="small text-muted"><?php echo htmlspecialchars($d->donor_phone); ?></div>
                                        </td>
                                        <td class="fw-bold" data-sort-value="<?php echo $d->amount; ?>">₹<?php echo number_format($d->amount, 2); ?></td>
                                        <?php
                                            $methodDisplay = [
                                                'razorpay' => ['name' => 'Online', 'color' => 'blue'],
                                                'offline' => ['name' => 'Cash', 'color' => 'teal'],
                                                'bank_transfer' => ['name' => 'Bank Transfer', 'color' => 'orange'],
                                            ];
                                            $pm = $d->payment_method ?: 'offline';
                                            $md = $methodDisplay[$pm] ?? ['name' => ucfirst($pm), 'color' => 'secondary'];
                                        ?>
                                        <td data-sort-value="<?php echo $pm; ?>">
                                            <span class="badge bg-<?php echo $md['color']; ?>-lt text-<?php echo $md['color']; ?>">
                                                <?php echo $md['name']; ?>
                                            </span>
                                        </td>
                                        <td data-sort-value="<?php echo $d->status; ?>">
                                            <?php $statusColors = ['completed' => 'bg-success text-white', 'pending' => 'bg-warning text-white', 'failed' => 'bg-danger text-white']; ?>
                                            <span class="badge <?php echo $statusColors[$d->status] ?? 'bg-secondary'; ?>"><?php echo ucfirst($d->status); ?></span>
                                        </td>
                                        <td class="text-muted small" data-sort-value="<?php echo strtotime($d->created_at); ?>"><?php echo date('d M Y, h:i A', strtotime($d->created_at)); ?></td>
                                        <td>
                                            <button type="button" class="btn btn-icon btn-warning btn-sm" title="Update Status" data-bs-toggle="tooltip" onclick="populateUpdateStatus('<?php echo ($d->uuid ?? $d->id); ?>', '<?php echo $d->status; ?>')">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" /><path d="M13.5 6.5l4 4" /></svg>
                                            </button>
                                            <?php if ($d->status === 'completed'): ?>
                                                <a href="<?php echo url('admin/finance/donations/receipt/' . ($d->uuid ?? $d->id)); ?>" target="_blank" class="btn btn-icon btn-success btn-sm" title="Receipt" data-bs-toggle="tooltip">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M12 11v5" /><path d="M9 14l3 3l3 -3" /></svg>
                                                </a>
                                                <?php if (!empty($d->donor_email)): ?>
                                                    <button type="button" class="btn btn-icon btn-info btn-sm btn-mail-receipt" data-id="<?php echo ($d->uuid ?? $d->id); ?>" data-email="<?php echo htmlspecialchars($d->donor_email); ?>" title="Mail Receipt" data-bs-toggle="tooltip">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z" /><path d="M3 7l9 6l9 -6" /></svg>
                                                    </button>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                            <a href="javascript:void(0)" onclick="confirmDelete('<?php echo url('admin/finance/delete-donation/' . ($d->uuid ?? $d->id)); ?>', 'admin/finance/delete-donation/<?php echo ($d->uuid ?? $d->id); ?>', '<?php echo csrf_token('admin/finance/delete-donation/' . ($d->uuid ?? $d->id)); ?>')" class="btn btn-icon btn-danger btn-sm" title="Delete" data-bs-toggle="tooltip">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="8" class="text-center py-4 text-muted">No donations found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal modal-blur fade" id="modal-add-donation" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-header">
                <h5 class="modal-title">Add Donation</h5>
            </div>
            <div class="modal-body">
                <form id="form-add-donation" method="POST">
                    <div class="text-center mb-3">
                        <label class="form-label small fw-semibold">Amount <span class="text-danger">*</span></label>
                        <div class="quick-amounts mb-2 d-flex flex-wrap justify-content-center gap-1">
                            <button type="button" class="btn btn-outline-primary btn-sm quick-amt" data-value="100">₹100</button>
                            <button type="button" class="btn btn-outline-primary btn-sm quick-amt" data-value="250">₹250</button>
                            <button type="button" class="btn btn-outline-primary btn-sm quick-amt" data-value="500">₹500</button>
                            <button type="button" class="btn btn-outline-primary btn-sm quick-amt" data-value="1000">₹1,000</button>
                            <button type="button" class="btn btn-outline-primary btn-sm quick-amt" data-value="2000">₹2,000</button>
                            <button type="button" class="btn btn-outline-primary btn-sm quick-amt" data-value="5000">₹5,000</button>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text">₹</span>
                            <input type="number" name="amount" class="form-control text-center" required min="1" step="0.01" placeholder="Enter amount">
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-12">
                            <label class="form-label small fw-semibold">Select Existing Donor (Optional)</label>
                            <input type="hidden" name="donor_id" id="hidden_donor_id">
                            <select id="select-registered-donor" class="form-select">
                                <option value="">-- Manual Entry (Type Below) --</option>
                                <?php if (!empty($registeredDonors)): ?>
                                    <?php foreach ($registeredDonors as $d): ?>
                                        <option value="<?php echo $d->id; ?>" 
                                            data-name="<?php echo htmlspecialchars($d->name); ?>"
                                            data-email="<?php echo htmlspecialchars($d->email); ?>"
                                            data-phone="<?php echo htmlspecialchars($d->phone); ?>"
                                            data-address="<?php echo htmlspecialchars($d->address); ?>"
                                        >
                                            <?php echo htmlspecialchars($d->name); ?> (<?php echo htmlspecialchars($d->phone); ?>)
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="donor_name" class="form-control" required placeholder="Your name">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Email <span class="text-danger">*</span></label>
                            <input type="email" name="donor_email" class="form-control" required placeholder="you@example.com">
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Phone <span class="text-danger">*</span></label>
                            <input type="tel" name="donor_phone" class="form-control" required placeholder="+91 98765 43210">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">PAN Number</label>
                            <input type="text" name="donor_pan" class="form-control" placeholder="e.g. ABCDE1234F" maxlength="10" style="text-transform:uppercase;">
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Address</label>
                            <input type="text" name="donor_address" class="form-control" placeholder="House / Flat / Street">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">City</label>
                            <input type="text" name="donor_city" class="form-control" placeholder="City">
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">State</label>
                            <input type="text" name="donor_state" class="form-control" placeholder="State">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Pincode</label>
                            <input type="text" name="donor_pincode" class="form-control" placeholder="e.g. 110001" maxlength="6">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Message (Optional)</label>
                        <textarea name="message" class="form-control" rows="3" placeholder="Any message or dedication..."></textarea>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Payment Method</label>
                            <select name="payment_method" class="form-select">
                                <option value="offline">Cash</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="card">Card</option>
                                <option value="upi">UPI</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Status</label>
                            <select name="status" class="form-select">
                                <option value="completed">Completed</option>
                                <option value="pending">Pending</option>
                                <option value="failed">Failed</option>
                            </select>
                        </div>
                    </div>
                </form>
                <style>
                .quick-amt.active { background-color: var(--bs-primary, #206bc4) !important; color: #fff !important; border-color: var(--bs-primary, #206bc4) !important; }
                .quick-amt { min-width: 70px; font-weight: 600; transition: all 0.15s ease; }
                </style>
                <div id="add-donation-msg" class="mt-2"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary ms-auto" id="btn-add-donation-submit">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                    Add Donation
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Quick Amounts
    document.querySelectorAll('.quick-amt').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.quick-amt').forEach(function(b) { b.classList.remove('active'); });
            this.classList.add('active');
            var input = document.querySelector('#form-add-donation [name="amount"]');
            input.value = this.getAttribute('data-value');
            input.focus();
        });
    });
    
    var amountInput = document.querySelector('#form-add-donation [name="amount"]');
    if (amountInput) {
        amountInput.addEventListener('input', function() {
            document.querySelectorAll('.quick-amt').forEach(function(b) { b.classList.remove('active'); });
        });
    }

    document.querySelectorAll('.btn-mail-receipt').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.getElementById('modal-mail-receipt-id').value = this.getAttribute('data-id');
            document.getElementById('modal-mail-receipt-email').textContent = this.getAttribute('data-email');
            var modal = new bootstrap.Modal(document.getElementById('modal-mail-receipt'));
            modal.show();
        });
    });
    document.getElementById('btn-mail-receipt-confirm').addEventListener('click', function() {
        var id = document.getElementById('modal-mail-receipt-id').value;
        var btn = this;
        var msgBox = document.getElementById('mail-receipt-msg');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span> Sending...';
        msgBox.innerHTML = '';
        fetch('<?php echo url('admin/finance/donations/send-receipt/'); ?>' + id, { method: 'POST' })
        .then(function(r) { return r.json(); })
        .then(function(res) {
            if (res.status === 'success') {
                msgBox.innerHTML = '<div class="alert alert-success py-2 mb-0">' + res.message + '</div>';
                var origBtn = document.querySelector('.btn-mail-receipt[data-id="' + id + '"]');
                if (origBtn) {
                    origBtn.title = 'Sent';
                    origBtn.classList.remove('btn-info');
                    origBtn.classList.add('btn-success');
                    origBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>';
                }
            } else {
                msgBox.innerHTML = '<div class="alert alert-danger py-2 mb-0">' + (res.message || 'Failed.') + '</div>';
                btn.disabled = false;
                btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg> Send Receipt';
            }
        })
        .catch(function() {
            msgBox.innerHTML = '<div class="alert alert-danger py-2 mb-0">Something went wrong.</div>';
            btn.disabled = false;
            btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg> Send Receipt';
        });
    });
    document.getElementById('modal-mail-receipt').addEventListener('hidden.bs.modal', function() {
        document.getElementById('mail-receipt-msg').innerHTML = '';
        var btn = document.getElementById('btn-mail-receipt-confirm');
        btn.disabled = false;
        btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z" /><path d="M3 7l9 6l9 -6" /></svg> Send Receipt';
    });

    document.getElementById('btn-add-donation-submit').addEventListener('click', function() {
        var form = document.getElementById('form-add-donation');
        var msgBox = document.getElementById('add-donation-msg');
        var btn = this;

        if (!form.checkValidity()) { form.reportValidity(); return; }

        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span> Adding...';

        var fd = new FormData(form);
        fetch('<?php echo url('admin/finance/donations/store'); ?>', {
            method: 'POST', body: fd
        })
        .then(function(r) { return r.json(); })
        .then(function(res) {
            if (res.status === 'success') {
                msgBox.innerHTML = '<div class="alert alert-success py-2 mb-0">' + res.message + '</div>';
                setTimeout(function() { location.reload(); }, 1000);
            } else {
                msgBox.innerHTML = '<div class="alert alert-danger py-2 mb-0">' + (res.message || 'Failed.') + '</div>';
                btn.disabled = false;
                btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg> Add Donation';
            }
        })
        .catch(function() {
            msgBox.innerHTML = '<div class="alert alert-danger py-2 mb-0">Something went wrong.</div>';
            btn.disabled = false;
            btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg> Add Donation';
        });
    });

    // Auto-fill donor details from select
    var donorSelect = document.getElementById('select-registered-donor');
    if (donorSelect) {
        donorSelect.addEventListener('change', function() {
            var selectedOption = this.options[this.selectedIndex];
            var form = document.getElementById('form-add-donation');
            
            document.getElementById('hidden_donor_id').value = this.value;

            if (this.value) {
                // Populate fields
                form.elements['donor_name'].value = selectedOption.getAttribute('data-name');
                form.elements['donor_email'].value = selectedOption.getAttribute('data-email');
                form.elements['donor_phone'].value = selectedOption.getAttribute('data-phone');
                form.elements['donor_address'].value = selectedOption.getAttribute('data-address');
            } else {
                // Clear fields for manual entry
                form.elements['donor_name'].value = '';
                form.elements['donor_email'].value = '';
                form.elements['donor_phone'].value = '';
                form.elements['donor_address'].value = '';
            }
        });
    }

    var modal = document.getElementById('modal-add-donation');
    modal.addEventListener('hidden.bs.modal', function() {
        document.getElementById('form-add-donation').reset();
        document.getElementById('add-donation-msg').innerHTML = '';
        var btn = document.getElementById('btn-add-donation-submit');
        btn.disabled = false;
        btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg> Add Donation';
    });
});
</script>

<div class="modal modal-blur fade" id="modal-mail-receipt" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-header">
                <h5 class="modal-title">Send Receipt</h5>
            </div>
            <div class="modal-body">
                <p class="mb-2">Send receipt to donor's email?</p>
                <p class="fw-bold text-primary mb-3" id="modal-mail-receipt-email"></p>
                <input type="hidden" id="modal-mail-receipt-id">
                <div id="mail-receipt-msg" class="mt-2"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary ms-auto" id="btn-mail-receipt-confirm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z" /><path d="M3 7l9 6l9 -6" /></svg>
                    Send Receipt
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal modal-blur fade" id="modal-update-status" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="form-update-status" method="POST" action="">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-header">
                    <h5 class="modal-title">Update Status</h5>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">New Status</label>
                        <select name="status" id="update-status-select" class="form-select" required>
                            <option value="pending">Pending</option>
                            <option value="completed">Completed</option>
                            <option value="failed">Failed</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary ms-auto">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function populateUpdateStatus(id, currentStatus) {
    document.getElementById('form-update-status').action = '<?php echo url('admin/finance/donations/update-status/'); ?>' + id;
    document.getElementById('update-status-select').value = currentStatus;
}
</script>

<style>
@media (max-width: 575.98px) {
    .card-table td:last-child .btn-group .btn { font-size: 0.55rem; padding: 0.1rem 0.25rem; }
    .card-table td:last-child .btn-group .btn svg { width: 0.75rem; height: 0.75rem; }
}
.btn-ghost-secondary { color: #667382; background: transparent; border: none; }
.btn-ghost-secondary:hover { color: #e53e3e; background: rgba(229,62,62,.06); }
tr.d-filter-hide { display: none !important; }
#donations-table tbody tr { transition: opacity .15s ease; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var search = document.getElementById('df-search');
    var status = document.getElementById('df-status');
    var method = document.getElementById('df-method');
    var dateFrom = document.getElementById('df-from');
    var dateTo = document.getElementById('df-to');
    var clearBtn = document.getElementById('df-clear');
    var countEl = document.getElementById('df-count');
    var rows = document.querySelectorAll('#donations-table tbody tr[data-status]');
    var totalRows = rows.length;
    var timer = null;

    function applyFilters() {
        var q = search.value.toLowerCase().trim();
        var s = status.value;
        var m = method.value;
        var df = dateFrom.value;
        var dt = dateTo.value;
        var visible = 0;
        var hasFilter = q || s || m || df || dt;

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

            if (show && m) {
                if (row.getAttribute('data-method') !== m) show = false;
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

        // Update counter
        if (hasFilter) {
            countEl.textContent = visible + ' of ' + totalRows;
            countEl.classList.remove('d-none');
            clearBtn.classList.remove('d-none');
        } else {
            countEl.classList.add('d-none');
            clearBtn.classList.add('d-none');
        }
    }

    // Debounced search
    search.addEventListener('input', function() {
        clearTimeout(timer);
        timer = setTimeout(applyFilters, 200);
    });

    // Instant on dropdowns / dates
    status.addEventListener('change', applyFilters);
    method.addEventListener('change', applyFilters);
    dateFrom.addEventListener('change', applyFilters);
    dateTo.addEventListener('change', applyFilters);

    // Clear
    clearBtn.addEventListener('click', function() {
        search.value = '';
        status.value = '';
        method.value = '';
        dateFrom.value = '';
        dateTo.value = '';
        applyFilters();
        search.focus();
    });
});
</script>
<?php require_once 'app/views/admin/layouts/footer.php'; ?>
