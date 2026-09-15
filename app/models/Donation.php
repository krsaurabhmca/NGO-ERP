<?php

namespace App\Models;

use App\Core\Model;

class Donation extends Model
{
    protected $table = 'donations';

    public function totalCompleted()
    {
        $stmt = $this->db->prepare("SELECT COALESCE(SUM(amount), 0) FROM {$this->table} WHERE status = 'completed'");
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function countCompleted()
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$this->table} WHERE status = 'completed'");
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function expirePendingDonations()
    {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET status = 'failed' WHERE status = 'pending' AND created_at < DATE_SUB(NOW(), INTERVAL 2 HOUR)");
        return $stmt->execute();
    }

    public function updateByTransactionId($txnId, $data)
    {
        foreach (array_keys($data) as $key) {
            $this->validateColumn($key);
        }
        $sets = [];
        $params = [':txn_id' => $txnId];
        foreach ($data as $key => $value) {
            $sets[] = "$key = :$key";
            $params[":$key"] = $value;
        }
        $sql = "UPDATE {$this->table} SET " . implode(', ', $sets) . " WHERE transaction_id = :txn_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function totalByDateRange($from, $to, $status = 'completed')
    {
        $stmt = $this->db->prepare("SELECT COALESCE(SUM(amount), 0) FROM {$this->table} WHERE status = :status AND created_at BETWEEN :from AND :to");
        $stmt->execute([':status' => $status, ':from' => $from . ' 00:00:00', ':to' => $to . ' 23:59:59']);
        return $stmt->fetchColumn();
    }

    public function countByDateRange($from, $to, $status = 'completed')
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$this->table} WHERE status = :status AND created_at BETWEEN :from AND :to");
        $stmt->execute([':status' => $status, ':from' => $from . ' 00:00:00', ':to' => $to . ' 23:59:59']);
        return $stmt->fetchColumn();
    }

    public function totalByPaymentMethod($method, $status = 'completed')
    {
        $stmt = $this->db->prepare("SELECT COALESCE(SUM(amount), 0) FROM {$this->table} WHERE payment_method = :method AND status = :status");
        $stmt->execute([':method' => $method, ':status' => $status]);
        return $stmt->fetchColumn();
    }

    public function getMonthlyTotals($year = null)
    {
        if (!$year) $year = date('Y');
        $stmt = $this->db->prepare("
            SELECT MONTH(COALESCE(payment_for_date, created_at)) as month, COALESCE(SUM(amount), 0) as total
            FROM {$this->table}
            WHERE status = 'completed' AND YEAR(COALESCE(payment_for_date, created_at)) = :year
            GROUP BY MONTH(COALESCE(payment_for_date, created_at))
            ORDER BY month ASC
        ");
        $stmt->execute([':year' => $year]);
        return $stmt->fetchAll();
    }

    public function getByMemberId($memberId, $limit = null)
    {
        $sql = "SELECT * FROM {$this->table} WHERE member_id = :member_id ORDER BY created_at DESC";
        $params = [':member_id' => $memberId];
        if ($limit) {
            $sql .= " LIMIT " . (int)$limit;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getMemberStats($memberId)
    {
        $stmt = $this->db->prepare("
            SELECT 
                COUNT(*) as total_donations,
                COALESCE(SUM(CASE WHEN status = 'completed' THEN amount ELSE 0 END), 0) as total_amount,
                COUNT(CASE WHEN is_recurring = 1 THEN 1 END) as recurring_count
            FROM {$this->table} 
            WHERE member_id = :member_id
        ");
        $stmt->execute([':member_id' => $memberId]);
        return $stmt->fetch();
    }

    public function getYearsRange()
    {
        $stmt = $this->db->prepare("SELECT DISTINCT YEAR(COALESCE(payment_for_date, created_at)) as yr FROM {$this->table} WHERE status = 'completed' ORDER BY yr ASC");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }

    public function getFullYearRange($memberId = null)
    {
        if ($memberId) {
            $stmt = $this->db->prepare("SELECT DISTINCT YEAR(COALESCE(payment_for_date, created_at)) as yr FROM {$this->table} WHERE member_id = :member_id AND status = 'completed' ORDER BY yr ASC");
            $stmt->execute([':member_id' => $memberId]);
            return $stmt->fetchAll(\PDO::FETCH_COLUMN);
        }
        return $this->getYearsRange();
    }

    public function getPaidMonthsByYear($memberId, $year)
    {
        $stmt = $this->db->prepare("
            SELECT MONTH(COALESCE(payment_for_date, created_at)) as month
            FROM {$this->table}
            WHERE member_id = :member_id
              AND YEAR(COALESCE(payment_for_date, created_at)) = :year
              AND status = 'completed'
            GROUP BY MONTH(COALESCE(payment_for_date, created_at))
            ORDER BY month ASC
        ");
        $stmt->execute([
            ':member_id' => $memberId,
            ':year' => $year
        ]);
        $months = [];
        foreach ($stmt->fetchAll() as $row) {
            $months[] = (int)$row->month;
        }
        return $months;
    }

    /**
     * Filter donations by multiple criteria
     */
    public function filter($filters = [])
    {
        $where = [];
        $params = [];

        if (!empty($filters['status'])) {
            $where[] = "status = :status";
            $params[':status'] = $filters['status'];
        }

        if (!empty($filters['payment_method'])) {
            $where[] = "payment_method = :payment_method";
            $params[':payment_method'] = $filters['payment_method'];
        }

        if (!empty($filters['date_from'])) {
            $where[] = "created_at >= :date_from";
            $params[':date_from'] = $filters['date_from'] . ' 00:00:00';
        }

        if (!empty($filters['date_to'])) {
            $where[] = "created_at <= :date_to";
            $params[':date_to'] = $filters['date_to'] . ' 23:59:59';
        }

        if (!empty($filters['search'])) {
            $where[] = "(donor_name LIKE :search OR donor_email LIKE :search2 OR donor_phone LIKE :search3)";
            $params[':search'] = '%' . $filters['search'] . '%';
            $params[':search2'] = '%' . $filters['search'] . '%';
            $params[':search3'] = '%' . $filters['search'] . '%';
        }

        $sql = "SELECT * FROM {$this->table}";
        if (!empty($where)) {
            $sql .= " WHERE " . implode(' AND ', $where);
        }
        $sql .= " ORDER BY created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getDonationReceiptsByYear($memberId, $year)
    {
        $stmt = $this->db->prepare("
            SELECT MONTH(COALESCE(payment_for_date, created_at)) as month, uuid, amount, payment_method, created_at
            FROM {$this->table}
            WHERE member_id = :member_id
              AND YEAR(COALESCE(payment_for_date, created_at)) = :year
              AND status = 'completed'
            ORDER BY created_at ASC
        ");
        $stmt->execute([
            ':member_id' => $memberId,
            ':year' => $year
        ]);
        $receipts = [];
        foreach ($stmt->fetchAll() as $row) {
            $receipts[(int)$row->month] = [
                'uuid' => $row->uuid,
                'amount' => $row->amount,
                'payment_method' => $row->payment_method,
                'created_at' => $row->created_at
            ];
        }
        return $receipts;
    }
}
