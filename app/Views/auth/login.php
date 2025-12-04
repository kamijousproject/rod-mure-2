<div class="min-vh-100 d-flex align-items-center" style="background: var(--gray-50);">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
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
                            <h4 class="fw-semibold mb-1">ยินดีต้อนรับกลับ</h4>
                            <p class="text-muted mb-0">เข้าสู่ระบบเพื่อดำเนินการต่อ</p>
                        </div>
                        
                        <?php $errors = \App\Core\Session::getErrors(); ?>
                        
                        <form action="<?= url('/login') ?>" method="POST">
                            <?= csrf_field() ?>
                            
                            <div class="mb-3">
                                <label class="form-label fw-medium">อีเมล</label>
                                <input type="email" name="email" class="form-control form-control-lg <?= isset($errors['email']) ? 'is-invalid' : '' ?>" 
                                       value="<?= e(old('email')) ?>" placeholder="name@example.com" required>
                                <?php if (isset($errors['email'])): ?>
                                    <div class="invalid-feedback"><?= e($errors['email'][0]) ?></div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label class="form-label fw-medium mb-0">รหัสผ่าน</label>
                                    <a href="<?= url('/forgot-password') ?>" class="text-muted small text-decoration-none">ลืมรหัสผ่าน?</a>
                                </div>
                                <input type="password" name="password" class="form-control form-control-lg mt-2 <?= isset($errors['password']) ? 'is-invalid' : '' ?>" 
                                       placeholder="••••••••" required>
                                <?php if (isset($errors['password'])): ?>
                                    <div class="invalid-feedback"><?= e($errors['password'][0]) ?></div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="form-check mb-4">
                                <input type="checkbox" class="form-check-input" name="remember" id="remember">
                                <label class="form-check-label text-muted" for="remember">จดจำการเข้าสู่ระบบ</label>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-lg w-100 mb-4">
                                เข้าสู่ระบบ
                            </button>
                        </form>
                        
                        <p class="text-center text-muted mb-0">
                            ยังไม่มีบัญชี? <a href="<?= url('/register') ?>" class="fw-medium" style="color: var(--accent);">สมัครสมาชิก</a>
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
