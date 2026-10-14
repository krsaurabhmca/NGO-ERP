<?php require_once 'app/views/admin/layouts/header.php'; ?>
<?php require_once 'app/views/admin/layouts/sidebar.php'; ?>

<div class="page-wrapper">
    <?php require_once 'app/views/admin/layouts/topbar.php'; ?>

    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <div class="mb-1">
                        <ol class="breadcrumb" aria-label="breadcrumbs">
                            <li class="breadcrumb-item small"><a href="<?php echo url('admin/dashboard'); ?>">Home</a></li>
                            <li class="breadcrumb-item small"><a href="#">Settings</a></li>
                            <li class="breadcrumb-item active small" aria-current="page">Organization Settings</li>
                        </ol>
                    </div>
                    <h2 class="page-title fw-bold fs-1">
                        Organization Settings
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
                            <h4 class="subheader mb-4">Organization Config</h4>
                            <div class="list-group list-group-transparent mb-3">
                                <a href="#tab-general" class="list-group-item list-group-item-action d-flex align-items-center active" data-bs-toggle="tab">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" /><path d="M12 9h.01" /><path d="M11 12h1v4h1" /></svg>
                                    General Info
                                </a>
                                <a href="#tab-tax" class="list-group-item list-group-item-action d-flex align-items-center" data-bs-toggle="tab">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 5h10l2 2l-2 2h-10a1 1 0 0 1 -1 -1v-2a1 1 0 0 1 1 -1z" /><path d="M13 13h7l2 2l-2 2h-7a1 1 0 0 1 -1 -1v-2a1 1 0 0 1 1 -1z" /><path d="M6 13v7a1 1 0 0 0 1 1h2" /><path d="M6 5v2" /></svg>
                                    Tax & Legal (80G/12A)
                                </a>
                                <a href="#tab-branding" class="list-group-item list-group-item-action d-flex align-items-center" data-bs-toggle="tab">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z" /><path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" /></svg>
                                    Branding Settings
                                </a>
                                <a href="#tab-social" class="list-group-item list-group-item-action d-flex align-items-center" data-bs-toggle="tab">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" /><path d="M8 11l0 5" /><path d="M8 8l0 .01" /><path d="M12 16l0 -5" /><path d="M16 16v-3a2 2 0 0 0 -4 0" /></svg>
                                    Social Links
                                </a>
                                <a href="#tab-radio" class="list-group-item list-group-item-action d-flex align-items-center" data-bs-toggle="tab">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-danger" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3a9 9 0 0 1 0 18" /><path d="M10 6a5 5 0 0 1 0 12" /><path d="M6 9a1 1 0 0 1 0 6" /><circle cx="4.5" cy="12" r="1.5" fill="currentColor" /></svg>
                                    Live Radio / FM
                                    <?php if (!empty($settings['radio_enabled']) && $settings['radio_enabled'] == '1' && !empty($settings['radio_stream_url'])): ?>
                                        <span class="badge bg-danger text-white ms-auto fw-bold px-1.5 py-0.5" style="font-size: 0.65rem; letter-spacing: 0.5px;">LIVE</span>
                                    <?php endif; ?>
                                </a>
                                <a href="#tab-map" class="list-group-item list-group-item-action d-flex align-items-center" data-bs-toggle="tab">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" /><path d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z" /></svg>
                                    Map Settings
                                </a>
                                <a href="#tab-id-prefix" class="list-group-item list-group-item-action d-flex align-items-center" data-bs-toggle="tab">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7v-1a2 2 0 0 1 2 -2h2" /><path d="M4 17v1a2 2 0 0 0 2 2h2" /><path d="M16 4h2a2 2 0 0 1 2 2v1" /><path d="M16 20h2a2 2 0 0 0 2 -2v-1" /><path d="M9 12h6" /></svg>
                                    ID Prefixes
                                </a>
                                <a href="#tab-theme" class="list-group-item list-group-item-action d-flex align-items-center" data-bs-toggle="tab">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 21a9 9 0 1 1 0 -18a9 8 0 0 1 9 8a4.5 4 0 0 1 -4.5 4h-2.5a2 2 0 0 0 -1 3.75a1.3 1.3 0 0 1 -1 2.25" /><circle cx="7.5" cy="10.5" r=".5" fill="currentColor" /><circle cx="12" cy="7.5" r=".5" fill="currentColor" /><circle cx="16.5" cy="10.5" r=".5" fill="currentColor" /></svg>
                                    Theme Colors
                                </a>
                                <a href="#tab-system-update" class="list-group-item list-group-item-action d-flex align-items-center" data-bs-toggle="tab">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" /><path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" /></svg>
                                    System Updates
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Content Section -->
                    <div class="col-12 col-md-9 d-flex flex-column">
                        <div class="card-body">
                            <div class="tab-content">
                                <!-- Page 1: General Info -->
                                <div class="tab-pane active show" id="tab-general">
                                    <form action="<?php echo url('admin/settings/organization'); ?>" method="POST">
                                        <input type="hidden" name="_csrf_token" value="<?php echo csrf_token(); ?>">

                                        <input type="hidden" name="active_tab" value="tab-general">
                                        <h3 class="card-title mb-4">Basic Organization Information</h3>
                                        <div class="row g-4">
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">NGO Name</label>
                                                    <input type="text" name="ngo_name" class="form-control" value="<?php echo $settings['ngo_name'] ?? ''; ?>" placeholder="Enter NGO name">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">NGO Email</label>
                                                    <input type="email" name="ngo_email" class="form-control" value="<?php echo $settings['ngo_email'] ?? ''; ?>" placeholder="contact@ngo.com">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Phone Number</label>
                                                    <input type="text" name="ngo_phone" class="form-control" value="<?php echo $settings['ngo_phone'] ?? ''; ?>" placeholder="+91 XXXXX XXXXX">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold"><i class="fab fa-whatsapp text-success me-1"></i>WhatsApp Number</label>
                                                    <input type="text" name="ngo_whatsapp" class="form-control" value="<?php echo $settings['ngo_whatsapp'] ?? ''; ?>" placeholder="+91 XXXXX XXXXX">
                                                    <div class="form-text text-muted small">Used for the floating WhatsApp chat button on the website.</div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Official Address</label>
                                                    <textarea name="ngo_address" class="form-control" rows="3" placeholder="Enter physical address"><?php echo $settings['ngo_address'] ?? ''; ?></textarea>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Footer Credits / Copyright Text</label>
                                                    <input type="text" name="ngo_footer_credits" class="form-control" value="<?php echo $settings['ngo_footer_credits'] ?? ''; ?>" placeholder="e.g. &copy; 2026 My NGO. All rights reserved. Developed by Company">
                                                    <div class="form-text text-muted small">Custom credit or copyright text displayed at the bottom of the website footer. Leave empty for automatic default.</div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="mt-4 pt-3 border-top d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" /><path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M14 4l0 4l-6 0l0 -4" /></svg>
                                                Save General Info
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Tax & Legal (80G / 12A) Settings -->
                                <div class="tab-pane" id="tab-tax">
                                    <form action="<?php echo url('admin/settings/organization'); ?>" method="POST">
                                        <input type="hidden" name="_csrf_token" value="<?php echo csrf_token(); ?>">
                                        <input type="hidden" name="active_tab" value="tab-tax">
                                        
                                        <h3 class="card-title mb-1">Tax Exemption & Legal Registrations (80G / 12A)</h3>
                                        <p class="text-muted small mb-4">Configure your NGO's PAN, 12A, and 80G registration details. These will appear automatically on donation receipts (PDF & HTML) and receipt emails.</p>

                                        <div class="row g-4">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">
                                                        <i class="fas fa-id-card text-primary me-1"></i> NGO PAN Number
                                                    </label>
                                                    <input type="text" name="ngo_pan" class="form-control text-uppercase" value="<?php echo htmlspecialchars($settings['ngo_pan'] ?? ''); ?>" placeholder="e.g. AAATN1234F" maxlength="10">
                                                    <div class="form-text text-muted small">Permanent Account Number (PAN) of the organization.</div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">
                                                        <i class="fas fa-certificate text-success me-1"></i> 80G Registration No. / URN
                                                    </label>
                                                    <input type="text" name="ngo_80g_reg_no" class="form-control" value="<?php echo htmlspecialchars($settings['ngo_80g_reg_no'] ?? ''); ?>" placeholder="e.g. AAATN1234FE20212">
                                                    <div class="form-text text-muted small">Unique Registration Number (URN) or Order Number for 80G tax exemption.</div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">
                                                        <i class="fas fa-file-invoice-dollar text-warning me-1"></i> 12A Registration No. / URN
                                                    </label>
                                                    <input type="text" name="ngo_12a_reg_no" class="form-control" value="<?php echo htmlspecialchars($settings['ngo_12a_reg_no'] ?? ''); ?>" placeholder="e.g. AAATN1234FE20211">
                                                    <div class="form-text text-muted small">Unique Registration Number (URN) or Order Number for 12A registration.</div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">
                                                        <i class="fas fa-calendar-check text-info me-1"></i> 80G Validity / Assessment Years
                                                    </label>
                                                    <input type="text" name="ngo_80g_validity" class="form-control" value="<?php echo htmlspecialchars($settings['ngo_80g_validity'] ?? ''); ?>" placeholder="e.g. From AY 2022-23 to AY 2026-27 or Perpetual">
                                                    <div class="form-text text-muted small">Validity period or assessment years mentioned on the 80G certificate.</div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">
                                                        <i class="fas fa-globe text-secondary me-1"></i> FCRA Registration No. (Optional)
                                                    </label>
                                                    <input type="text" name="ngo_fcra_reg_no" class="form-control" value="<?php echo htmlspecialchars($settings['ngo_fcra_reg_no'] ?? ''); ?>" placeholder="e.g. 083781234">
                                                    <div class="form-text text-muted small">Foreign Contribution Regulation Act registration number (if applicable).</div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">
                                                        <i class="fas fa-building text-secondary me-1"></i> CSR Registration No. (Optional)
                                                    </label>
                                                    <input type="text" name="ngo_csr_reg_no" class="form-control" value="<?php echo htmlspecialchars($settings['ngo_csr_reg_no'] ?? ''); ?>" placeholder="e.g. CSR00012345">
                                                    <div class="form-text text-muted small">MCA CSR registration number for corporate donations (if applicable).</div>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Tax Exemption Note / Disclaimer</label>
                                                    <textarea name="ngo_tax_exemption_note" class="form-control" rows="3" placeholder="e.g. Donations to this organization are eligible for 50% tax exemption under Section 80G of the Income Tax Act, 1961."><?php echo htmlspecialchars($settings['ngo_tax_exemption_note'] ?? ''); ?></textarea>
                                                    <div class="form-text text-muted small">Custom note printed at the bottom of tax receipts and in receipt emails. Leave blank for default standard note.</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-4 pt-3 border-top d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" /><path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M14 4l0 4l-6 0l0 -4" /></svg>
                                                Save Tax & Legal Info
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Page 2: Branding Settings -->
                                <div class="tab-pane" id="tab-branding">
                                    <form action="<?php echo url('admin/settings/organization'); ?>" method="POST" enctype="multipart/form-data">
                                        <input type="hidden" name="_csrf_token" value="<?php echo csrf_token(); ?>">

                                        <input type="hidden" name="active_tab" value="tab-branding">
                                        <h3 class="card-title mb-4">Visual Identity & Branding</h3>
                                        
                                        <div class="row g-4">
                                            <!-- Logo Section -->
                                            <div class="col-md-12">
                                                <div class="p-3 bg-light rounded-2 border mb-4">
                                                    <label class="form-label fw-bold mb-3">Organization Logo</label>
                                                    <div class="d-flex align-items-center gap-4">
                                                        <div class="border rounded d-flex align-items-center justify-content-center bg-white shadow-sm overflow-hidden" style="width: 140px; height: 100px; flex-shrink: 0;">
                                                            <img id="logo-preview" src="<?php echo !empty($settings['ngo_logo']) ? file_url($settings['ngo_logo']) : ''; ?>" alt="Logo" style="max-width: 100%; max-height: 100%; object-fit: contain; <?php echo empty($settings['ngo_logo']) ? 'display:none;' : ''; ?>">
                                                            <span id="logo-placeholder" class="text-muted small fw-bold" style="<?php echo !empty($settings['ngo_logo']) ? 'display:none;' : ''; ?>">No Logo</span>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <input type="file" name="ngo_logo" id="ngo_logo_input" class="form-control" accept="image/*" max="5242880">
                                                            <div class="text-muted mt-2 small">PNG or SVG with transparent background preferred (Max: 5MB).</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Favicon Section -->
                                            <div class="col-md-12">
                                                <div class="p-3 bg-light rounded-2 border mb-4">
                                                    <label class="form-label fw-bold mb-3">Site Favicon</label>
                                                    <div class="d-flex align-items-center gap-4">
                                                        <div class="border rounded d-flex align-items-center justify-content-center bg-white shadow-sm overflow-hidden" style="width: 60px; height: 60px; flex-shrink: 0;">
                                                            <img id="favicon-preview" src="<?php echo !empty($settings['ngo_favicon']) ? file_url($settings['ngo_favicon']) : ''; ?>" alt="Favicon" style="max-width: 100%; max-height: 100%; object-fit: contain; <?php echo empty($settings['ngo_favicon']) ? 'display:none;' : ''; ?>">
                                                            <span id="favicon-placeholder" class="text-muted extra-small fw-bold" style="<?php echo !empty($settings['ngo_favicon']) ? 'display:none;' : ''; ?>">None</span>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <input type="file" name="ngo_favicon" id="ngo_favicon_input" class="form-control" accept="image/x-icon,image/png" max="5242880">
                                                            <div class="text-muted mt-2 small">Browser tab icon. 32x32 px PNG or ICO (Max: 5MB).</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Signature Section -->
                                            <div class="col-md-12">
                                                <div class="p-3 bg-light rounded-2 border">
                                                    <label class="form-label fw-bold mb-3">Authorized Signature</label>
                                                    <div class="d-flex align-items-center gap-4">
                                                        <div class="border rounded d-flex align-items-center justify-content-center bg-white shadow-sm overflow-hidden" style="width: 200px; height: 80px; flex-shrink: 0;">
                                                            <img id="signature-preview" src="<?php echo !empty($settings['ngo_signature']) ? file_url($settings['ngo_signature']) : ''; ?>" alt="Signature" style="max-width: 100%; max-height: 100%; object-fit: contain; <?php echo empty($settings['ngo_signature']) ? 'display:none;' : ''; ?>">
                                                            <span id="signature-placeholder" class="text-muted small fw-bold" style="<?php echo !empty($settings['ngo_signature']) ? 'display:none;' : ''; ?>">No Signature</span>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <input type="file" name="ngo_signature" id="ngo_signature_input" class="form-control" accept="image/png,image/jpeg" max="5242880">
                                                            <div class="text-muted mt-2 small">For ID cards & certificates. Transparent PNG preferred (Max: 5MB).</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-4 pt-3 border-top d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" /><path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M14 4l0 4l-6 0l0 -4" /></svg>
                                                Save Branding Settings
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Page 3: Social Links -->
                                <div class="tab-pane" id="tab-social">
                                    <form action="<?php echo url('admin/settings/organization'); ?>" method="POST">
                                        <input type="hidden" name="_csrf_token" value="<?php echo csrf_token(); ?>">

                                        <input type="hidden" name="active_tab" value="tab-social">
                                        <h3 class="card-title mb-4">Social Media Links</h3>
                                        <p class="text-muted small mb-4">Enter your profile URLs to display social media icons on the website.</p>
                                        
                                        <div class="row g-4">
                                            <div class="col-md-6">
                                                <div class="mb-4">
                                                    <label class="form-label fw-bold"><i class="fab fa-facebook text-primary me-2"></i>Facebook URL</label>
                                                    <input type="url" name="social_facebook" class="form-control" value="<?php echo $settings['social_facebook'] ?? ''; ?>" placeholder="https://facebook.com/yourngo">
                                                </div>
                                                <div class="mb-4">
                                                    <label class="form-label fw-bold"><i class="fab fa-twitter text-info me-2"></i>Twitter URL</label>
                                                    <input type="url" name="social_twitter" class="form-control" value="<?php echo $settings['social_twitter'] ?? ''; ?>" placeholder="https://twitter.com/yourngo">
                                                </div>
                                                <div class="mb-4">
                                                    <label class="form-label fw-bold"><i class="fab fa-instagram text-danger me-2"></i>Instagram URL</label>
                                                    <input type="url" name="social_instagram" class="form-control" value="<?php echo $settings['social_instagram'] ?? ''; ?>" placeholder="https://instagram.com/yourngo">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-4">
                                                    <label class="form-label fw-bold"><i class="fab fa-linkedin text-primary me-2"></i>LinkedIn URL</label>
                                                    <input type="url" name="social_linkedin" class="form-control" value="<?php echo $settings['social_linkedin'] ?? ''; ?>" placeholder="https://linkedin.com/company/yourngo">
                                                </div>
                                                <div class="mb-4">
                                                    <label class="form-label fw-bold"><i class="fab fa-youtube text-danger me-2"></i>YouTube URL</label>
                                                    <input type="url" name="social_youtube" class="form-control" value="<?php echo $settings['social_youtube'] ?? ''; ?>" placeholder="https://youtube.com/c/yourngo">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-4 pt-3 border-top d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" /><path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M14 4l0 4l-6 0l0 -4" /></svg>
                                                Save Social Links
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Page 4: Map Settings -->
                                <div class="tab-pane" id="tab-map">
                                    <form action="<?php echo url('admin/settings/organization'); ?>" method="POST">
                                        <input type="hidden" name="_csrf_token" value="<?php echo csrf_token(); ?>">

                                        <input type="hidden" name="active_tab" value="tab-map">
                                        <h3 class="card-title mb-4">Map Embed Settings</h3>
                                        
                                        <div class="mb-4">
                                            <label class="form-label fw-bold">Google Map Embed Code (Iframe)</label>
                                            <textarea name="ngo_map_embed" class="form-control" rows="5" placeholder='<iframe src="https://www.google.com/maps/embed?pb=..." width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>'><?php echo htmlspecialchars($settings['ngo_map_embed'] ?? ''); ?></textarea>
                                            <div class="text-muted mt-2 small">
                                                Go to Google Maps, find your location, click "Share", select "Embed a map", and copy the HTML code here. <br>
                                                <em>If left empty, the system will try to generate a basic map using your physical address.</em>
                                            </div>
                                        </div>

                                        <?php if (!empty($settings['ngo_map_embed'])): ?>
                                        <div class="mb-4">
                                            <label class="form-label fw-bold">Current Map Preview</label>
                                            <div class="border rounded-3 overflow-hidden shadow-sm" style="max-height: 300px;">
                                                <?php echo $settings['ngo_map_embed']; ?>
                                            </div>
                                        </div>
                                        <?php endif; ?>

                                        <div class="mt-4 pt-3 border-top d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" /><path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M14 4l0 4l-6 0l0 -4" /></svg>
                                                Save Map Settings
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Page 5: ID Prefixes -->
                                <div class="tab-pane" id="tab-id-prefix">
                                    <form action="<?php echo url('admin/settings/organization'); ?>" method="POST">
                                        <input type="hidden" name="_csrf_token" value="<?php echo csrf_token(); ?>">

                                        <input type="hidden" name="active_tab" value="tab-id-prefix">
                                        <h3 class="card-title mb-4">ID Prefix Configuration</h3>
                                        <p class="text-muted small mb-4">Set custom prefixes for auto-generated IDs used across the system.</p>
                                        <div class="row g-4">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold"><i class="fas fa-user-tie me-2 text-primary"></i>Member ID Prefix</label>
                                                    <input type="text" name="id_prefix_member" class="form-control" value="<?php echo $settings['id_prefix_member'] ?? 'MEM'; ?>" placeholder="MEM">
                                                    <small class="text-muted">e.g. MEM → MEM-2026-0001</small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold"><i class="fas fa-hand-holding-heart me-2 text-success"></i>Donor ID Prefix</label>
                                                    <input type="text" name="id_prefix_donor" class="form-control" value="<?php echo $settings['id_prefix_donor'] ?? 'DNR'; ?>" placeholder="DNR">
                                                    <small class="text-muted">e.g. DNR → DNR-2026-0001</small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold"><i class="fas fa-users me-2 text-info"></i>Beneficiary ID Prefix</label>
                                                    <input type="text" name="id_prefix_beneficiary" class="form-control" value="<?php echo $settings['id_prefix_beneficiary'] ?? 'BEN'; ?>" placeholder="BEN">
                                                    <small class="text-muted">e.g. BEN → BEN-2026-0001</small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold"><i class="fas fa-folder-open me-2 text-warning"></i>Project ID Prefix</label>
                                                    <input type="text" name="id_prefix_project" class="form-control" value="<?php echo $settings['id_prefix_project'] ?? 'PROJ'; ?>" placeholder="PROJ">
                                                    <small class="text-muted">e.g. PROJ → PROJ-2026-0001</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-4 pt-3 border-top d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" /><path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M14 4l0 4l-6 0l0 -4" /></svg>
                                                Save ID Prefixes
                                            </button>
                                        </div>
                                </div>

                                <!-- Page 6: Theme Colors -->
                                <div class="tab-pane" id="tab-theme">
                                    <form action="<?php echo url('admin/settings/organization'); ?>" method="POST">
                                        <input type="hidden" name="_csrf_token" value="<?php echo csrf_token(); ?>">
                                        <input type="hidden" name="active_tab" value="tab-theme">
                                        
                                        <h3 class="card-title mb-4">Theme Configuration</h3>
                                        <p class="text-muted small mb-4">Customize the primary and secondary colors of the admin panel.</p>
                                        
                                        <div class="row g-4">
                                            <div class="col-md-12">
                                                <label class="form-label fw-bold mb-3">Quick Presets</label>
                                                <div class="d-flex flex-wrap gap-2 mb-3">
                                                    <button type="button" class="btn btn-sm btn-outline-secondary theme-preset" data-primary="#003566" data-secondary="#0d9488">
                                                        <span class="d-inline-flex align-items-center me-2">
                                                            <span style="background-color: #003566; width: 14px; height: 14px; display: inline-block; border-radius: 50%; z-index: 2; border: 1px solid rgba(0,0,0,0.1);"></span>
                                                            <span style="background-color: #0d9488; width: 14px; height: 14px; display: inline-block; border-radius: 50%; margin-left: -6px; z-index: 1; border: 1px solid rgba(0,0,0,0.1);"></span>
                                                        </span> Midnight & Teal
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-secondary theme-preset" data-primary="#1e293b" data-secondary="#3b82f6">
                                                        <span class="d-inline-flex align-items-center me-2">
                                                            <span style="background-color: #1e293b; width: 14px; height: 14px; display: inline-block; border-radius: 50%; z-index: 2; border: 1px solid rgba(0,0,0,0.1);"></span>
                                                            <span style="background-color: #3b82f6; width: 14px; height: 14px; display: inline-block; border-radius: 50%; margin-left: -6px; z-index: 1; border: 1px solid rgba(0,0,0,0.1);"></span>
                                                        </span> Slate & Neon Blue
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-secondary theme-preset" data-primary="#14532d" data-secondary="#10b981">
                                                        <span class="d-inline-flex align-items-center me-2">
                                                            <span style="background-color: #14532d; width: 14px; height: 14px; display: inline-block; border-radius: 50%; z-index: 2; border: 1px solid rgba(0,0,0,0.1);"></span>
                                                            <span style="background-color: #10b981; width: 14px; height: 14px; display: inline-block; border-radius: 50%; margin-left: -6px; z-index: 1; border: 1px solid rgba(0,0,0,0.1);"></span>
                                                        </span> Forest & Mint
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-secondary theme-preset" data-primary="#0f172a" data-secondary="#f59e0b">
                                                        <span class="d-inline-flex align-items-center me-2">
                                                            <span style="background-color: #0f172a; width: 14px; height: 14px; display: inline-block; border-radius: 50%; z-index: 2; border: 1px solid rgba(0,0,0,0.1);"></span>
                                                            <span style="background-color: #f59e0b; width: 14px; height: 14px; display: inline-block; border-radius: 50%; margin-left: -6px; z-index: 1; border: 1px solid rgba(0,0,0,0.1);"></span>
                                                        </span> Obsidian & Amber
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-secondary theme-preset" data-primary="#4c1d95" data-secondary="#fbbf24">
                                                        <span class="d-inline-flex align-items-center me-2">
                                                            <span style="background-color: #4c1d95; width: 14px; height: 14px; display: inline-block; border-radius: 50%; z-index: 2; border: 1px solid rgba(0,0,0,0.1);"></span>
                                                            <span style="background-color: #fbbf24; width: 14px; height: 14px; display: inline-block; border-radius: 50%; margin-left: -6px; z-index: 1; border: 1px solid rgba(0,0,0,0.1);"></span>
                                                        </span> Royal Purple & Gold
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-secondary theme-preset" data-primary="#881337" data-secondary="#f43f5e">
                                                        <span class="d-inline-flex align-items-center me-2">
                                                            <span style="background-color: #881337; width: 14px; height: 14px; display: inline-block; border-radius: 50%; z-index: 2; border: 1px solid rgba(0,0,0,0.1);"></span>
                                                            <span style="background-color: #f43f5e; width: 14px; height: 14px; display: inline-block; border-radius: 50%; margin-left: -6px; z-index: 1; border: 1px solid rgba(0,0,0,0.1);"></span>
                                                        </span> Deep Crimson & Rose
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-secondary theme-preset" data-primary="#1e3a8a" data-secondary="#06b6d4">
                                                        <span class="d-inline-flex align-items-center me-2">
                                                            <span style="background-color: #1e3a8a; width: 14px; height: 14px; display: inline-block; border-radius: 50%; z-index: 2; border: 1px solid rgba(0,0,0,0.1);"></span>
                                                            <span style="background-color: #06b6d4; width: 14px; height: 14px; display: inline-block; border-radius: 50%; margin-left: -6px; z-index: 1; border: 1px solid rgba(0,0,0,0.1);"></span>
                                                        </span> Sapphire & Cyan
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-secondary theme-preset" data-primary="#c2410c" data-secondary="#fb7185">
                                                        <span class="d-inline-flex align-items-center me-2">
                                                            <span style="background-color: #c2410c; width: 14px; height: 14px; display: inline-block; border-radius: 50%; z-index: 2; border: 1px solid rgba(0,0,0,0.1);"></span>
                                                            <span style="background-color: #fb7185; width: 14px; height: 14px; display: inline-block; border-radius: 50%; margin-left: -6px; z-index: 1; border: 1px solid rgba(0,0,0,0.1);"></span>
                                                        </span> Sunset Orange & Coral
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-secondary theme-preset" data-primary="#134e4a" data-secondary="#84cc16">
                                                        <span class="d-inline-flex align-items-center me-2">
                                                            <span style="background-color: #134e4a; width: 14px; height: 14px; display: inline-block; border-radius: 50%; z-index: 2; border: 1px solid rgba(0,0,0,0.1);"></span>
                                                            <span style="background-color: #84cc16; width: 14px; height: 14px; display: inline-block; border-radius: 50%; margin-left: -6px; z-index: 1; border: 1px solid rgba(0,0,0,0.1);"></span>
                                                        </span> Dark Teal & Lime Glow
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-secondary theme-preset" data-primary="#18181b" data-secondary="#d946ef">
                                                        <span class="d-inline-flex align-items-center me-2">
                                                            <span style="background-color: #18181b; width: 14px; height: 14px; display: inline-block; border-radius: 50%; z-index: 2; border: 1px solid rgba(0,0,0,0.1);"></span>
                                                            <span style="background-color: #d946ef; width: 14px; height: 14px; display: inline-block; border-radius: 50%; margin-left: -6px; z-index: 1; border: 1px solid rgba(0,0,0,0.1);"></span>
                                                        </span> Charcoal & Neon Fuchsia
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Primary Color</label>
                                                    <input type="color" name="theme_primary_color" id="theme_primary_color" class="form-control form-control-color" value="<?php echo $settings['theme_primary_color'] ?? '#0054a6'; ?>" title="Choose your color">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Secondary Color</label>
                                                    <input type="color" name="theme_secondary_color" id="theme_secondary_color" class="form-control form-control-color" value="<?php echo $settings['theme_secondary_color'] ?? '#206bc4'; ?>" title="Choose your color">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-4 pt-3 border-top d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" /><path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M14 4l0 4l-6 0l0 -4" /></svg>
                                                Save Theme Settings
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Page 7: System Update -->
                                <div class="tab-pane" id="tab-system-update">
                                    <form action="<?php echo url('admin/settings/organization'); ?>" method="POST">
                                        <input type="hidden" name="_csrf_token" value="<?php echo csrf_token(); ?>">
                                        <input type="hidden" name="active_tab" value="tab-system-update">
                                        
                                        <h3 class="card-title mb-4">System Update Configuration</h3>
                                        <p class="text-muted small mb-4">Configure the GitHub repository for automatic system updates.</p>
                                        
                                        <div class="row g-4">
                                            <div class="col-12">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">GitHub Repository URL</label>
                                                    <input type="url" name="github_repo_url" class="form-control" value="<?php echo $settings['github_repo_url'] ?? ''; ?>" placeholder="https://github.com/username/repo">
                                                    <small class="text-muted">The repository must be public and use GitHub Releases.</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-4 pt-3 border-top d-flex justify-content-between align-items-center">
                                            <a href="<?php echo url('admin/settings/update'); ?>" class="btn btn-secondary text-white">Go to System Updates</a>
                                            <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" /><path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M14 4l0 4l-6 0l0 -4" /></svg>
                                                Save System Update Settings
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <!-- Page 8: Live Radio / FM Settings -->
                                <div class="tab-pane" id="tab-radio">
                                    <form action="<?php echo url('admin/settings/organization'); ?>" method="POST" enctype="multipart/form-data">
                                        <input type="hidden" name="_csrf_token" value="<?php echo csrf_token(); ?>">
                                        <input type="hidden" name="active_tab" value="tab-radio">

                                        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
                                            <div>
                                                <h3 class="card-title mb-1 d-flex align-items-center gap-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon text-danger" width="28" height="28" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3a9 9 0 0 1 0 18" /><path d="M10 6a5 5 0 0 1 0 12" /><path d="M6 9a1 1 0 0 1 0 6" /><circle cx="4.5" cy="12" r="1.5" fill="currentColor" /></svg>
                                                    Live Radio & FM Player Settings
                                                </h3>
                                                <p class="text-muted small mb-0">Configure your online radio stream, station details, logo, and floating web player widget.</p>
                                            </div>
                                            <div>
                                                <?php if (!empty($settings['radio_enabled']) && $settings['radio_enabled'] == '1' && !empty($settings['radio_stream_url'])): ?>
                                                    <span class="badge bg-success-lt text-success px-3 py-2 fs-6 d-inline-flex align-items-center gap-2">
                                                        <span class="badge-dot bg-success"></span> Live Radio Widget is Active on Website
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary-lt text-secondary px-3 py-2 fs-6 d-inline-flex align-items-center gap-2">
                                                        <span class="badge-dot bg-secondary"></span> Widget Disabled / Offline
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <!-- Enable/Disable Master Toggle Card -->
                                        <div class="card mb-4 border-0 shadow-none bg-light p-3 rounded-3">
                                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                                                <div>
                                                    <div class="fw-bold text-dark fs-3 mb-1 d-flex align-items-center gap-2">
                                                        <i class="fas fa-broadcast-tower text-danger"></i> Enable Live Radio on Website
                                                    </div>
                                                    <div class="text-muted small">
                                                        When toggled ON, an interactive floating FM / Radio Player widget will be displayed across all website pages for visitors.
                                                    </div>
                                                </div>
                                                <div class="form-check form-switch m-0">
                                                    <input class="form-check-input" type="checkbox" name="radio_enabled" id="radio_enabled_switch" value="1" role="switch" style="width: 3.2rem; height: 1.7rem; cursor: pointer;" <?php echo (!empty($settings['radio_enabled']) && $settings['radio_enabled'] == '1') ? 'checked' : ''; ?>>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row g-4">
                                            <!-- Station Name -->
                                            <div class="col-md-7">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">
                                                        <i class="fas fa-signature text-primary me-1"></i> Radio Station / Channel Name <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="text" name="radio_name" id="input_radio_name" class="form-control form-control-lg" value="<?php echo htmlspecialchars($settings['radio_name'] ?? ''); ?>" placeholder="e.g. Hope FM 90.4 / Community Radio" required>
                                                    <div class="form-text text-muted small">Name of your radio station, channel, or podcast broadcast.</div>
                                                </div>
                                            </div>

                                            <!-- Tagline / Frequency -->
                                            <div class="col-md-5">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">
                                                        <i class="fas fa-wave-square text-success me-1"></i> Frequency / Tagline
                                                    </label>
                                                    <input type="text" name="radio_frequency" id="input_radio_freq" class="form-control form-control-lg" value="<?php echo htmlspecialchars($settings['radio_frequency'] ?? ''); ?>" placeholder="e.g. 90.4 MHz • Voice of Hope">
                                                    <div class="form-text text-muted small">Displays beneath the station title (e.g. FM frequency or broadcast motto).</div>
                                                </div>
                                            </div>

                                            <!-- Live Stream URL with Inline Tester -->
                                            <div class="col-12">
                                                <div class="p-3 border rounded-3 bg-white mb-2 shadow-sm">
                                                    <label class="form-label fw-bold d-flex align-items-center justify-content-between">
                                                        <span><i class="fas fa-link text-info me-1"></i> Live Audio Stream Link (URL) <span class="text-danger">*</span></span>
                                                        <span class="badge bg-blue-lt">Direct MP3 / AAC / Icecast / Shoutcast / OGG</span>
                                                    </label>
                                                    <div class="input-group input-group-flat mb-2">
                                                        <span class="input-group-text"><i class="fas fa-satellite-dish text-muted"></i></span>
                                                        <input type="url" name="radio_stream_url" id="admin_radio_stream_url" class="form-control" value="<?php echo htmlspecialchars($settings['radio_stream_url'] ?? ''); ?>" placeholder="https://stream.zeno.fm/... or https://your-icecast-server.com:8000/stream.mp3" required>
                                                        <button type="button" class="btn btn-outline-danger px-3 d-flex align-items-center gap-2" id="admin_test_stream_btn" title="Test audio stream directly in browser">
                                                            <i class="fas fa-play" id="admin_test_icon"></i> <span id="admin_test_text">Test Stream</span>
                                                        </button>
                                                    </div>
                                                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                                        <div class="text-muted small">
                                                            <i class="fas fa-info-circle me-1"></i> Enter a direct HTTPS or HTTP live audio stream URL. Make sure the server supports audio streaming over web browsers.
                                                        </div>
                                                        <div id="admin_stream_status" class="small fw-bold text-muted"></div>
                                                    </div>
                                                    <audio id="admin_preview_audio" preload="none" style="display:none;"></audio>
                                                </div>
                                            </div>

                                            <!-- Radio Logo Upload -->
                                            <div class="col-md-6">
                                                <div class="p-3 bg-light rounded-3 border h-100">
                                                    <label class="form-label fw-bold mb-3 d-flex align-items-center justify-content-between">
                                                        <span><i class="fas fa-image text-warning me-1"></i> Radio Station Logo / Artwork</span>
                                                        <span class="text-muted small">Upload Image (Max: 5MB)</span>
                                                    </label>
                                                    
                                                    <div class="d-flex align-items-center gap-3 mb-3">
                                                        <div class="border rounded-circle d-flex align-items-center justify-content-center bg-dark shadow-sm overflow-hidden position-relative" style="width: 90px; height: 90px; flex-shrink: 0; border: 2px solid rgba(255,255,255,0.15) !important;">
                                                            <?php 
                                                            $currentLogo = !empty($settings['radio_logo']) ? file_url($settings['radio_logo']) : (!empty($settings['radio_external_logo']) ? $settings['radio_external_logo'] : ''); 
                                                            ?>
                                                            <img id="radio-logo-preview" src="<?php echo $currentLogo; ?>" alt="Radio Logo" style="width: 100%; height: 100%; object-fit: cover; <?php echo empty($currentLogo) ? 'display:none;' : ''; ?>">
                                                            <span id="radio-logo-placeholder" class="text-white-50 text-center small fw-bold" style="<?php echo !empty($currentLogo) ? 'display:none;' : ''; ?>">
                                                                <i class="fas fa-radio fs-2 mb-1 d-block text-danger"></i>No Logo
                                                            </span>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <input type="file" name="radio_logo" id="radio_logo_input" class="form-control mb-2" accept="image/*">
                                                            <div class="text-muted small">Square recommended (500x500 PNG, JPG, or WEBP).</div>
                                                            <?php if (!empty($settings['radio_logo'])): ?>
                                                                <div class="form-check mt-2">
                                                                    <input class="form-check-input" type="checkbox" name="remove_radio_logo" id="remove_radio_logo" value="1">
                                                                    <label class="form-check-label text-danger small fw-semibold" for="remove_radio_logo">
                                                                        <i class="fas fa-trash-alt me-1"></i> Remove uploaded station logo
                                                                    </label>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>

                                                    <div class="mb-0">
                                                        <label class="form-label small text-muted fw-bold">Or External Logo Image URL</label>
                                                        <input type="url" name="radio_external_logo" id="input_external_logo" class="form-control form-control-sm" value="<?php echo htmlspecialchars($settings['radio_external_logo'] ?? ''); ?>" placeholder="https://example.com/images/radio-logo.png">
                                                        <div class="form-text text-muted" style="font-size: 0.75rem;">Used if no file is uploaded. If both are empty, the main NGO logo is used.</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Player Widget Preferences -->
                                            <div class="col-md-6">
                                                <div class="p-3 bg-light rounded-3 border h-100">
                                                    <label class="form-label fw-bold mb-3">
                                                        <i class="fas fa-sliders-h text-primary me-1"></i> Player Preferences & Position
                                                    </label>

                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold">Widget Screen Position</label>
                                                        <select name="radio_position" class="form-select">
                                                            <option value="bottom-left" <?php echo (($settings['radio_position'] ?? 'bottom-left') === 'bottom-left') ? 'selected' : ''; ?>>Bottom-Left Floating (Recommended, avoids WhatsApp)</option>
                                                            <option value="bottom-right" <?php echo (($settings['radio_position'] ?? '') === 'bottom-right') ? 'selected' : ''; ?>>Bottom-Right Floating (Above WhatsApp)</option>
                                                            <option value="bottom-bar" <?php echo (($settings['radio_position'] ?? '') === 'bottom-bar') ? 'selected' : ''; ?>>Bottom Dock Bar (Full Width)</option>
                                                        </select>
                                                        <div class="form-text text-muted small">Where the floating player pill appears on desktop & tablet screens.</div>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold d-flex justify-content-between">
                                                            <span>Default Volume</span>
                                                            <span id="volume_val_display" class="badge bg-secondary-lt"><?php echo htmlspecialchars($settings['radio_default_volume'] ?? '80'); ?>%</span>
                                                        </label>
                                                        <input type="range" name="radio_default_volume" id="radio_default_volume" class="form-range" min="10" max="100" step="5" value="<?php echo htmlspecialchars($settings['radio_default_volume'] ?? '80'); ?>" oninput="document.getElementById('volume_val_display').innerText = this.value + '%'">
                                                    </div>

                                                    <div class="form-check form-switch mb-0">
                                                        <input class="form-check-input" type="checkbox" name="radio_autoplay" id="radio_autoplay_switch" value="1" <?php echo (!empty($settings['radio_autoplay']) && $settings['radio_autoplay'] == '1') ? 'checked' : ''; ?>>
                                                        <label class="form-check-label fw-semibold" for="radio_autoplay_switch">
                                                            Attempt Autoplay
                                                        </label>
                                                        <div class="text-muted small">Note: Most modern mobile and desktop browsers block unmuted audio until the user interacts with the page.</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Live Mockup / Preview of Widget -->
                                            <div class="col-12">
                                                <div class="p-3 border rounded-3 bg-dark text-white">
                                                    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom border-secondary pb-2">
                                                        <span class="small fw-bold text-uppercase text-white-50 letter-spacing-1">
                                                            <i class="fas fa-eye text-info me-1"></i> Live Widget Mockup (Front-End Appearance)
                                                        </span>
                                                        <span class="badge bg-danger text-white"><i class="fas fa-circle me-1" style="font-size: 7px; animation: pulse 1.5s infinite;"></i> ON AIR</span>
                                                    </div>

                                                    <div class="d-flex align-items-center gap-3 p-3 rounded-pill" style="background: rgba(30, 41, 59, 0.95); border: 1px solid rgba(255,255,255,0.15); max-width: 380px; box-shadow: 0 10px 25px rgba(0,0,0,0.5);">
                                                        <div class="rounded-circle overflow-hidden d-flex align-items-center justify-content-center bg-black" style="width: 44px; height: 44px; flex-shrink: 0; border: 2px solid var(--accent, #f59e0b);">
                                                            <img id="preview_widget_logo" src="<?php echo !empty($currentLogo) ? $currentLogo : ''; ?>" style="width: 100%; height: 100%; object-fit: cover; <?php echo empty($currentLogo) ? 'display:none;' : ''; ?>">
                                                            <i id="preview_widget_icon" class="fas fa-broadcast-tower text-white" style="<?php echo !empty($currentLogo) ? 'display:none;' : ''; ?>"></i>
                                                        </div>
                                                        <div class="flex-grow-1 overflow-hidden" style="line-height: 1.2;">
                                                            <div id="preview_widget_title" class="fw-bold text-truncate text-white" style="font-size: 0.92rem;">
                                                                <?php echo htmlspecialchars($settings['radio_name'] ?? 'Community FM 90.4'); ?>
                                                            </div>
                                                            <div id="preview_widget_freq" class="text-white-50 text-truncate small" style="font-size: 0.75rem;">
                                                                <?php echo htmlspecialchars($settings['radio_frequency'] ?? '90.4 MHz • Live Stream'); ?>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <div class="d-flex align-items-end gap-1 px-1" style="height: 18px;">
                                                                <span class="bg-danger rounded-pill" style="width: 3px; height: 12px; display: inline-block;"></span>
                                                                <span class="bg-danger rounded-pill" style="width: 3px; height: 18px; display: inline-block;"></span>
                                                                <span class="bg-danger rounded-pill" style="width: 3px; height: 8px; display: inline-block;"></span>
                                                            </div>
                                                            <div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                                                <i class="fas fa-play" style="font-size: 0.8rem; margin-left: 2px;"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-4 pt-3 border-top d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary px-4 shadow-sm btn-lg d-flex align-items-center gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" /><path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M14 4l0 4l-6 0l0 -4" /></svg>
                                                Save Live Radio Settings
                                            </button>
                                        </div>
                                    </form>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require_once 'app/views/admin/layouts/footer.php'; ?>
</div>

<script>
    function setupLivePreview(inputId, previewImgId, placeholderId, maxSizeKB = null) {
        const input = document.getElementById(inputId);
        const previewImg = document.getElementById(previewImgId);
        const placeholder = document.getElementById(placeholderId);

        if (input) {
            input.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    if (maxSizeKB && file.size > maxSizeKB * 1024) {
                        showToast(`File is too large. Maximum size allowed is ${maxSizeKB}KB.`, 'error');
                        this.value = ''; // Clear input
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImg.src = e.target.result;
                        previewImg.style.display = 'block';
                        if (placeholder) placeholder.style.display = 'none';

                        // Also update mockup if radio logo
                        const mockImg = document.getElementById('preview_widget_logo');
                        const mockIcon = document.getElementById('preview_widget_icon');
                        if (mockImg && inputId === 'radio_logo_input') {
                            mockImg.src = e.target.result;
                            mockImg.style.display = 'block';
                            if (mockIcon) mockIcon.style.display = 'none';
                        }
                    }
                    reader.readAsDataURL(file);
                }
            });
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        // Tab Persistence
        const hash = window.location.hash;
        if (hash) {
            const triggerEl = document.querySelector(`.list-group-item[href="${hash}"]`);
            if (triggerEl) {
                bootstrap.Tab.getInstance(triggerEl)?.show() || new bootstrap.Tab(triggerEl).show();
            }
        }

        // Update hash on tab change
        const tabTriggers = document.querySelectorAll('.list-group-item[data-bs-toggle="tab"]');
        tabTriggers.forEach(trigger => {
            trigger.addEventListener('shown.bs.tab', (e) => {
                window.location.hash = e.target.getAttribute('href');
            });
        });

        // Setup image previews
        setupLivePreview('ngo_logo_input', 'logo-preview', 'logo-placeholder');
        setupLivePreview('ngo_favicon_input', 'favicon-preview', 'favicon-placeholder');
        setupLivePreview('ngo_signature_input', 'signature-preview', 'signature-placeholder');
        setupLivePreview('radio_logo_input', 'radio-logo-preview', 'radio-logo-placeholder');

        // External logo URL preview sync
        const extLogoInput = document.getElementById('input_external_logo');
        if (extLogoInput) {
            extLogoInput.addEventListener('input', function() {
                const url = this.value.trim();
                const previewImg = document.getElementById('radio-logo-preview');
                const placeholder = document.getElementById('radio-logo-placeholder');
                const mockImg = document.getElementById('preview_widget_logo');
                const mockIcon = document.getElementById('preview_widget_icon');

                if (url) {
                    if (previewImg) { previewImg.src = url; previewImg.style.display = 'block'; }
                    if (placeholder) { placeholder.style.display = 'none'; }
                    if (mockImg) { mockImg.src = url; mockImg.style.display = 'block'; }
                    if (mockIcon) { mockIcon.style.display = 'none'; }
                }
            });
        }

        // Mockup text sync
        const inputRadioName = document.getElementById('input_radio_name');
        const previewWidgetTitle = document.getElementById('preview_widget_title');
        if (inputRadioName && previewWidgetTitle) {
            inputRadioName.addEventListener('input', function() {
                previewWidgetTitle.innerText = this.value.trim() || 'Community FM 90.4';
            });
        }

        const inputRadioFreq = document.getElementById('input_radio_freq');
        const previewWidgetFreq = document.getElementById('preview_widget_freq');
        if (inputRadioFreq && previewWidgetFreq) {
            inputRadioFreq.addEventListener('input', function() {
                previewWidgetFreq.innerText = this.value.trim() || '90.4 MHz • Live Stream';
            });
        }

        // Admin Stream Test Audio
        const testBtn = document.getElementById('admin_test_stream_btn');
        const testIcon = document.getElementById('admin_test_icon');
        const testText = document.getElementById('admin_test_text');
        const testAudio = document.getElementById('admin_preview_audio');
        const testUrlInput = document.getElementById('admin_radio_stream_url');
        const testStatus = document.getElementById('admin_stream_status');

        if (testBtn && testAudio && testUrlInput) {
            let isTesting = false;

            testBtn.addEventListener('click', function() {
                const url = testUrlInput.value.trim();
                if (!url) {
                    alert('Please enter a stream URL first.');
                    testUrlInput.focus();
                    return;
                }

                if (!isTesting) {
                    isTesting = true;
                    testStatus.innerHTML = '<span class="text-warning"><i class="fas fa-spinner fa-spin me-1"></i> Connecting to audio stream...</span>';
                    testAudio.src = url;
                    testAudio.load();
                    testAudio.play().then(() => {
                        testIcon.className = 'fas fa-stop';
                        testText.innerText = 'Stop Audio';
                        testBtn.className = 'btn btn-danger px-3 d-flex align-items-center gap-2';
                        testStatus.innerHTML = '<span class="text-success"><i class="fas fa-volume-up me-1"></i> Playing stream successfully!</span>';
                    }).catch(err => {
                        isTesting = false;
                        testStatus.innerHTML = '<span class="text-danger"><i class="fas fa-exclamation-triangle me-1"></i> Failed to play stream: ' + err.message + '</span>';
                    });
                } else {
                    testAudio.pause();
                    testAudio.removeAttribute('src');
                    testAudio.load();
                    isTesting = false;
                    testIcon.className = 'fas fa-play';
                    testText.innerText = 'Test Stream';
                    testBtn.className = 'btn btn-outline-danger px-3 d-flex align-items-center gap-2';
                    testStatus.innerText = '';
                }
            });

            testAudio.addEventListener('error', function() {
                if (isTesting && testAudio.getAttribute('src')) {
                    testStatus.innerHTML = '<span class="text-danger"><i class="fas fa-times-circle me-1"></i> Stream connection error. Check URL.</span>';
                    isTesting = false;
                    testIcon.className = 'fas fa-play';
                    testText.innerText = 'Test Stream';
                    testBtn.className = 'btn btn-outline-danger px-3 d-flex align-items-center gap-2';
                }
            });
        }
        
        // Theme Presets functionality
        const presetButtons = document.querySelectorAll('.theme-preset');
        const primaryColorInput = document.getElementById('theme_primary_color');
        const secondaryColorInput = document.getElementById('theme_secondary_color');
        
        if(presetButtons.length > 0 && primaryColorInput && secondaryColorInput) {
            presetButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const primary = this.getAttribute('data-primary');
                    const secondary = this.getAttribute('data-secondary');
                    
                    if(primary) primaryColorInput.value = primary;
                    if(secondary) secondaryColorInput.value = secondary;
                });
            });
        }
    });
</script>

