<?php
/**
 * Admin DashboardController - Admin dashboard
 */

namespace App\Controllers\Admin;

use App\Core\BaseController;
use App\Core\Auth;
use App\Models\Car;
use App\Models\User;
use App\Models\Inquiry;

class DashboardController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->requireAdmin();
    }
    
    /**
     * Display admin dashboard
     */
    public function index(): void
    {
        $carStats = Car::getStats();
        $inquiryStats = Inquiry::getStats();
        
        // User stats
        $userStats = [
            'total' => User::count(),
            'buyers' => User::count("role = 'buyer'"),
            'sellers' => User::count("role = 'seller'"),
            'admins' => User::count("role = 'admin'"),
        ];
        
        // Recent activities
        $recentCars = Car::paginate(1, 10, '', [], 'created_at DESC');
        $recentUsers = User::search('', 1, 10);
        
        $this->view('admin.dashboard', [
            'title' => 'Admin Dashboard',
            'carStats' => $carStats,
            'userStats' => $userStats,
            'inquiryStats' => $inquiryStats,
            'recentCars' => $recentCars['items'],
            'recentUsers' => $recentUsers['items'],
        ]);
    }
}
