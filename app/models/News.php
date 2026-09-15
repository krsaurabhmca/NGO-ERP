<?php

namespace App\Models;

use App\Core\Model;

class News extends Model
{
    protected $table = 'news';

    public function createSlug($title)
    {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
        
        // Check if slug exists
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$this->table} WHERE slug = :slug");
        $stmt->execute([':slug' => $slug]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            $slug = $slug . '-' . time();
        }

        return $slug;
    }

    public function getActiveNews()
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE status = 'active' ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
