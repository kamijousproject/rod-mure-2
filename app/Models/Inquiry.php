<?php
/**
 * Inquiry Model - Handles inquiries/messages about cars
 */

namespace App\Models;

use App\Core\Database;

class Inquiry extends BaseModel
{
    protected static string $table = 'inquiries';
    protected static array $fillable = [
        'car_id', 'sender_id', 'receiver_id', 'name', 'email', 
        'phone', 'message', 'status'
    ];
    
    // Status constants
    public const STATUS_UNREAD = 'unread';
    public const STATUS_READ = 'read';
    public const STATUS_REPLIED = 'replied';
    
    /**
     * Create inquiry and notify seller
     */
    public static function createAndNotify(array $data): int
    {
        $id = self::create($data);
        
        // Get car and seller info for notification
        $car = Car::find($data['car_id']);
        $seller = User::find($data['receiver_id']);
        
        if ($car && $seller) {
            // Simulate email notification (log to file)
            $logMessage = sprintf(
                "[%s] New inquiry for car '%s' (ID: %d)\n" .
                "From: %s <%s>\n" .
                "Phone: %s\n" .
                "Message: %s\n" .
                "---\n",
                date('Y-m-d H:i:s'),
                $car['title'],
                $car['id'],
                $data['name'],
                $data['email'],
                $data['phone'] ?? 'N/A',
                $data['message']
            );
            
            $logPath = BASE_PATH . '/storage/logs/mail.log';
            file_put_contents($logPath, $logMessage, FILE_APPEND | LOCK_EX);
        }
        
        return $id;
    }
    
    /**
     * Get inquiries for user (both sent and received)
     */
    public static function getForUser(int $userId, ?string $status = null, int $page = 1, int $perPage = 20): array
    {
        $conditions = ["(i.receiver_id = ? OR i.sender_id = ?)"];
        $params = [$userId, $userId];
        
        if ($status) {
            $conditions[] = "i.status = ?";
            $params[] = $status;
        }
        
        $whereClause = implode(' AND ', $conditions);
        
        // Count
        $countParams = $params;
        $total = (int)(Database::fetch(
            "SELECT COUNT(*) as total FROM inquiries i WHERE {$whereClause}",
            $countParams
        )['total'] ?? 0);
        
        // Get inquiries
        $offset = ($page - 1) * $perPage;
        $inquiries = Database::fetchAll(
            "SELECT i.*, 
                    c.title as car_title,
                    c.slug as car_slug,
                    (SELECT image_path FROM car_images WHERE car_id = c.id ORDER BY is_primary DESC, id ASC LIMIT 1) as car_image
             FROM inquiries i
             LEFT JOIN cars c ON c.id = i.car_id
             WHERE {$whereClause}
             ORDER BY i.created_at DESC
             LIMIT {$perPage} OFFSET {$offset}",
            $params
        );
        
        return [
            'items' => $inquiries,
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => (int)ceil($total / $perPage),
        ];
    }
    
    /**
     * Get inquiries for seller (received only)
     */
    public static function getForSeller(int $sellerId, ?string $status = null, int $page = 1, int $perPage = 20): array
    {
        $conditions = ["i.receiver_id = ?"];
        $params = [$sellerId];
        
        if ($status) {
            $conditions[] = "i.status = ?";
            $params[] = $status;
        }
        
        $whereClause = implode(' AND ', $conditions);
        
        // Count
        $countParams = $params;
        $total = (int)(Database::fetch(
            "SELECT COUNT(*) as total FROM inquiries i WHERE {$whereClause}",
            $countParams
        )['total'] ?? 0);
        
        // Get inquiries
        $offset = ($page - 1) * $perPage;
        $inquiries = Database::fetchAll(
            "SELECT i.*, 
                    c.title as car_title,
                    c.slug as car_slug,
                    (SELECT image_path FROM car_images WHERE car_id = c.id ORDER BY is_primary DESC, id ASC LIMIT 1) as car_image
             FROM inquiries i
             LEFT JOIN cars c ON c.id = i.car_id
             WHERE {$whereClause}
             ORDER BY i.created_at DESC
             LIMIT {$perPage} OFFSET {$offset}",
            $params
        );
        
        return [
            'items' => $inquiries,
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => (int)ceil($total / $perPage),
        ];
    }
    
    /**
     * Get inquiry with conversation
     */
    public static function getWithMessages(int $id): ?array
    {
        $inquiry = Database::fetch(
            "SELECT i.*, 
                    c.title as car_title,
                    c.slug as car_slug,
                    (SELECT image_path FROM car_images WHERE car_id = c.id ORDER BY is_primary DESC, id ASC LIMIT 1) as car_image,
                    s.name as sender_name,
                    s.email as sender_email,
                    r.name as receiver_name
             FROM inquiries i
             LEFT JOIN cars c ON c.id = i.car_id
             LEFT JOIN users s ON s.id = i.sender_id
             LEFT JOIN users r ON r.id = i.receiver_id
             WHERE i.id = ?",
            [$id]
        );
        
        if ($inquiry) {
            $inquiry['messages'] = Message::getByInquiry($id);
        }
        
        return $inquiry;
    }
    
    /**
     * Mark as read
     */
    public static function markAsRead(int $id): bool
    {
        Database::query(
            "UPDATE inquiries SET status = 'read' WHERE id = ? AND status = 'unread'",
            [$id]
        );
        
        return true;
    }
    
    /**
     * Count unread for user
     */
    public static function countUnread(int $userId): int
    {
        $result = Database::fetch(
            "SELECT COUNT(*) as total FROM inquiries WHERE receiver_id = ? AND status = 'unread'",
            [$userId]
        );
        
        return (int)($result['total'] ?? 0);
    }
    
    /**
     * Get statistics
     */
    public static function getStats(): array
    {
        return Database::fetch(
            "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN status = 'unread' THEN 1 ELSE 0 END) as unread,
                SUM(CASE WHEN status = 'read' THEN 1 ELSE 0 END) as read_count,
                SUM(CASE WHEN status = 'replied' THEN 1 ELSE 0 END) as replied
             FROM inquiries"
        ) ?? [];
    }
}
