<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Member;
use App\Models\Designation;
use App\Models\Donation;
use App\Models\Notice;
use App\Models\Setting;
use App\Models\AuditLog;

class MemberDashboardController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!isset($_SESSION['member_id'])) {
            $_SESSION['error'] = 'Please login to access your dashboard.';
            return $this->redirect('auth');
        }
    }

    public function dashboard()
    {
        $memberModel = new Member();
        $member = $memberModel->find($_SESSION['member_id']);

        if (!$member) {
            $_SESSION['error'] = 'Member not found.';
            return $this->redirect('auth');
        }

        $designationName = 'Member';
        if ($member->designation_id) {
            $designationModel = new Designation();
            $designation = $designationModel->find($member->designation_id);
            if ($designation) {
                $designationName = $designation->name;
            }
        }

        $donationModel = new Donation();
        $donationStats = $donationModel->getMemberStats($_SESSION['member_id']);
        if (!$donationStats) {
            $donationStats = (object)['total_donations' => 0, 'total_amount' => 0, 'recurring_count' => 0];
        }
        $recentDonations = $donationModel->getByMemberId($_SESSION['member_id'], 5);
        if (!$recentDonations) $recentDonations = [];

        return $this->view('member/dashboard', [
            'title' => 'Member Dashboard',
            'member' => $member,
            'designationName' => $designationName,
            'donationStats' => $donationStats,
            'recentDonations' => $recentDonations
        ]);
    }

    public function profile()
    {
        $memberModel = new Member();
        $member = $memberModel->find($_SESSION['member_id']);

        if (!$member) {
            $_SESSION['error'] = 'Member not found.';
            return $this->redirect('auth');
        }

        $designationModel = new Designation();
        $designations = $designationModel->all();

        return $this->view('member/profile', [
            'title' => 'My Profile',
            'member' => $member,
            'designations' => $designations
        ]);
    }

    public function donate()
    {
        $memberModel = new Member();
        $member = $memberModel->find($_SESSION['member_id']);
        if (!$member) {
            $_SESSION['error'] = 'Member not found.';
            return $this->redirect('member/dashboard');
        }

        $designationAmount = 0;
        $designationName = '';
        if ($member->designation_id) {
            $designationModel = new Designation();
            $designation = $designationModel->find($member->designation_id);
            if ($designation) {
                $designationAmount = $designation->monthly_amount ?? 0;
                $designationName = $designation->name;
            }
        }

        // Allow ?amount= to override the designation amount
        $getAmount = $_GET['amount'] ?? null;
        if ($getAmount !== null && is_numeric($getAmount) && $getAmount > 0) {
            $designationAmount = (float)$getAmount;
        }

        // Generate signed receipt URL if donation_uuid is present in query string
        $receiptUrl = null;
        $donationUuid = $_GET['donation_uuid'] ?? null;
        if ($donationUuid) {
            $receiptUrl = \App\Helpers\SignedUrlHelper::generateReceiptUrl($donationUuid, 86400); // 24 hours
        }

        return $this->view('member/donate', [
            'title' => 'Pay Membership Fee',
            'member' => $member,
            'designationAmount' => $designationAmount,
            'designationName' => $designationName,
            'receiptUrl' => $receiptUrl
        ]);
    }

    public function submitDonation()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect('member/donate');
        }

        $amount = filter_var($_POST['amount'] ?? 0, FILTER_VALIDATE_FLOAT);
        if ($amount === false || $amount <= 0) {
            $_SESSION['error'] = 'Amount must be a positive number.';
            return $this->redirect('member/donate');
        }
        if ($amount > MAX_AMOUNT) {
            $_SESSION['error'] = 'Amount exceeds maximum allowed value.';
            return $this->redirect('member/donate');
        }

        $data = [
            'member_id' => $_SESSION['member_id'],
            'donor_name' => trim($_POST['donor_name'] ?? ''),
            'donor_email' => trim($_POST['donor_email'] ?? ''),
            'donor_phone' => preg_replace('/[^0-9]/', '', $_POST['donor_phone'] ?? ''),
            'donor_address' => trim($_POST['donor_address'] ?? ''),
            'donor_city' => trim($_POST['donor_city'] ?? ''),
            'donor_state' => trim($_POST['donor_state'] ?? ''),
            'donor_pincode' => preg_replace('/[^0-9]/', '', $_POST['donor_pincode'] ?? ''),
            'donor_pan' => strtoupper(trim($_POST['donor_pan'] ?? '')),
            'amount' => $amount,
            'payment_method' => $_POST['payment_method'] ?? 'offline',
            'is_recurring' => !empty($_POST['is_recurring']) ? 1 : 0,
            'recurring_frequency' => !empty($_POST['is_recurring']) ? 'monthly' : null,
            'status' => 'pending'
        ];

        // Check for 80G claim
        $claim80g = !empty($_POST['claim_80g']);
        if (!$claim80g) {
            $data['donor_pan'] = '';
        }

        $donationModel = new Donation();
        if ($donationModel->create($data)) {
            $donationId = $donationModel->lastInsertId();
            $donation = $donationModel->find($donationId);
            AuditLog::log('create', 'donation', $donationId, null, $data);
            $donationUuid = $donation->uuid;
            return $this->redirect('member/donate?success=1&donation_uuid=' . $donationUuid . '&status=pending');
        } else {
            $_SESSION['error'] = 'Failed to process donation. Please try again.';
        }
        return $this->redirect('member/donate');
    }

    public function donationHistory()
    {
        $donationModel = new Donation();
        $donations = $donationModel->getByMemberId($_SESSION['member_id']);
        $stats = $donationModel->getMemberStats($_SESSION['member_id']);

        // Generate signed URLs for completed donations
        if ($donations) {
            foreach ($donations as $donation) {
                if ($donation->status === 'completed') {
                    $donation->receipt_url = \App\Helpers\SignedUrlHelper::generateReceiptUrl($donation->uuid, 86400);
                }
            }
        }

        return $this->view('member/donations', [
            'title' => 'My Fee Payments',
            'donations' => $donations,
            'stats' => $stats
        ]);
    }

    public function fees()
    {
        $memberModel = new Member();
        $member = $memberModel->find($_SESSION['member_id']);

        if (!$member) {
            $_SESSION['error'] = 'Member not found.';
            return $this->redirect('auth');
        }

        $currentYear = (int)date('Y');
        $memberJoinYear = $member->join_date ? (int)date('Y', strtotime($member->join_date)) : $currentYear;
        $selectedYear = isset($_GET['year']) ? (int)$_GET['year'] : $currentYear;
        $selectedMonth = isset($_GET['month']) ? (int)$_GET['month'] : 0;
        if ($selectedYear < $memberJoinYear || $selectedYear > $currentYear) $selectedYear = $currentYear;
        $designationAmount = 0;
        $designationName = '';
        if ($member->designation_id) {
            $designationModel = new Designation();
            $designation = $designationModel->find($member->designation_id);
            if ($designation) {
                $designationAmount = (float)($designation->monthly_amount ?? 0);
                $designationName = $designation->name;
            }
        }

        $memberJoinMonth = $member->join_date ? (int)date('n', strtotime($member->join_date)) : 1;

        $donationModel = new Donation();
        $donationYears = $donationModel->getFullYearRange($_SESSION['member_id']);
        $minYear = $memberJoinYear;
        if (!empty($donationYears)) {
            $minYear = min($minYear, min($donationYears));
        }
        $years = range($minYear, $currentYear);

        $monthNames = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
        ];

        $feesByYear = [];
        foreach ($years as $yr) {
            $paidMonths = $donationModel->getPaidMonthsByYear($_SESSION['member_id'], $yr);
            $receipts = $donationModel->getDonationReceiptsByYear($_SESSION['member_id'], $yr);
            $months = [];
            $currentMonthNum = (int)date('n');

            $startMonth = ($yr == $memberJoinYear) ? $memberJoinMonth : 1;

            // Show all months from join month through December (no future skipping)
            for ($m = $startMonth; $m <= 12; $m++) {
                $isPaid = in_array($m, $paidMonths);
                $receiptData = $isPaid && isset($receipts[$m]) ? $receipts[$m] : null;

                if ($yr == $currentYear && $m == $currentMonthNum) {
                    $status = $isPaid ? 'Paid' : 'Due';
                } elseif ($yr > $currentYear || ($yr == $currentYear && $m > $currentMonthNum)) {
                    $status = $isPaid ? 'Paid' : 'Upcoming';
                } else {
                    $status = $isPaid ? 'Paid' : 'Due';
                }

                $months[] = [
                    'month_num' => $m,
                    'month_name' => $monthNames[$m],
                    'year' => $yr,
                    'amount' => $designationAmount,
                    'status' => $status,
                    'is_paid' => $isPaid,
                    'is_current' => ($yr == $currentYear && $m == $currentMonthNum),
                    'receipt_uuid' => $receiptData ? $receiptData['uuid'] : null,
                    'receipt_url' => $receiptData ? \App\Helpers\SignedUrlHelper::generateReceiptUrl($receiptData['uuid'], 86400) : null,
                    'receipt_amount' => $receiptData ? $receiptData['amount'] : null,
                    'receipt_date' => $receiptData ? $receiptData['created_at'] : null,
                ];
            }

            $visiblePaidCount = 0;
            foreach ($months as $mo) {
                if ($mo['is_paid']) $visiblePaidCount++;
            }
            $feesByYear[] = [
                'year' => $yr,
                'months' => $months,
                'paid_count' => $visiblePaidCount,
                'total_months' => count($months),
                'total_amount' => $designationAmount * count($months),
                'paid_amount' => $designationAmount * $visiblePaidCount,
            ];
        }

        // Filter to selected year
        $feesByYear = array_values(array_filter($feesByYear, function($yrData) use ($selectedYear) {
            return $yrData['year'] == $selectedYear;
        }));

        // Filter to selected month
        if ($selectedMonth > 0) {
            foreach ($feesByYear as $i => $yrData) {
                $feesByYear[$i]['months'] = array_values(array_filter($yrData['months'], function($mo) use ($selectedMonth) {
                    return $mo['month_num'] == $selectedMonth;
                }));
                $feesByYear[$i]['paid_count'] = count(array_filter($feesByYear[$i]['months'], function($mo) {
                    return $mo['is_paid'];
                }));
                $feesByYear[$i]['total_months'] = count($feesByYear[$i]['months']);
                $feesByYear[$i]['total_amount'] = $designationAmount * $feesByYear[$i]['total_months'];
                $feesByYear[$i]['paid_amount'] = $designationAmount * $feesByYear[$i]['paid_count'];
            }
        }

        // Calculate total due
        $totalDue = 0;
        foreach ($feesByYear as $yr) {
            foreach ($yr['months'] as $mo) {
                if (!$mo['is_paid']) $totalDue += $mo['amount'];
            }
        }

        return $this->view('member/fees', [
            'title' => 'Fees Ledger',
            'member' => $member,
            'designationAmount' => $designationAmount,
            'designationName' => $designationName,
            'feesByYear' => $feesByYear,
            'totalDue' => $totalDue,
            'years' => $years,
            'selectedYear' => $selectedYear,
            'selectedMonth' => $selectedMonth,
            'monthNames' => $monthNames
        ]);
    }

    public function idCard()
    {
        $memberModel = new Member();
        $member = $memberModel->find($_SESSION['member_id']);

        if (!$member) {
            $_SESSION['error'] = 'Member not found.';
            return $this->redirect('member/dashboard');
        }

        $designation = null;
        if ($member->designation_id) {
            $designationModel = new Designation();
            $designation = $designationModel->find($member->designation_id);
        }

        $qrData = urlencode("Member ID: {$member->membership_id}\nName: {$member->name}\nPhone: {$member->phone}");
        $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={$qrData}";

        $settingModel = new Setting();
        $settingsData = $settingModel->all();
        $globalSettings = [];
        foreach ($settingsData as $setting) {
            $globalSettings[$setting->key_name] = $setting->key_value;
        }

        $validity = date('d M, Y', strtotime('+1 year', strtotime($member->join_date)));

        return $this->view('admin/members/id_card', [
            'title' => 'My ID Card',
            'member' => $member,
            'designation' => $designation,
            'qrUrl' => $qrUrl,
            'globalSettings' => $globalSettings,
            'validity' => $validity
        ]);
    }

    public function offerLetter()
    {
        $memberModel = new Member();
        $member = $memberModel->find($_SESSION['member_id']);

        if (!$member) {
            $_SESSION['error'] = 'Member not found.';
            return $this->redirect('member/dashboard');
        }

        $designation = null;
        if ($member->designation_id) {
            $designationModel = new Designation();
            $designation = $designationModel->find($member->designation_id);
        }

        $memberAddress = '';
        $addressParts = [];
        if (!empty($member->address_line)) $addressParts[] = $member->address_line;
        if (!empty($member->city)) $addressParts[] = $member->city;
        if (!empty($member->district)) $addressParts[] = $member->district;
        if (!empty($member->state)) $addressParts[] = $member->state;
        if (!empty($member->pin)) $addressParts[] = $member->pin;
        if (!empty($addressParts)) {
            $memberAddress = implode(', ', $addressParts);
        } elseif (!empty($member->address)) {
            $memberAddress = $member->address;
        }

        $joinDate = date('d M, Y', strtotime($member->join_date));
        $todayDate = date('d M, Y');

        $settingModel = new Setting();
        $settingsData = $settingModel->all();
        $globalSettings = [];
        foreach ($settingsData as $setting) {
            $globalSettings[$setting->key_name] = $setting->key_value;
        }

        return $this->view('admin/members/offer_letter', [
            'title' => 'My Offer Letter',
            'member' => $member,
            'designation' => $designation,
            'memberAddress' => $memberAddress,
            'joinDate' => $joinDate,
            'todayDate' => $todayDate,
            'globalSettings' => $globalSettings,
        ]);
    }

    public function notices()
    {
        $noticeModel = new Notice();
        $notices = $noticeModel->getActiveNotices();

        return $this->view('member/notices', [
            'title' => 'Notices',
            'notices' => $notices
        ]);
    }

    public function logout()
    {
        $memberId = $_SESSION['member_id'] ?? null;
        AuditLog::log('logout', 'auth', $memberId, null, ['login_type' => 'member']);
        session_regenerate_id(true);
        session_destroy();
        return $this->redirect('auth');
    }
}
