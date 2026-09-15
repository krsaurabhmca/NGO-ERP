<?php

namespace App\Core;
use App\Models\Setting;
use App\Models\Permission;
use App\Helpers\CryptoHelper;

class Controller
{
    protected $globalSettings = [];

    public function __construct()
    {
        // Load global settings once per request
        $settingModel = new Setting();
        $settings = $settingModel->all();
        foreach ($settings as $s) {
            $value = $s->key_value;
            if (CryptoHelper::isEncrypted($value)) {
                $decrypted = CryptoHelper::decrypt($value);
                if ($decrypted !== null) {
                    $value = $decrypted;
                }
            }
            $this->globalSettings[$s->key_name] = $value;
        }

        // Seed default permissions — only once via DB flag
        $permModel = new Permission();
        if (!$permModel->isSeeded()) {
            $permModel->seedDefaults();
        }
    }

    protected function checkModuleAccess($module)
    {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Access denied. Please login.';
            return $this->redirect('auth');
        }

        $roleId = $_SESSION['role_id'];
        $userId = $_SESSION['user_id'];

        // Super Admin (role_id = 1) has full access
        if ($roleId == 1) {
            return true;
        }

        $permModel = new Permission();

        // Check if user has individual permission override
        if ($permModel->userHasModuleAccess($userId, $module)) {
            return true;
        }

        // Fallback to role-based permission
        if (!$permModel->roleHasModuleAccess($roleId, $module)) {
            $_SESSION['error'] = 'You do not have permission to access this module.';
            return $this->redirect('admin/dashboard');
        }

        return true;
    }

    public function view($view, $data = [])
    {
        $data['globalSettings'] = $this->globalSettings;
        
        $file = BASE_PATH . "app/views/{$view}.php";
        if (!file_exists($file)) {
            error_log("View not found: {$view}.php");
            $this->loadErrorPage(500, 'Internal Server Error');
            exit;
        }
        extract($data, EXTR_SKIP);
        require_once $file;
    }

    public function redirect($url)
    {
        header("Location: " . url($url));
        exit;
    }

    protected function loadErrorPage($code, $message)
    {
        http_response_code($code);
        $file = BASE_PATH . "app/views/errors/{$code}.php";
        if (file_exists($file)) {
            require $file;
        } else {
            echo "<!DOCTYPE html><html><head><title>{$code} - {$message}</title><style>body{font-family:sans-serif;text-align:center;padding:4rem;color:#666;}h1{font-size:4rem;color:#dc3545;}</style></head><body><h1>{$code}</h1><p>{$message}</p></body></html>";
        }
    }
}
