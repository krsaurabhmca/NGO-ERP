<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Project;
use App\Models\AuditLog;

class ProjectController extends Controller
{
    protected $projectModel;

    public function __construct()
    {
        parent::__construct();
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('auth');
        }
        $this->checkModuleAccess('projects');
        $this->projectModel = new Project();
    }

    public function index()
    {
        $projects = $this->projectModel->all();
        return $this->view('admin/projects/index', [
            'title' => 'Manage Projects',
            'projects' => $projects
        ]);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'] ?? '';
            $description = $_POST['description'] ?? '';
            $videoUrl = $_POST['video_url'] ?? '';
            $status = $_POST['status'] ?? 'ongoing';
            $startDate = !empty($_POST['start_date']) ? $_POST['start_date'] : null;
            $endDate = !empty($_POST['end_date']) ? $_POST['end_date'] : null;
            
            $imagePath = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $valid = validate_upload($_FILES['image'], ['jpg', 'jpeg', 'png', 'gif', 'webp'], ['image/jpeg', 'image/png', 'image/gif', 'image/webp'], 500 * 1024);
                if ($valid !== true) {
                    $_SESSION['error'] = 'Featured image: ' . $valid;
                    return $this->redirect('admin/projects');
                }
                $uploadDir = UPLOAD_PATH . 'projects/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                $fileName = time() . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $fileName)) {
                    $imagePath = 'uploads/projects/' . $fileName;
                }
            }
            if (isset($_FILES['gallery'])) {
                $galleryFiles = $_FILES['gallery'];
                if (!empty($galleryFiles['name'][0])) {
                    foreach ($galleryFiles['tmp_name'] as $key => $tmpName) {
                        if ($galleryFiles['error'][$key] !== UPLOAD_ERR_OK) continue;
                        $singleFile = [
                            'name' => $galleryFiles['name'][$key],
                            'tmp_name' => $galleryFiles['tmp_name'][$key],
                            'size' => $galleryFiles['size'][$key],
                            'error' => $galleryFiles['error'][$key]
                        ];
                        $valid = validate_upload($singleFile, ['jpg', 'jpeg', 'png', 'gif', 'webp'], ['image/jpeg', 'image/png', 'image/gif', 'image/webp'], 500 * 1024);
                        if ($valid !== true) {
                            $_SESSION['error'] = 'Gallery image "' . $galleryFiles['name'][$key] . '": ' . $valid;
                            return $this->redirect('admin/projects');
                        }
                    }
                }
            }

            $data = [
                'title' => $title,
                'slug' => $this->projectModel->createSlug($title),
                'description' => $description,
                'image' => $imagePath,
                'video_url' => $videoUrl,
                'status' => $status,
                'start_date' => $startDate,
                'end_date' => $endDate
            ];

            $success = $this->projectModel->create($data);
            if ($success) {
                $lastId = $this->projectModel->lastInsertId();
                AuditLog::log('create', 'project', $lastId, null, $data);

                // Handle Gallery Images
                if (isset($_FILES['gallery']) && !empty($_FILES['gallery']['name'][0])) {
                    $uploadDir = UPLOAD_PATH . 'projects/gallery/';
                    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

                    foreach ($_FILES['gallery']['tmp_name'] as $key => $tmpName) {
                        if ($_FILES['gallery']['error'][$key] === UPLOAD_ERR_OK) {
                            $singleFile = [
                                'name' => $_FILES['gallery']['name'][$key],
                                'tmp_name' => $_FILES['gallery']['tmp_name'][$key],
                                'size' => $_FILES['gallery']['size'][$key],
                                'error' => $_FILES['gallery']['error'][$key]
                            ];
                            $valid = validate_upload($singleFile, ['jpg', 'jpeg', 'png', 'gif', 'webp'], ['image/jpeg', 'image/png', 'image/gif', 'image/webp'], 500 * 1024);
                            if ($valid !== true) continue;
                            $ext = strtolower(pathinfo($_FILES['gallery']['name'][$key], PATHINFO_EXTENSION));
                            $fileName = time() . '_' . $key . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
                            if (move_uploaded_file($tmpName, $uploadDir . $fileName)) {
                                $this->projectModel->addGalleryImage($lastId, 'uploads/projects/gallery/' . $fileName);
                                AuditLog::log('create', 'project_gallery', $lastId, null, ['image' => 'uploads/projects/gallery/' . $fileName]);
                            }
                        }
                    }
                }

                $_SESSION['success'] = 'Project created successfully.';
            } else {
                $_SESSION['error'] = 'Failed to create project.';
            }
        }
        return $this->redirect('admin/projects');
    }

    public function edit($id)
    {
        $project = $this->projectModel->find($id);
        if (!$project) {
            $_SESSION['error'] = 'Project not found.';
            return $this->redirect('admin/projects');
        }

        $gallery = $this->projectModel->getGallery($id);
        json_response([
            'status' => 'success', 
            'data' => $project,
            'gallery' => $gallery
        ]);
        exit;
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $project = $this->projectModel->find($id);
            if (!$project) {
                $_SESSION['error'] = 'Project not found.';
                return $this->redirect('admin/projects');
            }

            // Capture old data for audit
            $oldData = [
                'title' => $project->title,
                'description' => $project->description,
                'status' => $project->status,
                'video_url' => $project->video_url,
                'start_date' => $project->start_date,
                'end_date' => $project->end_date
            ];

            $title = $_POST['title'] ?? '';
            $description = $_POST['description'] ?? '';
            $videoUrl = $_POST['video_url'] ?? '';
            $status = $_POST['status'] ?? 'ongoing';
            $startDate = !empty($_POST['start_date']) ? $_POST['start_date'] : null;
            $endDate = !empty($_POST['end_date']) ? $_POST['end_date'] : null;
            
            $imagePath = $project->image;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $valid = validate_upload($_FILES['image'], ['jpg', 'jpeg', 'png', 'gif', 'webp'], ['image/jpeg', 'image/png', 'image/gif', 'image/webp'], 500 * 1024);
                if ($valid !== true) {
                    $_SESSION['error'] = 'Featured image: ' . $valid;
                    return $this->redirect('admin/projects');
                }
                $uploadDir = UPLOAD_PATH . 'projects/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                $fileName = time() . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $fileName)) {
                    safe_unlink($imagePath);
                    $imagePath = 'uploads/projects/' . $fileName;
                }
            }
            if (isset($_FILES['gallery'])) {
                $galleryFiles = $_FILES['gallery'];
                if (!empty($galleryFiles['name'][0])) {
                    foreach ($galleryFiles['tmp_name'] as $key => $tmpName) {
                        if ($galleryFiles['error'][$key] !== UPLOAD_ERR_OK) continue;
                        $singleFile = [
                            'name' => $galleryFiles['name'][$key],
                            'tmp_name' => $galleryFiles['tmp_name'][$key],
                            'size' => $galleryFiles['size'][$key],
                            'error' => $galleryFiles['error'][$key]
                        ];
                        $valid = validate_upload($singleFile, ['jpg', 'jpeg', 'png', 'gif', 'webp'], ['image/jpeg', 'image/png', 'image/gif', 'image/webp'], 500 * 1024);
                        if ($valid !== true) {
                            $_SESSION['error'] = 'Gallery image "' . $galleryFiles['name'][$key] . '": ' . $valid;
                            return $this->redirect('admin/projects');
                        }
                    }
                }
            }

            $data = [
                'title' => $title,
                'description' => $description,
                'image' => $imagePath,
                'video_url' => $videoUrl,
                'status' => $status,
                'start_date' => $startDate,
                'end_date' => $endDate
            ];

            if ($title !== $project->title) {
                $data['slug'] = $this->projectModel->createSlug($title);
            }

            if ($this->projectModel->update($id, $data)) {
                // Handle New Gallery Images
                if (isset($_FILES['gallery']) && !empty($_FILES['gallery']['name'][0])) {
                    $uploadDir = UPLOAD_PATH . 'projects/gallery/';
                    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

                    foreach ($_FILES['gallery']['tmp_name'] as $key => $tmpName) {
                        if ($_FILES['gallery']['error'][$key] === UPLOAD_ERR_OK) {
                            $singleFile = [
                                'name' => $_FILES['gallery']['name'][$key],
                                'tmp_name' => $_FILES['gallery']['tmp_name'][$key],
                                'size' => $_FILES['gallery']['size'][$key],
                                'error' => $_FILES['gallery']['error'][$key]
                            ];
                            $valid = validate_upload($singleFile, ['jpg', 'jpeg', 'png', 'gif', 'webp'], ['image/jpeg', 'image/png', 'image/gif', 'image/webp'], 500 * 1024);
                            if ($valid !== true) continue;
                            $ext = strtolower(pathinfo($_FILES['gallery']['name'][$key], PATHINFO_EXTENSION));
                            $fileName = time() . '_' . $key . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
                            if (move_uploaded_file($tmpName, $uploadDir . $fileName)) {
                                $this->projectModel->addGalleryImage($id, 'uploads/projects/gallery/' . $fileName);
                                AuditLog::log('create', 'project_gallery', $id, null, ['image' => 'uploads/projects/gallery/' . $fileName]);
                            }
                        }
                    }
                }

                // Audit log
                AuditLog::log('update', 'projects', $id, $oldData, $data);

                $_SESSION['success'] = 'Project updated successfully.';
            } else {
                $_SESSION['error'] = 'Failed to update project.';
            }
        }
        return $this->redirect('admin/projects');
    }

    public function deleteGalleryImage($id)
    {
        $image = $this->projectModel->getGalleryImage($id);
        if ($this->projectModel->deleteGalleryImage($id)) {
            AuditLog::log('delete', 'project_gallery', $id, $image ? (array) $image : null, null);
            json_response(['status' => 'success']);
        } else {
            json_response(['status' => 'error', 'message' => 'Failed to delete image']);
        }
        exit;
    }

    public function delete($id)
    {
        $project = $this->projectModel->find($id);
        if ($project) {
            $oldData = [
                'title' => $project->title,
                'description' => $project->description,
                'status' => $project->status,
                'video_url' => $project->video_url,
                'start_date' => $project->start_date,
                'end_date' => $project->end_date
            ];

            safe_unlink($project->image);
            if ($this->projectModel->delete($id)) {
                // Audit log
                AuditLog::log('delete', 'projects', $id, $oldData, null);
                $_SESSION['success'] = 'Project deleted successfully.';
            } else {
                $_SESSION['error'] = 'Failed to delete project.';
            }
        }
        return $this->redirect('admin/projects');
    }
}
