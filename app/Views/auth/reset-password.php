<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <i class="bi bi-shield-lock text-primary display-4"></i>
                        <h3 class="mt-2">ตั้งรหัสผ่านใหม่</h3>
                        <p class="text-muted"><?= e($email) ?></p>
                    </div>
                    
                    <?php $errors = \App\Core\Session::getErrors(); ?>
                    
                    <form action="<?= url('/reset-password') ?>" method="POST">
                        <?= csrf_field() ?>
                        <input type="hidden" name="token" value="<?= e($token) ?>">
                        
                        <div class="mb-3">
                            <label class="form-label">รหัสผ่านใหม่</label>
                            <input type="password" name="password" class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" 
                                   required minlength="6">
                            <?php if (isset($errors['password'])): ?>
                                <div class="invalid-feedback"><?= e($errors['password'][0]) ?></div>
                            <?php endif; ?>
                            <small class="text-muted">อย่างน้อย 6 ตัวอักษร</small>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label">ยืนยันรหัสผ่านใหม่</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-check-lg me-2"></i>ตั้งรหัสผ่านใหม่
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
