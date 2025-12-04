<?php
/**
 * Message Model - Handles conversation messages
 */

namespace App\Models;

use App\Core\Database;

class Message extends BaseModel
{
    protected static string $table = 'messages';
    protected static array $fillable = ['inquiry_id', 'sender_id', 'message', 'is_read'];
    
    /**
     * Get messages for an inquiry
     */
    public static function getByInquiry(int $inquiryId): array
    {
        return Database::fetchAll(
            "SELECT m.*, u.name as sender_name, u.role as sender_role
             FROM messages m
             LEFT JOIN users u ON u.id = m.sender_id
             WHERE m.inquiry_id = ?
             ORDER BY m.created_at ASC",
            [$inquiryId]
        );
    }
    
    /**
     * Add reply to inquiry
     */
    public static function addReply(int $inquiryId, int $senderId, string $message): int
    {
        $id = self::create([
            'inquiry_id' => $inquiryId,
            'sender_id' => $senderId,
            'message' => $message,
            'is_read' => 0,
        ]);
        
        // Update inquiry status to replied
        Database::query(
            "UPDATE inquiries SET status = 'replied', updated_at = NOW() WHERE id = ?",
            [$inquiryId]
        );
        
        // Simulate email notification
        $inquiry = Inquiry::getWithMessages($inquiryId);
        if ($inquiry) {
            $recipientEmail = $inquiry['sender_id'] == $senderId 
                ? $inquiry['receiver_email'] ?? '' 
                : $inquiry['sender_email'] ?? $inquiry['email'];
            
            $logMessage = sprintf(
                "[%s] New reply to inquiry #%d\n" .
                "To: %s\n" .
                "Message: %s\n" .
                "---\n",
                date('Y-m-d H:i:s'),
                $inquiryId,
                $recipientEmail,
                $message
            );
            
            $logPath = BASE_PATH . '/storage/logs/mail.log';
            file_put_contents($logPath, $logMessage, FILE_APPEND | LOCK_EX);
        }
        
        return $id;
    }
    
    /**
     * Mark messages as read
     */
    public static function markAsRead(int $inquiryId, int $userId): void
    {
        Database::query(
            "UPDATE messages SET is_read = 1 
             WHERE inquiry_id = ? AND sender_id != ?",
            [$inquiryId, $userId]
        );
    }
    
    /**
     * Count unread messages for user
     */
    public static function countUnread(int $userId): int
    {
        $result = Database::fetch(
            "SELECT COUNT(*) as total 
             FROM messages m
             JOIN inquiries i ON i.id = m.inquiry_id
             WHERE (i.sender_id = ? OR i.receiver_id = ?)
               AND m.sender_id != ?
               AND m.is_read = 0",
            [$userId, $userId, $userId]
        );
        
        return (int)($result['total'] ?? 0);
    }
}
