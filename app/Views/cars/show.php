<?php
$primaryImage = null;
$images = $car['images'] ?? [];
foreach ($images as $img) {
    if ($img['is_primary']) {
        $primaryImage = $img;
        break;
    }
}
if (!$primaryImage && !empty($images)) {
    $primaryImage = $images[0];
}
?>

<!-- Breadcrumb -->
<div class="bg-white border-bottom py-3">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/">หน้าแรก</a></li>
                <li class="breadcrumb-item"><a href="/cars">ค้นหารถ</a></li>
                <li class="breadcrumb-item"><a href="/cars?brand_id=<?= $car['brand_id'] ?>"><?= e($car['brand_name']) ?></a></li>
                <li class="breadcrumb-item active"><?= e($car['title']) ?></li>
            </ol>
        </nav>
    </div>
</div>

<div class="container py-4">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Gallery -->
            <div class="card shadow-sm mb-4">
                <div class="card-body p-0">
                    <!-- Main Image -->
                    <div class="position-relative">
                        <?php if ($primaryImage): ?>
                            <a href="<?= upload_url('cars/' . $primaryImage['image_path']) ?>" class="glightbox" data-gallery="car-gallery">
                                <img src="<?= upload_url('cars/' . $primaryImage['image_path']) ?>" 
                                     class="img-fluid w-100 rounded-top" 
                                     id="main-image"
                                     style="max-height: 500px; object-fit: cover;"
                                     alt="<?= e($car['title']) ?>">
                            </a>
                        <?php else: ?>
                            <img src="<?= asset('images/no-image.png') ?>" class="img-fluid w-100 rounded-top" alt="No Image">
                        <?php endif; ?>
                        
                        <?php if ($car['is_featured']): ?>
                            <span class="badge bg-warning position-absolute top-0 start-0 m-3">
                                <i class="bi bi-star-fill"></i> แนะนำ
                            </span>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Thumbnails -->
                    <?php if (count($images) > 1): ?>
                        <div class="d-flex gap-2 p-3 overflow-auto">
                            <?php foreach ($images as $index => $img): ?>
                                <a href="<?= upload_url('cars/' . $img['image_path']) ?>" 
                                   class="glightbox <?= $index > 0 ? '' : '' ?>" 
                                   data-gallery="car-gallery">
                                    <img src="<?= upload_url('cars/' . $img['image_path']) ?>" 
                                         class="gallery-thumb rounded <?= $img['is_primary'] ? 'active border border-primary' : '' ?>"
                                         style="width: 80px; height: 60px; object-fit: cover;"
                                         onclick="document.getElementById('main-image').src = this.src"
                                         alt="รูปที่ <?= $index + 1 ?>">
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Title & Price -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h1 class="h3 mb-2"><?= e($car['title']) ?></h1>
                            <p class="text-muted mb-0">
                                <?= e($car['brand_name']) ?> <?= e($car['model_name']) ?> | 
                                ปี <?= e($car['year']) ?> | 
                                <?= format_number($car['mileage']) ?> กม.
                            </p>
                        </div>
                        <h2 class="text-primary mb-0"><?= format_price($car['price']) ?></h2>
                    </div>
                    
                    <div class="d-flex gap-3 text-muted small">
                        <span><i class="bi bi-eye me-1"></i><?= format_number($car['views']) ?> เข้าชม</span>
                        <span><i class="bi bi-calendar3 me-1"></i>ลงประกาศ <?= format_date($car['created_at']) ?></span>
                    </div>
                </div>
            </div>
            
            <!-- Specifications -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0"><i class="bi bi-list-check me-2"></i>ข้อมูลจำเพาะ</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6 col-md-4">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-building fs-4 text-primary me-3"></i>
                                <div>
                                    <small class="text-muted d-block">ยี่ห้อ</small>
                                    <strong><?= e($car['brand_name']) ?></strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-4">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-car-front fs-4 text-primary me-3"></i>
                                <div>
                                    <small class="text-muted d-block">รุ่น</small>
                                    <strong><?= e($car['model_name']) ?></strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-4">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-calendar3 fs-4 text-primary me-3"></i>
                                <div>
                                    <small class="text-muted d-block">ปี</small>
                                    <strong><?= e($car['year']) ?></strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-4">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-speedometer2 fs-4 text-primary me-3"></i>
                                <div>
                                    <small class="text-muted d-block">ระยะทาง</small>
                                    <strong><?= format_number($car['mileage']) ?> กม.</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-4">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-gear fs-4 text-primary me-3"></i>
                                <div>
                                    <small class="text-muted d-block">เกียร์</small>
                                    <strong><?= e(\App\Models\Car::TRANSMISSIONS[$car['transmission']] ?? $car['transmission']) ?></strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-4">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-fuel-pump fs-4 text-primary me-3"></i>
                                <div>
                                    <small class="text-muted d-block">เชื้อเพลิง</small>
                                    <strong><?= e(\App\Models\Car::FUELS[$car['fuel_type']] ?? $car['fuel_type']) ?></strong>
                                </div>
                            </div>
                        </div>
                        <?php if ($car['color']): ?>
                        <div class="col-6 col-md-4">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-palette fs-4 text-primary me-3"></i>
                                <div>
                                    <small class="text-muted d-block">สี</small>
                                    <strong><?= e($car['color']) ?></strong>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if ($car['engine_size']): ?>
                        <div class="col-6 col-md-4">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-lightning fs-4 text-primary me-3"></i>
                                <div>
                                    <small class="text-muted d-block">ขนาดเครื่องยนต์</small>
                                    <strong><?= e($car['engine_size']) ?> cc</strong>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <div class="col-6 col-md-4">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-geo-alt fs-4 text-primary me-3"></i>
                                <div>
                                    <small class="text-muted d-block">จังหวัด</small>
                                    <strong><?= e($car['province']) ?></strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Description -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0"><i class="bi bi-file-text me-2"></i>รายละเอียด</h5>
                </div>
                <div class="card-body">
                    <div class="description-content">
                        <?= nl2br(e($car['description'])) ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Seller Card -->
            <div class="card seller-card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3"><i class="bi bi-person-circle me-2"></i>ข้อมูลผู้ขาย</h5>
                    
                    <div class="mb-3">
                        <strong class="d-block"><?= e($car['seller_name']) ?></strong>
                        <small class="text-muted"><i class="bi bi-geo-alt"></i> <?= e($car['seller_province'] ?? $car['province']) ?></small>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <a href="tel:<?= e($car['seller_phone']) ?>" class="btn btn-primary btn-lg">
                            <i class="bi bi-telephone me-2"></i><?= e($car['seller_phone']) ?>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Inquiry Form -->
            <div class="card shadow-sm" id="inquiry-form">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0"><i class="bi bi-chat-dots me-2"></i>ส่งข้อความถึงผู้ขาย</h5>
                </div>
                <div class="card-body">
                    <?php 
                    $errors = \App\Core\Session::getErrors();
                    ?>
                    
                    <form action="/inquiries" method="POST">
                        <?= csrf_field() ?>
                        <input type="hidden" name="car_id" value="<?= $car['id'] ?>">
                        
                        <?php if (!auth()): ?>
                        <div class="mb-3">
                            <label class="form-label">ชื่อ <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>" 
                                   value="<?= e(old('name')) ?>" required>
                            <?php if (isset($errors['name'])): ?>
                                <div class="invalid-feedback"><?= e($errors['name'][0]) ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">อีเมล <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" 
                                   value="<?= e(old('email')) ?>" required>
                            <?php if (isset($errors['email'])): ?>
                                <div class="invalid-feedback"><?= e($errors['email'][0]) ?></div>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                        
                        <div class="mb-3">
                            <label class="form-label">เบอร์โทร <span class="text-danger">*</span></label>
                            <input type="tel" name="phone" class="form-control <?= isset($errors['phone']) ? 'is-invalid' : '' ?>" 
                                   value="<?= e(old('phone', auth()['phone'] ?? '')) ?>" required>
                            <?php if (isset($errors['phone'])): ?>
                                <div class="invalid-feedback"><?= e($errors['phone'][0]) ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">ข้อความ <span class="text-danger">*</span></label>
                            <textarea name="message" rows="4" class="form-control <?= isset($errors['message']) ? 'is-invalid' : '' ?>" 
                                      required placeholder="สนใจรถคันนี้ครับ/ค่ะ..."><?= e(old('message', 'สนใจรถคันนี้ ต้องการสอบถามรายละเอียดเพิ่มเติมครับ/ค่ะ')) ?></textarea>
                            <?php if (isset($errors['message'])): ?>
                                <div class="invalid-feedback"><?= e($errors['message'][0]) ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-send me-2"></i>ส่งข้อความ
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Related Cars -->
    <?php if (!empty($relatedCars)): ?>
    <section class="mt-5">
        <h3 class="mb-4">รถที่คล้ายกัน</h3>
        <div class="row g-4">
            <?php foreach ($relatedCars as $car): ?>
                <div class="col-md-6 col-lg-3">
                    <?php include BASE_PATH . '/app/Views/partials/car-card.php'; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>
</div>
