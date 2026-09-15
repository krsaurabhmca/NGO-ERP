<?php require_once 'app/views/admin/layouts/header.php'; ?>
<?php require_once 'app/views/admin/layouts/sidebar.php'; ?>

<div class="page-wrapper">
    <?php require_once 'app/views/admin/layouts/topbar.php'; ?>

    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title fw-bold fs-1">
                        Contact Manager
                    </h2>

                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table card-table table-vcenter text-nowrap datatable">
                    <thead>
                        <tr>
                            <th class="w-1">Status</th>
                            <th>Name</th>
                            <th>Subject</th>
                            <th>Date</th>
                            <th class="w-1">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($inquiries)): ?>
                            <?php foreach ($inquiries as $item): ?>
                                <tr>
                                    <td>
                                        <span class="badge <?php echo $item->status === 'unread' ? 'bg-red' : 'bg-success'; ?>-lt">
                                            <?php echo ucfirst($item->status); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="font-weight-medium"><?php echo htmlspecialchars($item->name); ?></div>
                                        <div class="text-muted small"><?php echo htmlspecialchars($item->email); ?></div>
                                    </td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 250px;"><?php echo htmlspecialchars($item->subject); ?></div>
                                    </td>
                                    <td><?php echo date('d M, Y h:i A', strtotime($item->created_at)); ?></td>
                                    <td>
                                        <button onclick="viewInquiry('<?php echo ($item->uuid ?? $item->id); ?>')" class="btn btn-icon btn-primary btn-sm" title="View Message">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                                        </button>
                                        <a href="mailto:<?php echo $item->email; ?>?subject=Re: <?php echo urlencode($item->subject ?? 'NGO Inquiry'); ?>" class="btn btn-icon btn-success btn-sm" title="Reply by Email">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z" /><path d="M3 7l9 6l9 -6" /></svg>
                                        </a>
                                        <a href="javascript:void(0)" onclick="confirmDelete('<?php echo url('admin/contacts/delete/' . ($item->uuid ?? $item->id)); ?>', 'admin/contacts/delete/<?php echo ($item->uuid ?? $item->id); ?>', '<?php echo csrf_token('admin/contacts/delete/' . ($item->uuid ?? $item->id)); ?>')" class="btn btn-icon btn-danger btn-sm" title="Delete">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">No inquiries found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

    <!-- View Inquiry Modal -->
    <div class="modal modal-blur fade" id="modal-view-inquiry" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Inquiry Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold">From</label>
                            <div id="view_name" class="fs-4 fw-bold"></div>
                            <div id="view_email" class="text-primary"></div>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <label class="form-label text-muted small fw-bold">Date Received</label>
                            <div id="view_date"></div>
                        </div>
                    </div>
                    <hr class="my-3">
                    <div class="mb-4">
                        <label class="form-label text-muted small fw-bold">Subject</label>
                        <div id="view_subject" class="fs-3 fw-bold text-dark"></div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label text-muted small fw-bold">Message</label>
                        <div id="view_message" class="p-3 bg-light rounded-3 border fs-4" style="white-space: pre-wrap; line-height: 1.6;"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link link-secondary me-auto" data-bs-dismiss="modal">Close</button>
                    <a href="#" id="view_reply_btn" class="btn btn-success px-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z" /><path d="M3 7l9 6l9 -6" /></svg>
                        Reply by Email
                    </a>
                </div>
            </div>
        </div>
    </div>

    <?php require_once 'app/views/admin/layouts/footer.php'; ?>
</div>

<script>
    function viewInquiry(id) {
        fetch('<?php echo url('admin/contacts/show/'); ?>' + id)
            .then(response => response.json())
            .then(result => {
                if (result.status === 'success') {
                    const data = result.data;
                    document.getElementById('view_name').innerText = data.name;
                    document.getElementById('view_email').innerText = data.email;
                    document.getElementById('view_subject').innerText = data.subject || '(No Subject)';
                    document.getElementById('view_message').innerText = data.message;
                    document.getElementById('view_date').innerText = new Date(data.created_at).toLocaleString();
                    
                    // Set reply button link
                    const replyBtn = document.getElementById('view_reply_btn');
                    const subject = encodeURIComponent('Re: ' + (data.subject || 'NGO Inquiry'));
                    replyBtn.href = `mailto:${data.email}?subject=${subject}`;

                    const modal = new bootstrap.Modal(document.getElementById('modal-view-inquiry'));
                    modal.show();

                    // Reload table if unread was marked as read on modal close
                    modal._element.addEventListener('hidden.bs.modal', function () {
                        if (data.status === 'unread') {
                            window.location.reload();
                        }
                    });
                }
            });
    }
</script>
