<!-- Hero Section - Modern Minimal -->
<section class="hero-section">
    <div class="container position-relative" style="z-index: 1;">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-5 mb-lg-0">
                <span class="badge bg-white bg-opacity-10 text-white mb-3 px-3 py-2" style="font-size: 0.85rem;">
                    🚗 แพลตฟอร์มซื้อขายรถมือสอง #1
                </span>
                <h1 class="mb-4">ค้นหารถในฝัน<br>ได้ง่ายกว่าที่คิด</h1>
                <p class="lead mb-0" style="max-width: 450px;">
                    เชื่อมต่อผู้ซื้อและผู้ขายรถยนต์มือสองทั่วประเทศ ปลอดภัย โปร่งใส ไว้วางใจได้
                </p>
            </div>
            <div class="col-lg-6">
                <!-- Search Box - Modern -->
                <div class="search-box">
                    <h5 class="mb-4 text-dark fw-semibold">ค้นหารถที่ต้องการ</h5>
                    <form action="<?= url('/cars') ?>" method="GET">
                        <div class="mb-3">
                            <input type="text" name="q" class="form-control form-control-lg" placeholder="พิมพ์ยี่ห้อ รุ่น หรือคำค้นหา...">
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <select name="brand_id" class="form-select">
                                    <option value="">ยี่ห้อทั้งหมด</option>
                                    <?php foreach ($brands as $brand): ?>
                                        <option value="<?= $brand['id'] ?>"><?= e($brand['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-6">
                                <select name="price_to" class="form-select">
                                    <option value="">งบประมาณ</option>
                                    <option value="300000">ไม่เกิน 300,000</option>
                                    <option value="500000">ไม่เกิน 500,000</option>
                                    <option value="800000">ไม่เกิน 800,000</option>
                                    <option value="1000000">ไม่เกิน 1,000,000</option>
                                    <option value="2000000">ไม่เกิน 2,000,000</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-accent btn-lg w-100">
                            <i class="bi bi-search me-2"></i>ค้นหาเลย
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section - Minimal -->
<section class="py-5">
    <div class="container">
        <div class="row g-4 justify-content-center">
            <div class="col-6 col-md-3">
                <div class="stat-card">
                    <div class="stat-value">1,000+</div>
                    <div class="stat-label">รถประกาศขาย</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card">
                    <div class="stat-value">500+</div>
                    <div class="stat-label">ผู้ขายที่ไว้ใจ</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card">
                    <div class="stat-value">77</div>
                    <div class="stat-label">จังหวัดทั่วไทย</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card">
                    <div class="stat-value">24/7</div>
                    <div class="stat-label">เปิดให้บริการ</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Cars -->
<?php if (!empty($featuredCars)): ?>
<section class="py-5 bg-white">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1">รถแนะนำ</h2>
                <p class="text-muted mb-0">คัดสรรรถคุณภาพดีจากผู้ขายชั้นนำ</p>
            </div>
            <a href="<?= url('/cars?featured=1') ?>" class="btn btn-outline-primary d-none d-md-inline-flex">
                ดูทั้งหมด <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>
        
        <div class="row g-4">
            <?php foreach ($featuredCars as $car): ?>
                <div class="col-md-6 col-lg-4">
                    <?php include BASE_PATH . '/app/Views/partials/car-card.php'; ?>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-4 d-md-none">
            <a href="<?= url('/cars?featured=1') ?>" class="btn btn-outline-primary">ดูทั้งหมด</a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Brands Section - Modern Grid -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="mb-2">ยี่ห้อยอดนิยม</h2>
            <p class="text-muted">เลือกดูรถตามยี่ห้อที่คุณสนใจ</p>
        </div>
        <div class="row g-3 justify-content-center">
            <?php foreach (array_slice($brands, 0, 8) as $brand): ?>
                <div class="col-6 col-sm-4 col-md-3 col-lg-auto">
                    <a href="<?= url('/cars?brand_id=' . $brand['id']) ?>" class="text-decoration-none">
                        <div class="card h-100 text-center px-4 py-3" style="min-width: 140px;">
                            <div class="fw-semibold text-dark"><?= e($brand['name']) ?></div>
                            <small class="text-muted"><?= $brand['car_count'] ?? 0 ?> รายการ</small>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Latest Cars -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1">เพิ่งลงประกาศ</h2>
                <p class="text-muted mb-0">รถมือสองที่เพิ่งเข้าใหม่ล่าสุด</p>
            </div>
            <a href="<?= url('/cars') ?>" class="btn btn-outline-primary d-none d-md-inline-flex">
                ดูทั้งหมด <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>
        
        <div class="row g-4">
            <?php foreach ($latestCars as $car): ?>
                <div class="col-6 col-lg-3">
                    <?php include BASE_PATH . '/app/Views/partials/car-card.php'; ?>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-4 d-md-none">
            <a href="<?= url('/cars') ?>" class="btn btn-outline-primary">ดูทั้งหมด</a>
        </div>
    </div>
</section>

<!-- CTA Section - Modern -->
<section class="py-5" style="background: linear-gradient(135deg, var(--primary) 0%, #1e3a5f 100%);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 text-white mb-4 mb-lg-0">
                <h2 class="mb-2">พร้อมขายรถของคุณแล้วหรือยัง?</h2>
                <p class="mb-0 opacity-75">ลงประกาศฟรี เข้าถึงผู้ซื้อหลายพันคน ขายได้เร็วขึ้น</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="<?= url('/register?role=seller') ?>" class="btn btn-light btn-lg px-4">
                    <i class="bi bi-plus-lg me-2"></i>ลงประกาศขายรถ
                </a>
            </div>
        </div>
    </div>
</section>
