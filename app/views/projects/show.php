<?php require_once 'app/views/layouts/header.php'; ?>

<section class="position-relative overflow-hidden" style="background: linear-gradient(135deg, #003566 0%, #00224d 100%);">
    <div class="container-fluid px-lg-5 py-5 text-center position-relative" style="z-index: 1;">
        <h1 class="display-5 fw-bold text-white mb-0"><?php echo htmlspecialchars($project->title); ?></h1>
    </div>
    <div class="position-absolute top-0 end-0 opacity-10 hero-svg-circle">
        <svg width="400" height="400" viewBox="0 0 400 400" fill="none"><circle cx="300" cy="100" r="200" fill="#FFBF00"/><circle cx="100" cy="350" r="150" fill="#0d9488"/></svg>
    </div>
    <div class="position-absolute bottom-0 start-0 opacity-10 hero-svg-circle">
        <svg width="300" height="300" viewBox="0 0 300 300" fill="none"><circle cx="50" cy="250" r="120" fill="#FFBF00"/></svg>
    </div>
</section>

<section class="py-5" style="background: #fffff0;">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                    <?php
                        $heroShown = false;
                        if (!empty($project->video_url)):
                            $videoUrl = $project->video_url;
                            $embedUrl = '';
                            if (strpos($videoUrl, 'youtube.com') !== false || strpos($videoUrl, 'youtu.be') !== false) {
                                if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $videoUrl, $match)) {
                                    $embedUrl = 'https://www.youtube.com/embed/' . $match[1];
                                }
                            } elseif (strpos($videoUrl, 'vimeo.com') !== false) {
                                if (preg_match('%^https?://(?:www\.)?vimeo\.com/(?:channels/(?:\w+/)?|groups/([^/]*)/videos/|album/(\d+)/video/|)(\d+)(?:$|/|\?)%i', $videoUrl, $match)) {
                                    $embedUrl = 'https://player.vimeo.com/video/' . $match[3];
                                }
                            }
                            if ($embedUrl):
                                $heroShown = true;
                    ?>
                        <div class="ratio ratio-16x9">
                            <iframe src="<?php echo $embedUrl; ?>" allowfullscreen></iframe>
                        </div>
                    <?php
                            endif;
                        endif;
                        if (!$heroShown && $project->image):
                    ?>
                        <img src="<?php echo file_url($project->image); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($project->title); ?>" style="max-height: 450px; object-fit: cover;">
                    <?php endif; ?>
                    <div class="card-body p-4 p-lg-5">
                        <div class="d-flex flex-wrap align-items-center gap-3 mb-4 pb-3 border-bottom text-muted" style="font-size: 0.85rem;">
                            <div class="d-flex align-items-center gap-2">
                                <div class="text-center" style="min-width: 24px; color: #0d9488;"><i class="far fa-calendar-alt"></i></div>
                                <div><small class="text-muted d-block" style="font-size: 0.7rem;">STARTED</small><span class="fw-semibold text-dark"><?php echo date('d M, Y', strtotime($project->start_date)); ?></span></div>
                            </div>
                            <?php if($project->end_date): ?>
                            <div class="d-flex align-items-center gap-2">
                                <div class="text-center" style="min-width: 24px; color: #0d9488;"><i class="fas fa-flag-checkered"></i></div>
                                <div><small class="text-muted d-block" style="font-size: 0.7rem;"><?php echo $project->status === 'completed' ? 'COMPLETED' : 'EXPECTED END'; ?></small><span class="fw-semibold text-dark"><?php echo date('d M, Y', strtotime($project->end_date)); ?></span></div>
                            </div>
                            <?php endif; ?>
                            <span class="badge px-3 py-2 rounded-pill fw-semibold" style="<?php echo $project->status === 'completed' ? 'background: #2ecc7120; color: #2ecc71;' : ($project->status === 'ongoing' ? 'background: #00356615; color: #003566;' : 'background: #FFBF0020; color: #b89400;'); ?> font-size: 0.75rem;">
                                <?php echo ucfirst($project->status); ?>
                            </span>
                        </div>
                        <h2 class="fw-bold h4 mb-3" style="color: #003566;">About the Project</h2>
                        <div class="lh-lg text-muted" style="text-align: justify;">
                            <?php echo nl2br(htmlspecialchars($project->description)); ?>
                        </div>
                    </div>
                </div>

                <?php if (!empty($gallery)): ?>
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                    <div class="card-body p-4 p-lg-5">
                        <h4 class="fw-bold mb-3" style="color: #003566;"><i class="fas fa-images me-2" style="color: #0d9488;"></i> Project Gallery</h4>
                        <div class="row g-2">
                            <?php foreach ($gallery as $image): ?>
                                <div class="col-4 col-sm-3">
                                    <a href="<?php echo file_url($image->image_path); ?>" target="_blank" class="gallery-link">
                                        <img src="<?php echo file_url($image->image_path); ?>" class="img-fluid rounded-3 shadow-sm gallery-img" alt="Gallery Image" style="aspect-ratio: 1/1; object-fit: cover; border: 2px solid #eee;">
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <a href="<?php echo url('/projects'); ?>" class="btn btn-outline-secondary rounded-pill px-4">
                    <i class="fas fa-arrow-left me-2"></i> Back to All Projects
                </a>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4" style="position: sticky; top: 100px; background: linear-gradient(135deg, #003566, #00224d);">
                    <div class="text-white text-center">
                        <div class="icon-circle mx-auto mb-3" style="width: 56px; height: 56px; background: rgba(255,255,255,0.1);">
                            <i class="fas fa-heart" style="color: #FFBF00; font-size: 1.2rem;"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Support This Project</h5>
                        <p class="text-white-50 small mb-3">Your contribution helps us create lasting impact.</p>
                        <a href="<?php echo url('/donate'); ?>" class="btn btn-accent rounded-pill px-4 fw-bold w-100">
                            <i class="fas fa-heart me-2" style="color: #003566;"></i> Donate Now
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



<?php require_once 'app/views/layouts/footer.php'; ?>
