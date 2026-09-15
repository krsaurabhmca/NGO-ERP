<?php require_once 'app/views/layouts/header.php'; ?>

<section class="position-relative overflow-hidden"
    style="background: linear-gradient(135deg, #003566 0%, #00224d 100%);">
    <div class="container-fluid px-lg-5 py-5 text-center position-relative" style="z-index: 1;">
        <h1 class="display-5 fw-bold text-white mb-0">Crowdfunding Campaigns</h1>
        <p class="text-white-50 mb-0">Join hands to fund our initiatives. Every contribution counts.</p>
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
        <?php if (!empty($campaigns)): ?>
            <div class="row g-4">
                <?php foreach ($campaigns as $campaign): ?>
                    <?php
                    $progress = $campaign->goal_amount > 0 ? min(100, round(($campaign->raised_amount / $campaign->goal_amount) * 100)) : 0;
                    $daysLeft = '';
                    $urgent = false;
                    if ($campaign->end_date) {
                        $end = new DateTime($campaign->end_date);
                        $now = new DateTime();
                        $diff = $now->diff($end);
                        if ($end > $now) {
                            $daysLeft = $diff->days . ' day' . ($diff->days > 1 ? 's' : '') . ' left';
                            $urgent = $diff->days <= 7;
                        } else {
                            $daysLeft = 'Ended';
                        }
                    }
                    ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm overflow-hidden hover-lift rounded-4">
                            <div class="position-relative overflow-hidden" style="height: 200px;">
                                <?php if ($campaign->image): ?>
                                    <img src="<?php echo file_url($campaign->image); ?>" class="w-100 h-100"
                                        alt="<?php echo htmlspecialchars($campaign->title); ?>"
                                        style="object-fit: cover; transition: transform 0.4s ease;">
                                <?php else: ?>
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center"
                                        style="background: linear-gradient(135deg, #00356610, #0d948810);">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="48" height="48"
                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="#003566" fill="none"
                                            stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.3;">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M3 17a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                                            <path d="M21 17a3 3 0 1 0 -6 0" />
                                            <path d="M9 17l6 0" />
                                            <path d="M9 17v-9" />
                                            <path d="M15 17v-9" />
                                            <path d="M12 3l6 4" />
                                            <path d="M12 3l-6 4" />
                                        </svg>
                                    </div>
                                <?php endif; ?>
                                <?php if ($daysLeft): ?>
                                    <div class="position-absolute top-0 end-0 m-3">
                                        <span class="badge px-3 py-2 rounded-pill fw-semibold shadow-sm"
                                            style="<?php echo $urgent ? 'background: #e74c3c;' : ($daysLeft === 'Ended' ? 'background: #95a5a6;' : 'background: #003566;'); ?> font-size: 0.7rem;">
                                            <?php if ($urgent): ?><i
                                                    class="fas fa-exclamation-circle me-1"></i><?php endif; ?><?php echo $daysLeft; ?>
                                        </span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="card-body p-4 d-flex flex-column">
                                <h3 class="fw-bold h5 mb-2"><?php echo htmlspecialchars($campaign->title); ?></h3>
                                <p class="text-muted small mb-3 flex-grow-1">
                                    <?php echo htmlspecialchars(substr($campaign->description ?? 'No description available.', 0, 100)); ?>...
                                </p>
                                <div class="mt-auto">
                                    <div class="d-flex justify-content-between small mb-1">
                                        <span class="fw-bold"
                                            style="color: #003566;"><?php echo '₹'; ?><?php echo number_format($campaign->raised_amount); ?></span>
                                        <span class="text-muted"><?php echo $progress; ?>%</span>
                                    </div>
                                    <div class="progress mb-3" style="height: 8px; border-radius: 1rem;">
                                        <div class="progress-bar"
                                            style="width: <?php echo $progress; ?>%; background: linear-gradient(90deg, #003566, #0d9488); border-radius: 1rem;">
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between text-muted mb-3" style="font-size: 0.72rem;">
                                        <span>Goal:
                                            <?php echo '₹'; ?>        <?php echo number_format($campaign->goal_amount); ?></span>
                                        <span><i class="fas fa-users me-1"></i> <?php echo $campaign->donor_count ?? 0; ?>
                                            donors</span>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a href="<?php echo url('campaigns/' . $campaign->slug); ?>"
                                            class="btn btn-outline-secondary rounded-pill flex-fill"
                                            style="font-size: 0.85rem;">Details</a>
                                        <a href="<?php echo url('campaigns/' . $campaign->slug . '#donate'); ?>"
                                            class="btn btn-accent rounded-pill flex-fill" style="font-size: 0.85rem;"><i
                                                class="fas fa-heart me-1"></i> Donate</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <div class="icon-circle bg-primary-lt mx-auto mb-3" style="width: 80px; height: 80px;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="40" height="40" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="#003566" fill="none" stroke-linecap="round" stroke-linejoin="round"
                        style="opacity: 0.5;">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M3 17a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                        <path d="M21 17a3 3 0 1 0 -6 0" />
                        <path d="M9 17l6 0" />
                        <path d="M9 17v-9" />
                        <path d="M15 17v-9" />
                        <path d="M12 3l6 4" />
                        <path d="M12 3l-6 4" />
                    </svg>
                </div>
                <h4 class="fw-bold text-dark">No Active Campaigns</h4>
                <p class="text-muted">Check back soon for new fundraising initiatives.</p>
            </div>
        <?php endif; ?>
    </div>
</section>



<?php require_once 'app/views/layouts/footer.php'; ?>