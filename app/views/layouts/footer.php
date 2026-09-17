<footer class="mt-0 position-relative overflow-hidden pt-5" style="color: #fff;">
    <!-- Smooth Vector Wave -->
    <div class="position-absolute top-0 w-100" style="z-index: 0; pointer-events: none; transform: rotate(180deg); margin-top: -1px;">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 100" style="display: block; width: 100%; height: auto;">
            <path fill="#fffff0" fill-opacity="1" d="M0,32L60,42.7C120,53,240,75,360,74.7C480,75,600,53,720,48C840,43,960,53,1080,58.7C1200,64,1320,64,1380,64L1440,64L1440,100L1380,100C1320,100,1200,100,1080,100C960,100,840,100,720,100C600,100,480,100,360,100C240,100,120,100,60,100L0,100Z"></path>
        </svg>
    </div>
    <div class="container-fluid px-lg-5 position-relative mt-4" style="z-index: 1;">
        <div class="row g-4 py-3">
            <div class="col-lg-3">
                <div class="mb-2">
                    <?php if (!empty($globalSettings['ngo_logo'])): ?>
                        <img src="<?php echo file_url($globalSettings['ngo_logo']); ?>" alt="Logo" class="mb-2 bg-white p-1.5 rounded-2 shadow-sm" style="max-height: 44px; filter: none !important; object-fit: contain;">
                    <?php else: ?>
                        <h5 class="fw-bold text-white mb-0"><i class="fas fa-hand-holding-heart me-2" style="color: var(--accent);"></i> <?php echo !empty($globalSettings['ngo_name']) ? $globalSettings['ngo_name'] : 'NGO HELP'; ?></h5>
                    <?php endif; ?>
                </div>
                <hr style="border-color: rgba(255,255,255,0.12); margin: 0.6rem 0;">
                <h6 class="fw-bold text-white mb-2" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px;">Social Links</h6>
                <div class="d-flex gap-2">
                    <?php
                    $socialIcons = [
                        'facebook'  => 'fab fa-facebook-f',
                        'twitter'   => 'fab fa-twitter',
                        'instagram' => 'fab fa-instagram',
                        'linkedin'  => 'fab fa-linkedin-in',
                        'youtube'   => 'fab fa-youtube',
                    ];
                    foreach ($socialIcons as $key => $icon):
                        $url = $globalSettings['social_' . $key] ?? '';
                        if (!empty($url)):
                    ?>
                        <a href="<?php echo htmlspecialchars($url); ?>" target="_blank" class="d-flex align-items-center justify-content-center rounded-circle text-decoration-none" style="width: 32px; height: 32px; background: rgba(255,255,255,0.15); color: #fff; font-size: 0.8rem; transition: all 0.25s ease;" onmouseover="this.style.background='var(--accent)';" onmouseout="this.style.background='rgba(255,255,255,0.15)';"><i class="<?php echo $icon; ?>"></i></a>
                    <?php
                        endif;
                    endforeach;
                    ?>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <h6 class="fw-bold text-white mb-2" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px;">Links</h6>
                <ul class="list-unstyled text-white" style="font-size: 0.85rem;">
                    <li class="mb-1"><a href="<?php echo url('/'); ?>" class="text-white text-decoration-none" onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='#fffff0'"><i class="fas fa-chevron-right me-1" style="font-size: 0.6rem; color: var(--accent);"></i> Home</a></li>
                    <li class="mb-1"><a href="<?php echo url('/about'); ?>" class="text-white text-decoration-none" onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='#fffff0'"><i class="fas fa-chevron-right me-1" style="font-size: 0.6rem; color: var(--accent);"></i> About Us</a></li>
                    <li class="mb-1"><a href="<?php echo url('/projects'); ?>" class="text-white text-decoration-none" onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='#fffff0'"><i class="fas fa-chevron-right me-1" style="font-size: 0.6rem; color: var(--accent);"></i> Projects</a></li>
                    <li class="mb-1"><a href="<?php echo url('/campaigns'); ?>" class="text-white text-decoration-none" onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='#fffff0'"><i class="fas fa-chevron-right me-1" style="font-size: 0.6rem; color: var(--accent);"></i> Crowdfunding</a></li>
                    <li class="mb-1"><a href="<?php echo url('/gallery'); ?>" class="text-white text-decoration-none" onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='#fffff0'"><i class="fas fa-chevron-right me-1" style="font-size: 0.6rem; color: var(--accent);"></i> Gallery</a></li>
                    <li class="mb-1"><a href="<?php echo url('/contact'); ?>" class="text-white text-decoration-none" onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='#fffff0'"><i class="fas fa-chevron-right me-1" style="font-size: 0.6rem; color: var(--accent);"></i> Contact</a></li>
                </ul>
            </div>
            <div class="col-6 col-lg-3">
                <h6 class="fw-bold text-white mb-2" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px;">Legal</h6>
                <ul class="list-unstyled text-white" style="font-size: 0.85rem;">
                    <li class="mb-1"><a href="<?php echo url('/privacy-policy'); ?>" class="text-white text-decoration-none" onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='#fffff0'"><i class="fas fa-chevron-right me-1" style="font-size: 0.6rem; color: var(--accent);"></i> Privacy Policy</a></li>
                    <li class="mb-1"><a href="<?php echo url('/terms-and-conditions'); ?>" class="text-white text-decoration-none" onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='#fffff0'"><i class="fas fa-chevron-right me-1" style="font-size: 0.6rem; color: var(--accent);"></i> Terms & Conditions</a></li>
                    <li class="mb-1"><a href="<?php echo url('/refund-policy'); ?>" class="text-white text-decoration-none" onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='#fffff0'"><i class="fas fa-chevron-right me-1" style="font-size: 0.6rem; color: var(--accent);"></i> Refund Policy</a></li>
                </ul>
            </div>
            <div class="col-6 col-lg-3">
                <h6 class="fw-bold text-white mb-2" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px;">Contact</h6>
                <ul class="list-unstyled text-white" style="font-size: 0.85rem;">
                    <?php if (!empty($globalSettings['ngo_address'])): ?>
                    <li class="mb-2 d-flex">
                        <div class="me-2 mt-1" style="min-width: 14px; color: var(--accent);"><i class="fas fa-map-marker-alt"></i></div>
                        <span class="text-white"><?php echo htmlspecialchars($globalSettings['ngo_address']); ?></span>
                    </li>
                    <?php endif; ?>
                    <?php if (!empty($globalSettings['ngo_phone'])): ?>
                    <li class="mb-2 d-flex">
                        <div class="me-2 mt-1" style="min-width: 14px; color: var(--accent);"><i class="fas fa-phone"></i></div>
                        <span class="text-white"><?php echo htmlspecialchars($globalSettings['ngo_phone']); ?></span>
                    </li>
                    <?php endif; ?>
                    <?php if (!empty($globalSettings['ngo_email'])): ?>
                    <li class="mb-2 d-flex">
                        <div class="me-2 mt-1" style="min-width: 14px; color: var(--accent);"><i class="fas fa-envelope"></i></div>
                        <span class="text-white"><?php echo htmlspecialchars($globalSettings['ngo_email']); ?></span>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>

        </div>

        <hr style="border-color: rgba(255,255,255,0.12); margin: 0;">

        <div class="text-center py-2">
            <p class="mb-0 text-white" style="font-size: 0.78rem;">
                <?php if (!empty($globalSettings['ngo_footer_credits'])): ?>
                    <?php echo $globalSettings['ngo_footer_credits']; ?>
                <?php else: ?>
                    &copy; <?php echo date('Y'); ?> <?php echo !empty($globalSettings['ngo_name']) ? htmlspecialchars($globalSettings['ngo_name']) : 'NGO HELP'; ?>. All rights reserved.
                <?php endif; ?>
            </p>
        </div>
    </div>
</footer>



<!-- Floating WhatsApp Button -->
<?php
$waNumber = !empty($globalSettings['ngo_whatsapp']) ? preg_replace('/[^0-9]/', '', $globalSettings['ngo_whatsapp']) : (!empty($globalSettings['ngo_phone']) ? preg_replace('/[^0-9]/', '', $globalSettings['ngo_phone']) : '1234567890');
$waMessage = 'Hello! I would like to know more about your services.';
?>
<a href="https://wa.me/<?php echo $waNumber; ?>?text=<?php echo urlencode($waMessage); ?>" target="_blank" class="whatsapp-float" title="Chat on WhatsApp">
    <i class="fab fa-whatsapp"></i>
</a>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var csrfMeta = document.querySelector('meta[name="csrf-token"]');
    if (csrfMeta) {
        var token = csrfMeta.getAttribute('content');
        document.querySelectorAll('form[method="POST"]').forEach(function(f) {
            if (f.querySelector('[name="_csrf_token"]')) return;
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = '_csrf_token';
            input.value = token;
            f.appendChild(input);
        });
    }

    var myCarousel = document.getElementById('heroSlider');
    if (myCarousel) {
        new bootstrap.Carousel(myCarousel, {
            interval: 5000,
            ride: 'carousel'
        });
    }
});
</script>
</body>
</html>



