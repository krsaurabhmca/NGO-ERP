<?php

namespace App\Models;

use App\Core\Model;

class Career extends Model
{
    protected $table = 'careers';

    public function autoCloseExpired()
    {
        $sql = "UPDATE {$this->table} SET status = 'closed' WHERE status = 'open' AND deadline IS NOT NULL AND deadline < CURDATE()";
        $this->db->query($sql);
    }

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

    public function getActiveCareers()
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE status = 'open' AND (deadline IS NULL OR deadline >= CURDATE()) ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getDistinctJobTypes()
    {
        $stmt = $this->db->prepare("SELECT DISTINCT job_type FROM {$this->table} WHERE job_type IS NOT NULL AND job_type != '' ORDER BY job_type ASC");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }

    public function getActiveByJobType($type)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE status = 'open' AND (deadline IS NULL OR deadline >= CURDATE()) AND job_type = :type ORDER BY created_at DESC");
        $stmt->bindValue(':type', $type);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getActiveNonInternship()
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE status = 'open' AND (deadline IS NULL OR deadline >= CURDATE()) AND (job_type IS NULL OR job_type != 'Internship') ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
