<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Mailer;
use App\Models\CMS;
use App\Models\Setting;
use App\Models\Member;
use App\Models\Donor;
use App\Models\Beneficiary;
use App\Models\Contact;
use App\Models\Donation;
use App\Models\Campaign;
use App\Models\RegistrationAttempt;
use App\Models\AuditLog;

class HomeController extends Controller
{
    protected $cmsModel;
    protected $settingModel;

    public function __construct()
    {
        parent::__construct();
        $this->cmsModel = new CMS();
        $this->settingModel = new Setting();
    }

    public function index()
    {
        $homePage = $this->cmsModel->getPage('home');
        $aboutPage = $this->cmsModel->getPage('about');
        $sliderMedia = $this->cmsModel->getMedia('slider');
        
        $memberModel = new Member();
        $donorModel = new Donor();
        $beneficiaryModel = new Beneficiary();
        $donationModel = new Donation();
        $projectModel = new \App\Models\Project();
        $newsModel = new \App\Models\News();
        $campaignModel = new Campaign();

        $recentProjects = $projectModel->getActiveProjects();
        $recentProjects = array_slice($recentProjects, 0, 3);

        $recentNews = $newsModel->getActiveNews();
        $recentNews = array_slice($recentNews, 0, 3);

        $activeCampaigns = $campaignModel->getActiveCampaigns();
        $activeCampaigns = array_slice($activeCampaigns, 0, 3);

        $galleryItems = $this->cmsModel->getMedia('gallery');
        $galleryPreview = array_slice($galleryItems, 0, 4);

        $certificateItems = $this->cmsModel->getMedia('certificate');
        $achievementItems = $this->cmsModel->getMedia('achievement');

        return $this->view('home', [
            'title' => $homePage->title ?? 'Welcome to NGO HELP',
            'cms' => $homePage,
            'aboutPage' => $aboutPage,
            'slider' => $sliderMedia,
            'recentProjects' => $recentProjects,
            'recentNews' => $recentNews,
            'activeCampaigns' => $activeCampaigns,
            'galleryPreview' => $galleryPreview,
            'certificateCount' => count($certificateItems),
            'achievementCount' => count($achievementItems),
            'stats' => [
                'members' => $memberModel->count(),
                'donors' => $donorModel->count(),
                'beneficiaries' => $beneficiaryModel->count(),
                'donations' => $donationModel->totalCompleted()
            ]
        ]);
    }

    public function about()
    {
        $aboutPage = $this->cmsModel->getPage('about');
        return $this->view('about', [
            'title' => $aboutPage->title ?? 'About Us',
            'cms' => $aboutPage
        ]);
    }

    public function contact()
    {
        return $this->view('contact', [
            'title' => 'Contact Us'
        ]);
    }

    public function submitContact()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'] ?? '',
                'email' => $_POST['email'] ?? '',
                'subject' => $_POST['subject'] ?? '',
                'message' => $_POST['message'] ?? ''
            ];

            $contactModel = new Contact();
            if ($contactModel->create($data)) {
                $_SESSION['success'] = 'Thank you! Your message has been sent successfully.';
            } else {
                $_SESSION['error'] = 'Failed to send message. Please try again later.';
            }
        }
        return $this->redirect('contact');
    }

    public function gallery()
    {
        $galleryMedia = $this->cmsModel->getMedia('gallery');
        return $this->view('gallery', [
            'title' => 'Our Gallery',
            'gallery' => $galleryMedia
        ]);
    }

    public function certificates()
    {
        $media = $this->cmsModel->getMedia('certificate');
        return $this->view('certificates', [
            'title' => 'Our Certificates',
            'media' => $media
        ]);
    }

    public function achievements()
    {
        $media = $this->cmsModel->getMedia('achievement');
        return $this->view('achievements', [
            'title' => 'Our Achievements',
            'media' => $media
        ]);
    }

    public function news()
    {
        $newsModel = new \App\Models\News();
        $news = $newsModel->getActiveNews();
        return $this->view('news/index', [
            'title' => 'Latest News',
            'news' => $news
        ]);
    }

    public function newsDetail($slug)
    {
        $newsModel = new \App\Models\News();
        $items = $newsModel->where('slug', $slug);
        if (empty($items)) {
            return $this->redirect('news');
        }
        $news = $items[0];
        return $this->view('news/show', [
            'title' => $news->title,
            'news' => $news
        ]);
    }

    public function projects()
    {
        $projectModel = new \App\Models\Project();
        $status = $_GET['status'] ?? 'active';
        if ($status === 'all') {
            $projects = $projectModel->all();
        } elseif ($status === 'completed') {
            $projects = $projectModel->where('status', 'completed');
        } elseif ($status === 'planned') {
            $projects = $projectModel->where('status', 'planned');
        } else {
            $projects = $projectModel->getActiveProjects();
        }
        return $this->view('projects/index', [
            'title' => 'Our Projects',
            'projects' => $projects,
            'currentStatus' => $status
        ]);
    }

    public function projectDetail($slug)
    {
        $projectModel = new \App\Models\Project();
        $items = $projectModel->where('slug', $slug);
        if (empty($items)) {
            return $this->redirect('projects');
        }
        $project = $items[0];
        $gallery = $projectModel->getGallery($project->id);
        
        return $this->view('projects/show', [
            'title' => $project->title,
            'project' => $project,
            'gallery' => $gallery
        ]);
    }

    public function campaigns()
    {
        $campaignModel = new Campaign();
        $campaigns = $campaignModel->getActiveCampaigns();
        return $this->view('campaigns/index', [
            'title' => 'Our Campaigns',
            'campaigns' => $campaigns
        ]);
    }

    public function campaignDetail($slug)
    {
        $campaignModel = new Campaign();
        $campaign = $campaignModel->getBySlug($slug);
        if (!$campaign) {
            return $this->redirect('campaigns');
        }
        return $this->view('campaigns/show', [
            'title' => $campaign->title,
            'campaign' => $campaign
        ]);
    }

    public function careers()
    {
        $careerModel = new \App\Models\Career();
        $careerModel->autoCloseExpired();
        $careers = $careerModel->getActiveCareers();
        return $this->view('careers/index', [
            'title' => 'Careers & Opportunities',
            'careers' => $careers
        ]);
    }

    public function careerDetail($slug)
    {
        $careerModel = new \App\Models\Career();
        $items = $careerModel->where('slug', $slug);
        if (empty($items)) {
            return $this->redirect('careers');
        }
        $career = $items[0];
        return $this->view('careers/show', [
            'title' => $career->title,
            'career' => $career
        ]);
    }

    public function showApplicationForm($slug)
    {
        $careerModel = new \App\Models\Career();
        $items = $careerModel->where('slug', $slug);
        if (empty($items) || $items[0]->status !== 'open') {
            $_SESSION['error'] = 'This position is no longer accepting applications.';
            return $this->redirect('careers');
        }
        $career = $items[0];
        return $this->view('careers/apply', [
            'title' => 'Apply for ' . $career->title,
            'career' => $career
        ]);
    }

    public function submitApplication($slug)
    {
        $careerModel = new \App\Models\Career();
        $items = $careerModel->where('slug', $slug);
        if (empty($items) || $items[0]->status !== 'open') {
            $_SESSION['error'] = 'This position is no longer accepting applications.';
            return $this->redirect('careers');
        }
        $career = $items[0];

        $resumePath = null;
        if (isset($_FILES['resume']) && $_FILES['resume']['error'] === UPLOAD_ERR_OK) {
            $valid = validate_upload($_FILES['resume'], ['pdf', 'doc', 'docx'], ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'], 2 * 1024 * 1024);
            if ($valid !== true) {
                $_SESSION['error'] = 'Resume: ' . $valid;
                return $this->redirect('careers/apply/' . $slug);
            }
            $uploadDir = UPLOAD_PATH . 'careers/resumes/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
            $ext = strtolower(pathinfo($_FILES['resume']['name'], PATHINFO_EXTENSION));
            $fileName = time() . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
            if (move_uploaded_file($_FILES['resume']['tmp_name'], $uploadDir . $fileName)) {
                $resumePath = 'uploads/careers/resumes/' . $fileName;
            }
        }

        $applicationModel = new \App\Models\JobApplication();
        $data = [
            'career_id' => $career->id,
            'name' => $_POST['name'] ?? '',
            'email' => $_POST['email'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'address' => $_POST['address'] ?? '',
            'district' => $_POST['district'] ?? '',
            'city' => $_POST['city'] ?? '',
            'pincode' => $_POST['pincode'] ?? '',
            'state' => $_POST['state'] ?? '',
            'cover_letter' => $_POST['cover_letter'] ?? '',
            'resume' => $resumePath,
            'status' => 'pending'
        ];

        if ($applicationModel->create($data)) {
            AuditLog::log('create', 'job_application', $applicationModel->lastInsertId(), null, ['name' => $data['name'], 'career_id' => $data['career_id']]);
            $_SESSION['success'] = 'Your application has been submitted successfully! We will review it and get back to you.';
        } else {
            $_SESSION['error'] = 'Failed to submit application. Please try again.';
        }
        return $this->redirect('careers/apply/' . $slug);
    }

    public function donate()
    {
        return $this->view('donate', [
            'title' => 'Donate Now'
        ]);
    }

    private function checkDonationRateLimit()
    {
        $key = '_donate_limit_' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown');
        $now = time();
        $window = $_SESSION[$key] ?? [];
        $window = array_filter($window, fn($t) => $t > $now - 3600);
        if (count($window) >= 10) {
            return false;
        }
        $window[] = $now;
        $_SESSION[$key] = $window;
        return true;
    }

    public function submitDonation()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->checkDonationRateLimit()) {
                $_SESSION['error'] = 'Too many donation attempts. Please try later.';
                return $this->redirect('donate');
            }
        // Mark any previous pending donation from this session as failed (page refresh / abandon)
        $prevDonationId = $_SESSION['razorpay_donation_id'] ?? null;
        if ($prevDonationId) {
            $prevModel = new Donation();
            $prevModel->update($prevDonationId, ['status' => 'failed']);
            unset($_SESSION['razorpay_donation_id'], $_SESSION['razorpay_order_amount'], $_SESSION['razorpay_order_id']);
        }

        $amount = filter_var($_POST['amount'] ?? 0, FILTER_VALIDATE_FLOAT);
            if ($amount === false || $amount <= 0) {
                $_SESSION['error'] = 'Amount must be a positive number.';
                return $this->redirect('donate');
            }
            if ($amount > MAX_AMOUNT) {
                $_SESSION['error'] = 'Amount exceeds maximum allowed value.';
                return $this->redirect('donate');
            }

            $data = [
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
                'campaign_id' => !empty($_POST['campaign_id']) ? (int)$_POST['campaign_id'] : null,
                'status' => 'pending'
            ];
            $donationModel = new Donation();
            if ($donationModel->create($data)) {
                $donationUuid = $donationModel->find($donationModel->lastInsertId())->uuid;
                $campaignSlug = trim($_POST['campaign_slug'] ?? '');
                if ($campaignSlug) {
                    return $this->redirect('campaigns/' . $campaignSlug . '?donation_success=1&donation_uuid=' . $donationUuid);
                }
                return $this->redirect('donate?success=1&donation_uuid=' . $donationUuid . '&status=pending');
            } else {
                $_SESSION['error'] = 'Failed to process donation. Please try again.';
            }
        }
        if (!empty($_POST['campaign_slug'])) {
            return $this->redirect('campaigns/' . trim($_POST['campaign_slug']));
        }
        return $this->redirect('donate');
    }

    public function createRazorpayOrder()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            json_response(['status' => 'error', 'message' => 'Invalid request']);
            exit;
        }

        $mode = $this->globalSettings['razorpay_mode'] ?? 'test';
        if ($mode === 'live') {
            $keyId = $this->globalSettings['razorpay_live_key_id'] ?? $this->globalSettings['razorpay_key_id'] ?? '';
            $keySecret = $this->globalSettings['razorpay_live_key_secret'] ?? $this->globalSettings['razorpay_key_secret'] ?? '';
        } else {
            $keyId = $this->globalSettings['razorpay_test_key_id'] ?? $this->globalSettings['razorpay_key_id'] ?? '';
            $keySecret = $this->globalSettings['razorpay_test_key_secret'] ?? $this->globalSettings['razorpay_key_secret'] ?? '';
        }

        if (empty($keyId) || empty($keySecret)) {
            json_response(['status' => 'error', 'message' => 'Razorpay not configured.']);
            exit;
        }

        $amount = filter_var($_POST['amount'] ?? 0, FILTER_VALIDATE_FLOAT);
        if ($amount === false || $amount < 1) {
            json_response(['status' => 'error', 'message' => 'Invalid donation amount.']);
            exit;
        }
        if ($amount > MAX_AMOUNT) {
            json_response(['status' => 'error', 'message' => 'Amount exceeds maximum allowed value.']);
            exit;
        }
        $amountPaise = (int) round($amount * 100);
        $receipt = 'DON_' . time() . '_' . bin2hex(random_bytes(4));

        $postData = [
            'amount' => $amountPaise,
            'currency' => 'INR',
            'receipt' => $receipt,
            'payment_capture' => 1
        ];

        $ch = curl_init('https://api.razorpay.com/v1/orders');
        curl_setopt($ch, CURLOPT_USERPWD, $keyId . ':' . $keySecret);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        $response = curl_exec($ch);
        $curlError = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($curlError) {
            json_response(['status' => 'error', 'message' => 'Payment service unavailable.']);
            exit;
        }

        if ($httpCode === 200) {
            $order = json_decode($response, true);
            
            $pan = strtoupper(trim($_POST['donor_pan'] ?? ''));
            $claim80g = !empty($_POST['claim_80g']);

            $pendingData = [
                'donor_name' => trim($_POST['donor_name'] ?? ''),
                'donor_email' => trim($_POST['donor_email'] ?? ''),
                'donor_phone' => preg_replace('/[^0-9]/', '', $_POST['donor_phone'] ?? ''),
                'donor_address' => trim($_POST['donor_address'] ?? ''),
                'donor_city' => trim($_POST['donor_city'] ?? ''),
                'donor_state' => trim($_POST['donor_state'] ?? ''),
                'donor_pincode' => preg_replace('/[^0-9]/', '', $_POST['donor_pincode'] ?? ''),
                'donor_pan' => $claim80g ? $pan : '',
                'amount' => $amount,
                'payment_method' => 'razorpay',
                'transaction_id' => $order['id'],
                'member_id' => !empty($_POST['member_id']) ? (int)$_POST['member_id'] : null,
                'is_recurring' => !empty($_POST['is_recurring']) ? 1 : 0,
                'recurring_frequency' => !empty($_POST['is_recurring']) ? 'monthly' : null,
                'campaign_id' => !empty($_POST['campaign_id']) ? (int)$_POST['campaign_id'] : null,
                'status' => 'pending'
            ];
            $donationUuid = '';
            $donationModel = new Donation();
            if ($donationModel->create($pendingData)) {
                $donationId = $donationModel->lastInsertId();
                $_SESSION['razorpay_donation_id'] = $donationId;
                $donation = $donationModel->find($donationId);
                $donationUuid = $donation ? $donation->uuid : '';
            }
            
            // Store the verified order amount in session for verification on return
            $_SESSION['razorpay_order_amount'] = $order['amount'];
            $_SESSION['razorpay_order_id'] = $order['id'];
            
            json_response([
                'status' => 'success',
                'order_id' => $order['id'],
                'amount' => $order['amount'],
                'key_id' => $keyId,
                'donation_uuid' => $donationUuid
            ]);
        } else {
            $errorBody = json_decode($response, true);
            $errorMsg = $errorBody['error']['description'] ?? 'Failed to create order.';
            json_response(['status' => 'error', 'message' => $errorMsg]);
        }
        exit;
    }

    public function verifyRazorpayPayment()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            json_response(['status' => 'error', 'message' => 'Invalid request']);
            exit;
        }

        $mode = $this->globalSettings['razorpay_mode'] ?? 'test';
        $keySecret = $mode === 'live'
            ? ($this->globalSettings['razorpay_live_key_secret'] ?? $this->globalSettings['razorpay_key_secret'] ?? '')
            : ($this->globalSettings['razorpay_test_key_secret'] ?? $this->globalSettings['razorpay_key_secret'] ?? '');
        $orderId = $_POST['razorpay_order_id'] ?? '';
        $paymentId = $_POST['razorpay_payment_id'] ?? '';
        $signature = $_POST['razorpay_signature'] ?? '';

        $expected = hash_hmac('sha256', $orderId . '|' . $paymentId, $keySecret);

        if (!hash_equals($expected, $signature)) {
            json_response(['status' => 'error', 'message' => 'Payment verification failed.']);
            exit;
        }

        // Verify that the order amount matches what was stored during order creation
        $expectedAmount = $_SESSION['razorpay_order_amount'] ?? null;
        $expectedOrderId = $_SESSION['razorpay_order_id'] ?? null;
        
        if ($expectedAmount === null || $expectedOrderId === null) {
            json_response(['status' => 'error', 'message' => 'Session expired. Please try again.']);
            exit;
        }
        
        if ($expectedOrderId !== $orderId) {
            json_response(['status' => 'error', 'message' => 'Order ID mismatch. Transaction rejected.']);
            exit;
        }
        
        // Clear stored session data to prevent replay
        unset($_SESSION['razorpay_order_amount'], $_SESSION['razorpay_order_id']);

        // Fetch order details from Razorpay to verify amount
        $keyId = $mode === 'live'
            ? ($this->globalSettings['razorpay_live_key_id'] ?? $this->globalSettings['razorpay_key_id'] ?? '')
            : ($this->globalSettings['razorpay_test_key_id'] ?? $this->globalSettings['razorpay_key_id'] ?? '');

        $ch = curl_init('https://api.razorpay.com/v1/orders/' . $orderId);
        curl_setopt($ch, CURLOPT_USERPWD, $keyId . ':' . $keySecret);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        $orderResponse = curl_exec($ch);
        $curlError = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($curlError || $httpCode !== 200) {
            json_response(['status' => 'error', 'message' => 'Failed to verify order details.']);
            exit;
        }

        $orderData = json_decode($orderResponse, true);
        $razorpayOrderAmount = (int) ($orderData['amount'] ?? 0); // Amount in paise from Razorpay

        $amount = (float) ($_POST['amount'] ?? 0);
        $submittedAmountPaise = (int) round($amount * 100);

        // Verify that submitted amount matches Razorpay order amount
        if ($submittedAmountPaise !== $razorpayOrderAmount) {
            json_response(['status' => 'error', 'message' => 'Payment amount mismatch. Transaction rejected.']);
            exit;
        }
        $donationModel = new Donation();
        $donationId = $_SESSION['razorpay_donation_id'] ?? null;

        if ($donationId) {
            // Update the pending donation to completed
            $updateData = [
                'transaction_id' => $paymentId,
                'status' => 'completed'
            ];
            if ($donationModel->update($donationId, $updateData)) {
                $donation = $donationModel->find($donationId);
                $donationUuid = $donation->uuid;
                if (!empty($donation->campaign_id)) {
                    $campaignModel = new \App\Models\Campaign();
                    $campaignModel->updateRaisedAmount($donation->campaign_id);
                }
                $receiptUrl = \App\Helpers\SignedUrlHelper::generateReceiptUrl($donationUuid, 86400);
                unset($_SESSION['razorpay_donation_id'], $_SESSION['razorpay_order_amount'], $_SESSION['razorpay_order_id']);
                json_response([
                    'status' => 'success', 
                    'message' => 'Donation completed successfully!', 
                    'donation_uuid' => $donationUuid,
                    'receipt_url' => $receiptUrl
                ]);
            } else {
                json_response(['status' => 'error', 'message' => 'Failed to update donation record.']);
            }
        } else {
            json_response(['status' => 'error', 'message' => 'Session expired. No pending donation found.']);
        }
        exit;
    }

    public function markRazorpayFailed()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            json_response(['status' => 'error', 'message' => 'Invalid request']);
            exit;
        }

        $donationModel = new Donation();
        $donationId = $_SESSION['razorpay_donation_id'] ?? null;
        $donationUuid = $_POST['donation_uuid'] ?? '';

        if ($donationUuid) {
            $donation = $donationModel->findByUuid($donationUuid);
            if ($donation && $donation->status === 'pending') {
                $donationModel->update($donation->id, ['status' => 'failed']);
            }
        } elseif ($donationId) {
            $donationModel->update($donationId, ['status' => 'failed']);
        } else {
            json_response(['status' => 'error', 'message' => 'No pending donation found.']);
            exit;
        }

        unset($_SESSION['razorpay_donation_id'], $_SESSION['razorpay_order_amount'], $_SESSION['razorpay_order_id']);
        json_response(['status' => 'success', 'message' => 'Donation marked as failed.']);
        exit;
    }

    public function receipt($uuid)
    {
        // Verify signed URL for security
        $verification = verify_signed_url();
        
        if (!$verification['valid']) {
            $errorMessage = $verification['error'] ?? 'This link is invalid or has expired.';
            echo '<!DOCTYPE html><html><head><title>Receipt Unavailable</title><style>body{font-family:sans-serif;display:flex;align-items:center;justify-content:center;min-height:100vh;background:#f5f5f5;margin:0}.card{background:#fff;padding:2rem;border-radius:1rem;text-align:center;box-shadow:0 4px 12px rgba(0,0,0,.1)}h2{color:#333;margin-bottom:.5rem}p{color:#666}a{color:#003566;text-decoration:none;display:inline-block;margin-top:1rem;padding:0.5rem 1rem;background:#003566;color:#fff;border-radius:0.5rem}</style></head><body><div class="card"><h2>Receipt Unavailable</h2><p>' . htmlspecialchars($errorMessage) . '</p><p style="font-size:0.9em;color:#999;margin-top:1rem">Please request a new receipt link from the donation confirmation email.</p><a href="/donate">Back to Donate</a></div></body></html>';
            exit;
        }
        
        $donationModel = new Donation();
        $donation = $donationModel->findByUuid($uuid);
        if (!$donation || $donation->status !== 'completed') {
            echo '<!DOCTYPE html><html><head><title>Receipt</title><style>body{font-family:sans-serif;display:flex;align-items:center;justify-content:center;min-height:100vh;background:#f5f5f5;margin:0}.card{background:#fff;padding:2rem;border-radius:1rem;text-align:center;box-shadow:0 4px 12px rgba(0,0,0,.1)}h2{color:#333;margin-bottom:.5rem}p{color:#666}a{color:#003566}</style></head><body><div class="card"><h2>Receipt Unavailable</h2><p>This receipt is not available yet. Please check back after the donation is confirmed.</p><a href="/donate">Back to Donate</a></div></body></html>';
            exit;
        }

        if (isset($_GET['html'])) {
            return $this->view('admin/finance/receipt', [
                'title' => 'Donation Receipt',
                'd' => $donation,
            ]);
        }

        require_once __DIR__ . '/../helpers/PdfGenerator.php';
        $pdf = new \App\Helpers\PdfGenerator();
        $pdf->setTitle('Donation Receipt');
        $pdf->addPage();

        $ngoName = $this->globalSettings['ngo_name'] ?? 'NGO HELP';
        $ngoAddr = $this->globalSettings['ngo_address'] ?? '';
        $ngoEmail = $this->globalSettings['ngo_email'] ?? '';
        $ngoPhone = $this->globalSettings['ngo_phone'] ?? '';

        $pw = 186;      // content width
        $ml = 12;       // left margin
        $pageW = $pdf->getPageWidth();
        $pageH = $pdf->getPageHeight();
        $mr = $ml + $pw;
        $publicDir = defined('PUBLIC_PATH') ? PUBLIC_PATH : (realpath(__DIR__ . '/../../public/') . '/');

        // === GRADIENT TOP BAR (4 colors across full width) ===
        $barW = $pageW / 4;
        $colors = [[231, 76, 60], [243, 156, 18], [46, 204, 113], [52, 152, 219]];
        for ($i = 0; $i < 4; $i++) {
            $pdf->filledRect($i * $barW, 0, $barW, 2.5, $colors[$i][0], $colors[$i][1], $colors[$i][2]);
        }

        $y = 14;

        // === HEADER: org info (left) + receipt badge (right) ===
        $leftW = $pw * 0.58;
        $rightW = $pw * 0.42;

        // Left: Organization Name + Details
        $pdf->setFont('Helvetica', 'B', 18);
        $pdf->setXY($ml, $y);
        $pdf->cell($leftW, 8, $ngoName, 0, 1, 'L');
        $y = $pdf->getY();

        if ($ngoAddr || $ngoEmail || $ngoPhone) {
            $pdf->setFont('Helvetica', '', 9);
            $pdf->setTextColor(100, 100, 100);
            if ($ngoAddr) {
                $pdf->setXY($ml, $y);
                $pdf->cell($leftW, 5, $ngoAddr, 0, 1, 'L');
                $y = $pdf->getY();
            }
            $contactStr = '';
            if ($ngoEmail) $contactStr .= 'Email: ' . $ngoEmail;
            if ($ngoPhone) $contactStr .= ($contactStr ? '  |  ' : '') . 'Phone: ' . $ngoPhone;
            if ($contactStr) {
                $pdf->setXY($ml, $y);
                $pdf->cell($leftW, 5, $contactStr, 0, 1, 'L');
                $y = $pdf->getY();
            }
            $pdf->setTextColor(0, 0, 0);
        }

        // Right: Receipt Title + Number + Date
        $rightX = $ml + $leftW;
        $pdf->setFont('Helvetica', 'B', 22);
        $pdf->setXY($rightX, 16);
        $pdf->cell($rightW, 9, 'Receipt', 0, 1, 'R');
        $pdf->setFont('Helvetica', '', 8);
        $pdf->setTextColor(150, 150, 150);
        $pdf->setXY($rightX, $pdf->getY());
        $pdf->cell($rightW, 5, 'TAX EXEMPTION UNDER 80G', 0, 1, 'R');
        $pdf->setTextColor(0, 0, 0);

        $rn = 'DON-' . str_pad($donation->id, 5, '0', STR_PAD_LEFT);
        $rd = date('d M Y', strtotime($donation->created_at));
        $pdf->setFont('Helvetica', '', 9);
        $pdf->setXY($rightX, $pdf->getY() + 2);
        $pdf->cell($rightW, 5, 'Receipt # ' . $rn, 0, 1, 'R');
        $pdf->setXY($rightX, $pdf->getY());
        $pdf->cell($rightW, 5, 'Date: ' . $rd, 0, 1, 'R');

        $y = max($y, $pdf->getY() + 6);

        // === SEPARATOR ===
        $pdf->setTextColor(230, 230, 230);
        $pdf->line($ml, $y, $mr, $y);
        $pdf->setTextColor(0, 0, 0);
        $y += 8;

        // === DONOR + PAYMENT CARDS (side by side) ===
        $cardW = ($pw - 6) / 2;
        $cardH = 36;
        $cardY = $y;

        // Card 1: Donor Details
        $cx = $ml;
        $pdf->filledRect($cx, $cardY, $cardW, $cardH, 248, 249, 252);
        $pdf->setFont('Helvetica', '', 7);
        $pdf->setTextColor(153, 153, 153);
        $pdf->setXY($cx + 4, $cardY + 4);
        $pdf->cell($cardW - 8, 4, 'DONOR DETAILS', 0, 1, 'L');
        $pdf->setTextColor(0, 0, 0);
        $pdf->setFont('Helvetica', 'B', 11);
        $pdf->setXY($cx + 4, $cardY + 10);
        $pdf->cell($cardW - 8, 6, $donation->donor_name, 0, 1, 'L');

        $pdf->setFont('Helvetica', '', 8);
        $pdf->setTextColor(136, 136, 136);
        $dvY = $cardY + 18;
        if ($donation->donor_address) {
            $pdf->setXY($cx + 4, $dvY);
            $pdf->cell($cardW - 8, 4, $donation->donor_address, 0, 1, 'L');
            $dvY = $pdf->getY();
        } else {
            $dvY += 4;
        }
        if (!empty($donation->donor_pan)) {
            $pdf->setXY($cx + 4, $dvY);
            $pdf->setTextColor(0, 0, 0);
            $pdf->setFont('Helvetica', 'B', 8);
            $pdf->cell(12, 4, 'PAN:', 0, 0, 'L');
            $pdf->setFont('Helvetica', '', 8);
            $pdf->setTextColor(136, 136, 136);
            $pdf->cell($cardW - 24, 4, $donation->donor_pan, 0, 1, 'L');
        }

        // Card 2: Payment Details
        $methodMap = [
            'razorpay' => 'Online Card / UPI', 'phonepe' => 'UPI', 'offline' => 'Cash',
            'upi' => 'UPI', 'card' => 'Card', 'bank_transfer' => 'Bank Transfer', 'cash' => 'Cash'
        ];
        $cx2 = $ml + $cardW + 6;
        $pdf->filledRect($cx2, $cardY, $cardW, $cardH, 248, 249, 252);
        $pdf->setFont('Helvetica', '', 7);
        $pdf->setTextColor(153, 153, 153);
        $pdf->setXY($cx2 + 4, $cardY + 4);
        $pdf->cell($cardW - 8, 4, 'PAYMENT DETAILS', 0, 1, 'L');
        $pdf->setTextColor(0, 0, 0);
        $pdf->setFont('Helvetica', 'B', 16);
        $pdf->setXY($cx2 + 4, $cardY + 10);
        $pdf->cell($cardW - 8, 8, 'Rs. ' . number_format($donation->amount, 2), 0, 1, 'L');

        $pdY = $cardY + 21;
        $pdf->setFont('Helvetica', '', 8);
        $pdf->setTextColor(136, 136, 136);
        $payMethod = $methodMap[$donation->payment_method] ?? ucfirst($donation->payment_method);
        $pdf->setXY($cx2 + 4, $pdY);
        $pdf->cell($cardW - 8, 4, 'Mode: ' . $payMethod, 0, 1, 'L');
        $pdY = $pdf->getY();
        if ($donation->transaction_id) {
            $pdf->setXY($cx2 + 4, $pdY);
            $pdf->cell($cardW - 8, 4, 'Txn ID: ' . $donation->transaction_id, 0, 1, 'L');
        }

        $y = $cardY + $cardH + 10;
        $pdf->setTextColor(0, 0, 0);

        // === AMOUNT IN WORDS ===
        $pdf->setFont('Helvetica', 'I', 11);
        $pdf->setTextColor(100, 100, 100);
        $pdf->setXY($ml, $y);
        $pdf->cell($pw, 6, 'Amount in words: Rupees ' . $pdf->numberToWords($donation->amount) . ' Only', 0, 1, 'L');
        $y = $pdf->getY() + 6;
        $pdf->setTextColor(0, 0, 0);

        // === TAX EXEMPTION SECTION ===
        $taxY = $y;
        $taxH = 24;
        // Red left border
        $pdf->filledRect($ml, $taxY, 1.5, $taxH, 231, 76, 60);
        // Light red background
        $pdf->filledRect($ml + 1.5, $taxY, $pw - 1.5, $taxH, 253, 246, 246);
        $pdf->setFont('Helvetica', '', 8);
        $pdf->setTextColor(100, 100, 100);
        $pdf->setXY($ml + 6, $taxY + 3);
        $pdf->cell($pw - 12, 4, 'Donations to ' . $ngoName . ' are exempt under Section 80G of the Income Tax Act, 1961.', 0, 1, 'L');
        $pdf->setXY($ml + 6, $taxY + 9);
        $pdf->cell($pw - 12, 4, 'This receipt is valid for claiming deduction. This is a system-generated receipt', 0, 1, 'L');
        $pdf->setXY($ml + 6, $taxY + 14);
        $pdf->cell($pw - 12, 4, 'and does not require a physical signature.', 0, 1, 'L');
        $y = $taxY + $taxH + 14;

        // === FOOTER ===
        $fy = $pageH - 30;
        // Separator line
        $pdf->line($ml, $fy, $mr, $fy);

        // Left: org info
        $pdf->setFont('Helvetica', 'B', 8);
        $pdf->setTextColor(100, 100, 100);
        $pdf->setXY($ml, $fy + 4);
        $pdf->cell($pw * 0.5, 4, $ngoName, 0, 1, 'L');
        $pdf->setFont('Helvetica', '', 7);
        $pdf->setTextColor(170, 170, 170);
        $pdf->setXY($ml, $fy + 9);
        $pdf->cell($pw * 0.5, 4, $ngoAddr, 0, 1, 'L');
        $pdf->setXY($ml, $fy + 14);
        $pdf->cell($pw * 0.5, 4, 'Thank you for your generosity!', 0, 1, 'L');

        // Center: Signature
        $sigX = $ml + $pw * 0.5 - 40;
        $sigPath = $this->globalSettings['ngo_signature'] ?? '';
        $sigImgY = $fy + 2;
        if ($sigPath) {
            $sigFile = $publicDir . ltrim($sigPath, '/\\');
            if (file_exists($sigFile)) {
                $pdf->image($sigFile, $sigX, $sigImgY, 50, 14);
                $sigImgY += 12;
            }
        }
        $pdf->line($sigX, $sigImgY + 4, $sigX + 50, $sigImgY + 4);
        $pdf->setFont('Helvetica', '', 8);
        $pdf->setTextColor(153, 153, 153);
        $pdf->setXY($sigX, $sigImgY + 6);
        $pdf->cell(50, 4, 'Authorised Signatory', 0, 0, 'C');

        // Right: Stamp area
        $stampX = $mr - 52;
        $pdf->rect($stampX, $fy + 2, 48, 36);
        $pdf->rect($stampX + 1, $fy + 3, 46, 34);
        // Dashed circle approximation
        $pdf->setTextColor(200, 200, 200);
        $pdf->setFont('Helvetica', '', 7);
        $pdf->setXY($stampX, $fy + 16);
        $pdf->cell(48, 5, 'STAMP', 0, 1, 'C');
        $pdf->setTextColor(0, 0, 0);

        $pdf->output('receipt-DON-' . str_pad($donation->id, 5, '0', STR_PAD_LEFT) . '.pdf');
        exit;
    }

    public function beneficiaries()
    {
        $beneficiaryModel = new Beneficiary();
        $beneficiaries = $beneficiaryModel->where('status', 'active');
        return $this->view('beneficiaries', [
            'title' => 'Our Beneficiaries',
            'beneficiaries' => $beneficiaries
        ]);
    }

    public function members()
    {
        $memberModel = new Member();
        $members = $memberModel->getActiveWithDesignation();
        return $this->view('members', [
            'title' => 'Our Members',
            'members' => $members
        ]);
    }

    public function showRegister()
    {
        $designationModel = new \App\Models\Designation();
        $designations = $designationModel->where('show_in_form', 1);

        captcha_generate();

        return $this->view('members/register', [
            'title' => 'Member Registration',
            'designations' => $designations,
        ]);
    }

    public function register()
    {
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';

        // --- Rate Limiting ---
        $regAttemptModel = new RegistrationAttempt();
        $maxRegAttempts = 3;
        $regWindowMinutes = 60;

        if ($regAttemptModel->countRecentByIp($ip, $regWindowMinutes) >= $maxRegAttempts) {
            $_SESSION['error'] = 'Too many registration attempts from your IP. Please try again later.';
            return $this->redirect('members/register');
        }
        if (!empty($email) && $regAttemptModel->countRecentByEmail($email, $regWindowMinutes) >= $maxRegAttempts) {
            $_SESSION['error'] = 'Too many registration attempts for this email. Please try again later.';
            return $this->redirect('members/register');
        }

        // --- CAPTCHA Verification ---
        $captchaAnswer = $_POST['_captcha'] ?? '';
        if (!verify_captcha($captchaAnswer)) {
            $regAttemptModel->record($email, $ip);
            $_SESSION['error'] = 'Incorrect CAPTCHA answer. Please try again.';
            return $this->redirect('members/register');
        }

        // --- Existing email/phone check ---
        $memberModel = new Member();
        if (!empty($email) && $memberModel->existsByEmail($email)) {
            $_SESSION['rejection'] = 'This email address is already registered. Please use a different email or login to your existing account.';
            return $this->redirect('members/register');
        }
        if (!empty($phone) && $memberModel->existsByPhone($phone)) {
            $_SESSION['rejection'] = 'This phone number is already registered. Please use a different number or login to your existing account.';
            return $this->redirect('members/register');
        }

        // --- File upload ---
        $imagePath = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $valid = validate_upload($_FILES['image'], ['jpg', 'jpeg', 'png', 'gif', 'webp'], ['image/jpeg', 'image/png', 'image/gif', 'image/webp'], 500 * 1024);
            if ($valid !== true) {
                $_SESSION['error'] = 'Image: ' . $valid;
                return $this->redirect('members/register');
            }
            $uploadDir = UPLOAD_PATH . 'members/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
            $extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $fileName = time() . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
            $targetPath = $uploadDir . $fileName;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                $imagePath = 'uploads/members/' . $fileName;
            }
        }

        // --- Generate email verification token ---
        $verificationToken = bin2hex(random_bytes(32));

        $data = [
            'name' => $_POST['name'] ?? '',
            'email' => $email,
            'phone' => $phone,
            'gender' => $_POST['gender'] ?? '',
            'dob' => !empty($_POST['dob']) ? $_POST['dob'] : null,
            'address' => $_POST['address'] ?? '',
            'address_line' => $_POST['address_line'] ?? '',
            'city' => $_POST['city'] ?? '',
            'district' => $_POST['district'] ?? '',
            'state' => $_POST['state'] ?? '',
            'pin' => $_POST['pin'] ?? '',
            'blood_group' => $_POST['blood_group'] ?? '',
            'occupation' => $_POST['occupation'] ?? '',
            'designation_id' => !empty($_POST['designation_id']) ? $_POST['designation_id'] : null,
            'membership_type' => $_POST['membership_type'] ?? 'Regular',
            'image' => $imagePath,
            'join_date' => date('Y-m-d'),
            'status' => 'pending',
            'email_verification_token' => $verificationToken,
        ];

        $data['membership_id'] = $memberModel->generateMembershipId($this->globalSettings['id_prefix_member'] ?? 'MEM');

        try {
            if ($memberModel->create($data)) {
                $memberId = $memberModel->lastInsertId();
                AuditLog::log('register', 'member', $memberId, null, ['email' => $email, 'name' => $data['name']]);
                $regAttemptModel->record($email, $ip);

                // --- Send verification email ---
                $verificationUrl = url("members/verify-email/{$verificationToken}");
                $ngoName = $this->globalSettings['ngo_name'] ?? 'Our Organization';
                $subject = "Verify your email - {$ngoName}";
                $message = '<div style="font-family:Arial,sans-serif;max-width:600px;margin:0 auto;">';
                $message .= '<div style="background:#003566;color:#fff;padding:20px;text-align:center;font-size:20px;font-weight:bold;">' . htmlspecialchars($ngoName) . '</div>';
                $message .= '<div style="padding:30px;background:#fff;border:1px solid #eee;">';
                $message .= '<h2 style="color:#333;margin-top:0;">Email Verification</h2>';
                $message .= '<p style="font-size:14px;color:#555;line-height:1.6;">Thank you for registering. Please verify your email address by clicking the button below:</p>';
                $message .= '<div style="text-align:center;margin:30px 0;">';
                $message .= '<a href="' . $verificationUrl . '" style="display:inline-block;padding:12px 30px;background:#003566;color:#fff;text-decoration:none;border-radius:5px;font-weight:bold;">Verify Email Address</a>';
                $message .= '</div>';
                $message .= '<p style="font-size:13px;color:#999;line-height:1.6;">If you did not create an account, you can safely ignore this email. The link will expire in 24 hours.</p>';
                $message .= '<p style="font-size:13px;color:#999;">If the button doesn\'t work, copy and paste this URL into your browser:</p>';
                $message .= '<p style="font-size:12px;color:#666;word-break:break-all;">' . $verificationUrl . '</p>';
                $message .= '</div>';
                $message .= '<div style="text-align:center;padding:15px;background:#f5f5f5;font-size:12px;color:#999;">&copy; ' . date('Y') . ' ' . htmlspecialchars($ngoName) . '</div>';
                $message .= '</div>';

                try {
                    $mailer = new Mailer($this->globalSettings);
                    $mailer->send($email, $subject, $message, true);
                } catch (\Exception $e) {
                    // Email failure shouldn't block registration
                }

                $_SESSION['success'] = 'Registration submitted! Please check your email to verify your address before our team reviews your application.';
                return $this->redirect('members/register');
            } else {
                $_SESSION['error'] = 'Registration failed. Please try again.';
                return $this->redirect('members/register');
            }
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Registration failed due to a system error. Please contact support.';
            return $this->redirect('members/register');
        }
    }

    public function verifyEmail($token)
    {
        $memberModel = new Member();
        $member = $memberModel->where('email_verification_token', $token);

        if (empty($member)) {
            $_SESSION['error'] = 'Invalid or expired verification link.';
            return $this->redirect('members/register');
        }

        $member = $member[0];

        if (!empty($member->email_verified_at)) {
            $_SESSION['success'] = 'Your email is already verified.';
            return $this->redirect('members/register');
        }

        $memberModel->update($member->id, [
            'email_verified_at' => date('Y-m-d H:i:s'),
            'email_verification_token' => null,
        ]);

        $_SESSION['success'] = 'Email verified successfully! Your registration is now pending admin approval.';
        return $this->redirect('members/register');
    }

    public function privacy()
    {
        $settingModel = new Setting();
        $content = $settingModel->getVal('policy_privacy');
        return $this->view('privacy', [
            'title' => 'Privacy Policy',
            'content' => $content
        ]);
    }

    public function terms()
    {
        $settingModel = new Setting();
        $content = $settingModel->getVal('policy_terms');
        return $this->view('terms', [
            'title' => 'Terms & Conditions',
            'content' => $content
        ]);
    }

    public function refund()
    {
        $settingModel = new Setting();
        $content = $settingModel->getVal('policy_refund');
        return $this->view('refund', [
            'title' => 'Refund Policy',
            'content' => $content
        ]);
    }
}
