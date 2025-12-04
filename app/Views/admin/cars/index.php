<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">
            จัดการประกาศ
            <?php if (!empty($brandId)): ?>
                <span class="badge bg-primary fs-6">กรองตามยี่ห้อ</span>
            <?php endif; ?>
        </h2>
    </div>
    
    <!-- Filters -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="<?= url('/admin/cars') ?>" method="GET" class="row g-3 align-items-end">
                <?php if (!empty($brandId)): ?>
                    <input type="hidden" name="brand_id" value="<?= e($brandId) ?>">
                <?php endif; ?>
                <div class="col-md-4">
                    <label class="form-label">ค้นหา</label>
                    <input type="text" name="q" class="form-control" placeholder="ค้นหาหัวข้อ..." value="<?= e($search ?? '') ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">สถานะ</label>
                    <select name="status" class="form-select">
                        <option value="">ทั้งหมด</option>
                        <?php foreach ($statuses as $key => $label): ?>
                            <option value="<?= $key ?>" <?= ($currentStatus ?? '') === $key ? 'selected' : '' ?>><?= e($label) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i> ค้นหา
                    </button>
                    <a href="<?= url('/admin/cars') ?>" class="btn btn-outline-secondary">ล้าง</a>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Results -->
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>รถ</th>
                        <th>ราคา</th>
                        <th>สถานะ</th>
                        <th>แนะนำ</th>
                        <th>เข้าชม</th>
                        <th>วันที่</th>
                        <th class="text-end">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cars as $car): ?>
                    <tr>
                        <td><?= $car['id'] ?></td>
                        <td>
                            <strong><?= e(str_limit($car['title'], 40)) ?></strong>
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
                                <?= e($statuses[$car['status']] ?? $car['status']) ?>
                            </span>
                        </td>
                        <td>
                            <form action="<?= url('/admin/cars/' . $car['id'] . '/feature') ?>" method="POST" class="d-inline feature-form">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-sm btn-<?= $car['is_featured'] ? 'warning' : 'outline-secondary' ?>">
                                    <i class="bi bi-star<?= $car['is_featured'] ? '-fill' : '' ?>"></i>
                                </button>
                            </form>
                        </td>
                        <td><?= number_format($car['views']) ?></td>
                        <td><small><?= format_date($car['created_at'], 'd M Y') ?></small></td>
                        <td class="text-end">
                            <div class="btn-group">
                                <a href="<?= url('/admin/cars/' . $car['id']) ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <?php if ($car['status'] === 'pending'): ?>
                                    <form action="<?= url('/admin/cars/' . $car['id'] . '/approve') ?>" method="POST" class="d-inline">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-sm btn-success" title="อนุมัติ">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                    </form>
                                    <form action="<?= url('/admin/cars/' . $car['id'] . '/reject') ?>" method="POST" class="d-inline">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-sm btn-danger" title="ปฏิเสธ">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>
                                <form action="<?= url('/admin/cars/' . $car['id'] . '/delete') ?>" method="POST" class="d-inline" 
                                      onsubmit="return confirm('ยืนยันการลบ?')">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="ลบ">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
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
                        <a class="page-link" href="?page=<?= $i ?>&status=<?= $currentStatus ?? '' ?>&q=<?= e($search ?? '') ?>&brand_id=<?= e($brandId ?? '') ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php endif; ?>
</div>

<script>
// Handle feature toggle via AJAX
document.querySelectorAll('.feature-form').forEach(form => {
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const response = await fetchWithCsrf(this.action, { method: 'POST' });
        const data = await response.json();
        
        if (data.success) {
            location.reload();
        }
    });
});
</script>
