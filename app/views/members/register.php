<?php require_once 'app/views/layouts/header.php'; ?>

<section class="position-relative overflow-hidden"
    style="background: linear-gradient(135deg, #003566 0%, #00224d 100%);">
    <div class="container-fluid px-lg-5 py-5 text-center position-relative" style="z-index: 1;">
        <h1 class="fw-bold display-5 text-white mb-0"><?php echo $title; ?></h1>
        <p class="text-white-50 mb-0">Join our community and make a difference.</p>
    </div>
    <div class="position-absolute top-0 end-0 opacity-10 hero-svg-circle">
        <svg width="400" height="400" viewBox="0 0 400 400" fill="none">
            <circle cx="300" cy="100" r="200" fill="#FFBF00" />
            <circle cx="100" cy="350" r="150" fill="#0d9488" />
        </svg>
    </div>
    <div class="position-absolute bottom-0 start-0 opacity-10 hero-svg-circle">
        <svg width="300" height="300" viewBox="0 0 300 300" fill="none">
            <circle cx="50" cy="250" r="120" fill="#FFBF00" />
        </svg>
    </div>
</section>

<section class="py-5" style="background: #fffff0;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4"
                        role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?php echo e($_SESSION['error']);
                        unset($_SESSION['error']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['success'])):
                    $showSuccess = true; ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['rejection'])):
                    $showRejection = true; ?>
                <?php endif; ?>

                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="p-4 text-white text-center"
                        style="background: linear-gradient(135deg, #003566 0%, #00224d 100%);">
                        <div class="icon-circle bg-white bg-opacity-10 mx-auto mb-3" style="width: 64px; height: 64px;">
                            <i class="fas fa-user-plus" style="color: #FFBF00; font-size: 1.5rem;"></i>
                        </div>
                        <h4 class="fw-bold mb-1">Become a Member</h4>
                        <p class="text-white-50 mb-0 small">Fill in your details below to join our community</p>
                    </div>
                    <div class="card-body p-4 p-lg-5">
                        <form action="<?php echo url('/members/register'); ?>" method="POST"
                            enctype="multipart/form-data">
                            <?php echo csrf_field('members/register'); ?>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Full Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control"
                                        placeholder="Enter your full name" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Email Address <span
                                            class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control" placeholder="you@example.com"
                                        required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Phone Number <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="phone" class="form-control" placeholder="+91 98765 43210"
                                        required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Gender <span
                                            class="text-danger">*</span></label>
                                    <select name="gender" class="form-select" required>
                                        <option value="">Select Gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Date of Birth <span
                                            class="text-danger">*</span></label>
                                    <input type="date" name="dob" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Blood Group <span
                                            class="text-danger">*</span></label>
                                    <select name="blood_group" class="form-select" required>
                                        <option value="">Select Blood Group</option>
                                        <option value="A+">A+</option>
                                        <option value="A-">A-</option>
                                        <option value="B+">B+</option>
                                        <option value="B-">B-</option>
                                        <option value="AB+">AB+</option>
                                        <option value="AB-">AB-</option>
                                        <option value="O+">O+</option>
                                        <option value="O-">O-</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Occupation <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="occupation" class="form-control"
                                        placeholder="Your occupation" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Request Designation <span
                                            class="text-danger">*</span></label>
                                    <select name="designation_id" class="form-select" required>
                                        <option value="">Select Designation</option>
                                        <?php if (!empty($designations)): ?>
                                            <?php foreach ($designations as $designation): ?>
                                                <option value="<?php echo $designation->id; ?>">
                                                    <?php echo htmlspecialchars($designation->name); ?>
                                                    (<?php echo '₹' . number_format($designation->monthly_amount, 2); ?>/month)
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Upload Your Photo <span
                                            class="text-danger">*</span> <span class="text-muted fw-normal">(Max 500
                                            KB)</span></label>
                                    <input type="file" name="image" class="form-control" accept="image/*" required>
                                    <small class="form-text text-muted">Passport-size photo preferred. JPG, PNG
                                        accepted.</small>
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-semibold">Address Line <span
                                            class="text-danger">*</span></label>
                                    <textarea name="address_line" class="form-control" rows="2"
                                        placeholder="Street, locality, landmark, etc." required></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">City / Village <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="city" class="form-control"
                                        placeholder="Enter city or village" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">State <span
                                            class="text-danger">*</span></label>
                                    <select name="state" class="form-select" data-state-district="true"
                                        data-district-id="register_district" required>
                                        <option value="">Select State</option>
                                        <?php $statesList = require BASE_PATH . 'app/config/states_districts.php'; ?>
                                        <?php foreach ($statesList as $stateName => $districts): ?>
                                            <option value="<?php echo e($stateName); ?>"><?php echo e($stateName); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">District <span
                                            class="text-danger">*</span></label>
                                    <select name="district" id="register_district" class="form-select" disabled
                                        required>
                                        <option value="">First select a state</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">PIN Code <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="pin" class="form-control" placeholder="Enter PIN code"
                                        required>
                                </div>
                                <div class="col-12 mt-3">
                                    <div class="p-3 bg-light rounded-3 border">
                                        <label class="form-label fw-semibold small mb-2">CAPTCHA Verification <span
                                                class="text-danger">*</span></label>
                                        <div class="d-flex align-items-center gap-3 flex-wrap">
                                            <span class="badge bg-dark fs-5 px-3 py-2"
                                                style="letter-spacing: 3px; user-select: none;"><?php echo captcha_question(); ?></span>
                                            <span class="text-muted small">= ?</span>
                                            <input type="number" name="_captcha" class="form-control"
                                                style="max-width: 100px;" placeholder="Answer" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 mt-3 text-center">
                                    <button type="submit"
                                        class="btn btn-accent btn-lg px-5 rounded-pill fw-bold shadow-sm">
                                        <i class="fas fa-paper-plane me-2"></i> Submit Registration
                                    </button>
                                    <p class="text-muted small mt-3 mb-0">You will receive a verification email. Please
                                        verify your email before our team reviews your application.</p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Success Modal -->
<?php if (!empty($showSuccess)): ?>
    <div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-body text-center py-5 px-4">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-success bg-opacity-10 mb-3"
                        style="width: 72px; height: 72px;">
                        <i class="fas fa-check-circle text-success" style="font-size: 2rem;"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Submission Complete!</h5>
                    <p class="text-muted mb-1" style="font-size: 0.95rem;">
                        <?php echo e($_SESSION['success']);
                        unset($_SESSION['success']); ?></p>
                    <p class="text-muted mb-4" style="font-size: 0.9rem;">Keep an eye on your mail for further updates.</p>
                    <button type="button" class="btn btn-accent rounded-pill px-4 fw-semibold"
                        data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var modal = new bootstrap.Modal(document.getElementById('successModal'));
            modal.show();
        });
    </script>
<?php endif; ?>

<!-- Rejection Modal -->
<?php if (!empty($showRejection)): ?>
    <div class="modal fade" id="rejectionModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-body text-center py-5 px-4">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-danger bg-opacity-10 mb-3"
                        style="width: 72px; height: 72px;">
                        <i class="fas fa-times-circle text-danger" style="font-size: 2rem;"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Registration Failed</h5>
                    <p class="text-muted mb-4" style="font-size: 0.95rem;">
                        <?php echo e($_SESSION['rejection']);
                        unset($_SESSION['rejection']); ?></p>
                    <button type="button" class="btn btn-danger rounded-pill px-4 fw-semibold"
                        data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var modal = new bootstrap.Modal(document.getElementById('rejectionModal'));
            modal.show();
        });
    </script>
<?php endif; ?>

<script>
    const STATES_DISTRICTS = <?php echo json_encode(require BASE_PATH . 'app/config/states_districts.php'); ?>;
</script>
<script src="<?php echo url('assets/js/states-districts.js'); ?>"></script>

<?php require_once 'app/views/layouts/footer.php'; ?>