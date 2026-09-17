<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Campaign;
use App\Helpers\UploadHelper;
use App\Models\AuditLog;

class CampaignController extends Controller
{
    protected $campaignModel;

    public function __construct()
    {
        parent::__construct();
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('auth');
        }
        $this->checkModuleAccess('campaigns');
        $this->campaignModel = new Campaign();
    }

    public function index()
    {
        $campaigns = $this->campaignModel->all();
        return $this->view('admin/campaigns/index', [
            'title' => 'Manage Campaigns',
            'campaigns' => $campaigns
        ]);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $goal_amount = $_POST['goal_amount'] ?? 0;
            $start_date = $_POST['start_date'] ?? null;
            $end_date = $_POST['end_date'] ?? null;
            $status = $_POST['status'] ?? 'active';

            if (empty($title)) {
                $_SESSION['error'] = 'Campaign title is required.';
                return $this->redirect('admin/campaigns');
            }

            $imagePath = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $valid = validate_upload($_FILES['image'], ['jpg', 'jpeg', 'png', 'gif', 'webp'], ['image/jpeg', 'image/png', 'image/gif', 'image/webp'], 500 * 1024);
                if ($valid !== true) {
                    $_SESSION['error'] = 'Campaign image: ' . $valid;
                    return $this->redirect('admin/campaigns');
                }
                $uploadDir = UPLOAD_PATH . 'campaigns/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                $fileName = time() . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $fileName)) {
                    $imagePath = 'uploads/campaigns/' . $fileName;
                }
            }

            $data = [
                'title' => $title,
                'slug' => $this->campaignModel->createSlug($title),
                'description' => $description,
                'image' => $imagePath,
                'goal_amount' => $goal_amount,
                'raised_amount' => 0,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'status' => $status
            ];

            if ($this->campaignModel->create($data)) {
                AuditLog::log('create', 'campaign', $this->campaignModel->lastInsertId(), null, $data);
                $_SESSION['success'] = 'Campaign created successfully.';
            } else {
                $_SESSION['error'] = 'Failed to create campaign.';
            }
        }
        return $this->redirect('admin/campaigns');
    }

    public function edit($id)
    {
        $campaign = $this->campaignModel->find($id);
        if (!$campaign) {
            json_response(['status' => 'error', 'message' => 'Campaign not found.']);
            exit;
        }
        json_response(['status' => 'success', 'data' => $campaign]);
        exit;
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $campaign = $this->campaignModel->find($id);
            if (!$campaign) {
                $_SESSION['error'] = 'Campaign not found.';
                return $this->redirect('admin/campaigns');
            }

            $title = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $goal_amount = $_POST['goal_amount'] ?? 0;
            $raised_amount = max(0, (float)($_POST['raised_amount'] ?? 0));
            $oldRaised = (float)($campaign->raised_amount ?? 0);
            if ($raised_amount < $oldRaised) {
                $raised_amount = $oldRaised;
            }
            $start_date = $_POST['start_date'] ?? null;
            $end_date = $_POST['end_date'] ?? null;
            $status = $_POST['status'] ?? 'active';

            $imagePath = $campaign->image;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $valid = validate_upload($_FILES['image'], ['jpg', 'jpeg', 'png', 'gif', 'webp'], ['image/jpeg', 'image/png', 'image/gif', 'image/webp'], 500 * 1024);
                if ($valid !== true) {
                    $_SESSION['error'] = 'Campaign image: ' . $valid;
                    return $this->redirect('admin/campaigns');
                }
                $uploadDir = UPLOAD_PATH . 'campaigns/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                $fileName = time() . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $fileName)) {
                    safe_unlink($imagePath);
                    $imagePath = 'uploads/campaigns/' . $fileName;
                }
            }

            $data = [
                'title' => $title,
                'description' => $description,
                'image' => $imagePath,
                'goal_amount' => $goal_amount,
                'raised_amount' => $raised_amount,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'status' => $status
            ];

            if ($title !== $campaign->title) {
                $data['slug'] = $this->campaignModel->createSlug($title);
            }

            // Capture old data for audit log
            $oldData = [
                'title' => $campaign->title,
                'goal_amount' => $campaign->goal_amount,
                'raised_amount' => $campaign->raised_amount,
                'status' => $campaign->status,
                'start_date' => $campaign->start_date,
                'end_date' => $campaign->end_date
            ];

            if ($this->campaignModel->update($id, $data)) {
                // Log campaign update (especially important for raised_amount changes)
                AuditLog::log('update', 'campaigns', $id, $oldData, $data);
                $_SESSION['success'] = 'Campaign updated successfully.';
            } else {
                $_SESSION['error'] = 'Failed to update campaign.';
            }
        }
        return $this->redirect('admin/campaigns');
    }

    public function delete($id)
    {
        $campaign = $this->campaignModel->find($id);
        if ($campaign) {
            // Capture campaign data before deletion
            $oldData = [
                'title' => $campaign->title,
                'goal_amount' => $campaign->goal_amount,
                'raised_amount' => $campaign->raised_amount,
                'status' => $campaign->status,
                'start_date' => $campaign->start_date,
                'end_date' => $campaign->end_date
            ];

            safe_unlink($campaign->image);
            if ($this->campaignModel->delete($id)) {
                // Log campaign deletion
                AuditLog::log('delete', 'campaigns', $id, $oldData, null);
                $_SESSION['success'] = 'Campaign deleted successfully.';
            } else {
                $_SESSION['error'] = 'Failed to delete campaign.';
            }
        }
        return $this->redirect('admin/campaigns');
    }
}
