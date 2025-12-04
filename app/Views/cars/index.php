<!-- Breadcrumb -->
<div class="bg-white border-bottom py-3">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?= url('/') ?>">หน้าแรก</a></li>
                <li class="breadcrumb-item active">ค้นหารถ</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container py-4">
    <div class="row">
        <!-- Filter Sidebar -->
        <div class="col-lg-3 mb-4">
            <div class="filter-sidebar shadow-sm">
                <h5 class="mb-3"><i class="bi bi-funnel me-2"></i>กรองผลลัพธ์</h5>
                
                <form action="<?= url('/cars') ?>" method="GET" id="filter-form">
                    <!-- Search -->
                    <div class="mb-3">
                        <label class="form-label">คำค้นหา</label>
                        <input type="text" name="q" class="form-control" value="<?= e($filters['q'] ?? '') ?>" placeholder="ค้นหา...">
                    </div>
                    
                    <!-- Brand -->
                    <div class="mb-3">
                        <label class="form-label">ยี่ห้อ</label>
                        <select name="brand_id" class="form-select" id="brand-select">
                            <option value="">ทั้งหมด</option>
                            <?php foreach ($brands as $brand): ?>
                                <option value="<?= $brand['id'] ?>" <?= ($filters['brand_id'] ?? '') == $brand['id'] ? 'selected' : '' ?>>
                                    <?= e($brand['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <!-- Model -->
                    <div class="mb-3">
                        <label class="form-label">รุ่น</label>
                        <select name="model_id" class="form-select" id="model-select">
                            <option value="">ทั้งหมด</option>
                            <?php foreach ($models as $model): ?>
                                <option value="<?= $model['id'] ?>" <?= ($filters['model_id'] ?? '') == $model['id'] ? 'selected' : '' ?>>
                                    <?= e($model['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <!-- Price Range -->
                    <div class="mb-3">
                        <label class="form-label">ราคา (บาท)</label>
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="number" name="price_from" class="form-control" placeholder="ต่ำสุด" value="<?= e($filters['price_from'] ?? '') ?>">
                            </div>
                            <div class="col-6">
                                <input type="number" name="price_to" class="form-control" placeholder="สูงสุด" value="<?= e($filters['price_to'] ?? '') ?>">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Year Range -->
                    <div class="mb-3">
                        <label class="form-label">ปี</label>
                        <div class="row g-2">
                            <div class="col-6">
                                <select name="year_from" class="form-select">
                                    <option value="">ตั้งแต่</option>
                                    <?php foreach (year_options() as $year): ?>
                                        <option value="<?= $year ?>" <?= ($filters['year_from'] ?? '') == $year ? 'selected' : '' ?>><?= $year ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-6">
                                <select name="year_to" class="form-select">
                                    <option value="">ถึง</option>
                                    <?php foreach (year_options() as $year): ?>
                                        <option value="<?= $year ?>" <?= ($filters['year_to'] ?? '') == $year ? 'selected' : '' ?>><?= $year ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Transmission -->
                    <div class="mb-3">
                        <label class="form-label">เกียร์</label>
                        <select name="transmission" class="form-select">
                            <option value="">ทั้งหมด</option>
                            <?php foreach ($transmissions as $key => $label): ?>
                                <option value="<?= $key ?>" <?= ($filters['transmission'] ?? '') == $key ? 'selected' : '' ?>><?= e($label) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <!-- Fuel Type -->
                    <div class="mb-3">
                        <label class="form-label">เชื้อเพลิง</label>
                        <select name="fuel_type" class="form-select">
                            <option value="">ทั้งหมด</option>
                            <?php foreach ($fuels as $key => $label): ?>
                                <option value="<?= $key ?>" <?= ($filters['fuel_type'] ?? '') == $key ? 'selected' : '' ?>><?= e($label) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <!-- Province -->
                    <div class="mb-3">
                        <label class="form-label">จังหวัด</label>
                        <select name="province" class="form-select">
                            <option value="">ทั้งหมด</option>
                            <?php foreach ($provinces as $province): ?>
                                <option value="<?= e($province) ?>" <?= ($filters['province'] ?? '') == $province ? 'selected' : '' ?>><?= e($province) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search me-2"></i>ค้นหา
                        </button>
                        <a href="<?= url('/cars') ?>" class="btn btn-outline-secondary">ล้างตัวกรอง</a>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Results -->
        <div class="col-lg-9">
            <!-- Sort & Count -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <span class="text-muted">พบ <strong><?= number_format($pagination['total']) ?></strong> รายการ</span>
                </div>
                <div class="d-flex gap-2 align-items-center">
                    <label class="form-label mb-0 text-nowrap">เรียงตาม:</label>
                    <select name="sort" class="form-select form-select-sm w-auto" onchange="this.form.submit()" form="filter-form">
                        <option value="newest" <?= ($filters['sort'] ?? '') == 'newest' ? 'selected' : '' ?>>ล่าสุด</option>
                        <option value="price_low" <?= ($filters['sort'] ?? '') == 'price_low' ? 'selected' : '' ?>>ราคาต่ำ-สูง</option>
                        <option value="price_high" <?= ($filters['sort'] ?? '') == 'price_high' ? 'selected' : '' ?>>ราคาสูง-ต่ำ</option>
                        <option value="year_new" <?= ($filters['sort'] ?? '') == 'year_new' ? 'selected' : '' ?>>ปีใหม่-เก่า</option>
                        <option value="mileage_low" <?= ($filters['sort'] ?? '') == 'mileage_low' ? 'selected' : '' ?>>ระยะทางน้อย-มาก</option>
                    </select>
                </div>
            </div>
            
            <?php if (empty($cars)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-car-front display-1 text-muted"></i>
                    <h4 class="mt-3">ไม่พบรถที่ค้นหา</h4>
                    <p class="text-muted">ลองปรับเงื่อนไขการค้นหาใหม่</p>
                    <a href="<?= url('/cars') ?>" class="btn btn-primary">ดูรถทั้งหมด</a>
                </div>
            <?php else: ?>
                <!-- Car Grid -->
                <div class="row g-4">
                    <?php foreach ($cars as $car): ?>
                        <div class="col-md-6 col-xl-4">
                            <?php include BASE_PATH . '/app/Views/partials/car-card.php'; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Pagination -->
                <?php if ($pagination['total_pages'] > 1): ?>
                    <nav class="mt-4">
                        <ul class="pagination justify-content-center">
                            <?php if ($pagination['page'] > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?<?= http_build_query(array_merge($filters, ['page' => $pagination['page'] - 1])) ?>">
                                        <i class="bi bi-chevron-left"></i>
                                    </a>
                                </li>
                            <?php endif; ?>
                            
                            <?php for ($i = max(1, $pagination['page'] - 2); $i <= min($pagination['total_pages'], $pagination['page'] + 2); $i++): ?>
                                <li class="page-item <?= $i == $pagination['page'] ? 'active' : '' ?>">
                                    <a class="page-link" href="?<?= http_build_query(array_merge($filters, ['page' => $i])) ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>
                            
                            <?php if ($pagination['page'] < $pagination['total_pages']): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?<?= http_build_query(array_merge($filters, ['page' => $pagination['page'] + 1])) ?>">
                                        <i class="bi bi-chevron-right"></i>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
// Load models when brand changes
document.getElementById('brand-select').addEventListener('change', async function() {
    const brandId = this.value;
    const modelSelect = document.getElementById('model-select');
    
    modelSelect.innerHTML = '<option value="">ทั้งหมด</option>';
    
    if (brandId) {
        const response = await fetch('<?= url('/api/models') ?>?brand_id=' + brandId);
        const data = await response.json();
        
        if (data.success) {
            data.data.forEach(model => {
                const option = document.createElement('option');
                option.value = model.id;
                option.textContent = model.name;
                modelSelect.appendChild(option);
            });
        }
    }
});
</script>
