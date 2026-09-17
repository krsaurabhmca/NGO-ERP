<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\CMS;
use App\Helpers\UploadHelper;
use App\Models\AuditLog;

class CMSController extends Controller
{
    protected $cmsModel;

    public function __construct()
    {
        parent::__construct();
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('auth');
        }
        $this->checkModuleAccess('cms');
        $this->cmsModel = new CMS();
    }

    public function about()
    {
        $page = $this->cmsModel->getPage('about');
        return $this->view('admin/cms/about', [
            'title' => 'Manage About Page',
            'page' => $page
        ]);
    }

    public function gallery()
    {
        $items = $this->cmsModel->getMedia('gallery');
        return $this->view('admin/cms/media_index', [
            'title' => 'Photo Gallery',
            'category' => 'gallery',
            'items' => $items
        ]);
    }

    public function slider()
    {
        $items = $this->cmsModel->getMedia('slider');
        return $this->view('admin/cms/media_index', [
            'title' => 'Home Slider Manager',
            'category' => 'slider',
            'items' => $items
        ]);
    }

    public function certificates()
    {
        $items = $this->cmsModel->getMedia('certificate');
        return $this->view('admin/cms/media_index', [
            'title' => 'Certificates',
            'category' => 'certificate',
            'items' => $items
        ]);
    }

    public function achievements()
    {
        $items = $this->cmsModel->getMedia('achievement');
        return $this->view('admin/cms/media_index', [
            'title' => 'Our Achievements',
            'category' => 'achievement',
            'items' => $items
        ]);
    }

    public function updatePage()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $key = $_POST['page_key'];
            $activeTab = $_POST['active_tab'] ?? '';

            // Get existing page data to merge
            $page = $this->cmsModel->getPage($key);
            if (!$page) {
                $_SESSION['error'] = 'Page not found.';
                return $this->redirect('admin/dashboard');
            }

            // Capture old data for audit
            $oldData = [
                'title' => $page->title,
                'content' => $page->content,
                'mission' => $page->mission,
                'vision' => $page->vision
            ];

            // Merge POST data with existing database values
            $title = $_POST['title'] ?? $page->title;
            $mission = strip_dangerous_html($_POST['mission'] ?? $page->mission);
            $vision = strip_dangerous_html($_POST['vision'] ?? $page->vision);
            $content = strip_dangerous_html($_POST['content'] ?? $page->content);
            $imagePath = $page->image ?? null;

            $values = [
                'integrity_title' => $_POST['integrity_title'] ?? $page->value_integrity_title,
                'integrity_desc' => $_POST['integrity_desc'] ?? $page->value_integrity_desc,
                'integrity_icon' => $_POST['integrity_icon'] ?? $page->value_integrity_icon,
                'integrity_image' => $page->value_integrity_image,
                'compassion_title' => $_POST['compassion_title'] ?? $page->value_compassion_title,
                'compassion_desc' => $_POST['compassion_desc'] ?? $page->value_compassion_desc,
                'compassion_icon' => $_POST['compassion_icon'] ?? $page->value_compassion_icon,
                'compassion_image' => $page->value_compassion_image,
                'innovation_title' => $_POST['innovation_title'] ?? $page->value_innovation_title,
                'innovation_desc' => $_POST['innovation_desc'] ?? $page->value_innovation_desc,
                'innovation_icon' => $_POST['innovation_icon'] ?? $page->value_innovation_icon,
                'innovation_image' => $page->value_innovation_image,
                'collaboration_title' => $_POST['collaboration_title'] ?? $page->value_collaboration_title,
                'collaboration_desc' => $_POST['collaboration_desc'] ?? $page->value_collaboration_desc,
                'collaboration_icon' => $_POST['collaboration_icon'] ?? $page->value_collaboration_icon,
                'collaboration_image' => $page->value_collaboration_image
            ];
            
            // Handle Main Page Banner Upload
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $valid = validate_upload($_FILES['image'], ['jpg', 'jpeg', 'png', 'gif', 'webp'], ['image/jpeg', 'image/png', 'image/gif', 'image/webp'], 500 * 1024);
                if ($valid !== true) {
                    $_SESSION['error'] = 'Banner image: ' . $valid;
                    return $this->redirect('admin/cms/about');
                }
                $uploadDir = UPLOAD_PATH . 'cms/pages/';
                $fileName = \App\Helpers\UploadHelper::processFile($_FILES['image'], $uploadDir);
                if ($fileName) {
                    $imagePath = 'uploads/cms/pages/' . $fileName;
                }
            }

            // Handle Core Values Images Upload
            $valueKeys = ['integrity', 'compassion', 'innovation', 'collaboration'];
            foreach ($valueKeys as $vk) {
                $fileKey = 'value_' . $vk . '_image';
                if (isset($_FILES[$fileKey]) && $_FILES[$fileKey]['error'] === UPLOAD_ERR_OK) {
                    $valid = validate_upload($_FILES[$fileKey], ['jpg', 'jpeg', 'png', 'gif', 'webp'], ['image/jpeg', 'image/png', 'image/gif', 'image/webp'], 500 * 1024);
                    if ($valid !== true) {
                        $_SESSION['error'] = 'Value image "' . ucfirst($vk) . '": ' . $valid;
                        continue;
                    }
                    $uploadDir = UPLOAD_PATH . 'cms/values/';
                    $fileName = \App\Helpers\UploadHelper::processFile($_FILES[$fileKey], $uploadDir);
                    if ($fileName) {
                        $values[$vk . '_image'] = 'uploads/cms/values/' . $fileName;
                        AuditLog::log('update', 'cms_value', 0, null, ['image' => 'updated', 'key' => $vk]);
                    }
                }
            }

            if ($this->cmsModel->updatePage($key, $title, $imagePath, $mission, $vision, $content, $values)) {
                // Audit log
                AuditLog::log('update', 'cms_pages', 0, $oldData, [
                    'title' => $title,
                    'content' => $content,
                    'mission' => $mission,
                    'vision' => $vision,
                    'page_key' => $key
                ]);
                $_SESSION['success'] = 'Section updated successfully.';
            } else {
                $_SESSION['error'] = 'Failed to update section.';
            }

            // Redirect back to the same tab
            $redirectUrl = url('admin/cms/about');
            if ($activeTab) {
                $redirectUrl .= '#' . $activeTab;
            }
            header("Location: " . $redirectUrl);
            exit;
        }
        $referer = $_SERVER['HTTP_REFERER'] ?? url('admin/dashboard');
        header("Location: " . $referer);
        exit;
    }

    public function storeMedia()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $category = $_POST['category'];
            $title = $_POST['title'] ?? '';
            $desc = $_POST['description'] ?? '';
            
            $uploadDir = UPLOAD_PATH . 'cms/' . $category . '/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                $valid = validate_upload($_FILES['file'], ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'doc', 'docx'], ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'], 5 * 1024 * 1024);
                if ($valid !== true) {
                    $_SESSION['error'] = 'File: ' . $valid;
                    $referer = $_SERVER['HTTP_REFERER'] ?? url('admin/dashboard');
                    header("Location: " . $referer);
                    exit;
                }
                $fileName = \App\Helpers\UploadHelper::processFile($_FILES['file'], $uploadDir);
                if ($fileName) {
                    $filePath = 'uploads/cms/' . $category . '/' . $fileName;
                    if ($this->cmsModel->addMedia($category, $title, $desc, $filePath)) {
                        $insertId = $this->cmsModel->lastInsertId();
                        AuditLog::log('create', 'cms_media', $insertId, null, ['file' => $fileName]);
                        $_SESSION['success'] = 'Media added successfully.';
                    }
                } else {
                    $_SESSION['error'] = 'Failed to process file.';
                }
            }
        }
        $referer = $_SERVER['HTTP_REFERER'] ?? url('admin/dashboard');
        header("Location: " . $referer);
        exit;
    }

    public function deleteMedia($id)
    {
        $item = $this->cmsModel->getMediaItem($id);
        if ($item) {
            // Delete file — validate path with realpath()
            $filePath = realpath(PUBLIC_PATH . $item->file_path);
            $allowedPath = realpath(UPLOAD_PATH);
            if ($filePath !== false && $allowedPath !== false && strpos($filePath, $allowedPath) === 0 && file_exists($filePath)) {
                unlink($filePath);
            }
            
            $oldData = [
                'title' => $item->title,
                'category' => $item->category,
                'file_path' => $item->file_path
            ];
            
            if ($this->cmsModel->deleteMedia($id)) {
                // Audit log
                AuditLog::log('delete', 'cms_media', $id, $oldData, null);
                $_SESSION['success'] = 'Item deleted successfully.';
            } else {
                $_SESSION['error'] = 'Failed to delete item.';
            }
        }
        $referer = $_SERVER['HTTP_REFERER'] ?? url('admin/dashboard');
        header("Location: " . $referer);
        exit;
    }

    public function toggleMediaStatus($id)
    {
        $item = $this->cmsModel->getMediaItem($id);
        if ($this->cmsModel->toggleStatus($id)) {
            // Audit log
            AuditLog::log(
                'status_change',
                'cms_media',
                $id,
                ['title' => $item->title ?? '', 'category' => $item->category ?? '', 'status' => $item->status ?? ''],
                ['title' => $item->title ?? '', 'category' => $item->category ?? '', 'status' => 'toggled']
            );
            $_SESSION['success'] = 'Status updated successfully.';
        } else {
            $_SESSION['error'] = 'Failed to update status.';
        }
        $referer = $_SERVER['HTTP_REFERER'] ?? url('admin/dashboard');
        header("Location: " . $referer);
        exit;
    }

    public function policies()
    {
        $settingModel = new \App\Models\Setting();
        $settings = [];
        foreach ($settingModel->all() as $s) {
            $settings[$s->key_name] = $s->key_value;
        }

        return $this->view('admin/cms/policies', [
            'title' => 'Manage Policies',
            'settings' => $settings
        ]);
    }

    public function updatePolicies()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $activeTab = $_POST['active_tab'] ?? '';
            $settingModel = new \App\Models\Setting();
            
            // Capture old policy values for audit
            $oldPolicies = [];
            foreach ($settingModel->all() as $s) {
                if (in_array($s->key_name, ['policy_privacy', 'policy_terms', 'policy_refund'])) {
                    $oldPolicies[$s->key_name] = $s->key_value;
                }
            }
            
            if (isset($_POST['policy_privacy'])) {
                $settingModel->updateByKey('policy_privacy', strip_dangerous_html($_POST['policy_privacy']), 'policies');
            }
            if (isset($_POST['policy_terms'])) {
                $settingModel->updateByKey('policy_terms', strip_dangerous_html($_POST['policy_terms']), 'policies');
            }
            if (isset($_POST['policy_refund'])) {
                $settingModel->updateByKey('policy_refund', strip_dangerous_html($_POST['policy_refund']), 'policies');
            }
            
            // Audit log
            AuditLog::log('update', 'policies', 0, $oldPolicies, [
                'policy_privacy' => isset($_POST['policy_privacy']) ? $_POST['policy_privacy'] : ($oldPolicies['policy_privacy'] ?? ''),
                'policy_terms' => isset($_POST['policy_terms']) ? $_POST['policy_terms'] : ($oldPolicies['policy_terms'] ?? ''),
                'policy_refund' => isset($_POST['policy_refund']) ? $_POST['policy_refund'] : ($oldPolicies['policy_refund'] ?? '')
            ]);
            
            $_SESSION['success'] = 'Policies updated successfully.';

            if ($activeTab) {
                header("Location: " . url('admin/cms/policies') . '#' . $activeTab);
                exit;
            }
        }
        return $this->redirect('admin/cms/policies');
    }
}
