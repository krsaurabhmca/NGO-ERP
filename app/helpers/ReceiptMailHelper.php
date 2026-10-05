<?php

namespace App\Helpers;

use App\Core\Mailer;
use App\Models\Setting;

/**
 * ReceiptMailHelper
 * 
 * Helper class to format and send branded, tax-compliant donation receipts via email.
 */
class ReceiptMailHelper
{
    /**
     * Send receipt email to donor for a completed donation.
     *
     * @param object|array $donation Donation record
     * @param array $globalSettings System settings (optional)
     * @return bool
     */
    public static function sendReceipt($donation, array $globalSettings = []): bool
    {
        try {
            if (is_array($donation)) {
                $donation = (object)$donation;
            }

            if (empty($donation) || empty($donation->donor_email)) {
                return false;
            }

            $to = trim($donation->donor_email);
            if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
                return false;
            }

            // Load settings if not provided
            if (empty($globalSettings)) {
                $settingModel = new Setting();
                $allSettings = $settingModel->getAllByGroup();
                $globalSettings = [];
                if ($allSettings) {
                    foreach ($allSettings as $s) {
                        $globalSettings[$s->key_name] = $s->key_value;
                    }
                }
            }

            $ngoName = $globalSettings['ngo_name'] ?? 'NGO HELP';
            $ngoEmail = $globalSettings['ngo_email'] ?? '';
            $ngoPhone = $globalSettings['ngo_phone'] ?? '';
            $ngoAddress = $globalSettings['ngo_address'] ?? '';
            $ngoPan = $globalSettings['ngo_pan'] ?? '';
            $ngo12a = $globalSettings['ngo_12a_reg_no'] ?? '';
            $ngo80g = $globalSettings['ngo_80g_reg_no'] ?? '';
            $ngo80gValidity = $globalSettings['ngo_80g_validity'] ?? '';
            $ngoTaxNote = $globalSettings['ngo_tax_exemption_note'] ?? '';

            $donationId = $donation->id ?? 0;
            $donationUuid = $donation->uuid ?? '';
            $receiptNo = 'DON-' . str_pad($donationId, 5, '0', STR_PAD_LEFT);
            $amountFormatted = number_format((float)($donation->amount ?? 0), 2);
            $dateFormatted = !empty($donation->created_at) ? date('d M Y, h:i A', strtotime($donation->created_at)) : date('d M Y');
            
            $methodMap = [
                'razorpay' => 'Razorpay (Online / UPI)',
                'cashfree' => 'Cashfree (Online / UPI)',
                'phonepe' => 'PhonePe (UPI)',
                'offline' => 'Offline / Cash',
                'upi' => 'UPI',
                'card' => 'Credit / Debit Card',
                'bank_transfer' => 'Bank Transfer',
                'cash' => 'Cash'
            ];
            $payMethodKey = strtolower($donation->payment_method ?? 'online');
            $payMethodLabel = $methodMap[$payMethodKey] ?? ucfirst($donation->payment_method ?? 'Online');

            // Generate secure URLs
            if (!empty($donationUuid)) {
                $pdfReceiptUrl = SignedUrlHelper::generateReceiptUrl($donationUuid, 86400 * 365);
                $htmlReceiptUrl = SignedUrlHelper::generate("donate/receipt/{$donationUuid}", 86400 * 365, ['html' => 1]);
            } else {
                $pdfReceiptUrl = url('donate');
                $htmlReceiptUrl = url('donate');
            }

            $donorName = htmlspecialchars($donation->donor_name ?? 'Valued Donor');
            $donorPan = htmlspecialchars($donation->donor_pan ?? '');
            $txnId = htmlspecialchars($donation->transaction_id ?? '');

            $subject = "Donation Receipt [{$receiptNo}] - {$ngoName}";

            // Tax section html for email
            $taxDetailsHtml = '';
            $hasTaxDetails = !empty($ngo80g) || !empty($ngo12a) || !empty($ngoPan) || !empty($ngo80gValidity);

            if ($hasTaxDetails || !empty($ngoTaxNote)) {
                $taxDetailsHtml .= '
                <div style="background-color: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 16px; margin: 24px 0;">
                    <div style="font-weight: 700; color: #166534; font-size: 14px; margin-bottom: 10px; display: flex; align-items: center;">
                        <span style="display:inline-block; margin-right:6px;">&#10003;</span> 80G Tax Exemption & Registration Details
                    </div>
                    <table style="width: 100%; font-size: 13px; color: #374151; border-collapse: collapse;">';

                if (!empty($ngo80g)) {
                    $taxDetailsHtml .= '
                        <tr>
                            <td style="padding: 4px 0; font-weight: 600; width: 42%; color: #4b5563;">80G Registration / URN:</td>
                            <td style="padding: 4px 0; font-weight: 700; color: #111827;">' . htmlspecialchars($ngo80g) . '</td>
                        </tr>';
                }

                if (!empty($ngo12a)) {
                    $taxDetailsHtml .= '
                        <tr>
                            <td style="padding: 4px 0; font-weight: 600; color: #4b5563;">12A Registration / URN:</td>
                            <td style="padding: 4px 0; font-weight: 700; color: #111827;">' . htmlspecialchars($ngo12a) . '</td>
                        </tr>';
                }

                if (!empty($ngoPan)) {
                    $taxDetailsHtml .= '
                        <tr>
                            <td style="padding: 4px 0; font-weight: 600; color: #4b5563;">NGO PAN Number:</td>
                            <td style="padding: 4px 0; font-weight: 700; color: #111827;">' . htmlspecialchars($ngoPan) . '</td>
                        </tr>';
                }

                if (!empty($ngo80gValidity)) {
                    $taxDetailsHtml .= '
                        <tr>
                            <td style="padding: 4px 0; font-weight: 600; color: #4b5563;">80G Validity / Period:</td>
                            <td style="padding: 4px 0; color: #111827;">' . htmlspecialchars($ngo80gValidity) . '</td>
                        </tr>';
                }

                $taxDetailsHtml .= '
                    </table>
                    <div style="margin-top: 10px; font-size: 12px; color: #15803d; line-height: 1.4;">
                        ' . (!empty($ngoTaxNote) ? htmlspecialchars($ngoTaxNote) : 'Donations to ' . htmlspecialchars($ngoName) . ' are eligible for tax deduction under Section 80G of the Income Tax Act, 1961.') . '
                    </div>
                </div>';
            }

            // Assemble Full Branded HTML Email
            $html = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donation Receipt</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f6f8; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased; color: #1e293b;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; background-color: #f4f6f8; padding: 30px 10px;">
        <tr>
            <td align="center">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 620px; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #e2e8f0;">
                    
                    <!-- Header Banner -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #003566 0%, #0054a6 100%); padding: 32px 28px; text-align: center; color: #ffffff;">
                            <h1 style="margin: 0; font-size: 24px; font-weight: 700; letter-spacing: -0.5px;">' . htmlspecialchars($ngoName) . '</h1>
                            <p style="margin: 8px 0 0 0; font-size: 14px; opacity: 0.9;">Official Donation Receipt & Tax Acknowledgement</p>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 32px 28px;">
                            
                            <!-- Greeting -->
                            <p style="margin: 0 0 16px 0; font-size: 16px; line-height: 1.5; color: #334155;">
                                Dear <strong>' . $donorName . '</strong>,
                            </p>
                            <p style="margin: 0 0 24px 0; font-size: 15px; line-height: 1.6; color: #475569;">
                                Thank you wholeheartedly for your generous donation. Your valuable contribution empowers us to continue our social mission and make a positive impact in our community.
                            </p>

                            <!-- Amount Card -->
                            <div style="background-color: #f8fafc; border: 2px dashed #cbd5e1; border-radius: 10px; padding: 20px; text-align: center; margin-bottom: 24px;">
                                <div style="font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; color: #64748b; margin-bottom: 6px;">Total Donation Amount</div>
                                <div style="font-size: 32px; font-weight: 800; color: #003566;">&#8377; ' . $amountFormatted . '</div>
                                <div style="font-size: 12px; color: #10b981; font-weight: 600; margin-top: 4px;">&#10004; Payment Verified & Successfully Received</div>
                            </div>

                            <!-- Receipt Information Table -->
                            <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 14px;">
                                <tr>
                                    <td style="padding: 10px 12px; border-bottom: 1px solid #f1f5f9; color: #64748b; font-weight: 600; width: 40%;">Receipt Number</td>
                                    <td style="padding: 10px 12px; border-bottom: 1px solid #f1f5f9; color: #0f172a; font-weight: 700;">' . $receiptNo . '</td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px 12px; border-bottom: 1px solid #f1f5f9; color: #64748b; font-weight: 600;">Date & Time</td>
                                    <td style="padding: 10px 12px; border-bottom: 1px solid #f1f5f9; color: #0f172a;">' . $dateFormatted . '</td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px 12px; border-bottom: 1px solid #f1f5f9; color: #64748b; font-weight: 600;">Payment Mode</td>
                                    <td style="padding: 10px 12px; border-bottom: 1px solid #f1f5f9; color: #0f172a;">' . $payMethodLabel . '</td>
                                </tr>';

            if (!empty($txnId)) {
                $html .= '
                                <tr>
                                    <td style="padding: 10px 12px; border-bottom: 1px solid #f1f5f9; color: #64748b; font-weight: 600;">Transaction ID</td>
                                    <td style="padding: 10px 12px; border-bottom: 1px solid #f1f5f9; color: #0f172a; font-family: monospace;">' . $txnId . '</td>
                                </tr>';
            }

            if (!empty($donorPan)) {
                $html .= '
                                <tr>
                                    <td style="padding: 10px 12px; border-bottom: 1px solid #f1f5f9; color: #64748b; font-weight: 600;">Donor PAN</td>
                                    <td style="padding: 10px 12px; border-bottom: 1px solid #f1f5f9; color: #0f172a; font-family: monospace; font-weight: 600;">' . $donorPan . '</td>
                                </tr>';
            }

            $html .= '
                            </table>

                            <!-- Tax & 80G / 12A Details Section -->
                            ' . $taxDetailsHtml . '

                            <!-- Action Buttons -->
                            <div style="text-align: center; margin: 30px 0 20px 0;">
                                <a href="' . $pdfReceiptUrl . '" style="display: inline-block; background-color: #003566; color: #ffffff; text-decoration: none; font-weight: 600; font-size: 14px; padding: 12px 26px; border-radius: 6px; margin: 6px; box-shadow: 0 2px 8px rgba(0,53,102,0.25);">
                                    &#128196; Download PDF Receipt
                                </a>
                                <a href="' . $htmlReceiptUrl . '" style="display: inline-block; background-color: #f1f5f9; color: #334155; text-decoration: none; font-weight: 600; font-size: 14px; padding: 12px 22px; border-radius: 6px; margin: 6px; border: 1px solid #cbd5e1;">
                                    &#127760; View Receipt Online
                                </a>
                            </div>

                            <p style="margin: 24px 0 0 0; font-size: 13px; color: #64748b; line-height: 1.5; text-align: center;">
                                Note: This is a system-generated receipt. Please save this email and the attached link for your income tax deduction filings.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8fafc; padding: 24px 28px; border-top: 1px solid #e2e8f0; text-align: center; font-size: 12px; color: #64748b; line-height: 1.5;">
                            <strong style="color: #334155; font-size: 13px;">' . htmlspecialchars($ngoName) . '</strong><br>
                            ' . (!empty($ngoAddress) ? htmlspecialchars($ngoAddress) . '<br>' : '') . '
                            ' . (!empty($ngoEmail) ? 'Email: ' . htmlspecialchars($ngoEmail) . ' ' : '') . '
                            ' . (!empty($ngoPhone) ? '| Phone: ' . htmlspecialchars($ngoPhone) : '') . '
                            <div style="margin-top: 12px; font-size: 11px; color: #94a3b8;">
                                &copy; ' . date('Y') . ' ' . htmlspecialchars($ngoName) . '. All rights reserved.
                            </div>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>';

            $mailer = new Mailer($globalSettings);
            return $mailer->send($to, $subject, $html, true);
        } catch (\Throwable $e) {
            error_log('[ReceiptMailHelper] Failed to send receipt: ' . $e->getMessage());
            return false;
        }
    }
}
