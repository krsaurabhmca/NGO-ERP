<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Mailer;
use App\Models\Career;
use App\Models\JobApplication;
use App\Models\AuditLog;

class CareerController extends Controller
{
    protected $careerModel;

    public function __construct()
    {
        parent::__construct();
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('auth');
        }
        $this->checkModuleAccess('careers');
        $this->careerModel = new Career();
    }

    public function index()
    {
        $this->careerModel->autoCloseExpired();
        $careers = $this->careerModel->all();
        return $this->view('admin/careers/index', [
            'title' => 'Manage Careers',
            'careers' => $careers
        ]);
    }

    public function interns()
    {
        $applicationModel = new JobApplication();
        $interns = $applicationModel->getHiredInterns();
        return $this->view('admin/careers/interns', [
            'title' => 'Hired Interns',
            'interns' => $interns
        ]);
    }

    public function employees()
    {
        $applicationModel = new JobApplication();
        $employees = $applicationModel->getHiredEmployees();
        return $this->view('admin/careers/employees', [
            'title' => 'Hired Employees',
            'employees' => $employees
        ]);
    }

    public function storeIntern()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            json_response(['status' => 'error', 'message' => 'Invalid request method.']);
            exit;
        }

        $careerModel = new Career();
        $internshipCareers = $careerModel->all();

        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $address = $_POST['address'] ?? '';
        $careerId = $_POST['career_id'] ?? null;
        $customPosition = trim($_POST['custom_position'] ?? '');
        $duration = $_POST['duration'] ?? '';
        $stipend = $_POST['stipend'] ?? '';

        if (empty($name) || empty($email) || empty($careerId)) {
            json_response(['status' => 'error', 'message' => 'Name, email, and position are required.']);
            exit;
        }

        if ($careerId === 'custom') {
            if (empty($customPosition)) {
                json_response(['status' => 'error', 'message' => 'Custom position title is required.']);
                exit;
            }
            $careerData = [
                'title' => $customPosition,
                'slug' => strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $customPosition))) . '-' . uniqid(),
                'description' => 'Manually created position.',
                'job_type' => 'Internship',
                'status' => 'closed'
            ];
            $success = $careerModel->create($careerData);
            if (!$success) {
                json_response(['status' => 'error', 'message' => 'Failed to create custom position.']);
                exit;
            }
            $careerId = $careerModel->lastInsertId();
        }

        $applicationModel = new JobApplication();
        $data = [
            'career_id' => $careerId,
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'address' => $address,
            'status' => 'hired',
            'duration' => $duration,
            'stipend' => $stipend,
        ];

        // Handle profile image upload
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
            $valid = validate_upload($_FILES['profile_image'], ['jpg', 'jpeg', 'png', 'gif', 'webp'], ['image/jpeg', 'image/png', 'image/gif', 'image/webp'], 500 * 1024);
            if ($valid !== true) {
                json_response(['status' => 'error', 'message' => 'Profile image: ' . $valid]);
                exit;
            }
            $uploadDir = UPLOAD_PATH . 'profiles/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $ext = strtolower(pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION));
            $filename = 'profile_' . time() . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
            $dest = $uploadDir . $filename;
            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $dest)) {
                $data['profile_image'] = 'uploads/profiles/' . $filename;
            }
        }

        if ($applicationModel->create($data)) {
            json_response(['status' => 'success', 'message' => 'Intern added successfully.']);
        } else {
            json_response(['status' => 'error', 'message' => 'Failed to add intern.']);
        }
        exit;
    }

    public function storeEmployee()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            json_response(['status' => 'error', 'message' => 'Invalid request method.']);
            exit;
        }

        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $address = $_POST['address'] ?? '';
        $careerId = $_POST['career_id'] ?? null;
        $customPosition = trim($_POST['custom_position'] ?? '');
        $salary = $_POST['salary'] ?? '';

        if (empty($name) || empty($email) || empty($careerId)) {
            json_response(['status' => 'error', 'message' => 'Name, email, and position are required.']);
            exit;
        }

        if ($careerId === 'custom') {
            if (empty($customPosition)) {
                json_response(['status' => 'error', 'message' => 'Custom position title is required.']);
                exit;
            }
            $careerModel = new Career();
            $careerData = [
                'title' => $customPosition,
                'slug' => strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $customPosition))) . '-' . uniqid(),
                'description' => 'Manually created position.',
                'job_type' => 'Full Time',
                'status' => 'closed'
            ];
            $success = $careerModel->create($careerData);
            if (!$success) {
                json_response(['status' => 'error', 'message' => 'Failed to create custom position.']);
                exit;
            }
            $careerId = $careerModel->lastInsertId();
        }

        $applicationModel = new JobApplication();
        $data = [
            'career_id' => $careerId,
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'address' => $address,
            'status' => 'hired',
            'stipend' => $salary,
        ];

        // Handle profile image upload
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
            $valid = validate_upload($_FILES['profile_image'], ['jpg', 'jpeg', 'png', 'gif', 'webp'], ['image/jpeg', 'image/png', 'image/gif', 'image/webp'], 500 * 1024);
            if ($valid !== true) {
                json_response(['status' => 'error', 'message' => 'Profile image: ' . $valid]);
                exit;
            }
            $uploadDir = UPLOAD_PATH . 'profiles/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $ext = strtolower(pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION));
            $filename = 'profile_' . time() . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
            $dest = $uploadDir . $filename;
            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $dest)) {
                $data['profile_image'] = 'uploads/profiles/' . $filename;
            }
        }

        if ($applicationModel->create($data)) {
            json_response(['status' => 'success', 'message' => 'Employee added successfully.']);
        } else {
            json_response(['status' => 'error', 'message' => 'Failed to add employee.']);
        }
        exit;
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'title' => $_POST['title'] ?? '',
                'slug' => $this->careerModel->createSlug($_POST['title'] ?? ''),
                'description' => $_POST['description'] ?? '',
                'location' => $_POST['location'] ?? '',
                'job_type' => $_POST['job_type'] ?? '',
                'status' => $_POST['status'] ?? 'open',
                'deadline' => !empty($_POST['deadline']) ? $_POST['deadline'] : null
            ];

            if ($this->careerModel->create($data)) {
                AuditLog::log('create', 'career', $this->careerModel->lastInsertId(), null, $data);
                $_SESSION['success'] = 'Job posting created successfully.';
            } else {
                $_SESSION['error'] = 'Failed to create job posting.';
            }
        }
        return $this->redirect('admin/careers');
    }

    public function edit($id)
    {
        $career = $this->careerModel->find($id);
        if (!$career) {
            $_SESSION['error'] = 'Job posting not found.';
            return $this->redirect('admin/careers');
        }
        json_response(['status' => 'success', 'data' => $career]);
        exit;
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $career = $this->careerModel->find($id);
            if (!$career) {
                $_SESSION['error'] = 'Job posting not found.';
                return $this->redirect('admin/careers');
            }

            $title = $_POST['title'] ?? '';
            $data = [
                'title' => $title,
                'description' => $_POST['description'] ?? '',
                'location' => $_POST['location'] ?? '',
                'job_type' => $_POST['job_type'] ?? '',
                'status' => $_POST['status'] ?? 'open',
                'deadline' => !empty($_POST['deadline']) ? $_POST['deadline'] : null
            ];

            if ($title !== $career->title) {
                $data['slug'] = $this->careerModel->createSlug($title);
            }

            if ($this->careerModel->update($id, $data)) {
                AuditLog::log('update', 'career', $id, null, $data);
                $_SESSION['success'] = 'Job posting updated successfully.';
            } else {
                $_SESSION['error'] = 'Failed to update job posting.';
            }
        }
        return $this->redirect('admin/careers');
    }

    public function delete($id)
    {
        $career = $this->careerModel->find($id);
        if ($this->careerModel->delete($id)) {
            // Audit log
            AuditLog::log('delete', 'careers', $id, $career, null);
            
            $_SESSION['success'] = 'Job posting deleted successfully.';
        } else {
            $_SESSION['error'] = 'Failed to delete job posting.';
        }
        return $this->redirect('admin/careers');
    }

    public function applications()
    {
        $applicationModel = new JobApplication();
        
        // Fetch all records for client-side filtering
        $all = $applicationModel->getAllWithCareer();

        $careerModel = new \App\Models\Career();
        $jobTypes = $careerModel->getDistinctJobTypes();

        return $this->view('admin/careers/applications', [
            'title' => 'Job Applications',
            'applications' => $all,
            'currentStatus' => $status,
            'currentJobType' => $jobType,
            'jobTypes' => $jobTypes
        ]);
    }

    public function applicationDetail($id)
    {
        $applicationModel = new JobApplication();
        $app = $applicationModel->getWithCareer($id);
        if (!$app) {
            $_SESSION['error'] = 'Application not found.';
            return $this->redirect('admin/careers/applications');
        }
        return $this->view('admin/careers/application_detail', [
            'title' => 'Application - ' . $app->name,
            'app' => $app
        ]);
    }

    public function applicationJson($id)
    {
        $applicationModel = new JobApplication();
        $app = $applicationModel->getWithCareer($id);
        if (!$app) {
            json_response(['status' => 'error', 'message' => 'Not found']);
            exit;
        }
        json_response(['status' => 'success', 'data' => $app]);
        exit;
    }

    public function updateApplicationFields($id)
    {
        $applicationModel = new JobApplication();
        $app = $applicationModel->find($id);
        if (!$app) {
            json_response(['status' => 'error', 'message' => 'Application not found.']);
            exit;
        }

        // Capture old data for audit
        $oldData = clone $app;

        $data = [];
        if (isset($_POST['duration'])) {
            $data['duration'] = $_POST['duration'];
        }
        if (isset($_POST['stipend'])) {
            $data['stipend'] = $_POST['stipend'];
        }

        // Handle profile image upload
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
            $valid = validate_upload($_FILES['profile_image'], ['jpg', 'jpeg', 'png', 'gif', 'webp'], ['image/jpeg', 'image/png', 'image/gif', 'image/webp'], 500 * 1024);
            if ($valid !== true) {
                json_response(['status' => 'error', 'message' => 'Profile image: ' . $valid]);
                exit;
            }
            $uploadDir = UPLOAD_PATH . 'profiles/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $ext = strtolower(pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION));
            $filename = 'profile_' . $id . '_' . time() . '.' . $ext;
            $dest = $uploadDir . $filename;
            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $dest)) {
                $data['profile_image'] = 'uploads/profiles/' . $filename;
            }
        }

        // Handle multiple KYC document upload
        $allowedExts = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];
        $allowedMimes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'image/jpeg', 'image/png'];
        $maxSize = 2 * 1024 * 1024; // 2MB per file
        $uploadedDocs = [];

        // Load existing documents from DB
        if (!empty($app->resume)) {
            $existing = json_decode($app->resume, true);
            if (is_array($existing)) {
                $uploadedDocs = $existing;
            } else {
                $uploadedDocs[] = $app->resume; // single-file legacy
            }
        }

        if (isset($_FILES['documents'])) {
            $files = $_FILES['documents'];
            // Normalize to array structure when single file
            if (!is_array($files['name'])) {
                $files['name'] = [$files['name']];
                $files['tmp_name'] = [$files['tmp_name']];
                $files['error'] = [$files['error']];
                $files['size'] = [$files['size']];
            }

            // Limit max KYC uploads to 5 files
            $maxUploads = 5;
            $totalAfter = count($uploadedDocs) + count($files['name']);
            if ($totalAfter > $maxUploads) {
                $_SESSION['error'] = 'Maximum ' . $maxUploads . ' documents allowed.';
                return $this->redirect('admin/careers/applications/edit/' . $id);
            }

            $uploadDir = UPLOAD_PATH . 'kyc/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $hasUpload = false;
            foreach ($files['name'] as $idx => $name) {
                if ($files['error'][$idx] !== UPLOAD_ERR_OK) continue;
                $singleFile = [
                    'name' => $files['name'][$idx],
                    'tmp_name' => $files['tmp_name'][$idx],
                    'size' => $files['size'][$idx],
                    'error' => $files['error'][$idx]
                ];
                $valid = validate_upload($singleFile, $allowedExts, $allowedMimes, $maxSize);
                if ($valid !== true) continue;
                $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                $filename = 'kyc_' . $id . '_' . time() . '_' . $idx . '.' . $ext;
                $dest = $uploadDir . $filename;
                if (move_uploaded_file($files['tmp_name'][$idx], $dest)) {
                    $uploadedDocs[] = 'uploads/kyc/' . $filename;
                    $hasUpload = true;
                }
            }
            if ($hasUpload) {
                $data['resume'] = json_encode($uploadedDocs);
            }
        }

        // Handle offer letter upload
        if (isset($_FILES['offer_letter']) && $_FILES['offer_letter']['error'] === UPLOAD_ERR_OK) {
            $valid = validate_upload($_FILES['offer_letter'], ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'], ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'image/jpeg', 'image/png'], 2 * 1024 * 1024);
            if ($valid !== true) {
                json_response(['status' => 'error', 'message' => 'Offer letter: ' . $valid]);
                exit;
            }
            $uploadDir = UPLOAD_PATH . 'offer_letters/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $ext = strtolower(pathinfo($_FILES['offer_letter']['name'], PATHINFO_EXTENSION));
            $filename = 'offer_' . $id . '_' . time() . '.' . $ext;
            $dest = $uploadDir . $filename;
            if (move_uploaded_file($_FILES['offer_letter']['tmp_name'], $dest)) {
                $data['offer_letter'] = 'uploads/offer_letters/' . $filename;
            }
        }

        if (empty($data)) {
            json_response(['status' => 'error', 'message' => 'No data to update.']);
            exit;
        }

        if ($applicationModel->update($id, $data)) {
            // Log the update
            \App\Models\AuditLog::log(
                'update',
                'career_applications',
                $id,
                $oldData,
                $data
            );
            json_response(['status' => 'success', 'message' => 'Details updated successfully.']);
        } else {
            json_response(['status' => 'error', 'message' => 'Failed to update details.']);
        }
        exit;
    }

    public function completeInternship($id)
    {
        $applicationModel = new JobApplication();
        $app = $applicationModel->find($id);
        if (!$app) {
            json_response(['status' => 'error', 'message' => 'Application not found.']);
            exit;
        }
        
        $oldData = (array) $app;
        
        $data = [
            'internship_status' => 'completed',
            'completion_date' => date('Y-m-d')
        ];
        if ($applicationModel->update($id, $data)) {
            // Log internship completion
            \App\Models\AuditLog::log(
                'complete_internship',
                'career_applications',
                $id,
                $oldData,
                $data
            );
            json_response(['status' => 'success', 'message' => 'Internship marked as completed.']);
        } else {
            json_response(['status' => 'error', 'message' => 'Failed to complete internship.']);
        }
        exit;
    }

    public function certificate($id)
    {
        $applicationModel = new JobApplication();
        $intern = $applicationModel->getWithCareer($id);
        if (!$intern || $intern->internship_status !== 'completed') {
            $_SESSION['error'] = 'Internship not completed yet.';
            return $this->redirect('admin/careers/interns');
        }
        return $this->view('admin/careers/certificate', [
            'title' => 'Certificate - ' . $intern->name,
            'intern' => $intern
        ]);
    }

    public function downloadCertificate($id)
    {
        $applicationModel = new JobApplication();
        $intern = $applicationModel->getWithCareer($id);
        if (!$intern || $intern->internship_status !== 'completed') {
            $_SESSION['error'] = 'Internship not completed yet.';
            return $this->redirect('admin/careers/interns');
        }

        $name = $intern->name;
        $position = $intern->career_title ?? 'Intern';
        $duration = $intern->duration ?? '';
        $compDate = !empty($intern->completion_date) ? date('d F, Y', strtotime($intern->completion_date)) : date('d F, Y');
        $ngo = $this->globalSettings['ngo_name'] ?? 'CARE Foundation';

        $safe = preg_replace('/[^a-zA-Z0-9_-]/', '', str_replace(' ', '_', $name));
        if (empty($safe)) $safe = 'certificate';

        header('Content-Type: image/png');
        header('Content-Disposition: attachment; filename="certificate_' . $safe . '.png"');

        $w = 1200; $h = 800;
        $im = imagecreatetruecolor($w, $h);

        $bg  = imagecolorallocate($im, 245, 242, 235);
        $gd  = imagecolorallocate($im, 201, 168, 76);
        $gl  = imagecolorallocate($im, 212, 184, 90);
        $dk  = imagecolorallocate($im, 44, 62, 80);
        $tx  = imagecolorallocate($im, 60, 60, 60);
        $ln  = imagecolorallocate($im, 200, 200, 200);

        imagefill($im, 0, 0, $bg);
        imagerectangle($im, 15, 15, $w-16, $h-16, $gd);
        imagerectangle($im, 22, 22, $w-23, $h-23, $gl);

        $cx = $w/2;
        imagesetthickness($im, 2);
        imageline($im, $cx-90, 160, $cx+90, 160, $gd);
        imagesetthickness($im, 1);
        imageline($im, $cx-60, 230, $cx+60, 230, $gd);
        imageline($im, $cx-60, $h-120, $cx+60, $h-120, $gd);
        imagesetthickness($im, 2);
        imageline($im, $cx-90, $h-116, $cx+90, $h-116, $gd);

        $fb = 'C:\Windows\Fonts\timesbd.ttf';
        $fr = 'C:\Windows\Fonts\times.ttf';

        if (file_exists($fb) && file_exists($fr)) {
            @imagettftext($im, 28, 0, ($w-420)/2, 180, $dk, $fb, 'CERTIFICATE OF COMPLETION');
            @imagettftext($im, 16, 0, ($w-200)/2, 240, $gd, $fb, strtoupper($ngo));
            @imagettftext($im, 20, 0, ($w-320)/2, 330, $tx, $fr, 'This is to certify that');
            $nBox = @imagettfbbox(36, 0, $fb, $name);
            if ($nBox) {
                $nw = $nBox[2] - $nBox[0];
                @imagettftext($im, 36, 0, ($w-$nw)/2, 400, $dk, $fb, $name);
            }
            @imagettftext($im, 20, 0, ($w-500)/2, 470, $tx, $fr, 'has successfully completed the internship program');
            @imagettftext($im, 17, 0, ($w-200)/2, 520, $tx, $fr, "Position: $position");
            if ($duration) {
                @imagettftext($im, 17, 0, ($w-200)/2, 560, $tx, $fr, "Duration: $duration");
            }

            imageline($im, 80, $h-90, 300, $h-90, $ln);
            @imagettftext($im, 14, 0, 100, $h-50, $tx, $fr, $compDate);
            @imagettftext($im, 11, 0, 145, $h-65, $gd, $fb, 'DATE');

            imageline($im, $w-380, $h-90, $w-80, $h-90, $ln);
            $sigLabel = 'AUTHORIZED SIGNATURE';
            $slBox = @imagettfbbox(11, 0, $fb, $sigLabel);
            if ($slBox) {
                $slw = $slBox[2] - $slBox[0];
                @imagettftext($im, 11, 0, ($w-230)-$slw/2, $h-65, $gd, $fb, $sigLabel);
            }
        } else {
            @imagestring($im, 5, 50, 50, 'CERTIFICATE OF COMPLETION', $dk);
        }

        imagepng($im);
        imagedestroy($im);
        exit;
    }

    private function checkRateLimit($key, $maxPerHour = 10)
    {
        $rateKey = '_rate_limit_' . $key;
        $now = time();
        $window = isset($_SESSION[$rateKey]) ? $_SESSION[$rateKey] : [];
        $window = array_filter($window, fn($t) => $t > $now - 3600);
        if (count($window) >= $maxPerHour) {
            return false;
        }
        $window[] = $now;
        $_SESSION[$rateKey] = $window;
        return true;
    }

    public function sendCertificate($id)
    {
        if (!$this->checkRateLimit('send_certificate')) {
            json_response(['status' => 'error', 'message' => 'Rate limit exceeded. Please try later.']);
            exit;
        }

        $applicationModel = new JobApplication();
        $intern = $applicationModel->getWithCareer($id);
        if (!$intern || $intern->internship_status !== 'completed') {
            json_response(['status' => 'error', 'message' => 'Invalid request.']);
            exit;
        }

        $to = $_POST['email'] ?? $intern->email;

        $templateSubject = $this->globalSettings['template_email_acceptance_subject'] ?? '';
        $templateBody = $this->globalSettings['template_email_acceptance'] ?? '';

        $placeholders = [
            '{name}' => $intern->name,
            '{position}' => $intern->career_title ?? 'Intern',
            '{ngo_name}' => $this->globalSettings['ngo_name'] ?? 'NGO',
            '{duration}' => $intern->duration ?? '',
            '{stipend}' => $intern->stipend ?? '',
            '{date}' => !empty($intern->completion_date) ? date('d F, Y', strtotime($intern->completion_date)) : date('d F, Y'),
        ];

        $subject = !empty($templateSubject)
            ? str_replace(array_keys($placeholders), array_values($placeholders), $templateSubject)
            : ($_POST['subject'] ?? 'Certificate of Completion - ' . $intern->name);

        $message = !empty($templateBody)
            ? str_replace(array_keys($placeholders), array_values($placeholders), $templateBody) . "\n\n" . url('admin/careers/certificate/' . $id)
            : ($_POST['message'] ?? '');

        $mailer = new Mailer($this->globalSettings);
        if ($mailer->send($to, $subject, $message)) {
            json_response(['status' => 'success', 'message' => 'Certificate sent successfully to ' . htmlspecialchars($to) . '.']);
        } else {
            json_response(['status' => 'error', 'message' => 'Failed to send email. Please check server mail configuration.']);
        }
        exit;
    }

    public function sendOfferLetter($id)
    {
        if (!$this->checkRateLimit('send_offer_letter')) {
            json_response(['status' => 'error', 'message' => 'Rate limit exceeded. Please try later.']);
            exit;
        }

        $applicationModel = new JobApplication();
        $intern = $applicationModel->getWithCareer($id);
        if (!$intern || $intern->status !== 'hired') {
            json_response(['status' => 'error', 'message' => 'Invalid request.']);
            exit;
        }

        $to = $_POST['email'] ?? $intern->email;

        $templateSubject = $this->globalSettings['template_email_offer_subject'] ?? '';
        $templateBody = $this->globalSettings['template_email_offer'] ?? '';

        $placeholders = [
            '{name}' => $intern->name,
            '{position}' => $intern->career_title ?? 'Intern',
            '{ngo_name}' => $this->globalSettings['ngo_name'] ?? 'NGO',
            '{duration}' => $intern->duration ?? '',
            '{stipend}' => $intern->stipend ?? '',
            '{date}' => date('d F, Y'),
        ];

        $subject = !empty($templateSubject)
            ? str_replace(array_keys($placeholders), array_values($placeholders), $templateSubject)
            : ($_POST['subject'] ?? 'Offer Letter - ' . $intern->name);

        $message = !empty($templateBody)
            ? str_replace(array_keys($placeholders), array_values($placeholders), $templateBody)
            : ($_POST['message'] ?? 'Dear ' . $intern->name . ",\n\nPlease find attached your offer letter from " . ($this->globalSettings['ngo_name'] ?? 'NGO') . ".\n\nRegards,\n" . ($this->globalSettings['ngo_name'] ?? 'NGO'));

        $mailer = new Mailer($this->globalSettings);
        if ($mailer->send($to, $subject, $message)) {
            json_response(['status' => 'success', 'message' => 'Offer letter sent successfully to ' . htmlspecialchars($to) . '.']);
        } else {
            json_response(['status' => 'error', 'message' => 'Failed to send email. Please check server mail configuration.']);
        }
        exit;
    }

    public function updateApplicationStatus($id)
    {
        $applicationModel = new JobApplication();
        $app = $applicationModel->find($id);
        if (!$app) {
            $_SESSION['error'] = 'Application not found.';
            return $this->redirect('admin/careers/applications');
        }
        $status = $_POST['status'] ?? 'pending';
        $allowed = ['pending', 'reviewed', 'shortlisted', 'called_for_interview', 'rejected', 'hired', 'terminated', 'resigned'];
        if (!in_array($status, $allowed)) {
            $status = 'pending';
        }
        $oldStatus = $app->status ?? '';
        $success = $applicationModel->update($id, ['status' => $status]);
        if ($success) {
            AuditLog::log('update', 'job_application', $id, ['old_status' => $oldStatus], ['new_status' => $status]);
            $_SESSION['success'] = 'Application status updated to ' . ucfirst($status) . '.';
        } else {
            $_SESSION['error'] = 'Failed to update status.';
        }
        
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            if ($success) {
                json_response(['status' => 'success', 'message' => 'Status updated to ' . ucfirst($status) . '.']);
            } else {
                json_response(['status' => 'error', 'message' => 'Failed to update status.']);
            }
            exit;
        }
        
        return $this->redirect('admin/careers/application/' . $id);
    }

    public function legacyOfferLetterPdf() {
        if (empty($_SESSION['user_id']) && empty($_SESSION['member_id'])) {
            echo 'Access denied. Please login first.';
            exit;
        }
        require_once __DIR__ . '/../views/admin/careers/offer_letter_pdf.php';
    }

    public function legacyCertificatePdf() {
        if (empty($_SESSION['user_id']) && empty($_SESSION['member_id'])) {
            echo 'Access denied. Please login first.';
            exit;
        }
        require_once __DIR__ . '/../views/admin/careers/certificate_pdf.php';
    }
}
