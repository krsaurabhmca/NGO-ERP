<?php

namespace App\Models;

use App\Core\Model;

class Campaign extends Model
{
    protected $table = 'campaigns';

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

    public function getBySlug($slug)
    {
        $stmt = $this->db->prepare("
            SELECT c.*, 
                   (SELECT COUNT(*) FROM donations d WHERE d.campaign_id = c.id AND d.status = 'completed') AS donor_count
            FROM {$this->table} c 
            WHERE c.slug = :slug
            LIMIT 1
        ");
        $stmt->execute([':slug' => $slug]);
        return $stmt->fetch();
    }

    public function updateRaisedAmount($campaignId)
    {
        $stmt = $this->db->prepare("
            UPDATE {$this->table} 
            SET raised_amount = (
                SELECT COALESCE(SUM(amount), 0) 
                FROM donations 
                WHERE campaign_id = :cid AND status = 'completed'
            )
            WHERE id = :cid
        ");
        return $stmt->execute([':cid' => $campaignId]);
    }

    public function getActiveCampaigns()
    {
        $stmt = $this->db->prepare("
            SELECT c.*, 
                   (SELECT COUNT(*) FROM donations d WHERE d.campaign_id = c.id AND d.status = 'completed') AS donor_count
            FROM {$this->table} c 
            WHERE c.status = 'active' 
            ORDER BY c.created_at DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function all()
    {
        $stmt = $this->db->prepare("
            SELECT c.*, 
                   (SELECT COUNT(*) FROM donations d WHERE d.campaign_id = c.id AND d.status = 'completed') AS donor_count
            FROM {$this->table} c 
            ORDER BY c.created_at DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
