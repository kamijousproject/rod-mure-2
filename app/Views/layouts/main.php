<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= e($description ?? 'ตลาดรถมือสองออนไลน์ ซื้อขายรถยนต์มือสอง ราคาดี มีคุณภาพ') ?>">
    <meta name="robots" content="index, follow">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="<?= e($canonical ?? base_url($_SERVER['REQUEST_URI'])) ?>">
    
    <title><?= e($title ?? 'ตลาดรถมือสอง') ?> | <?= e($config['app']['name']) ?></title>
    
    <!-- Google Fonts - Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Noto+Sans+Thai:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- GLightbox CSS -->
    <link href="https://cdn.jsdelivr.net/npm/glightbox@3.2.0/dist/css/glightbox.min.css" rel="stylesheet">
    
    <!-- Modern Minimal CSS -->
    <style>
        :root {
            --primary: #0f172a;
            --primary-light: #1e293b;
            --accent: #3b82f6;
            --accent-hover: #2563eb;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --gray-50: #f8fafc;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-400: #94a3b8;
            --gray-500: #64748b;
            --gray-600: #475569;
            --gray-900: #0f172a;
            --radius: 16px;
            --radius-sm: 10px;
            --shadow-sm: 0 1px 2px rgba(0,0,0,0.04);
            --shadow: 0 4px 20px rgba(0,0,0,0.06);
            --shadow-lg: 0 10px 40px rgba(0,0,0,0.1);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        * {
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', 'Noto Sans Thai', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--gray-50);
            color: var(--gray-900);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }
        
        /* Typography */
        h1, h2, h3, h4, h5, h6 {
            font-weight: 600;
            letter-spacing: -0.02em;
        }
        
        /* Navbar - Glass morphism */
        .navbar {
            background: rgba(255,255,255,0.8) !important;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--gray-200);
            padding: 1rem 0;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.4rem;
            color: var(--primary) !important;
            letter-spacing: -0.03em;
        }
        
        .navbar-brand i {
            color: var(--accent);
        }
        
        .nav-link {
            font-weight: 500;
            color: var(--gray-600) !important;
            padding: 0.5rem 1rem !important;
            border-radius: var(--radius-sm);
            transition: var(--transition);
        }
        
        .nav-link:hover {
            color: var(--primary) !important;
            background: var(--gray-100);
        }
        
        /* Buttons */
        .btn {
            font-weight: 500;
            border-radius: var(--radius-sm);
            padding: 0.625rem 1.25rem;
            transition: var(--transition);
            border: none;
        }
        
        .btn-primary {
            background: var(--primary);
            color: white;
        }
        
        .btn-primary:hover {
            background: var(--primary-light);
            transform: translateY(-1px);
            box-shadow: var(--shadow);
        }
        
        .btn-accent {
            background: var(--accent);
            color: white;
        }
        
        .btn-accent:hover {
            background: var(--accent-hover);
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 20px rgba(59, 130, 246, 0.4);
        }
        
        .btn-outline-primary {
            border: 1.5px solid var(--gray-300);
            color: var(--gray-700);
            background: transparent;
        }
        
        .btn-outline-primary:hover {
            border-color: var(--primary);
            background: var(--primary);
            color: white;
        }
        
        .btn-light {
            background: white;
            color: var(--primary);
            box-shadow: var(--shadow-sm);
        }
        
        .btn-light:hover {
            background: var(--gray-100);
            transform: translateY(-1px);
        }
        
        /* Cards - Modern minimal */
        .card {
            border: none;
            border-radius: var(--radius);
            background: white;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
        }
        
        .card:hover {
            box-shadow: var(--shadow);
        }
        
        .card-header {
            background: transparent;
            border-bottom: 1px solid var(--gray-100);
            padding: 1.25rem 1.5rem;
            font-weight: 600;
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        /* Car Card */
        .car-card {
            border-radius: var(--radius);
            overflow: hidden;
            background: white;
        }
        
        .car-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }
        
        .car-card .card-img-wrapper {
            position: relative;
            overflow: hidden;
            aspect-ratio: 4/3;
        }
        
        .car-card .card-img-top {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition);
        }
        
        .car-card:hover .card-img-top {
            transform: scale(1.05);
        }
        
        .car-card .badge-featured {
            position: absolute;
            top: 12px;
            left: 12px;
            background: var(--primary);
            color: white;
            font-weight: 500;
            padding: 0.4rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
        }
        
        .car-card .card-body {
            padding: 1.25rem;
        }
        
        .car-card .card-title {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            line-height: 1.4;
        }
        
        .car-card .card-title a {
            color: var(--primary);
            text-decoration: none;
        }
        
        .car-card .card-title a:hover {
            color: var(--accent);
        }
        
        .car-card .price {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--accent);
        }
        
        .car-card .specs {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            margin: 0.75rem 0;
        }
        
        .car-card .spec-badge {
            background: var(--gray-100);
            color: var(--gray-600);
            padding: 0.3rem 0.6rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        
        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, var(--primary) 0%, #1e3a5f 100%);
            color: white;
            padding: 5rem 0;
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 50%;
            height: 100%;
            background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 320'%3E%3Cpath fill='%23ffffff' fill-opacity='0.03' d='M0,160L48,176C96,192,192,224,288,213.3C384,203,480,149,576,149.3C672,149,768,203,864,208C960,213,1056,171,1152,154.7C1248,139,1344,149,1392,154.7L1440,160L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z'%3E%3C/path%3E%3C/svg%3E") no-repeat bottom;
            background-size: cover;
        }
        
        .hero-section h1 {
            font-size: 2.75rem;
            font-weight: 700;
            letter-spacing: -0.03em;
            line-height: 1.2;
        }
        
        .hero-section .lead {
            font-size: 1.125rem;
            opacity: 0.9;
            font-weight: 400;
        }
        
        .search-box {
            background: white;
            border-radius: var(--radius);
            padding: 2rem;
            box-shadow: var(--shadow-lg);
        }
        
        .search-box .form-control,
        .search-box .form-select {
            border: 1.5px solid var(--gray-200);
            border-radius: var(--radius-sm);
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: var(--transition);
        }
        
        .search-box .form-control:focus,
        .search-box .form-select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        /* Filter Sidebar */
        .filter-sidebar {
            background: white;
            border-radius: var(--radius);
            padding: 1.5rem;
            position: sticky;
            top: 100px;
        }
        
        .filter-sidebar .form-label {
            font-weight: 500;
            font-size: 0.875rem;
            color: var(--gray-600);
            margin-bottom: 0.5rem;
        }
        
        .filter-sidebar .form-control,
        .filter-sidebar .form-select {
            border: 1.5px solid var(--gray-200);
            border-radius: var(--radius-sm);
            font-size: 0.9rem;
        }
        
        /* Form Controls */
        .form-control, .form-select {
            border: 1.5px solid var(--gray-200);
            border-radius: var(--radius-sm);
            padding: 0.625rem 1rem;
            transition: var(--transition);
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        /* Badges */
        .badge {
            font-weight: 500;
            padding: 0.4em 0.8em;
            border-radius: 6px;
        }
        
        .badge.bg-primary { background: var(--primary) !important; }
        .badge.bg-success { background: var(--success) !important; }
        .badge.bg-warning { background: var(--warning) !important; color: var(--primary) !important; }
        .badge.bg-danger { background: var(--danger) !important; }
        .badge.bg-light { background: var(--gray-100) !important; color: var(--gray-600) !important; }
        
        /* Tables */
        .table {
            margin-bottom: 0;
        }
        
        .table th {
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--gray-500);
            border-bottom: 2px solid var(--gray-100);
            padding: 1rem;
        }
        
        .table td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid var(--gray-100);
        }
        
        .table-hover tbody tr:hover {
            background: var(--gray-50);
        }
        
        /* Pagination */
        .pagination {
            gap: 0.25rem;
        }
        
        .pagination .page-link {
            border: none;
            border-radius: var(--radius-sm);
            padding: 0.5rem 1rem;
            color: var(--gray-600);
            font-weight: 500;
            transition: var(--transition);
        }
        
        .pagination .page-link:hover {
            background: var(--gray-100);
            color: var(--primary);
        }
        
        .pagination .page-item.active .page-link {
            background: var(--primary);
            color: white;
        }
        
        /* Alerts */
        .alert {
            border: none;
            border-radius: var(--radius-sm);
            padding: 1rem 1.25rem;
            font-weight: 500;
        }
        
        .alert-success {
            background: #ecfdf5;
            color: #065f46;
        }
        
        .alert-danger {
            background: #fef2f2;
            color: #991b1b;
        }
        
        .alert-warning {
            background: #fffbeb;
            color: #92400e;
        }
        
        /* Dropdown */
        .dropdown-menu {
            border: none;
            border-radius: var(--radius-sm);
            box-shadow: var(--shadow-lg);
            padding: 0.5rem;
        }
        
        .dropdown-item {
            border-radius: 6px;
            padding: 0.6rem 1rem;
            font-weight: 500;
            transition: var(--transition);
        }
        
        .dropdown-item:hover {
            background: var(--gray-100);
        }
        
        /* Footer */
        footer {
            background: var(--primary);
            color: var(--gray-400);
            padding: 4rem 0 2rem;
        }
        
        footer h5, footer h6 {
            color: white;
            font-weight: 600;
            margin-bottom: 1.25rem;
        }
        
        footer a {
            color: var(--gray-400);
            text-decoration: none;
            transition: var(--transition);
        }
        
        footer a:hover {
            color: white;
        }
        
        footer ul {
            list-style: none;
            padding: 0;
        }
        
        footer ul li {
            margin-bottom: 0.75rem;
        }
        
        /* Breadcrumb */
        .breadcrumb {
            background: transparent;
            padding: 0;
            margin: 0;
            font-size: 0.875rem;
        }
        
        .breadcrumb-item a {
            color: var(--gray-500);
            text-decoration: none;
        }
        
        .breadcrumb-item a:hover {
            color: var(--accent);
        }
        
        .breadcrumb-item.active {
            color: var(--gray-400);
        }
        
        /* Gallery */
        .gallery-thumb {
            cursor: pointer;
            opacity: 0.6;
            transition: var(--transition);
            border-radius: var(--radius-sm);
            overflow: hidden;
        }
        
        .gallery-thumb:hover,
        .gallery-thumb.active {
            opacity: 1;
        }
        
        /* Seller Card */
        .seller-card {
            background: linear-gradient(135deg, var(--gray-50), var(--gray-100));
            border-radius: var(--radius);
        }
        
        /* Empty State */
        .empty-state {
            padding: 4rem 2rem;
            text-align: center;
        }
        
        .empty-state i {
            font-size: 4rem;
            color: var(--gray-300);
            margin-bottom: 1rem;
        }
        
        /* Stats Card */
        .stat-card {
            background: white;
            border-radius: var(--radius);
            padding: 1.5rem;
            text-align: center;
        }
        
        .stat-card .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary);
        }
        
        .stat-card .stat-label {
            color: var(--gray-500);
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        
        /* List Group */
        .list-group-item {
            border: none;
            border-bottom: 1px solid var(--gray-100);
            padding: 1rem 1.25rem;
        }
        
        .list-group-item:last-child {
            border-bottom: none;
        }
        
        .list-group-item-action:hover {
            background: var(--gray-50);
        }
        
        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: var(--gray-100);
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--gray-300);
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: var(--gray-400);
        }
        
        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .fade-in {
            animation: fadeIn 0.5s ease forwards;
        }
        
        /* Chat Messages */
        .new-message {
            animation: slideIn 0.3s ease;
        }
        
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        #messages-container {
            scroll-behavior: smooth;
        }
        
        /* Mobile Responsive */
        @media (max-width: 768px) {
            .hero-section {
                padding: 3rem 0;
            }
            
            .hero-section h1 {
                font-size: 2rem;
            }
            
            .search-box {
                padding: 1.5rem;
            }
            
            .car-card .card-img-wrapper {
                aspect-ratio: 16/10;
            }
            
            .filter-sidebar {
                position: static;
                margin-bottom: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand text-primary" href="<?= url('/') ?>">
                <i class="bi bi-car-front-fill me-2"></i>
                <?= e($config['app']['name']) ?>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= url('/') ?>">หน้าแรก</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= url('/cars') ?>">ค้นหารถ</a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <?php if (isset($auth) && $auth): ?>
                        <?php if ($auth['role'] === 'admin'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= url('/admin') ?>">
                                    <i class="bi bi-gear"></i> Admin
                                </a>
                            </li>
                        <?php endif; ?>
                        
                        <?php if (in_array($auth['role'], ['seller', 'admin'])): ?>
                            <li class="nav-item">
                                <a class="nav-link btn btn-outline-primary btn-sm me-2" href="<?= url('/my-cars/create') ?>">
                                    <i class="bi bi-plus-lg"></i> ลงประกาศ
                                </a>
                            </li>
                        <?php endif; ?>
                        
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> <?= e($auth['name']) ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="<?= url('/dashboard') ?>"><i class="bi bi-speedometer2 me-2"></i>แดชบอร์ด</a></li>
                                <li><a class="dropdown-item" href="<?= url('/my-cars') ?>"><i class="bi bi-car-front me-2"></i>รถของฉัน</a></li>
                                <li><a class="dropdown-item" href="<?= url('/inquiries') ?>"><i class="bi bi-chat-dots me-2"></i>ข้อความ</a></li>
                                <li><a class="dropdown-item" href="<?= url('/profile') ?>"><i class="bi bi-person me-2"></i>โปรไฟล์</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="<?= url('/logout') ?>" method="POST" class="d-inline">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right me-2"></i>ออกจากระบบ
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= url('/login') ?>">เข้าสู่ระบบ</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-primary btn-sm text-white" href="<?= url('/register') ?>">สมัครสมาชิก</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Flash Messages -->
    <?php if (!empty($flash)): ?>
        <div class="container mt-3">
            <?php foreach ($flash as $type => $message): ?>
                <div class="alert alert-<?= $type === 'error' ? 'danger' : $type ?> alert-dismissible fade show">
                    <?= e($message) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <!-- Main Content -->
    <main>
        <?= $content ?>
    </main>
    
    <!-- Footer -->
    <footer class="py-5 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5 class="text-white mb-3">
                        <i class="bi bi-car-front-fill me-2"></i>
                        <?= e($config['app']['name']) ?>
                    </h5>
                    <p>แหล่งซื้อขายรถมือสองที่ใหญ่ที่สุด รวมประกาศรถยนต์มือสองคุณภาพดี ราคาถูก จากผู้ขายทั่วประเทศ</p>
                </div>
                <div class="col-md-2 mb-4">
                    <h6 class="text-white mb-3">ลิงก์ด่วน</h6>
                    <ul class="list-unstyled">
                        <li><a href="<?= url('/cars') ?>">ค้นหารถ</a></li>
                        <li><a href="<?= url('/register?role=seller') ?>">ลงขายรถ</a></li>
                        <li><a href="<?= url('/login') ?>">เข้าสู่ระบบ</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-4">
                    <h6 class="text-white mb-3">ยี่ห้อยอดนิยม</h6>
                    <ul class="list-unstyled">
                        <li><a href="<?= url('/cars?brand_id=1') ?>">Toyota</a></li>
                        <li><a href="<?= url('/cars?brand_id=2') ?>">Honda</a></li>
                        <li><a href="<?= url('/cars?brand_id=3') ?>">Mazda</a></li>
                        <li><a href="<?= url('/cars?brand_id=4') ?>">Isuzu</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-4">
                    <h6 class="text-white mb-3">ติดต่อเรา</h6>
                    <ul class="list-unstyled">
                        <li><i class="bi bi-envelope me-2"></i> contact@usedcar.test</li>
                        <li><i class="bi bi-telephone me-2"></i> 02-xxx-xxxx</li>
                        <li><i class="bi bi-geo-alt me-2"></i> กรุงเทพมหานคร</li>
                    </ul>
                </div>
            </div>
            <hr class="my-4 border-secondary">
            <div class="text-center">
                <small>&copy; <?= date('Y') ?> <?= e($config['app']['name']) ?>. All rights reserved.</small>
            </div>
        </div>
    </footer>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- GLightbox JS -->
    <script src="https://cdn.jsdelivr.net/npm/glightbox@3.2.0/dist/js/glightbox.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        // Initialize lightbox
        const lightbox = GLightbox({
            selector: '.glightbox'
        });
        
        // CSRF token for AJAX
        const csrfToken = '<?= e($csrf_token) ?>';
        
        // Helper function for fetch with CSRF
        async function fetchWithCsrf(url, options = {}) {
            const headers = {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                ...(options.headers || {})
            };
            
            return fetch(url, {...options, headers});
        }
    </script>
    
    <?php if (isset($scripts)): ?>
        <?= $scripts ?>
    <?php endif; ?>
</body>
</html>
