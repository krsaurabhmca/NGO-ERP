<?php require_once 'app/views/layouts/header.php'; ?>



<!-- Hero / Slider Section -->
<?php if (!empty($slider)): ?>
    <div id="heroSlider" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
        <div class="carousel-inner">
            <?php foreach ($slider as $index => $slide): ?>
                <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                    <div class="hero-section text-center" style="background-image: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('<?php echo file_url($slide->file_path); ?>'); height: 500px;">
                        <div class="container-fluid px-lg-5">
                            <h1 class="display-4 fw-bold mb-3"><?php echo htmlspecialchars($slide->title); ?></h1>
                            <p class="fs-5 mb-4 opacity-75"><?php echo htmlspecialchars($slide->description); ?></p>
                            <div class="text-center mt-2">
                                <a href="<?php echo url('/donate'); ?>" class="btn btn-accent btn-lg rounded-pill d-inline-block" style="width: auto; min-width: 150px;">Donate Now</a>
                            </div>
                        </div>
                        <img src="<?php echo file_url($slide->file_path); ?>" alt="<?php echo htmlspecialchars($slide->title); ?>" class="hero-mobile-img d-none">
                        
                        <!-- Smooth Vector Wave -->
                        <div class="position-absolute bottom-0 w-100" style="z-index: 10; pointer-events: none; margin-bottom: -1px;">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 100" style="display: block; width: 100%; height: auto;">
                                <path fill="#fffff0" fill-opacity="1" d="M0,32L60,42.7C120,53,240,75,360,74.7C480,75,600,53,720,48C840,43,960,53,1080,58.7C1200,64,1320,64,1380,64L1440,64L1440,100L1380,100C1320,100,1200,100,1080,100C960,100,840,100,720,100C600,100,480,100,360,100C240,100,120,100,60,100L0,100Z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php if (count($slider) > 1): ?>
            <button class="carousel-control-prev" type="button" data-bs-target="#heroSlider" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroSlider" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        <?php endif; ?>
    </div>
<?php else: ?>
    <header class="hero-section text-center" style="height: 500px; position: relative;">
        <div class="container-fluid px-lg-5">
            <h1 class="display-4 fw-bold mb-3">Empowering Lives, Building Futures</h1>
            <p class="fs-5 mb-4 opacity-75">Join us in our mission to bring positive change to communities around the world.</p>
            <div class="text-center mt-2">
                <a href="<?php echo url('/donate'); ?>" class="btn btn-accent btn-lg rounded-pill d-inline-block" style="width: auto; min-width: 150px;">Donate Now</a>
            </div>
        </div>
        
        <!-- Smooth Vector Wave -->
        <div class="position-absolute bottom-0 w-100" style="z-index: 10; pointer-events: none; margin-bottom: -1px;">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 100" style="display: block; width: 100%; height: auto;">
                <path fill="#fffff0" fill-opacity="1" d="M0,32L60,42.7C120,53,240,75,360,74.7C480,75,600,53,720,48C840,43,960,53,1080,58.7C1200,64,1320,64,1380,64L1440,64L1440,100L1380,100C1320,100,1200,100,1080,100C960,100,840,100,720,100C600,100,480,100,360,100C240,100,120,100,60,100L0,100Z"></path>
            </svg>
        </div>
    </header>
<?php endif; ?>

<!-- Mission & Vision -->
<section class="py-5" style="background: #fffff0;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-5 mb-4 mb-lg-0">
                <?php if (!empty($aboutPage->image)): ?>
                    <img src="<?php echo file_url($aboutPage->image); ?>" class="img-fluid rounded-4 shadow-sm w-100" alt="Mission & Vision" style="max-height: 400px; object-fit: cover;">
                <?php else: ?>
                    <img src="<?php echo url('assets/images/placeholder.jpg'); ?>" class="img-fluid rounded-4 shadow-sm w-100" alt="About Us" style="max-height: 400px; object-fit: cover;">
                <?php endif; ?>
            </div>
            <div class="col-lg-7 ps-lg-5">
                <span class="badge px-3 py-2 rounded-pill mb-3 fw-semibold" style="background: rgba(13,148,136,0.12); color: var(--accent);">Who We Are</span>
                <h2 class="fw-bold display-6 mb-3" style="color: var(--primary);">About Us</h2>
                <p class="text-secondary lh-lg mb-4"><?php echo !empty($aboutPage->content) ? htmlspecialchars($aboutPage->content) : 'Our mission is to provide sustainable support and resources to underprivileged communities, focusing on education, healthcare, and economic empowerment.'; ?></p>
                <div class="mt-4">
                    <a href="<?php echo url('/about'); ?>" class="btn rounded-pill px-4 py-2 fw-semibold" style="border: 2px solid var(--accent); color: var(--accent);">
                        Learn More <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>



<!-- Recent Projects -->
<?php if (!empty($recentProjects)): ?>
<section class="py-5" style="background: #fffff0;">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge px-3 py-2 rounded-pill mb-3 fw-semibold" style="background: rgba(0,53,102,0.1); color: var(--primary);">Our Impact</span>
            <h2 class="fw-bold display-6" style="color: var(--primary);">Recent Projects</h2>
            <p class="text-muted">See how we're making a difference through our ongoing initiatives.</p>
        </div>
        <div class="row g-4">
            <?php foreach ($recentProjects as $project): ?>
            <div class="col-md-6 col-lg-4">
                <a href="<?php echo url('projects/' . $project->slug); ?>" class="text-decoration-none">
                    <div class="card border-0 shadow-sm hover-lift rounded-4 overflow-hidden h-100">
                        <div style="height: 180px; overflow: hidden; position: relative;">
                            <?php if ($project->image): ?>
                                <img src="<?php echo file_url($project->image); ?>" class="w-100 h-100" alt="<?php echo htmlspecialchars($project->title); ?>" style="object-fit: cover; transition: transform 0.4s ease;">
                            <?php else: ?>
                                <div class="w-100 h-100 d-flex align-items-center justify-content-center" style="background-color: var(--slate-100); border: 2px solid var(--primary);">
                                    <i class="fas fa-project-diagram fa-3x" style="color: var(--primary); opacity: 0.2;"></i>
                                </div>
                            <?php endif; ?>
                            <div class="position-absolute top-0 start-0 m-2">
                                <span class="badge px-2 py-1 rounded-pill fw-semibold text-white" style="background: <?php echo $project->status === 'completed' ? '#2ecc71' : 'var(--primary)'; ?>; font-size: 0.65rem;"><?php echo ucfirst($project->status); ?></span>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <h5 class="fw-bold mb-1 text-dark" style="font-size: 0.95rem;"><?php echo htmlspecialchars($project->title); ?></h5>
                            <p class="text-muted small mb-0" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;"><?php echo htmlspecialchars(substr(strip_tags($project->description ?? ''), 0, 80)); ?></p>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-4">
            <a href="<?php echo url('/projects'); ?>" class="btn rounded-pill px-4 fw-semibold" style="border: 2px solid var(--primary); color: var(--primary);">
                View All Projects <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>

<?php endif; ?>

<!-- CTA Section -->
<section class="py-5" style="background-color: var(--primary); background-image: radial-gradient(circle at 10% 20%, var(--accent) 0%, transparent 50%), radial-gradient(circle at 90% 80%, var(--accent) 0%, transparent 50%), radial-gradient(circle at 50% 50%, var(--primary-dark) 0%, transparent 70%);">
    <div class="container text-center">
        <h2 class="fw-bold display-6 text-white mb-3">Want to Make a Difference?</h2>
        <p class="text-white-50 fs-5 mb-4 mx-auto" style="max-width: 600px;">Join hands with us to create lasting change. Every contribution, big or small, makes an impact.</p>
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="<?php echo url('/donate'); ?>" class="btn btn-accent btn-lg rounded-pill d-inline-flex align-items-center justify-content-center fw-bold" style="width: auto; min-width: 160px;">
                <i class="fas fa-heart me-2" style="color: var(--primary);"></i> Donate Now
            </a>
            <a href="<?php echo url('/members/register'); ?>" class="btn btn-outline-light btn-lg rounded-pill d-inline-flex align-items-center justify-content-center fw-semibold" style="width: auto; min-width: 160px;">
                <i class="fas fa-user-plus me-2"></i> Become a Member
            </a>
        </div>
    </div>
</section>

<!-- Active Campaigns -->
<?php if (!empty($activeCampaigns)): ?>
<section class="py-5" style="background: #fffff0;">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge px-3 py-2 rounded-pill mb-3 fw-semibold" style="background: rgba(13,148,136,0.12); color: var(--accent);">Support Us</span>
            <h2 class="fw-bold display-6" style="color: var(--primary);">Active Campaigns</h2>
            <p class="text-muted">Join hands to fund our initiatives. Every contribution counts.</p>
        </div>
        <div class="row g-4">
            <?php foreach ($activeCampaigns as $campaign): ?>
                <?php $cProgress = $campaign->goal_amount > 0 ? min(100, round(($campaign->raised_amount / $campaign->goal_amount) * 100)) : 0; ?>
            <div class="col-md-6 col-lg-4">
                <a href="<?php echo url('campaigns/' . $campaign->slug); ?>" class="text-decoration-none">
                    <div class="card border-0 shadow-sm hover-lift rounded-4 overflow-hidden h-100">
                        <div style="height: 180px; overflow: hidden; position: relative;">
                            <?php if ($campaign->image): ?>
                                <img src="<?php echo file_url($campaign->image); ?>" class="w-100 h-100" alt="<?php echo htmlspecialchars($campaign->title); ?>" style="object-fit: cover; transition: transform 0.4s ease;">
                            <?php else: ?>
                                <div class="w-100 h-100 d-flex align-items-center justify-content-center" style="background-color: var(--slate-100);">
                                    <i class="fas fa-hand-holding-heart fa-3x" style="color: var(--accent); opacity: 0.2;"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="card-body p-3">
                            <h5 class="fw-bold mb-1 text-dark" style="font-size: 0.95rem;"><?php echo htmlspecialchars($campaign->title); ?></h5>
                            <p class="text-muted small mb-2" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;"><?php echo htmlspecialchars(substr($campaign->description ?? '', 0, 80)); ?></p>
                            <div class="d-flex justify-content-between align-items-center small mb-1">
                                <span class="fw-bold" style="color: var(--primary);">₹<?php echo number_format($campaign->raised_amount); ?></span>
                                <span class="text-muted" style="font-size: 0.75rem;"><?php echo $cProgress; ?>% funded</span>
                            </div>
                            <div class="progress" style="height: 5px; border-radius: 1rem;">
                                <div class="progress-bar" style="width: <?php echo $cProgress; ?>%; background-color: var(--primary); border-radius: 1rem;"></div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-4">
            <a href="<?php echo url('/campaigns'); ?>" class="btn rounded-pill px-4 fw-semibold" style="border: 2px solid var(--accent); color: var(--accent);">
                View All Campaigns <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>

<?php endif; ?>

<!-- Gallery, Certificates & Achievements -->
<section class="py-5" style="background: #fffff0;">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge px-3 py-2 rounded-pill mb-3 fw-semibold" style="background: rgba(255,191,0,0.15); color: #B8860B;">Our Work</span>
            <h2 class="fw-bold display-6" style="color: var(--primary);">Gallery & Recognitions</h2>
            <p class="text-muted">Explore our moments, certifications, and milestones.</p>
        </div>
        <div class="row g-4">
            <div class="col-12 col-md-4">
                <a href="<?php echo url('/gallery'); ?>" class="text-decoration-none">
                    <div class="card border-0 shadow-sm hover-lift rounded-4 overflow-hidden text-center p-4 h-100" style="background-color: transparent; border: 1px solid var(--primary);">
                        <div class="icon-circle mx-auto mb-3" style="width: 64px; height: 64px; background: rgba(124,58,237,0.1);">
                            <i class="fas fa-images fa-2x" style="color: #7c3aed;"></i>
                        </div>
                        <h5 class="fw-bold mb-0" style="color: var(--primary);">Photo Gallery</h5>
                    </div>
                </a>
            </div>
            <div class="col-12 col-md-4">
                <a href="<?php echo url('/certificates'); ?>" class="text-decoration-none">
                    <div class="card border-0 shadow-sm hover-lift rounded-4 overflow-hidden text-center p-4 h-100" style="background-color: transparent; border: 1px solid var(--accent);">
                        <div class="icon-circle mx-auto mb-3" style="width: 64px; height: 64px; background: rgba(255,191,0,0.15);">
                            <i class="fas fa-certificate fa-2x" style="color: var(--accent);"></i>
                        </div>
                        <h5 class="fw-bold mb-0" style="color: var(--primary);">Certifications</h5>
                    </div>
                </a>
            </div>
            <div class="col-12 col-md-4">
                <a href="<?php echo url('/achievements'); ?>" class="text-decoration-none">
                    <div class="card border-0 shadow-sm hover-lift rounded-4 overflow-hidden text-center p-4 h-100" style="background-color: transparent; border: 1px solid var(--accent);">
                        <div class="icon-circle mx-auto mb-3" style="width: 64px; height: 64px; background: rgba(13,148,136,0.12);">
                            <i class="fas fa-trophy fa-2x" style="color: var(--accent);"></i>
                        </div>
                        <h5 class="fw-bold mb-0" style="color: var(--primary);">Achievements</h5>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Latest News -->
<?php if (!empty($recentNews)): ?>
<section class="py-5" style="background: #fffff0;">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge px-3 py-2 rounded-pill mb-3 fw-semibold" style="background: rgba(255,191,0,0.15); color: #B8860B;">Stay Updated</span>
            <h2 class="fw-bold display-6" style="color: var(--primary);">Latest News</h2>
            <p class="text-muted">Read about our recent activities and announcements.</p>
        </div>
        <div class="row g-4">
            <?php foreach ($recentNews as $news): ?>
            <div class="col-md-6 col-lg-4">
                <a href="<?php echo url('news/' . $news->slug); ?>" class="text-decoration-none">
                    <div class="card border-0 shadow-sm hover-lift rounded-4 overflow-hidden h-100">
                        <div style="height: 180px; overflow: hidden; position: relative;">
                            <?php if ($news->image): ?>
                                <img src="<?php echo file_url($news->image); ?>" class="w-100 h-100" alt="<?php echo htmlspecialchars($news->title); ?>" style="object-fit: cover; transition: transform 0.4s ease;">
                            <?php else: ?>
                                <div class="w-100 h-100 d-flex align-items-center justify-content-center" style="background-color: var(--slate-100);">
                                    <i class="fas fa-newspaper fa-3x" style="color: var(--accent); opacity: 0.2;"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="card-body p-3">
                            <div class="text-muted mb-1" style="font-size: 0.7rem;">
                                <i class="far fa-calendar-alt me-1" style="color: var(--accent);"></i> <?php echo date('d M Y', strtotime($news->created_at)); ?>
                            </div>
                            <h5 class="fw-bold mb-1 text-dark" style="font-size: 0.95rem;"><?php echo htmlspecialchars($news->title); ?></h5>
                            <p class="text-muted small mb-0" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;"><?php echo htmlspecialchars(substr(strip_tags($news->content ?? ''), 0, 80)); ?></p>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-4">
            <a href="<?php echo url('/news'); ?>" class="btn rounded-pill px-4 fw-semibold" style="border: 2px solid #B8860B; color: #B8860B;">
                All News <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>

<?php endif; ?>

<!-- Partner Logo Marquee -->
<?php
$partnerData = [];
try {
    $pdo = \App\Core\Database::getInstance();
    $stmt = $pdo->query("SELECT * FROM partners ORDER BY created_at DESC");
    $partnerData = $stmt->fetchAll(\PDO::FETCH_OBJ);
} catch (\Exception $e) {}
?>
<?php if (!empty($partnerData)): ?>
<section class="py-4" style="background: #fffff0; border-top: 1px solid #e5e7eb;">
    <div class="container-fluid px-0">
        <div class="text-center mb-3">
            <span class="badge px-4 py-2 rounded-pill mb-2 fw-bold" style="background: rgba(0,53,102,0.1); color: var(--primary); font-size: 0.9rem; letter-spacing: 1px;">Our Partners</span>
        </div>
        <div style="overflow: hidden;">
            <div class="d-flex align-items-center" id="partnersMarquee" style="animation: scrollPartners 20s linear infinite; width: fit-content;">
                <?php for ($dup = 0; $dup < 6; $dup++): ?>
                <?php foreach ($partnerData as $partner): ?>
                    <div class="text-center flex-shrink-0 px-3" style="min-width: 140px; max-width: 200px;">
                        <?php if (!empty($partner->website)): ?>
                            <a href="<?php echo htmlspecialchars($partner->website); ?>" target="_blank" rel="noopener" class="text-decoration-none">
                        <?php endif; ?>
                        <?php if (!empty($partner->logo)): ?>
                            <img src="<?php echo file_url($partner->logo); ?>" alt="<?php echo htmlspecialchars($partner->name); ?>" style="max-height: 90px; max-width: 100%; object-fit: contain; opacity: 0.75; transition: opacity 0.3s, transform 0.3s;" onmouseover="this.style.opacity='1'; this.style.transform='scale(1.05)';" onmouseout="this.style.opacity='0.75'; this.style.transform='scale(1)';">
                        <?php else: ?>
                            <span class="fw-bold d-block" style="color: var(--primary); font-size: 1rem; line-height: 90px;"><?php echo htmlspecialchars($partner->name); ?></span>
                        <?php endif; ?>
                        <?php if (!empty($partner->website)): ?>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
                <?php endfor; ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>



<?php require_once 'app/views/layouts/footer.php'; ?>
