<?php
/**
 * Admin BrandController - Manage car brands
 */

namespace App\Controllers\Admin;

use App\Core\BaseController;
use App\Core\CSRF;
use App\Core\Session;
use App\Models\Brand;

class BrandController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->requireAdmin();
    }
    
    /**
     * List brands
     */
    public function index(): void
    {
        $brands = Brand::withCarCount();
        
        $this->view('admin.brands.index', [
            'title' => 'จัดการยี่ห้อรถ',
            'brands' => $brands,
        ]);
    }
    
    /**
     * Create brand
     */
    public function store(): void
    {
        CSRF::check();
        
        $name = $this->input('name');
        
        $errors = $this->validate(['name' => $name], [
            'name' => 'required|min:2|max:100',
        ]);
        
        if (!empty($errors)) {
            Session::setErrors($errors);
            $this->redirect('/admin/brands');
        }
        
        Brand::create([
            'name' => $name,
            'slug' => Brand::generateSlug($name),
            'status' => 'active',
        ]);
        
        Session::setFlash('success', 'เพิ่มยี่ห้อสำเร็จ');
        $this->redirect('/admin/brands');
    }
    
    /**
     * Update brand
     */
    public function update(string $id): void
    {
        CSRF::check();
        
        $name = $this->input('name');
        $status = $this->input('status', 'active');
        
        $errors = $this->validate(['name' => $name], [
            'name' => 'required|min:2|max:100',
        ]);
        
        if (!empty($errors)) {
            Session::setErrors($errors);
            $this->redirect('/admin/brands');
        }
        
        Brand::update((int)$id, [
            'name' => $name,
            'slug' => Brand::generateSlug($name, (int)$id),
            'status' => $status,
        ]);
        
        Session::setFlash('success', 'อัปเดตยี่ห้อสำเร็จ');
        $this->redirect('/admin/brands');
    }
    
    /**
     * Delete brand
     */
    public function destroy(string $id): void
    {
        CSRF::check();
        
        Brand::delete((int)$id);
        
        Session::setFlash('success', 'ลบยี่ห้อสำเร็จ');
        $this->redirect('/admin/brands');
    }
}
