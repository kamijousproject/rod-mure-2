<?php
/**
 * Admin ModelController - Manage car models
 */

namespace App\Controllers\Admin;

use App\Core\BaseController;
use App\Core\CSRF;
use App\Core\Session;
use App\Models\Brand;
use App\Models\CarModel;

class ModelController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->requireAdmin();
    }
    
    /**
     * List models
     */
    public function index(): void
    {
        $brandId = $this->input('brand_id');
        
        if ($brandId) {
            $models = CarModel::withCarCount((int)$brandId);
        } else {
            $models = CarModel::withBrand();
        }
        
        $brands = Brand::getActive();
        
        $this->view('admin.models.index', [
            'title' => 'จัดการรุ่นรถ',
            'models' => $models,
            'brands' => $brands,
            'selectedBrand' => $brandId,
        ]);
    }
    
    /**
     * Create model
     */
    public function store(): void
    {
        CSRF::check();
        
        $brandId = (int)$this->input('brand_id');
        $name = $this->input('name');
        
        $errors = $this->validate([
            'brand_id' => $brandId,
            'name' => $name,
        ], [
            'brand_id' => 'required|integer',
            'name' => 'required|min:1|max:100',
        ]);
        
        if (!empty($errors)) {
            Session::setErrors($errors);
            $this->redirect('/admin/models');
        }
        
        CarModel::create([
            'brand_id' => $brandId,
            'name' => $name,
            'slug' => CarModel::generateSlug($name, $brandId),
            'status' => 'active',
        ]);
        
        Session::setFlash('success', 'เพิ่มรุ่นสำเร็จ');
        $this->redirect('/admin/models?brand_id=' . $brandId);
    }
    
    /**
     * Update model
     */
    public function update(string $id): void
    {
        CSRF::check();
        
        $model = CarModel::find((int)$id);
        
        if (!$model) {
            Session::setFlash('error', 'ไม่พบรุ่น');
            $this->redirect('/admin/models');
        }
        
        $name = $this->input('name');
        $status = $this->input('status', 'active');
        
        CarModel::update((int)$id, [
            'name' => $name,
            'slug' => CarModel::generateSlug($name, $model['brand_id'], (int)$id),
            'status' => $status,
        ]);
        
        Session::setFlash('success', 'อัปเดตรุ่นสำเร็จ');
        $this->redirect('/admin/models?brand_id=' . $model['brand_id']);
    }
    
    /**
     * Delete model
     */
    public function destroy(string $id): void
    {
        CSRF::check();
        
        $model = CarModel::find((int)$id);
        $brandId = $model['brand_id'] ?? '';
        
        CarModel::delete((int)$id);
        
        Session::setFlash('success', 'ลบรุ่นสำเร็จ');
        $this->redirect('/admin/models?brand_id=' . $brandId);
    }
}
