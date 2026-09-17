<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Notice;
use App\Helpers\UploadHelper;
use App\Models\AuditLog;

class NoticeController extends Controller
{
    protected $noticeModel;

    public function __construct()
    {
        parent::__construct();
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('auth');
        }
        $this->checkModuleAccess('notices');
        $this->noticeModel = new Notice();
    }

    public function index()
    {
        $notices = $this->noticeModel->all();
        return $this->view('admin/notices/index', [
            'title' => 'Manage Notices',
            'notices' => $notices
        ]);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'] ?? '';
            $content = strip_dangerous_html($_POST['content'] ?? '');
            $status = $_POST['status'] ?? 'active';
            
            $imagePath = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $valid = validate_upload($_FILES['image'], ['jpg', 'jpeg', 'png', 'gif', 'webp'], ['image/jpeg', 'image/png', 'image/gif', 'image/webp'], 500 * 1024);
                if ($valid !== true) {
                    $_SESSION['error'] = 'Notice image: ' . $valid;
                    return $this->redirect('admin/notices');
                }
                $uploadDir = UPLOAD_PATH . 'notices/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                $fileName = time() . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $fileName)) {
                    $imagePath = 'uploads/notices/' . $fileName;
                }
            }

            $data = [
                'title' => $title,
                'content' => $content,
                'image' => $imagePath,
                'status' => $status
            ];

            if ($this->noticeModel->create($data)) {
                AuditLog::log('create', 'notice', $this->noticeModel->lastInsertId(), null, $data);
                $_SESSION['success'] = 'Notice created successfully.';
            } else {
                $_SESSION['error'] = 'Failed to create notice.';
            }
        }
        return $this->redirect('admin/notices');
    }

    public function edit($id)
    {
        $notice = $this->noticeModel->find($id);
        if (!$notice) {
            $_SESSION['error'] = 'Notice not found.';
            return $this->redirect('admin/notices');
        }

        json_response(['status' => 'success', 'data' => $notice]);
        exit;
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $notice = $this->noticeModel->find($id);
            if (!$notice) {
                $_SESSION['error'] = 'Notice not found.';
                return $this->redirect('admin/notices');
            }

            // Capture old data for audit
            $oldData = [
                'title' => $notice->title,
                'content' => $notice->content,
                'status' => $notice->status
            ];

            $title = $_POST['title'] ?? '';
            $content = strip_dangerous_html($_POST['content'] ?? '');
            $status = $_POST['status'] ?? 'active';
            
            $imagePath = $notice->image;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $valid = validate_upload($_FILES['image'], ['jpg', 'jpeg', 'png', 'gif', 'webp'], ['image/jpeg', 'image/png', 'image/gif', 'image/webp'], 500 * 1024);
                if ($valid !== true) {
                    $_SESSION['error'] = 'Notice image: ' . $valid;
                    return $this->redirect('admin/notices');
                }
                $uploadDir = UPLOAD_PATH . 'notices/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                $fileName = time() . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $fileName)) {
                    safe_unlink($imagePath);
                    $imagePath = 'uploads/notices/' . $fileName;
                }
            }

            $data = [
                'title' => $title,
                'content' => $content,
                'image' => $imagePath,
                'status' => $status
            ];

            if ($this->noticeModel->update($id, $data)) {
                // Audit log
                AuditLog::log('update', 'notices', $id, $oldData, $data);
                $_SESSION['success'] = 'Notice updated successfully.';
            } else {
                $_SESSION['error'] = 'Failed to update notice.';
            }
        }
        return $this->redirect('admin/notices');
    }

    public function delete($id)
    {
        $notice = $this->noticeModel->find($id);
        if ($notice) {
            $oldData = [
                'title' => $notice->title,
                'content' => $notice->content,
                'status' => $notice->status
            ];

            safe_unlink($notice->image);
            if ($this->noticeModel->delete($id)) {
                // Audit log
                AuditLog::log('delete', 'notices', $id, $oldData, null);
                $_SESSION['success'] = 'Notice deleted successfully.';
            } else {
                $_SESSION['error'] = 'Failed to delete notice.';
            }
        }
        return $this->redirect('admin/notices');
    }
}
