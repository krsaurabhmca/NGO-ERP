<?php

namespace App\Models;

use App\Core\Model;

class Testimonial extends Model
{
    protected $table = 'testimonials';

    public function getPublished()
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE status = 'published' ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
