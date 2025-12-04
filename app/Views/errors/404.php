<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - ไม่พบหน้าที่ต้องการ</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Noto+Sans+Thai:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root { --primary: #0f172a; --accent: #3b82f6; --gray-50: #f8fafc; --gray-400: #94a3b8; }
        body { font-family: 'Inter', 'Noto Sans Thai', sans-serif; background: var(--gray-50); }
        .error-code { font-size: 8rem; font-weight: 700; color: var(--primary); line-height: 1; letter-spacing: -0.05em; }
        .btn-primary { background: var(--primary); border: none; padding: 0.75rem 1.5rem; border-radius: 10px; }
        .btn-primary:hover { background: #1e293b; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row min-vh-100 align-items-center justify-content-center">
            <div class="col-md-6 text-center">
                <div class="error-code">404</div>
                <h2 class="fw-semibold mt-3 mb-2">ไม่พบหน้าที่ต้องการ</h2>
                <p class="text-muted mb-4">หน้าที่คุณกำลังมองหาอาจถูกย้าย ลบไปแล้ว หรือไม่เคยมีอยู่</p>
                <?php $basePath = '/used-car/public'; ?>
                <a href="<?= $basePath ?>/" class="btn btn-primary">
                    <i class="bi bi-house me-2"></i>กลับหน้าแรก
                </a>
                <a href="<?= $basePath ?>/cars" class="btn btn-outline-dark ms-2" style="border-radius: 10px; padding: 0.75rem 1.5rem;">
                    <i class="bi bi-search me-2"></i>ค้นหารถ
                </a>
            </div>
        </div>
    </div>
</body>
</html>
