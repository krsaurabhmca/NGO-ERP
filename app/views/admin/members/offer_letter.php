<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?></title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (new URLSearchParams(window.location.search).has('download')) {
                setTimeout(downloadOfferLetter, 1000);
            }
        });

        function downloadOfferLetter() {
            const btn = document.querySelector('.btn-download');
            const originalText = btn.innerHTML;
            btn.innerHTML = 'Generating...';
            btn.disabled = true;

            html2canvas(document.querySelector('.offer-letter'), {
                scale: 2,
                useCORS: true,
                backgroundColor: '#ffffff',
                logging: false
            }).then(canvas => {
                const link = document.createElement('a');
                link.download = 'Offer_Letter_<?php echo $member->membership_id; ?>.png';
                link.href = canvas.toDataURL('image/png');
                link.click();
                btn.innerHTML = originalText;
                btn.disabled = false;
            }).catch(() => {
                alert('Failed to generate image. Please use Print instead.');
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
        }
    </script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Georgia', 'Times New Roman', serif;
            background: #f0f2f5;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px 20px;
        }
        .no-print { margin-bottom: 20px; }
        .btn-print, .btn-download, .btn-back {
            padding: 10px 24px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 15px;
            font-family: Arial, sans-serif;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            color: #fff;
        }
        .btn-print { background: #1a44a6; }
        .btn-download { background: #2fb344; margin-left: 10px; }
        .btn-back { background: #616876; margin-left: 10px; }
        .btn-print:hover { background: #123075; }
        .btn-download:hover { background: #248a35; }

        .offer-letter {
            width: 800px;
            background: #ffffff;
            padding: 60px 70px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            position: relative;
        }

        /* Letterhead */
        .letterhead {
            text-align: center;
            border-bottom: 3px double #1a44a6;
            padding-bottom: 25px;
            margin-bottom: 35px;
        }
        .letterhead .logo {
            max-height: 70px;
            margin-bottom: 10px;
        }
        .letterhead h1 {
            font-size: 24px;
            color: #1a44a6;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }
        .letterhead .address {
            font-size: 13px;
            color: #555;
            font-family: Arial, sans-serif;
        }

        /* Date & Ref */
        .meta-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            font-size: 14px;
            color: #333;
            font-family: Arial, sans-serif;
        }

        /* Subject */
        .subject {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 25px;
            color: #1a44a6;
        }

        /* Salutation */
        .salutation {
            font-size: 15px;
            margin-bottom: 20px;
            line-height: 1.8;
        }

        /* Body */
        .body-text {
            font-size: 15px;
            line-height: 1.9;
            margin-bottom: 25px;
            text-align: justify;
        }

        /* Details table */
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            font-size: 14px;
        }
        .details-table td {
            padding: 6px 10px;
            border: 1px solid #ddd;
        }
        .details-table td.label {
            width: 140px;
            font-weight: 600;
            background: #f8f9fc;
            color: #333;
        }
        .details-table td.value {
            color: #000;
        }

        /* Terms */
        .terms {
            margin-bottom: 30px;
        }
        .terms h4 {
            font-size: 14px;
            color: #1a44a6;
            margin-bottom: 8px;
            text-transform: uppercase;
        }
        .terms ul {
            list-style: none;
            padding: 0;
        }
        .terms ul li {
            font-size: 13px;
            color: #555;
            padding: 3px 0;
            padding-left: 18px;
            position: relative;
            font-family: Arial, sans-serif;
        }
        .terms ul li::before {
            content: "•";
            position: absolute;
            left: 4px;
            color: #1a44a6;
        }

        /* Signature */
        .signature-block {
            margin-top: 40px;
            text-align: right;
        }
        .signature-block img {
            max-height: 60px;
            margin-bottom: 5px;
        }
        .signature-block .line {
            width: 200px;
            border-top: 2px solid #1a44a6;
            margin-left: auto;
            margin-bottom: 5px;
        }
        .signature-block .name {
            font-weight: bold;
            font-size: 15px;
            color: #1a44a6;
        }
        .signature-block .title {
            font-size: 13px;
            color: #666;
            font-family: Arial, sans-serif;
        }

        @media print {
            body { background: #fff; padding: 0; }
            .no-print { display: none !important; }
            .offer-letter { box-shadow: none; padding: 40px 60px; width: 100%; }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <button class="btn-print" onclick="window.print()">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9V2h12v7"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
            Print
        </button>
        <button class="btn-download" onclick="downloadOfferLetter()">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            Download
        </button>
        <a href="<?php echo url('admin/members'); ?>" class="btn-back">Back</a>
    </div>

    <div class="offer-letter">
        <!-- Letterhead -->
        <div class="letterhead">
            <?php if (!empty($globalSettings['ngo_logo'])): ?>
                <img src="<?php echo file_url($globalSettings['ngo_logo']); ?>" alt="Logo" class="logo" crossorigin="anonymous">
            <?php endif; ?>
            <h1><?php echo !empty($globalSettings['ngo_name']) ? strtoupper($globalSettings['ngo_name']) : 'NGO FOUNDATION'; ?></h1>
            <div class="address">
                <?php echo !empty($globalSettings['ngo_address']) ? htmlspecialchars($globalSettings['ngo_address']) : ''; ?>
                <?php if (!empty($globalSettings['ngo_phone'])): ?>
                    &nbsp; | &nbsp; Phone: <?php echo htmlspecialchars($globalSettings['ngo_phone']); ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Date & Reference -->
        <div class="meta-row">
            <span><strong>Date:</strong> <?php echo $todayDate; ?></span>
            <span><strong>Ref:</strong> MEM/OL/<?php echo $member->membership_id; ?></span>
        </div>

        <!-- Subject -->
        <div class="subject">Subject: Letter of Offer for Membership</div>

        <!-- Salutation -->
        <div class="salutation">
            Dear <strong><?php echo htmlspecialchars($member->name); ?></strong>,
        </div>

        <!-- Body -->
        <div class="body-text">
            We are delighted to welcome you as a valued member of <strong><?php echo !empty($globalSettings['ngo_name']) ? htmlspecialchars($globalSettings['ngo_name']) : 'our organization'; ?></strong>. 
            Based on your application and credentials, we are pleased to offer you the position of 
            <strong><?php echo $designation ? htmlspecialchars($designation->name) : 'Member'; ?></strong> 
            effective from <strong><?php echo $joinDate; ?></strong>.
        </div>
        <div class="body-text">
            As a member, you will be an integral part of our mission to serve the community and drive positive change. 
            Your dedication, skills, and passion align perfectly with our organizational values and goals. 
            We look forward to your active participation and contribution to our initiatives.
        </div>

        <!-- Member Details -->
        <table class="details-table">
            <tr><td class="label">Member Name</td><td class="value"><?php echo htmlspecialchars($member->name); ?></td></tr>
            <tr><td class="label">Membership ID</td><td class="value"><?php echo htmlspecialchars($member->membership_id); ?></td></tr>
            <tr><td class="label">Designation</td><td class="value"><?php echo $designation ? htmlspecialchars($designation->name) : 'Member'; ?></td></tr>
            <tr><td class="label">Joining Date</td><td class="value"><?php echo $joinDate; ?></td></tr>
            <?php if ($memberAddress): ?>
            <tr><td class="label">Address</td><td class="value"><?php echo htmlspecialchars($memberAddress); ?></td></tr>
            <?php endif; ?>
        </table>

        <!-- Terms -->
        <div class="terms">
            <h4>Terms & Conditions</h4>
            <ul>
                <li>Members are expected to uphold the values and mission of the organization.</li>
                <li>Monthly membership fees must be paid by the 10th of each month.</li>
                <li>Members must maintain accurate and up-to-date personal information.</li>
                <li>Any changes to contact details should be promptly communicated to the administration.</li>
                <li>The organization reserves the right to modify membership terms with prior notice.</li>
            </ul>
        </div>

        <!-- Signature -->
        <div class="signature-block">
            <?php if (!empty($globalSettings['ngo_signature'])): ?>
                <img src="<?php echo file_url($globalSettings['ngo_signature']); ?>" alt="Signature" crossorigin="anonymous">
            <?php endif; ?>
            <div class="line"></div>
            <div class="name"><?php echo !empty($globalSettings['ngo_name']) ? htmlspecialchars($globalSettings['ngo_name']) : 'Authorized Signatory'; ?></div>
            <div class="title">Authorized Signatory</div>
        </div>
    </div>

</body>
</html>
