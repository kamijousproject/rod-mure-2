<?php
/**
 * Admin ReportController - Reports and CSV export
 */

namespace App\Controllers\Admin;

use App\Core\BaseController;
use App\Core\Database;
use App\Models\Car;
use App\Models\User;

class ReportController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->requireAdmin();
    }
    
    /**
     * Show reports page
     */
    public function index(): void
    {
        $period = $this->input('period', '30');
        $startDate = date('Y-m-d', strtotime("-{$period} days"));
        
        // Cars by status
        $carsByStatus = Database::fetchAll(
            "SELECT status, COUNT(*) as count FROM cars GROUP BY status"
        );
        
        // Cars created per day
        $carsPerDay = Database::fetchAll(
            "SELECT DATE(created_at) as date, COUNT(*) as count 
             FROM cars 
             WHERE created_at >= ? 
             GROUP BY DATE(created_at) 
             ORDER BY date",
            [$startDate]
        );
        
        // Users by role
        $usersByRole = Database::fetchAll(
            "SELECT role, COUNT(*) as count FROM users GROUP BY role"
        );
        
        // Top brands
        $topBrands = Database::fetchAll(
            "SELECT b.name, COUNT(c.id) as count 
             FROM brands b 
             LEFT JOIN cars c ON c.brand_id = b.id 
             GROUP BY b.id 
             ORDER BY count DESC 
             LIMIT 10"
        );
        
        // Recent inquiries stats
        $inquiryStats = Database::fetch(
            "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN created_at >= ? THEN 1 ELSE 0 END) as recent
             FROM inquiries",
            [$startDate]
        );
        
        $this->view('admin.reports.index', [
            'title' => 'รายงาน',
            'period' => $period,
            'carsByStatus' => $carsByStatus,
            'carsPerDay' => $carsPerDay,
            'usersByRole' => $usersByRole,
            'topBrands' => $topBrands,
            'inquiryStats' => $inquiryStats,
        ]);
    }
    
    /**
     * Export data to CSV
     */
    public function export(): void
    {
        $type = $this->input('type', 'cars');
        
        switch ($type) {
            case 'cars':
                $this->exportCars();
                break;
            case 'users':
                $this->exportUsers();
                break;
            case 'inquiries':
                $this->exportInquiries();
                break;
            default:
                $this->redirect('/admin/reports');
        }
    }
    
    /**
     * Export cars to CSV
     */
    private function exportCars(): void
    {
        $cars = Database::fetchAll(
            "SELECT c.id, c.title, b.name as brand, m.name as model, 
                    c.year, c.price, c.mileage, c.transmission, c.fuel_type,
                    c.province, c.status, c.views, c.created_at,
                    u.name as seller_name, u.email as seller_email
             FROM cars c
             LEFT JOIN brands b ON b.id = c.brand_id
             LEFT JOIN models m ON m.id = c.model_id
             LEFT JOIN users u ON u.id = c.user_id
             ORDER BY c.created_at DESC"
        );
        
        $headers = ['ID', 'Title', 'Brand', 'Model', 'Year', 'Price', 'Mileage', 
                    'Transmission', 'Fuel', 'Province', 'Status', 'Views', 'Created', 
                    'Seller', 'Seller Email'];
        
        $this->outputCsv('cars_export_' . date('Y-m-d') . '.csv', $headers, $cars);
    }
    
    /**
     * Export users to CSV
     */
    private function exportUsers(): void
    {
        $users = Database::fetchAll(
            "SELECT id, name, email, phone, role, status, 
                    province, created_at, last_login
             FROM users
             ORDER BY created_at DESC"
        );
        
        $headers = ['ID', 'Name', 'Email', 'Phone', 'Role', 'Status', 
                    'Province', 'Created', 'Last Login'];
        
        $this->outputCsv('users_export_' . date('Y-m-d') . '.csv', $headers, $users);
    }
    
    /**
     * Export inquiries to CSV
     */
    private function exportInquiries(): void
    {
        $inquiries = Database::fetchAll(
            "SELECT i.id, c.title as car_title, i.name, i.email, i.phone,
                    i.message, i.status, i.created_at,
                    u.name as seller_name
             FROM inquiries i
             LEFT JOIN cars c ON c.id = i.car_id
             LEFT JOIN users u ON u.id = i.receiver_id
             ORDER BY i.created_at DESC"
        );
        
        $headers = ['ID', 'Car', 'Name', 'Email', 'Phone', 'Message', 
                    'Status', 'Created', 'Seller'];
        
        $this->outputCsv('inquiries_export_' . date('Y-m-d') . '.csv', $headers, $inquiries);
    }
    
    /**
     * Output CSV file
     */
    private function outputCsv(string $filename, array $headers, array $data): void
    {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        // Add BOM for Excel UTF-8 compatibility
        echo "\xEF\xBB\xBF";
        
        $output = fopen('php://output', 'w');
        
        // Headers
        fputcsv($output, $headers);
        
        // Data
        foreach ($data as $row) {
            fputcsv($output, array_values($row));
        }
        
        fclose($output);
        exit;
    }
}
