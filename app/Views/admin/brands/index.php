<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">จัดการยี่ห้อรถ</h2>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBrandModal">
            <i class="bi bi-plus-lg me-2"></i>เพิ่มยี่ห้อ
        </button>
    </div>
    
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>ยี่ห้อ</th>
                        <th>Slug</th>
                        <th>จำนวนรถ</th>
                        <th>สถานะ</th>
                        <th class="text-end">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($brands as $brand): ?>
                    <tr>
                        <td><?= $brand['id'] ?></td>
                        <td><strong><?= e($brand['name']) ?></strong></td>
                        <td><code><?= e($brand['slug']) ?></code></td>
                        <td>
                            <a href="<?= url('/admin/cars?brand_id=' . $brand['id']) ?>">
                                <?= number_format($brand['car_count'] ?? 0) ?> รายการ
                            </a>
                        </td>
                        <td>
                            <span class="badge bg-<?= $brand['status'] === 'active' ? 'success' : 'secondary' ?>">
                                <?= $brand['status'] === 'active' ? 'ใช้งาน' : 'ปิดใช้งาน' ?>
                            </span>
                        </td>
                        <td class="text-end">
                            <button type="button" class="btn btn-sm btn-outline-primary" 
                                    onclick="editBrand(<?= $brand['id'] ?>, '<?= e($brand['name']) ?>', '<?= e($brand['status']) ?>')">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form action="<?= url('/admin/brands/' . $brand['id'] . '/delete') ?>" method="POST" class="d-inline"
                                  onsubmit="return confirm('ยืนยันการลบยี่ห้อนี้?')">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Brand Modal -->
<div class="modal fade" id="addBrandModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= url('/admin/brands') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title">เพิ่มยี่ห้อใหม่</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">ชื่อยี่ห้อ <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required placeholder="เช่น Toyota, Honda">
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

<!-- Edit Brand Modal -->
<div class="modal fade" id="editBrandModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editBrandForm" method="POST">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title">แก้ไขยี่ห้อ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">ชื่อยี่ห้อ <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="editBrandName" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">สถานะ</label>
                        <select name="status" id="editBrandStatus" class="form-select">
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
function editBrand(id, name, status) {
    document.getElementById('editBrandForm').action = '<?= url('/admin/brands/') ?>' + id;
    document.getElementById('editBrandName').value = name;
    document.getElementById('editBrandStatus').value = status;
    new bootstrap.Modal(document.getElementById('editBrandModal')).show();
}
</script>
