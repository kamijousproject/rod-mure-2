<?php
/**
 * CarController - Handles car listings display
 */

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Session;
use App\Models\Car;
use App\Models\Brand;
use App\Models\CarModel;

class CarController extends BaseController
{
    /**
     * Display car listings with filters
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
            'mileage_from' => $this->input('mileage_from'),
            'mileage_to' => $this->input('mileage_to'),
            'transmission' => $this->input('transmission'),
            'fuel_type' => $this->input('fuel_type'),
            'province' => $this->input('province'),
            'sort' => $this->input('sort', 'newest'),
        ];
        
        $page = (int)($this->input('page', 1));
        $perPage = $this->config['pagination']['per_page'];
        
        $result = Car::search($filters, $page, $perPage);
        $brands = Brand::getActive();
        
        // Get models if brand is selected
        $models = [];
        if (!empty($filters['brand_id'])) {
            $models = CarModel::getByBrand((int)$filters['brand_id']);
        }
        
        $this->view('cars.index', [
            'title' => 'ค้นหารถมือสอง',
            'cars' => $result['items'],
            'pagination' => $result,
            'filters' => $filters,
            'brands' => $brands,
            'models' => $models,
            'transmissions' => Car::TRANSMISSIONS,
            'fuels' => Car::FUELS,
            'provinces' => $this->getProvinces(),
        ]);
    }
    
    /**
     * Display single car details
     */
    public function show(string $slug): void
    {
        $car = Car::findBySlug($slug);
        
        if (!$car) {
            http_response_code(404);
            $this->view('errors.404', ['title' => 'ไม่พบรถ']);
            return;
        }
        
        // Increment view count
        Car::incrementViews($car['id']);
        
        // Get related cars
        $relatedCars = Car::getRelated($car['id'], $car['brand_id'], 4);
        
        $this->view('cars.show', [
            'title' => $car['title'] . ' - รายละเอียดรถ',
            'description' => str_limit(strip_tags($car['description']), 160),
            'car' => $car,
            'relatedCars' => $relatedCars,
        ]);
    }
    
    /**
     * AJAX search endpoint
     */
    public function search(): void
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
        $perPage = $this->config['pagination']['per_page'];
        
        $result = Car::search($filters, $page, $perPage);
        
        // Format cars for JSON response
        $cars = array_map(function($car) {
            return [
                'id' => $car['id'],
                'title' => $car['title'],
                'slug' => $car['slug'],
                'price' => format_price($car['price']),
                'price_raw' => $car['price'],
                'year' => $car['year'],
                'mileage' => format_number($car['mileage']),
                'brand' => $car['brand_name'],
                'model' => $car['model_name'],
                'transmission' => Car::TRANSMISSIONS[$car['transmission']] ?? $car['transmission'],
                'fuel' => Car::FUELS[$car['fuel_type']] ?? $car['fuel_type'],
                'province' => $car['province'],
                'image' => $car['primary_image'] ? upload_url('cars/' . $car['primary_image']) : asset('images/no-image.png'),
                'url' => '/cars/' . $car['slug'],
                'is_featured' => (bool)$car['is_featured'],
            ];
        }, $result['items']);
        
        $this->json([
            'success' => true,
            'data' => $cars,
            'pagination' => [
                'total' => $result['total'],
                'page' => $result['page'],
                'per_page' => $result['per_page'],
                'total_pages' => $result['total_pages'],
                'has_more' => $result['has_more'],
            ],
        ]);
    }
    
    /**
     * Get Thai provinces list
     */
    private function getProvinces(): array
    {
        return [
            'กรุงเทพมหานคร', 'กระบี่', 'กาญจนบุรี', 'กาฬสินธุ์', 'กำแพงเพชร',
            'ขอนแก่น', 'จันทบุรี', 'ฉะเชิงเทรา', 'ชลบุรี', 'ชัยนาท',
            'ชัยภูมิ', 'ชุมพร', 'เชียงราย', 'เชียงใหม่', 'ตรัง',
            'ตราด', 'ตาก', 'นครนายก', 'นครปฐม', 'นครพนม',
            'นครราชสีมา', 'นครศรีธรรมราช', 'นครสวรรค์', 'นนทบุรี', 'นราธิวาส',
            'น่าน', 'บึงกาฬ', 'บุรีรัมย์', 'ปทุมธานี', 'ประจวบคีรีขันธ์',
            'ปราจีนบุรี', 'ปัตตานี', 'พระนครศรีอยุธยา', 'พังงา', 'พัทลุง',
            'พิจิตร', 'พิษณุโลก', 'เพชรบุรี', 'เพชรบูรณ์', 'แพร่',
            'พะเยา', 'ภูเก็ต', 'มหาสารคาม', 'มุกดาหาร', 'แม่ฮ่องสอน',
            'ยะลา', 'ยโสธร', 'ร้อยเอ็ด', 'ระนอง', 'ระยอง',
            'ราชบุรี', 'ลพบุรี', 'ลำปาง', 'ลำพูน', 'เลย',
            'ศรีสะเกษ', 'สกลนคร', 'สงขลา', 'สตูล', 'สมุทรปราการ',
            'สมุทรสงคราม', 'สมุทรสาคร', 'สระแก้ว', 'สระบุรี', 'สิงห์บุรี',
            'สุโขทัย', 'สุพรรณบุรี', 'สุราษฎร์ธานี', 'สุรินทร์', 'หนองคาย',
            'หนองบัวลำภู', 'อ่างทอง', 'อุดรธานี', 'อุทัยธานี', 'อุตรดิตถ์',
            'อุบลราชธานี', 'อำนาจเจริญ',
        ];
    }
}
