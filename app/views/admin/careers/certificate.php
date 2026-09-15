<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate - <?php echo htmlspecialchars($intern->name); ?></title>

    <style>
        body { background: #e8e4de; font-family: 'Georgia', 'Times New Roman', serif; }
        .certificate-wrapper { min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 30px; }
        .certificate {
            width: 100%; max-width: 900px; background: #fefcf5; padding: 60px 70px; position: relative;
            box-shadow: 0 15px 50px rgba(0,0,0,.2);
        }
        .cert-border-frame1 {
            position: absolute; top: 15px; left: 15px; right: 15px; bottom: 15px;
            border: 2px solid #c9a84c; pointer-events: none;
        }
        .cert-border-frame2 {
            position: absolute; top: 8px; left: 8px; right: 8px; bottom: 8px;
            border: 1px solid #d4b85a; pointer-events: none;
        }

        .certificate-logo { max-height: 90px; max-width: 200px; margin: 0 auto 8px; display: block; }
        .certificate-title { font-family: 'Georgia', 'Times New Roman', serif; font-size: 2.2rem; font-weight: 700; color: #2c3e50; text-transform: uppercase; letter-spacing: 4px; margin-bottom: 2px; text-align: center; }
        .gold-line { width: 180px; height: 2px; background: linear-gradient(90deg, transparent, #c9a84c, transparent); margin: 12px auto; }
        .gold-line-thin { width: 120px; height: 1px; background: linear-gradient(90deg, transparent, #c9a84c, transparent); margin: 8px auto; }
        .certificate-subtitle { font-size: 1rem; color: #7a6b4f; letter-spacing: 3px; text-transform: uppercase; font-weight: 600; text-align: center; }
        .certificate-body { padding: 15px 0 10px; text-align: center; }
        .certificate-body p { font-size: 1.1rem; color: #3d3d3d; font-style: italic; margin-bottom: 5px; text-align: center; }
        .certificate-name { font-family: 'Georgia', 'Times New Roman', serif; font-size: 2rem; font-weight: 700; color: #1a1a1a; margin: 10px 0; letter-spacing: 1px; text-align: center; }
        .certificate-detail { font-size: 1rem; color: #555; text-align: center; }
        .certificate-detail strong { color: #2c3e50; }
        .certificate-seal { margin-top: 25px; padding-top: 15px; display: flex; justify-content: space-between; align-items: flex-end; }
        .cert-seal-item { text-align: center; }
        .cert-seal-line { width: 160px; height: 1px; background: #999; margin: 0 auto 4px; }
        .cert-seal-label { font-size: .85rem; color: #888; letter-spacing: 1px; text-transform: uppercase; }
        .actions-sidebar { position: fixed; top: 50%; right: 20px; transform: translateY(-50%); display: flex; flex-direction: column; gap: 8px; z-index: 1000; }
        .actions-sidebar .btn { text-align: left; min-width: 200px; }
        @media (max-width: 768px) { .certificate { padding: 30px; } }
        @media print {
            @page { size: A4 landscape; margin: 0; }
            body { background: #fff; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .no-print { display: none !important; }
            .certificate { box-shadow: none; }
        }
    </style>
</head>
<body>
    <div class="certificate-wrapper">
        <div class="certificate text-center">
            <div class="cert-border-frame1"></div>
            <div class="cert-border-frame2"></div>
            <?php if (!empty($globalSettings['ngo_logo'])): ?>
                <img src="<?php echo file_url($globalSettings['ngo_logo']); ?>" class="certificate-logo" alt="Logo">
            <?php endif; ?>
            <div class="gold-line"></div>
            <div class="certificate-title">Certificate of Completion</div>
            <div class="gold-line-thin"></div>
            <div class="certificate-subtitle"><?php echo htmlspecialchars($globalSettings['ngo_name'] ?? 'NGO HELP'); ?></div>
            <div class="gold-line"></div>

            <div class="certificate-body">
                <p>This is to certify that</p>
                <div class="certificate-name"><?php echo htmlspecialchars($intern->name); ?></div>
                <p>has successfully completed the internship program</p>
                <div class="gold-line-thin"></div>
                <div class="certificate-detail mb-1">
                    <strong>Position:</strong> <?php echo htmlspecialchars($intern->career_title ?? 'Intern'); ?>
                </div>
                <?php if (!empty($intern->duration)): ?>
                    <div class="certificate-detail mb-1">
                        <strong>Duration:</strong> <?php echo htmlspecialchars($intern->duration); ?>
                    </div>
                <?php endif; ?>
                <?php if (!empty($intern->completion_date)): ?>
                    <div class="certificate-detail mb-1">
                        <strong>Completion Date:</strong> <?php echo date('d F, Y', strtotime($intern->completion_date)); ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="certificate-seal">
                <div class="cert-seal-item">
                    <div style="font-size:.95rem;color:#555;"><?php echo !empty($intern->completion_date) ? date('d F, Y', strtotime($intern->completion_date)) : date('d F, Y'); ?></div>
                    <div class="cert-seal-line" style="margin:4px auto 2px;"></div>
                    <div class="cert-seal-label">Date</div>
                </div>
                <div class="cert-seal-item">
                    <?php if (!empty($globalSettings['ngo_signature'])): ?>
                        <img src="<?php echo file_url($globalSettings['ngo_signature']); ?>" style="max-height: 50px;" alt="Signature">
                    <?php else: ?>
                        <div class="cert-seal-line" style="margin:4px auto;"></div>
                    <?php endif; ?>
                    <div class="cert-seal-label">Authorized Signature</div>
                </div>
            </div>
        </div>
    </div>

    <div class="actions-sidebar no-print">
        <a href="<?php echo url('certificate_pdf.php?uuid=' . ($intern->uuid ?? $intern->id)); ?>" target="_blank" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" /><path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" /><path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" /></svg>
            Download Certificate
        </a>
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modal-email-cert">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z" /><path d="M3 7l9 6l9 -6" /></svg>
            Email Certificate
        </button>
        <a href="<?php echo url('admin/careers/interns'); ?>" class="btn btn-outline-secondary">Back to Interns</a>
    </div>

    <!-- Email Certificate Modal -->
    <div class="modal modal-blur fade" id="modal-email-cert" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-header">
                    <h5 class="modal-title">Send Certificate via Email</h5>
                </div>
                <form id="email-cert-form" method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Recipient Email</label>
                            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($intern->email); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Subject</label>
                            <input type="text" name="subject" class="form-control" value="Certificate of Completion - <?php echo htmlspecialchars($intern->name); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Message</label>
                            <textarea name="message" class="form-control" rows="4" required>Dear <?php echo htmlspecialchars($intern->name); ?>,

Congratulations on successfully completing your internship with <?php echo htmlspecialchars($globalSettings['ngo_name'] ?? 'NGO HELP'); ?>.

Please find your certificate of completion at the link below:

<?php echo url('admin/careers/certificate/' . ($intern->uuid ?? $intern->id)); ?>

Best regards,
<?php echo htmlspecialchars($globalSettings['ngo_name'] ?? 'NGO HELP'); ?></textarea>
                        </div>
                        <div id="email-cert-msg"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success" id="send-cert-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z" /><path d="M3 7l9 6l9 -6" /></svg>
                            Send Email
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    document.getElementById('email-cert-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = document.getElementById('send-cert-btn');
        const msg = document.getElementById('email-cert-msg');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Sending...';
        const fd = new FormData(this);
        fetch('<?php echo url('admin/careers/send-certificate/' . ($intern->uuid ?? $intern->id)); ?>', { method: 'POST', body: fd })
            .then(r => r.json())
            .then(res => {
                msg.innerHTML = res.status === 'success'
                    ? '<div class="alert alert-success py-2 mb-0">' + res.message + '</div>'
                    : '<div class="alert alert-danger py-2 mb-0">' + (res.message || 'Failed to send.') + '</div>';
                if (res.status === 'success') {
                    setTimeout(() => { location.reload(); }, 1500);
                } else {
                    btn.disabled = false;
                    btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z" /><path d="M3 7l9 6l9 -6" /></svg> Send Email';
                }
            })
            .catch(() => {
                msg.innerHTML = '<div class="alert alert-danger py-2 mb-0">Something went wrong.</div>';
                btn.disabled = false;
                btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z" /><path d="M3 7l9 6l9 -6" /></svg> Send Email';
            });
    });
    </script>
</body>
</html>
