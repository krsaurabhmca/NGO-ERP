<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Mailer;
use App\Models\Donation;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\AuditLog;

class FinanceController extends Controller
{
    protected $donationModel;
    protected $expenseModel;

    public function __construct()
    {
        parent::__construct();
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('auth');
        }
        $this->checkModuleAccess('finance');
        $this->donationModel = new Donation();
        $this->expenseModel = new Expense();
    }

    public function donations()
    {
        // Automatically expire pending donations older than 2 hours
        $this->donationModel->expirePendingDonations();

        // Collect filter parameters
        $filters = [
            'status' => $_GET['status'] ?? '',
            'payment_method' => $_GET['payment_method'] ?? '',
            'date_from' => $_GET['date_from'] ?? '',
            'date_to' => $_GET['date_to'] ?? '',
            'search' => trim($_GET['search'] ?? ''),
        ];

        // Check if any filter is active
        $hasFilters = !empty($filters['status']) || !empty($filters['payment_method']) || !empty($filters['date_from']) || !empty($filters['date_to']) || !empty($filters['search']);

        if ($hasFilters) {
            $donations = $this->donationModel->filter($filters);
        } else {
            $donations = array_reverse($this->donationModel->all());
        }

        $totalCompleted = $this->donationModel->totalCompleted();
        $totalPending = $this->donationModel->totalByDateRange('2000-01-01', date('Y-m-d'), 'pending');
        $totalFailed = $this->donationModel->totalByDateRange('2000-01-01', date('Y-m-d'), 'failed');
        $countCompleted = $this->donationModel->countCompleted();

        $razorpayTotal = $this->donationModel->totalByPaymentMethod('razorpay');
        $offlineTotal = $this->donationModel->totalByPaymentMethod('offline');
        
        $donorModel = new \App\Models\Donor();
        $registeredDonors = $donorModel->where('status', 'active');

        return $this->view('admin/finance/donations', [
            'title' => 'All Donations',
            'donations' => $donations,
            'totalCompleted' => $totalCompleted,
            'totalPending' => $totalPending,
            'totalFailed' => $totalFailed,
            'countCompleted' => $countCompleted,
            'razorpayTotal' => $razorpayTotal,
            'offlineTotal' => $offlineTotal,
            'registeredDonors' => $registeredDonors,
            'filters' => $filters,
        ]);
    }

    public function expenses()
    {
        $expenses = $this->expenseModel->all();
        $categoryModel = new ExpenseCategory();
        $categories = $categoryModel->all();
        $totalExpenses = $this->expenseModel->totalByDateRange('2000-01-01', date('Y-m-d'));

        return $this->view('admin/finance/expenses', [
            'title' => 'Expenses',
            'expenses' => $expenses,
            'categories' => $categories,
            'totalExpenses' => $totalExpenses
        ]);
    }

    public function storeCategory()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            if (!empty($name)) {
                $model = new ExpenseCategory();
                $model->create(['name' => $name]);
                try { AuditLog::log('create', 'expense_category', $model->lastInsertId(), null, ['name' => $name]); } catch (\Exception $e) {}
                $_SESSION['success'] = 'Expense category added successfully.';
            } else {
                $_SESSION['error'] = 'Please enter a category name.';
            }
        }
        return $this->redirect('admin/finance/expenses#modal-add-category');
    }

    public function deleteCategory($id)
    {
        $model = new ExpenseCategory();
        $category = $model->find($id);
        if ($category) {
            $model->delete($id);

            try { AuditLog::log('delete', 'expense_categories', $id, (array) $category, null); } catch (\Exception $e) {}

            $_SESSION['success'] = 'Category deleted successfully.';
        }
        return $this->redirect('admin/finance/expenses#modal-add-category');
    }

    public function storeExpense()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            json_response(['status' => 'error', 'message' => 'Invalid request']);
            exit;
        }

        $amount = filter_var($_POST['amount'] ?? 0, FILTER_VALIDATE_FLOAT);
        if ($amount === false || $amount <= 0) {
            json_response(['status' => 'error', 'message' => 'Amount must be a positive number.']);
            exit;
        }
        if ($amount > MAX_AMOUNT) {
            json_response(['status' => 'error', 'message' => 'Amount exceeds maximum allowed value.']);
            exit;
        }

        $data = [
            'description' => $_POST['description'] ?? '',
            'amount' => $amount,
            'category' => $_POST['category'] ?? 'General',
            'expense_date' => $_POST['expense_date'] ?? date('Y-m-d'),
            'payment_method' => $_POST['payment_method'] ?? 'cash',
            'notes' => $_POST['notes'] ?? ''
        ];

        if (empty($data['description'])) {
            json_response(['status' => 'error', 'message' => 'Description is required.']);
            exit;
        }

        if (isset($_FILES['receipt']) && $_FILES['receipt']['error'] === UPLOAD_ERR_OK) {
            $valid = validate_upload($_FILES['receipt'], ['pdf', 'jpg', 'jpeg', 'png'], ['application/pdf', 'image/jpeg', 'image/png'], 2 * 1024 * 1024);
            if ($valid !== true) {
                json_response(['status' => 'error', 'message' => 'Receipt: ' . $valid]);
                exit;
            }
            $uploadDir = UPLOAD_PATH . 'receipts/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            $ext = strtolower(pathinfo($_FILES['receipt']['name'], PATHINFO_EXTENSION));
            $filename = 'receipt_' . time() . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
            if (move_uploaded_file($_FILES['receipt']['tmp_name'], $uploadDir . $filename)) {
                $data['receipt'] = 'uploads/receipts/' . $filename;
            }
        }

        if ($this->expenseModel->create($data)) {
            try { AuditLog::log('create', 'expense', $this->expenseModel->lastInsertId(), null, $data); } catch (\Exception $e) {}
            json_response(['status' => 'success', 'message' => 'Expense added successfully.']);
        } else {
            json_response(['status' => 'error', 'message' => 'Failed to add expense.']);
        }
        exit;
    }

    public function updateExpense()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            json_response(['status' => 'error', 'message' => 'Invalid request']);
            exit;
        }

        $id = $_POST['id'] ?? 0;
        $expense = $this->expenseModel->find($id);
        if (!$expense) {
            json_response(['status' => 'error', 'message' => 'Expense not found.']);
            exit;
        }

        // Capture old data for audit
        $oldData = (array) $expense;

        $amount = filter_var($_POST['amount'] ?? 0, FILTER_VALIDATE_FLOAT);
        if ($amount === false || $amount <= 0) {
            json_response(['status' => 'error', 'message' => 'Amount must be a positive number.']);
            exit;
        }
        if ($amount > MAX_AMOUNT) {
            json_response(['status' => 'error', 'message' => 'Amount exceeds maximum allowed value.']);
            exit;
        }

        $data = [
            'description' => $_POST['description'] ?? '',
            'amount' => $amount,
            'category' => $_POST['category'] ?? 'General',
            'expense_date' => $_POST['expense_date'] ?? date('Y-m-d'),
            'payment_method' => $_POST['payment_method'] ?? 'cash',
            'notes' => $_POST['notes'] ?? ''
        ];

        if (empty($data['description'])) {
            json_response(['status' => 'error', 'message' => 'Description is required.']);
            exit;
        }

        if (isset($_FILES['receipt']) && $_FILES['receipt']['error'] === UPLOAD_ERR_OK) {
            $valid = validate_upload($_FILES['receipt'], ['pdf', 'jpg', 'jpeg', 'png'], ['application/pdf', 'image/jpeg', 'image/png'], 2 * 1024 * 1024);
            if ($valid !== true) {
                json_response(['status' => 'error', 'message' => 'Receipt: ' . $valid]);
                exit;
            }
            $uploadDir = UPLOAD_PATH . 'receipts/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            $ext = strtolower(pathinfo($_FILES['receipt']['name'], PATHINFO_EXTENSION));
            $filename = 'receipt_' . time() . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
            if (move_uploaded_file($_FILES['receipt']['tmp_name'], $uploadDir . $filename)) {
                $data['receipt'] = 'uploads/receipts/' . $filename;
            }
        }

        if ($this->expenseModel->update($id, $data)) {
            try { AuditLog::log('update', 'expenses', $id, $oldData, $data); } catch (\Exception $e) {}

            json_response(['status' => 'success', 'message' => 'Expense updated successfully.']);
        } else {
            json_response(['status' => 'error', 'message' => 'Failed to update expense.']);
        }
        exit;
    }

    public function viewExpense($id)
    {
        $expense = $this->expenseModel->find($id);
        if ($expense) {
            json_response(['status' => 'success', 'data' => $expense]);
        } else {
            json_response(['status' => 'error', 'message' => 'Expense not found.']);
        }
        exit;
    }

    public function deleteExpense($id)
    {
        // Capture expense data before deletion
        $expense = $this->expenseModel->find($id);

        if ($this->expenseModel->delete($id)) {
            try { AuditLog::log('delete', 'expenses', $id, $expense ? (array) $expense : null, null); } catch (\Exception $e) {}

            $_SESSION['success'] = 'Expense deleted successfully.';
        } else {
            $_SESSION['error'] = 'Failed to delete expense.';
        }
        return $this->redirect('admin/finance/expenses');
    }

    public function reports()
    {
        $from = $_GET['from'] ?? date('Y-m-01');
        $to = $_GET['to'] ?? date('Y-m-d');

        $donationTotal = $this->donationModel->totalByDateRange($from, $to);
        $donationCount = $this->donationModel->countByDateRange($from, $to);
        $expenseTotal = $this->expenseModel->totalByDateRange($from, $to);
        $balance = $donationTotal - $expenseTotal;

        $monthlyDonations = $this->donationModel->getMonthlyTotals();
        $monthlyExpenses = [];
        $year = date('Y');
        for ($m = 1; $m <= 12; $m++) {
            $monthlyExpenses[$m] = $this->expenseModel->totalByDateRange("$year-$m-01", "$year-$m-31");
        }

        return $this->view('admin/finance/reports', [
            'title' => 'Financial Reports',
            'from' => $from,
            'to' => $to,
            'donationTotal' => $donationTotal,
            'donationCount' => $donationCount,
            'expenseTotal' => $expenseTotal,
            'balance' => $balance,
            'monthlyDonations' => $monthlyDonations,
            'monthlyExpenses' => $monthlyExpenses,
            'year' => $year
        ]);
    }

    public function donationReceipt($id)
    {
        $donation = $this->donationModel->find($id);
        if (!$donation || $donation->status !== 'completed') {
            $_SESSION['error'] = 'Donation not found or not completed.';
            return $this->redirect('admin/finance/donations');
        }

        $globalSettings = $this->globalSettings;
        $d = $donation;

        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $dompdf = new \Dompdf\Dompdf($options);

        ob_start();
        require __DIR__ . '/../views/admin/finance/receipt.php';
        $html = ob_get_clean();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $dompdf->stream('receipt-DON-' . str_pad($d->id, 5, '0', STR_PAD_LEFT) . '.pdf', [
            'Attachment' => false
        ]);
        exit;
    }

    public function storeDonation()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                json_response(['status' => 'error', 'message' => 'Invalid request']);
                exit;
            }

            $amount = filter_var($_POST['amount'] ?? 0, FILTER_VALIDATE_FLOAT);
            if ($amount === false || $amount <= 0) {
                json_response(['status' => 'error', 'message' => 'Amount must be a positive number.']);
                exit;
            }
            if ($amount > MAX_AMOUNT) {
                json_response(['status' => 'error', 'message' => 'Amount exceeds maximum allowed value.']);
                exit;
            }

            // Validate status against allowed values
            $allowedStatuses = ['pending', 'completed', 'failed'];
            $status = $_POST['status'] ?? 'pending';
            if (!in_array($status, $allowedStatuses)) {
                $status = 'pending';
            }

            // Admin can set any status they want, no forced reset for offline methods
            $paymentMethod = $_POST['payment_method'] ?? 'offline';

            $memberId = null;
            if (!empty($_POST['member_id'])) {
                $memberModel = new \App\Models\Member();
                $member = $memberModel->find($_POST['member_id']);
                if ($member) {
                    $memberId = $member->id;
                } else {
                    $memberId = (int)$_POST['member_id']; // Fallback
                }
            }

            $data = [
                'member_id' => $memberId,
                'donor_name' => trim($_POST['donor_name'] ?? ''),
                'donor_email' => trim($_POST['donor_email'] ?? ''),
                'donor_phone' => preg_replace('/[^0-9]/', '', $_POST['donor_phone'] ?? ''),
                'donor_address' => trim($_POST['donor_address'] ?? ''),
                'donor_city' => trim($_POST['donor_city'] ?? ''),
                'donor_state' => trim($_POST['donor_state'] ?? ''),
                'donor_pincode' => preg_replace('/[^0-9]/', '', $_POST['donor_pincode'] ?? ''),
                'donor_pan' => strtoupper(trim($_POST['donor_pan'] ?? '')),
                'amount' => $amount,
                'payment_method' => $paymentMethod,
                'status' => $status,
                'created_by' => $_SESSION['user_id'] ?? null,
                'campaign_id' => !empty($_POST['campaign_id']) ? (int)$_POST['campaign_id'] : null,
            ];

            $payFor = trim($_POST['payment_for_date'] ?? '');
            if (!empty($payFor)) {
                if (preg_match('/^\d{4}-\d{2}$/', $payFor)) {
                    $payFor .= '-01';
                }
                $data['payment_for_date'] = $payFor;
            }

            if (empty($data['donor_name']) || empty($data['amount'])) {
                json_response(['status' => 'error', 'message' => 'Donor name and amount are required.']);
                exit;
            }

            if ($this->donationModel->create($data)) {
                $donationId = $this->donationModel->lastInsertId();

                if ($status === 'completed') {
                    $donorModel = new \App\Models\Donor();
                    $donorModel->addDonationAmount($data['donor_email'], $data['donor_phone'], $amount);
                }

                if (!empty($data['campaign_id']) && $status === 'completed') {
                    $campaignModel = new \App\Models\Campaign();
                    $campaignModel->updateRaisedAmount($data['campaign_id']);
                }

                try { AuditLog::log('create', 'donations', $donationId, null, $data); } catch (\Exception $e) {}

                $message = $status === 'pending'
                    ? 'Donation recorded as pending. An admin must approve it to mark as completed.'
                    : 'Donation added successfully.';

                json_response(['status' => 'success', 'message' => $message]);
            } else {
                json_response(['status' => 'error', 'message' => 'Failed to add donation.']);
            }
            exit;
        } catch (\Throwable $t) {
            json_response(['status' => 'error', 'message' => 'Server Error: ' . $t->getMessage() . ' at ' . $t->getFile() . ':' . $t->getLine()]);
            exit;
        }
    }

    /**
     * Approve a pending offline donation
     */
    public function approveDonation($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            json_response(['status' => 'error', 'message' => 'Invalid request']);
            exit;
        }

        $donation = $this->donationModel->find($id);
        if (!$donation) {
            json_response(['status' => 'error', 'message' => 'Donation not found.']);
            exit;
        }

        if ($donation->status !== 'pending') {
            json_response(['status' => 'error', 'message' => 'Only pending donations can be approved.']);
            exit;
        }

        $oldData = (array) $donation;

        if ($this->donationModel->update($id, [
            'status' => 'completed',
            'approved_by' => $_SESSION['user_id'] ?? null,
            'approved_at' => date('Y-m-d H:i:s')
        ])) {
            $donorModel = new \App\Models\Donor();
            $donorModel->addDonationAmount($donation->donor_email, $donation->donor_phone, $donation->amount);

            if (!empty($donation->campaign_id)) {
                $campaignModel = new \App\Models\Campaign();
                $campaignModel->updateRaisedAmount($donation->campaign_id);
            }

            try { AuditLog::log('approve', 'donations', $id, $oldData, ['status' => 'completed', 'approved_by' => $_SESSION['user_id'] ?? null]); } catch (\Exception $e) {}

            json_response(['status' => 'success', 'message' => 'Donation approved successfully.']);
        } else {
            json_response(['status' => 'error', 'message' => 'Failed to approve donation.']);
        }
    }

    public function updateDonationStatus($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = 'Invalid request';
            return $this->redirect('admin/finance/donations');
        }

        $donation = $this->donationModel->findByUuid($id);
        if (!$donation) {
            $donation = $this->donationModel->find($id);
        }

        if (!$donation) {
            $_SESSION['error'] = 'Donation not found.';
            return $this->redirect('admin/finance/donations');
        }

        $allowedStatuses = ['pending', 'completed', 'failed'];
        $newStatus = $_POST['status'] ?? '';

        if (!in_array($newStatus, $allowedStatuses)) {
            $_SESSION['error'] = 'Invalid status selected.';
            return $this->redirect('admin/finance/donations');
        }

        $oldData = (array) $donation;
        $updateData = ['status' => $newStatus];

        if ($newStatus === 'completed' && $donation->status !== 'completed') {
            $updateData['approved_by'] = $_SESSION['user_id'] ?? null;
            $updateData['approved_at'] = date('Y-m-d H:i:s');
            
            $donorModel = new \App\Models\Donor();
            $donorModel->addDonationAmount($donation->donor_email, $donation->donor_phone, $donation->amount);
        } else if ($donation->status === 'completed' && $newStatus !== 'completed') {
            $donorModel = new \App\Models\Donor();
            $donorModel->addDonationAmount($donation->donor_email, $donation->donor_phone, -$donation->amount);
        }

        if ($this->donationModel->update($donation->id, $updateData)) {
            if (!empty($donation->campaign_id) && ($newStatus === 'completed' || $donation->status === 'completed')) {
                $campaignModel = new \App\Models\Campaign();
                $campaignModel->updateRaisedAmount($donation->campaign_id);
            }
            try { AuditLog::log('update_status', 'donations', $donation->id, $oldData, $updateData); } catch (\Exception $e) {}
            $_SESSION['success'] = 'Donation status updated successfully.';
        } else {
            $_SESSION['error'] = 'Failed to update donation status.';
        }

        return $this->redirect('admin/finance/donations');
    }

    public function sendReceiptMail($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            json_response(['status' => 'error', 'message' => 'Invalid request']);
            exit;
        }

        $donation = $this->donationModel->find($id);
        if (!$donation || $donation->status !== 'completed') {
            json_response(['status' => 'error', 'message' => 'Donation not found or not completed.']);
            exit;
        }

        $to = $donation->donor_email;
        if (empty($to)) {
            json_response(['status' => 'error', 'message' => 'Donor email not available.']);
            exit;
        }

        $ngoName = $this->globalSettings['ngo_name'] ?? 'NGO HELP';
        $rn = 'DON-' . str_pad($donation->id, 5, '0', STR_PAD_LEFT);
        $receiptUrl = url('admin/finance/donations/receipt/' . $donation->id);
        $htmlReceiptUrl = url('admin/finance/donations/receipt/' . $donation->id . '?html=1');

        $subject = "Donation Receipt - {$rn} - {$ngoName}";
        $message = "Dear {$donation->donor_name},\n\n";
        $message .= "Thank you for your generous donation of Rs. " . number_format($donation->amount, 2) . " to {$ngoName}.\n\n";
        $message .= "Receipt No: {$rn}\n";
        $message .= "Date: " . date('d M Y, h:i A', strtotime($donation->created_at)) . "\n\n";
        $message .= "You can view/download your receipt here:\n";
        $message .= "PDF: {$receiptUrl}\n";
        $message .= "HTML: {$htmlReceiptUrl}\n\n";
        $message .= "Thank you for your support!\n";
        $message .= $ngoName;

        $mailer = new Mailer($this->globalSettings);
        if ($mailer->send($to, $subject, $message)) {
            json_response(['status' => 'success', 'message' => 'Receipt sent to ' . $to]);
        } else {
            json_response(['status' => 'error', 'message' => 'Failed to send email. Check server mail configuration.']);
        }
        exit;
    }

    public function deleteDonation($id)
    {
        // Capture donation data before deletion
        $donation = $this->donationModel->findByUuid($id);
        if (!$donation) {
            $donation = $this->donationModel->find($id);
        }

        if ($this->donationModel->delete($donation ? $donation->id : $id)) {
            if ($donation && $donation->status === 'completed') {
                $donorModel = new \App\Models\Donor();
                $donorModel->addDonationAmount($donation->donor_email, $donation->donor_phone, -$donation->amount);
            }
            try { AuditLog::log('delete', 'donations', $id, $donation ? (array) $donation : null, null); } catch (\Exception $e) {}

            $_SESSION['success'] = 'Donation deleted successfully.';
        } else {
            $_SESSION['error'] = 'Failed to delete donation.';
        }
        return $this->redirect('admin/finance/donations');
    }
}
