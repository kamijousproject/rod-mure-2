<?php
/**
 * API CarController - REST API for cars
 */

namespace App\Controllers\Api;

use App\Core\BaseController;
use App\Core\Auth;
use App\Models\Car;
use App\Models\CarImage;

class CarController extends BaseController
{
    /**
     * Get car listings
     * GET /api/cars
     */
    public function index(): void
    {
        $filters = [
            'q' => $this->input('q'),
            'brand_id' => $this->input('brand_id'),
            'model_id' => $this->input('model_id'),
            'year_from' => $this->input('year_from'),
            'year_to' => $this->input('year_to'),
            'price_from' => $this->input('price_from'),
            'price_to' => $this->input('price_to'),
            'transmission' => $this->input('transmission'),
            'fuel_type' => $this->input('fuel_type'),
            'province' => $this->input('province'),
            'sort' => $this->input('sort', 'newest'),
        ];
        
        $page = (int)($this->input('page', 1));
        $perPage = min((int)($this->input('per_page', 12)), 50);
        
        $result = Car::search($filters, $page, $perPage);
        
        // Format response
        $cars = array_map(function($car) {
            return $this->formatCar($car);
        }, $result['items']);
        
        $this->json([
            'success' => true,
            'data' => $cars,
            'meta' => [
                'total' => $result['total'],
                'page' => $result['page'],
                'per_page' => $result['per_page'],
                'total_pages' => $result['total_pages'],
                'has_more' => $result['has_more'],
            ],
        ]);
    }
    
    /**
     * Get single car
     * GET /api/cars/{id}
     */
    public function show(string $id): void
    {
        $car = Car::findWithRelations((int)$id);
        
        if (!$car || $car['status'] !== 'published') {
            $this->json(['success' => false, 'error' => 'Car not found'], 404);
        }
        
        Car::incrementViews((int)$id);
        
        $this->json([
            'success' => true,
            'data' => $this->formatCarDetail($car),
        ]);
    }
    
    /**
     * Create car listing (requires auth)
     * POST /api/cars
     */
    public function store(): void
    {
        $this->requireApiAuth();
        
        if (!Auth::isSeller()) {
            $this->json(['success' => false, 'error' => 'Seller access required'], 403);
        }
        
        $data = $this->getJsonInput();
        
        // Validate
        $required = ['brand_id', 'model_id', 'title', 'description', 'price', 'year', 'mileage', 'transmission', 'fuel_type', 'province'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                $this->json(['success' => false, 'error' => "Field '{$field}' is required"], 400);
            }
        }
        
        $data['user_id'] = Auth::id();
        $data['slug'] = Car::generateSlug($data['title']);
        $data['status'] = Car::STATUS_PENDING;
        $data['views'] = 0;
        
        $carId = Car::create($data);
        
        $this->json([
            'success' => true,
            'message' => 'Car created successfully',
            'data' => ['id' => $carId],
        ], 201);
    }
    
    /**
     * Update car listing (requires auth)
     * PUT /api/cars/{id}
     */
    public function update(string $id): void
    {
        $this->requireApiAuth();
        
        $car = Car::find((int)$id);
        
        if (!$car) {
            $this->json(['success' => false, 'error' => 'Car not found'], 404);
        }
        
        if ($car['user_id'] != Auth::id() && !Auth::isAdmin()) {
            $this->json(['success' => false, 'error' => 'Unauthorized'], 403);
        }
        
        $data = $this->getJsonInput();
        
        // Update slug if title changed
        if (!empty($data['title']) && $data['title'] !== $car['title']) {
            $data['slug'] = Car::generateSlug($data['title'], (int)$id);
        }
        
        Car::update((int)$id, $data);
        
        $this->json([
            'success' => true,
            'message' => 'Car updated successfully',
        ]);
    }
    
    /**
     * Delete car listing (requires auth)
     * DELETE /api/cars/{id}
     */
    public function destroy(string $id): void
    {
        $this->requireApiAuth();
        
        $car = Car::find((int)$id);
        
        if (!$car) {
            $this->json(['success' => false, 'error' => 'Car not found'], 404);
        }
        
        if ($car['user_id'] != Auth::id() && !Auth::isAdmin()) {
            $this->json(['success' => false, 'error' => 'Unauthorized'], 403);
        }
        
        CarImage::deleteAllForCar((int)$id);
        Car::delete((int)$id);
        
        $this->json([
            'success' => true,
            'message' => 'Car deleted successfully',
        ]);
    }
    
    /**
     * Format car for list response
     */
    private function formatCar(array $car): array
    {
        return [
            'id' => (int)$car['id'],
            'title' => $car['title'],
            'slug' => $car['slug'],
            'price' => (int)$car['price'],
            'price_formatted' => format_price($car['price']),
            'year' => (int)$car['year'],
            'mileage' => (int)$car['mileage'],
            'brand' => $car['brand_name'] ?? null,
            'model' => $car['model_name'] ?? null,
            'transmission' => $car['transmission'],
            'fuel_type' => $car['fuel_type'],
            'province' => $car['province'],
            'image' => $car['primary_image'] ? upload_url('cars/' . $car['primary_image']) : null,
            'is_featured' => (bool)($car['is_featured'] ?? false),
            'views' => (int)($car['views'] ?? 0),
        ];
    }
    
    /**
     * Format car for detail response
     */
    private function formatCarDetail(array $car): array
    {
        $images = array_map(function($img) {
            return [
                'id' => (int)$img['id'],
                'url' => upload_url('cars/' . $img['image_path']),
                'is_primary' => (bool)$img['is_primary'],
            ];
        }, $car['images'] ?? []);
        
        return [
            'id' => (int)$car['id'],
            'title' => $car['title'],
            'slug' => $car['slug'],
            'description' => $car['description'],
            'price' => (int)$car['price'],
            'price_formatted' => format_price($car['price']),
            'year' => (int)$car['year'],
            'mileage' => (int)$car['mileage'],
            'color' => $car['color'],
            'engine_size' => $car['engine_size'],
            'transmission' => $car['transmission'],
            'fuel_type' => $car['fuel_type'],
            'vin' => $car['vin'],
            'province' => $car['province'],
            'brand' => [
                'id' => (int)$car['brand_id'],
                'name' => $car['brand_name'],
            ],
            'model' => [
                'id' => (int)$car['model_id'],
                'name' => $car['model_name'],
            ],
            'seller' => [
                'name' => $car['seller_name'],
                'phone' => $car['seller_phone'],
                'email' => $car['seller_email'],
                'province' => $car['seller_province'],
            ],
            'images' => $images,
            'views' => (int)$car['views'],
            'created_at' => $car['created_at'],
        ];
    }
    
    /**
     * Get JSON input
     */
    private function getJsonInput(): array
    {
        $input = file_get_contents('php://input');
        return json_decode($input, true) ?? [];
    }
    
    /**
     * Require API authentication
     */
    private function requireApiAuth(): void
    {
        // Check Bearer token
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        
        if (preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            $token = $matches[1];
            
            // Validate token (simple implementation)
            $tokenData = $this->validateToken($token);
            
            if ($tokenData && isset($tokenData['user_id'])) {
                $user = \App\Models\User::find($tokenData['user_id']);
                if ($user && $user['status'] === 'active') {
                    $_SESSION['user'] = $user;
                    return;
                }
            }
        }
        
        $this->json(['success' => false, 'error' => 'Unauthorized'], 401);
    }
    
    /**
     * Validate JWT-like token (simple implementation)
     */
    private function validateToken(string $token): ?array
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return null;
        }
        
        [$header, $payload, $signature] = $parts;
        
        // Verify signature
        $expectedSignature = hash_hmac('sha256', "$header.$payload", config('app.key'));
        if (!hash_equals($expectedSignature, $signature)) {
            return null;
        }
        
        // Decode payload
        $data = json_decode(base64_decode($payload), true);
        
        // Check expiration
        if (isset($data['exp']) && $data['exp'] < time()) {
            return null;
        }
        
        return $data;
    }
}
