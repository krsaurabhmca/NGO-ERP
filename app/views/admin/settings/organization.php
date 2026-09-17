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
                                <a href="#tab-branding" class="list-group-item list-group-item-action d-flex align-items-center" data-bs-toggle="tab">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z" /><path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" /></svg>
                                    Branding Settings
                                </a>
                                <a href="#tab-social" class="list-group-item list-group-item-action d-flex align-items-center" data-bs-toggle="tab">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" /><path d="M8 11l0 5" /><path d="M8 8l0 .01" /><path d="M12 16l0 -5" /><path d="M16 16v-3a2 2 0 0 0 -4 0" /></svg>
                                    Social Links
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
                                                            <input type="file" name="ngo_logo" id="ngo_logo_input" class="form-control" accept="image/*" max="512000">
                                                            <div class="text-muted mt-2 small">PNG or SVG with transparent background preferred (Max: 500KB).</div>
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
                                                            <input type="file" name="ngo_favicon" id="ngo_favicon_input" class="form-control" accept="image/x-icon,image/png" max="512000">
                                                            <div class="text-muted mt-2 small">Browser tab icon. 32x32 px PNG or ICO (Max: 500KB).</div>
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
                                                            <input type="file" name="ngo_signature" id="ngo_signature_input" class="form-control" accept="image/png,image/jpeg" max="512000">
                                                            <div class="text-muted mt-2 small">For ID cards & certificates. Transparent PNG preferred (Max: 500KB).</div>
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

        setupLivePreview('ngo_logo_input', 'logo-preview', 'logo-placeholder', 200);
        setupLivePreview('ngo_favicon_input', 'favicon-preview', 'favicon-placeholder', 50);
        setupLivePreview('ngo_signature_input', 'signature-preview', 'signature-placeholder', 200);
        
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
