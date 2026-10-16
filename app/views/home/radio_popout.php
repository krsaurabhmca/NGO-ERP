<?php
/**
 * Standalone Pop-out Live Radio Studio Window
 * Continuous, Uninterrupted Live Streaming Player
 */
$radioName = !empty($globalSettings['radio_name']) ? htmlspecialchars($globalSettings['radio_name']) : 'Live Radio FM';
$radioFreq = !empty($globalSettings['radio_frequency']) ? htmlspecialchars($globalSettings['radio_frequency']) : 'Live Broadcast';
$radioStreamUrl = !empty($globalSettings['radio_stream_url']) ? htmlspecialchars($globalSettings['radio_stream_url']) : '';
$radioDefaultVolume = !empty($globalSettings['radio_default_volume']) ? (int)$globalSettings['radio_default_volume'] : 80;

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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $radioName; ?> - Live Studio Pop-out Player</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: linear-gradient(165deg, #090d16 0%, #0f172a 50%, #1e293b 100%);
            color: #ffffff;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 20px;
            user-select: none;
            overflow: hidden;
        }
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .live-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.72rem;
            font-weight: 800;
            color: #ef4444;
            letter-spacing: 1px;
        }
        .beacon {
            width: 7px;
            height: 7px;
            background: #ef4444;
            border-radius: 50%;
            animation: pulse 1.4s infinite;
        }
        .station-badge {
            font-size: 0.72rem;
            color: rgba(255, 255, 255, 0.6);
            font-weight: 600;
        }
        .center-stage {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            margin: 15px 0;
        }
        .vinyl-wrap {
            position: relative;
            width: 120px;
            height: 120px;
            margin-bottom: 15px;
        }
        .vinyl {
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
        .playing .vinyl {
            animation: spin 10s linear infinite;
            border-color: #ef4444;
            box-shadow: 0 0 30px rgba(239, 68, 68, 0.45);
        }
        .vinyl-center {
            width: 68px;
            height: 68px;
            border-radius: 50%;
            overflow: hidden;
            background: #09090b;
            border: 2px solid rgba(255, 255, 255, 0.25);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .vinyl-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .station-name {
            font-size: 1.25rem;
            font-weight: 800;
            margin-bottom: 4px;
        }
        .station-freq {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.65);
            margin-bottom: 8px;
        }
        .status-msg {
            font-size: 0.76rem;
            color: #ef4444;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            min-height: 20px;
        }
        .eq-bars {
            display: flex;
            align-items: flex-end;
            justify-content: center;
            gap: 4px;
            height: 24px;
            margin: 10px 0;
        }
        .eq-bar {
            width: 4px;
            height: 4px;
            background: linear-gradient(180deg, #ef4444, #f97316);
            border-radius: 2px;
            transition: height 0.2s ease;
        }
        .playing .eq-bar:nth-child(1) { animation: bounce 0.8s infinite alternate 0.1s; }
        .playing .eq-bar:nth-child(2) { animation: bounce 1.1s infinite alternate 0.3s; }
        .playing .eq-bar:nth-child(3) { animation: bounce 0.9s infinite alternate 0.2s; }
        .playing .eq-bar:nth-child(4) { animation: bounce 1.3s infinite alternate 0.4s; }
        .playing .eq-bar:nth-child(5) { animation: bounce 0.7s infinite alternate 0.15s; }
        .playing .eq-bar:nth-child(6) { animation: bounce 1.2s infinite alternate 0.35s; }
        .playing .eq-bar:nth-child(7) { animation: bounce 0.85s infinite alternate 0.25s; }
        .playing .eq-bar:nth-child(8) { animation: bounce 1.05s infinite alternate 0.45s; }

        .controls {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 14px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            padding-top: 14px;
        }
        .play-btn {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: linear-gradient(135deg, #ef4444, #dc2626);
            border: none;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 8px 24px rgba(239, 68, 68, 0.55);
            font-size: 1.4rem;
            transition: transform 0.2s ease;
        }
        .play-btn:hover {
            transform: scale(1.06);
        }
        .vol-row {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            padding: 0 10px;
        }
        .vol-slider {
            flex-grow: 1;
            accent-color: #ef4444;
            cursor: pointer;
        }
        .footer-note {
            text-align: center;
            font-size: 0.68rem;
            color: rgba(255, 255, 255, 0.4);
            margin-top: 8px;
        }

        @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
        @keyframes pulse { 0%, 100% { opacity: 1; transform: scale(1); } 50% { opacity: 0.4; } }
        @keyframes bounce { 0% { height: 4px; } 50% { height: 22px; } 100% { height: 8px; } }
    </style>
</head>
<body id="popout-body">
    <div class="header">
        <div class="live-tag">
            <span class="beacon"></span>
            <span id="popout-live-tag">ON AIR</span>
        </div>
        <div class="station-badge"><?php echo $ngoOrgName; ?></div>
    </div>

    <div class="center-stage">
        <div class="vinyl-wrap">
            <div class="vinyl">
                <div class="vinyl-center">
                    <?php if (!empty($radioLogoUrl)): ?>
                        <img src="<?php echo $radioLogoUrl; ?>" alt="Logo" class="vinyl-img">
                    <?php else: ?>
                        <i class="fas fa-radio" style="font-size: 1.8rem; color: #ef4444;"></i>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <h2 class="station-name"><?php echo $radioName; ?></h2>
        <div class="station-freq"><?php echo $radioFreq; ?></div>
        <div class="status-msg" id="popout-status">Connecting Live...</div>

        <div class="eq-bars">
            <span class="eq-bar"></span>
            <span class="eq-bar"></span>
            <span class="eq-bar"></span>
            <span class="eq-bar"></span>
            <span class="eq-bar"></span>
            <span class="eq-bar"></span>
            <span class="eq-bar"></span>
            <span class="eq-bar"></span>
        </div>
    </div>

    <div class="controls">
        <button type="button" class="play-btn" id="popout-play-btn">
            <i class="fas fa-pause" id="popout-play-icon"></i>
        </button>

        <div class="vol-row">
            <i class="fas fa-volume-high" id="popout-vol-icon" style="font-size: 0.9rem; color: rgba(255,255,255,0.7);"></i>
            <input type="range" class="vol-slider" id="popout-vol-slider" min="0" max="100" value="<?php echo $radioDefaultVolume; ?>">
            <span id="popout-vol-pct" style="font-size: 0.75rem; width: 32px; text-align: right;"><?php echo $radioDefaultVolume; ?>%</span>
        </div>

        <div class="footer-note">Continuous background stream &bull; Stays playing while you browse</div>
    </div>

    <audio id="popout-native-audio" preload="none" playsinline style="display:none;">
        <source src="<?php echo $radioStreamUrl; ?>" type="audio/mpeg">
        Your browser does not support HTML audio.
    </audio>

    <script>
    (function() {
        const streamUrl = <?php echo json_encode($radioStreamUrl); ?>;
        const defaultVol = <?php echo $radioDefaultVolume; ?> / 100;
        const body = document.getElementById('popout-body');
        const playBtn = document.getElementById('popout-play-btn');
        const playIcon = document.getElementById('popout-play-icon');
        const volSlider = document.getElementById('popout-vol-slider');
        const volPct = document.getElementById('popout-vol-pct');
        const statusMsg = document.getElementById('popout-status');
        const liveTag = document.getElementById('popout-live-tag');
        const audio = document.getElementById('popout-native-audio');

        let isPlaying = false;
        let isConnecting = false;

        audio.volume = defaultVol;

        function setStatus(text, tag = 'ON AIR') {
            statusMsg.textContent = text;
            liveTag.textContent = tag;
        }

        function setPlayingState(playing) {
            isPlaying = playing;
            if (playing) {
                body.classList.add('playing');
                playIcon.className = 'fas fa-pause';
                setStatus('Streaming Live on Air', 'ON AIR');
            } else {
                body.classList.remove('playing');
                playIcon.className = 'fas fa-play';
                setStatus('Broadcast Paused', 'PAUSED');
            }
        }

        function playStream() {
            if (isPlaying) return;
            isConnecting = true;
            setStatus('Connecting Live...', 'CONNECTING');

            if (!audio.src && !audio.currentSrc) {
                let srcEl = audio.querySelector('source');
                if (!srcEl) {
                    srcEl = document.createElement('source');
                    srcEl.type = 'audio/mpeg';
                    audio.appendChild(srcEl);
                }
                srcEl.src = streamUrl;
                audio.src = streamUrl;
            }

            const p = audio.play();
            if (p !== undefined) {
                p.then(function() {
                    isConnecting = false;
                    setPlayingState(true);
                }).catch(function(e) {
                    console.warn('Audio play error:', e);
                    isConnecting = false;
                    setPlayingState(false);
                    setStatus('Tap Play to start', 'OFFLINE');
                });
            }
        }

        function pauseStream() {
            isConnecting = false;
            audio.pause();
            setPlayingState(false);
        }

        playBtn.addEventListener('click', function() {
            if (isPlaying || isConnecting) {
                pauseStream();
            } else {
                playStream();
            }
        });

        volSlider.addEventListener('input', function() {
            const val = parseInt(this.value, 10);
            volPct.textContent = val + '%';
            audio.volume = val / 100;
        });

        audio.addEventListener('playing', function() {
            isConnecting = false;
            setPlayingState(true);
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

        audio.addEventListener('timeupdate', function() {
            if (!isPlaying && !audio.paused && audio.currentTime > 0) {
                setPlayingState(true);
            }
        });

        // Live chunk boundary: seamlessly continue
        audio.addEventListener('ended', function() {
            if (isPlaying) {
                audio.src = streamUrl;
                audio.play().catch(function() {});
            }
        });

        audio.addEventListener('error', function() {
            if (isPlaying) {
                setStatus('Reconnecting stream...', 'CONNECTING');
                setTimeout(function() {
                    if (isPlaying) {
                        audio.src = streamUrl;
                        audio.play().catch(function() {});
                    }
                }, 1500);
            }
        });

        // Auto-play on launch
        playStream();
    })();
    </script>
</body>
</html>
