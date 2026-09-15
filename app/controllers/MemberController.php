<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Mailer;
use App\Models\Member;
use App\Models\AuditLog;

class MemberController extends Controller
{
    protected $memberModel;

    public function __construct()
    {
        parent::__construct();
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('auth');
        }
        $this->checkModuleAccess('members');
        $this->memberModel = new Member();
    }

    public function index()
    {
        $status = $_GET['status'] ?? null;

        if ($status === 'pending') {
            $members = $this->memberModel->allWithDesignation();
        } else {
            $members = $this->memberModel->allWithDesignation('pending');
        }

        $designationModel = new \App\Models\Designation();
        $designations = $designationModel->all();

        return $this->view('admin/members/index', [
            'title' => 'Manage Members',
            'members' => $members,
            'status_filter' => $status,
            'designations' => $designations,
        ]);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $imagePath = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $valid = validate_upload($_FILES['image'], ['jpg', 'jpeg', 'png', 'gif', 'webp'], ['image/jpeg', 'image/png', 'image/gif', 'image/webp'], 500 * 1024);
                if ($valid !== true) {
                    $_SESSION['error'] = 'Image: ' . $valid;
                    return $this->redirect('admin/members');
                }

                $uploadDir = UPLOAD_PATH . 'members/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                $fileName = time() . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
                $targetPath = $uploadDir . $fileName;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                    $imagePath = 'uploads/members/' . $fileName;
                }
            }

            $data = [
                'membership_id' => $this->memberModel->generateMembershipId($this->globalSettings['id_prefix_member'] ?? 'MEM'),
                'name' => $_POST['name'] ?? '',
                'email' => $_POST['email'] ?? '',
                'phone' => $_POST['phone'] ?? '',
                'gender' => $_POST['gender'] ?? '',
                'dob' => !empty($_POST['dob']) ? $_POST['dob'] : null,
                'blood_group' => $_POST['blood_group'] ?? '',
                'occupation' => $_POST['occupation'] ?? '',
                'designation_id' => !empty($_POST['designation_id']) ? $_POST['designation_id'] : null,
                'address' => $_POST['address'] ?? '',
                'image' => $imagePath,
                'join_date' => !empty($_POST['join_date']) ? $_POST['join_date'] : date('Y-m-d'),
                'status' => $_POST['status'] ?? 'active'
            ];

            // Auto-generate password
            $plainPassword = substr(str_replace(['+', '/', '='], '', base64_encode(random_bytes(9))), 0, 12);
            $data['password'] = password_hash($plainPassword, PASSWORD_DEFAULT);

            if ($this->memberModel->create($data)) {
                $auditData = $data;
                unset($auditData['password']);
                AuditLog::log('create', 'member', $this->memberModel->lastInsertId(), null, $auditData);
                $_SESSION['success'] = 'Member added successfully.';
                $_SESSION['member_credentials'] = [
                    'name' => $data['name'],
                    'membership_id' => $data['membership_id'],
                    'email' => $data['email'],
                    'password' => $plainPassword
                ];
            } else {
                $_SESSION['error'] = 'Failed to add member.';
            }
        }
        return $this->redirect('admin/members');
    }

    public function edit($id)
    {
        $member = $this->memberModel->find($id);
        if (!$member) {
            $_SESSION['error'] = 'Member not found.';
            return $this->redirect('admin/members');
        }

        $designationModel = new \App\Models\Designation();
        $designations = $designationModel->all();

        return $this->view('admin/members/edit', [
            'title' => 'Edit Member',
            'member' => $member,
            'designations' => $designations
        ]);
    }

    public function get($id)
    {
        $member = $this->memberModel->find($id);
        if (!$member) {
            json_response(['status' => 'error', 'message' => 'Member not found']);
            exit;
        }

        $designation_name = 'Member';
        if ($member->designation_id) {
            $designationModel = new \App\Models\Designation();
            $designation = $designationModel->find($member->designation_id);
            if ($designation) {
                $designation_name = $designation->name;
            }
        }
        $member->designation_name = $designation_name;

        json_response(['status' => 'success', 'data' => $member]);
        exit;
    }

    public function generatePassword($id)
    {
        $member = $this->memberModel->find($id);
        if (!$member) {
            json_response(['status' => 'error', 'message' => 'Member not found']);
            exit;
        }

        $plainPassword = substr(str_replace(['+', '/', '='], '', base64_encode(random_bytes(9))), 0, 12);
        $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

        if ($this->memberModel->update($id, ['password' => $hashedPassword])) {
            try {
                AuditLog::log(
                    'generate_password',
                    'members',
                    $id,
                    ['membership_id' => $member->membership_id, 'name' => $member->name],
                    ['password_generated' => true]
                );
            } catch (\Exception $e) {
                // Audit log failure shouldn't block the operation
            }
            
            json_response(['status' => 'success', 'password' => $plainPassword]);
        } else {
            json_response(['status' => 'error', 'message' => 'Failed to generate password.']);
        }
        exit;
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $member = $this->memberModel->find($id);
            $oldData = (array) $member;
            $imagePath = $member->image;

            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $valid = validate_upload($_FILES['image'], ['jpg', 'jpeg', 'png', 'gif', 'webp'], ['image/jpeg', 'image/png', 'image/gif', 'image/webp'], 500 * 1024);
                if ($valid !== true) {
                    $_SESSION['error'] = 'Image: ' . $valid;
                    return $this->redirect('admin/members');
                }

                $uploadDir = UPLOAD_PATH . 'members/';
                $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                $fileName = time() . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
                $targetPath = $uploadDir . $fileName;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                    safe_unlink($imagePath);
                    $imagePath = 'uploads/members/' . $fileName;
                }
            }

            $data = [
                'name' => $_POST['name'] ?? '',
                'email' => $_POST['email'] ?? '',
                'phone' => $_POST['phone'] ?? '',
                'gender' => $_POST['gender'] ?? '',
                'dob' => !empty($_POST['dob']) ? $_POST['dob'] : null,
                'blood_group' => $_POST['blood_group'] ?? '',
                'occupation' => $_POST['occupation'] ?? '',
                'designation_id' => !empty($_POST['designation_id']) ? $_POST['designation_id'] : null,
                'address' => $_POST['address'] ?? '',
                'address_line' => $_POST['address_line'] ?? '',
                'city' => $_POST['city'] ?? '',
                'district' => $_POST['district'] ?? '',
                'state' => $_POST['state'] ?? '',
                'pin' => $_POST['pin'] ?? '',
                'image' => $imagePath,
                'join_date' => !empty($_POST['join_date']) ? $_POST['join_date'] : date('Y-m-d'),
                'status' => $_POST['status'] ?? 'active'
            ];

            $password = $_POST['password'] ?? '';
            $passwordChanged = false;
            if (!empty($password)) {
                $passwordConfirm = $_POST['password_confirm'] ?? '';
                if ($password !== $passwordConfirm) {
                    $_SESSION['error'] = 'Password and confirm password do not match.';
                    return $this->redirect('admin/members');
                }
                $data['password'] = password_hash($password, PASSWORD_DEFAULT);
                $passwordChanged = true;
            }

            if ($this->memberModel->update($id, $data)) {
                // Audit log
                unset($oldData['password'], $oldData['password_plain']);
                $newDataForAudit = $data;
                unset($newDataForAudit['password'], $newDataForAudit['password_plain']);
                if ($passwordChanged) {
                    $newDataForAudit['password_changed'] = true;
                }
                
                try {
                    AuditLog::log(
                        'update',
                        'members',
                        $id,
                        $oldData,
                        $newDataForAudit
                    );
                } catch (\Exception $e) {
                    // Audit log failure shouldn't block the operation
                }
                
                $_SESSION['success'] = 'Member updated successfully.';
            } else {
                $_SESSION['error'] = 'Failed to update member.';
            }
        }
        return $this->redirect('admin/members');
    }

    public function delete($id)
    {
        $member = $this->memberModel->find($id);
        if (!$member) {
            $_SESSION['error'] = 'Member not found.';
            return $this->redirect('admin/members');
        }
        
        $oldData = (array) $member;
        unset($oldData['password'], $oldData['password_plain']);
        
        if ($this->memberModel->delete($id)) {
            try {
                AuditLog::log(
                    'delete',
                    'members',
                    $id,
                    $oldData,
                    null
                );
            } catch (\Exception $e) {
                // Audit log failure shouldn't block the operation
            }
            
            $_SESSION['success'] = 'Member deleted successfully.';
        } else {
            $_SESSION['error'] = 'Failed to delete member.';
        }
        return $this->redirect('admin/members');
    }

    public function approve($id)
    {
        $member = $this->memberModel->find($id);
        if (!$member) {
            $_SESSION['error'] = 'Member not found.';
            return $this->redirect('admin/members?status=pending');
        }

        $oldData = ['status' => $member->status];
        $plainPassword = substr(str_replace(['+', '/', '='], '', base64_encode(random_bytes(9))), 0, 12);
        $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

        if ($this->memberModel->update($id, ['status' => 'active', 'password' => $hashedPassword])) {
            try {
                AuditLog::log(
                    'approve',
                    'members',
                    $id,
                    $oldData,
                    ['status' => 'active', 'password_generated' => true]
                );
            } catch (\Exception $e) {
                // Audit log failure shouldn't block the operation
            }
            // Load designation
            $designation = null;
            if ($member->designation_id) {
                $designationModel = new \App\Models\Designation();
                $designation = $designationModel->find($member->designation_id);
            }

            $mailSent = false;
            // Send offer letter email
            if (!empty($member->email)) {
                $ngoName = $this->globalSettings['ngo_name'] ?? 'NGO';
                $joinDate = date('d M, Y', strtotime($member->join_date));
                $loginUrl = url('auth');
                $designationName = $designation ? $designation->name : 'Member';

                $placeholders = [
                    '{name}' => $member->name,
                    '{member_id}' => $member->membership_id,
                    '{designation}' => $designationName,
                    '{join_date}' => $joinDate,
                    '{ngo_name}' => $ngoName,
                    '{password}' => $plainPassword,
                    '{login_url}' => $loginUrl,
                ];

                $templateSubject = $this->globalSettings['template_member_offer_subject'] ?? '';
                $templateBody = $this->globalSettings['template_member_offer'] ?? '';

                $subject = !empty($templateSubject)
                    ? str_replace(array_keys($placeholders), array_values($placeholders), $templateSubject)
                    : 'Welcome – Membership Offer Letter | ' . $ngoName;

                $message = !empty($templateBody)
                    ? str_replace(array_keys($placeholders), array_values($placeholders), $templateBody)
                    : '<!DOCTYPE html><html><head><meta charset="UTF-8"></head><body style="font-family:Georgia,serif;background:#f4f4f4;padding:30px;">
                        <table align="center" width="600" style="background:#fff;border-radius:8px;overflow:hidden;">
                            <tr><td style="text-align:center;padding:30px 40px 10px;border-bottom:3px double #1a44a6;">
                                <h1 style="color:#1a44a6;text-transform:uppercase;font-size:22px;margin:0;">' . htmlspecialchars($ngoName) . '</h1>
                            </td></tr>
                            <tr><td style="padding:30px 40px;">
                                <h2 style="color:#1a44a6;font-size:18px;margin:0 0 20px;">Letter of Offer for Membership</h2>
                                <p style="font-size:15px;line-height:1.8;margin:0 0 15px;">Dear <strong>' . htmlspecialchars($member->name) . '</strong>,</p>
                                <p style="font-size:15px;line-height:1.8;margin:0 0 15px;">We are delighted to welcome you as a valued member of <strong>' . htmlspecialchars($ngoName) . '</strong>. Based on your application and credentials, we are pleased to offer you the position of <strong>' . htmlspecialchars($designationName) . '</strong> effective from <strong>' . $joinDate . '</strong>.</p>
                                <p style="font-size:15px;line-height:1.8;margin:0 0 20px;">As a member, you will be an integral part of our mission to serve the community and drive positive change.</p>
                                <table width="100%" style="border-collapse:collapse;font-size:14px;margin-bottom:20px;">
                                    <tr><td style="padding:8px 12px;border:1px solid #ddd;background:#f8f9fc;font-weight:bold;width:130px;">Member Name</td><td style="padding:8px 12px;border:1px solid #ddd;">' . htmlspecialchars($member->name) . '</td></tr>
                                    <tr><td style="padding:8px 12px;border:1px solid #ddd;background:#f8f9fc;font-weight:bold;">Membership ID</td><td style="padding:8px 12px;border:1px solid #ddd;">' . htmlspecialchars($member->membership_id) . '</td></tr>
                                    <tr><td style="padding:8px 12px;border:1px solid #ddd;background:#f8f9fc;font-weight:bold;">Designation</td><td style="padding:8px 12px;border:1px solid #ddd;">' . htmlspecialchars($designationName) . '</td></tr>
                                    <tr><td style="padding:8px 12px;border:1px solid #ddd;background:#f8f9fc;font-weight:bold;">Joining Date</td><td style="padding:8px 12px;border:1px solid #ddd;">' . $joinDate . '</td></tr>
                                </table>
                                <div style="background:#fef3e6;border-radius:6px;padding:15px 20px;margin-bottom:20px;">
                                    <h3 style="color:#e8943e;font-size:15px;margin:0 0 10px;">Login Credentials</h3>
                                    <p style="font-size:14px;margin:0 0 5px;"><strong>Membership ID:</strong> ' . htmlspecialchars($member->membership_id) . '</p>
                                    <p style="font-size:14px;margin:0 0 5px;"><strong>Password:</strong> ' . htmlspecialchars($plainPassword) . '</p>
                                    <p style="font-size:14px;margin:0;"><a href="' . $loginUrl . '" style="color:#1a44a6;font-weight:bold;">Click here to login</a></p>
                                </div>
                                <p style="font-size:13px;color:#666;line-height:1.6;margin:0;">Please keep your login credentials secure. We recommend changing your password after your first login.</p>
                            </td></tr>
                            <tr><td style="text-align:center;padding:15px 40px;background:#1a44a6;color:#fff;font-size:12px;">' . htmlspecialchars($ngoName) . '</td></tr>
                        </table></body></html>';

                if (!empty($message)) {
                    try {
                        $mailer = new Mailer($this->globalSettings);
                        $mailSent = $mailer->send($member->email, $subject, $message, true);
                    } catch (\Exception $e) {
                        // Email failure shouldn't block approval
                        $mailSent = false;
                    }
                }
            }

            if ($mailSent) {
                $_SESSION['success'] = 'Member approved successfully. Login credentials sent to email.';
            } else {
                $_SESSION['success'] = 'Member approved successfully, but email failed to send (check SMTP settings).';
            }
            $_SESSION['member_credentials'] = [
                'name' => $member->name,
                'membership_id' => $member->membership_id,
                'email' => $member->email,
                'password' => $plainPassword
            ];
        } else {
            $_SESSION['error'] = 'Failed to approve member.';
        }
        return $this->redirect('admin/members?status=pending');
    }

    public function idCard($id)
    {
        $member = $this->memberModel->find($id);
        if (!$member) {
            $_SESSION['error'] = 'Member not found.';
            return $this->redirect('admin/members');
        }

        $designationModel = new \App\Models\Designation();
        $designation = null;
        if ($member->designation_id) {
            $designation = $designationModel->find($member->designation_id);
        }

        // Generate QR code URL (using a free API for simplicity, or we can use a library if available. For now, use an external API)
        $qrData = urlencode("Member ID: {$member->membership_id}\nName: {$member->name}\nPhone: {$member->phone}");
        $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={$qrData}";

        // Get global settings for NGO name and logo
        $settingModel = new \App\Models\Setting();
        $settingsData = $settingModel->all();
        $globalSettings = [];
        foreach ($settingsData as $setting) {
            $globalSettings[$setting->key_name] = $setting->key_value;
        }

        // Calculate validity (e.g., 1 year from join date, or static for now, let's use 1 year from join date)
        $validity = date('d M, Y', strtotime('+1 year', strtotime($member->join_date)));

        return $this->view('admin/members/id_card', [
            'title' => 'Member ID Card',
            'member' => $member,
            'designation' => $designation,
            'qrUrl' => $qrUrl,
            'globalSettings' => $globalSettings,
            'validity' => $validity
        ]);
    }

    public function offerLetter($id)
    {
        $member = $this->memberModel->find($id);
        if (!$member) {
            $_SESSION['error'] = 'Member not found.';
            return $this->redirect('admin/members');
        }

        $designation = null;
        if ($member->designation_id) {
            $designationModel = new \App\Models\Designation();
            $designation = $designationModel->find($member->designation_id);
        }

        // Build full address from structured fields
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

        $globalSettings = $this->loadGlobalSettings();

        return $this->view('admin/members/offer_letter', [
            'title' => 'Member Offer Letter',
            'member' => $member,
            'designation' => $designation,
            'memberAddress' => $memberAddress,
            'joinDate' => $joinDate,
            'todayDate' => $todayDate,
            'globalSettings' => $globalSettings,
        ]);
    }

    private function loadGlobalSettings()
    {
        $settingModel = new \App\Models\Setting();
        $settingsData = $settingModel->all();
        $globalSettings = [];
        foreach ($settingsData as $setting) {
            $globalSettings[$setting->key_name] = $setting->key_value;
        }
        return $globalSettings;
    }

    public function fees()
    {
        $year = $_GET['year'] ?? date('Y');
        $month = isset($_GET['month']) ? (int)$_GET['month'] : 0;
        $members = $this->memberModel->getActiveWithDesignation();

        // Determine the full year range for the dropdown
        $donationModel = new \App\Models\Donation();
        $donationYears = $donationModel->getYearsRange();
        $minDonationYear = !empty($donationYears) ? min($donationYears) : date('Y');
        // Also check the earliest member join date
        $minJoinYear = $this->memberModel->getMinJoinYear();
        $minYear = $minJoinYear ? min($minJoinYear, $minDonationYear) : $minDonationYear;
        $years = range($minYear, date('Y'));
        $designationModel = new \App\Models\Designation();
        $designations = $designationModel->all();
        $designationMap = [];
        foreach ($designations as $d) {
            $designationMap[$d->id] = ['name' => $d->name, 'amount' => (float)($d->monthly_amount ?? 0)];
        }

        $donationModel = new \App\Models\Donation();
        $currentMonth = (int)date('n');
        $monthsSoFar = (int)$year < (int)date('Y') ? 12 : $currentMonth;

        $feesData = [];
        foreach ($members as $member) {
            $desig = $designationMap[$member->designation_id] ?? ['name' => 'Member', 'amount' => 0];
            $monthlyFee = $desig['amount'];
            $paidMonths = $donationModel->getPaidMonthsByYear($member->id, $year);
            $stats = $donationModel->getMemberStats($member->id);
            $totalPaid = (float)($stats->total_amount ?? 0);
            $expected = $monthlyFee * $monthsSoFar;

            $feesData[] = [
                'id' => $member->id,
                'name' => $member->name,
                'membership_id' => $member->membership_id,
                'designation' => $desig['name'],
                'monthly_fee' => $monthlyFee,
                'paid_months' => $paidMonths,
                'total_paid' => $totalPaid,
                'total_due' => max(0, $expected - $totalPaid),
                'member_status' => $member->status,
            ];
        }

        $monthNames = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
            5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug',
            9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec'
        ];

        return $this->view('admin/members/fees', [
            'title' => 'Membership Fees',
            'feesData' => $feesData,
            'year' => $year,
            'years' => $years,
            'month' => $month,
            'monthNames' => $monthNames,
            'currentMonth' => $currentMonth,
            'designations' => $designations,
        ]);
    }

    public function memberFees($id)
    {
        $member = $this->memberModel->find($id);
        if (!$member) {
            $_SESSION['error'] = 'Member not found.';
            return $this->redirect('admin/members/fees');
        }

        $year = $_GET['year'] ?? date('Y');
        $month = isset($_GET['month']) ? (int)$_GET['month'] : 0;
        $joinYear = $member->join_date ? (int)date('Y', strtotime($member->join_date)) : date('Y');
        $years = range($joinYear, date('Y'));
        $designationModel = new \App\Models\Designation();
        $designation = $member->designation_id ? $designationModel->find($member->designation_id) : null;
        $designationName = $designation ? $designation->name : 'Member';
        $monthlyFee = $designation ? (float)($designation->monthly_amount ?? 0) : 0;

        $donationModel = new \App\Models\Donation();
        $currentMonth = (int)date('n');
        $monthsSoFar = (int)$year < (int)date('Y') ? 12 : $currentMonth;
        $paidMonths = $donationModel->getPaidMonthsByYear($member->id, $year);
        $stats = $donationModel->getMemberStats($member->id);
        $totalPaid = (float)($stats->total_amount ?? 0);
        $expected = $monthlyFee * $monthsSoFar;

        $monthNames = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
        ];
        $monthNamesShort = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
            5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug',
            9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec'
        ];

        $months = [];
        $joinYear = $member->join_date ? (int)date('Y', strtotime($member->join_date)) : date('Y');
        $joinMonth = $member->join_date ? (int)date('n', strtotime($member->join_date)) : 1;
        $startMonth = ((int)$year == $joinYear) ? $joinMonth : 1;

        for ($m = $startMonth; $m <= 12; $m++) {
            $isPaid = in_array($m, $paidMonths);
            if ((int)$year == (int)date('Y') && $m == $currentMonth) {
                $status = $isPaid ? 'Paid' : 'Due';
            } elseif ((int)$year > (int)date('Y') || ((int)$year == (int)date('Y') && $m > $currentMonth)) {
                $status = $isPaid ? 'Paid' : 'Upcoming';
            } else {
                $status = $isPaid ? 'Paid' : 'Due';
            }
            $months[] = [
                'num' => $m,
                'name' => $monthNames[$m],
                'amount' => $monthlyFee,
                'status' => $status,
                'is_paid' => $isPaid,
                'is_current' => ((int)$year == (int)date('Y') && $m == $currentMonth),
            ];
        }

        // Filter by month if selected
        if ($month > 0) {
            $months = array_values(array_filter($months, function($mth) use ($month) {
                return $mth['num'] == $month;
            }));
        }

        // Get actual donations for this member
        $donations = $donationModel->getByMemberId($member->id);
        
        // Generate signed URLs for completed donations
        foreach ($donations as $donation) {
            if ($donation->status === 'completed' && !empty($donation->uuid)) {
                $donation->receipt_url = \App\Helpers\SignedUrlHelper::generateReceiptUrl($donation->uuid);
            }
        }

        // Calculate pending months for register payment dropdown
        $joinMonth = $member->join_date ? (int)date('n', strtotime($member->join_date)) : 1;
        $pendingMonths = [];
        $currentYear = (int)date('Y');
        for ($y = $joinYear; $y <= $currentYear; $y++) {
            $startM = ($y == $joinYear) ? $joinMonth : 1;
            $endM = ($y == $currentYear) ? 12 : 12;
            $paidInYear = $donationModel->getPaidMonthsByYear($member->id, $y);
            for ($m = $startM; $m <= $endM; $m++) {
                if (!in_array($m, $paidInYear)) {
                    $pendingMonths[] = ['month' => $m, 'year' => $y];
                }
            }
        }

        return $this->view('admin/members/member_fees', [
            'title' => 'Fees - ' . $member->name,
            'member' => $member,
            'designationName' => $designationName,
            'monthlyFee' => $monthlyFee,
            'year' => $year,
            'years' => $years,
            'month' => $month,
            'monthNames' => $monthNamesShort,
            'months' => $months,
            'totalPaid' => $totalPaid,
            'totalDue' => max(0, $expected - $totalPaid),
            'paidMonths' => $paidMonths,
            'donations' => $donations,
            'pendingMonths' => $pendingMonths,
        ]);
    }
}
