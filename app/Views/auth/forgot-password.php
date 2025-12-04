<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <i class="bi bi-key text-primary display-4"></i>
                        <h3 class="mt-2">ลืมรหัสผ่าน</h3>
                        <p class="text-muted">กรอกอีเมลเพื่อรับลิงก์รีเซ็ตรหัสผ่าน</p>
                    </div>
                    
                    <?php $errors = \App\Core\Session::getErrors(); ?>
                    
                    <form action="/forgot-password" method="POST">
                        <?= csrf_field() ?>
                        
                        <div class="mb-4">
                            <label class="form-label">อีเมล</label>
                            <input type="email" name="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" 
                                   value="<?= e(old('email')) ?>" placeholder="your@email.com" required>
                            <?php if (isset($errors['email'])): ?>
                                <div class="invalid-feedback"><?= e($errors['email'][0]) ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 mb-3">
                            <i class="bi bi-envelope me-2"></i>ส่งลิงก์รีเซ็ต
                        </button>
                    </form>
                    
                    <p class="text-center mb-0">
                        <a href="/login" class="text-decoration-none">
                            <i class="bi bi-arrow-left"></i> กลับไปหน้าเข้าสู่ระบบ
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
