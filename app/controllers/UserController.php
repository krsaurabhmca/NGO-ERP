<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\AuditLog;

class UserController extends Controller
{
    protected $userModel;

    public function __construct()
    {
        parent::__construct();
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('auth');
        }

        $this->checkModuleAccess('users');
        $this->userModel = new User();
    }

    public function index()
    {
        $search = $_GET['search'] ?? null;

        if ($search) {
            $users = $this->userModel->searchWithRoles($search);
        } else {
            $users = $this->userModel->getAllWithRoles();
        }

        $roleModel = new Role();
        $roles = $roleModel->all();

        $permModel = new Permission();
        $modules = $permModel->getModules();
        $modulePermissions = [];
        foreach ($modules as $mod) {
            $modulePermissions[$mod->module] = $permModel->getByModule($mod->module);
        }

        return $this->view('admin/users/index', [
            'title' => 'User Management',
            'users' => $users,
            'roles' => $roles,
            'modules' => $modules,
            'modulePermissions' => $modulePermissions
        ]);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            if (empty($password) || strlen($password) < 6) {
                $_SESSION['error'] = 'Password must be at least 6 characters.';
                return $this->redirect('admin/users');
            }

            if ($password !== $confirmPassword) {
                $_SESSION['error'] = 'Passwords do not match.';
                return $this->redirect('admin/users');
            }

            $data = [
                'name' => $_POST['name'] ?? '',
                'email' => $_POST['email'] ?? '',
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'role_id' => !empty($_POST['role_id']) ? $_POST['role_id'] : null,
                'status' => $_POST['status'] ?? 'active'
            ];

            if ($this->userModel->create($data)) {
                $userId = $this->userModel->lastInsertId();
                $permModel = new Permission();
                $permModel->removeAllFromUser($userId);
                if (!empty($_POST['permissions'])) {
                    foreach ($_POST['permissions'] as $permId) {
                        $permModel->assignToUser($permId, $userId);
                    }
                }
                $_SESSION['success'] = 'User added successfully.';

                // Send email with login details to the newly created user
                if (!empty($data['email']) && $data['status'] === 'active') {
                    $ngoName = $this->globalSettings['ngo_name'] ?? 'NGO Management System';
                    $loginUrl = url('auth');
                    $subject = "Your Account Details | {$ngoName}";
                    
                    $message = "
                    <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden;'>
                        <div style='background-color: #1a44a6; padding: 20px; text-align: center;'>
                            <h2 style='color: white; margin: 0;'>Welcome to {$ngoName}</h2>
                        </div>
                        <div style='padding: 20px; background-color: #ffffff;'>
                            <p style='font-size: 16px; color: #333;'>Dear <strong>" . htmlspecialchars($data['name']) . "</strong>,</p>
                            <p style='font-size: 15px; color: #444; line-height: 1.6;'>An administrative account has been created for you at <strong>{$ngoName}</strong>. Below are your login credentials:</p>
                            
                            <div style='background-color: #f8f9fa; padding: 15px; border-radius: 6px; margin: 20px 0;'>
                                <p style='margin: 0 0 10px 0; font-size: 14px;'><strong>Login URL:</strong> <a href='{$loginUrl}' style='color: #1a44a6;'>{$loginUrl}</a></p>
                                <p style='margin: 0 0 10px 0; font-size: 14px;'><strong>Email / Username:</strong> " . htmlspecialchars($data['email']) . "</p>
                                <p style='margin: 0; font-size: 14px;'><strong>Password:</strong> " . htmlspecialchars($password) . "</p>
                            </div>
                            
                            <p style='font-size: 14px; color: #666;'><em>For security reasons, we strongly recommend that you log in and change your password immediately.</em></p>
                        </div>
                        <div style='background-color: #f1f5f9; padding: 15px; text-align: center; border-top: 1px solid #e2e8f0;'>
                            <p style='margin: 0; font-size: 12px; color: #64748b;'>This is an automated message, please do not reply directly to this email.</p>
                        </div>
                    </div>";

                    try {
                        $mailer = new \App\Core\Mailer($this->globalSettings);
                        $mailSent = $mailer->send($data['email'], $subject, $message, true);
                        if ($mailSent) {
                            $_SESSION['success'] .= ' Login details have been emailed to the user.';
                        } else {
                            $_SESSION['success'] .= ' However, the email failed to send (check SMTP settings).';
                        }
                    } catch (\Exception $e) {
                        error_log("Failed to send user creation email: " . $e->getMessage());
                    }
                }
            } else {
                $_SESSION['error'] = 'Failed to add user.';
            }
        }
        return $this->redirect('admin/users');
    }

    public function get($id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            json_response(['status' => 'error', 'message' => 'User not found']);
            exit;
        }

        $roleModel = new Role();
        $role = $roleModel->find($user->role_id);
        $user->role_name = $role ? $role->name : '';

        $permModel = new Permission();
        if ($user->role_id == 1) {
            $allPerms = $permModel->all();
            $user->permission_ids = array_map(function($p) { return $p->id; }, $allPerms);
        } else {
            $userPerms = $permModel->getUserPermissions($user->id);
            $user->permission_ids = array_map(function($p) { return $p->id; }, $userPerms);
        }

        json_response(['status' => 'success', 'data' => $user]);
        exit;
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get old data for audit log
            $oldUser = $this->userModel->find($id);
            $permModel = new Permission();
            $oldPermissions = $permModel->getUserPermissions($oldUser->id);
            
            $data = [
                'name' => $_POST['name'] ?? '',
                'email' => $_POST['email'] ?? '',
                'role_id' => !empty($_POST['role_id']) ? $_POST['role_id'] : null,
                'status' => $_POST['status'] ?? 'active'
            ];

            $password = $_POST['password'] ?? '';
            if (!empty($password)) {
                if (strlen($password) < 6) {
                    $_SESSION['error'] = 'Password must be at least 6 characters.';
                    return $this->redirect('admin/users');
                }
                $data['password'] = password_hash($password, PASSWORD_DEFAULT);
            }

            if ($this->userModel->update($id, $data)) {
                $permModel->removeAllFromUser($oldUser->id);
                $newPermissions = [];
                if (!empty($_POST['permissions'])) {
                    foreach ($_POST['permissions'] as $permId) {
                        $permModel->assignToUser($permId, $oldUser->id);
                    }
                    $newPermissions = $_POST['permissions'];
                }
                
                // Log the update with detailed changes
                $oldData = [
                    'name' => $oldUser->name,
                    'email' => $oldUser->email,
                    'role_id' => $oldUser->role_id,
                    'status' => $oldUser->status,
                    'permissions' => array_map(function($p) { return $p->id; }, $oldPermissions),
                    'password_changed' => false
                ];
                
                $newData = [
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'role_id' => $data['role_id'],
                    'status' => $data['status'],
                    'permissions' => $newPermissions,
                    'password_changed' => !empty($password)
                ];
                
                AuditLog::log('update', 'users', $oldUser->id, $oldData, $newData);
                
                $_SESSION['success'] = 'User updated successfully.';

                // Send email with new password if it was changed and user is active
                if (!empty($password) && $data['status'] === 'active') {
                    $ngoName = $this->globalSettings['ngo_name'] ?? 'NGO Management System';
                    $loginUrl = url('auth');
                    $subject = "Your Password Has Been Updated | {$ngoName}";
                    
                    $message = "
                    <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden;'>
                        <div style='background-color: #1a44a6; padding: 20px; text-align: center;'>
                            <h2 style='color: white; margin: 0;'>Password Updated</h2>
                        </div>
                        <div style='padding: 20px; background-color: #ffffff;'>
                            <p style='font-size: 16px; color: #333;'>Dear <strong>" . htmlspecialchars($data['name']) . "</strong>,</p>
                            <p style='font-size: 15px; color: #444; line-height: 1.6;'>Your administrative account password at <strong>{$ngoName}</strong> has been updated by an administrator. Below are your new login credentials:</p>
                            
                            <div style='background-color: #f8f9fa; padding: 15px; border-radius: 6px; margin: 20px 0;'>
                                <p style='margin: 0 0 10px 0; font-size: 14px;'><strong>Login URL:</strong> <a href='{$loginUrl}' style='color: #1a44a6;'>{$loginUrl}</a></p>
                                <p style='margin: 0 0 10px 0; font-size: 14px;'><strong>Email / Username:</strong> " . htmlspecialchars($data['email']) . "</p>
                                <p style='margin: 0; font-size: 14px;'><strong>New Password:</strong> " . htmlspecialchars($password) . "</p>
                            </div>
                            
                            <p style='font-size: 14px; color: #666;'><em>Please log in and change your password to ensure your account remains secure.</em></p>
                        </div>
                    </div>";

                    try {
                        $mailer = new \App\Core\Mailer($this->globalSettings);
                        $mailSent = $mailer->send($data['email'], $subject, $message, true);
                        if ($mailSent) {
                            $_SESSION['success'] .= ' New login details have been emailed to the user.';
                        } else {
                            $_SESSION['success'] .= ' However, the email failed to send (check SMTP settings).';
                        }
                    } catch (\Exception $e) {}
                }
            } else {
                $_SESSION['error'] = 'Failed to update user.';
            }
        }
        return $this->redirect('admin/users');
    }

    public function delete($id)
    {
        if ($id == $_SESSION['user_id']) {
            $_SESSION['error'] = 'You cannot delete your own account.';
            return $this->redirect('admin/users');
        }

        // Get user data before deletion for audit log
        $user = $this->userModel->find($id);
        $permModel = new Permission();
        $userPermissions = $permModel->getUserPermissions($user->id);
        
        $oldData = [
            'name' => $user->name,
            'email' => $user->email,
            'role_id' => $user->role_id,
            'status' => $user->status,
            'permissions' => array_map(function($p) { return $p->id; }, $userPermissions)
        ];

        if ($this->userModel->delete($id)) {
            // Log the deletion
            AuditLog::log('delete', 'users', $user->id, $oldData, null);
            
            $_SESSION['success'] = 'User deleted successfully.';
        } else {
            $_SESSION['error'] = 'Failed to delete user.';
        }
        return $this->redirect('admin/users');
    }

    public function storeRole()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            if (!empty($name)) {
                $roleModel = new Role();
                $roleModel->create(['name' => $name]);
                $roleId = $roleModel->lastInsertId();
                
                // Log role creation
                AuditLog::log('create', 'roles', $roleId, null, ['name' => $name]);
                
                $_SESSION['success'] = 'Role added successfully.';
            } else {
                $_SESSION['error'] = 'Please enter a role name.';
            }
        }
        return $this->redirect('admin/users#modal-add-role');
    }

    public function deleteRole($id)
    {
        $roleModel = new Role();
        $role = $roleModel->find($id);
        if ($role && strtolower($role->name) !== 'super admin') {
            // Log role deletion
            AuditLog::log('delete', 'roles', $id, ['name' => $role->name], null);
            
            $roleModel->delete($id);
            $_SESSION['success'] = 'Role deleted successfully.';
        } else {
            $_SESSION['error'] = 'Cannot delete the Super Admin role.';
        }
        return $this->redirect('admin/users#modal-add-role');
    }

    public function toggleStatus($id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            $_SESSION['error'] = 'User not found.';
            return $this->redirect('admin/users');
        }

        if ($id == $_SESSION['user_id']) {
            $_SESSION['error'] = 'You cannot change your own status.';
            return $this->redirect('admin/users');
        }

        $newStatus = $user->status === 'active' ? 'inactive' : 'active';
        
        // Log status change
        AuditLog::log(
            'status_change',
            'users',
            $id,
            ['status' => $user->status, 'name' => $user->name, 'email' => $user->email],
            ['status' => $newStatus, 'name' => $user->name, 'email' => $user->email]
        );
        
        if ($this->userModel->update($id, ['status' => $newStatus])) {
            $_SESSION['success'] = "User status changed to {$newStatus}.";
        } else {
            $_SESSION['error'] = 'Failed to update user status.';
        }
        return $this->redirect('admin/users');
    }
}
