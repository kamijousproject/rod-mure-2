<?php
$images = $car['images'] ?? [];
$primaryImage = null;
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

<div class="container-fluid py-4">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= url('/admin') ?>">Admin</a></li>
            <li class="breadcrumb-item"><a href="<?= url('/admin/cars') ?>">จัดการประกาศ</a></li>
            <li class="breadcrumb-item active">รายละเอียด</li>
        </ol>
    </nav>
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">รายละเอียดประกาศ #<?= $car['id'] ?></h2>
        <div>
            <?php if ($car['status'] === 'pending'): ?>
                <form action="<?= url('/admin/cars/' . $car['id'] . '/approve') ?>" method="POST" class="d-inline">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-lg me-2"></i>อนุมัติ
                    </button>
                </form>
                <form action="<?= url('/admin/cars/' . $car['id'] . '/reject') ?>" method="POST" class="d-inline">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-x-lg me-2"></i>ปฏิเสธ
                    </button>
                </form>
            <?php endif; ?>
            <a href="<?= url('/admin/cars') ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>กลับ
            </a>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-8">
            <!-- Images -->
            <div class="card shadow-sm mb-4">
                <div class="card-body p-0">
                    <?php if ($primaryImage): ?>
                        <img src="<?= upload_url($primaryImage['image_path']) ?>" 
                             class="img-fluid w-100 rounded-top" 
                             style="max-height: 400px; object-fit: cover;"
                             alt="<?= e($car['title']) ?>">
                    <?php else: ?>
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 300px;">
                            <i class="bi bi-image text-muted display-1"></i>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (count($images) > 1): ?>
                        <div class="d-flex gap-2 p-3 overflow-auto">
                            <?php foreach ($images as $img): ?>
                                <img src="<?= upload_url($img['image_path']) ?>" 
                                     class="rounded <?= $img['is_primary'] ? 'border border-primary border-2' : '' ?>"
                                     style="width: 80px; height: 60px; object-fit: cover;"
                                     alt="รูปภาพ">
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Car Info -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0"><?= e($car['title']) ?></h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <small class="text-muted d-block">ยี่ห้อ</small>
                            <strong><?= e($car['brand_name'] ?? '-') ?></strong>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted d-block">รุ่น</small>
                            <strong><?= e($car['model_name'] ?? '-') ?></strong>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted d-block">ปี</small>
                            <strong><?= e($car['year']) ?></strong>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted d-block">ราคา</small>
                            <strong class="text-primary"><?= format_price($car['price']) ?></strong>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted d-block">ระยะทาง</small>
                            <strong><?= format_number($car['mileage']) ?> กม.</strong>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted d-block">เกียร์</small>
                            <strong><?= e(\App\Models\Car::TRANSMISSIONS[$car['transmission']] ?? $car['transmission']) ?></strong>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted d-block">เชื้อเพลิง</small>
                            <strong><?= e(\App\Models\Car::FUELS[$car['fuel_type']] ?? $car['fuel_type']) ?></strong>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted d-block">สี</small>
                            <strong><?= e($car['color'] ?? '-') ?></strong>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted d-block">จังหวัด</small>
                            <strong><?= e($car['province']) ?></strong>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Description -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0">รายละเอียด</h5>
                </div>
                <div class="card-body">
                    <div style="white-space: pre-line;"><?= e($car['description']) ?></div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Status -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0">สถานะ</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>สถานะ</span>
                        <span class="badge bg-<?= match($car['status']) {
                            'published' => 'success',
                            'pending' => 'warning',
                            'sold' => 'info',
                            'rejected' => 'danger',
                            default => 'secondary'
                        } ?>">
                            <?= e(\App\Models\Car::STATUSES[$car['status']] ?? $car['status']) ?>
                        </span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>แนะนำ</span>
                        <span>
                            <?php if ($car['is_featured']): ?>
                                <i class="bi bi-star-fill text-warning"></i> ใช่
                            <?php else: ?>
                                <i class="bi bi-star text-muted"></i> ไม่
                            <?php endif; ?>
                        </span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>เข้าชม</span>
                        <strong><?= format_number($car['views']) ?></strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>ลงประกาศ</span>
                        <span><?= format_date($car['created_at'], 'd M Y H:i') ?></span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>แก้ไขล่าสุด</span>
                        <span><?= format_date($car['updated_at'], 'd M Y H:i') ?></span>
                    </div>
                </div>
            </div>
            
            <!-- Seller Info -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0">ข้อมูลผู้ขาย</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2"><i class="bi bi-person me-2"></i><?= e($car['seller_name'] ?? '-') ?></p>
                    <p class="mb-2"><i class="bi bi-envelope me-2"></i><?= e($car['seller_email'] ?? '-') ?></p>
                    <p class="mb-0"><i class="bi bi-telephone me-2"></i><?= e($car['seller_phone'] ?? '-') ?></p>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="card shadow-sm">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0">จัดการ</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <form action="<?= url('/admin/cars/' . $car['id'] . '/feature') ?>" method="POST">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-<?= $car['is_featured'] ? 'warning' : 'outline-warning' ?> w-100">
                                <i class="bi bi-star<?= $car['is_featured'] ? '-fill' : '' ?> me-2"></i>
                                <?= $car['is_featured'] ? 'ยกเลิกแนะนำ' : 'ตั้งเป็นรถแนะนำ' ?>
                            </button>
                        </form>
                        
                        <a href="<?= url('/cars/' . $car['slug']) ?>" target="_blank" class="btn btn-outline-primary">
                            <i class="bi bi-eye me-2"></i>ดูหน้าประกาศ
                        </a>
                        
                        <form action="<?= url('/admin/cars/' . $car['id'] . '/delete') ?>" method="POST" 
                              onsubmit="return confirm('ยืนยันการลบประกาศนี้?')">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="bi bi-trash me-2"></i>ลบประกาศ
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
