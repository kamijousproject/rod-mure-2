<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - เกิดข้อผิดพลาด</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Noto+Sans+Thai:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root { --primary: #0f172a; --danger: #ef4444; --gray-50: #f8fafc; }
        body { font-family: 'Inter', 'Noto Sans Thai', sans-serif; background: var(--gray-50); }
        .error-code { font-size: 8rem; font-weight: 700; color: var(--danger); line-height: 1; letter-spacing: -0.05em; }
        .btn-primary { background: var(--primary); border: none; padding: 0.75rem 1.5rem; border-radius: 10px; }
        .btn-primary:hover { background: #1e293b; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row min-vh-100 align-items-center justify-content-center">
            <div class="col-md-6 text-center">
                <div class="error-code">500</div>
                <h2 class="fw-semibold mt-3 mb-2">เกิดข้อผิดพลาด</h2>
                <p class="text-muted mb-4">ขออภัย เกิดข้อผิดพลาดในระบบ กรุณาลองใหม่อีกครั้ง</p>
                <?php $basePath = '/used-car/public'; ?>
                <a href="<?= $basePath ?>/" class="btn btn-primary">
                    <i class="bi bi-arrow-left me-2"></i>กลับหน้าแรก
                </a>
            </div>
        </div>
    </div>
</body>
</html>
