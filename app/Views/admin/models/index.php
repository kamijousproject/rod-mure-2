<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">จัดการรุ่นรถ</h2>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModelModal">
            <i class="bi bi-plus-lg me-2"></i>เพิ่มรุ่น
        </button>
    </div>
    
    <!-- Filter by Brand -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="<?= url('/admin/models') ?>" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">กรองตามยี่ห้อ</label>
                    <select name="brand_id" class="form-select" onchange="this.form.submit()">
                        <option value="">-- ทุกยี่ห้อ --</option>
                        <?php foreach ($brands as $brand): ?>
                            <option value="<?= $brand['id'] ?>" <?= $selectedBrand == $brand['id'] ? 'selected' : '' ?>>
                                <?= e($brand['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-auto">
                    <a href="<?= url('/admin/models') ?>" class="btn btn-outline-secondary">ล้างตัวกรอง</a>
                </div>
            </form>
        </div>
    </div>
    
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>รุ่น</th>
                        <th>ยี่ห้อ</th>
                        <th>Slug</th>
                        <th>จำนวนรถ</th>
                        <th>สถานะ</th>
                        <th class="text-end">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($models)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            ไม่พบรุ่นรถ
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($models as $model): ?>
                    <tr>
                        <td><?= $model['id'] ?></td>
                        <td><strong><?= e($model['name']) ?></strong></td>
                        <td><?= e($model['brand_name'] ?? '-') ?></td>
                        <td><code><?= e($model['slug']) ?></code></td>
                        <td><?= number_format($model['car_count'] ?? 0) ?> รายการ</td>
                        <td>
                            <span class="badge bg-<?= $model['status'] === 'active' ? 'success' : 'secondary' ?>">
                                <?= $model['status'] === 'active' ? 'ใช้งาน' : 'ปิดใช้งาน' ?>
                            </span>
                        </td>
                        <td class="text-end">
                            <button type="button" class="btn btn-sm btn-outline-primary" 
                                    onclick="editModel(<?= $model['id'] ?>, '<?= e($model['name']) ?>', '<?= e($model['status']) ?>')">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form action="<?= url('/admin/models/' . $model['id'] . '/delete') ?>" method="POST" class="d-inline"
                                  onsubmit="return confirm('ยืนยันการลบรุ่นนี้?')">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Model Modal -->
<div class="modal fade" id="addModelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= url('/admin/models') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title">เพิ่มรุ่นใหม่</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">ยี่ห้อ <span class="text-danger">*</span></label>
                        <select name="brand_id" class="form-select" required>
                            <option value="">-- เลือกยี่ห้อ --</option>
                            <?php foreach ($brands as $brand): ?>
                                <option value="<?= $brand['id'] ?>" <?= $selectedBrand == $brand['id'] ? 'selected' : '' ?>>
                                    <?= e($brand['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ชื่อรุ่น <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required placeholder="เช่น Camry, Civic">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary">บันทึก</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Model Modal -->
<div class="modal fade" id="editModelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editModelForm" method="POST">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title">แก้ไขรุ่น</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">ชื่อรุ่น <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="editModelName" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">สถานะ</label>
                        <select name="status" id="editModelStatus" class="form-select">
                            <option value="active">ใช้งาน</option>
                            <option value="inactive">ปิดใช้งาน</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary">บันทึก</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editModel(id, name, status) {
    document.getElementById('editModelForm').action = '<?= url('/admin/models/') ?>' + id;
    document.getElementById('editModelName').value = name;
    document.getElementById('editModelStatus').value = status;
    new bootstrap.Modal(document.getElementById('editModelModal')).show();
}
</script>
