<?php

namespace App\Models;

use App\Core\Model;

class Donor extends Model
{
    protected $table = 'donors';

    public function generateDonorId($prefix = null)
    {
        $prefix = ($prefix ?: 'DNR') . '-';
        $year = date('Y');
        
        $stmt = $this->db->prepare("SELECT donor_id FROM {$this->table} WHERE donor_id LIKE :pattern ORDER BY id DESC LIMIT 1");
        $stmt->execute([':pattern' => "{$prefix}{$year}-%"]);
        $lastId = $stmt->fetch();

        if ($lastId) {
            $parts = explode('-', $lastId->donor_id);
            $sequence = (int)end($parts) + 1;
        } else {
            $sequence = 1;
        }

        return $prefix . $year . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    public function addDonationAmount($email, $phone, $amount)
    {
        if (empty($email) && empty($phone)) {
            return false;
        }
        
        $conditions = [];
        $params = [];
        
        if (!empty($email)) {
            $conditions[] = "email = :email";
            $params[':email'] = $email;
        }
        
        if (!empty($phone)) {
            $conditions[] = "phone = :phone";
            $params[':phone'] = $phone;
        }
        
        $whereClause = implode(" OR ", $conditions);
        
        $stmt = $this->db->prepare("UPDATE {$this->table} SET total_donations = total_donations + :amount WHERE {$whereClause}");
        $params[':amount'] = $amount;
        
        return $stmt->execute($params);
    }
}
