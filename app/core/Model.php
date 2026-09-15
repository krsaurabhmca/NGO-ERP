<?php

namespace App\Core;

use PDO;

class Model
{
    protected $db;
    protected $table;
    private $_hasUuid;
    private static $_columnCache = [];

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    private function hasUuidColumn()
    {
        if ($this->_hasUuid === null) {
            $stmt = $this->db->prepare("SHOW COLUMNS FROM {$this->table} LIKE 'uuid'");
            $stmt->execute();
            $this->_hasUuid = (bool) $stmt->fetch();
        }
        return $this->_hasUuid;
    }

    protected function getTableColumns()
    {
        $table = $this->table;
        if (!isset(self::$_columnCache[$table])) {
            $stmt = $this->db->prepare("SHOW COLUMNS FROM `{$table}`");
            $stmt->execute();
            self::$_columnCache[$table] = [];
            while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                self::$_columnCache[$table][] = $row['Field'];
            }
        }
        return self::$_columnCache[$table];
    }

    protected function validateColumn($column)
    {
        if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $column)) {
            throw new \InvalidArgumentException("Invalid column name: '$column'");
        }
        $columns = $this->getTableColumns();
        if (!in_array($column, $columns)) {
            throw new \InvalidArgumentException("Unknown column '$column' for table '{$this->table}'");
        }
    }

    public static function uuid()
    {
        $data = random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    public function all()
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table}");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function count()
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$this->table}");
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function find($id)
    {
        if (is_string($id) && strlen($id) === 36 && preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $id)) {
            return $this->findByUuid($id);
        }
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function findByUuid($uuid)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE uuid = :uuid");
        $stmt->execute([':uuid' => $uuid]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        if (!isset($data['uuid']) && $this->hasUuidColumn()) {
            $data['uuid'] = self::uuid();
        }
        foreach (array_keys($data) as $key) {
            $this->validateColumn($key);
        }
        $keys = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        $sql = "INSERT INTO {$this->table} ({$keys}) VALUES ({$placeholders})";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function lastInsertId()
    {
        return $this->db->lastInsertId();
    }

    public function update($id, $data)
    {
        foreach (array_keys($data) as $key) {
            $this->validateColumn($key);
        }
        $fields = "";
        foreach ($data as $key => $value) {
            $fields .= "{$key} = :{$key}, ";
        }
        $fields = rtrim($fields, ', ');

        if (is_string($id) && strlen($id) === 36 && preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $id)) {
            $sql = "UPDATE {$this->table} SET {$fields} WHERE uuid = :uuid";
            $data['uuid'] = $id;
        } else {
            $sql = "UPDATE {$this->table} SET {$fields} WHERE id = :id";
            $data['id'] = $id;
        }

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function delete($id)
    {
        if (is_string($id) && strlen($id) === 36 && preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $id)) {
            $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE uuid = :uuid");
            $stmt->bindParam(':uuid', $id);
            return $stmt->execute();
        }
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function where($column, $value)
    {
        $this->validateColumn($column);
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$column} = :value");
        $stmt->execute([':value' => $value]);
        return $stmt->fetchAll();
    }

    public function whereNot($column, $value)
    {
        $this->validateColumn($column);
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$column} != :value");
        $stmt->execute([':value' => $value]);
        return $stmt->fetchAll();
    }

    public function search($columns, $term, $conditions = [])
    {
        $where = [];
        $params = [':term' => "%{$term}%"];
        
        foreach ($columns as $column) {
            $this->validateColumn($column);
            $where[] = "({$column} LIKE :term)";
        }
        $whereSql = "(" . implode(' OR ', $where) . ")";

        foreach ($conditions as $column => $value) {
            $this->validateColumn($column);
            $op = '=';
            $allowedOps = ['=', '!=', '<', '>', '<=', '>=', 'LIKE', 'NOT LIKE'];
            if (is_array($value)) {
                $op = strtoupper($value[0]);
                if (!in_array($op, $allowedOps)) {
                    throw new \InvalidArgumentException("Invalid operator: $op");
                }
                $val = $value[1];
            } else {
                $val = $value;
            }
            $paramName = ":cond_" . str_replace('.', '_', $column);
            $whereSql .= " AND {$column} {$op} {$paramName}";
            $params[$paramName] = $val;
        }

        $sql = "SELECT * FROM {$this->table} WHERE {$whereSql}";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}
