<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Designation;
use App\Models\AuditLog;

class DesignationController extends Controller
{
    protected $designationModel;

    public function __construct()
    {
        parent::__construct();
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('auth');
        }
        $this->checkModuleAccess('members');
        $this->designationModel = new Designation();
    }

    public function index()
    {
        $designations = $this->designationModel->all();
        return $this->view('admin/designations/index', [
            'title' => 'Manage Designations',
            'designations' => $designations
        ]);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'] ?? '',
                'monthly_amount' => $_POST['monthly_amount'] ?? 0,
                'show_in_form' => isset($_POST['show_in_form']) ? 1 : 0
            ];

            if ($this->designationModel->create($data)) {
                AuditLog::log('create', 'designation', $this->designationModel->lastInsertId(), null, $data);
                $_SESSION['success'] = 'Designation added successfully.';
            } else {
                $_SESSION['error'] = 'Failed to add designation.';
            }
        }
        return $this->redirect('admin/designations');
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $designation = $this->designationModel->find($id);
            $oldData = (array) $designation;
            
            $data = [
                'name' => $_POST['name'] ?? '',
                'monthly_amount' => $_POST['monthly_amount'] ?? 0,
                'show_in_form' => isset($_POST['show_in_form']) ? 1 : 0
            ];

            if ($this->designationModel->update($id, $data)) {
                // Audit log
                AuditLog::log('update', 'designations', $id, $oldData, $data);
                $_SESSION['success'] = 'Designation updated successfully.';
            } else {
                $_SESSION['error'] = 'Failed to update designation.';
            }
        }
        return $this->redirect('admin/designations');
    }

    public function toggleStatus($id)
    {
        $designation = $this->designationModel->find($id);
        if ($designation) {
            $newStatus = $designation->show_in_form ? 0 : 1;
            
            // Audit log
            AuditLog::log(
                'status_change',
                'designations',
                $id,
                ['name' => $designation->name, 'show_in_form' => $designation->show_in_form],
                ['name' => $designation->name, 'show_in_form' => $newStatus]
            );
            
            if ($this->designationModel->update($id, ['show_in_form' => $newStatus])) {
                $_SESSION['success'] = 'Designation status updated successfully.';
            } else {
                $_SESSION['error'] = 'Failed to update designation status.';
            }
        }
        return $this->redirect('admin/designations');
    }

    public function delete($id)
    {
        $designation = $this->designationModel->find($id);
        if ($this->designationModel->delete($id)) {
            // Audit log
            AuditLog::log('delete', 'designations', $id, $designation ? (array) $designation : null, null);
            $_SESSION['success'] = 'Designation deleted successfully.';
        } else {
            $_SESSION['error'] = 'Failed to delete designation.';
        }
        return $this->redirect('admin/designations');
    }
}
