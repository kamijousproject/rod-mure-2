<?php
/**
 * ProfileController - User profile management
 */

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Auth;
use App\Core\CSRF;
use App\Core\Session;
use App\Models\User;

class ProfileController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
    }
    
    /**
     * Display profile
     */
    public function show(): void
    {
        $user = User::withStats(Auth::id());
        
        $this->view('profile.show', [
            'title' => 'โปรไฟล์ของฉัน',
            'user' => $user,
        ]);
    }
    
    /**
     * Show edit profile form
     */
    public function edit(): void
    {
        $user = Auth::user();
        
        $this->view('profile.edit', [
            'title' => 'แก้ไขโปรไฟล์',
            'user' => $user,
            'provinces' => $this->getProvinces(),
        ]);
    }
    
    /**
     * Update profile
     */
    public function update(): void
    {
        CSRF::check();
        
        $data = [
            'name' => $this->input('name'),
            'phone' => $this->input('phone'),
            'address' => $this->input('address'),
            'province' => $this->input('province'),
        ];
        
        $errors = $this->validate($data, [
            'name' => 'required|min:2|max:100',
            'phone' => 'required|min:9|max:15',
        ]);
        
        if (!empty($errors)) {
            Session::setErrors($errors);
            Session::setOld($data);
            $this->redirect('/profile/edit');
        }
        
        User::update(Auth::id(), $data);
        Auth::refresh();
        
        Session::setFlash('success', 'อัปเดตโปรไฟล์สำเร็จ');
        $this->redirect('/profile');
    }
    
    /**
     * Update password
     */
    public function updatePassword(): void
    {
        CSRF::check();
        
        $currentPassword = $this->input('current_password');
        $newPassword = $this->input('password');
        $confirmPassword = $this->input('password_confirmation');
        
        // Verify current password
        $user = User::find(Auth::id());
        if (!password_verify($currentPassword, $user['password'])) {
            Session::setFlash('error', 'รหัสผ่านปัจจุบันไม่ถูกต้อง');
            $this->redirect('/profile/edit');
        }
        
        $errors = $this->validate([
            'password' => $newPassword,
            'password_confirmation' => $confirmPassword,
        ], [
            'password' => 'required|min:6|confirmed',
        ]);
        
        if (!empty($errors)) {
            Session::setErrors($errors);
            $this->redirect('/profile/edit');
        }
        
        User::updatePassword(Auth::id(), $newPassword);
        
        Session::setFlash('success', 'เปลี่ยนรหัสผ่านสำเร็จ');
        $this->redirect('/profile');
    }
    
    /**
     * Get provinces
     */
    private function getProvinces(): array
    {
        return [
            'กรุงเทพมหานคร', 'กระบี่', 'กาญจนบุรี', 'กาฬสินธุ์', 'กำแพงเพชร',
            'ขอนแก่น', 'จันทบุรี', 'ฉะเชิงเทรา', 'ชลบุรี', 'ชัยนาท',
            'ชัยภูมิ', 'ชุมพร', 'เชียงราย', 'เชียงใหม่', 'ตรัง',
            'ตราด', 'ตาก', 'นครนายก', 'นครปฐม', 'นครพนม',
            'นครราชสีมา', 'นครศรีธรรมราช', 'นครสวรรค์', 'นนทบุรี', 'นราธิวาส',
            'น่าน', 'บึงกาฬ', 'บุรีรัมย์', 'ปทุมธานี', 'ประจวบคีรีขันธ์',
            'ปราจีนบุรี', 'ปัตตานี', 'พระนครศรีอยุธยา', 'พังงา', 'พัทลุง',
            'พิจิตร', 'พิษณุโลก', 'เพชรบุรี', 'เพชรบูรณ์', 'แพร่',
            'พะเยา', 'ภูเก็ต', 'มหาสารคาม', 'มุกดาหาร', 'แม่ฮ่องสอน',
            'ยะลา', 'ยโสธร', 'ร้อยเอ็ด', 'ระนอง', 'ระยอง',
            'ราชบุรี', 'ลพบุรี', 'ลำปาง', 'ลำพูน', 'เลย',
            'ศรีสะเกษ', 'สกลนคร', 'สงขลา', 'สตูล', 'สมุทรปราการ',
            'สมุทรสงคราม', 'สมุทรสาคร', 'สระแก้ว', 'สระบุรี', 'สิงห์บุรี',
            'สุโขทัย', 'สุพรรณบุรี', 'สุราษฎร์ธานี', 'สุรินทร์', 'หนองคาย',
            'หนองบัวลำภู', 'อ่างทอง', 'อุดรธานี', 'อุทัยธานี', 'อุตรดิตถ์',
            'อุบลราชธานี', 'อำนาจเจริญ',
        ];
    }
}
