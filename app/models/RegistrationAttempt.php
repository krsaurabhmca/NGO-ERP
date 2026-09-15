<?php

namespace App\Models;

use App\Core\Model;

class RegistrationAttempt extends Model
{
    protected $table = 'registration_attempts';

    public function countRecentByIp($ip, $minutes = 60)
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

    public function countRecentByEmail($email, $minutes = 60)
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

    public function record($email, $ip)
    {
        return $this->create([
            'email' => $email,
            'ip_address' => $ip,
        ]);
    }

    public function clearForEmail($email)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE email = :email");
        return $stmt->execute([':email' => $email]);
    }

    public function clearForIp($ip)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE ip_address = :ip");
        return $stmt->execute([':ip' => $ip]);
    }
}
