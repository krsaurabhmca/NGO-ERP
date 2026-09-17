<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Beneficiary;
use App\Helpers\UploadHelper;
use App\Models\AssistanceType;
use App\Models\AuditLog;

class BeneficiaryController extends Controller
{
    protected $beneficiaryModel;

    public function __construct()
    {
        parent::__construct();
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('auth');
        }
        $this->checkModuleAccess('stakeholders');
        $this->beneficiaryModel = new Beneficiary();
    }

    public function index()
    {
        $search = $_GET['search'] ?? null;
        if ($search) {
            $beneficiaries = $this->beneficiaryModel->search(['name', 'contact_info', 'beneficiary_id'], $search);
        } else {
            $beneficiaries = $this->beneficiaryModel->all();
        }

        $assistanceTypeModel = new AssistanceType();
        $assistanceTypes = $assistanceTypeModel->all();

        return $this->view('admin/beneficiaries/index', [
            'title' => 'Manage Beneficiaries',
            'beneficiaries' => $beneficiaries,
            'assistanceTypes' => $assistanceTypes
        ]);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $photoPath = null;
            $docPath = null;

            // Handle uploads
            $uploadDir = UPLOAD_PATH . 'beneficiaries/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Photo upload
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                $valid = validate_upload($_FILES['photo'], ['jpg', 'jpeg', 'png', 'gif', 'webp'], ['image/jpeg', 'image/png', 'image/gif', 'image/webp'], 500 * 1024);
                if ($valid !== true) {
                    $_SESSION['error'] = 'Photo: ' . $valid;
                    return $this->redirect('admin/beneficiaries');
                }
                $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
                $photoName = 'photo_' . time() . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
                if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadDir . $photoName)) {
                    $photoPath = 'uploads/beneficiaries/' . $photoName;
                }
            }

            // Document upload
            if (isset($_FILES['documents']) && $_FILES['documents']['error'] === UPLOAD_ERR_OK) {
                $valid = validate_upload($_FILES['documents'], ['pdf', 'jpg', 'jpeg', 'png'], ['application/pdf', 'image/jpeg', 'image/png'], 2 * 1024 * 1024);
                if ($valid !== true) {
                    $_SESSION['error'] = 'Documents: ' . $valid;
                    return $this->redirect('admin/beneficiaries');
                }
                $ext = strtolower(pathinfo($_FILES['documents']['name'], PATHINFO_EXTENSION));
                $docName = 'doc_' . time() . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
                if (move_uploaded_file($_FILES['documents']['tmp_name'], $uploadDir . $docName)) {
                    $docPath = 'uploads/beneficiaries/' . $docName;
                }
            }

            $data = [
                'beneficiary_id' => $this->beneficiaryModel->generateBeneficiaryId($this->globalSettings['id_prefix_beneficiary'] ?? 'BEN'),
                'name' => $_POST['name'] ?? '',
                'date_of_birth' => !empty($_POST['date_of_birth']) ? $_POST['date_of_birth'] : null,
                'gender' => $_POST['gender'] ?? 'not_specified',
                'photo' => $photoPath,
                'contact_info' => $_POST['contact_info'] ?? '',
                'address' => $_POST['address'] ?? '',
                'assistance_type' => $_POST['assistance_type'] ?? '',
                'documents' => $docPath,
                'enrolled_date' => $_POST['enrolled_date'] ?? date('Y-m-d'),
                'status' => $_POST['status'] ?? 'active'
            ];

            if ($this->beneficiaryModel->create($data)) {
                AuditLog::log('create', 'beneficiary', $this->beneficiaryModel->lastInsertId(), null, $data);
                $_SESSION['success'] = 'Beneficiary added successfully.';
            } else {
                $_SESSION['error'] = 'Failed to add beneficiary.';
            }
        }
        return $this->redirect('admin/beneficiaries');
    }

    public function edit($id)
    {
        $beneficiary = $this->beneficiaryModel->find($id);
        if (!$beneficiary) {
            json_response(['status' => 'error', 'message' => 'Beneficiary not found.']);
            exit;
        }
        // Verify user has access to this record
        if (!$this->beneficiaryModel->userCanAccess($id, $_SESSION['user_id'] ?? 0)) {
            json_response(['status' => 'error', 'message' => 'Access denied.']);
            exit;
        }
        json_response(['status' => 'success', 'data' => $beneficiary]);
        exit;
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $beneficiary = $this->beneficiaryModel->find($id);
            if (!$beneficiary) {
                $_SESSION['error'] = 'Beneficiary not found.';
                return $this->redirect('admin/beneficiaries');
            }

            if (!$this->beneficiaryModel->userCanAccess($id, $_SESSION['user_id'] ?? 0)) {
                $_SESSION['error'] = 'Access denied.';
                return $this->redirect('admin/beneficiaries');
            }

            // Capture old data for audit
            $oldData = (array) $beneficiary;

            $photoPath = $beneficiary->photo;
            $docPath = $beneficiary->documents;
            $uploadDir = UPLOAD_PATH . 'beneficiaries/';

            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                $valid = validate_upload($_FILES['photo'], ['jpg', 'jpeg', 'png', 'gif', 'webp'], ['image/jpeg', 'image/png', 'image/gif', 'image/webp'], 500 * 1024);
                if ($valid !== true) {
                    $_SESSION['error'] = 'Photo: ' . $valid;
                    return $this->redirect('admin/beneficiaries');
                }
                $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
                $photoName = 'photo_' . time() . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
                if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadDir . $photoName)) {
                    if ($photoPath && file_exists(UPLOAD_PATH . 'beneficiaries/' . basename($photoPath))) {
                        unlink(UPLOAD_PATH . 'beneficiaries/' . basename($photoPath));
                    }
                    $photoPath = 'uploads/beneficiaries/' . $photoName;
                }
            }

            if (isset($_FILES['documents']) && $_FILES['documents']['error'] === UPLOAD_ERR_OK) {
                $valid = validate_upload($_FILES['documents'], ['pdf', 'jpg', 'jpeg', 'png'], ['application/pdf', 'image/jpeg', 'image/png'], 2 * 1024 * 1024);
                if ($valid !== true) {
                    $_SESSION['error'] = 'Documents: ' . $valid;
                    return $this->redirect('admin/beneficiaries');
                }
                $ext = strtolower(pathinfo($_FILES['documents']['name'], PATHINFO_EXTENSION));
                $docName = 'doc_' . time() . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
                if (move_uploaded_file($_FILES['documents']['tmp_name'], $uploadDir . $docName)) {
                    if ($docPath && file_exists(UPLOAD_PATH . 'beneficiaries/' . basename($docPath))) {
                        unlink(UPLOAD_PATH . 'beneficiaries/' . basename($docPath));
                    }
                    $docPath = 'uploads/beneficiaries/' . $docName;
                }
            }

            $data = [
                'name' => $_POST['name'] ?? '',
                'date_of_birth' => !empty($_POST['date_of_birth']) ? $_POST['date_of_birth'] : null,
                'gender' => $_POST['gender'] ?? 'not_specified',
                'photo' => $photoPath,
                'contact_info' => $_POST['contact_info'] ?? '',
                'address' => $_POST['address'] ?? '',
                'assistance_type' => $_POST['assistance_type'] ?? '',
                'documents' => $docPath,
                'enrolled_date' => $_POST['enrolled_date'] ?? date('Y-m-d'),
                'status' => $_POST['status'] ?? 'active'
            ];

            if ($this->beneficiaryModel->update($id, $data)) {
                // Audit log
                AuditLog::log('update', 'beneficiaries', $id, $oldData, $data);
                $_SESSION['success'] = 'Beneficiary updated successfully.';
            } else {
                $_SESSION['error'] = 'Failed to update beneficiary.';
            }
        }
        return $this->redirect('admin/beneficiaries');
    }

    public function storeAssistanceType()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            if (!empty($name)) {
                $model = new AssistanceType();
                $model->create(['name' => $name]);
                AuditLog::log('create', 'assistance_type', $model->lastInsertId(), null, ['name' => $name]);
                $_SESSION['success'] = 'Assistance type added successfully.';
            } else {
                $_SESSION['error'] = 'Please enter a type name.';
            }
        }
        return $this->redirect('admin/beneficiaries#modal-add-assistance-type');
    }

    public function deleteAssistanceType($id)
    {
        $model = new AssistanceType();
        $type = $model->find($id);
        if ($type) {
            $model->delete($id);
            
            // Audit log
            AuditLog::log('delete', 'assistance_types', $id, (array) $type, null);
            
            $_SESSION['success'] = 'Assistance type deleted successfully.';
        }
        return $this->redirect('admin/beneficiaries#modal-add-assistance-type');
    }

    public function delete($id)
    {
        $beneficiary = $this->beneficiaryModel->find($id);
        if (!$beneficiary) {
            $_SESSION['error'] = 'Beneficiary not found.';
            return $this->redirect('admin/beneficiaries');
        }
        
        $oldData = (array) $beneficiary;
        
        if ($this->beneficiaryModel->delete($id)) {
            // Audit log
            AuditLog::log('delete', 'beneficiaries', $id, $oldData, null);
            $_SESSION['success'] = 'Beneficiary deleted successfully.';
        } else {
            $_SESSION['error'] = 'Failed to delete beneficiary.';
        }
        return $this->redirect('admin/beneficiaries');
    }
}
