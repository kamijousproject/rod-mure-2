<div class="container py-4">
    <h2 class="mb-4">ข้อความสอบถาม</h2>
    
    <!-- Status Filters -->
    <div class="card shadow-sm mb-4">
        <div class="card-body py-2">
            <div class="d-flex gap-2 flex-wrap">
                <a href="/inquiries" class="btn btn-sm <?= empty($currentStatus) ? 'btn-primary' : 'btn-outline-secondary' ?>">
                    ทั้งหมด
                </a>
                <a href="/inquiries?status=unread" class="btn btn-sm <?= $currentStatus === 'unread' ? 'btn-primary' : 'btn-outline-secondary' ?>">
                    ยังไม่อ่าน
                </a>
                <a href="/inquiries?status=read" class="btn btn-sm <?= $currentStatus === 'read' ? 'btn-primary' : 'btn-outline-secondary' ?>">
                    อ่านแล้ว
                </a>
                <a href="/inquiries?status=replied" class="btn btn-sm <?= $currentStatus === 'replied' ? 'btn-primary' : 'btn-outline-secondary' ?>">
                    ตอบแล้ว
                </a>
            </div>
        </div>
    </div>
    
    <?php if (empty($inquiries)): ?>
        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-chat-dots display-1 text-muted"></i>
                <h4 class="mt-3">ยังไม่มีข้อความ</h4>
                <p class="text-muted">ข้อความสอบถามจากผู้สนใจจะแสดงที่นี่</p>
            </div>
        </div>
    <?php else: ?>
        <div class="card shadow-sm">
            <div class="list-group list-group-flush">
                <?php foreach ($inquiries as $inquiry): ?>
                    <a href="/inquiries/<?= $inquiry['id'] ?>" 
                       class="list-group-item list-group-item-action <?= $inquiry['status'] === 'unread' ? 'bg-light' : '' ?>">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <?php if ($inquiry['car_image']): ?>
                                    <img src="<?= upload_url('cars/' . $inquiry['car_image']) ?>" 
                                         class="rounded" style="width: 60px; height: 45px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="bg-secondary rounded d-flex align-items-center justify-content-center" 
                                         style="width: 60px; height: 45px;">
                                        <i class="bi bi-car-front text-white"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong><?= e($inquiry['name']) ?></strong>
                                        <?php if ($inquiry['status'] === 'unread'): ?>
                                            <span class="badge bg-primary ms-2">ใหม่</span>
                                        <?php endif; ?>
                                        <br>
                                        <small class="text-muted"><?= e($inquiry['car_title']) ?></small>
                                    </div>
                                    <small class="text-muted"><?= format_date($inquiry['created_at'], 'd M H:i') ?></small>
                                </div>
                                <p class="mb-0 mt-1 text-muted small"><?= e(str_limit($inquiry['message'], 80)) ?></p>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        
        <!-- Pagination -->
        <?php if ($pagination['total_pages'] > 1): ?>
            <nav class="mt-4">
                <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                        <li class="page-item <?= $i == $pagination['page'] ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?><?= $currentStatus ? '&status=' . $currentStatus : '' ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php endif; ?>
    <?php endif; ?>
</div>
