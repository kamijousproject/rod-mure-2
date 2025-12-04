<div class="container py-4">
    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 100px; height: 100px;">
                        <i class="bi bi-person-fill display-4"></i>
                    </div>
                    <h4><?= e($user['name']) ?></h4>
                    <p class="text-muted mb-3">
                        <?= match($user['role']) {
                            'admin' => '<span class="badge bg-danger">ผู้ดูแลระบบ</span>',
                            'seller' => '<span class="badge bg-primary">ผู้ขาย</span>',
                            default => '<span class="badge bg-secondary">ผู้ซื้อ</span>'
                        } ?>
                    </p>
                    <a href="<?= url('/profile/edit') ?>" class="btn btn-outline-primary">
                        <i class="bi bi-pencil me-2"></i>แก้ไขโปรไฟล์
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0">ข้อมูลส่วนตัว</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="text-muted small">ชื่อ-นามสกุล</label>
                            <p class="mb-0"><?= e($user['name']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">อีเมล</label>
                            <p class="mb-0"><?= e($user['email']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">เบอร์โทรศัพท์</label>
                            <p class="mb-0"><?= e($user['phone'] ?? '-') ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">จังหวัด</label>
                            <p class="mb-0"><?= e($user['province'] ?? '-') ?></p>
                        </div>
                        <div class="col-12">
                            <label class="text-muted small">ที่อยู่</label>
                            <p class="mb-0"><?= e($user['address'] ?? '-') ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">สมัครเมื่อ</label>
                            <p class="mb-0"><?= format_date($user['created_at'], 'd M Y') ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">เข้าสู่ระบบล่าสุด</label>
                            <p class="mb-0"><?= $user['last_login'] ? format_date($user['last_login'], 'd M Y H:i') : '-' ?></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php if (in_array($user['role'], ['seller', 'admin']) && !empty($user['stats'])): ?>
            <div class="card shadow-sm">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0">สถิติการใช้งาน</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center g-3">
                        <div class="col-3">
                            <h4 class="mb-0 text-primary"><?= number_format($user['stats']['total_listings'] ?? 0) ?></h4>
                            <small class="text-muted">ประกาศทั้งหมด</small>
                        </div>
                        <div class="col-3">
                            <h4 class="mb-0 text-success"><?= number_format($user['stats']['active_listings'] ?? 0) ?></h4>
                            <small class="text-muted">กำลังขาย</small>
                        </div>
                        <div class="col-3">
                            <h4 class="mb-0 text-info"><?= number_format($user['stats']['sold_listings'] ?? 0) ?></h4>
                            <small class="text-muted">ขายแล้ว</small>
                        </div>
                        <div class="col-3">
                            <h4 class="mb-0 text-warning"><?= number_format($user['stats']['total_views'] ?? 0) ?></h4>
                            <small class="text-muted">ยอดเข้าชม</small>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
