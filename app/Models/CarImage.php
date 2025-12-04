<?php
/**
 * CarImage Model - Handles car images
 */

namespace App\Models;

use App\Core\Database;

class CarImage extends BaseModel
{
    protected static string $table = 'car_images';
    protected static array $fillable = ['car_id', 'image_path', 'is_primary', 'sort_order'];
    
    /**
     * Get images for a car
     */
    public static function getByCarId(int $carId): array
    {
        return Database::fetchAll(
            "SELECT * FROM car_images WHERE car_id = ? ORDER BY is_primary DESC, sort_order ASC, id ASC",
            [$carId]
        );
    }
    
    /**
     * Add image to car
     */
    public static function addToCar(int $carId, string $imagePath, bool $isPrimary = false): int
    {
        // If setting as primary, unset other primaries
        if ($isPrimary) {
            Database::query(
                "UPDATE car_images SET is_primary = 0 WHERE car_id = ?",
                [$carId]
            );
        }
        
        return self::create([
            'car_id' => $carId,
            'image_path' => $imagePath,
            'is_primary' => $isPrimary ? 1 : 0,
            'sort_order' => self::getNextSortOrder($carId),
        ]);
    }
    
    /**
     * Set primary image
     */
    public static function setPrimary(int $carId, int $imageId): bool
    {
        Database::query(
            "UPDATE car_images SET is_primary = 0 WHERE car_id = ?",
            [$carId]
        );
        
        Database::query(
            "UPDATE car_images SET is_primary = 1 WHERE id = ? AND car_id = ?",
            [$imageId, $carId]
        );
        
        return true;
    }
    
    /**
     * Delete image and file
     */
    public static function deleteWithFile(int $id): bool
    {
        $image = self::find($id);
        
        if ($image) {
            $filePath = BASE_PATH . '/storage/uploads/' . $image['image_path'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            
            return self::delete($id);
        }
        
        return false;
    }
    
    /**
     * Delete all images for a car
     */
    public static function deleteAllForCar(int $carId): void
    {
        $images = self::getByCarId($carId);
        
        foreach ($images as $image) {
            self::deleteWithFile($image['id']);
        }
    }
    
    /**
     * Get next sort order for car
     */
    private static function getNextSortOrder(int $carId): int
    {
        $result = Database::fetch(
            "SELECT MAX(sort_order) as max_order FROM car_images WHERE car_id = ?",
            [$carId]
        );
        
        return ($result['max_order'] ?? 0) + 1;
    }
    
    /**
     * Update sort order
     */
    public static function updateSortOrder(int $carId, array $imageIds): bool
    {
        foreach ($imageIds as $order => $imageId) {
            Database::query(
                "UPDATE car_images SET sort_order = ? WHERE id = ? AND car_id = ?",
                [$order, $imageId, $carId]
            );
        }
        
        return true;
    }
}
