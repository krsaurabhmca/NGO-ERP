<?php

namespace App\Models;

use App\Core\Model;

class Member extends Model
{
    protected $table = 'members';

    public function getByDesignation($designationId, $status = null)
    {
        $sql = "SELECT * FROM {$this->table} WHERE designation_id = :designation_id";
        $params = [':designation_id' => $designationId];

        if ($status === 'pending') {
            $sql .= " AND status = 'pending'";
        } elseif (!$status) {
            $sql .= " AND status != 'pending'";
        }

        $sql .= " ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function generateMembershipId($prefix = null)
    {
        $prefix = ($prefix ?: 'MEM') . '-';
        $year = date('Y');
        
        // Get the latest member ID for the current year
        $stmt = $this->db->prepare("SELECT membership_id FROM {$this->table} WHERE membership_id LIKE :pattern ORDER BY id DESC LIMIT 1");
        $stmt->execute([':pattern' => "{$prefix}{$year}-%"]);
        $lastId = $stmt->fetch();

        if ($lastId) {
            $parts = explode('-', $lastId->membership_id);
            $sequence = (int)end($parts) + 1;
        } else {
            $sequence = 1;
        }

        return $prefix . $year . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    public function getActiveWithDesignation()
    {
        $stmt = $this->db->prepare("
            SELECT m.*, d.name AS designation_name
            FROM {$this->table} m
            LEFT JOIN designations d ON m.designation_id = d.id
            WHERE m.status = 'active'
            ORDER BY m.created_at DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function allWithDesignation($excludeStatus = null)
    {
        $sql = "SELECT m.*, d.name AS designation_name
                FROM {$this->table} m
                LEFT JOIN designations d ON m.designation_id = d.id";
        if ($excludeStatus) {
            $sql .= " WHERE m.status != :exclude_status";
        }
        $sql .= " ORDER BY m.created_at DESC";
        $stmt = $this->db->prepare($sql);
        if ($excludeStatus) {
            $stmt->execute([':exclude_status' => $excludeStatus]);
        } else {
            $stmt->execute();
        }
        return $stmt->fetchAll();
    }

    public function getMinJoinYear()
    {
        $stmt = $this->db->prepare("SELECT MIN(YEAR(join_date)) FROM {$this->table}");
        $stmt->execute();
        $year = $stmt->fetchColumn();
        return $year ? (int)$year : null;
    }

    public function existsByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$this->table} WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return $stmt->fetchColumn() > 0;
    }

    public function existsByPhone($phone)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$this->table} WHERE phone = :phone");
        $stmt->execute([':phone' => $phone]);
        return $stmt->fetchColumn() > 0;
    }
}
