<?php

namespace App\Models;

use App\Core\Model;

class Contact extends Model
{
    protected $table = 'contact_inquiries';

    public function getLatest($limit = 10)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} ORDER BY created_at DESC LIMIT :limit");
        $stmt->bindValue(':limit', (int)$limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function markAsRead($id)
    {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET status = 'read' WHERE id = :id AND status = 'unread'");
        return $stmt->execute([':id' => $id]);
    }
}
