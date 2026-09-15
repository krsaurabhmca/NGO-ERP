<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Donor;
use App\Models\AuditLog;

class DonorController extends Controller
{
    protected $donorModel;

    public function __construct()
    {
        parent::__construct();
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('auth');
        }
        $this->checkModuleAccess('stakeholders');
        $this->donorModel = new Donor();
    }

    public function index()
    {
        $search = $_GET['search'] ?? null;
        if ($search) {
            $donors = $this->donorModel->search(['name', 'email', 'phone', 'donor_id'], $search);
        } else {
            $donors = $this->donorModel->all();
        }
        return $this->view('admin/donors/index', [
            'title' => 'Manage Donors',
            'donors' => $donors
        ]);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'donor_id' => $this->donorModel->generateDonorId($this->globalSettings['id_prefix_donor'] ?? 'DNR'),
                'name' => $_POST['name'] ?? '',
                'email' => $_POST['email'] ?? '',
                'phone' => $_POST['phone'] ?? '',
                'address' => $_POST['address'] ?? '',
                'donor_type' => $_POST['donor_type'] ?? 'individual',
                'status' => $_POST['status'] ?? 'active'
            ];

            if ($this->donorModel->create($data)) {
                AuditLog::log('create', 'donor', $this->donorModel->lastInsertId(), null, $data);
                $_SESSION['success'] = 'Donor added successfully.';
            } else {
                $_SESSION['error'] = 'Failed to add donor.';
            }
        }
        return $this->redirect('admin/donors');
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $donor = $this->donorModel->findByUuid($id);
            if (!$donor) {
                $donor = $this->donorModel->find($id);
            }
            if ($donor) {
                $data = [
                    'name' => $_POST['name'] ?? '',
                    'email' => $_POST['email'] ?? '',
                    'phone' => $_POST['phone'] ?? '',
                    'address' => $_POST['address'] ?? '',
                    'donor_type' => $_POST['donor_type'] ?? 'individual',
                    'status' => $_POST['status'] ?? 'active'
                ];

                if ($this->donorModel->update($donor->id, $data)) {
                    AuditLog::log('update', 'donor', $donor->id, (array)$donor, $data);
                    $_SESSION['success'] = 'Donor updated successfully.';
                } else {
                    $_SESSION['error'] = 'Failed to update donor.';
                }
            } else {
                $_SESSION['error'] = 'Donor not found.';
            }
        }
        return $this->redirect('admin/donors');
    }

    public function delete($id)
    {
        $donor = $this->donorModel->find($id);
        if ($this->donorModel->delete($id)) {
            // Audit log
            AuditLog::log('delete', 'donors', $id, $donor ? (array) $donor : null, null);
            $_SESSION['success'] = 'Donor deleted successfully.';
        } else {
            $_SESSION['error'] = 'Failed to delete donor.';
        }
        return $this->redirect('admin/donors');
    }
}
