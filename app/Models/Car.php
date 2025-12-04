<?php
/**
 * Car Model - Handles car listings data
 */

namespace App\Models;

use App\Core\Database;

class Car extends BaseModel
{
    protected static string $table = 'cars';
    protected static array $fillable = [
        'user_id', 'brand_id', 'model_id', 'title', 'slug', 'description',
        'price', 'year', 'mileage', 'color', 'engine_size', 'transmission',
        'fuel_type', 'vin', 'province', 'status', 'is_featured', 'views'
    ];
    
    // Transmission types
    public const TRANSMISSION_AUTO = 'auto';
    public const TRANSMISSION_MANUAL = 'manual';
    public const TRANSMISSIONS = [
        self::TRANSMISSION_AUTO => 'อัตโนมัติ',
        self::TRANSMISSION_MANUAL => 'ธรรมดา',
    ];
    
    // Fuel types
    public const FUEL_GASOLINE = 'gasoline';
    public const FUEL_DIESEL = 'diesel';
    public const FUEL_HYBRID = 'hybrid';
    public const FUEL_ELECTRIC = 'electric';
    public const FUEL_LPG = 'lpg';
    public const FUELS = [
        self::FUEL_GASOLINE => 'เบนซิน',
        self::FUEL_DIESEL => 'ดีเซล',
        self::FUEL_HYBRID => 'ไฮบริด',
        self::FUEL_ELECTRIC => 'ไฟฟ้า',
        self::FUEL_LPG => 'แก๊ส LPG',
    ];
    
    // Status
    public const STATUS_DRAFT = 'draft';
    public const STATUS_PENDING = 'pending';
    public const STATUS_PUBLISHED = 'published';
    public const STATUS_SOLD = 'sold';
    public const STATUS_REJECTED = 'rejected';
    public const STATUSES = [
        self::STATUS_DRAFT => 'ฉบับร่าง',
        self::STATUS_PENDING => 'รอตรวจสอบ',
        self::STATUS_PUBLISHED => 'เผยแพร่',
        self::STATUS_SOLD => 'ขายแล้ว',
        self::STATUS_REJECTED => 'ไม่อนุมัติ',
    ];
    
    /**
     * Get car with all relations
     */
    public static function findWithRelations(int $id): ?array
    {
        $car = Database::fetch(
            "SELECT c.*, 
                    b.name as brand_name,
                    m.name as model_name,
                    u.name as seller_name,
                    u.phone as seller_phone,
                    u.email as seller_email,
                    u.province as seller_province
             FROM cars c
             LEFT JOIN brands b ON b.id = c.brand_id
             LEFT JOIN models m ON m.id = c.model_id
             LEFT JOIN users u ON u.id = c.user_id
             WHERE c.id = ?",
            [$id]
        );
        
        if ($car) {
            $car['images'] = CarImage::getByCarId($id);
        }
        
        return $car;
    }
    
    /**
     * Get car by slug
     */
    public static function findBySlug(string $slug): ?array
    {
        $car = Database::fetch(
            "SELECT c.*, 
                    b.name as brand_name,
                    m.name as model_name,
                    u.name as seller_name,
                    u.phone as seller_phone,
                    u.email as seller_email,
                    u.province as seller_province
             FROM cars c
             LEFT JOIN brands b ON b.id = c.brand_id
             LEFT JOIN models m ON m.id = c.model_id
             LEFT JOIN users u ON u.id = c.user_id
             WHERE c.slug = ?",
            [$slug]
        );
        
        if ($car) {
            $car['images'] = CarImage::getByCarId($car['id']);
        }
        
        return $car;
    }
    
    /**
     * Increment view count
     */
    public static function incrementViews(int $id): void
    {
        Database::query(
            "UPDATE cars SET views = views + 1 WHERE id = ?",
            [$id]
        );
    }
    
    /**
     * Search and filter cars
     */
    public static function search(array $filters = [], int $page = 1, int $perPage = 12): array
    {
        $conditions = ["c.status = 'published'"];
        $params = [];
        
        // Keyword search
        if (!empty($filters['q'])) {
            $conditions[] = "(c.title LIKE ? OR c.description LIKE ? OR b.name LIKE ? OR m.name LIKE ?)";
            $searchTerm = "%{$filters['q']}%";
            $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
        }
        
        // Brand filter
        if (!empty($filters['brand_id'])) {
            $conditions[] = "c.brand_id = ?";
            $params[] = $filters['brand_id'];
        }
        
        // Model filter
        if (!empty($filters['model_id'])) {
            $conditions[] = "c.model_id = ?";
            $params[] = $filters['model_id'];
        }
        
        // Year range
        if (!empty($filters['year_from'])) {
            $conditions[] = "c.year >= ?";
            $params[] = $filters['year_from'];
        }
        if (!empty($filters['year_to'])) {
            $conditions[] = "c.year <= ?";
            $params[] = $filters['year_to'];
        }
        
        // Price range
        if (!empty($filters['price_from'])) {
            $conditions[] = "c.price >= ?";
            $params[] = $filters['price_from'];
        }
        if (!empty($filters['price_to'])) {
            $conditions[] = "c.price <= ?";
            $params[] = $filters['price_to'];
        }
        
        // Mileage range
        if (!empty($filters['mileage_from'])) {
            $conditions[] = "c.mileage >= ?";
            $params[] = $filters['mileage_from'];
        }
        if (!empty($filters['mileage_to'])) {
            $conditions[] = "c.mileage <= ?";
            $params[] = $filters['mileage_to'];
        }
        
        // Transmission
        if (!empty($filters['transmission'])) {
            $conditions[] = "c.transmission = ?";
            $params[] = $filters['transmission'];
        }
        
        // Fuel type
        if (!empty($filters['fuel_type'])) {
            $conditions[] = "c.fuel_type = ?";
            $params[] = $filters['fuel_type'];
        }
        
        // Province
        if (!empty($filters['province'])) {
            $conditions[] = "c.province = ?";
            $params[] = $filters['province'];
        }
        
        $whereClause = implode(' AND ', $conditions);
        
        // Sorting
        $orderBy = match ($filters['sort'] ?? 'newest') {
            'price_low' => 'c.price ASC',
            'price_high' => 'c.price DESC',
            'year_new' => 'c.year DESC',
            'year_old' => 'c.year ASC',
            'mileage_low' => 'c.mileage ASC',
            'views' => 'c.views DESC',
            default => 'c.created_at DESC',
        };
        
        // Count total
        $countParams = $params;
        $countSql = "SELECT COUNT(*) as total 
                     FROM cars c
                     LEFT JOIN brands b ON b.id = c.brand_id
                     LEFT JOIN models m ON m.id = c.model_id
                     WHERE {$whereClause}";
        $total = (int)(Database::fetch($countSql, $countParams)['total'] ?? 0);
        
        // Get cars
        $offset = ($page - 1) * $perPage;
        $sql = "SELECT c.*, 
                       b.name as brand_name,
                       m.name as model_name,
                       (SELECT image_path FROM car_images WHERE car_id = c.id ORDER BY is_primary DESC, id ASC LIMIT 1) as primary_image
                FROM cars c
                LEFT JOIN brands b ON b.id = c.brand_id
                LEFT JOIN models m ON m.id = c.model_id
                WHERE {$whereClause}
                ORDER BY c.is_featured DESC, {$orderBy}
                LIMIT {$perPage} OFFSET {$offset}";
        
        $cars = Database::fetchAll($sql, $params);
        
        return [
            'items' => $cars,
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => (int)ceil($total / $perPage),
            'has_more' => ($page * $perPage) < $total,
        ];
    }
    
    /**
     * Get featured cars
     */
    public static function getFeatured(int $limit = 6): array
    {
        return Database::fetchAll(
            "SELECT c.*, 
                    b.name as brand_name,
                    m.name as model_name,
                    (SELECT image_path FROM car_images WHERE car_id = c.id ORDER BY is_primary DESC, id ASC LIMIT 1) as primary_image
             FROM cars c
             LEFT JOIN brands b ON b.id = c.brand_id
             LEFT JOIN models m ON m.id = c.model_id
             WHERE c.status = 'published' AND c.is_featured = 1
             ORDER BY c.created_at DESC
             LIMIT ?",
            [$limit]
        );
    }
    
    /**
     * Get latest cars
     */
    public static function getLatest(int $limit = 8): array
    {
        return Database::fetchAll(
            "SELECT c.*, 
                    b.name as brand_name,
                    m.name as model_name,
                    (SELECT image_path FROM car_images WHERE car_id = c.id ORDER BY is_primary DESC, id ASC LIMIT 1) as primary_image
             FROM cars c
             LEFT JOIN brands b ON b.id = c.brand_id
             LEFT JOIN models m ON m.id = c.model_id
             WHERE c.status = 'published'
             ORDER BY c.created_at DESC
             LIMIT ?",
            [$limit]
        );
    }
    
    /**
     * Get cars by user
     */
    public static function getByUser(int $userId, ?string $status = null, int $page = 1, int $perPage = 10): array
    {
        $conditions = ["c.user_id = ?"];
        $params = [$userId];
        
        if ($status) {
            $conditions[] = "c.status = ?";
            $params[] = $status;
        }
        
        $whereClause = implode(' AND ', $conditions);
        
        // Count
        $countParams = $params;
        $total = (int)(Database::fetch(
            "SELECT COUNT(*) as total FROM cars c WHERE {$whereClause}",
            $countParams
        )['total'] ?? 0);
        
        // Get cars
        $offset = ($page - 1) * $perPage;
        $cars = Database::fetchAll(
            "SELECT c.*, 
                    b.name as brand_name,
                    m.name as model_name,
                    (SELECT image_path FROM car_images WHERE car_id = c.id ORDER BY is_primary DESC, id ASC LIMIT 1) as primary_image,
                    (SELECT COUNT(*) FROM inquiries WHERE car_id = c.id) as inquiry_count
             FROM cars c
             LEFT JOIN brands b ON b.id = c.brand_id
             LEFT JOIN models m ON m.id = c.model_id
             WHERE {$whereClause}
             ORDER BY c.created_at DESC
             LIMIT {$perPage} OFFSET {$offset}",
            $params
        );
        
        return [
            'items' => $cars,
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => (int)ceil($total / $perPage),
        ];
    }
    
    /**
     * Generate unique slug
     */
    public static function generateSlug(string $title, ?int $exceptId = null): string
    {
        $slug = slugify($title);
        $originalSlug = $slug;
        $counter = 1;
        
        while (self::exists('slug', $slug, $exceptId)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }
    
    /**
     * Get related cars
     */
    public static function getRelated(int $carId, int $brandId, int $limit = 4): array
    {
        return Database::fetchAll(
            "SELECT c.*, 
                    b.name as brand_name,
                    m.name as model_name,
                    (SELECT image_path FROM car_images WHERE car_id = c.id ORDER BY is_primary DESC, id ASC LIMIT 1) as primary_image
             FROM cars c
             LEFT JOIN brands b ON b.id = c.brand_id
             LEFT JOIN models m ON m.id = c.model_id
             WHERE c.status = 'published' AND c.id != ? AND c.brand_id = ?
             ORDER BY c.created_at DESC
             LIMIT ?",
            [$carId, $brandId, $limit]
        );
    }
    
    /**
     * Get statistics
     */
    public static function getStats(): array
    {
        return Database::fetch(
            "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN status = 'published' THEN 1 ELSE 0 END) as published,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'sold' THEN 1 ELSE 0 END) as sold,
                SUM(views) as total_views
             FROM cars"
        ) ?? [];
    }
}
