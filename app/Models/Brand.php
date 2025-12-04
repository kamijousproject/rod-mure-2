<?php
/**
 * Brand Model - Car brands
 */

namespace App\Models;

use App\Core\Database;

class Brand extends BaseModel
{
    protected static string $table = 'brands';
    protected static array $fillable = ['name', 'slug', 'logo', 'status'];
    
    /**
     * Get active brands
     */
    public static function getActive(): array
    {
        return Database::fetchAll(
            "SELECT * FROM brands WHERE status = 'active' ORDER BY name ASC"
        );
    }
    
    /**
     * Get brands with car count
     */
    public static function withCarCount(): array
    {
        return Database::fetchAll(
            "SELECT b.*, COUNT(c.id) as car_count
             FROM brands b
             LEFT JOIN cars c ON c.brand_id = b.id AND c.status = 'published'
             WHERE b.status = 'active'
             GROUP BY b.id
             ORDER BY car_count DESC, b.name ASC"
        );
    }
    
    /**
     * Get brand by slug
     */
    public static function findBySlug(string $slug): ?array
    {
        return self::findBy('slug', $slug);
    }
    
    /**
     * Generate unique slug
     */
    public static function generateSlug(string $name, ?int $exceptId = null): string
    {
        $slug = slugify($name);
        $originalSlug = $slug;
        $counter = 1;
        
        while (self::exists('slug', $slug, $exceptId)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }
}
