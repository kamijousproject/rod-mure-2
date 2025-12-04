<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">รถของฉัน</h2>
        <a href="<?= url('/my-cars/create') ?>" class="btn btn-primary">
            <i class="bi bi-plus-lg me-2"></i>ลงประกาศใหม่
        </a>
    </div>
    
    <!-- Status Filters -->
    <div class="card shadow-sm mb-4">
        <div class="card-body py-2">
            <div class="d-flex gap-2 flex-wrap">
                <a href="<?= url('/my-cars') ?>" class="btn btn-sm <?= empty($currentStatus) ? 'btn-primary' : 'btn-outline-secondary' ?>">
                    ทั้งหมด
                </a>
                <?php foreach ($statuses as $key => $label): ?>
                    <a href="<?= url('/my-cars?status=' . $key) ?>" class="btn btn-sm <?= $currentStatus === $key ? 'btn-primary' : 'btn-outline-secondary' ?>">
                        <?= e($label) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    
    <?php if (empty($cars)): ?>
        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-car-front display-1 text-muted"></i>
                <h4 class="mt-3">ยังไม่มีประกาศ</h4>
                <p class="text-muted">เริ่มต้นลงประกาศขายรถของคุณวันนี้</p>
                <a href="<?= url('/my-cars/create') ?>" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-2"></i>ลงประกาศใหม่
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 40%">รถ</th>
                            <th>ราคา</th>
                            <th>สถานะ</th>
                            <th>เข้าชม</th>
                            <th>สอบถาม</th>
                            <th class="text-end">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cars as $car): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <?php if ($car['primary_image']): ?>
                                        <img src="<?= upload_url($car['primary_image']) ?>" 
                                             class="rounded me-3" style="width: 80px; height: 60px; object-fit: cover;">
                                    <?php else: ?>
                                        <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 60px;">
                                            <i class="bi bi-image text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <a href="<?= url('/cars/' . e($car['slug'])) ?>" target="_blank" class="text-decoration-none">
                                            <strong><?= e(str_limit($car['title'], 40)) ?></strong>
                                        </a>
                                        <br>
                                        <small class="text-muted">
                                            <?= e($car['brand_name']) ?> <?= e($car['model_name']) ?> | <?= e($car['year']) ?>
                                        </small>
                                    </div>
                                </div>
                            </td>
                            <td class="text-primary fw-bold"><?= format_price($car['price']) ?></td>
                            <td>
                                <span class="badge bg-<?= match($car['status']) {
                                    'published' => 'success',
                                    'pending' => 'warning',
                                    'sold' => 'info',
                                    'rejected' => 'danger',
                                    default => 'secondary'
                                } ?>">
                                    <?= e($statuses[$car['status']] ?? $car['status']) ?>
                                </span>
                            </td>
                            <td><?= number_format($car['views']) ?></td>
                            <td>
                                <?php if (($car['inquiry_count'] ?? 0) > 0): ?>
                                    <span class="badge bg-primary"><?= $car['inquiry_count'] ?></span>
                                <?php else: ?>
                                    <span class="text-muted">0</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="<?= url('/my-cars/' . $car['id'] . '/edit') ?>">
                                                <i class="bi bi-pencil me-2"></i>แก้ไข
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="<?= url('/cars/' . e($car['slug'])) ?>" target="_blank">
                                                <i class="bi bi-eye me-2"></i>ดูประกาศ
                                            </a>
                                        </li>
                                        <?php if ($car['status'] === 'published'): ?>
                                        <li>
                                            <form action="<?= url('/my-cars/' . $car['id'] . '/status') ?>" method="POST" class="d-inline">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="status" value="sold">
                                                <button type="submit" class="dropdown-item text-success">
                                                    <i class="bi bi-check-circle me-2"></i>ขายแล้ว
                                                </button>
                                            </form>
                                        </li>
                                        <?php endif; ?>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="<?= url('/my-cars/' . $car['id'] . '/delete') ?>" method="POST" 
                                                  onsubmit="return confirm('ยืนยันการลบประกาศนี้?')">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="bi bi-trash me-2"></i>ลบ
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Pagination -->
        <?php if ($pagination['total_pages'] > 1): ?>
            <nav class="mt-4">
                <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                        <li class="page-item <?= $i == $pagination['page'] ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?><?= $currentStatus ? '&status=' . $currentStatus : '' ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php endif; ?>
    <?php endif; ?>
</div>
