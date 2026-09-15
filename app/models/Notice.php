<?php

namespace App\Models;

use App\Core\Model;

class Notice extends Model
{
    protected $table = 'notices';

    public function getActiveNotices()
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE status = 'active' ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
