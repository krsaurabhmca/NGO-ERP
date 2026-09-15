<?php
error_reporting(0);
ini_set('display_errors', 0);
while (ob_get_level()) ob_end_clean();

use App\Models\JobApplication;
use App\Models\Setting;

$uuid = $_GET['uuid'] ?? '';
$appModel = new JobApplication();
$intern = $appModel->findByUuid($uuid);

if (!$intern || $intern->internship_status !== 'completed') {
    echo 'Certificate not available.';
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
$completionDate = !empty($intern->completion_date) ? date('d F, Y', strtotime($intern->completion_date)) : date('d F, Y');

// Load template from settings
$templateBody = $globalSettings['template_certificate'] ?? '';
if (empty(trim($templateBody))) {
    $templateBody = "This is to certify that\n{name}\nhas successfully completed the internship program\nfor the position of {position} at\n{ngo_name}\nDuration: {duration}";
}
$placeholders = ['{name}' => $name, '{position}' => $position, '{ngo_name}' => $ngoName, '{duration}' => $duration, '{stipend}' => $stipend, '{date}' => $completionDate];
$body = str_replace(array_keys($placeholders), array_values($placeholders), $templateBody);
$bodyLines = explode("\n", $body);
$bodyLines = array_values(array_filter($bodyLines, function($l) { return trim($l) !== ''; }));
$bodyLines = array_slice($bodyLines, 0, 6);
$bodyJson = json_encode($bodyLines);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Certificate - <?php echo htmlspecialchars($name); ?></title>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
</head>
<body style="margin:0;background:#fff;font-family:sans-serif;display:flex;align-items:center;justify-content:center;min-height:100vh;">
<div id="loading" style="text-align:center;color:#555;">
    <div style="font-size:18px;margin-bottom:10px;">Generating Certificate...</div>
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
        var doc = new jspdf.jsPDF({ orientation: 'landscape', unit: 'mm', format: 'a4' });
        var w = 297, h = 210;

        doc.setFillColor(255, 255, 255);
        doc.rect(0, 0, w, h, 'F');

        // Borders
        doc.setDrawColor(201, 168, 76);
        doc.setLineWidth(1.5);
        doc.rect(13, 13, w - 26, h - 26, 'S');
        doc.setDrawColor(212, 184, 90);
        doc.setLineWidth(0.8);
        doc.rect(17, 17, w - 34, h - 34, 'S');

        // Top lines
        doc.setDrawColor(201, 168, 76);
        doc.setLineWidth(0.6);
        doc.line(w/2 - 50, 48, w/2 + 50, 48);
        doc.setLineWidth(0.3);
        doc.line(w/2 - 30, 53, w/2 + 30, 53);

        // Logo (original aspect ratio, capped for alignment)
        if (logoData && logoInfo) {
            var logoW = logoInfo.width * 0.264583;
            var logoH = logoInfo.height * 0.264583;
            var maxW = 60, maxH = 35;
            if (logoW > maxW) { logoH = logoH * (maxW / logoW); logoW = maxW; }
            if (logoH > maxH) { logoW = logoW * (maxH / logoH); logoH = maxH; }
            try { doc.addImage(logoData, 'PNG', w/2 - logoW/2, 22, logoW, logoH); } catch(e) { console.warn('Logo addImage failed:', e); }
        }

        var bodyLines = <?php echo $bodyJson; ?>;
        var bodyY = [84, 105, 120, 132, 146, 160];
        var bodyFont = ['normal', 'bold', 'normal', 'normal', 'bold', 'normal'];
        var bodySize = [12, 22, 13, 13, 15, 13];
        var bodyColor = [
            [85, 85, 85], [44, 62, 80], [85, 85, 85],
            [85, 85, 85], [201, 168, 76], [85, 85, 85]
        ];

        doc.setFont('helvetica', 'bold');
        doc.setFontSize(22);
        doc.setTextColor(44, 62, 80);
        doc.text('CERTIFICATE OF COMPLETION', w/2, 65, { align: 'center' });

        for (var i = 0; i < bodyLines.length && i < 6; i++) {
            doc.setFont('helvetica', bodyFont[i]);
            doc.setFontSize(bodySize[i]);
            doc.setTextColor(bodyColor[i][0], bodyColor[i][1], bodyColor[i][2]);
            doc.text(bodyLines[i], w/2, bodyY[i], { align: 'center' });
        }

        // Bottom lines
        doc.setDrawColor(136, 136, 136);
        doc.setLineWidth(0.4);
        doc.line(35, h - 32, 85, h - 32);
        doc.line(w - 85, h - 32, w - 35, h - 32);

        doc.setFont('helvetica', 'normal');
        doc.setFontSize(14);
        doc.setTextColor(85, 85, 85);
        doc.text('<?php echo $completionDate; ?>', 60, h - 35, { align: 'center' });

        doc.setFont('helvetica', 'bold');
        doc.setFontSize(10);
        doc.setTextColor(201, 168, 76);
        doc.text('ISSUE DATE', 60, h - 24, { align: 'center' });
        doc.text('AUTHORIZED SIGNATURE', w - 60, h - 24, { align: 'center' });

        // Signature
        if (sigData) {
            try { doc.addImage(sigData, 'PNG', w - 82, h - 50, 44, 16); } catch(e) { console.warn('Signature addImage failed:', e); }
        }

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

