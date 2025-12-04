<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Admin Dashboard</h2>
        <span class="badge bg-primary">Administrator</span>
    </div>
    
    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1 opacity-75">ประกาศทั้งหมด</h6>
                            <h2 class="mb-0"><?= number_format($carStats['total'] ?? 0) ?></h2>
                        </div>
                        <i class="bi bi-car-front display-4 opacity-50"></i>
                    </div>
                </div>
                <div class="card-footer bg-primary border-0 opacity-75">
                    <a href="/admin/cars" class="text-white text-decoration-none small">
                        ดูทั้งหมด <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1 opacity-75">รอตรวจสอบ</h6>
                            <h2 class="mb-0"><?= number_format($carStats['pending'] ?? 0) ?></h2>
                        </div>
                        <i class="bi bi-clock-history display-4 opacity-50"></i>
                    </div>
                </div>
                <div class="card-footer bg-warning border-0 opacity-75">
                    <a href="/admin/cars?status=pending" class="text-dark text-decoration-none small">
                        ตรวจสอบ <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1 opacity-75">ผู้ใช้ทั้งหมด</h6>
                            <h2 class="mb-0"><?= number_format($userStats['total'] ?? 0) ?></h2>
                        </div>
                        <i class="bi bi-people display-4 opacity-50"></i>
                    </div>
                </div>
                <div class="card-footer bg-success border-0 opacity-75">
                    <a href="/admin/users" class="text-white text-decoration-none small">
                        จัดการผู้ใช้ <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1 opacity-75">ข้อความสอบถาม</h6>
                            <h2 class="mb-0"><?= number_format($inquiryStats['total'] ?? 0) ?></h2>
                        </div>
                        <i class="bi bi-chat-dots display-4 opacity-50"></i>
                    </div>
                </div>
                <div class="card-footer bg-info border-0 opacity-75">
                    <span class="text-white small">
                        <?= number_format($inquiryStats['unread'] ?? 0) ?> ยังไม่อ่าน
                    </span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row g-4">
        <!-- Recent Cars -->
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">ประกาศล่าสุด</h5>
                    <a href="/admin/cars" class="btn btn-sm btn-outline-primary">ดูทั้งหมด</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>รถ</th>
                                    <th>ราคา</th>
                                    <th>สถานะ</th>
                                    <th>วันที่</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentCars as $car): ?>
                                <tr>
                                    <td>
                                        <strong><?= e(str_limit($car['title'], 30)) ?></strong>
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
                                    <td><small><?= format_date($car['created_at'], 'd M') ?></small></td>
                                    <td>
                                        <a href="/admin/cars/<?= $car['id'] ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Stats -->
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0">ผู้ใช้ตามประเภท</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>ผู้ซื้อ</span>
                        <strong><?= number_format($userStats['buyers'] ?? 0) ?></strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>ผู้ขาย</span>
                        <strong><?= number_format($userStats['sellers'] ?? 0) ?></strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>ผู้ดูแล</span>
                        <strong><?= number_format($userStats['admins'] ?? 0) ?></strong>
                    </div>
                </div>
            </div>
            
            <div class="card shadow-sm">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0">เมนูด่วน</h5>
                </div>
                <div class="list-group list-group-flush">
                    <a href="/admin/cars?status=pending" class="list-group-item list-group-item-action">
                        <i class="bi bi-clock-history me-2 text-warning"></i>ตรวจสอบประกาศ
                        <?php if (($carStats['pending'] ?? 0) > 0): ?>
                            <span class="badge bg-warning float-end"><?= $carStats['pending'] ?></span>
                        <?php endif; ?>
                    </a>
                    <a href="/admin/brands" class="list-group-item list-group-item-action">
                        <i class="bi bi-building me-2 text-primary"></i>จัดการยี่ห้อ
                    </a>
                    <a href="/admin/models" class="list-group-item list-group-item-action">
                        <i class="bi bi-car-front me-2 text-primary"></i>จัดการรุ่น
                    </a>
                    <a href="/admin/reports" class="list-group-item list-group-item-action">
                        <i class="bi bi-graph-up me-2 text-success"></i>รายงาน
                    </a>
                    <a href="/admin/reports/export?type=cars" class="list-group-item list-group-item-action">
                        <i class="bi bi-download me-2 text-info"></i>Export CSV
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
