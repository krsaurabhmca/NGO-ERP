<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\News;
use App\Helpers\UploadHelper;
use App\Models\AuditLog;

class NewsController extends Controller
{
    protected $newsModel;

    public function __construct()
    {
        parent::__construct();
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('auth');
        }
        $this->checkModuleAccess('news');
        $this->newsModel = new News();
    }

    public function index()
    {
        $news = $this->newsModel->all();
        return $this->view('admin/news/index', [
            'title' => 'Manage News',
            'news' => $news
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
                    $_SESSION['error'] = 'News image: ' . $valid;
                    return $this->redirect('admin/news');
                }
                $uploadDir = UPLOAD_PATH . 'news/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                $fileName = time() . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $fileName)) {
                    $imagePath = 'uploads/news/' . $fileName;
                }
            }

            $data = [
                'title' => $title,
                'slug' => $this->newsModel->createSlug($title),
                'content' => $content,
                'image' => $imagePath,
                'status' => $status
            ];

            if ($this->newsModel->create($data)) {
                AuditLog::log('create', 'news', $this->newsModel->lastInsertId(), null, $data);
                $_SESSION['success'] = 'News article created successfully.';
            } else {
                $_SESSION['error'] = 'Failed to create news article.';
            }
        }
        return $this->redirect('admin/news');
    }

    public function edit($id)
    {
        $news = $this->newsModel->find($id);
        if (!$news) {
            $_SESSION['error'] = 'News not found.';
            return $this->redirect('admin/news');
        }

        json_response(['status' => 'success', 'data' => $news]);
        exit;
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $news = $this->newsModel->find($id);
            if (!$news) {
                $_SESSION['error'] = 'News not found.';
                return $this->redirect('admin/news');
            }

            // Capture old data for audit
            $oldData = [
                'title' => $news->title,
                'content' => $news->content,
                'status' => $news->status
            ];

            $title = $_POST['title'] ?? '';
            $content = strip_dangerous_html($_POST['content'] ?? '');
            $status = $_POST['status'] ?? 'active';
            
            $imagePath = $news->image;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $valid = validate_upload($_FILES['image'], ['jpg', 'jpeg', 'png', 'gif', 'webp'], ['image/jpeg', 'image/png', 'image/gif', 'image/webp'], 500 * 1024);
                if ($valid !== true) {
                    $_SESSION['error'] = 'News image: ' . $valid;
                    return $this->redirect('admin/news');
                }
                $uploadDir = UPLOAD_PATH . 'news/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                $fileName = time() . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $fileName)) {
                    safe_unlink($imagePath);
                    $imagePath = 'uploads/news/' . $fileName;
                }
            }

            $data = [
                'title' => $title,
                'content' => $content,
                'image' => $imagePath,
                'status' => $status
            ];

            // Only update slug if title changed
            if ($title !== $news->title) {
                $data['slug'] = $this->newsModel->createSlug($title);
            }

            if ($this->newsModel->update($id, $data)) {
                // Audit log
                AuditLog::log('update', 'news', $id, $oldData, $data);
                $_SESSION['success'] = 'News article updated successfully.';
            } else {
                $_SESSION['error'] = 'Failed to update news article.';
            }
        }
        return $this->redirect('admin/news');
    }

    public function delete($id)
    {
        $news = $this->newsModel->find($id);
        if ($news) {
            $oldData = [
                'title' => $news->title,
                'content' => $news->content,
                'status' => $news->status
            ];

            safe_unlink($news->image);
            if ($this->newsModel->delete($id)) {
                // Audit log
                AuditLog::log('delete', 'news', $id, $oldData, null);
                $_SESSION['success'] = 'News deleted successfully.';
            } else {
                $_SESSION['error'] = 'Failed to delete news.';
            }
        }
        return $this->redirect('admin/news');
    }
}
