<?php
/**
 * HomeController - Handles homepage
 */

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\Car;
use App\Models\Brand;

class HomeController extends BaseController
{
    /**
     * Display homepage
     */
    public function index(): void
    {
        $featuredCars = Car::getFeatured(6);
        $latestCars = Car::getLatest(8);
        $brands = Brand::withCarCount();
        
        $this->view('home.index', [
            'title' => 'หน้าแรก - ตลาดรถมือสอง',
            'featuredCars' => $featuredCars,
            'latestCars' => $latestCars,
            'brands' => $brands,
        ]);
    }
}
