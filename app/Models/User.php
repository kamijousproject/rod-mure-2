<?php
/**
 * User Model - Handles user data and authentication
 */

namespace App\Models;

use App\Core\Database;

class User extends BaseModel
{
    protected static string $table = 'users';
    protected static array $fillable = [
        'name', 'email', 'password', 'phone', 'address', 
        'province', 'role', 'status', 'avatar', 
        'reset_token', 'reset_expires'
    ];
    
    /**
     * Create new user with hashed password
     */
    public static function register(array $data): int
    {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $data['role'] = $data['role'] ?? 'buyer';
        $data['status'] = 'active';
        
        return self::create($data);
    }
    
    /**
     * Find user by email
     */
    public static function findByEmail(string $email): ?array
    {
        return self::findBy('email', $email);
    }
    
    /**
     * Update password
     */
    public static function updatePassword(int $id, string $password): bool
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        
        Database::query(
            "UPDATE users SET password = ? WHERE id = ?",
            [$hash, $id]
        );
        
        return true;
    }
    
    /**
     * Get user with stats
     */
    public static function withStats(int $id): ?array
    {
        $user = self::find($id);
        
        if (!$user) {
            return null;
        }
        
        // Get listing count
        $stats = Database::fetch(
            "SELECT 
                COUNT(*) as total_listings,
                SUM(CASE WHEN status = 'published' THEN 1 ELSE 0 END) as active_listings,
                SUM(CASE WHEN status = 'sold' THEN 1 ELSE 0 END) as sold_listings,
                SUM(views) as total_views
             FROM cars WHERE user_id = ?",
            [$id]
        );
        
        $user['stats'] = $stats;
        
        return $user;
    }
    
    /**
     * Get sellers with their listings count
     */
    public static function getSellers(int $limit = 10): array
    {
        return Database::fetchAll(
            "SELECT u.*, COUNT(c.id) as listing_count
             FROM users u
             LEFT JOIN cars c ON c.user_id = u.id AND c.status = 'published'
             WHERE u.role IN ('seller', 'admin') AND u.status = 'active'
             GROUP BY u.id
             ORDER BY listing_count DESC
             LIMIT ?",
            [$limit]
        );
    }
    
    /**
     * Search users
     */
    public static function search(string $query, int $page = 1, int $perPage = 20): array
    {
        $offset = ($page - 1) * $perPage;
        $searchTerm = "%{$query}%";
        
        $countResult = Database::fetch(
            "SELECT COUNT(*) as total FROM users 
             WHERE name LIKE ? OR email LIKE ? OR phone LIKE ?",
            [$searchTerm, $searchTerm, $searchTerm]
        );
        
        $users = Database::fetchAll(
            "SELECT id, name, email, phone, role, status, created_at, last_login
             FROM users 
             WHERE name LIKE ? OR email LIKE ? OR phone LIKE ?
             ORDER BY created_at DESC
             LIMIT ? OFFSET ?",
            [$searchTerm, $searchTerm, $searchTerm, $perPage, $offset]
        );
        
        return [
            'items' => $users,
            'total' => (int)$countResult['total'],
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => (int)ceil($countResult['total'] / $perPage),
        ];
    }
}
