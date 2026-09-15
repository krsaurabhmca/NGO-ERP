<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Donation Receipt - <?php echo htmlspecialchars($globalSettings['ngo_name'] ?? 'NGO'); ?></title>
  <style>
    @page {
      margin: 0;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Helvetica', sans-serif;
      background: #fff;
      padding: 0;
      color: #1a1a2e;
      margin: 0;
    }

    .receipt {
      background: #fff;
      border-top: 8px solid #3498db;
      padding: 40px;
      padding-top: 35px;
    }

    .header {
      width: 100%;
      margin-bottom: 25px;
      border-bottom: 2px solid #f0f0f0;
      padding-bottom: 20px;
    }

    .header-table {
      width: 100%;
      border-collapse: collapse;
    }

    .header-table td {
      vertical-align: top;
    }

    .org-logo {
      width: 80px;
      height: auto;
    }

    .org-name {
      font-size: 26px;
      font-weight: bold;
      margin-bottom: 5px;
      color: #1a1a2e;
      text-transform: uppercase;
    }

    .org-details {
      font-size: 12px;
      color: #555;
      line-height: 1.5;
    }

    .cards-table {
      width: 100%;
      margin-bottom: 25px;
      border-collapse: collapse;
    }

    .cards-table td.card {
      width: 48%;
      vertical-align: top;
      padding: 18px;
      background: #f8f9fc;
      border-radius: 6px;
      border: 1px solid #eef0f5;
    }

    .cards-table td.spacer {
      width: 4%;
      padding: 0;
      background: transparent;
    }

    .label {
      font-size: 11px;
      text-transform: uppercase;
      color: #888;
      font-weight: bold;
      margin-bottom: 8px;
      border-bottom: 1px solid #e1e5ee;
      padding-bottom: 5px;
    }

    .value {
      font-size: 16px;
      font-weight: bold;
      margin-bottom: 6px;
      color: #1a1a2e;
      margin-top: 8px;
    }

    .sub-value {
      font-size: 12px;
      color: #444;
      line-height: 1.5;
      margin-bottom: 4px;
    }

    .words {
      margin: 20px 0;
      font-size: 13px;
      color: #444;
      padding: 10px;
      background: #fdfdfd;
      border: 1px dashed #ddd;
      border-radius: 4px;
    }

    .words strong {
      color: #1a1a2e;
    }

    .tax-section {
      margin-top: 25px;
      padding: 15px;
      background: #fdf6f6;
      border-left: 4px solid #e74c3c;
      font-size: 12px;
      color: #555;
      line-height: 1.6;
    }

    .tax-section strong {
      color: #1a1a2e;
    }

    .footer {
      margin-top: 35px;
      padding-top: 15px;
      border-top: 2px solid #f0f0f0;
      text-align: center;
    }

    .footer-text {
      font-size: 11px;
      color: #888;
      line-height: 1.6;
    }

    .footer-text strong {
      color: #444;
      font-size: 12px;
      text-transform: uppercase;
    }
  </style>
</head>

<body>
  <div class="receipt">

    <div class="header">
      <table class="header-table">
        <tr>
          <?php if (!empty($globalSettings['ngo_logo'])):
            $logoFile = preg_replace('#^uploads/+#', '', ltrim($globalSettings['ngo_logo'], '/\\'));
            $logoPath = UPLOAD_PATH . $logoFile;
            if (file_exists($logoPath)) {
              $type = pathinfo($logoPath, PATHINFO_EXTENSION);
              $data = file_get_contents($logoPath);
              $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
              ?>
              <td style="width: 100px;">
                <img src="<?php echo $base64; ?>" class="org-logo">
              </td>
            <?php }endif; ?>
          <td>
            <div class="org-name"><?php echo htmlspecialchars($globalSettings['ngo_name'] ?? 'NGO HELP'); ?></div>
            <div class="org-details">
              <?php if (!empty($globalSettings['ngo_address'])): ?>
                <?php echo htmlspecialchars($globalSettings['ngo_address']); ?><br>
              <?php endif; ?>
              <?php if (!empty($globalSettings['ngo_email'])): ?>Email:
                <?php echo htmlspecialchars($globalSettings['ngo_email']); ?><?php endif; ?>
              <?php if (!empty($globalSettings['ngo_phone'])): ?>  <?php if (!empty($globalSettings['ngo_email'])): ?>
                  &middot; <?php endif; ?>Phone:
                <?php echo htmlspecialchars($globalSettings['ngo_phone']); ?><?php endif; ?>
            </div>
          </td>
          <td style="text-align: right; width: 250px; vertical-align: top; padding-top: 5px;">
            <span
              style="font-size: 28px; font-weight: bold; text-transform: uppercase; color: #1a1a2e; line-height: 1;">Receipt</span><br>
            <span
              style="font-size: 10px; color: #999; text-transform: uppercase; letter-spacing: 1px; display: inline-block; margin-top: 4px;">Tax
              Exemption Under 80G</span><br>
            <div style="margin-top: 10px;">
              <span style="font-size: 12px; color: #555;"><strong>Receipt #</strong>
                DON-<?php echo str_pad($d->id, 5, '0', STR_PAD_LEFT); ?></span><br>
              <span style="font-size: 12px; color: #555; display: inline-block; margin-top: 4px;"><strong>Date:</strong>
                <?php echo date('d M Y', strtotime($d->created_at)); ?></span>
            </div>
          </td>
        </tr>
      </table>
    </div>

    <table class="cards-table">
      <tr>
        <td class="card">
          <div class="label">Donor Details</div>
          <div class="value"><?php echo htmlspecialchars($d->donor_name); ?></div>
          <?php
          $addressParts = [];
          if (!empty($d->donor_address))
            $addressParts[] = $d->donor_address;
          if (!empty($d->donor_city))
            $addressParts[] = $d->donor_city;
          if (!empty($d->donor_state))
            $addressParts[] = $d->donor_state;
          if (!empty($d->donor_pincode))
            $addressParts[] = 'PIN - ' . $d->donor_pincode;
          $fullAddress = implode(', ', $addressParts);
          ?>
          <?php if ($fullAddress): ?>
            <div class="sub-value"><?php echo htmlspecialchars($fullAddress); ?></div><?php endif; ?>
          <?php if ($d->donor_email || $d->donor_phone): ?>
            <div class="sub-value" style="margin-top:4px;">
              <?php if ($d->donor_email): ?>    <?php echo htmlspecialchars($d->donor_email); ?>  <?php endif; ?>
              <?php if ($d->donor_phone): ?>    <?php if ($d->donor_email): ?> &middot;
                <?php endif; ?>    <?php echo htmlspecialchars($d->donor_phone); ?>  <?php endif; ?>
            </div>
          <?php endif; ?>
          <?php if (!empty($d->donor_pan)): ?>
            <div class="sub-value"><strong>PAN:</strong> <?php echo htmlspecialchars($d->donor_pan); ?></div>
          <?php endif; ?>
        </td>
        <td class="spacer"></td>
        <td class="card">
          <div class="label">Payment Details</div>
          <div class="value"><?php echo '₹'; ?><?php echo number_format($d->amount, 2); ?></div>
          <div class="sub-value">Mode: <?php echo ucfirst($d->payment_method ?: 'Offline'); ?></div>
          <?php if ($d->transaction_id): ?>
            <div class="sub-value">Txn ID: <?php echo htmlspecialchars($d->transaction_id); ?></div><?php endif; ?>
        </td>
      </tr>
    </table>

    <div class="words"><strong>Amount in words:</strong> Rupees
      <?php echo ucfirst(strtolower(convertNumberToWords($d->amount))); ?> Only</div>

    <div class="tax-section">
      <strong>Tax Exemption:</strong> Donations to
      <?php echo htmlspecialchars($globalSettings['ngo_name'] ?? 'NGO HELP'); ?> are exempt under Section 80G of the
      Income Tax Act, 1961. This receipt is valid for claiming deduction.<br><br>
      <strong>Disclaimer:</strong> This is a system-generated receipt and does not require a physical signature or
      stamp.
    </div>

    <div class="footer">
      <div class="footer-text">
        <strong><?php echo htmlspecialchars($globalSettings['ngo_name'] ?? 'NGO HELP'); ?></strong><br>
        <?php echo htmlspecialchars($globalSettings['ngo_address'] ?? ''); ?><br>
        <em>Thank you for your generosity!</em>
      </div>
    </div>
  </div>

  <?php
  function convertNumberToWords($num)
  {
    $num = (int) $num;
    if ($num == 0)
      return 'Zero';
    $ones = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
    $tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];
    $thousands = ['', 'Thousand', 'Lakh', 'Crore'];
    if ($num < 20)
      return $ones[$num];
    if ($num < 100)
      return $tens[intval($num / 10)] . ' ' . $ones[$num % 10];
    $groups = [];
    while ($num > 0) {
      $groups[] = $num % 100;
      $num = intval($num / 100);
    }
    $words = [];
    for ($i = count($groups) - 1; $i >= 0; $i--) {
      if ($groups[$i] == 0)
        continue;
      $n = $groups[$i];
      $words[] = $n < 20 ? $ones[$n] : $tens[intval($n / 10)] . ' ' . $ones[$n % 10];
      $words[] = $thousands[$i];
    }
    return implode(' ', array_filter($words));
  }
  ?>
</body>

</html>