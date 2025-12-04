<?php
/**
 * AuthController - Handles authentication
 */

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Auth;
use App\Core\CSRF;
use App\Core\Session;
use App\Models\User;

class AuthController extends BaseController
{
    /**
     * Show login form
     */
    public function showLogin(): void
    {
        if (Auth::check()) {
            $this->redirect('/dashboard');
        }
        
        $this->view('auth.login', [
            'title' => 'เข้าสู่ระบบ',
        ]);
    }
    
    /**
     * Process login
     */
    public function login(): void
    {
        CSRF::check();
        
        $email = $this->input('email');
        $password = $this->input('password');
        $remember = $this->input('remember');
        
        // Validate
        $errors = $this->validate([
            'email' => $email,
            'password' => $password,
        ], [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);
        
        if (!empty($errors)) {
            Session::setErrors($errors);
            Session::setOld(['email' => $email]);
            $this->redirect('/login');
        }
        
        if (Auth::attempt($email, $password)) {
            Session::setFlash('success', 'เข้าสู่ระบบสำเร็จ');
            
            // Redirect based on role
            $user = Auth::user();
            if ($user['role'] === 'admin') {
                $this->redirect('/admin');
            } else {
                $this->redirect('/dashboard');
            }
        }
        
        Session::setFlash('error', 'อีเมลหรือรหัสผ่านไม่ถูกต้อง');
        Session::setOld(['email' => $email]);
        $this->redirect('/login');
    }
    
    /**
     * Show registration form
     */
    public function showRegister(): void
    {
        if (Auth::check()) {
            $this->redirect('/dashboard');
        }
        
        $this->view('auth.register', [
            'title' => 'สมัครสมาชิก',
        ]);
    }
    
    /**
     * Process registration
     */
    public function register(): void
    {
        CSRF::check();
        
        $data = [
            'name' => $this->input('name'),
            'email' => $this->input('email'),
            'password' => $this->input('password'),
            'password_confirmation' => $this->input('password_confirmation'),
            'phone' => $this->input('phone'),
            'role' => $this->input('role', 'buyer'),
        ];
        
        // Validate
        $errors = $this->validate($data, [
            'name' => 'required|min:2|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'phone' => 'required|min:9|max:15',
            'role' => 'required|in:buyer,seller',
        ]);
        
        if (!empty($errors)) {
            Session::setErrors($errors);
            Session::setOld($data);
            $this->redirect('/register');
        }
        
        // Create user
        unset($data['password_confirmation']);
        $userId = User::register($data);
        
        // Auto login
        Auth::attempt($data['email'], $this->input('password'));
        
        Session::setFlash('success', 'สมัครสมาชิกสำเร็จ ยินดีต้อนรับ!');
        $this->redirect('/dashboard');
    }
    
    /**
     * Logout
     */
    public function logout(): void
    {
        CSRF::check();
        Auth::logout();
        Session::setFlash('success', 'ออกจากระบบสำเร็จ');
        $this->redirect('/');
    }
    
    /**
     * Show forgot password form
     */
    public function showForgotPassword(): void
    {
        $this->view('auth.forgot-password', [
            'title' => 'ลืมรหัสผ่าน',
        ]);
    }
    
    /**
     * Send password reset link
     */
    public function sendResetLink(): void
    {
        CSRF::check();
        
        $email = $this->input('email');
        
        $errors = $this->validate(['email' => $email], ['email' => 'required|email']);
        
        if (!empty($errors)) {
            Session::setErrors($errors);
            Session::setOld(['email' => $email]);
            $this->redirect('/forgot-password');
        }
        
        $token = Auth::generateResetToken($email);
        
        if ($token) {
            // Simulate sending email (log to file)
            $resetUrl = base_url("reset-password/{$token}");
            $logMessage = sprintf(
                "[%s] Password reset requested for: %s\nReset URL: %s\n---\n",
                date('Y-m-d H:i:s'),
                $email,
                $resetUrl
            );
            
            $logPath = BASE_PATH . '/storage/logs/mail.log';
            file_put_contents($logPath, $logMessage, FILE_APPEND | LOCK_EX);
        }
        
        // Always show success message (security)
        Session::setFlash('success', 'หากอีเมลนี้มีในระบบ เราได้ส่งลิงก์รีเซ็ตรหัสผ่านไปแล้ว');
        $this->redirect('/forgot-password');
    }
    
    /**
     * Show reset password form
     */
    public function showResetForm(string $token): void
    {
        $user = Auth::validateResetToken($token);
        
        if (!$user) {
            Session::setFlash('error', 'ลิงก์รีเซ็ตรหัสผ่านไม่ถูกต้องหรือหมดอายุ');
            $this->redirect('/forgot-password');
        }
        
        $this->view('auth.reset-password', [
            'title' => 'ตั้งรหัสผ่านใหม่',
            'token' => $token,
            'email' => $user['email'],
        ]);
    }
    
    /**
     * Reset password
     */
    public function resetPassword(): void
    {
        CSRF::check();
        
        $token = $this->input('token');
        $password = $this->input('password');
        $passwordConfirmation = $this->input('password_confirmation');
        
        $errors = $this->validate([
            'password' => $password,
            'password_confirmation' => $passwordConfirmation,
        ], [
            'password' => 'required|min:6|confirmed',
        ]);
        
        if (!empty($errors)) {
            Session::setErrors($errors);
            $this->redirect("/reset-password/{$token}");
        }
        
        if (Auth::resetPassword($token, $password)) {
            Session::setFlash('success', 'รีเซ็ตรหัสผ่านสำเร็จ กรุณาเข้าสู่ระบบด้วยรหัสผ่านใหม่');
            $this->redirect('/login');
        }
        
        Session::setFlash('error', 'ไม่สามารถรีเซ็ตรหัสผ่านได้');
        $this->redirect('/forgot-password');
    }
}
