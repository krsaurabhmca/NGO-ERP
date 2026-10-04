<?php

namespace App\Models;

use App\Core\Model;

class Project extends Model
{
    protected $table = 'projects';

    public function createSlug($title)
    {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
        
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$this->table} WHERE slug = :slug");
        $stmt->execute([':slug' => $slug]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            $slug = $slug . '-' . time();
        }

        return $slug;
    }

    public function getActiveProjects()
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE status != 'planned' ORDER BY start_date DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getGallery($projectId)
    {
        if (is_string($projectId) && strlen($projectId) === 36 && preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $projectId)) {
            $project = $this->findByUuid($projectId);
            if (!$project) return [];
            $projectId = $project->id;
        }
        $stmt = $this->db->prepare("SELECT * FROM project_gallery WHERE project_id = :project_id ORDER BY id ASC");
        $stmt->execute([':project_id' => $projectId]);
        return $stmt->fetchAll();
    }

    public function addGalleryImage($projectId, $imagePath)
    {
        if (is_string($projectId) && strlen($projectId) === 36 && preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $projectId)) {
            $project = $this->findByUuid($projectId);
            if (!$project) return false;
            $projectId = $project->id;
        }
        $uuid = self::uuid();
        $stmt = $this->db->prepare("INSERT INTO project_gallery (uuid, project_id, image_path) VALUES (:uuid, :project_id, :image_path)");
        return $stmt->execute([
            ':uuid' => $uuid,
            ':project_id' => $projectId,
            ':image_path' => $imagePath
        ]);
    }

    public function getGalleryImage($id)
    {
        if (is_string($id) && strlen($id) === 36 && preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $id)) {
            $stmt = $this->db->prepare("SELECT * FROM project_gallery WHERE uuid = :id");
            $stmt->execute([':id' => $id]);
        } else {
            $stmt = $this->db->prepare("SELECT * FROM project_gallery WHERE id = :id");
            $stmt->execute([':id' => $id]);
        }
        return $stmt->fetch();
    }

    public function deleteGalleryImage($id)
    {
        $image = $this->getGalleryImage($id);
        if ($image && !empty($image->image_path)) {
            safe_unlink($image->image_path);
        }

        if (is_string($id) && strlen($id) === 36 && preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $id)) {
            $stmt = $this->db->prepare("DELETE FROM project_gallery WHERE uuid = :id");
            return $stmt->execute([':id' => $id]);
        }
        $stmt = $this->db->prepare("DELETE FROM project_gallery WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
