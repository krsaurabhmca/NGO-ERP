<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\AuditLog;

class AdminController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('auth');
        }
        $this->checkModuleAccess('dashboard');
    }

    public function dashboard()
    {
        $donationModel = new \App\Models\Donation();
        $expenseModel = new \App\Models\Expense();
        $projectModel = new \App\Models\Project();
        $memberModel = new \App\Models\Member();
        $donorModel = new \App\Models\Donor();
        $campaignModel = new \App\Models\Campaign();
        $beneficiaryModel = new \App\Models\Beneficiary();
        $contactModel = new \App\Models\Contact();

        $totalDonations = $donationModel->totalCompleted();
        $totalExpenses = $expenseModel->totalByDateRange('2000-01-01', date('Y-m-d'));
        $balance = $totalDonations - $totalExpenses;
        $pendingDonations = $donationModel->totalByDateRange('2000-01-01', date('Y-m-d'), 'pending');

        $recentDonations = $donationModel->all();
        usort($recentDonations, function($a, $b) { return strtotime($b->created_at) - strtotime($a->created_at); });
        $recentDonations = array_slice($recentDonations, 0, 5);

        $recentProjects = $projectModel->all();
        usort($recentProjects, function($a, $b) { return $b->id - $a->id; });
        $recentProjects = array_slice($recentProjects, 0, 5);

        $stats = [
            'members' => $memberModel->count(),
            'donors' => $donorModel->count(),
            'projects' => $projectModel->count(),
            'ongoing_projects' => count($projectModel->where('status', 'ongoing')),
            'campaigns' => count($campaignModel->where('status', 'active')),
            'beneficiaries' => $beneficiaryModel->count(),
            'donations' => $totalDonations,
            'expenses' => $totalExpenses,
            'balance' => $balance,
            'pending_donations' => $pendingDonations,
            'pending_contacts' => count($contactModel->where('status', 'unread')),
        ];

        return $this->view('admin/dashboard', [
            'title' => 'Admin Dashboard',
            'stats' => $stats,
            'recentDonations' => $recentDonations,
            'recentProjects' => $recentProjects,
        ]);
    }

    public function profile()
    {
        $userModel = new User();
        $user = $userModel->find($_SESSION['user_id']);
        if (!$user) {
            $_SESSION['error'] = 'User not found.';
            return $this->redirect('admin/dashboard');
        }

        $roleModel = new Role();
        $role = $roleModel->find($user->role_id);
        $user->role_name = $role ? $role->name : '';

        return $this->view('admin/profile/index', [
            'title' => 'My Profile',
            'user' => $user,
        ]);
    }

    public function updateProfile()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect('admin/profile');
        }

        $userModel = new User();
        $userId = $_SESSION['user_id'];

        $data = [
            'name' => $_POST['name'] ?? '',
            'email' => $_POST['email'] ?? '',
        ];

        $password = $_POST['password'] ?? '';
        if (!empty($password)) {
            if (strlen($password) < 6) {
                $_SESSION['error'] = 'Password must be at least 6 characters.';
                return $this->redirect('admin/profile');
            }
            $confirmPassword = $_POST['confirm_password'] ?? '';
            if ($password !== $confirmPassword) {
                $_SESSION['error'] = 'Passwords do not match.';
                return $this->redirect('admin/profile');
            }
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        if ($userModel->update($userId, $data)) {
            $_SESSION['user_name'] = $data['name'];
            AuditLog::log('update', 'profile', $userId, null, ['name' => $data['name'], 'email' => $data['email'], 'password_changed' => !empty($password)]);
            $_SESSION['success'] = 'Profile updated successfully.';
        } else {
            $_SESSION['error'] = 'Failed to update profile.';
        }

        return $this->redirect('admin/profile');
    }
}
