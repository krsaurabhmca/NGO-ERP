<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .id-card-wrapper {
            width: 800px; /* Slightly larger for high res, will scale down */
            height: 500px;
            background-color: #ffffff;
            border-radius: 0;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            position: relative;
        }
        /* Header */
        .id-card-header {
            background-color: #1a44a6; /* Deep blue */
            color: #ffffff;
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 120px;
            box-sizing: border-size;
        }
        .header-logo-container {
            width: 80px;
            height: 80px;
            background-color: #ffffff;
            border-radius: 12px;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }
        .header-logo-container img {
            max-width: 90%;
            max-height: 90%;
        }
        .header-text {
            flex-grow: 1;
            padding-left: 20px;
        }
        .header-text h1 {
            margin: 0;
            font-size: 28px;
            text-transform: uppercase;
            letter-spacing: 1px;
            line-height: 1.2;
        }
        .header-text h2 {
            margin: 5px 0 0 0;
            font-size: 16px;
            font-weight: normal;
            text-transform: uppercase;
        }
        .header-qr {
            width: 80px;
            height: 80px;
            background-color: #ffffff;
            padding: 5px;
            border-radius: 5px;
        }
        .header-qr img {
            width: 100%;
            height: 100%;
        }

        /* Body */
        .id-card-body {
            flex-grow: 1;
            display: flex;
            padding: 25px 30px;
            background-color: #ffffff;
        }
        .photo-container {
            width: 200px;
            height: 250px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 5px;
            background-color: #ffffff;
        }
        .photo-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 4px;
        }
        .details-container {
            flex-grow: 1;
            padding-left: 40px;
            position: relative;
        }
        .member-name {
            color: #1a44a6;
            font-size: 26px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0 0 10px 0;
            border-bottom: 2px solid #e53935; /* Red underline */
            padding-bottom: 5px;
            display: inline-block;
        }
        .info-table {
            margin-top: 15px;
            width: 100%;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 8px 0;
            font-size: 18px;
            color: #555555;
        }
        .info-table td.label {
            width: 100px;
            text-transform: uppercase;
        }
        .info-table td.colon {
            width: 20px;
            font-weight: bold;
            color: #000;
        }
        .info-table td.value {
            font-weight: bold;
            color: #000;
        }
        .info-table td.value.validity {
            color: #e53935;
        }

        .signature-container {
            position: absolute;
            bottom: 0;
            right: 0;
            text-align: center;
            width: 150px;
        }
        .signature-container img {
            max-width: 120px;
            max-height: 50px;
            margin-bottom: 5px;
        }
        .signature-line {
            border-top: 2px solid #e53935;
            margin: 0 auto;
            width: 100%;
        }
        .signature-text {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }

        /* Footer */
        .id-card-footer {
            background-color: #1a44a6;
            color: #ffffff;
            text-align: center;
            padding: 12px 20px;
            font-size: 14px;
        }

        /* Mobile Responsive */
        @media (max-width: 840px) {
            body { padding: 0; margin: 0; overflow-x: hidden; display: flex; flex-direction: column; align-items: center; justify-content: flex-start; height: auto; min-height: 100vh; }
            .print-btn-container { position: static; text-align: center; padding: 10px; display: flex; flex-wrap: wrap; gap: 6px; justify-content: center; }
            .print-btn-container .btn-print, .print-btn-container .btn-download { font-size: 12px; padding: 6px 10px; margin: 0; }
            .print-btn-container a.btn-print { margin-left: 0 !important; }
            .id-card-wrapper { width: 100vw; height: auto; border-radius: 0; box-shadow: none; }
            .id-card-header { height: auto; padding: 3vw 4vw; flex-wrap: wrap; gap: 2vw; }
            .header-logo-container { width: 10vw; height: 10vw; }
            .header-logo-container img { max-width: 85%; max-height: 85%; }
            .header-text { padding-left: 2vw; }
            .header-text h1 { font-size: 3.5vw; }
            .header-text h2 { font-size: 2vw; }
            .header-qr { width: 10vw; height: 10vw; padding: 0.5vw; }
            .id-card-body { flex-direction: column; align-items: center; padding: 3vw 4vw; gap: 3vw; }
            .photo-container { width: 25vw; height: 32vw; padding: 0.5vw; }
            .details-container { padding-left: 0; width: 100%; }
            .member-name { font-size: 3.2vw; margin-bottom: 1vw; padding-bottom: 0.5vw; }
            .info-table { margin-top: 2vw; }
            .info-table td { padding: 0.8vw 0; font-size: 2.2vw; }
            .info-table td.label { width: auto; min-width: 12vw; }
            .info-table td.colon { width: 3vw; }
            .signature-container { position: relative; bottom: auto; right: auto; margin-top: 3vw; width: 100%; text-align: right; }
            .signature-container img { max-height: 6vw; max-width: 15vw; }
            .signature-line { width: 50%; margin: 0 0 0 auto; }
            .id-card-footer { padding: 1.5vw 3vw; font-size: 1.8vw; }
        }

        /* Print Specifics */
        @media print {
            body {
                background-color: #ffffff;
            }
            .id-card-wrapper {
                box-shadow: none;
                border: 1px solid #ccc; /* Add thin border for cutting */
            }
            .no-print {
                display: none !important;
            }
        }
        .print-btn-container {
            position: absolute;
            top: 20px;
            right: 20px;
        }
        .btn-print, .btn-download {
            padding: 10px 20px;
            background-color: #1a44a6;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-download {
            background-color: #2fb344;
            margin-left: 10px;
        }
        .btn-print:hover {
            background-color: #123075;
        }
        .btn-download:hover {
            background-color: #248a35;
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('download')) {
                // Small delay to ensure all images are loaded
                setTimeout(downloadIDCard, 1000);
            }
        });

        function downloadIDCard() {
            const btn = document.querySelector('.btn-download');
            const originalText = btn.innerHTML;
            btn.innerHTML = 'Generating...';
            btn.disabled = true;

            const element = document.querySelector('.id-card-wrapper');
            
            html2canvas(element, {
                scale: 3, // Higher resolution for better print quality
                useCORS: true,
                allowTaint: false,
                backgroundColor: null,
                logging: false
            }).then(canvas => {
                const link = document.createElement('a');
                const fileName = 'ID_Card_<?php echo $member->membership_id; ?>.png';
                link.download = fileName;
                link.href = canvas.toDataURL('image/png');
                link.click();
                
                btn.innerHTML = originalText;
                btn.disabled = false;

                // If it was an auto-download, maybe close the window or show a message
                if (new URLSearchParams(window.location.search).has('download')) {
                    // window.close(); // Only works if opened by script
                }
            }).catch(err => {
                console.error('Error generating ID card:', err);
                alert('Failed to generate ID card image. Please try again.');
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
        }
    </script>
</head>
<body>

    <div class="print-btn-container no-print">
        <button class="btn-print" onclick="window.print()">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9V2h12v7"></path><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
            Print
        </button>
        <button class="btn-download" onclick="downloadIDCard()">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
            Download
        </button>
        <a href="<?php echo url('admin/members'); ?>" class="btn-print" style="background-color: #616876; margin-left: 10px;">Back</a>
    </div>

    <div class="id-card-wrapper">
        <!-- Header -->
        <div class="id-card-header">
            <div class="header-logo-container">
                <?php if (!empty($globalSettings['ngo_logo'])): ?>
                    <img src="<?php echo file_url($globalSettings['ngo_logo']); ?>" alt="Logo" crossorigin="anonymous">
                <?php else: ?>
                    <div style="font-size:30px; color:#1a44a6; font-weight:bold;">NGO</div>
                <?php endif; ?>
            </div>
            <div class="header-text">
                <h1><?php echo !empty($globalSettings['ngo_name']) ? strtoupper($globalSettings['ngo_name']) : 'NGO FOUNDATION'; ?></h1>
                <h2>
                    <?php 
                        $desigName = $designation ? $designation->name : 'MEMBER';
                        echo strtoupper($desigName) . ' IDENTITY CARD'; 
                    ?>
                </h2>
            </div>
            <div class="header-qr">
                <img src="<?php echo $qrUrl; ?>" alt="QR Code" crossorigin="anonymous">
            </div>
        </div>

        <!-- Body -->
        <div class="id-card-body">
            <div class="photo-container">
                <?php if (!empty($member->image)): ?>
                    <img src="<?php echo file_url($member->image); ?>" alt="Photo" crossorigin="anonymous">
                <?php else: ?>
                    <img src="data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23003566'%3e%3ccircle cx='12' cy='8' r='4'/%3e%3cpath d='M4 20c0-4 3.6-7 8-7s8 3 8 7'/%3e%3c/svg%3e" alt="Photo" crossorigin="anonymous" style="background: %23f1f5f9; padding: 10px;">
                <?php endif; ?>
            </div>
            <div class="details-container">
                <div class="member-name"><?php echo htmlspecialchars($member->name); ?></div>
                <div style="font-size: 18px; color: #555; font-weight: bold; margin-top: -5px; margin-bottom: 10px; text-transform: uppercase;">
                    <?php echo htmlspecialchars($designation ? $designation->name : 'Member'); ?>
                </div>
                
                <table class="info-table">
                    <tr>
                        <td class="label">ID NO</td>
                        <td class="colon">:</td>
                        <td class="value"><?php echo htmlspecialchars($member->membership_id); ?></td>
                    </tr>
                    <tr>
                        <td class="label">PHONE</td>
                        <td class="colon">:</td>
                        <td class="value"><?php echo htmlspecialchars($member->phone); ?></td>
                    </tr>
                    <tr>
                        <td class="label">BLOOD</td>
                        <td class="colon">:</td>
                        <td class="value"><?php echo htmlspecialchars($member->blood_group); ?></td>
                    </tr>
                    <tr>
                        <td class="label">VALIDITY</td>
                        <td class="colon">:</td>
                        <td class="value validity"><?php echo $validity; ?></td>
                    </tr>
                    <?php
                        $addressParts = [];
                        if (!empty($member->address_line)) $addressParts[] = $member->address_line;
                        if (!empty($member->city)) $addressParts[] = $member->city;
                        if (!empty($member->district)) $addressParts[] = $member->district;
                        if (!empty($member->state)) $addressParts[] = $member->state;
                        if (!empty($member->pin)) $addressParts[] = 'PIN - ' . $member->pin;
                        
                        $fullAddress = implode(', ', $addressParts);
                        if (empty($fullAddress) && !empty($member->address)) {
                            $fullAddress = $member->address;
                        }
                    ?>
                    <?php if ($fullAddress): ?>
                    <tr>
                        <td class="label" style="vertical-align: top; padding-top: 6px;">ADDRESS</td>
                        <td class="colon" style="vertical-align: top; padding-top: 6px;">:</td>
                        <td class="value" style="font-size: 14px; line-height: 1.3; font-weight: bold; color: #000; vertical-align: top; padding-top: 6px;"><?php echo htmlspecialchars($fullAddress); ?></td>
                    </tr>
                    <?php endif; ?>
                </table>


            </div>
        </div>

        <!-- Footer -->
        <div class="id-card-footer">
            Address: <?php echo !empty($globalSettings['ngo_address']) ? htmlspecialchars($globalSettings['ngo_address']) : 'Your NGO Address Here'; ?> | 
            Emergency: <?php echo !empty($globalSettings['ngo_phone']) ? htmlspecialchars($globalSettings['ngo_phone']) : '+91 XXXXXXXXXX'; ?>
        </div>
    </div>

</body>
</html>