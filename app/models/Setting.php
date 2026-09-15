<?php

namespace App\Models;

use App\Core\Model;

class Setting extends Model
{
    protected $table = 'settings';

    public function getAllByGroup($group = null)
    {
        $sql = "SELECT * FROM {$this->table}";
        if ($group) {
            $sql .= " WHERE group_name = :group";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':group', $group);
        } else {
            $stmt = $this->db->prepare($sql);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function updateByKey($key, $value, $group = 'general')
    {
        try {
            // Check if key exists
            $stmt = $this->db->prepare("SELECT COUNT(*) as cnt FROM {$this->table} WHERE key_name = :key");
            $stmt->bindValue(':key', $key);
            $stmt->execute();
            $row = $stmt->fetch();

            if ($row->cnt > 0) {
                $stmt = $this->db->prepare("UPDATE {$this->table} SET key_value = :value, group_name = :group WHERE key_name = :key");
                $stmt->bindValue(':key', $key);
                $stmt->bindValue(':value', $value);
                $stmt->bindValue(':group', $group);
                return $stmt->execute();
            } else {
                $stmt = $this->db->prepare("INSERT INTO {$this->table} (key_name, key_value, group_name) VALUES (:key, :value, :group)");
                $stmt->bindValue(':key', $key);
                $stmt->bindValue(':value', $value);
                $stmt->bindValue(':group', $group);
                return $stmt->execute();
            }
        } catch (\PDOException $e) {
            error_log("Settings Update Error: " . $e->getMessage());
            return false;
        }
    }

    public function getVal($key)
    {
        $stmt = $this->db->prepare("SELECT key_value FROM {$this->table} WHERE key_name = :key");
        $stmt->bindValue(':key', $key);
        $stmt->execute();
        $res = $stmt->fetch();
        return $res ? $res->key_value : null;
    }
}
