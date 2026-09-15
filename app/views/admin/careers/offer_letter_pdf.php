<?php
error_reporting(0);
ini_set('display_errors', 0);
while (ob_get_level()) ob_end_clean();

use App\Models\JobApplication;
use App\Models\Setting;

$uuid = $_GET['uuid'] ?? '';
$appModel = new JobApplication();
$intern = $appModel->getWithCareer($uuid);

if (!$intern || $intern->status !== 'hired') {
    echo 'Offer letter not available.';
    exit;
}

$settingModel = new Setting();
$settings = $settingModel->all();
$globalSettings = [];
foreach ($settings as $s) {
    $globalSettings[$s->key_name] = $s->key_value;
}

$ngoName = $globalSettings['ngo_name'] ?? 'CARE Foundation';
$ngoLogo = !empty($globalSettings['ngo_logo']) ? file_url($globalSettings['ngo_logo']) : '';
$ngoSignature = !empty($globalSettings['ngo_signature']) ? file_url($globalSettings['ngo_signature']) : '';
$name = $intern->name;
$position = $intern->career_title ?? 'Intern';
$duration = $intern->duration ?? '';
$stipend = $intern->stipend ?? '';
$today = date('d F, Y');

// Load template from settings
$templateBody = $globalSettings['template_offer_letter'] ?? '';
if (empty(trim($templateBody))) {
    $templateBody = "Dear {name},\n\nWe are pleased to offer you the position of {position} at {ngo_name}. " .
        "After reviewing your qualifications and interview performance, we are confident that you will make a valuable contribution to our team.\n\n" .
        "The details of your internship offer are as follows:\n\n" .
        "Position: {position}\nDuration: {duration}\nStipend: {stipend}\nReporting: Head Office\nStart Date: {date}\n\n" .
        "Please confirm your acceptance of this offer by replying to this email within 5 business days. " .
        "We look forward to welcoming you to our team and providing you with a rewarding internship experience.\n\n" .
        "If you have any questions, please do not hesitate to contact us.\n\n" .
        "We wish you a fulfilling journey with us.\n\nSincerely,\n{ngo_name}";
}
$placeholders = ['{name}' => $name, '{position}' => $position, '{ngo_name}' => $ngoName, '{duration}' => $duration, '{stipend}' => $stipend, '{date}' => $today];
$body = str_replace(array_keys($placeholders), array_values($placeholders), $templateBody);
$bodyLines = explode("\n", $body);
// Strip trailing duplicate closing lines (Sincerely, NGO name) so hardcoded signature block shows only once
while (count($bodyLines) > 0) {
    $last = trim(end($bodyLines));
    if ($last === '' || $last === 'Sincerely,' || $last === $ngoName) {
        array_pop($bodyLines);
    } else {
        break;
    }
}
$bodyJson = json_encode($bodyLines);
$bodyLineCount = count($bodyLines);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Offer Letter - <?php echo $name; ?></title>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
</head>
<body style="margin:0;background:#fff;font-family:sans-serif;display:flex;align-items:center;justify-content:center;min-height:100vh;">
<div id="loading" style="text-align:center;color:#555;">
    <div style="font-size:18px;margin-bottom:10px;">Generating Offer Letter...</div>
    <div style="width:40px;height:40px;border:4px solid #c9a84c;border-top-color:transparent;border-radius:50%;animation:spin .8s linear infinite;margin:0 auto;"></div>
</div>
<style>
@keyframes spin { to { transform: rotate(360deg); } }
</style>
<script>
function loadImageAsDataUrl(url) {
    return new Promise(function(resolve) {
        if (!url) { resolve(null); return; }
        var img = new Image();
        img.crossOrigin = 'anonymous';
        img.onload = function() {
            var canvas = document.createElement('canvas');
            canvas.width = img.naturalWidth;
            canvas.height = img.naturalHeight;
            var ctx = canvas.getContext('2d');
            ctx.drawImage(img, 0, 0);
            try {
                resolve({ dataUrl: canvas.toDataURL('image/png'), width: img.naturalWidth, height: img.naturalHeight });
            } catch(e) {
                resolve(null);
            }
        };
        img.onerror = function() { resolve(null); };
        img.src = url;
    });
}

window.addEventListener('load', function() {
    var logoUrl = <?php echo json_encode($ngoLogo); ?>;
    var sigUrl = <?php echo json_encode($ngoSignature); ?>;

    Promise.all([loadImageAsDataUrl(logoUrl), loadImageAsDataUrl(sigUrl)]).then(function(images) {
        var logoInfo = images[0];
        var logoData = logoInfo ? logoInfo.dataUrl : null;
        var sigInfo = images[1];
        var sigData = sigInfo ? sigInfo.dataUrl : null;

        try {
        var doc = new jspdf.jsPDF({ orientation: 'portrait', unit: 'mm', format: 'a4' });
        var w = 210, h = 297;
        var mx = 20;

        doc.setFillColor(255, 255, 255);
        doc.rect(0, 0, w, h, 'F');

        // Header line
        doc.setDrawColor(201, 168, 76);
        doc.setLineWidth(0.6);
        doc.line(mx, 35, w - mx, 35);

        // Logo (original aspect ratio, capped for alignment)
        if (logoData && logoInfo) {
            var logoW = logoInfo.width * 0.264583;
            var logoH = logoInfo.height * 0.264583;
            var maxW = 50, maxH = 20;
            if (logoW > maxW) { logoH = logoH * (maxW / logoW); logoW = maxW; }
            if (logoH > maxH) { logoW = logoW * (maxH / logoH); logoH = maxH; }
            try { doc.addImage(logoData, 'PNG', w/2 - logoW/2, 14, logoW, logoH); } catch(e) { console.warn('Logo addImage failed:', e); }
        }

        // Title
        doc.setFont('helvetica', 'bold');
        doc.setFontSize(18);
        doc.setTextColor(44, 62, 80);
        doc.text('OFFER LETTER', w/2, 48, { align: 'center' });

        doc.setFontSize(10);
        doc.setTextColor(201, 168, 76);
        doc.text('<?php echo strtoupper($ngoName); ?>', w/2, 55, { align: 'center' });

        // Reference
        doc.setFont('helvetica', 'normal');
        doc.setFontSize(10);
        doc.setTextColor(85, 85, 85);
        doc.text('Date: <?php echo $today; ?>', mx, 70);
        doc.text('Ref: OL/<?php echo str_pad($intern->id, 4, '0', STR_PAD_LEFT); ?>/<?php echo date('Y'); ?>', w - mx, 70, { align: 'right' });

        // Address block
        doc.setFont('helvetica', 'bold');
        doc.setFontSize(11);
        doc.setTextColor(44, 62, 80);
        doc.text('To,', mx, 85);
        doc.text('<?php echo $name; ?>', mx, 93);
        doc.setFont('helvetica', 'normal');
        doc.setFontSize(11);
        doc.setTextColor(60, 60, 60);
        doc.text('<?php echo $intern->email ?? ''; ?>', mx, 100);
        doc.text('<?php echo $intern->phone ?? ''; ?>', mx, 107);

        // Subject line
        doc.setFont('helvetica', 'bold');
        doc.setFontSize(11);
        doc.setTextColor(44, 62, 80);
        doc.text('Subject: Offer of Internship', mx, 118);

        var margin = 20;
        var pageBottom = h - 35;

        function drawOfferFooter() {
            doc.setDrawColor(201, 168, 76);
            doc.setLineWidth(0.3);
            doc.line(mx, h - 25, w - mx, h - 25);
            doc.setFont('helvetica', 'normal');
            doc.setFontSize(8);
            doc.setTextColor(136, 136, 136);
            doc.text('This is a computer-generated document. No signature is required.', w/2, h - 18, { align: 'center' });
            doc.text('<?php echo $ngoName; ?>', w/2, h - 12, { align: 'center' });
        }

        var isFirstPage = true;
        function drawOfferHeader() {
            doc.setFillColor(255, 255, 255);
            doc.rect(0, 0, w, h, 'F');
            doc.setDrawColor(201, 168, 76);
            doc.setLineWidth(0.6);
            doc.line(mx, 35, w - mx, 35);
            if (logoData && logoInfo) {
                var logoW = logoInfo.width * 0.264583;
                var logoH = logoInfo.height * 0.264583;
                var maxW = 50, maxH = 20;
                if (logoW > maxW) { logoH = logoH * (maxW / logoW); logoW = maxW; }
                if (logoH > maxH) { logoW = logoW * (maxH / logoH); logoH = maxH; }
                try { doc.addImage(logoData, 'PNG', w/2 - logoW/2, 14, logoW, logoH); } catch(e) {}
            }
            if (isFirstPage) {
                doc.setFont('helvetica', 'bold');
                doc.setFontSize(18);
                doc.setTextColor(44, 62, 80);
                doc.text('OFFER LETTER', w/2, 48, { align: 'center' });
                doc.setFontSize(10);
                doc.setTextColor(201, 168, 76);
                doc.text('<?php echo strtoupper($ngoName); ?>', w/2, 55, { align: 'center' });
                doc.setFont('helvetica', 'normal');
                doc.setFontSize(10);
                doc.setTextColor(85, 85, 85);
                doc.text('Date: <?php echo $today; ?>', mx, 70);
        doc.text('Ref: OL/<?php echo str_pad($intern->id, 4, '0', STR_PAD_LEFT); ?>/<?php echo date('Y'); ?>', w - mx, 70, { align: 'right' });
                doc.setFont('helvetica', 'bold');
                doc.setFontSize(11);
                doc.setTextColor(44, 62, 80);
                doc.text('To,', mx, 85);
                doc.text('<?php echo $name; ?>', mx, 93);
                doc.setFont('helvetica', 'normal');
                doc.setFontSize(11);
                doc.setTextColor(60, 60, 60);
                doc.text('<?php echo $intern->email ?? ''; ?>', mx, 100);
                doc.text('<?php echo $intern->phone ?? ''; ?>', mx, 107);
                doc.setFont('helvetica', 'bold');
                doc.setFontSize(11);
                doc.setTextColor(44, 62, 80);
                doc.text('Subject: Offer of Internship', mx, 118);
            }
            isFirstPage = false;
        }

        var bodyStartY = 128;

        // Body from template
        var bodyLines = <?php echo $bodyJson; ?>;
        var bodyLineCount = <?php echo $bodyLineCount; ?>;

        drawOfferHeader();
        var bodyY = bodyStartY;
        for (var i = 0; i < bodyLineCount; i++) {
            var line = bodyLines[i];
            if (line.trim() === '') {
                bodyY += 5;
                continue;
            }
            var words = doc.splitTextToSize(line, w - mx * 2);
            if (bodyY + 7 * words.length > pageBottom) {
                drawOfferFooter();
                doc.addPage();
                bodyStartY = 42;
                drawOfferHeader();
                bodyY = bodyStartY;
            }
            doc.setFont('helvetica', 'normal');
            doc.setFontSize(11);
            doc.setTextColor(60, 60, 60);
            doc.text(words, mx, bodyY);
            bodyY += 7 * Math.max(1, words.length);
        }

        if (bodyY + 40 > pageBottom) {
            drawOfferFooter();
            doc.addPage();
            bodyStartY = 42;
            drawOfferHeader();
            bodyY = bodyStartY;
        }

        bodyY += 4;

        // Signature
        doc.setFont('helvetica', 'normal');
        doc.setFontSize(11);
        doc.setTextColor(60, 60, 60);
        doc.text('Sincerely,', mx, bodyY);
        bodyY += 16;

        if (sigData) {
            try { doc.addImage(sigData, 'PNG', mx, bodyY - 12, 32, 12); } catch(e) { console.warn('Signature addImage failed:', e); }
        }

        doc.setFont('helvetica', 'bold');
        doc.setFontSize(11);
        doc.setTextColor(44, 62, 80);
        doc.text('Authorized Signatory', mx, bodyY + 5);
        doc.setFont('helvetica', 'normal');
        doc.setFontSize(11);
        doc.setTextColor(60, 60, 60);
        doc.text('<?php echo $ngoName; ?>', mx, bodyY + 14);

        // Footer
        drawOfferFooter();

        var pdfBlob = doc.output('bloburl');
        document.body.style.margin = '0';
        document.body.style.padding = '0';
        document.body.style.overflow = 'hidden';
        document.body.innerHTML = '<iframe src="' + pdfBlob + '" style="width:100vw;height:100vh;border:none;"></iframe>';
        } catch(e) {
            document.getElementById('loading').innerHTML = 'Error: ' + e.message;
        }
    });
});
</script>
</body>
</html>

