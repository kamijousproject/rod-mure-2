<?php
/**
 * API ModelController - Car Models API
 */

namespace App\Controllers\Api;

use App\Core\BaseController;
use App\Models\CarModel;

class ModelController extends BaseController
{
    /**
     * Get models (optionally by brand)
     * GET /api/models?brand_id=1
     */
    public function index(): void
    {
        $brandId = $this->input('brand_id');
        
        if ($brandId) {
            $models = CarModel::getByBrand((int)$brandId);
        } else {
            $models = CarModel::withBrand();
        }
        
        $data = array_map(function($model) {
            return [
                'id' => (int)$model['id'],
                'name' => $model['name'],
                'slug' => $model['slug'],
                'brand_id' => (int)$model['brand_id'],
                'brand_name' => $model['brand_name'] ?? null,
            ];
        }, $models);
        
        $this->json([
            'success' => true,
            'data' => $data,
        ]);
    }
}
