<?php

namespace App\Models;

use App\Core\Model;

class Expense extends Model
{
    protected $table = 'expenses';

    public function totalByCategory($category)
    {
        $stmt = $this->db->prepare("SELECT COALESCE(SUM(amount), 0) FROM {$this->table} WHERE category = :category");
        $stmt->execute([':category' => $category]);
        return $stmt->fetchColumn();
    }

    public function totalByDateRange($from, $to)
    {
        $stmt = $this->db->prepare("SELECT COALESCE(SUM(amount), 0) FROM {$this->table} WHERE expense_date BETWEEN :from AND :to");
        $stmt->execute([':from' => $from, ':to' => $to]);
        return $stmt->fetchColumn();
    }

    public function getCategories()
    {
        $stmt = $this->db->prepare("SELECT DISTINCT category FROM {$this->table} ORDER BY category ASC");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }
}
