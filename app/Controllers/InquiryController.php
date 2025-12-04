<?php
/**
 * InquiryController - Handles inquiries/messages
 */

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Auth;
use App\Core\CSRF;
use App\Core\Session;
use App\Models\Car;
use App\Models\Inquiry;
use App\Models\Message;

class InquiryController extends BaseController
{
    /**
     * List inquiries for current user
     */
    public function index(): void
    {
        $this->requireAuth();
        
        $status = $this->input('status');
        $page = (int)$this->input('page', 1);
        
        $result = Inquiry::getForSeller(Auth::id(), $status, $page, 15);
        
        $this->view('inquiries.index', [
            'title' => 'ข้อความสอบถาม',
            'inquiries' => $result['items'],
            'pagination' => $result,
            'currentStatus' => $status,
        ]);
    }
    
    /**
     * Show single inquiry with conversation
     */
    public function show(string $id): void
    {
        $this->requireAuth();
        
        $inquiry = Inquiry::getWithMessages((int)$id);
        
        if (!$inquiry || ($inquiry['sender_id'] != Auth::id() && $inquiry['receiver_id'] != Auth::id())) {
            Session::setFlash('error', 'ไม่พบข้อความ');
            $this->redirect('/inquiries');
        }
        
        // Mark as read if receiver
        if ($inquiry['receiver_id'] == Auth::id()) {
            Inquiry::markAsRead((int)$id);
            Message::markAsRead((int)$id, Auth::id());
        }
        
        $this->view('inquiries.show', [
            'title' => 'ข้อความสอบถาม - ' . $inquiry['car_title'],
            'inquiry' => $inquiry,
        ]);
    }
    
    /**
     * Create new inquiry (from car detail page)
     */
    public function store(): void
    {
        CSRF::check();
        
        $carId = (int)$this->input('car_id');
        $car = Car::find($carId);
        
        if (!$car || $car['status'] !== 'published') {
            Session::setFlash('error', 'ไม่พบรถที่ต้องการสอบถาม');
            $this->redirect('/cars');
        }
        
        // Prevent seller from inquiring own car
        if (Auth::check() && $car['user_id'] == Auth::id()) {
            Session::setFlash('error', 'ไม่สามารถสอบถามรถของตัวเองได้');
            $this->redirect('/cars/' . $car['slug']);
        }
        
        $data = [
            'car_id' => $carId,
            'sender_id' => Auth::id(),
            'receiver_id' => $car['user_id'],
            'name' => $this->input('name'),
            'email' => $this->input('email'),
            'phone' => $this->input('phone'),
            'message' => $this->input('message'),
            'status' => Inquiry::STATUS_UNREAD,
        ];
        
        // Use logged in user data if available
        if (Auth::check()) {
            $user = Auth::user();
            $data['name'] = $user['name'];
            $data['email'] = $user['email'];
            $data['phone'] = $data['phone'] ?: $user['phone'];
        }
        
        $errors = $this->validate($data, [
            'name' => 'required|min:2|max:100',
            'email' => 'required|email',
            'phone' => 'required|min:9|max:15',
            'message' => 'required|min:10|max:1000',
        ]);
        
        if (!empty($errors)) {
            Session::setErrors($errors);
            Session::setOld($data);
            $this->redirect('/cars/' . $car['slug'] . '#inquiry-form');
        }
        
        Inquiry::createAndNotify($data);
        
        Session::setFlash('success', 'ส่งข้อความสำเร็จ ผู้ขายจะติดต่อกลับเร็วๆ นี้');
        $this->redirect('/cars/' . $car['slug']);
    }
    
    /**
     * Reply to inquiry
     */
    public function reply(string $id): void
    {
        $this->requireAuth();
        CSRF::check();
        
        $inquiry = Inquiry::getWithMessages((int)$id);
        
        if (!$inquiry || ($inquiry['sender_id'] != Auth::id() && $inquiry['receiver_id'] != Auth::id())) {
            $this->json(['success' => false, 'message' => 'ไม่พบข้อความ'], 404);
        }
        
        $message = trim($this->input('message'));
        
        if (empty($message)) {
            $this->json(['success' => false, 'message' => 'กรุณากรอกข้อความ'], 400);
        }
        
        $messageId = Message::addReply((int)$id, Auth::id(), $message);
        
        $this->json([
            'success' => true,
            'message' => 'ส่งข้อความสำเร็จ',
            'data' => [
                'id' => $messageId,
                'message' => $message,
                'sender_name' => Auth::user()['name'],
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ]);
    }
}
