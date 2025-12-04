<?php
/**
 * API BrandController - Brands and Models API
 */

namespace App\Controllers\Api;

use App\Core\BaseController;
use App\Models\Brand;
use App\Models\CarModel;

class BrandController extends BaseController
{
    /**
     * Get all brands
     * GET /api/brands
     */
    public function index(): void
    {
        $withCount = $this->input('with_count', false);
        
        if ($withCount) {
            $brands = Brand::withCarCount();
        } else {
            $brands = Brand::getActive();
        }
        
        $data = array_map(function($brand) use ($withCount) {
            $result = [
                'id' => (int)$brand['id'],
                'name' => $brand['name'],
                'slug' => $brand['slug'],
            ];
            
            if ($withCount && isset($brand['car_count'])) {
                $result['car_count'] = (int)$brand['car_count'];
            }
            
            return $result;
        }, $brands);
        
        $this->json([
            'success' => true,
            'data' => $data,
        ]);
    }
}
