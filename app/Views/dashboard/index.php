<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">แดชบอร์ด</h2>
        <?php if (in_array($user['role'], ['seller', 'admin'])): ?>
            <a href="<?= url('/my-cars/create') ?>" class="btn btn-primary">
                <i class="bi bi-plus-lg me-2"></i>ลงประกาศขายรถ
            </a>
        <?php endif; ?>
    </div>
    
    <!-- Welcome Card -->
    <div class="card bg-primary text-white mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col">
                    <h4 class="mb-1">สวัสดี, <?= e($user['name']) ?>!</h4>
                    <p class="mb-0 opacity-75">
                        <?= match($user['role']) {
                            'admin' => 'ผู้ดูแลระบบ',
                            'seller' => 'ผู้ขาย',
                            default => 'ผู้ซื้อ'
                        } ?>
                    </p>
                </div>
                <div class="col-auto">
                    <i class="bi bi-person-circle display-4 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    
    <?php if (in_array($user['role'], ['seller', 'admin'])): ?>
    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">ประกาศทั้งหมด</h6>
                            <h3 class="mb-0"><?= number_format($stats['total_listings'] ?? 0) ?></h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-car-front text-primary fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">กำลังขาย</h6>
                            <h3 class="mb-0"><?= number_format($stats['active_listings'] ?? 0) ?></h3>
                        </div>
                        <div class="bg-success bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-check-circle text-success fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">ขายแล้ว</h6>
                            <h3 class="mb-0"><?= number_format($stats['sold_listings'] ?? 0) ?></h3>
                        </div>
                        <div class="bg-info bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-trophy text-info fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">ยอดเข้าชม</h6>
                            <h3 class="mb-0"><?= number_format($stats['total_views'] ?? 0) ?></h3>
                        </div>
                        <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-eye text-warning fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Cars -->
    <?php if (!empty($recentCars)): ?>
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
            <h5 class="mb-0">รถของฉัน</h5>
            <a href="<?= url('/my-cars') ?>" class="btn btn-sm btn-outline-primary">ดูทั้งหมด</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>รถ</th>
                            <th>ราคา</th>
                            <th>สถานะ</th>
                            <th>เข้าชม</th>
                            <th>สอบถาม</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentCars as $car): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <?php if ($car['primary_image']): ?>
                                        <img src="<?= upload_url($car['primary_image']) ?>" 
                                             class="rounded me-2" style="width: 50px; height: 40px; object-fit: cover;">
                                    <?php endif; ?>
                                    <div>
                                        <strong><?= e(str_limit($car['title'], 30)) ?></strong>
                                        <br><small class="text-muted"><?= e($car['brand_name']) ?> <?= e($car['model_name']) ?></small>
                                    </div>
                                </div>
                            </td>
                            <td><?= format_price($car['price']) ?></td>
                            <td>
                                <span class="badge bg-<?= match($car['status']) {
                                    'published' => 'success',
                                    'pending' => 'warning',
                                    'sold' => 'info',
                                    'rejected' => 'danger',
                                    default => 'secondary'
                                } ?>">
                                    <?= e(\App\Models\Car::STATUSES[$car['status']] ?? $car['status']) ?>
                                </span>
                            </td>
                            <td><?= number_format($car['views']) ?></td>
                            <td><?= number_format($car['inquiry_count'] ?? 0) ?></td>
                            <td>
                                <a href="<?= url('/my-cars/' . $car['id'] . '/edit') ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <?php endif; ?>
    
    <!-- Recent Inquiries -->
    <div class="card shadow-sm">
        <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                ข้อความสอบถาม
                <?php if ($unreadInquiries > 0): ?>
                    <span class="badge bg-danger"><?= $unreadInquiries ?> ใหม่</span>
                <?php endif; ?>
            </h5>
            <a href="<?= url('/inquiries') ?>" class="btn btn-sm btn-outline-primary">ดูทั้งหมด</a>
        </div>
        <div class="card-body">
            <?php if (empty($recentInquiries)): ?>
                <p class="text-muted text-center mb-0">ยังไม่มีข้อความสอบถาม</p>
            <?php else: ?>
                <div class="list-group list-group-flush">
                    <?php foreach ($recentInquiries as $inquiry): ?>
                    <a href="<?= url('/inquiries/' . $inquiry['id']) ?>" class="list-group-item list-group-item-action <?= $inquiry['status'] === 'unread' ? 'bg-light' : '' ?>">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong><?= e($inquiry['name']) ?></strong>
                                <?php if ($inquiry['status'] === 'unread'): ?>
                                    <span class="badge bg-primary ms-2">ใหม่</span>
                                <?php endif; ?>
                                <br>
                                <small class="text-muted">
                                    <?= e($inquiry['car_title']) ?> - <?= e(str_limit($inquiry['message'], 50)) ?>
                                </small>
                            </div>
                            <small class="text-muted"><?= format_date($inquiry['created_at'], 'd M H:i') ?></small>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
