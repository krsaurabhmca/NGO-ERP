<footer class="mt-0 position-relative overflow-hidden" style="background: linear-gradient(135deg, #003566 0%, #001a3a 100%); color: #fff; border-top: 4px solid #0d9488;">
    <div class="container-fluid px-lg-5 position-relative" style="z-index: 1;">
        <div class="row g-4 py-3">
            <div class="col-lg-3">
                <div class="mb-2">
                    <?php if (!empty($globalSettings['ngo_logo'])): ?>
                        <img src="<?php echo file_url($globalSettings['ngo_logo']); ?>" alt="Logo" class="mb-2 bg-white p-1.5 rounded-2 shadow-sm" style="max-height: 44px; filter: none !important; object-fit: contain;">
                    <?php else: ?>
                        <h5 class="fw-bold text-white mb-0"><i class="fas fa-hand-holding-heart me-2" style="color: #0d9488;"></i> <?php echo !empty($globalSettings['ngo_name']) ? $globalSettings['ngo_name'] : 'NGO HELP'; ?></h5>
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
                        <a href="<?php echo htmlspecialchars($url); ?>" target="_blank" class="d-flex align-items-center justify-content-center rounded-circle text-decoration-none" style="width: 32px; height: 32px; background: rgba(255,255,255,0.15); color: #fff; font-size: 0.8rem; transition: all 0.25s ease;" onmouseover="this.style.background='#0d9488';" onmouseout="this.style.background='rgba(255,255,255,0.15)';"><i class="<?php echo $icon; ?>"></i></a>
                    <?php
                        endif;
                    endforeach;
                    ?>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <h6 class="fw-bold text-white mb-2" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px;">Links</h6>
                <ul class="list-unstyled text-white" style="font-size: 0.85rem;">
                    <li class="mb-1"><a href="<?php echo url('/'); ?>" class="text-white text-decoration-none" onmouseover="this.style.color='#0d9488'" onmouseout="this.style.color='#fffff0'"><i class="fas fa-chevron-right me-1" style="font-size: 0.6rem; color: #0d9488;"></i> Home</a></li>
                    <li class="mb-1"><a href="<?php echo url('/about'); ?>" class="text-white text-decoration-none" onmouseover="this.style.color='#0d9488'" onmouseout="this.style.color='#fffff0'"><i class="fas fa-chevron-right me-1" style="font-size: 0.6rem; color: #0d9488;"></i> About Us</a></li>
                    <li class="mb-1"><a href="<?php echo url('/projects'); ?>" class="text-white text-decoration-none" onmouseover="this.style.color='#0d9488'" onmouseout="this.style.color='#fffff0'"><i class="fas fa-chevron-right me-1" style="font-size: 0.6rem; color: #0d9488;"></i> Projects</a></li>
                    <li class="mb-1"><a href="<?php echo url('/campaigns'); ?>" class="text-white text-decoration-none" onmouseover="this.style.color='#0d9488'" onmouseout="this.style.color='#fffff0'"><i class="fas fa-chevron-right me-1" style="font-size: 0.6rem; color: #0d9488;"></i> Crowdfunding</a></li>
                    <li class="mb-1"><a href="<?php echo url('/gallery'); ?>" class="text-white text-decoration-none" onmouseover="this.style.color='#0d9488'" onmouseout="this.style.color='#fffff0'"><i class="fas fa-chevron-right me-1" style="font-size: 0.6rem; color: #0d9488;"></i> Gallery</a></li>
                    <li class="mb-1"><a href="<?php echo url('/contact'); ?>" class="text-white text-decoration-none" onmouseover="this.style.color='#0d9488'" onmouseout="this.style.color='#fffff0'"><i class="fas fa-chevron-right me-1" style="font-size: 0.6rem; color: #0d9488;"></i> Contact</a></li>
                </ul>
            </div>
            <div class="col-6 col-lg-3">
                <h6 class="fw-bold text-white mb-2" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px;">Legal</h6>
                <ul class="list-unstyled text-white" style="font-size: 0.85rem;">
                    <li class="mb-1"><a href="<?php echo url('/privacy-policy'); ?>" class="text-white text-decoration-none" onmouseover="this.style.color='#0d9488'" onmouseout="this.style.color='#fffff0'"><i class="fas fa-chevron-right me-1" style="font-size: 0.6rem; color: #0d9488;"></i> Privacy Policy</a></li>
                    <li class="mb-1"><a href="<?php echo url('/terms-and-conditions'); ?>" class="text-white text-decoration-none" onmouseover="this.style.color='#0d9488'" onmouseout="this.style.color='#fffff0'"><i class="fas fa-chevron-right me-1" style="font-size: 0.6rem; color: #0d9488;"></i> Terms & Conditions</a></li>
                    <li class="mb-1"><a href="<?php echo url('/refund-policy'); ?>" class="text-white text-decoration-none" onmouseover="this.style.color='#0d9488'" onmouseout="this.style.color='#fffff0'"><i class="fas fa-chevron-right me-1" style="font-size: 0.6rem; color: #0d9488;"></i> Refund Policy</a></li>
                </ul>
            </div>
            <div class="col-6 col-lg-3">
                <h6 class="fw-bold text-white mb-2" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px;">Contact</h6>
                <ul class="list-unstyled text-white" style="font-size: 0.85rem;">
                    <?php if (!empty($globalSettings['ngo_address'])): ?>
                    <li class="mb-2 d-flex">
                        <div class="me-2 mt-1" style="min-width: 14px; color: #0d9488;"><i class="fas fa-map-marker-alt"></i></div>
                        <span class="text-white"><?php echo htmlspecialchars($globalSettings['ngo_address']); ?></span>
                    </li>
                    <?php endif; ?>
                    <?php if (!empty($globalSettings['ngo_phone'])): ?>
                    <li class="mb-2 d-flex">
                        <div class="me-2 mt-1" style="min-width: 14px; color: #0d9488;"><i class="fas fa-phone"></i></div>
                        <span class="text-white"><?php echo htmlspecialchars($globalSettings['ngo_phone']); ?></span>
                    </li>
                    <?php endif; ?>
                    <?php if (!empty($globalSettings['ngo_email'])): ?>
                    <li class="mb-2 d-flex">
                        <div class="me-2 mt-1" style="min-width: 14px; color: #0d9488;"><i class="fas fa-envelope"></i></div>
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



