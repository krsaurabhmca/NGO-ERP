<?php

namespace App\Models;

use App\Core\Model;

class SuccessStory extends Model
{
    protected $table = 'success_stories';

    public function getPublished()
    {
        $stmt = $this->db->prepare("SELECT s.*, b.name as beneficiary_name, b.photo as beneficiary_photo, b.assistance_type
            FROM {$this->table} s
            LEFT JOIN beneficiaries b ON s.beneficiary_id = b.id
            WHERE s.status = 'published'
            ORDER BY s.created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
