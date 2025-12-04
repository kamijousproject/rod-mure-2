<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">รายงาน</h2>
        <div class="dropdown">
            <button class="btn btn-success dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="bi bi-download me-2"></i>Export CSV
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="<?= url('/admin/reports/export?type=cars') ?>">ข้อมูลรถทั้งหมด</a></li>
                <li><a class="dropdown-item" href="<?= url('/admin/reports/export?type=users') ?>">ข้อมูลผู้ใช้</a></li>
                <li><a class="dropdown-item" href="<?= url('/admin/reports/export?type=inquiries') ?>">ข้อมูลการสอบถาม</a></li>
            </ul>
        </div>
    </div>
    
    <!-- Period Filter -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="<?= url('/admin/reports') ?>" method="GET" class="row g-3 align-items-center">
                <div class="col-auto">
                    <label class="form-label mb-0">ช่วงเวลา:</label>
                </div>
                <div class="col-auto">
                    <select name="period" class="form-select" onchange="this.form.submit()">
                        <option value="7" <?= $period == 7 ? 'selected' : '' ?>>7 วันล่าสุด</option>
                        <option value="30" <?= $period == 30 ? 'selected' : '' ?>>30 วันล่าสุด</option>
                        <option value="90" <?= $period == 90 ? 'selected' : '' ?>>90 วันล่าสุด</option>
                        <option value="365" <?= $period == 365 ? 'selected' : '' ?>>1 ปีล่าสุด</option>
                    </select>
                </div>
            </form>
        </div>
    </div>
    
    <div class="row">
        <!-- Cars by Status -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0"><i class="bi bi-car-front me-2"></i>สถานะประกาศรถ</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>สถานะ</th>
                                    <th class="text-end">จำนวน</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $statusLabels = [
                                    'draft' => ['ฉบับร่าง', 'secondary'],
                                    'pending' => ['รอตรวจสอบ', 'warning'],
                                    'published' => ['เผยแพร่', 'success'],
                                    'sold' => ['ขายแล้ว', 'info'],
                                    'rejected' => ['ถูกปฏิเสธ', 'danger'],
                                ];
                                foreach ($carsByStatus as $stat): 
                                    $label = $statusLabels[$stat['status']] ?? [$stat['status'], 'secondary'];
                                ?>
                                <tr>
                                    <td>
                                        <span class="badge bg-<?= $label[1] ?>"><?= $label[0] ?></span>
                                    </td>
                                    <td class="text-end"><strong><?= number_format($stat['count']) ?></strong></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Users by Role -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0"><i class="bi bi-people me-2"></i>ผู้ใช้ตามประเภท</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>ประเภท</th>
                                    <th class="text-end">จำนวน</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $roleLabels = [
                                    'buyer' => ['ผู้ซื้อ', 'secondary'],
                                    'seller' => ['ผู้ขาย', 'primary'],
                                    'admin' => ['ผู้ดูแลระบบ', 'danger'],
                                ];
                                foreach ($usersByRole as $stat): 
                                    $label = $roleLabels[$stat['role']] ?? [$stat['role'], 'secondary'];
                                ?>
                                <tr>
                                    <td>
                                        <span class="badge bg-<?= $label[1] ?>"><?= $label[0] ?></span>
                                    </td>
                                    <td class="text-end"><strong><?= number_format($stat['count']) ?></strong></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between">
                        <span>ข้อความสอบถามทั้งหมด</span>
                        <strong><?= number_format($inquiryStats['total'] ?? 0) ?></strong>
                    </div>
                    <div class="d-flex justify-content-between text-muted">
                        <span>ใน <?= $period ?> วันล่าสุด</span>
                        <strong><?= number_format($inquiryStats['recent'] ?? 0) ?></strong>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Top Brands -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0"><i class="bi bi-bar-chart me-2"></i>ยี่ห้อยอดนิยม</h5>
                </div>
                <div class="card-body">
                    <?php foreach ($topBrands as $index => $brand): ?>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>
                            <span class="badge bg-secondary me-2"><?= $index + 1 ?></span>
                            <?= e($brand['name']) ?>
                        </span>
                        <span class="badge bg-primary"><?= number_format($brand['count']) ?> รายการ</span>
                    </div>
                    <?php endforeach; ?>
                    
                    <?php if (empty($topBrands)): ?>
                    <p class="text-muted text-center mb-0">ยังไม่มีข้อมูล</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Cars Per Day -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0"><i class="bi bi-graph-up me-2"></i>ประกาศใหม่ต่อวัน</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($carsPerDay)): ?>
                    <p class="text-muted text-center mb-0">ยังไม่มีข้อมูลในช่วงเวลานี้</p>
                    <?php else: ?>
                    <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>วันที่</th>
                                    <th class="text-end">จำนวน</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (array_reverse($carsPerDay) as $day): ?>
                                <tr>
                                    <td><?= date('d M Y', strtotime($day['date'])) ?></td>
                                    <td class="text-end"><?= number_format($day['count']) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
