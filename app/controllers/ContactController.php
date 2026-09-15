<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Contact;
use App\Models\AuditLog;

class ContactController extends Controller
{
    protected $contactModel;

    public function __construct()
    {
        parent::__construct();
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('auth');
        }
        $this->checkModuleAccess('contacts');
        $this->contactModel = new Contact();
    }

    public function index()
    {
        $inquiries = $this->contactModel->all();
        return $this->view('admin/contacts/index', [
            'title' => 'Contact Inquiries',
            'inquiries' => $inquiries
        ]);
    }

    public function show($id)
    {
        $inquiry = $this->contactModel->find($id);
        if (!$inquiry) {
            $_SESSION['error'] = 'Inquiry not found.';
            return $this->redirect('admin/contacts');
        }

        // Mark as read if unread
        if ($inquiry->status === 'unread') {
            $this->contactModel->markAsRead($inquiry->id);
            AuditLog::log('update', 'contact', $inquiry->id, ['status' => 'unread'], ['status' => 'read']);
        }

        json_response(['status' => 'success', 'data' => $inquiry]);
        exit;
    }

    public function delete($id)
    {
        $inquiry = $this->contactModel->find($id);
        if ($this->contactModel->delete($id)) {
            // Audit log
            AuditLog::log('delete', 'contacts', $inquiry ? $inquiry->id : null, $inquiry ? (array) $inquiry : null, null);
            $_SESSION['success'] = 'Inquiry deleted successfully.';
        } else {
            $_SESSION['error'] = 'Failed to delete inquiry.';
        }
        return $this->redirect('admin/contacts');
    }
}
