<?php

namespace App\Models;

use App\Core\Model;

class LoginAttempt extends Model
{
    protected $table = 'login_attempts';

    /**
     * Count recent failed attempts from an IP/email combination
     */
    public function countRecent($email, $ip, $minutes = 15)
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM {$this->table}
             WHERE email = :email AND ip_address = :ip
             AND attempted_at > DATE_SUB(NOW(), INTERVAL :minutes MINUTE)"
        );
        $stmt->execute([
            ':email' => $email,
            ':ip' => $ip,
            ':minutes' => (int)$minutes,
        ]);
        return (int)$stmt->fetchColumn();
    }

    /**
     * Count recent failed attempts by IP only (across all emails)
     */
    public function countRecentByIp($ip, $minutes = 15)
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM {$this->table}
             WHERE ip_address = :ip
             AND attempted_at > DATE_SUB(NOW(), INTERVAL :minutes MINUTE)"
        );
        $stmt->execute([
            ':ip' => $ip,
            ':minutes' => (int)$minutes,
        ]);
        return (int)$stmt->fetchColumn();
    }

    /**
     * Count recent failed attempts for a specific email
     */
    public function countRecentByEmail($email, $minutes = 15)
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM {$this->table}
             WHERE email = :email
             AND attempted_at > DATE_SUB(NOW(), INTERVAL :minutes MINUTE)"
        );
        $stmt->execute([
            ':email' => $email,
            ':minutes' => (int)$minutes,
        ]);
        return (int)$stmt->fetchColumn();
    }

    /**
     * Record a failed login attempt
     */
    public function record($email, $ip)
    {
        return $this->create([
            'email' => $email,
            'ip_address' => $ip,
        ]);
    }

    /**
     * Clear all failed attempts for an email
     */
    public function clearForEmail($email)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE email = :email");
        return $stmt->execute([':email' => $email]);
    }

    /**
     * Clear all failed attempts for an IP
     */
    public function clearForIp($ip)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE ip_address = :ip");
        return $stmt->execute([':ip' => $ip]);
    }
}
