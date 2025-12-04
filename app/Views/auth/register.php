<div class="min-vh-100 d-flex align-items-center" style="background: var(--gray-50);">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <!-- Logo -->
                <div class="text-center mb-4">
                    <a href="<?= url('/') ?>" class="text-decoration-none">
                        <span style="font-size: 2rem; font-weight: 700; color: var(--primary);">
                            <i class="bi bi-car-front-fill me-2" style="color: var(--accent);"></i>UsedCar
                        </span>
                    </a>
                </div>
                
                <div class="card" style="border-radius: var(--radius);">
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-4">
                            <h4 class="fw-semibold mb-1">สร้างบัญชีใหม่</h4>
                            <p class="text-muted mb-0">เริ่มต้นซื้อขายรถกับเรา</p>
                        </div>
                        
                        <?php $errors = \App\Core\Session::getErrors(); ?>
                        
                        <form action="<?= url('/register') ?>" method="POST">
                            <?= csrf_field() ?>
                            
                            <!-- Account Type -->
                            <div class="mb-4">
                                <label class="form-label fw-medium mb-3">ฉันต้องการ</label>
                                <div class="row g-3">
                                    <div class="col-6">
                                        <input class="btn-check" type="radio" name="role" id="role-buyer" value="buyer" 
                                               <?= old('role', $_GET['role'] ?? 'buyer') === 'buyer' ? 'checked' : '' ?>>
                                        <label class="btn btn-outline-primary w-100 py-3" for="role-buyer">
                                            <i class="bi bi-search d-block mb-1" style="font-size: 1.25rem;"></i>
                                            <span class="fw-medium">หารถซื้อ</span>
                                        </label>
                                    </div>
                                    <div class="col-6">
                                        <input class="btn-check" type="radio" name="role" id="role-seller" value="seller"
                                               <?= old('role', $_GET['role'] ?? '') === 'seller' ? 'checked' : '' ?>>
                                        <label class="btn btn-outline-primary w-100 py-3" for="role-seller">
                                            <i class="bi bi-tag d-block mb-1" style="font-size: 1.25rem;"></i>
                                            <span class="fw-medium">ลงขายรถ</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label fw-medium">ชื่อ-นามสกุล</label>
                                    <input type="text" name="name" class="form-control form-control-lg <?= isset($errors['name']) ? 'is-invalid' : '' ?>" 
                                           value="<?= e(old('name')) ?>" placeholder="ชื่อ นามสกุล" required>
                                    <?php if (isset($errors['name'])): ?>
                                        <div class="invalid-feedback"><?= e($errors['name'][0]) ?></div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">อีเมล</label>
                                    <input type="email" name="email" class="form-control form-control-lg <?= isset($errors['email']) ? 'is-invalid' : '' ?>" 
                                           value="<?= e(old('email')) ?>" placeholder="name@example.com" required>
                                    <?php if (isset($errors['email'])): ?>
                                        <div class="invalid-feedback"><?= e($errors['email'][0]) ?></div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">เบอร์โทรศัพท์</label>
                                    <input type="tel" name="phone" class="form-control form-control-lg <?= isset($errors['phone']) ? 'is-invalid' : '' ?>" 
                                           value="<?= e(old('phone')) ?>" placeholder="08x-xxx-xxxx" required>
                                    <?php if (isset($errors['phone'])): ?>
                                        <div class="invalid-feedback"><?= e($errors['phone'][0]) ?></div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">รหัสผ่าน</label>
                                    <input type="password" name="password" class="form-control form-control-lg <?= isset($errors['password']) ? 'is-invalid' : '' ?>" 
                                           placeholder="อย่างน้อย 6 ตัวอักษร" required minlength="6">
                                    <?php if (isset($errors['password'])): ?>
                                        <div class="invalid-feedback"><?= e($errors['password'][0]) ?></div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">ยืนยันรหัสผ่าน</label>
                                    <input type="password" name="password_confirmation" class="form-control form-control-lg" 
                                           placeholder="พิมพ์รหัสผ่านอีกครั้ง" required>
                                </div>
                            </div>
                            
                            <div class="form-check mt-4 mb-4">
                                <input class="form-check-input" type="checkbox" id="terms" required>
                                <label class="form-check-label text-muted" for="terms">
                                    ฉันยอมรับ <a href="#" style="color: var(--accent);">ข้อกำหนดและเงื่อนไข</a> การใช้งาน
                                </label>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-lg w-100 mb-4">
                                สมัครสมาชิก
                            </button>
                        </form>
                        
                        <p class="text-center text-muted mb-0">
                            มีบัญชีอยู่แล้ว? <a href="<?= url('/login') ?>" class="fw-medium" style="color: var(--accent);">เข้าสู่ระบบ</a>
                        </p>
                    </div>
                </div>
                
                <p class="text-center text-muted small mt-4">
                    <a href="<?= url('/') ?>" class="text-muted text-decoration-none">
                        <i class="bi bi-arrow-left me-1"></i>กลับหน้าหลัก
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
