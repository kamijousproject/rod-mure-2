<?php
/**
 * CarModel Model - Car models (e.g., Civic, Corolla)
 */

namespace App\Models;

use App\Core\Database;

class CarModel extends BaseModel
{
    protected static string $table = 'models';
    protected static array $fillable = ['brand_id', 'name', 'slug', 'status'];
    
    /**
     * Get models by brand
     */
    public static function getByBrand(int $brandId): array
    {
        return Database::fetchAll(
            "SELECT * FROM models WHERE brand_id = ? AND status = 'active' ORDER BY name ASC",
            [$brandId]
        );
    }
    
    /**
     * Get active models with brand
     */
    public static function withBrand(): array
    {
        return Database::fetchAll(
            "SELECT m.*, b.name as brand_name
             FROM models m
             LEFT JOIN brands b ON b.id = m.brand_id
             WHERE m.status = 'active'
             ORDER BY b.name ASC, m.name ASC"
        );
    }
    
    /**
     * Get models with car count
     */
    public static function withCarCount(int $brandId): array
    {
        return Database::fetchAll(
            "SELECT m.*, COUNT(c.id) as car_count
             FROM models m
             LEFT JOIN cars c ON c.model_id = m.id AND c.status = 'published'
             WHERE m.brand_id = ? AND m.status = 'active'
             GROUP BY m.id
             ORDER BY car_count DESC, m.name ASC",
            [$brandId]
        );
    }
    
    /**
     * Generate unique slug
     */
    public static function generateSlug(string $name, int $brandId, ?int $exceptId = null): string
    {
        $slug = slugify($name);
        $originalSlug = $slug;
        $counter = 1;
        
        // Check within the same brand
        $existsQuery = "SELECT COUNT(*) as count FROM models WHERE slug = ? AND brand_id = ?";
        $params = [$slug, $brandId];
        
        if ($exceptId) {
            $existsQuery .= " AND id != ?";
            $params[] = $exceptId;
        }
        
        while ((Database::fetch($existsQuery, $params)['count'] ?? 0) > 0) {
            $slug = $originalSlug . '-' . $counter;
            $params[0] = $slug;
            $counter++;
        }
        
        return $slug;
    }
}
