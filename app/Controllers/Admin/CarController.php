<?php
/**
 * Admin CarController - Manage car listings
 */

namespace App\Controllers\Admin;

use App\Core\BaseController;
use App\Core\CSRF;
use App\Core\Session;
use App\Core\Database;
use App\Models\Car;
use App\Models\CarImage;

class CarController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->requireAdmin();
    }
    
    /**
     * List all cars
     */
    public function index(): void
    {
        $status = $this->input('status');
        $search = $this->input('q');
        $page = (int)$this->input('page', 1);
        
        $conditions = [];
        $params = [];
        
        if ($status) {
            $conditions[] = "status = ?";
            $params[] = $status;
        }
        
        if ($search) {
            $conditions[] = "(title LIKE ? OR description LIKE ?)";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }
        
        $where = !empty($conditions) ? implode(' AND ', $conditions) : '';
        
        $result = Car::paginate($page, 20, $where, $params, 'created_at DESC');
        
        $this->view('admin.cars.index', [
            'title' => 'จัดการประกาศ',
            'cars' => $result['items'],
            'pagination' => $result,
            'currentStatus' => $status,
            'search' => $search,
            'statuses' => Car::STATUSES,
        ]);
    }
    
    /**
     * Show car details
     */
    public function show(string $id): void
    {
        $car = Car::findWithRelations((int)$id);
        
        if (!$car) {
            Session::setFlash('error', 'ไม่พบประกาศ');
            $this->redirect('/admin/cars');
        }
        
        $this->view('admin.cars.show', [
            'title' => 'รายละเอียดประกาศ',
            'car' => $car,
        ]);
    }
    
    /**
     * Approve car listing
     */
    public function approve(string $id): void
    {
        CSRF::check();
        
        Car::update((int)$id, ['status' => Car::STATUS_PUBLISHED]);
        
        $this->logAction('car_approved', $id);
        
        Session::setFlash('success', 'อนุมัติประกาศสำเร็จ');
        $this->redirect('/admin/cars');
    }
    
    /**
     * Reject car listing
     */
    public function reject(string $id): void
    {
        CSRF::check();
        
        $reason = $this->input('reason', 'ไม่ผ่านการตรวจสอบ');
        
        Car::update((int)$id, ['status' => Car::STATUS_REJECTED]);
        
        $this->logAction('car_rejected', $id, ['reason' => $reason]);
        
        Session::setFlash('success', 'ปฏิเสธประกาศสำเร็จ');
        $this->redirect('/admin/cars');
    }
    
    /**
     * Toggle featured status
     */
    public function feature(string $id): void
    {
        CSRF::check();
        
        $car = Car::find((int)$id);
        
        if (!$car) {
            $this->json(['success' => false, 'message' => 'ไม่พบประกาศ'], 404);
        }
        
        $isFeatured = $car['is_featured'] ? 0 : 1;
        Car::update((int)$id, ['is_featured' => $isFeatured]);
        
        $this->json([
            'success' => true,
            'message' => $isFeatured ? 'ตั้งเป็นประกาศแนะนำสำเร็จ' : 'ยกเลิกประกาศแนะนำสำเร็จ',
            'is_featured' => (bool)$isFeatured,
        ]);
    }
    
    /**
     * Delete car listing
     */
    public function destroy(string $id): void
    {
        CSRF::check();
        
        CarImage::deleteAllForCar((int)$id);
        Car::delete((int)$id);
        
        $this->logAction('car_deleted', $id);
        
        Session::setFlash('success', 'ลบประกาศสำเร็จ');
        $this->redirect('/admin/cars');
    }
    
    /**
     * Log admin action
     */
    private function logAction(string $action, string $targetId, array $data = []): void
    {
        Database::query(
            "INSERT INTO admin_logs (admin_id, action, target_type, target_id, data, ip_address, created_at) 
             VALUES (?, ?, 'car', ?, ?, ?, NOW())",
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
