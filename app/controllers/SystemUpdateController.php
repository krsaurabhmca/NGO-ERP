<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Setting;
use ZipArchive;

class SystemUpdateController extends Controller
{
    protected $settingModel;

    public function __construct()
    {
        parent::__construct();
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('auth');
        }
        $this->checkModuleAccess('settings');
        $this->settingModel = new Setting();
    }

    private function getGithubRepoUrl()
    {
        $settings = $this->settingModel->getAllByGroup('organization');
        foreach ($settings as $s) {
            if ($s->key_name === 'github_repo_url') {
                return $s->key_value;
            }
        }
        return '';
    }

    private function getLatestRelease($repoUrl)
    {
        if (empty($repoUrl)) return null;
        
        // Extract owner and repo from URL
        $path = parse_url($repoUrl, PHP_URL_PATH);
        $path = trim($path, '/');
        // Remove .git if present
        $path = preg_replace('/\.git$/', '', $path);
        
        $apiUrl = "https://api.github.com/repos/{$path}/releases/latest";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'NGO-Management-System-Updater');
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode === 200 && $response) {
            return json_decode($response, true);
        }
        
        return null;
    }

    public function index()
    {
        $repoUrl = $this->getGithubRepoUrl();
        $latestRelease = $this->getLatestRelease($repoUrl);
        
        return $this->view('admin/settings/update', [
            'title' => 'System Updates',
            'repoUrl' => $repoUrl,
            'currentVersion' => defined('APP_VERSION') ? APP_VERSION : '1.0.0',
            'latestRelease' => $latestRelease
        ]);
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_csrf('admin/settings/update')) {
            $_SESSION['error'] = 'Invalid request.';
            $this->redirect('admin/settings/update');
        }

        $repoUrl = $this->getGithubRepoUrl();
        $latestRelease = $this->getLatestRelease($repoUrl);

        if (!$latestRelease || empty($latestRelease['zipball_url'])) {
            $_SESSION['error'] = 'Could not fetch the latest release from GitHub.';
            $this->redirect('admin/settings/update');
        }

        $zipUrl = $latestRelease['zipball_url'];
        
        // Setup temp directory
        $tempDir = STORAGE_PATH . 'temp/';
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }
        
        $zipFile = $tempDir . 'update.zip';
        $extractPath = $tempDir . 'update_extracted/';
        
        // Download Zip
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $zipUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'NGO-Management-System-Updater');
        $zipData = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($zipData === false || empty($zipData)) {
            $_SESSION['error'] = 'Failed to download update file. ' . $error;
            $this->redirect('admin/settings/update');
        }

        file_put_contents($zipFile, $zipData);

        // Extract Zip
        $zip = new ZipArchive;
        if ($zip->open($zipFile) === TRUE) {
            if (!is_dir($extractPath)) {
                mkdir($extractPath, 0755, true);
            }
            $zip->extractTo($extractPath);
            $zip->close();
            
            // GitHub wraps everything in a root folder inside the zip
            $extractedFolders = glob($extractPath . '*', GLOB_ONLYDIR);
            if (!empty($extractedFolders)) {
                $sourcePath = $extractedFolders[0] . '/';
                
                // Copy files
                $this->recursiveCopy($sourcePath, BASE_PATH);
                
                $_SESSION['success'] = 'System successfully updated to ' . $latestRelease['tag_name'];
                
                // Update APP_VERSION in config/config.php if possible
                $this->updateAppVersion(trim($latestRelease['tag_name'], 'v'));
            } else {
                $_SESSION['error'] = 'Extraction failed or folder structure unrecognized.';
            }
        } else {
            $_SESSION['error'] = 'Failed to open the downloaded zip file.';
        }

        // Cleanup
        if (file_exists($zipFile)) unlink($zipFile);
        if (is_dir($extractPath)) $this->deleteDirectory($extractPath);

        $this->redirect('admin/settings/update');
    }

    private function recursiveCopy($src, $dst)
    {
        $dir = opendir($src);
        @mkdir($dst);
        
        $ignorePaths = ['.env', 'storage', 'uploads', '.git', 'vendor', 'composer.lock'];
        
        while (( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if (in_array($file, $ignorePaths)) continue;
                
                $srcFile = $src . '/' . $file;
                $dstFile = $dst . '/' . $file;
                
                if ( is_dir($srcFile) ) {
                    $this->recursiveCopy($srcFile, $dstFile);
                } else {
                    copy($srcFile, $dstFile);
                }
            }
        }
        closedir($dir);
    }

    private function deleteDirectory($dir) {
        if (!file_exists($dir)) {
            return true;
        }
        if (!is_dir($dir)) {
            return unlink($dir);
        }
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }
            if (!$this->deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }
        return rmdir($dir);
    }
    
    private function updateAppVersion($newVersion)
    {
        $configFile = BASE_PATH . 'config/config.php';
        if (file_exists($configFile)) {
            $content = file_get_contents($configFile);
            $content = preg_replace("/define\('APP_VERSION',\s*'.*?'\);/", "define('APP_VERSION', '$newVersion');", $content);
            file_put_contents($configFile, $content);
        }
    }
}
