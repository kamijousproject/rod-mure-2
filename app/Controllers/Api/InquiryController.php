<?php
/**
 * API InquiryController - Handle inquiry messages API
 */

namespace App\Controllers\Api;

use App\Core\BaseController;
use App\Core\Auth;
use App\Models\Inquiry;
use App\Models\Message;

class InquiryController extends BaseController
{
    /**
     * Get messages for an inquiry (for polling)
     */
    public function messages(string $id): void
    {
        // Require auth
        if (!Auth::check()) {
            $this->json(['success' => false, 'message' => 'Unauthorized'], 401);
            return;
        }
        
        $inquiry = Inquiry::find((int)$id);
        
        // Check access
        if (!$inquiry || ($inquiry['sender_id'] != Auth::id() && $inquiry['receiver_id'] != Auth::id())) {
            $this->json(['success' => false, 'message' => 'Not found'], 404);
            return;
        }
        
        // Get offset (number of messages already loaded)
        $after = (int)$this->input('after', 0);
        
        // Get all messages and return only new ones
        $allMessages = Message::getByInquiry((int)$id);
        $newMessages = array_slice($allMessages, $after);
        
        $this->json([
            'success' => true,
            'data' => [
                'messages' => $newMessages,
                'total' => count($allMessages),
            ],
        ]);
    }
}
