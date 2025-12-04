<?php
$isEdit = !empty($car);
$errors = \App\Core\Session::getErrors();
?>

<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= url('/dashboard') ?>">แดชบอร์ด</a></li>
            <li class="breadcrumb-item"><a href="<?= url('/my-cars') ?>">รถของฉัน</a></li>
            <li class="breadcrumb-item active"><?= $isEdit ? 'แก้ไขประกาศ' : 'ลงประกาศใหม่' ?></li>
        </ol>
    </nav>
    
    <h2 class="mb-4"><?= $isEdit ? 'แก้ไขประกาศ' : 'ลงประกาศขายรถ' ?></h2>
    
    <form action="<?= $isEdit ? url('/my-cars/' . $car['id']) : url('/my-cars') ?>" method="POST" enctype="multipart/form-data" id="car-form">
        <?= csrf_field() ?>
        <input type="hidden" name="deleted_images" id="deleted-images" value="">
        <input type="hidden" name="new_images" id="new-images" value="">
        <input type="hidden" name="primary_image_id" id="primary-image-id" value="">
        
        <div class="row">
            <div class="col-lg-8">
                <!-- Basic Info -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-transparent">
                        <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>ข้อมูลพื้นฐาน</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">ยี่ห้อ <span class="text-danger">*</span></label>
                                <select name="brand_id" id="brand-select" class="form-select <?= isset($errors['brand_id']) ? 'is-invalid' : '' ?>" required>
                                    <option value="">-- เลือกยี่ห้อ --</option>
                                    <?php foreach ($brands as $brand): ?>
                                        <option value="<?= $brand['id'] ?>" <?= old('brand_id', $car['brand_id'] ?? '') == $brand['id'] ? 'selected' : '' ?>>
                                            <?= e($brand['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">รุ่น <span class="text-danger">*</span></label>
                                <select name="model_id" id="model-select" class="form-select <?= isset($errors['model_id']) ? 'is-invalid' : '' ?>" required>
                                    <option value="">-- เลือกรุ่น --</option>
                                    <?php foreach ($models as $model): ?>
                                        <option value="<?= $model['id'] ?>" <?= old('model_id', $car['model_id'] ?? '') == $model['id'] ? 'selected' : '' ?>>
                                            <?= e($model['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">หัวข้อประกาศ <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control <?= isset($errors['title']) ? 'is-invalid' : '' ?>" 
                                       value="<?= e(old('title', $car['title'] ?? '')) ?>" 
                                       placeholder="เช่น Toyota Camry 2.5 HV Premium ปี 2020 สภาพดีมาก" required>
                                <?php if (isset($errors['title'])): ?>
                                    <div class="invalid-feedback"><?= e($errors['title'][0]) ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-12">
                                <label class="form-label">รายละเอียด <span class="text-danger">*</span></label>
                                <textarea name="description" rows="6" class="form-control <?= isset($errors['description']) ? 'is-invalid' : '' ?>" 
                                          placeholder="บอกรายละเอียดรถ ประวัติการใช้งาน สภาพ อุปกรณ์เสริม ฯลฯ" required><?= e(old('description', $car['description'] ?? '')) ?></textarea>
                                <?php if (isset($errors['description'])): ?>
                                    <div class="invalid-feedback"><?= e($errors['description'][0]) ?></div>
                                <?php endif; ?>
                                <small class="text-muted">อย่างน้อย 50 ตัวอักษร</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Specifications -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-transparent">
                        <h5 class="mb-0"><i class="bi bi-gear me-2"></i>ข้อมูลจำเพาะ</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">ปี <span class="text-danger">*</span></label>
                                <select name="year" class="form-select" required>
                                    <?php foreach (year_options() as $year): ?>
                                        <option value="<?= $year ?>" <?= old('year', $car['year'] ?? date('Y')) == $year ? 'selected' : '' ?>>
                                            <?= $year ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">ระยะทาง (กม.) <span class="text-danger">*</span></label>
                                <input type="number" name="mileage" class="form-control" 
                                       value="<?= e(old('mileage', $car['mileage'] ?? '')) ?>" required min="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">ราคา (บาท) <span class="text-danger">*</span></label>
                                <input type="number" name="price" class="form-control" 
                                       value="<?= e(old('price', $car['price'] ?? '')) ?>" required min="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">เกียร์ <span class="text-danger">*</span></label>
                                <select name="transmission" class="form-select" required>
                                    <?php foreach ($transmissions as $key => $label): ?>
                                        <option value="<?= $key ?>" <?= old('transmission', $car['transmission'] ?? '') == $key ? 'selected' : '' ?>>
                                            <?= e($label) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">เชื้อเพลิง <span class="text-danger">*</span></label>
                                <select name="fuel_type" class="form-select" required>
                                    <?php foreach ($fuels as $key => $label): ?>
                                        <option value="<?= $key ?>" <?= old('fuel_type', $car['fuel_type'] ?? '') == $key ? 'selected' : '' ?>>
                                            <?= e($label) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">สี <span class="text-danger">*</span></label>
                                <input type="text" name="color" class="form-control" 
                                       value="<?= e(old('color', $car['color'] ?? '')) ?>" placeholder="เช่น ขาว, ดำ, เงิน" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">ขนาดเครื่องยนต์ (cc)</label>
                                <input type="text" name="engine_size" class="form-control" 
                                       value="<?= e(old('engine_size', $car['engine_size'] ?? '')) ?>" placeholder="เช่น 1500, 2000">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">เลข VIN (ถ้ามี)</label>
                                <input type="text" name="vin" class="form-control" 
                                       value="<?= e(old('vin', $car['vin'] ?? '')) ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">จังหวัด <span class="text-danger">*</span></label>
                                <select name="province" class="form-select" required>
                                    <option value="">-- เลือกจังหวัด --</option>
                                    <?php foreach ($provinces as $province): ?>
                                        <option value="<?= e($province) ?>" <?= old('province', $car['province'] ?? '') == $province ? 'selected' : '' ?>>
                                            <?= e($province) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Images -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-transparent">
                        <h5 class="mb-0"><i class="bi bi-images me-2"></i>รูปภาพ</h5>
                    </div>
                    <div class="card-body">
                        <div id="image-preview" class="row g-2 mb-3">
                            <?php foreach ($images as $image): ?>
                                <div class="col-6 col-md-3 image-item" data-id="<?= $image['id'] ?>">
                                    <div class="position-relative">
                                        <img src="<?= upload_url($image['image_path']) ?>" class="img-fluid rounded">
                                        <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 btn-delete-image">
                                            <i class="bi bi-x"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm <?= $image['is_primary'] ? 'btn-warning' : 'btn-secondary' ?> position-absolute bottom-0 start-0 m-1 btn-set-primary">
                                            <i class="bi bi-star<?= $image['is_primary'] ? '-fill' : '' ?>"></i>
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="border border-dashed rounded p-4 text-center" id="drop-zone">
                            <i class="bi bi-cloud-upload display-4 text-muted"></i>
                            <p class="mb-2">ลากไฟล์มาวางที่นี่ หรือ</p>
                            <input type="file" id="image-input" accept="image/*" multiple class="d-none">
                            <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('image-input').click()">
                                เลือกรูปภาพ
                            </button>
                            <p class="text-muted small mt-2 mb-0">รองรับ JPG, PNG, GIF, WebP ขนาดไม่เกิน 5MB</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <!-- Status -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-transparent">
                        <h5 class="mb-0">สถานะประกาศ</h5>
                    </div>
                    <div class="card-body">
                        <select name="status" class="form-select">
                            <option value="pending" <?= old('status', $car['status'] ?? 'pending') == 'pending' ? 'selected' : '' ?>>
                                รอตรวจสอบ
                            </option>
                            <option value="draft" <?= old('status', $car['status'] ?? '') == 'draft' ? 'selected' : '' ?>>
                                ฉบับร่าง
                            </option>
                            <?php if ($isEdit && in_array($car['status'], ['published', 'sold'])): ?>
                                <option value="sold" <?= $car['status'] == 'sold' ? 'selected' : '' ?>>
                                    ขายแล้ว
                                </option>
                            <?php endif; ?>
                        </select>
                        <small class="text-muted">ประกาศจะถูกตรวจสอบก่อนเผยแพร่</small>
                    </div>
                </div>
                
                <!-- Submit -->
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-lg me-2"></i><?= $isEdit ? 'บันทึกการแก้ไข' : 'ลงประกาศ' ?>
                            </button>
                            <a href="<?= url('/my-cars') ?>" class="btn btn-outline-secondary">ยกเลิก</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const brandSelect = document.getElementById('brand-select');
    const modelSelect = document.getElementById('model-select');
    const imageInput = document.getElementById('image-input');
    const imagePreview = document.getElementById('image-preview');
    const deletedImagesInput = document.getElementById('deleted-images');
    const newImagesInput = document.getElementById('new-images');
    const primaryImageInput = document.getElementById('primary-image-id');
    const dropZone = document.getElementById('drop-zone');
    
    let deletedImages = [];
    let newImages = [];
    
    // Load models when brand changes
    brandSelect.addEventListener('change', async function() {
        const brandId = this.value;
        modelSelect.innerHTML = '<option value="">-- เลือกรุ่น --</option>';
        
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
    
    // Image upload
    imageInput.addEventListener('change', async function() {
        for (const file of this.files) {
            await uploadImage(file);
        }
        this.value = '';
    });
    
    // Drag and drop
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('border-primary');
    });
    
    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('border-primary');
    });
    
    dropZone.addEventListener('drop', async (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-primary');
        
        for (const file of e.dataTransfer.files) {
            if (file.type.startsWith('image/')) {
                await uploadImage(file);
            }
        }
    });
    
    async function uploadImage(file) {
        const formData = new FormData();
        formData.append('image', file);
        formData.append('_csrf_token', csrfToken);
        
        try {
            const response = await fetch('<?= url('/upload/image') ?>', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                newImages.push(data.data.path);
                newImagesInput.value = newImages.join(',');
                
                addImagePreview(data.data.url, data.data.path);
            } else {
                alert(data.message || 'เกิดข้อผิดพลาดในการอัปโหลด');
            }
        } catch (error) {
            alert('เกิดข้อผิดพลาดในการอัปโหลด');
        }
    }
    
    function addImagePreview(url, path) {
        const div = document.createElement('div');
        div.className = 'col-6 col-md-3 image-item';
        div.dataset.path = path;
        div.innerHTML = `
            <div class="position-relative">
                <img src="${url}" class="img-fluid rounded">
                <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 btn-delete-image">
                    <i class="bi bi-x"></i>
                </button>
            </div>
        `;
        imagePreview.appendChild(div);
    }
    
    // Delete image
    imagePreview.addEventListener('click', function(e) {
        if (e.target.closest('.btn-delete-image')) {
            const item = e.target.closest('.image-item');
            
            if (item.dataset.id) {
                deletedImages.push(item.dataset.id);
                deletedImagesInput.value = deletedImages.join(',');
            } else if (item.dataset.path) {
                newImages = newImages.filter(p => p !== item.dataset.path);
                newImagesInput.value = newImages.join(',');
            }
            
            item.remove();
        }
        
        if (e.target.closest('.btn-set-primary')) {
            const item = e.target.closest('.image-item');
            if (item.dataset.id) {
                primaryImageInput.value = item.dataset.id;
                
                // Update UI
                document.querySelectorAll('.btn-set-primary').forEach(btn => {
                    btn.classList.remove('btn-warning');
                    btn.classList.add('btn-secondary');
                    btn.querySelector('i').classList.remove('bi-star-fill');
                    btn.querySelector('i').classList.add('bi-star');
                });
                
                const btn = e.target.closest('.btn-set-primary');
                btn.classList.remove('btn-secondary');
                btn.classList.add('btn-warning');
                btn.querySelector('i').classList.remove('bi-star');
                btn.querySelector('i').classList.add('bi-star-fill');
            }
        }
    });
});
</script>
