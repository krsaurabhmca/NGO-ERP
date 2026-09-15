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
                            <li class="breadcrumb-item"><a href="#">Settings</a></li>
                            <li class="breadcrumb-item active" aria-current="page"><a href="#">Templates</a></li>
                        </ol>
                    </div>
                    <h2 class="page-title fw-bold fs-1">
                        Email & Document Templates
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="row g-0">
                    <!-- Sidebar Navigation -->
                    <div class="col-12 col-md-3 border-end">
                        <div class="card-body">
                            <div class="list-group list-group-transparent mb-3">
                                <a href="#tab-certificate" class="list-group-item list-group-item-action d-flex align-items-center active" data-bs-toggle="tab">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 15m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /><path d="M13 17.5v4.5l2 -1l2 1v-4.5" /><path d="M10 19h-5a2 2 0 0 1 -2 -2v-10c0 -1.1 .9 -2 2 -2h14a2 2 0 0 1 2 2v3" /></svg>
                                    Certificate
                                </a>
                                <a href="#tab-offer-letter" class="list-group-item list-group-item-action d-flex align-items-center" data-bs-toggle="tab">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 15l2 2l4 -4" /></svg>
                                    Offer Letter
                                </a>

                                <a href="#tab-acceptance" class="list-group-item list-group-item-action d-flex align-items-center" data-bs-toggle="tab">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                                    Acceptance Email
                                </a>
                                <a href="#tab-rejection" class="list-group-item list-group-item-action d-flex align-items-center" data-bs-toggle="tab">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 6l-12 12" /><path d="M6 6l12 12" /></svg>
                                    Rejection Email
                                </a>
                                <a href="#tab-member-offer" class="list-group-item list-group-item-action d-flex align-items-center" data-bs-toggle="tab">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 12l2 2l4 -4" /><path d="M12 3a9 9 0 1 0 0 18a9 9 0 0 0 0 -18" /></svg>
                                    Member Offer Letter
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Content Section -->
                    <div class="col-12 col-md-9 d-flex flex-column">
                        <div class="card-body">
                            <div class="alert alert-info bg-info-lt border-0 small">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v4" /><path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.535a1.914 1.914 0 0 0 -3.274 0z" /><path d="M12 16h.01" /></svg>
                                Use placeholders: <code>{name}</code>, <code>{position}</code>, <code>{ngo_name}</code>, <code>{duration}</code>, <code>{stipend}</code>, <code>{date}</code>
                                <span class="mx-2">|</span>
                                Member offer: <code>{name}</code>, <code>{member_id}</code>, <code>{designation}</code>, <code>{join_date}</code>, <code>{password}</code>, <code>{login_url}</code>
                            </div>
                            <form action="<?php echo url('admin/settings/templates'); ?>" method="POST">
                                        <input type="hidden" name="_csrf_token" value="<?php echo csrf_token(); ?>">

                                <input type="hidden" name="active_tab" value="tab-certificate">
                                <div class="tab-content">
                                    <!-- Tab 1: Certificate -->
                                    <div class="tab-pane active show" id="tab-certificate">
                                        <h3 class="card-title mb-3">Certificate Template</h3>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Subject</label>
                                            <input type="text" name="template_certificate_subject" class="form-control" value="<?php echo $settings['template_certificate_subject'] ?? 'Certificate of Completion - {ngo_name}'; ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Body</label>
                                            <textarea name="template_certificate" class="form-control" rows="12" style="font-family:monospace;"><?php echo htmlspecialchars($settings['template_certificate'] ?? ''); ?></textarea>
                                        </div>
                                        <div class="pt-3 border-top d-flex justify-content-end gap-2">
                                            <button type="button" class="btn btn-outline-primary px-3 preview-btn" data-target="template_certificate">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                                                Preview
                                            </button>
                                            <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" /><path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M14 4l0 4l-6 0l0 -4" /></svg>
                                                Save
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Tab 2: Offer Letter -->
                                    <div class="tab-pane" id="tab-offer-letter">
                                        <h3 class="card-title mb-3">Offer Letter Template</h3>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Subject</label>
                                            <input type="text" name="template_offer_letter_subject" class="form-control" value="<?php echo $settings['template_offer_letter_subject'] ?? 'Offer Letter - {position} at {ngo_name}'; ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Body</label>
                                            <textarea name="template_offer_letter" class="form-control" rows="12" style="font-family:monospace;"><?php echo htmlspecialchars($settings['template_offer_letter'] ?? ''); ?></textarea>
                                        </div>
                                        <div class="pt-3 border-top d-flex justify-content-end gap-2">
                                            <button type="button" class="btn btn-outline-primary px-3 preview-btn" data-target="template_offer_letter">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                                                Preview
                                            </button>
                                            <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" /><path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M14 4l0 4l-6 0l0 -4" /></svg>
                                                Save
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Tab 3: Acceptance Email -->
                                    <div class="tab-pane" id="tab-acceptance">
                                        <h3 class="card-title mb-3">Acceptance Email</h3>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Subject</label>
                                            <input type="text" name="template_email_acceptance_subject" class="form-control" value="<?php echo $settings['template_email_acceptance_subject'] ?? 'Congratulations! Application Accepted - {ngo_name}'; ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Body</label>
                                            <textarea name="template_email_acceptance" class="form-control" rows="12" style="font-family:monospace;"><?php echo htmlspecialchars($settings['template_email_acceptance'] ?? ''); ?></textarea>
                                        </div>
                                        <div class="pt-3 border-top d-flex justify-content-end gap-2">
                                            <button type="button" class="btn btn-outline-primary px-3 preview-btn" data-target="template_email_acceptance">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                                                Preview
                                            </button>
                                            <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" /><path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M14 4l0 4l-6 0l0 -4" /></svg>
                                                Save
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Tab 5: Rejection Email -->
                                    <div class="tab-pane" id="tab-rejection">
                                        <h3 class="card-title mb-3">Rejection Email</h3>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Subject</label>
                                            <input type="text" name="template_email_rejection_subject" class="form-control" value="<?php echo $settings['template_email_rejection_subject'] ?? 'Application Status Update - {ngo_name}'; ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Body</label>
                                            <textarea name="template_email_rejection" class="form-control" rows="12" style="font-family:monospace;"><?php echo htmlspecialchars($settings['template_email_rejection'] ?? ''); ?></textarea>
                                        </div>
                                        <div class="pt-3 border-top d-flex justify-content-end gap-2">
                                            <button type="button" class="btn btn-outline-primary px-3 preview-btn" data-target="template_email_rejection">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                                                Preview
                                            </button>
                                            <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" /><path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M14 4l0 4l-6 0l0 -4" /></svg>
                                                Save
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Tab 6: Member Offer Letter -->
                                    <div class="tab-pane" id="tab-member-offer">
                                        <h3 class="card-title mb-3">Member Offer Letter Email</h3>
                                        <p class="text-muted small mb-3">This email is sent to members when their membership request is approved.</p>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Subject</label>
                                            <input type="text" name="template_member_offer_subject" class="form-control" value="<?php echo $settings['template_member_offer_subject'] ?? 'Welcome - Membership Offer Letter | {ngo_name}'; ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Body (HTML)</label>
                                            <textarea name="template_member_offer" id="template_member_offer" class="form-control" rows="12" style="font-family:monospace;"><?php echo htmlspecialchars($settings['template_member_offer'] ?? ''); ?></textarea>
                                        </div>
                                        <div class="pt-3 border-top d-flex justify-content-end gap-2">
                                            <button type="button" class="btn btn-outline-secondary px-3" onclick="loadMemberOfferDemo()">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5v14" /><path d="M5 12l14 0" /></svg>
                                                Load Demo
                                            </button>
                                            <button type="button" class="btn btn-outline-primary px-3 preview-btn" data-target="template_member_offer">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                                                Preview
                                            </button>
                                            <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" /><path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M14 4l0 4l-6 0l0 -4" /></svg>
                                                Save
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require_once 'app/views/admin/layouts/footer.php'; ?>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewModalLabel">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                    Template Preview
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" id="previewContent">
                <div class="text-center text-muted py-5">Loading preview...</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
var NGO_LOGO = '<?php echo !empty($orgSettings['ngo_logo']) ? file_url($orgSettings['ngo_logo']) : ''; ?>';
var NGO_SIGNATURE = '<?php echo !empty($orgSettings['ngo_signature']) ? file_url($orgSettings['ngo_signature']) : ''; ?>';

var memberSampleData = {
    '{name}': 'Rahul Sharma',
    '{member_id}': 'MEM-2026-0042',
    '{designation}': 'Life Member',
    '{join_date}': '15 June, 2026',
    '{ngo_name}': 'Hope Foundation',
    '{password}': 'a8b3k9x2',
    '{login_url}':                     '<?php echo url('auth'); ?>',
};

function loadMemberOfferDemo() {
    document.getElementById('template_member_offer').value =
        '<!DOCTYPE html>\n' +
        '<html>\n' +
        '<head><meta charset="UTF-8"></head>\n' +
        '<body style="font-family:Georgia,\'Times New Roman\',serif;color:#333;padding:40px;">\n' +
        '    <div style="text-align:center;margin-bottom:30px;">\n' +
        '        <h1 style="color:#c9a84c;font-size:24px;margin:0;">{ngo_name}</h1>\n' +
        '        <p style="color:#888;font-size:13px;margin:4px 0 0;">Empowering Communities, Transforming Lives</p>\n' +
        '        <hr style="border:0;border-top:2px solid #c9a84c;margin-top:15px;">\n' +
        '    </div>\n' +
        '    <p style="font-size:16px;"><strong>Date:</strong> {join_date}</p>\n' +
        '    <p style="font-size:16px;"><strong>Member ID:</strong> {member_id}</p>\n' +
        '    <br>\n' +
        '    <p style="font-size:16px;"><strong>To,</strong></p>\n' +
        '    <p style="font-size:16px;font-weight:bold;">{name}</p>\n' +
        '    <br>\n' +
        '    <p style="font-size:16px;"><strong>Subject: Welcome to {ngo_name} \u2013 Membership Offer Letter</strong></p>\n' +
        '    <br>\n' +
        '    <p style="font-size:15px;line-height:1.8;">Dear {name},</p>\n' +
        '    <p style="font-size:15px;line-height:1.8;">\n' +
        '        Congratulations! On behalf of <strong>{ngo_name}</strong>, we are delighted to welcome you as a valued member.\n' +
        '        Your dedication and commitment to our cause align perfectly with our mission of empowering communities\n' +
        '        and transforming lives.\n' +
        '    </p>\n' +
        '    <p style="font-size:15px;line-height:1.8;">\n' +
        '        Your membership has been officially registered with the following details:\n' +
        '    </p>\n' +
        '    <table style="font-size:15px;line-height:1.8;margin-left:20px;">\n' +
        '        <tr><td style="width:120px;"><strong>Member ID:</strong></td><td>{member_id}</td></tr>\n' +
        '        <tr><td><strong>Designation:</strong></td><td>{designation}</td></tr>\n' +
        '        <tr><td><strong>Join Date:</strong></td><td>{join_date}</td></tr>\n' +
        '    </table>\n' +
        '    <br>\n' +
        '    <p style="font-size:15px;line-height:1.8;">\n' +
        '        You can now access your member portal using the following credentials:\n' +
        '    </p>\n' +
        '    <table style="font-size:15px;line-height:1.8;margin-left:20px;">\n' +
        '        <tr><td style="width:120px;"><strong>Login URL:</strong></td><td><a href="{login_url}">{login_url}</a></td></tr>\n' +
        '        <tr><td><strong>Password:</strong></td><td>{password}</td></tr>\n' +
        '    </table>\n' +
        '    <p style="font-size:15px;line-height:1.8;margin-top:20px;">\n' +
        '        We encourage you to log in, update your profile, and stay connected with our upcoming events,\n' +
        '        projects, and initiatives. Together, we can make a meaningful difference.\n' +
        '    </p>\n' +
        '    <p style="font-size:15px;line-height:1.8;">\n' +
        '        Thank you for joining hands with us. We look forward to your active participation.\n' +
        '    </p>\n' +
        '    <br>\n' +
        '    <p style="font-size:15px;line-height:1.8;">Warm regards,</p>\n' +
        '    <p style="font-size:16px;font-weight:bold;color:#c9a84c;">{ngo_name}</p>\n' +
        '    <hr style="border:0;border-top:1px solid #ddd;margin-top:20px;">\n' +
        '    <p style="font-size:12px;color:#888;text-align:center;">\n' +
        '        This is a computer-generated document. No signature is required.<br>\n' +
        '        {ngo_name} | Empowering Communities, Transforming Lives\n' +
        '    </p>\n' +
        '</body>\n' +
        '</html>';
}

document.addEventListener("DOMContentLoaded", function() {
    const hash = window.location.hash;
    if (hash) {
        const triggerEl = document.querySelector(`.list-group-item[href="${hash}"]`);
        if (triggerEl) {
            bootstrap.Tab.getInstance(triggerEl)?.show() || new bootstrap.Tab(triggerEl).show();
        }
    }
    document.querySelectorAll('.list-group-item[data-bs-toggle="tab"]').forEach(function(trigger) {
        trigger.addEventListener('shown.bs.tab', function(e) {
            document.querySelector('input[name="active_tab"]').value = e.target.getAttribute('href').replace('#', '');
            window.location.hash = e.target.getAttribute('href');
        });
    });

    const sampleData = {
        '{name}': 'John Doe',
        '{position}': 'Web Development Intern',
        '{ngo_name}': 'Hope Foundation',
        '{duration}': '3 months',
        '{stipend}': '\u20B95,000/month',
        '{date}': 'June 15, 2026'
    };

    function fillPlaceholders(content) {
        Object.keys(sampleData).forEach(function(placeholder) {
            content = content.split(placeholder).join(sampleData[placeholder]);
        });
        return content;
    }

    function certificatePreview(bodyContent) {
        var content = fillPlaceholders(bodyContent);
        var lines = content.split('\n').filter(function(l) { return l.trim(); });
        var logoBlock = NGO_LOGO
            ? '<img src="' + NGO_LOGO + '" style="position:absolute;left:137.5mm;top:22mm;width:22mm;height:22mm;object-fit:contain;">'
            : '';
        var sigBlock = NGO_SIGNATURE
            ? '<img src="' + NGO_SIGNATURE + '" style="position:absolute;left:215mm;top:160mm;width:44mm;height:16mm;object-fit:contain;">'
            : '';
        var bodyHtml = '';
        var dateText = sampleData['{date}'];
        if (lines.length >= 6) {
            bodyHtml =
                '<div style="position:absolute;left:0;right:0;top:84mm;text-align:center;font-size:12pt;color:#555;font-weight:normal;">' + lines[0] + '</div>' +
                '<div style="position:absolute;left:0;right:0;top:105mm;text-align:center;font-size:22pt;color:#2c3e50;font-weight:bold;">' + lines[1] + '</div>' +
                '<div style="position:absolute;left:0;right:0;top:120mm;text-align:center;font-size:13pt;color:#555;">' + lines[2] + '</div>' +
                '<div style="position:absolute;left:0;right:0;top:132mm;text-align:center;font-size:13pt;color:#555;">' + lines[3] + '</div>' +
                '<div style="position:absolute;left:0;right:0;top:146mm;text-align:center;font-size:15pt;color:#c9a84c;font-weight:bold;">' + lines[4] + '</div>' +
                '<div style="position:absolute;left:0;right:0;top:160mm;text-align:center;font-size:13pt;color:#555;">' + lines[5] + '</div>';
            var m = content.match(/\d{1,2}\s+(January|February|March|April|May|June|July|August|September|October|November|December),?\s+\d{4}/i);
            if (m) dateText = m[0];
            var m2 = content.match(/\d{4}-\d{2}-\d{2}/);
            if (!m && m2) dateText = m2[0];
        } else {
            bodyHtml =
                '<div style="position:absolute;left:0;right:0;top:90mm;text-align:center;font-size:13pt;color:#555;padding:0 30mm;">' + content + '</div>';
        }
        return '' +
            '<div style="width:297mm;height:210mm;background:#fff;margin:0 auto;position:relative;font-family:Helvetica,Arial,sans-serif;overflow:hidden;box-shadow:0 0 20px rgba(0,0,0,0.15);">' +
            '  <div style="position:absolute;top:13mm;left:13mm;right:13mm;bottom:13mm;border:1.5pt solid #c9a84c;"></div>' +
            '  <div style="position:absolute;top:17mm;left:17mm;right:17mm;bottom:17mm;border:0.8pt solid #d4b85a;"></div>' +
            '  <div style="position:absolute;top:48mm;left:50%;transform:translateX(-50%);width:100mm;border-top:0.6pt solid #c9a84c;"></div>' +
            '  <div style="position:absolute;top:53mm;left:50%;transform:translateX(-50%);width:60mm;border-top:0.3pt solid #c9a84c;"></div>' +
            logoBlock +
            '  <div style="position:absolute;left:0;right:0;top:65mm;text-align:center;font-size:22pt;font-weight:bold;color:#2c3e50;">CERTIFICATE OF COMPLETION</div>' +
            bodyHtml +
            '  <div style="position:absolute;left:35mm;top:178mm;width:50mm;border-top:0.4pt solid #888;"></div>' +
            '  <div style="position:absolute;right:35mm;top:178mm;width:50mm;border-top:0.4pt solid #888;"></div>' +
            '  <div style="position:absolute;left:60mm;top:170mm;transform:translateX(-50%);font-size:14pt;font-weight:normal;color:#555;text-align:center;">' + dateText + '</div>' +
            '  <div style="position:absolute;left:60mm;top:180mm;transform:translateX(-50%);font-size:10pt;font-weight:bold;color:#c9a84c;text-align:center;">ISSUE DATE</div>' +
            sigBlock +
            '  <div style="position:absolute;left:237mm;top:180mm;transform:translateX(-50%);font-size:10pt;font-weight:bold;color:#c9a84c;text-align:center;white-space:nowrap;">AUTHORIZED SIGNATURE</div>' +
            '</div>';
    }

    function offerLetterPreview(bodyContent) {
        var filled = fillPlaceholders(bodyContent);
        var logoHtml = NGO_LOGO
            ? '<div style="text-align:center;margin-bottom:4mm;"><img src="' + NGO_LOGO + '" style="width:20mm;height:20mm;object-fit:contain;"></div>'
            : '';
        var sigHtml = NGO_SIGNATURE
            ? '<img src="' + NGO_SIGNATURE + '" style="height:22px;object-fit:contain;display:block;margin-top:2mm;">'
            : '';
        // Strip trailing duplicate closing lines so hardcoded signature shows only once
        var bodyPreview = filled;
        var ngoName = sampleData['{ngo_name}'];
        var lines = bodyPreview.split('\n');
        while (lines.length > 0) {
            var t = lines[lines.length - 1].trim();
            if (t === '' || t === 'Sincerely,' || t === ngoName) { lines.pop(); }
            else { break; }
        }
        var bodyHtml = lines.join('\n').replace(/\n/g, '<br>');
        return '' +
            '<div style="background:#f0f0f0;overflow-y:auto;max-height:80vh;">' +
            '  <div style="width:210mm;background:#fff;margin:0 auto;font-family:Helvetica,Arial,sans-serif;padding:15mm 20mm;box-shadow:0 0 10px rgba(0,0,0,0.1);">' +
            logoHtml +
            '    <div style="border-top:0.6pt solid #c9a84c;margin-bottom:6mm;"></div>' +
            '    <div style="font-size:18pt;font-weight:bold;color:#2c3e50;text-align:center;">OFFER LETTER</div>' +
            '    <div style="font-size:10pt;font-weight:bold;color:#c9a84c;text-align:center;margin-bottom:8mm;">' + sampleData['{ngo_name}'].toUpperCase() + '</div>' +
            '    <div style="display:flex;justify-content:space-between;font-size:10pt;color:#555;margin-bottom:6mm;">' +
            '      <span>Date: ' + sampleData['{date}'] + '</span>' +
            '      <span>Ref: OL/0001/2026</span>' +
            '    </div>' +
            '    <div style="font-size:11pt;font-weight:bold;color:#2c3e50;">To,</div>' +
            '    <div style="font-size:11pt;font-weight:bold;color:#2c3e50;">' + sampleData['{name}'] + '</div>' +
            '    <div style="font-size:11pt;color:#3c3c3c;">john.doe@example.com</div>' +
            '    <div style="font-size:11pt;color:#3c3c3c;margin-bottom:4mm;">+91 98765 43210</div>' +
            '    <div style="font-size:11pt;font-weight:bold;color:#2c3e50;margin-bottom:4mm;">Subject: Offer of Internship</div>' +
            '    <div style="font-size:11pt;color:#3c3c3c;line-height:1.6;">' + bodyHtml + '</div>' +
            '    <div style="margin-top:6mm;font-size:11pt;color:#3c3c3c;">Sincerely,</div>' +
            sigHtml +
            '    <div style="font-size:11pt;font-weight:bold;color:#2c3e50;margin-top:2mm;">Authorized Signatory</div>' +
            '    <div style="font-size:11pt;color:#3c3c3c;">' + sampleData['{ngo_name}'] + '</div>' +
            '    <div style="margin-top:6mm;border-top:0.3pt solid #c9a84c;font-size:8pt;color:#888;text-align:center;padding-top:2mm;">' +
            '      This is a computer-generated document. No signature is required.<br>' + sampleData['{ngo_name}'] +
            '    </div>' +
            '  </div>' +
            '</div>';
    }

    document.querySelectorAll('.preview-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const textarea = document.querySelector('textarea[name="' + targetId + '"]');
            let content = textarea ? textarea.value : '';
            var previewHtml = '';
            var title = 'Template Preview';
            if (targetId === 'template_certificate') {
                previewHtml = certificatePreview(content);
                title = 'Certificate Preview';
            } else if (targetId === 'template_offer_letter') {
                previewHtml = offerLetterPreview(content);
                title = 'Offer Letter Preview';
            } else if (targetId === 'template_member_offer') {
                if (!content.trim()) {
                    previewHtml = '<div style="padding:60px 20px;text-align:center;color:#888;font-family:Arial,sans-serif;">' +
                        '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#ccc" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>' +
                        '<p style="margin-top:16px;font-size:14px;">Template is empty. Write your offer letter HTML in the <strong>Body (HTML)</strong> field above, then preview.</p>' +
                        '<p style="font-size:12px;color:#aaa;">Tip: Use placeholders like <code>{name}</code>, <code>{member_id}</code>, <code>{designation}</code>, etc.</p>' +
                        '</div>';
                    title = 'Member Offer Letter Preview';
                } else {
                    var filled = content;
                    Object.keys(memberSampleData).forEach(function(key) {
                        filled = filled.split(key).join(memberSampleData[key]);
                    });
                    // Strip outer html/head/body tags for clean inline rendering
                    filled = filled.replace(/<!DOCTYPE[^>]*>/gi, '')
                        .replace(/<html[^>]*>/gi, '').replace(/<\/html>/gi, '')
                        .replace(/<head[^>]*>[\s\S]*?<\/head>/gi, '')
                        .replace(/<body[^>]*>/gi, '').replace(/<\/body>/gi, '')
                        .trim();
                    previewHtml = '<div style="background:#e8ecf0;padding:30px;text-align:center;max-height:80vh;overflow-y:auto;">' +
                        '<div style="width:700px;background:#fff;margin:0 auto;padding:50px 60px;box-shadow:0 4px 20px rgba(0,0,0,0.15);text-align:left;font-family:Georgia,\'Times New Roman\',serif;">' +
                            filled +
                        '</div></div>';
                    title = 'Member Offer Letter Preview';
                }
            } else {
                previewHtml = '<div style="font-family:Helvetica,Arial,sans-serif;padding:16px;max-width:600px;margin:0 auto;">' + fillPlaceholders(content) + '</div>';
                title = targetId.indexOf('acceptance') !== -1 ? 'Acceptance Email Preview' : 'Rejection Email Preview';
            }
            document.getElementById('previewModalLabel').innerHTML =
                '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg> ' + title;
            document.getElementById('previewContent').innerHTML = previewHtml;
            var modal = new bootstrap.Modal(document.getElementById('previewModal'));
            modal.show();
        });
    });
});
</script>
