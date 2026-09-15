<?php

namespace App\Models;

use App\Core\Model;

class Permission extends Model
{
    protected $table = 'permissions';

    public function getRolePermissions($roleId)
    {
        $stmt = $this->db->prepare("
            SELECT p.* FROM permissions p
            JOIN role_permissions rp ON p.id = rp.permission_id
            WHERE rp.role_id = :role_id
        ");
        $stmt->execute([':role_id' => $roleId]);
        return $stmt->fetchAll();
    }

    public function roleHasModuleAccess($roleId, $module)
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM role_permissions rp
            JOIN permissions p ON p.id = rp.permission_id
            WHERE rp.role_id = :role_id AND p.module = :module
        ");
        $stmt->execute([':role_id' => $roleId, ':module' => $module]);
        return $stmt->fetchColumn() > 0;
    }

    public function roleHasPermission($roleId, $permissionName)
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM role_permissions rp
            JOIN permissions p ON p.id = rp.permission_id
            WHERE rp.role_id = :role_id AND p.name = :name
        ");
        $stmt->execute([':role_id' => $roleId, ':name' => $permissionName]);
        return $stmt->fetchColumn() > 0;
    }

    public function getModules()
    {
        $stmt = $this->db->prepare("SELECT DISTINCT module FROM permissions WHERE module IS NOT NULL ORDER BY module");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getByModule($module)
    {
        $stmt = $this->db->prepare("SELECT * FROM permissions WHERE module = :module ORDER BY name");
        $stmt->execute([':module' => $module]);
        return $stmt->fetchAll();
    }

    public function assignToRole($permissionId, $roleId)
    {
        $stmt = $this->db->prepare("INSERT IGNORE INTO role_permissions (role_id, permission_id) VALUES (:role_id, :permission_id)");
        return $stmt->execute([':role_id' => $roleId, ':permission_id' => $permissionId]);
    }

    public function removeFromRole($permissionId, $roleId)
    {
        $stmt = $this->db->prepare("DELETE FROM role_permissions WHERE role_id = :role_id AND permission_id = :permission_id");
        return $stmt->execute([':role_id' => $roleId, ':permission_id' => $permissionId]);
    }

    public function isSeeded()
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$this->table}");
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function seedDefaults()
    {
        if (!empty($_SESSION['_permissions_seeded'])) {
            return;
        }

        $defaults = [
            // Top-level pages
            ['name' => 'Dashboard', 'module' => 'dashboard'],
            // CMS sub-pages
            ['name' => 'Slider Manager', 'module' => 'cms'],
            ['name' => 'About Page', 'module' => 'cms'],
            ['name' => 'Gallery', 'module' => 'cms'],
            ['name' => 'Certificates', 'module' => 'cms'],
            ['name' => 'Achievements', 'module' => 'cms'],
            ['name' => 'Policies', 'module' => 'cms'],
            // Top-level pages
            ['name' => 'News Manager', 'module' => 'news'],
            ['name' => 'Notices Manager', 'module' => 'notices'],
            ['name' => 'Projects', 'module' => 'projects'],
            ['name' => 'Crowdfunding', 'module' => 'campaigns'],
            // Careers sub-pages
            ['name' => 'Job Postings', 'module' => 'careers'],
            ['name' => 'Interns', 'module' => 'careers'],
            ['name' => 'Employees', 'module' => 'careers'],
            ['name' => 'Applications', 'module' => 'careers'],
            // Top-level page
            ['name' => 'Contact Inquiries', 'module' => 'contacts'],
            // Members sub-pages
            ['name' => 'All Members', 'module' => 'members'],
            ['name' => 'Member Requests', 'module' => 'members'],
            ['name' => 'Membership Fees', 'module' => 'members'],
            ['name' => 'Designations', 'module' => 'members'],
            // Stakeholders sub-pages
            ['name' => 'Donors', 'module' => 'stakeholders'],
            ['name' => 'Beneficiaries', 'module' => 'stakeholders'],
            // Finance sub-pages
            ['name' => 'All Donations', 'module' => 'finance'],
            ['name' => 'Expenses', 'module' => 'finance'],
            ['name' => 'Financial Reports', 'module' => 'finance'],
            // Top-level page
            ['name' => 'User Management', 'module' => 'users'],
            // Settings sub-pages
            ['name' => 'Organization', 'module' => 'settings'],
            ['name' => 'SMTP Settings', 'module' => 'settings'],
            ['name' => 'Payment Gateways', 'module' => 'settings'],
            ['name' => 'Templates', 'module' => 'settings'],
            // Partners
            ['name' => 'Partners', 'module' => 'partners'],
        ];

        // First pass: delete old legacy permissions from the same modules
        $legacyNames = ['user-management', 'user-create', 'user-edit', 'user-delete', 'cms', 'news', 'campaigns', 'careers', 'Careers & Jobs', 'contacts', 'members', 'stakeholders', 'finance', 'donations', 'expenses', 'finance-reports', 'Donations & Finance', 'Role Permissions', 'Settings'];
        foreach ($legacyNames as $oldName) {
            $stmt = $this->db->prepare("DELETE FROM permissions WHERE name = :name");
            $stmt->execute([':name' => $oldName]);
        }

        foreach ($defaults as $perm) {
            $stmt = $this->db->prepare("SELECT id FROM permissions WHERE name = :name");
            $stmt->execute([':name' => $perm['name']]);
            if (!$stmt->fetch()) {
                $this->create($perm);
            }
        }

        $superAdminRoleId = 1;
        $allPerms = $this->db->prepare("SELECT id FROM permissions");
        $allPerms->execute();
        foreach ($allPerms->fetchAll() as $p) {
            $this->assignToRole($p->id, $superAdminRoleId);
        }

        $_SESSION['_permissions_seeded'] = true;
    }

    public function getUserPermissions($userId)
    {
        $stmt = $this->db->prepare("
            SELECT p.* FROM permissions p
            JOIN user_permissions up ON p.id = up.permission_id
            WHERE up.user_id = :user_id
        ");
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public function userHasModuleAccess($userId, $module)
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM user_permissions up
            JOIN permissions p ON p.id = up.permission_id
            WHERE up.user_id = :user_id AND p.module = :module
        ");
        $stmt->execute([':user_id' => $userId, ':module' => $module]);
        return $stmt->fetchColumn() > 0;
    }

    public function userHasPermission($userId, $permissionName)
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM user_permissions up
            JOIN permissions p ON p.id = up.permission_id
            WHERE up.user_id = :user_id AND p.name = :name
        ");
        $stmt->execute([':user_id' => $userId, ':name' => $permissionName]);
        return $stmt->fetchColumn() > 0;
    }

    public function assignToUser($permissionId, $userId)
    {
        $stmt = $this->db->prepare("INSERT IGNORE INTO user_permissions (user_id, permission_id) VALUES (:user_id, :permission_id)");
        return $stmt->execute([':user_id' => $userId, ':permission_id' => $permissionId]);
    }

    public function removeAllFromUser($userId)
    {
        $stmt = $this->db->prepare("DELETE FROM user_permissions WHERE user_id = :user_id");
        return $stmt->execute([':user_id' => $userId]);
    }
}
