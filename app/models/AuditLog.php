<?php

namespace App\Models;

use App\Core\Model;

class AuditLog extends Model
{
    protected $table = 'audit_logs';

    /**
     * Log an action to the audit trail
     * 
     * @param string $action Action performed (e.g., 'delete', 'update', 'approve')
     * @param string $module Module/resource type (e.g., 'donations', 'members')
     * @param int $recordId The ID of the affected record
     * @param mixed $oldData The data before the change (will be JSON encoded)
     * @param mixed $newData The data after the change (will be JSON encoded)
     * @param int|null $userId Admin user ID (if admin action)
     * @param int|null $memberId Member ID (if member action)
     * @return int|false The audit log ID or false on failure
     */
    public static function log($action, $module, $recordId = null, $oldData = null, $newData = null, $userId = null, $memberId = null)
    {
        $instance = new self();
        
        // Get user/member ID from session if not provided
        if ($userId === null && isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];
        }
        if ($memberId === null && isset($_SESSION['member_id'])) {
            $memberId = $_SESSION['member_id'];
        }

        $data = [
            'user_id' => $userId,
            'member_id' => $memberId,
            'action' => $action,
            'module' => $module,
            'record_id' => $recordId,
            'old_data' => is_array($oldData) || is_object($oldData) ? json_encode($oldData) : $oldData,
            'new_data' => is_array($newData) || is_object($newData) ? json_encode($newData) : $newData,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null
        ];

        try {
            return $instance->create($data);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get audit logs with optional filters
     * 
     * @param array $filters ['action' => 'delete', 'module' => 'donations', 'user_id' => 1, 'date_from' => '2024-01-01', 'date_to' => '2024-12-31']
     * @param int $limit Number of records to return
     * @param int $offset Starting offset
     * @return array
     */
    public function getFiltered($filters = [], $limit = 50, $offset = 0)
    {
        $where = [];
        $params = [];

        if (!empty($filters['action'])) {
            $where[] = "action = :action";
            $params[':action'] = $filters['action'];
        }

        if (!empty($filters['module'])) {
            $where[] = "module = :module";
            $params[':module'] = $filters['module'];
        }

        if (!empty($filters['user_id'])) {
            $where[] = "user_id = :user_id";
            $params[':user_id'] = $filters['user_id'];
        }

        if (!empty($filters['member_id'])) {
            $where[] = "member_id = :member_id";
            $params[':member_id'] = $filters['member_id'];
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
            $where[] = "(action LIKE :search OR module LIKE :search OR ip_address LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }

        $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';
        
        $sql = "SELECT al.*, 
                       u.username as admin_username,
                       m.name as member_name
                FROM {$this->table} al
                LEFT JOIN users u ON al.user_id = u.id
                LEFT JOIN members m ON al.member_id = m.id
                {$whereClause}
                ORDER BY al.created_at DESC
                LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', (int)$limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, \PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    /**
     * Get total count of audit logs with filters
     */
    public function countFiltered($filters = [])
    {
        $where = [];
        $params = [];

        if (!empty($filters['action'])) {
            $where[] = "action = :action";
            $params[':action'] = $filters['action'];
        }

        if (!empty($filters['module'])) {
            $where[] = "module = :module";
            $params[':module'] = $filters['module'];
        }

        if (!empty($filters['user_id'])) {
            $where[] = "user_id = :user_id";
            $params[':user_id'] = $filters['user_id'];
        }

        if (!empty($filters['member_id'])) {
            $where[] = "member_id = :member_id";
            $params[':member_id'] = $filters['member_id'];
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
            $where[] = "(action LIKE :search OR module LIKE :search OR ip_address LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }

        $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';
        
        $sql = "SELECT COUNT(*) FROM {$this->table} {$whereClause}";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchColumn();
    }

    /**
     * Get recent audit logs
     */
    public function getRecent($limit = 50)
    {
        $sql = "SELECT al.*, 
                       u.username as admin_username,
                       m.name as member_name
                FROM {$this->table} al
                LEFT JOIN users u ON al.user_id = u.id
                LEFT JOIN members m ON al.member_id = m.id
                ORDER BY al.created_at DESC
                LIMIT :limit";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, \PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    /**
     * Get audit logs for a specific record
     */
    public function getForRecord($module, $recordId)
    {
        $sql = "SELECT al.*, 
                       u.username as admin_username,
                       m.name as member_name
                FROM {$this->table} al
                LEFT JOIN users u ON al.user_id = u.id
                LEFT JOIN members m ON al.member_id = m.id
                WHERE al.module = :module AND al.record_id = :record_id
                ORDER BY al.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':module' => $module, ':record_id' => $recordId]);
        
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    /**
     * Get unique actions from audit logs (for filter dropdowns)
     */
    public function getUniqueActions()
    {
        $sql = "SELECT DISTINCT action FROM {$this->table} ORDER BY action";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }

    /**
     * Get unique modules from audit logs (for filter dropdowns)
     */
    public function getUniqueModules()
    {
        $sql = "SELECT DISTINCT module FROM {$this->table} ORDER BY module";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }
}
