<?php
/**
 * Admin UserController - Manage users
 */

namespace App\Controllers\Admin;

use App\Core\BaseController;
use App\Core\CSRF;
use App\Core\Session;
use App\Core\Database;
use App\Models\User;

class UserController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->requireAdmin();
    }
    
    /**
     * List users
     */
    public function index(): void
    {
        $search = $this->input('q', '');
        $page = (int)$this->input('page', 1);
        
        $result = User::search($search, $page, 20);
        
        $this->view('admin.users.index', [
            'title' => 'จัดการผู้ใช้',
            'users' => $result['items'],
            'pagination' => $result,
            'search' => $search,
        ]);
    }
    
    /**
     * Show user details
     */
    public function show(string $id): void
    {
        $user = User::withStats((int)$id);
        
        if (!$user) {
            Session::setFlash('error', 'ไม่พบผู้ใช้');
            $this->redirect('/admin/users');
        }
        
        $this->view('admin.users.show', [
            'title' => 'รายละเอียดผู้ใช้',
            'user' => $user,
        ]);
    }
    
    /**
     * Update user status
     */
    public function updateStatus(string $id): void
    {
        CSRF::check();
        
        $status = $this->input('status');
        
        if (!in_array($status, ['active', 'suspended', 'banned'])) {
            $this->json(['success' => false, 'message' => 'สถานะไม่ถูกต้อง'], 400);
        }
        
        User::update((int)$id, ['status' => $status]);
        
        $this->logAction('user_status_changed', $id, ['status' => $status]);
        
        $this->json(['success' => true, 'message' => 'อัปเดตสถานะสำเร็จ']);
    }
    
    /**
     * Update user role
     */
    public function updateRole(string $id): void
    {
        CSRF::check();
        
        $role = $this->input('role');
        
        if (!in_array($role, ['buyer', 'seller', 'admin'])) {
            $this->json(['success' => false, 'message' => 'บทบาทไม่ถูกต้อง'], 400);
        }
        
        User::update((int)$id, ['role' => $role]);
        
        $this->logAction('user_role_changed', $id, ['role' => $role]);
        
        $this->json(['success' => true, 'message' => 'อัปเดตบทบาทสำเร็จ']);
    }
    
    /**
     * Log admin action
     */
    private function logAction(string $action, string $targetId, array $data = []): void
    {
        Database::query(
            "INSERT INTO admin_logs (admin_id, action, target_type, target_id, data, ip_address, created_at) 
             VALUES (?, ?, 'user', ?, ?, ?, NOW())",
            [
                \App\Core\Auth::id(),
                $action,
                $targetId,
                json_encode($data),
                get_ip(),
            ]
        );
    }
}
