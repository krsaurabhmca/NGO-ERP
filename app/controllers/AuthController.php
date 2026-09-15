<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Models\Member;
use App\Models\LoginAttempt;
use App\Models\AuditLog;

class AuthController extends Controller
{
    protected $userModel;
    protected $loginAttemptModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new User();
        $this->loginAttemptModel = new LoginAttempt();
    }

    public function showLogin()
    {
        if (isset($_SESSION['user_id'])) {
            return $this->redirect('admin/dashboard');
        }
        if (isset($_SESSION['member_id'])) {
            return $this->redirect('member/dashboard');
        }

        // Check for account lockout
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        $lockoutMinutes = 30;
        $lockoutThreshold = 10;
        $ipAttempts = $this->loginAttemptModel->countRecentByIp($ip, $lockoutMinutes);

        if ($ipAttempts >= $lockoutThreshold) {
            return $this->view('auth/login', [
                'title' => 'Login',
                'locked' => true,
                'lockout_minutes' => $lockoutMinutes,
            ]);
        }

        // Check if CAPTCHA is needed
        $captchaThreshold = 3;
        $needsCaptcha = $ipAttempts >= $captchaThreshold;

        return $this->view('auth/login', [
            'title' => 'Login',
            'needs_captcha' => $needsCaptcha,
            'captcha_question' => $needsCaptcha ? captcha_question() : null,
        ]);
    }

    public function login()
    {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';

        if (empty($email) || empty($password)) {
            $_SESSION['error'] = 'Please fill in all fields';
            return $this->redirect('auth');
        }

        // --- Rate Limiting Checks ---

        $windowMinutes = 15;
        $maxAttempts = 5;
        $lockoutThreshold = 10;
        $captchaThreshold = 3;

        // Check IP-level lockout (10 failed attempts in 30 min)
        $ipAttemptsWindow = 30;
        $ipAttempts = $this->loginAttemptModel->countRecentByIp($ip, $ipAttemptsWindow);
        if ($ipAttempts >= $lockoutThreshold) {
            $_SESSION['error'] = 'Too many failed attempts. Account locked for ' . $ipAttemptsWindow . ' minutes.';
            return $this->redirect('auth');
        }

        // Check per-email rate limit (5 attempts per 15 min per IP/email)
        $recentAttempts = $this->loginAttemptModel->countRecent($email, $ip, $windowMinutes);
        if ($recentAttempts >= $maxAttempts) {
            $_SESSION['error'] = 'Too many login attempts. Please try again later.';
            $this->loginAttemptModel->record($email, $ip);
            return $this->redirect('auth');
        }

        // Check if CAPTCHA is required (3+ failed attempts)
        if ($ipAttempts >= $captchaThreshold) {
            $captchaAnswer = $_POST['_captcha'] ?? '';
            if (!verify_captcha($captchaAnswer)) {
                $_SESSION['error'] = 'Incorrect CAPTCHA answer. Please try again.';
                $this->loginAttemptModel->record($email, $ip);
                return $this->redirect('auth');
            }
        }

        // Try admin login first
        $user = $this->userModel->findByEmail($email);
        if ($user && password_verify($password, $user->password)) {
            if ($user->status !== 'active') {
                $_SESSION['error'] = 'Invalid credentials. Please check your email and password.';
                $this->loginAttemptModel->record($email, $ip);
                return $this->redirect('auth');
            }

            // Clear failed attempts on successful login
            $this->loginAttemptModel->clearForEmail($email);
            $this->loginAttemptModel->clearForIp($ip);

            session_regenerate_id(true);
            $_SESSION['user_id'] = $user->id;
            $_SESSION['user_name'] = $user->name;
            $_SESSION['user_email'] = $user->email ?? $email;
            $_SESSION['role_id'] = $user->role_id ?? 0;
            $_SESSION['login_type'] = 'admin';
            $_SESSION['welcome_toast'] = 'Welcome back, ' . $user->name . '!';

            $this->userModel->updateLoginTime($user->id);
            AuditLog::log('login', 'auth', $user->id, null, ['login_type' => 'admin', 'email' => $email]);
            return $this->redirect('admin/dashboard');
        }

        // Try member login
        $memberModel = new Member();
        $members = $memberModel->where('email', $email);
        if (!empty($members)) {
            $member = $members[0];
            
            if (empty($member->password)) {
                $_SESSION['error'] = 'Your account password has not been generated yet. Please contact the administrator to send your login credentials.';
                $this->loginAttemptModel->record($email, $ip);
                return $this->redirect('auth');
            }

            if (password_verify($password, $member->password)) {
                if ($member->status !== 'active') {
                    $_SESSION['error'] = 'Invalid credentials. Please check your email and password.';
                    $this->loginAttemptModel->record($email, $ip);
                    return $this->redirect('auth');
                }

                // Clear failed attempts on successful login
                $this->loginAttemptModel->clearForEmail($email);
                $this->loginAttemptModel->clearForIp($ip);

                session_regenerate_id(true);
                $_SESSION['member_id'] = $member->id;
                $_SESSION['member_name'] = $member->name;
                $_SESSION['member_email'] = $member->email;
                $_SESSION['login_type'] = 'member';
                $_SESSION['welcome_toast'] = 'Welcome back, ' . $member->name . '!';

                AuditLog::log('login', 'auth', $member->id, null, ['login_type' => 'member', 'email' => $email]);
                return $this->redirect('member/dashboard');
            }
        }

        // Failed login — record attempt
        $this->loginAttemptModel->record($email, $ip);

        $_SESSION['error'] = 'Invalid credentials. Please check your email and password.';
        return $this->redirect('auth');
    }

    public function logout()
    {
        $userId = $_SESSION['user_id'] ?? null;
        $memberId = $_SESSION['member_id'] ?? null;
        $loginType = $_SESSION['login_type'] ?? '';
        AuditLog::log('logout', 'auth', $userId ?? $memberId, null, ['login_type' => $loginType]);
        session_regenerate_id(true);
        session_destroy();
        return $this->redirect('auth');
    }
}
