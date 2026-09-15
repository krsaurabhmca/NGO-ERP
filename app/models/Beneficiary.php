<?php

namespace App\Models;

use App\Core\Model;

class Beneficiary extends Model
{
    protected $table = 'beneficiaries';

    public function generateBeneficiaryId($prefix = null)
    {
        $prefix = ($prefix ?: 'BEN') . '-';
        $year = date('Y');
        
        $stmt = $this->db->prepare("SELECT beneficiary_id FROM {$this->table} WHERE beneficiary_id LIKE :pattern ORDER BY id DESC LIMIT 1");
        $stmt->execute([':pattern' => "{$prefix}{$year}-%"]);
        $lastId = $stmt->fetch();

        if ($lastId) {
            $parts = explode('-', $lastId->beneficiary_id);
            $sequence = (int)end($parts) + 1;
        } else {
            $sequence = 1;
        }

        return $prefix . $year . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    public function userCanAccess($id, $userId)
    {
        // No per-owner column exists. Only admins (role_id=1) can access.
        $stmt = $this->db->prepare("SELECT 1 FROM users WHERE id = :user_id AND role_id = 1");
        $stmt->execute([':user_id' => $userId]);
        return (bool)$stmt->fetchColumn();
    }
}
