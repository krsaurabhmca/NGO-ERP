<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Partner;
use App\Models\AuditLog;
use App\Helpers\UploadHelper;

class PartnerController extends Controller
{
    protected $partnerModel;

    public function __construct()
    {
        parent::__construct();
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('auth');
        }
        $this->checkModuleAccess('partners');
        $this->partnerModel = new Partner();
    }

    public function index()
    {
        $partners = $this->partnerModel->all();
        return $this->view('admin/partners/index', [
            'title' => 'Manage Partners',
            'partners' => $partners
        ]);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            json_response(['status' => 'error', 'message' => 'Invalid request']);
            exit;
        }

        $name = trim($_POST['name'] ?? '');
        $website = trim($_POST['website'] ?? '');

        if (empty($name)) {
            json_response(['status' => 'error', 'message' => 'Partner name is required.']);
            exit;
        }

        $logoPath = null;
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
            $valid = validate_upload($_FILES['logo'], ['jpg', 'jpeg', 'png', 'svg', 'webp'], ['image/jpeg', 'image/png', 'image/svg+xml', 'image/webp'], 500 * 1024);
            if ($valid !== true) {
                json_response(['status' => 'error', 'message' => 'Logo: ' . $valid]);
                exit;
            }
            $uploadDir = UPLOAD_PATH . 'partners/';
            $fileName = \App\Helpers\UploadHelper::processImage($_FILES['logo'], $uploadDir);
            if ($fileName) {
                $logoPath = 'uploads/partners/' . $fileName;
            }
        }

        $data = [
            'name' => $name,
            'website' => $website ?: null,
            'logo' => $logoPath
        ];

        if ($this->partnerModel->create($data)) {
            $id = $this->partnerModel->lastInsertId();
            $partner = $this->partnerModel->find($id);
            AuditLog::log('create', 'partner', $id, null, $data);
            json_response(['status' => 'success', 'message' => 'Partner added successfully.', 'data' => (array) $partner]);
        } else {
            json_response(['status' => 'error', 'message' => 'Failed to add partner.']);
        }
        exit;
    }

    public function edit($id)
    {
        $partner = $this->partnerModel->find($id);
        if (!$partner) {
            json_response(['status' => 'error', 'message' => 'Partner not found.']);
            exit;
        }
        json_response(['status' => 'success', 'data' => $partner]);
        exit;
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            json_response(['status' => 'error', 'message' => 'Invalid request']);
            exit;
        }

        $partner = $this->partnerModel->find($id);
        if (!$partner) {
            json_response(['status' => 'error', 'message' => 'Partner not found.']);
            exit;
        }

        // Capture old data
        $oldData = (array) $partner;

        $name = trim($_POST['name'] ?? '');
        $website = trim($_POST['website'] ?? '');

        $logoPath = $partner->logo;
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
            $valid = validate_upload($_FILES['logo'], ['jpg', 'jpeg', 'png', 'svg', 'webp'], ['image/jpeg', 'image/png', 'image/svg+xml', 'image/webp'], 500 * 1024);
            if ($valid !== true) {
                json_response(['status' => 'error', 'message' => 'Logo: ' . $valid]);
                exit;
            }
            $uploadDir = UPLOAD_PATH . 'partners/';
            $fileName = \App\Helpers\UploadHelper::processImage($_FILES['logo'], $uploadDir);
            if ($fileName) {
                $logoPath = 'uploads/partners/' . $fileName;
            }
        }

        $data = [
            'name' => $name,
            'website' => $website ?: null,
            'logo' => $logoPath
        ];

        if ($this->partnerModel->update($id, $data)) {
            $partner = $this->partnerModel->find($id);
            // Audit log
            AuditLog::log('update', 'partners', $id, $oldData, $data);
            json_response(['status' => 'success', 'message' => 'Partner updated successfully.', 'data' => (array) $partner]);
        } else {
            json_response(['status' => 'error', 'message' => 'Failed to update partner.']);
        }
        exit;
    }

    public function delete($id)
    {
        $partner = $this->partnerModel->find($id);
        if ($this->partnerModel->delete($id)) {
            // Audit log
            AuditLog::log('delete', 'partners', $id, $partner ? (array) $partner : null, null);
            json_response(['status' => 'success', 'message' => 'Partner deleted successfully.']);
        } else {
            json_response(['status' => 'error', 'message' => 'Failed to delete partner.']);
        }
        exit;
    }
}
