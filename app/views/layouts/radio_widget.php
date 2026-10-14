<?php
/**
 * Live Radio & FM Web Player Widget
 * Professional, Responsive, High-Performance Streaming Widget
 */

// Check if Live Radio is configured and enabled
$radioEnabled = (!empty($globalSettings['radio_enabled']) && $globalSettings['radio_enabled'] == '1')
    || (!isset($globalSettings['radio_enabled']) && !empty($globalSettings['radio_stream_url']));

if (!$radioEnabled || empty($globalSettings['radio_stream_url'])) {
    return;
}

$radioName = !empty($globalSettings['radio_name']) ? htmlspecialchars($globalSettings['radio_name']) : 'Live Radio FM';
$radioFreq = !empty($globalSettings['radio_frequency']) ? htmlspecialchars($globalSettings['radio_frequency']) : 'Live Broadcast';
$radioStreamUrl = htmlspecialchars($globalSettings['radio_stream_url']);
$radioPosition = !empty($globalSettings['radio_position']) ? $globalSettings['radio_position'] : 'bottom-left';
$radioDefaultVolume = !empty($globalSettings['radio_default_volume']) ? (int)$globalSettings['radio_default_volume'] : 80;
$radioAutoplay = !empty($globalSettings['radio_autoplay']) && $globalSettings['radio_autoplay'] == '1';

// Determine logo URL
$radioLogoUrl = '';
if (!empty($globalSettings['radio_logo'])) {
    $radioLogoUrl = file_url($globalSettings['radio_logo']);
} elseif (!empty($globalSettings['radio_external_logo'])) {
    $radioLogoUrl = $globalSettings['radio_external_logo'];
} elseif (!empty($globalSettings['ngo_logo'])) {
    $radioLogoUrl = file_url($globalSettings['ngo_logo']);
}
$ngoOrgName = !empty($globalSettings['ngo_name']) ? htmlspecialchars($globalSettings['ngo_name']) : 'NGO Live';
?>

<!-- ================= LIVE RADIO PLAYER WIDGET ================= -->
<div id="ngo-radio-container" class="ngo-radio-pos-<?php echo htmlspecialchars($radioPosition); ?>" data-stream="<?php echo $radioStreamUrl; ?>" data-volume="<?php echo $radioDefaultVolume; ?>" data-autoplay="<?php echo $radioAutoplay ? '1' : '0'; ?>" data-station="<?php echo $radioName; ?>" data-org="<?php echo $ngoOrgName; ?>" data-logo="<?php echo $radioLogoUrl; ?>">

    <!-- Backdrop Scrim (Mobile only when expanded) -->
    <div id="ngo-radio-backdrop" class="ngo-radio-backdrop"></div>

    <!-- 1. EXPANDED STUDIO CARD -->
    <div id="ngo-radio-card" class="ngo-radio-card" aria-hidden="true">
        <!-- Top Bar -->
        <div class="ngo-card-topbar">
            <div class="ngo-live-indicator">
                <span class="ngo-live-beacon"></span>
                <span class="ngo-live-text">ON AIR</span>
                <span class="ngo-stream-chip" id="ngo-card-status-chip">FM LIVE</span>
            </div>
            <div class="ngo-card-actions">
                <button type="button" class="ngo-icon-btn" id="ngo-radio-popout-btn" title="Open Pop-out Floating Player (Plays continuously while you browse)" aria-label="Pop-out Player">
                    <i class="fas fa-up-right-from-square"></i>
                </button>
                <button type="button" class="ngo-icon-btn" id="ngo-radio-reload-btn" title="Refresh Live Stream" aria-label="Refresh Stream">
                    <i class="fas fa-arrows-rotate"></i>
                </button>
                <button type="button" class="ngo-icon-btn" id="ngo-radio-minimize-btn" title="Minimize Player" aria-label="Minimize Player">
                    <i class="fas fa-chevron-down"></i>
                </button>
            </div>
        </div>

        <!-- Center Showcase: Vinyl Artwork & Details -->
        <div class="ngo-card-body">
            <div class="ngo-artwork-wrapper">
                <div class="ngo-vinyl-disc" id="ngo-vinyl-disc">
                    <div class="ngo-vinyl-grooves"></div>
                    <div class="ngo-vinyl-center">
                        <?php if (!empty($radioLogoUrl)): ?>
                            <img src="<?php echo $radioLogoUrl; ?>" alt="<?php echo $radioName; ?>" class="ngo-vinyl-img" onerror="this.style.display='none'; document.getElementById('ngo-vinyl-fallback').style.display='flex';">
                        <?php endif; ?>
                        <div id="ngo-vinyl-fallback" class="ngo-vinyl-fallback" style="<?php echo !empty($radioLogoUrl) ? 'display:none;' : 'display:flex;'; ?>">
                            <i class="fas fa-radio"></i>
                        </div>
                    </div>
                </div>
                <div class="ngo-pulse-ring"></div>
            </div>

            <!-- Station Info -->
            <div class="ngo-station-info">
                <h4 class="ngo-station-title text-truncate" title="<?php echo $radioName; ?>"><?php echo $radioName; ?></h4>
                <div class="ngo-station-freq text-truncate"><?php echo $radioFreq; ?></div>
                <div class="ngo-status-msg" id="ngo-status-msg">Click play to listen live</div>
            </div>

            <!-- Dynamic Equalizer Frequency Bars -->
            <div class="ngo-eq-visualizer" id="ngo-eq-visualizer">
                <span class="ngo-eq-bar" style="--d: 0.1s; --h: 35%;"></span>
                <span class="ngo-eq-bar" style="--d: 0.3s; --h: 75%;"></span>
                <span class="ngo-eq-bar" style="--d: 0.15s; --h: 50%;"></span>
                <span class="ngo-eq-bar" style="--d: 0.4s; --h: 90%;"></span>
                <span class="ngo-eq-bar" style="--d: 0.25s; --h: 60%;"></span>
                <span class="ngo-eq-bar" style="--d: 0.5s; --h: 100%;"></span>
                <span class="ngo-eq-bar" style="--d: 0.2s; --h: 70%;"></span>
                <span class="ngo-eq-bar" style="--d: 0.35s; --h: 85%;"></span>
                <span class="ngo-eq-bar" style="--d: 0.45s; --h: 40%;"></span>
                <span class="ngo-eq-bar" style="--d: 0.18s; --h: 65%;"></span>
                <span class="ngo-eq-bar" style="--d: 0.32s; --h: 95%;"></span>
                <span class="ngo-eq-bar" style="--d: 0.22s; --h: 45%;"></span>
            </div>
        </div>

        <!-- Card Controls (Play / Pause & Volume) -->
        <div class="ngo-card-controls">
            <!-- Main Play/Pause Button -->
            <button type="button" class="ngo-play-btn-large" id="ngo-card-play-btn" aria-label="Play or Pause Radio">
                <span class="ngo-play-icon-wrap">
                    <i class="fas fa-play" id="ngo-card-play-icon"></i>
                </span>
                <span class="ngo-btn-ripple"></span>
            </button>

            <!-- Volume Row -->
            <div class="ngo-volume-row">
                <button type="button" class="ngo-vol-btn" id="ngo-card-vol-btn" title="Mute / Unmute" aria-label="Mute / Unmute">
                    <i class="fas fa-volume-high" id="ngo-vol-icon"></i>
                </button>
                <div class="ngo-slider-wrap">
                    <input type="range" class="ngo-slider" id="ngo-card-vol-slider" min="0" max="100" value="<?php echo $radioDefaultVolume; ?>" aria-label="Volume Slider">
                    <div class="ngo-slider-fill" id="ngo-slider-fill" style="width: <?php echo $radioDefaultVolume; ?>%;"></div>
                </div>
                <span class="ngo-vol-pct" id="ngo-vol-pct"><?php echo $radioDefaultVolume; ?>%</span>
            </div>

            <!-- Card Footer Links -->
            <div class="ngo-card-footer-links">
                <span class="ngo-engine-badge" id="ngo-engine-badge"><i class="fas fa-shield-halved me-1"></i>Protected Stream</span>
                <a href="<?php echo $radioStreamUrl; ?>" target="_blank" rel="noopener noreferrer" class="ngo-direct-url-link" title="Open direct audio stream URL in new browser tab">
                    <i class="fas fa-arrow-up-right-from-square me-1"></i>Direct URL
                </a>
            </div>
        </div>
    </div>

    <!-- 2. COLLAPSED FLOATING MINI PILL (DOCK) -->
    <div id="ngo-radio-mini" class="ngo-radio-mini" role="region" aria-label="Live Radio Mini Player">
        <!-- Station Logo / Icon Avatar -->
        <div class="ngo-mini-avatar" id="ngo-mini-expand-trigger" title="Expand Radio Player">
            <?php if (!empty($radioLogoUrl)): ?>
                <img src="<?php echo $radioLogoUrl; ?>" alt="Logo" class="ngo-mini-img" onerror="this.style.display='none'; document.getElementById('ngo-mini-fallback-icon').style.display='block';">
            <?php endif; ?>
            <i id="ngo-mini-fallback-icon" class="fas fa-broadcast-tower" style="<?php echo !empty($radioLogoUrl) ? 'display:none;' : 'display:block;'; ?>"></i>
            <span class="ngo-mini-pulse"></span>
        </div>

        <!-- Info Area (Click to expand) -->
        <div class="ngo-mini-info" id="ngo-mini-info-trigger" title="Expand Radio Player">
            <div class="ngo-mini-badge-row">
                <span class="ngo-mini-dot"></span>
                <span class="ngo-mini-badge-text">LIVE</span>
                <span class="ngo-mini-live-tag" id="ngo-mini-live-status">FM ON AIR</span>
            </div>
            <div class="ngo-mini-title text-truncate"><?php echo $radioName; ?></div>
        </div>

        <!-- Mini Equalizer Waves -->
        <div class="ngo-mini-waves" id="ngo-mini-waves" title="Live Broadcast">
            <span class="ngo-mwave"></span>
            <span class="ngo-mwave"></span>
            <span class="ngo-mwave"></span>
        </div>

        <!-- Quick Action Play/Pause Button -->
        <button type="button" class="ngo-mini-play-btn" id="ngo-mini-play-btn" title="Play / Pause Radio" aria-label="Play or Pause Radio">
            <i class="fas fa-play" id="ngo-mini-play-icon"></i>
        </button>

        <!-- Expand Arrow Button -->
        <button type="button" class="ngo-mini-expand-btn" id="ngo-mini-chevron-btn" title="Open Studio Player" aria-label="Open Studio Player">
            <i class="fas fa-chevron-up"></i>
        </button>
    </div>

    <!-- High-Performance Native Live Audio Streaming Element -->
    <audio id="ngo-audio-element" preload="auto" playsinline></audio>
</div>

<!-- ================= STYLES ================= -->
<style>
/* Base Container & Positioning */
#ngo-radio-container {
    position: fixed;
    z-index: 9995;
    font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    user-select: none;
    -webkit-tap-highlight-color: transparent;
}

/* Position Variants */
#ngo-radio-container.ngo-radio-pos-bottom-left {
    bottom: 24px;
    left: 24px;
}
#ngo-radio-container.ngo-radio-pos-bottom-right {
    bottom: 92px; /* Positioned cleanly above WhatsApp button */
    right: 24px;
}
#ngo-radio-container.ngo-radio-pos-bottom-bar {
    bottom: 0;
    left: 0;
    right: 0;
    display: flex;
    justify-content: center;
}

/* Backdrop for Mobile */
.ngo-radio-backdrop {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.65);
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
    z-index: 9993;
    opacity: 0;
    transition: opacity 0.3s ease;
}
.ngo-radio-backdrop.ngo-active {
    display: block;
    opacity: 1;
}

/* ================= 1. MINI FLOATING PILL ================= */
.ngo-radio-mini {
    display: flex;
    align-items: center;
    gap: 10px;
    background: linear-gradient(135deg, rgba(15, 23, 42, 0.94), rgba(30, 41, 59, 0.94));
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid rgba(255, 255, 255, 0.14);
    box-shadow: 0 14px 35px rgba(0, 0, 0, 0.45), 0 0 0 1px rgba(255, 255, 255, 0.05);
    border-radius: 50px;
    padding: 6px 10px 6px 8px;
    color: #fff;
    cursor: pointer;
    transition: transform 0.25s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow 0.25s ease, opacity 0.25s ease;
    max-width: 320px;
}
.ngo-radio-mini:hover {
    transform: translateY(-2px);
    box-shadow: 0 18px 40px rgba(0, 0, 0, 0.55), 0 0 15px rgba(239, 68, 68, 0.25);
    border-color: rgba(239, 68, 68, 0.4);
}
.ngo-radio-mini.ngo-hidden {
    display: none !important;
}

/* Mini Avatar */
.ngo-mini-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #0f172a;
    border: 2px solid #ef4444;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    position: relative;
    overflow: hidden;
    box-shadow: 0 0 10px rgba(239, 68, 68, 0.4);
}
.ngo-mini-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.ngo-mini-avatar i {
    font-size: 1.1rem;
    color: #ef4444;
}
.ngo-mini-pulse {
    position: absolute;
    inset: -2px;
    border-radius: 50%;
    border: 2px solid #ef4444;
    opacity: 0;
    animation: ngoRingPulse 2s cubic-bezier(0.215, 0.61, 0.355, 1) infinite;
}

/* Mini Info */
.ngo-mini-info {
    flex-grow: 1;
    min-width: 0;
    line-height: 1.25;
}
.ngo-mini-badge-row {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 0.65rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.8px;
}
.ngo-mini-dot {
    width: 7px;
    height: 7px;
    background: #ef4444;
    border-radius: 50%;
    display: inline-block;
    animation: ngoDotBlink 1.4s ease-in-out infinite;
}
.ngo-mini-badge-text {
    color: #ef4444;
}
.ngo-mini-live-tag {
    color: rgba(255, 255, 255, 0.65);
    font-size: 0.62rem;
    font-weight: 600;
}
.ngo-mini-title {
    font-weight: 700;
    font-size: 0.86rem;
    color: #ffffff;
    letter-spacing: -0.01em;
    max-width: 150px;
}

/* Mini Equalizer Waves */
.ngo-mini-waves {
    display: flex;
    align-items: flex-end;
    gap: 2.5px;
    height: 16px;
    padding: 0 2px;
}
.ngo-mwave {
    width: 3px;
    background: #ef4444;
    border-radius: 2px;
    height: 4px;
    transition: height 0.2s ease;
}
.ngo-playing .ngo-mwave:nth-child(1) { animation: ngoWave 0.8s ease-in-out infinite alternate; }
.ngo-playing .ngo-mwave:nth-child(2) { animation: ngoWave 0.6s ease-in-out infinite 0.2s alternate; }
.ngo-playing .ngo-mwave:nth-child(3) { animation: ngoWave 0.9s ease-in-out infinite 0.4s alternate; }

/* Mini Play Button */
.ngo-mini-play-btn {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: linear-gradient(135deg, #ef4444, #dc2626);
    border: none;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.45);
    transition: transform 0.2s ease, background 0.2s ease;
}
.ngo-mini-play-btn:hover {
    transform: scale(1.08);
    background: linear-gradient(135deg, #f87171, #ef4444);
}
.ngo-mini-play-btn i {
    font-size: 0.85rem;
    margin-left: 1px;
}
.ngo-mini-play-btn i.fa-pause {
    margin-left: 0;
}

/* Mini Expand Chevron */
.ngo-mini-expand-btn {
    background: transparent;
    border: none;
    color: rgba(255, 255, 255, 0.6);
    font-size: 0.75rem;
    padding: 4px 6px;
    cursor: pointer;
    transition: color 0.2s ease;
}
.ngo-mini-expand-btn:hover {
    color: #fff;
}

/* ================= 2. EXPANDED STUDIO CARD ================= */
.ngo-radio-card {
    display: none;
    width: 330px;
    background: linear-gradient(165deg, rgba(15, 23, 42, 0.97) 0%, rgba(30, 41, 59, 0.96) 100%);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.14);
    box-shadow: 0 24px 60px rgba(0, 0, 0, 0.65), 0 0 0 1px rgba(255, 255, 255, 0.08);
    border-radius: 24px;
    padding: 20px;
    color: #fff;
    transform-origin: bottom left;
    animation: ngoCardIn 0.35s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
.ngo-radio-card.ngo-active {
    display: block;
}

/* Top Bar */
.ngo-card-topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
}
.ngo-live-indicator {
    display: flex;
    align-items: center;
    gap: 7px;
}
.ngo-live-beacon {
    width: 8px;
    height: 8px;
    background: #ef4444;
    border-radius: 50%;
    box-shadow: 0 0 8px #ef4444;
    animation: ngoDotBlink 1.4s infinite;
}
.ngo-live-text {
    font-weight: 800;
    font-size: 0.74rem;
    letter-spacing: 1px;
    color: #ef4444;
}
.ngo-stream-chip {
    font-size: 0.65rem;
    font-weight: 700;
    background: rgba(255, 255, 255, 0.1);
    color: rgba(255, 255, 255, 0.85);
    padding: 2px 7px;
    border-radius: 10px;
    letter-spacing: 0.5px;
}
.ngo-card-actions {
    display: flex;
    align-items: center;
    gap: 6px;
}
.ngo-icon-btn {
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: rgba(255, 255, 255, 0.7);
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 0.75rem;
    transition: all 0.2s ease;
}
.ngo-icon-btn:hover {
    background: rgba(255, 255, 255, 0.18);
    color: #fff;
    transform: scale(1.05);
}

/* Body / Artwork */
.ngo-card-body {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    margin-bottom: 16px;
}
.ngo-artwork-wrapper {
    position: relative;
    width: 104px;
    height: 104px;
    margin-bottom: 14px;
}
.ngo-vinyl-disc {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background: radial-gradient(circle, #27272a 0%, #18181b 40%, #09090b 100%);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.6), inset 0 0 0 2px rgba(255, 255, 255, 0.1);
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 3px solid rgba(239, 68, 68, 0.6);
}
.ngo-playing .ngo-vinyl-disc {
    animation: ngoSpin 12s linear infinite;
    border-color: #ef4444;
    box-shadow: 0 0 25px rgba(239, 68, 68, 0.45);
}
.ngo-vinyl-grooves {
    position: absolute;
    inset: 8px;
    border-radius: 50%;
    border: 1px dashed rgba(255, 255, 255, 0.12);
    pointer-events: none;
}
.ngo-vinyl-center {
    width: 58px;
    height: 58px;
    border-radius: 50%;
    overflow: hidden;
    background: #09090b;
    border: 2px solid rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    z-index: 2;
}
.ngo-vinyl-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.ngo-vinyl-fallback {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ef4444;
    font-size: 1.5rem;
}
.ngo-pulse-ring {
    position: absolute;
    inset: -6px;
    border-radius: 50%;
    border: 2px solid rgba(239, 68, 68, 0.35);
    opacity: 0;
    pointer-events: none;
}
.ngo-playing .ngo-pulse-ring {
    animation: ngoRingPulse 2.2s cubic-bezier(0.2, 0.8, 0.2, 1) infinite;
}

/* Station Details */
.ngo-station-info {
    width: 100%;
    margin-bottom: 12px;
}
.ngo-station-title {
    font-size: 1.15rem;
    font-weight: 800;
    color: #fff;
    margin: 0 0 4px;
    letter-spacing: -0.02em;
}
.ngo-station-freq {
    font-size: 0.82rem;
    color: rgba(255, 255, 255, 0.65);
    font-weight: 500;
    margin-bottom: 4px;
}
.ngo-status-msg {
    font-size: 0.72rem;
    color: #ef4444;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    min-height: 18px;
}

/* Equalizer Visualizer */
.ngo-eq-visualizer {
    display: flex;
    align-items: flex-end;
    justify-content: center;
    gap: 4px;
    height: 28px;
    width: 100%;
    padding: 0 10px;
}
.ngo-eq-bar {
    flex: 1;
    max-width: 5px;
    background: linear-gradient(180deg, #ef4444 0%, #f97316 100%);
    border-radius: 3px;
    height: 5px;
    transition: height 0.25s ease;
}
.ngo-playing .ngo-eq-bar {
    animation: ngoEqBounce 1.2s ease-in-out infinite alternate;
    animation-delay: var(--d);
}

/* Card Controls */
.ngo-card-controls {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 14px;
    border-top: 1px solid rgba(255, 255, 255, 0.08);
    padding-top: 16px;
}
.ngo-play-btn-large {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #ef4444, #dc2626);
    border: none;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 8px 24px rgba(239, 68, 68, 0.55);
    position: relative;
    transition: transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.2s ease;
}
.ngo-play-btn-large:hover {
    transform: scale(1.08);
    box-shadow: 0 10px 30px rgba(239, 68, 68, 0.7);
}
.ngo-play-icon-wrap i {
    font-size: 1.4rem;
    margin-left: 2px;
}
.ngo-play-icon-wrap i.fa-pause {
    margin-left: 0;
}

/* Volume Row */
.ngo-volume-row {
    display: flex;
    align-items: center;
    gap: 10px;
    width: 100%;
    padding: 0 4px;
}
.ngo-vol-btn {
    background: transparent;
    border: none;
    color: rgba(255, 255, 255, 0.7);
    font-size: 0.95rem;
    cursor: pointer;
    padding: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: color 0.2s ease;
}
.ngo-vol-btn:hover {
    color: #fff;
}
.ngo-slider-wrap {
    position: relative;
    flex-grow: 1;
    display: flex;
    align-items: center;
}
.ngo-slider {
    -webkit-appearance: none;
    appearance: none;
    width: 100%;
    height: 6px;
    border-radius: 10px;
    background: rgba(255, 255, 255, 0.15);
    outline: none;
    cursor: pointer;
    margin: 0;
}
.ngo-slider::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 14px;
    height: 14px;
    border-radius: 50%;
    background: #ef4444;
    border: 2px solid #fff;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.4);
    cursor: pointer;
    transition: transform 0.15s ease;
}
.ngo-slider::-webkit-slider-thumb:hover {
    transform: scale(1.25);
}
.ngo-slider::-moz-range-thumb {
    width: 14px;
    height: 14px;
    border-radius: 50%;
    background: #ef4444;
    border: 2px solid #fff;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.4);
    cursor: pointer;
}
.ngo-vol-pct {
    font-size: 0.72rem;
    font-weight: 700;
    color: rgba(255, 255, 255, 0.6);
    min-width: 32px;
    text-align: right;
}

/* Card Footer Meta Links */
.ngo-card-footer-links {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    margin-top: 2px;
    padding: 0 4px;
    font-size: 0.68rem;
}
.ngo-engine-badge {
    color: rgba(255, 255, 255, 0.45);
    display: inline-flex;
    align-items: center;
    font-size: 0.65rem;
    font-weight: 600;
}
.ngo-direct-url-link {
    color: rgba(239, 68, 68, 0.85);
    text-decoration: none;
    font-weight: 700;
    font-size: 0.68rem;
    display: inline-flex;
    align-items: center;
    transition: color 0.2s ease;
}
.ngo-direct-url-link:hover {
    color: #ef4444;
    text-decoration: underline;
}

/* ================= KEYFRAME ANIMATIONS ================= */
@keyframes ngoSpin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
@keyframes ngoDotBlink {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.3; transform: scale(0.85); }
}
@keyframes ngoRingPulse {
    0% { transform: scale(0.95); opacity: 0.8; }
    100% { transform: scale(1.35); opacity: 0; }
}
@keyframes ngoWave {
    from { height: 3px; }
    to { height: 16px; }
}
@keyframes ngoEqBounce {
    0% { height: 4px; }
    50% { height: var(--h); }
    100% { height: 8px; }
}
@keyframes ngoCardIn {
    from {
        opacity: 0;
        transform: translateY(20px) scale(0.94);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

/* ================= MOBILE OPTIMIZATION (< 576px) ================= */
@media (max-width: 576px) {
    #ngo-radio-container.ngo-radio-pos-bottom-left {
        left: 14px;
        bottom: 16px;
    }
    #ngo-radio-container.ngo-radio-pos-bottom-right {
        right: 14px;
        bottom: 84px;
    }

    /* Mini Pill on Mobile */
    .ngo-radio-mini {
        max-width: calc(100vw - 92px); /* Avoids touching screen edge and leaves room for WhatsApp */
        padding: 4px 8px 4px 6px;
        gap: 8px;
    }
    .ngo-mini-avatar {
        width: 36px;
        height: 36px;
    }
    .ngo-mini-title {
        max-width: 100px;
        font-size: 0.8rem;
    }
    .ngo-mini-play-btn {
        width: 34px;
        height: 34px;
    }
    .ngo-mini-waves {
        display: none; /* Hide waves on small screens to save width */
    }

    /* Expanded Card on Mobile (Bottom Drawer Sheet) */
    .ngo-radio-card {
        position: fixed;
        left: 10px !important;
        right: 10px !important;
        bottom: 12px !important;
        width: calc(100% - 20px) !important;
        max-width: 420px;
        margin: 0 auto;
        padding: 18px 16px;
        border-radius: 20px;
        z-index: 9996;
    }
    .ngo-play-btn-large {
        width: 56px;
        height: 56px;
    }
}
</style>

<!-- ================= JAVASCRIPT ENGINE ================= -->
<script>
(function() {
    'use strict';

    // Prevent duplicate initialization
    if (window.ngoRadioInitialized) return;
    window.ngoRadioInitialized = true;

    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('ngo-radio-container');
        if (!container) return;

        const streamUrl = container.getAttribute('data-stream');
        if (!streamUrl) return;

        const stationName = container.getAttribute('data-station') || 'Live Radio';
        const orgName = container.getAttribute('data-org') || 'NGO';
        const logoUrl = container.getAttribute('data-logo') || '';
        const defaultVolume = parseInt(container.getAttribute('data-volume') || '80', 10);
        const shouldAutoplay = container.getAttribute('data-autoplay') === '1';

        const audio = document.getElementById('ngo-audio-element');
        const miniPill = document.getElementById('ngo-radio-mini');
        const studioCard = document.getElementById('ngo-radio-card');
        const backdrop = document.getElementById('ngo-radio-backdrop');

        const miniPlayBtn = document.getElementById('ngo-mini-play-btn');
        const miniPlayIcon = document.getElementById('ngo-mini-play-icon');
        const cardPlayBtn = document.getElementById('ngo-card-play-btn');
        const cardPlayIcon = document.getElementById('ngo-card-play-icon');

        const minimizeBtn = document.getElementById('ngo-radio-minimize-btn');
        const reloadBtn = document.getElementById('ngo-radio-reload-btn');
        const popoutBtn = document.getElementById('ngo-radio-popout-btn');
        const volBtn = document.getElementById('ngo-card-vol-btn');
        const volIcon = document.getElementById('ngo-vol-icon');
        const volSlider = document.getElementById('ngo-card-vol-slider');
        const volFill = document.getElementById('ngo-slider-fill');
        const volPct = document.getElementById('ngo-vol-pct');
        const statusMsg = document.getElementById('ngo-status-msg');
        const miniLiveStatus = document.getElementById('ngo-mini-live-status');
        const cardStatusChip = document.getElementById('ngo-card-status-chip');

        const popoutUrl = '<?php echo url('radio/popout'); ?>';

        let isPlaying = false;
        let isConnecting = false;
        let isMuted = false;
        let reconnectTimer = null;
        let reconnectAttempts = 0;
        let lastVolume = defaultVolume / 100;

        // Restore volume from storage
        const storedVol = localStorage.getItem('ngo_radio_vol');
        if (storedVol !== null) {
            lastVolume = parseFloat(storedVol);
            audio.volume = lastVolume;
            setVolumeUI(Math.round(lastVolume * 100));
        } else {
            audio.volume = defaultVolume / 100;
            setVolumeUI(defaultVolume);
        }

        // Restore player expansion state
        const storedState = sessionStorage.getItem('ngo_radio_state');
        if (storedState === 'expanded' && window.innerWidth > 576) {
            expandPlayer();
        }

        // Setup MediaSession API (Lock screen / Bluetooth info)
        function updateMediaSession() {
            if ('mediaSession' in navigator) {
                const artworkList = [];
                if (logoUrl) {
                    artworkList.push(
                        { src: logoUrl, sizes: '96x96', type: 'image/png' },
                        { src: logoUrl, sizes: '192x192', type: 'image/png' },
                        { src: logoUrl, sizes: '512x512', type: 'image/png' }
                    );
                }
                navigator.mediaSession.metadata = new MediaMetadata({
                    title: stationName,
                    artist: orgName,
                    album: 'Live FM Web Radio',
                    artwork: artworkList
                });

                navigator.mediaSession.setActionHandler('play', playRadio);
                navigator.mediaSession.setActionHandler('pause', pauseRadio);
                navigator.mediaSession.setActionHandler('stop', pauseRadio);
            }
        }

        // Helpers
        function setVolumeUI(val) {
            if (volSlider) volSlider.value = val;
            if (volFill) volFill.style.width = val + '%';
            if (volPct) volPct.textContent = val + '%';

            if (val === 0) {
                if (volIcon) volIcon.className = 'fas fa-volume-xmark';
            } else if (val < 50) {
                if (volIcon) volIcon.className = 'fas fa-volume-low';
            } else {
                if (volIcon) volIcon.className = 'fas fa-volume-high';
            }
        }

        function setStatus(text, miniText = '') {
            if (statusMsg) statusMsg.textContent = text;
            if (miniLiveStatus && miniText) miniLiveStatus.textContent = miniText;
            if (cardStatusChip && miniText) cardStatusChip.textContent = miniText;
        }

        function updatePlayIcons(playing) {
            if (playing) {
                container.classList.add('ngo-playing');
                if (miniPlayIcon) miniPlayIcon.className = 'fas fa-pause';
                if (cardPlayIcon) cardPlayIcon.className = 'fas fa-pause';
            } else {
                container.classList.remove('ngo-playing');
                if (miniPlayIcon) miniPlayIcon.className = 'fas fa-play';
                if (cardPlayIcon) cardPlayIcon.className = 'fas fa-play';
            }
        }

        function clearReconnectTimer() {
            if (reconnectTimer) {
                clearTimeout(reconnectTimer);
                reconnectTimer = null;
            }
        }

        // --- Stream Control: Proven High-Resilience Streaming Engine ---
        function playRadio() {
            if (isPlaying) return;
            isConnecting = true;
            clearReconnectTimer();
            setStatus('Connecting live...', 'CONNECTING');

            sessionStorage.setItem('ngo_radio_listening', '1');

            if (audio.src !== streamUrl) {
                audio.src = streamUrl;
                audio.preload = 'auto';
                audio.load();
            }

            const playPromise = audio.play();
            if (playPromise !== undefined) {
                playPromise.then(function() {
                    isPlaying = true;
                    isConnecting = false;
                    reconnectAttempts = 0;
                    updatePlayIcons(true);
                    setStatus('Streaming live on air', 'FM LIVE');
                    updateMediaSession();
                }).catch(function(err) {
                    console.warn('[RadioWidget] Play error or blocked:', err);
                    isConnecting = false;
                    isPlaying = false;
                    updatePlayIcons(false);
                    setStatus('Tap play to start', 'OFFLINE');
                });
            }
        }

        function pauseRadio() {
            clearReconnectTimer();
            isPlaying = false;
            isConnecting = false;
            sessionStorage.setItem('ngo_radio_listening', '0');

            audio.pause();
            audio.removeAttribute('src');
            audio.load();

            updatePlayIcons(false);
            setStatus('Broadcast paused', 'PAUSED');
        }

        function toggleRadio() {
            if (isPlaying || isConnecting) {
                pauseRadio();
            } else {
                playRadio();
            }
        }

        function triggerStreamRecovery(reason) {
            if (!isPlaying && !isConnecting) return;
            console.log(`[RadioWidget] Stream recovery (${reason})...`);
            setStatus('Buffering stream...', 'BUFFERING');

            if (reconnectTimer) return;
            reconnectAttempts++;
            const backoff = Math.min(1000 * reconnectAttempts, 3000);

            reconnectTimer = setTimeout(function() {
                reconnectTimer = null;
                if (sessionStorage.getItem('ngo_radio_listening') === '1') {
                    audio.src = streamUrl;
                    audio.load();
                    audio.play().then(function() {
                        isPlaying = true;
                        isConnecting = false;
                        reconnectAttempts = 0;
                        updatePlayIcons(true);
                        setStatus('Streaming live on air', 'FM LIVE');
                    }).catch(function(e) {
                        console.warn('[RadioWidget] Reconnect retry:', e);
                    });
                }
            }, backoff);
        }

        // --- Audio Element Event Handling (Continuous Jitter-Buffer Protection) ---
        audio.addEventListener('playing', function() {
            isPlaying = true;
            isConnecting = false;
            reconnectAttempts = 0;
            clearReconnectTimer();
            updatePlayIcons(true);
            setStatus('Streaming live on air', 'FM LIVE');
        });

        audio.addEventListener('timeupdate', function() {
            if (!isPlaying && !audio.paused && audio.currentTime > 0) {
                isPlaying = true;
                isConnecting = false;
                updatePlayIcons(true);
                setStatus('Streaming live on air', 'FM LIVE');
            }
        });

        audio.addEventListener('waiting', function() {
            if (isPlaying) {
                setStatus('Buffering stream...', 'BUFFERING');
            }
        });

        audio.addEventListener('stalled', function() {
            if (isPlaying) {
                setStatus('Buffering stream...', 'BUFFERING');
            }
        });

        // Live chunked streams: NEVER halt on ended! Reconnect chunk boundary smoothly
        audio.addEventListener('ended', function() {
            if (sessionStorage.getItem('ngo_radio_listening') === '1' || isPlaying) {
                console.log('[RadioWidget] Live chunk boundary reached, seamlessly continuing stream...');
                audio.src = streamUrl;
                audio.load();
                audio.play().catch(function() {});
            } else {
                pauseRadio();
            }
        });

        audio.addEventListener('error', function() {
            if (sessionStorage.getItem('ngo_radio_listening') === '1' || isPlaying) {
                triggerStreamRecovery('network-error');
            }
        });

        // View State (Expand / Minimize)
        function expandPlayer() {
            miniPill.classList.add('ngo-hidden');
            studioCard.classList.add('ngo-active');
            studioCard.setAttribute('aria-hidden', 'false');
            if (window.innerWidth <= 576 && backdrop) {
                backdrop.classList.add('ngo-active');
            }
            sessionStorage.setItem('ngo_radio_state', 'expanded');
        }

        function minimizePlayer() {
            studioCard.classList.remove('ngo-active');
            studioCard.setAttribute('aria-hidden', 'true');
            miniPill.classList.remove('ngo-hidden');
            if (backdrop) {
                backdrop.classList.remove('ngo-active');
            }
            sessionStorage.setItem('ngo_radio_state', 'mini');
        }

        // Event Listeners
        if (miniPlayBtn) {
            miniPlayBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                toggleRadio();
            });
        }

        if (cardPlayBtn) {
            cardPlayBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                toggleRadio();
            });
        }

        // Clicking pill expands
        const expandTriggers = ['ngo-mini-expand-trigger', 'ngo-mini-info-trigger', 'ngo-mini-chevron-btn', 'ngo-mini-waves'];
        expandTriggers.forEach(function(id) {
            const el = document.getElementById(id);
            if (el) {
                el.addEventListener('click', function(e) {
                    e.stopPropagation();
                    expandPlayer();
                });
            }
        });

        if (minimizeBtn) {
            minimizeBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                minimizePlayer();
            });
        }

        if (backdrop) {
            backdrop.addEventListener('click', minimizePlayer);
        }

        // Pop-out Floating Studio Button (Plays continuously while user browses)
        if (popoutBtn) {
            popoutBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                // Pause in-page player to avoid dual audio
                pauseRadio();
                window.open(
                    popoutUrl,
                    'NgoRadioPopout',
                    'width=380,height=520,status=no,toolbar=no,menubar=no,location=no,resizable=yes'
                );
            });
        }

        // Reload Stream Button
        if (reloadBtn) {
            reloadBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                reloadBtn.querySelector('i').classList.add('fa-spin');
                setStatus('Reconnecting stream...', 'RECONNECTING');

                pauseRadio();
                setTimeout(function() {
                    playRadio();
                    if (reloadBtn.querySelector('i')) reloadBtn.querySelector('i').classList.remove('fa-spin');
                }, 400);
            });
        }

        // Volume Slider
        if (volSlider) {
            volSlider.addEventListener('input', function() {
                const val = parseInt(this.value, 10);
                lastVolume = val / 100;
                audio.volume = lastVolume;
                isMuted = (val === 0);
                setVolumeUI(val);
                localStorage.setItem('ngo_radio_vol', lastVolume);
            });
        }

        // Volume Mute Button
        if (volBtn) {
            volBtn.addEventListener('click', function() {
                if (isMuted || audio.volume === 0) {
                    lastVolume = lastVolume > 0 ? lastVolume : 0.8;
                    audio.volume = lastVolume;
                    isMuted = false;
                    setVolumeUI(Math.round(lastVolume * 100));
                } else {
                    lastVolume = audio.volume;
                    audio.volume = 0;
                    isMuted = true;
                    setVolumeUI(0);
                }
                localStorage.setItem('ngo_radio_vol', audio.volume);
            });
        }

        // Global functions for topbar or external triggers
        window.ngoPlayRadio = function() {
            expandPlayer();
            if (!isPlaying) playRadio();
        };
        window.ngoPauseRadio = pauseRadio;
        window.ngoToggleRadio = function(openCard) {
            if (openCard) expandPlayer();
            toggleRadio();
        };
        window.ngoExpandRadio = expandPlayer;
        window.ngoMinimizeRadio = minimizePlayer;
        window.ngoOpenPopout = function() {
            pauseRadio();
            window.open(popoutUrl, 'NgoRadioPopout', 'width=380,height=520,status=no,toolbar=no,menubar=no,location=no,resizable=yes');
        };

        // Autoplay check (if enabled and user already interacted or allowed)
        if (shouldAutoplay || sessionStorage.getItem('ngo_radio_listening') === '1') {
            playRadio();
        }
    });
})();
</script>
