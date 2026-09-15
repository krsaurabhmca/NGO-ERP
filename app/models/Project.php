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
        $stmt = $this->db->prepare("SELECT * FROM project_gallery WHERE project_id = :project_id");
        $stmt->execute([':project_id' => $projectId]);
        return $stmt->fetchAll();
    }

    public function addGalleryImage($projectId, $imagePath)
    {
        $stmt = $this->db->prepare("INSERT INTO project_gallery (project_id, image_path) VALUES (:project_id, :image_path)");
        return $stmt->execute([
            ':project_id' => $projectId,
            ':image_path' => $imagePath
        ]);
    }

    public function getGalleryImage($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM project_gallery WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function deleteGalleryImage($id)
    {
        // Get file path first
        $stmt = $this->db->prepare("SELECT image_path FROM project_gallery WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $image = $stmt->fetch();
        
        if ($image && file_exists($image->image_path)) {
            unlink($image->image_path);
        }

        $stmt = $this->db->prepare("DELETE FROM project_gallery WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
