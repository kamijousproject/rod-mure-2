<?php
/**
 * DashboardController - User dashboard
 */

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Auth;
use App\Models\Car;
use App\Models\Inquiry;
use App\Models\User;

class DashboardController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
    }
    
    /**
     * Display dashboard
     */
    public function index(): void
    {
        $user = User::withStats(Auth::id());
        $unreadInquiries = Inquiry::countUnread(Auth::id());
        
        // Get recent cars (for sellers)
        $recentCars = [];
        if (Auth::isSeller()) {
            $result = Car::getByUser(Auth::id(), null, 1, 5);
            $recentCars = $result['items'];
        }
        
        // Get recent inquiries
        $inquiries = Inquiry::getForSeller(Auth::id(), null, 1, 5);
        
        $this->view('dashboard.index', [
            'title' => 'แดชบอร์ด',
            'user' => $user,
            'stats' => $user['stats'] ?? [],
            'unreadInquiries' => $unreadInquiries,
            'recentCars' => $recentCars,
            'recentInquiries' => $inquiries['items'],
        ]);
    }
}
