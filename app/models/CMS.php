<?php

namespace App\Models;

use App\Core\Model;

class CMS extends Model
{
    // Handle Page Content
    public function getPage($key)
    {
        $stmt = $this->db->prepare("SELECT * FROM cms_pages WHERE page_key = :key");
        $stmt->execute([':key' => $key]);
        return $stmt->fetch();
    }

    public function updatePage($key, $title, $image, $mission, $vision, $content, $values = [])
    {
        $sql = "UPDATE cms_pages SET 
                title = :title, 
                image = :image, 
                mission = :mission, 
                vision = :vision, 
                content = :content,
                value_integrity_title = :v_i_t, value_integrity_desc = :v_i_d, value_integrity_icon = :v_i_icon, value_integrity_image = :v_i_img,
                value_compassion_title = :v_cp_t, value_compassion_desc = :v_cp_d, value_compassion_icon = :v_cp_icon, value_compassion_image = :v_cp_img,
                value_innovation_title = :v_in_t, value_innovation_desc = :v_in_d, value_innovation_icon = :v_in_icon, value_innovation_image = :v_in_img,
                value_collaboration_title = :v_cl_t, value_collaboration_desc = :v_cl_d, value_collaboration_icon = :v_cl_icon, value_collaboration_image = :v_cl_img
                WHERE page_key = :key";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':key' => $key, 
            ':title' => $title, 
            ':image' => $image,
            ':mission' => $mission, 
            ':vision' => $vision, 
            ':content' => $content,
            ':v_i_t' => $values['integrity_title'] ?? 'Integrity',
            ':v_i_d' => $values['integrity_desc'] ?? '',
            ':v_i_icon' => $values['integrity_icon'] ?? 'fas fa-shield-alt',
            ':v_i_img' => $values['integrity_image'] ?? null,
            ':v_cp_t' => $values['compassion_title'] ?? 'Compassion',
            ':v_cp_d' => $values['compassion_desc'] ?? '',
            ':v_cp_icon' => $values['compassion_icon'] ?? 'fas fa-heart',
            ':v_cp_img' => $values['compassion_image'] ?? null,
            ':v_in_t' => $values['innovation_title'] ?? 'Innovation',
            ':v_in_d' => $values['innovation_desc'] ?? '',
            ':v_in_icon' => $values['innovation_icon'] ?? 'fas fa-lightbulb',
            ':v_in_img' => $values['innovation_image'] ?? null,
            ':v_cl_t' => $values['collaboration_title'] ?? 'Collaboration',
            ':v_cl_d' => $values['collaboration_desc'] ?? '',
            ':v_cl_icon' => $values['collaboration_icon'] ?? 'fas fa-users',
            ':v_cl_img' => $values['collaboration_image'] ?? null
        ]);
    }

    // Handle Media (Gallery/Certificates/Achievements)
    public function getMedia($category)
    {
        $stmt = $this->db->prepare("SELECT * FROM cms_media WHERE category = :category ORDER BY sort_order ASC, created_at DESC");
        $stmt->execute([':category' => $category]);
        return $stmt->fetchAll();
    }

    public function addMedia($category, $title, $description, $file_path)
    {
        $uuid = self::uuid();
        $stmt = $this->db->prepare("INSERT INTO cms_media (uuid, category, title, description, file_path) VALUES (:uuid, :category, :title, :description, :file_path)");
        return $stmt->execute([
            ':uuid' => $uuid,
            ':category' => $category,
            ':title' => $title,
            ':description' => $description,
            ':file_path' => $file_path
        ]);
    }

    public function getMediaItem($id)
    {
        if (is_string($id) && strlen($id) === 36 && preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $id)) {
            $stmt = $this->db->prepare("SELECT * FROM cms_media WHERE uuid = :uuid");
            $stmt->execute([':uuid' => $id]);
        } else {
            $stmt = $this->db->prepare("SELECT * FROM cms_media WHERE id = :id");
            $stmt->execute([':id' => $id]);
        }
        return $stmt->fetch();
    }

    public function deleteMedia($id)
    {
        if (is_string($id) && strlen($id) === 36 && preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $id)) {
            $stmt = $this->db->prepare("DELETE FROM cms_media WHERE uuid = :uuid");
            return $stmt->execute([':uuid' => $id]);
        }
        $stmt = $this->db->prepare("DELETE FROM cms_media WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function toggleStatus($id)
    {
        if (is_string($id) && strlen($id) === 36 && preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $id)) {
            $stmt = $this->db->prepare("UPDATE cms_media SET status = CASE WHEN status = 'active' THEN 'inactive' ELSE 'active' END WHERE uuid = :uuid");
            return $stmt->execute([':uuid' => $id]);
        }
        $stmt = $this->db->prepare("UPDATE cms_media SET status = CASE WHEN status = 'active' THEN 'inactive' ELSE 'active' END WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
