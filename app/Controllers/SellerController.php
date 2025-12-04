<?php
/**
 * SellerController - Handles seller car management
 */

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Auth;
use App\Core\CSRF;
use App\Core\Session;
use App\Models\Car;
use App\Models\CarImage;
use App\Models\Brand;
use App\Models\CarModel;

class SellerController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
        
        // Must be seller or admin
        if (!Auth::isSeller()) {
            Session::setFlash('error', 'คุณต้องเป็นผู้ขายเพื่อเข้าถึงหน้านี้');
            redirect('/dashboard');
        }
    }
    
    /**
     * Display seller's car listings
     */
    public function index(): void
    {
        $status = $this->input('status');
        $page = (int)$this->input('page', 1);
        
        $result = Car::getByUser(Auth::id(), $status, $page, 10);
        
        $this->view('seller.cars.index', [
            'title' => 'รถของฉัน',
            'cars' => $result['items'],
            'pagination' => $result,
            'currentStatus' => $status,
            'statuses' => Car::STATUSES,
        ]);
    }
    
    /**
     * Show create car form
     */
    public function create(): void
    {
        $brands = Brand::getActive();
        
        $this->view('seller.cars.form', [
            'title' => 'ลงประกาศขายรถ',
            'brands' => $brands,
            'models' => [],
            'transmissions' => Car::TRANSMISSIONS,
            'fuels' => Car::FUELS,
            'provinces' => $this->getProvinces(),
            'car' => null,
            'images' => [],
        ]);
    }
    
    /**
     * Store new car listing
     */
    public function store(): void
    {
        CSRF::check();
        
        $data = $this->getCarData();
        
        // Validate
        $errors = $this->validateCarData($data);
        
        if (!empty($errors)) {
            Session::setErrors($errors);
            Session::setOld($data);
            $this->redirect('/my-cars/create');
        }
        
        // Prepare data
        $data['user_id'] = Auth::id();
        $data['slug'] = Car::generateSlug($data['title']);
        $data['status'] = $data['status'] ?? Car::STATUS_PENDING;
        $data['views'] = 0;
        
        // Create car
        $carId = Car::create($data);
        
        // Handle images
        $this->handleImages($carId);
        
        Session::setFlash('success', 'ลงประกาศสำเร็จ');
        $this->redirect('/my-cars');
    }
    
    /**
     * Show edit car form
     */
    public function edit(string $id): void
    {
        $car = Car::find((int)$id);
        
        if (!$car || $car['user_id'] != Auth::id()) {
            Session::setFlash('error', 'ไม่พบรถที่ต้องการแก้ไข');
            $this->redirect('/my-cars');
        }
        
        $brands = Brand::getActive();
        $models = CarModel::getByBrand($car['brand_id']);
        $images = CarImage::getByCarId($car['id']);
        
        $this->view('seller.cars.form', [
            'title' => 'แก้ไขประกาศ',
            'brands' => $brands,
            'models' => $models,
            'transmissions' => Car::TRANSMISSIONS,
            'fuels' => Car::FUELS,
            'provinces' => $this->getProvinces(),
            'car' => $car,
            'images' => $images,
        ]);
    }
    
    /**
     * Update car listing
     */
    public function update(string $id): void
    {
        CSRF::check();
        
        $car = Car::find((int)$id);
        
        if (!$car || $car['user_id'] != Auth::id()) {
            Session::setFlash('error', 'ไม่พบรถที่ต้องการแก้ไข');
            $this->redirect('/my-cars');
        }
        
        $data = $this->getCarData();
        
        // Validate
        $errors = $this->validateCarData($data, (int)$id);
        
        if (!empty($errors)) {
            Session::setErrors($errors);
            Session::setOld($data);
            $this->redirect("/my-cars/{$id}/edit");
        }
        
        // Update slug if title changed
        if ($data['title'] !== $car['title']) {
            $data['slug'] = Car::generateSlug($data['title'], (int)$id);
        }
        
        // If was rejected, set back to pending for review
        if ($car['status'] === Car::STATUS_REJECTED) {
            $data['status'] = Car::STATUS_PENDING;
        }
        
        // Update car
        Car::update((int)$id, $data);
        
        // Handle images
        $this->handleImages((int)$id);
        
        Session::setFlash('success', 'อัปเดตประกาศสำเร็จ');
        $this->redirect('/my-cars');
    }
    
    /**
     * Delete car listing
     */
    public function destroy(string $id): void
    {
        CSRF::check();
        
        $car = Car::find((int)$id);
        
        if (!$car || $car['user_id'] != Auth::id()) {
            Session::setFlash('error', 'ไม่พบรถที่ต้องการลบ');
            $this->redirect('/my-cars');
        }
        
        // Delete images first
        CarImage::deleteAllForCar((int)$id);
        
        // Delete car
        Car::delete((int)$id);
        
        Session::setFlash('success', 'ลบประกาศสำเร็จ');
        $this->redirect('/my-cars');
    }
    
    /**
     * Update car status
     */
    public function updateStatus(string $id): void
    {
        CSRF::check();
        
        $car = Car::find((int)$id);
        
        if (!$car || $car['user_id'] != Auth::id()) {
            $this->json(['success' => false, 'message' => 'ไม่พบรถ'], 404);
        }
        
        $status = $this->input('status');
        
        if (!in_array($status, [Car::STATUS_DRAFT, Car::STATUS_PENDING, Car::STATUS_SOLD])) {
            $this->json(['success' => false, 'message' => 'สถานะไม่ถูกต้อง'], 400);
        }
        
        Car::update((int)$id, ['status' => $status]);
        
        $this->json(['success' => true, 'message' => 'อัปเดตสถานะสำเร็จ']);
    }
    
    /**
     * Get car data from request
     */
    private function getCarData(): array
    {
        return [
            'brand_id' => $this->input('brand_id'),
            'model_id' => $this->input('model_id'),
            'title' => $this->input('title'),
            'description' => $this->input('description'),
            'price' => $this->input('price'),
            'year' => $this->input('year'),
            'mileage' => $this->input('mileage'),
            'color' => $this->input('color'),
            'engine_size' => $this->input('engine_size'),
            'transmission' => $this->input('transmission'),
            'fuel_type' => $this->input('fuel_type'),
            'vin' => $this->input('vin'),
            'province' => $this->input('province'),
            'status' => $this->input('status', Car::STATUS_PENDING),
        ];
    }
    
    /**
     * Validate car data
     */
    private function validateCarData(array $data, ?int $exceptId = null): array
    {
        return $this->validate($data, [
            'brand_id' => 'required|integer',
            'model_id' => 'required|integer',
            'title' => 'required|min:10|max:200',
            'description' => 'required|min:50',
            'price' => 'required|numeric',
            'year' => 'required|integer',
            'mileage' => 'required|integer',
            'color' => 'required|max:50',
            'transmission' => 'required|in:' . implode(',', array_keys(Car::TRANSMISSIONS)),
            'fuel_type' => 'required|in:' . implode(',', array_keys(Car::FUELS)),
            'province' => 'required|max:100',
        ]);
    }
    
    /**
     * Handle image uploads
     */
    private function handleImages(int $carId): void
    {
        // Handle deleted images
        $deletedImages = $this->input('deleted_images');
        if ($deletedImages) {
            $deletedIds = explode(',', $deletedImages);
            foreach ($deletedIds as $imageId) {
                if ($imageId) {
                    CarImage::deleteWithFile((int)$imageId);
                }
            }
        }
        
        // Handle new images (already uploaded via AJAX)
        $newImages = $this->input('new_images');
        if ($newImages) {
            $imagePaths = explode(',', $newImages);
            $isFirst = empty(CarImage::getByCarId($carId));
            
            foreach ($imagePaths as $path) {
                if ($path) {
                    CarImage::addToCar($carId, $path, $isFirst);
                    $isFirst = false;
                }
            }
        }
        
        // Handle primary image
        $primaryImageId = $this->input('primary_image_id');
        if ($primaryImageId) {
            CarImage::setPrimary($carId, (int)$primaryImageId);
        }
    }
    
    /**
     * Get Thai provinces
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
