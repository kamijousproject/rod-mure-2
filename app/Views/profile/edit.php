<?php $errors = \App\Core\Session::getErrors(); ?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h2 class="mb-4">แก้ไขโปรไฟล์</h2>
            
            <!-- Profile Form -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0">ข้อมูลส่วนตัว</h5>
                </div>
                <div class="card-body">
                    <form action="<?= url('/profile/update') ?>" method="POST">
                        <?= csrf_field() ?>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">ชื่อ-นามสกุล <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>" 
                                       value="<?= e(old('name', $user['name'])) ?>" required>
                                <?php if (isset($errors['name'])): ?>
                                    <div class="invalid-feedback"><?= e($errors['name'][0]) ?></div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">อีเมล</label>
                                <input type="email" class="form-control" value="<?= e($user['email']) ?>" disabled>
                                <small class="text-muted">ไม่สามารถเปลี่ยนอีเมลได้</small>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">เบอร์โทรศัพท์ <span class="text-danger">*</span></label>
                                <input type="tel" name="phone" class="form-control <?= isset($errors['phone']) ? 'is-invalid' : '' ?>" 
                                       value="<?= e(old('phone', $user['phone'])) ?>" required>
                                <?php if (isset($errors['phone'])): ?>
                                    <div class="invalid-feedback"><?= e($errors['phone'][0]) ?></div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">จังหวัด</label>
                                <select name="province" class="form-select">
                                    <option value="">-- เลือกจังหวัด --</option>
                                    <?php foreach ($provinces as $province): ?>
                                        <option value="<?= e($province) ?>" <?= old('province', $user['province']) == $province ? 'selected' : '' ?>>
                                            <?= e($province) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label">ที่อยู่</label>
                                <textarea name="address" rows="3" class="form-control"><?= e(old('address', $user['address'])) ?></textarea>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-2"></i>บันทึกการเปลี่ยนแปลง
                            </button>
                            <a href="<?= url('/profile') ?>" class="btn btn-outline-secondary ms-2">ยกเลิก</a>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Change Password -->
            <div class="card shadow-sm">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0">เปลี่ยนรหัสผ่าน</h5>
                </div>
                <div class="card-body">
                    <form action="<?= url('/profile/password') ?>" method="POST">
                        <?= csrf_field() ?>
                        
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">รหัสผ่านปัจจุบัน <span class="text-danger">*</span></label>
                                <input type="password" name="current_password" class="form-control" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">รหัสผ่านใหม่ <span class="text-danger">*</span></label>
                                <input type="password" name="password" class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" 
                                       required minlength="6">
                                <?php if (isset($errors['password'])): ?>
                                    <div class="invalid-feedback"><?= e($errors['password'][0]) ?></div>
                                <?php endif; ?>
                                <small class="text-muted">อย่างน้อย 6 ตัวอักษร</small>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">ยืนยันรหัสผ่านใหม่ <span class="text-danger">*</span></label>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-shield-lock me-2"></i>เปลี่ยนรหัสผ่าน
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
