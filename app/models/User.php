<?php

namespace App\Models;

use App\Core\Model;

class User extends Model
{
    protected $table = 'users';

    public function findByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function updateLoginTime($id)
    {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET last_login = NOW() WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function getAllWithRoles()
    {
        $stmt = $this->db->prepare("
            SELECT u.*, r.name as role_name 
            FROM {$this->table} u 
            LEFT JOIN roles r ON u.role_id = r.id 
            ORDER BY u.created_at DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getLastInsertId()
    {
        return $this->db->lastInsertId();
    }

    public function searchWithRoles($term)
    {
        $stmt = $this->db->prepare("
            SELECT u.*, r.name as role_name 
            FROM {$this->table} u 
            LEFT JOIN roles r ON u.role_id = r.id 
            WHERE u.name LIKE :term OR u.email LIKE :term OR u.phone LIKE :term
            ORDER BY u.created_at DESC
        ");
        $stmt->execute([':term' => "%{$term}%"]);
        return $stmt->fetchAll();
    }
}
