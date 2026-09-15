<?php

namespace App\Models;

use App\Core\Model;

class JobApplication extends Model
{
    protected $table = 'job_applications';

    public function getByCareer($careerId)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE career_id = :career_id ORDER BY created_at DESC");
        $stmt->execute([':career_id' => $careerId]);
        return $stmt->fetchAll();
    }

    public function getAllWithCareer()
    {
        $stmt = $this->db->prepare("
            SELECT a.*, c.title AS career_title 
            FROM {$this->table} a 
            LEFT JOIN careers c ON a.career_id = c.id 
            ORDER BY a.created_at DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getFiltered($status, $jobType)
    {
        $stmt = $this->db->prepare("
            SELECT a.*, c.title AS career_title 
            FROM {$this->table} a 
            LEFT JOIN careers c ON a.career_id = c.id 
            WHERE a.status = :status AND c.job_type = :job_type
            ORDER BY a.created_at DESC
        ");
        $stmt->execute([':status' => $status, ':job_type' => $jobType]);
        return $stmt->fetchAll();
    }

    public function getByJobType($jobType)
    {
        $stmt = $this->db->prepare("
            SELECT a.*, c.title AS career_title 
            FROM {$this->table} a 
            LEFT JOIN careers c ON a.career_id = c.id 
            WHERE c.job_type = :job_type
            ORDER BY a.created_at DESC
        ");
        $stmt->execute([':job_type' => $jobType]);
        return $stmt->fetchAll();
    }

    public function getWithCareer($id)
    {
        if (is_string($id) && strlen($id) === 36 && preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $id)) {
            $stmt = $this->db->prepare("
                SELECT a.*, c.title AS career_title, c.location, c.job_type 
                FROM {$this->table} a 
                LEFT JOIN careers c ON a.career_id = c.id 
                WHERE a.uuid = :uuid
            ");
            $stmt->execute([':uuid' => $id]);
        } else {
            $stmt = $this->db->prepare("
                SELECT a.*, c.title AS career_title, c.location, c.job_type 
                FROM {$this->table} a 
                LEFT JOIN careers c ON a.career_id = c.id 
                WHERE a.id = :id
            ");
            $stmt->execute([':id' => $id]);
        }
        return $stmt->fetch();
    }

    public function getHiredInterns()
    {
        $stmt = $this->db->prepare("
            SELECT a.*, c.title AS career_title, c.location, c.job_type
            FROM {$this->table} a
            LEFT JOIN careers c ON a.career_id = c.id
            WHERE a.status IN ('hired', 'terminated', 'resigned') AND c.job_type = 'Internship'
            ORDER BY a.updated_at DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getHiredEmployees()
    {
        $stmt = $this->db->prepare("
            SELECT a.*, c.title AS career_title, c.location, c.job_type
            FROM {$this->table} a
            LEFT JOIN careers c ON a.career_id = c.id
            WHERE a.status IN ('hired', 'terminated', 'resigned') AND (c.job_type IS NULL OR c.job_type != 'Internship')
            ORDER BY a.updated_at DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
